<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Support\HasUniqueSlug;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;

class AdminOfferController extends Controller
{
    use HasUniqueSlug;

    protected function slugFallback(): string
    {
        return 'oferta';
    }

    public function index(Request $request): View
    {
        $query = Offer::query();

        // Búsqueda simple por nombre o slug
        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%");
            });
        }

        // Filtro por estado activo (1/0)
        if ($request->filled('active')) {
            $active = $request->input('active');
            if ($active === '1' || $active === '0') {
                $query->where('active', (int) $active);
            }
        }

        // (Opcional) papelera
        if ($request->input('trashed') === '1') {
            $query->onlyTrashed();
        }

        $offers = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.offers.index', compact('offers'));
    }

    public function create(): View
    {
        return view('admin.offers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        // checkbox
        $data['active'] = $request->boolean('active');

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['slug'] = $this->uniqueSlugForModel(
            Offer::class,
            $data['slug'],
            null,
            $this->slugFallback()
        );

        $offer = Offer::create($data);

        return redirect()
            ->route('admin.offers.edit', $offer)
            ->with('success', 'Oferta creada correctamente.');
    }

    public function edit(Offer $offer): View
    {
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, Offer $offer): RedirectResponse
    {
        $data = $this->validated($request, $offer->id);

        $data['active'] = $request->boolean('active');

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['slug'] = $this->uniqueSlugForModel(
            Offer::class,
            $data['slug'],
            $offer->id,
            $this->slugFallback()
        );

        $offer->update($data);

        return redirect()
            ->route('admin.offers.edit', $offer)
            ->with('success', 'Oferta actualizada correctamente.');
    }

    public function destroy(Offer $offer): RedirectResponse
    {
        $offer->delete();

        return redirect()
            ->route('admin.offers.index')
            ->with('success', 'Oferta enviada a la papelera.');
    }

    private function validated(Request $request, ?int $ignoreOfferId = null): array
    {
        $uniqueSlugRule = 'unique:offers,slug';
        if ($ignoreOfferId) {
            $uniqueSlugRule .= ',' . $ignoreOfferId;
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', $uniqueSlugRule],

            'discount_percentage' => ['required', 'numeric', 'min:0', 'max:99.99'],
            'description' => ['nullable', 'string'],

            'active' => ['nullable'],

            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);
    }
}
