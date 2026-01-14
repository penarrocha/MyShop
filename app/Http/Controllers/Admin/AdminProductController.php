<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Offer;
use App\Support\HasUniqueSlug;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    use HasUniqueSlug;

    /**
     * Muestra la lista de productos en el panel de administración
     */
    public function index(): View
    {
        $products = Product::query()
            ->with(['category', 'offer'])
            ->latest()
            ->paginate(10);

        $categories = Category::orderBy('name')->get();
        $offers = Offer::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'offers'));
    }


    /**
     * Muestra el formulario para editar un producto existente
     */
    public function edit(Product $product): View
    {
        $categories = Category::all();
        $offers = Offer::all();

        return view('admin.products.edit', compact('product', 'categories', 'offers'));
    }


    /**
     * Muestra el formulario para crear un nuevo producto.
     */
    public function create(): View
    {
        $categories = Category::all();
        $offers = Offer::all();

        return view('admin.products.create', compact('categories', 'offers'));
    }

    /**
     * Almacena un nuevo producto en la base de datos
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:products,name',
            'slug'        => 'nullable|string|max:255|unique:products,slug',
            'description' => 'required|string|max:1000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'price'       => 'required|numeric|min:0|max:999999.99',
            'category_id' => 'required|exists:categories,id',
            'offer_id'    => 'nullable|exists:offers,id',
        ], [
            'name.required'        => 'El nombre del producto es obligatorio.',
            'name.unique'          => 'Ya existe un producto con ese nombre.',
            'description.required' => 'La descripción es obligatoria.',
            'image.image'          => 'El archivo debe ser una imagen.',
            'image.mimes'          => 'La imagen debe ser de tipo: jpeg, png, jpg, webp.',
            'image.max'            => 'La imagen no debe superar los 2MB.',
            'price.required'       => 'El precio es obligatorio.',
            'price.numeric'        => 'El precio debe ser un número.',
            'category_id.required' => 'Debes seleccionar una categoría.',
            'category_id.exists'   => 'La categoría seleccionada no es válida.',
            'offer_id.exists'      => 'La oferta seleccionada no es válida.',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['slug'] = $this->uniqueSlugForModel(Product::class, $validated['slug'], null, 'producto');

        DB::transaction(function () use ($request, &$validated) {

            // Si hay imagen: subir a Cloudinary y guardar en BD el public_id en 'image'
            if ($request->hasFile('image')) {
                $path = $request->file('image')->getRealPath();
                $slug = Str::slug($validated['name']); // "nombre-del-producto"

                // Queremos: productos/nombre-del-producto_<suffix_cloudinary>
                $result = cloudinary()->uploadApi()->upload($path, [
                    'folder'            => 'productos',
                    'use_filename'      => true,
                    'filename_override' => $slug,
                    'unique_filename'   => true,  // Cloudinary añade sufijo
                    'resource_type'     => 'image',
                ]);

                $validated['image'] = $result['public_id'];
            }

            Product::create($validated);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', '¡Producto creado exitosamente!');
    }

    /**
     * Actualiza un producto existente en la base de datos.
     */

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255|unique:products,name,' . $product->id,
            'slug'         => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description'  => 'required|string|max:1000',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'price'        => 'required|numeric|min:0|max:999999.99',
            'category_id'  => 'required|exists:categories,id',
            'offer_id'     => 'nullable|exists:offers,id',
            'remove_image' => 'nullable|boolean', // viene de un <input type="hidden" name="remove_image" value="0|1">
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        $validated['slug'] = $this->uniqueSlugForModel(Product::class, $validated['slug'], $product->id, 'producto');


        DB::transaction(function () use ($request, $product, &$validated) {

            // Valor REAL de BD (no accessor)
            $storedPublicId = $product->getRawOriginal('image');

            // Si por histórico se guardó algo tipo /tmp/... lo ignoramos
            if ($storedPublicId && str_contains($storedPublicId, '/tmp/')) {
                $storedPublicId = null;
            }

            // Si suben archivo nuevo, ignoramos remove_image (aunque venga)
            $removeImage = !$request->hasFile('image') && $request->boolean('remove_image');

            // Desvincular imagen
            if ($removeImage) {
                if (!empty($storedPublicId)) {
                    cloudinary()->uploadApi()->destroy($storedPublicId, [
                        'invalidate'    => true,
                        'resource_type' => 'image',
                    ]);
                }

                // Guardar NULL en BD
                $validated['image'] = null;
            }

            // Subir nueva imagen
            if ($request->hasFile('image')) {
                $path = $request->file('image')->getRealPath();

                if (!empty($storedPublicId)) {
                    // Overwrite sobre el public_id existente
                    $publicId = ltrim($storedPublicId, '/');

                    $result = cloudinary()->uploadApi()->upload($path, [
                        'public_id'     => $publicId,
                        'overwrite'     => true,
                        'invalidate'    => true,
                        'resource_type' => 'image',
                    ]);

                    $validated['image'] = $result['public_id']; // debería ser el mismo
                } else {
                    // Primera vez: productos/<slug>_<suffix>
                    $slug = Str::slug($validated['name']);

                    $result = cloudinary()->uploadApi()->upload($path, [
                        //'folder'            => 'productos',
                        'use_filename'      => true,
                        //'filename_override' => $slug,
                        'unique_filename'   => true,
                        'resource_type'     => 'image',
                    ]);

                    $validated['image'] = $result['public_id'];
                }
            }

            // Si no hay archivo y no se ha pedido desvincular, NO tocar image
            if (!$request->hasFile('image') && !$removeImage) {
                unset($validated['image']);
            }

            // remove_image no es columna, fuera:
            unset($validated['remove_image']);

            $product->update($validated);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', '¡Producto actualizado exitosamente!');
    }


    /**
     * Elimina un producto de la base de datos.
     */
    public function destroy(Product $product): RedirectResponse
    {
        DB::transaction(function () use ($product) {

            // product->image = public_id en Cloudinary
            if (!empty($product->image) && !str_contains($product->image, '/tmp/')) {
                cloudinary()->uploadApi()->destroy($product->image, [
                    'invalidate' => true,
                    'resource_type' => 'image',
                ]);
            }

            $product->delete();
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}
