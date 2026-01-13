<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index(Request $request): View
    {
        $addresses = $request->user()
            ->addresses()
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('account.addresses.index', compact('addresses'));
    }

    public function create(): View
    {
        return view('account.addresses.create');
    }

    public function store(StoreAddressRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $isDefault = (bool) ($data['is_default'] ?? false);

        if ($isDefault) {
            $user->addresses()->update(['is_default' => 0]);
        }

        $user->addresses()->create([
            ...$data,
            'is_default' => $isDefault ? 1 : 0,
        ]);

        return redirect()
            ->route('account.addresses.index')
            ->with('success', 'Dirección creada correctamente.');
    }

    public function edit(Address $address): View
    {
        abort_unless($address->user_id === Auth::id(), 403);

        return view('account.addresses.edit', compact('address'));
    }

    public function update(UpdateAddressRequest $request, Address $address): RedirectResponse
    {
        abort_unless($address->user_id === Auth::id(), 403);

        $user = $request->user();
        $data = $request->validated();
        $isDefault = (bool) ($data['is_default'] ?? false);

        if ($isDefault) {
            $user->addresses()->whereKeyNot($address->id)->update(['is_default' => 0]);
        }

        $address->update([
            ...$data,
            'is_default' => $isDefault ? 1 : 0,
        ]);

        return redirect()
            ->route('account.addresses.index')
            ->with('success', 'Dirección actualizada.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        abort_unless($address->user_id === Auth::id(), 403);

        $address->delete();

        return redirect()
            ->route('account.addresses.index')
            ->with('success', 'Dirección eliminada.');
    }
}
