<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\HasUniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class AdminCategoryController extends Controller
{
    use HasUniqueSlug;

    protected function slugFallback(): string
    {
        return 'categoria';
    }

    public function index(): View
    {
        $categories = Category::query()
            ->orderBy('name')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['slug'] = $this->uniqueSlugForModel(
            Category::class,
            $data['slug'],
            null,
            $this->slugFallback()
        );

        DB::transaction(function () use ($request, &$data) {

            // Si hay imagen: subir a Cloudinary y guardar en BD el public_id en 'image'
            if ($request->hasFile('image')) {
                $path = $request->file('image')->getRealPath();
                $slug = Str::slug($data['name']); // "nombre-de-la-categoria"

                // Queremos: categorias/nombre-de-la-categoria_<suffix_cloudinary>
                $result = cloudinary()->uploadApi()->upload($path, [
                    'folder'            => 'categorias',
                    'use_filename'      => true,
                    'filename_override' => $slug,
                    'unique_filename'   => true,
                    'resource_type'     => 'image',
                ]);

                $data['image'] = $result['public_id'];
            }

            Category::create($data);
        });

        return redirect()
            ->route('admin.categories.index')
            ->with('success', '¡Categoría creada correctamente!');
    }


    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validated($request, $category->id);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['slug'] = $this->uniqueSlugForModel(
            Category::class,
            $data['slug'],
            $category->id,
            $this->slugFallback()
        );

        DB::transaction(function () use ($request, $category, &$data) {

            // Valor REAL de BD (no accessor)
            $storedPublicId = $category->getRawOriginal('image');

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
                $data['image'] = null;
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

                    $data['image'] = $result['public_id']; // debería ser el mismo
                } else {
                    // Primera vez: categorias/<slug>_<suffix>
                    $slug = Str::slug($data['name']);

                    $result = cloudinary()->uploadApi()->upload($path, [
                        'folder'            => 'categorias',
                        'use_filename'      => true,
                        'filename_override' => $slug,
                        'unique_filename'   => true,
                        'resource_type'     => 'image',
                    ]);

                    $data['image'] = $result['public_id'];
                }
            }

            // Si no hay archivo y no se ha pedido desvincular, NO tocar image
            if (!$request->hasFile('image') && !$removeImage) {
                unset($data['image']);
            }

            // remove_image no es columna, fuera (si la estás usando en el form)
            unset($data['remove_image']);

            $category->update($data);
        });

        return redirect()
            ->route('admin.categories.index')
            ->with('success', '¡Categoría actualizada correctamente!');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'No puedes eliminar una categoría que tiene productos.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $uniqueSlugRule = 'unique:categories,slug';
        if ($ignoreId) {
            $uniqueSlugRule .= ',' . $ignoreId;
        }

        return $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', $uniqueSlugRule],
            'image'       => ['nullable|image|mimes:jpeg,png,jpg,webp|max:2048'],
            'description' => ['required', 'string'],
        ]);
    }
}
