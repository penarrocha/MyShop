<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mi cuenta</h2>

            <a href="{{ route('account.addresses.create') }}"
                class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                Nueva dirección
            </a>
        </div>
    </x-slot>

    <x-account-layout>
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Direcciones</h3>
                <p class="text-sm text-gray-600">Gestiona tus direcciones de envío y facturación.</p>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            @if($addresses->isEmpty())
            <div class="rounded-lg border border-gray-200 p-6 text-gray-600">
                No tienes direcciones guardadas todavía.
            </div>
            @else
            @foreach($addresses as $address)
            <div class="rounded-lg border border-gray-200 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <div class="font-semibold text-gray-900 truncate">
                                {{ $address->label ?: 'Dirección' }}
                            </div>

                            @if($address->is_default)
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700">
                                Predeterminada
                            </span>
                            @endif
                        </div>

                        <div class="mt-2 text-sm text-gray-700 space-y-1">
                            <div>
                                <span class="font-medium">{{ $address->recipient_name }}</span>
                                @if($address->phone) · {{ $address->phone }} @endif
                            </div>
                            <div>{{ $address->line1 }}</div>
                            @if($address->line2)<div>{{ $address->line2 }}</div>@endif
                            <div>
                                {{ $address->postcode }} {{ $address->city }}
                                @if($address->province) ({{ $address->province }}) @endif
                            </div>
                            <div class="text-xs text-gray-500">País: {{ $address->country }}</div>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <a href="{{ route('account.addresses.edit', $address) }}"
                            class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Editar
                        </a>

                        <form method="POST" action="{{ route('account.addresses.destroy', $address) }}"
                            onsubmit="return confirm('¿Eliminar esta dirección?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="inline-flex items-center rounded-md border border-red-300 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </x-account-layout>
</x-app-layout>