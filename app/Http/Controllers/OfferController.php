<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Contracts\View\View;

class OfferController extends Controller
{
    /**
     * Show all offers
     */
    public function index(): View
    {
        $offers = Offer::all();
        return view('offers.index', ['offers' => $offers]);
    }

    /**
     * Show products with a specific offer
     */
    public function show(Offer $offer): View
    {

        $offerProducts = $offer->products()->with(['category'])->get();

        return view('offers.show', [
            'offer' => $offer,
            'offerProducts' => $offerProducts,
            'breadcrumb' => [
                'name' => 'offers.show',
                'params' => [$offer]
            ]
        ]);
    }
}
