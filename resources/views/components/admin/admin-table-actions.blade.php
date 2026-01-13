@props([])

@php
// Fallbacks razonables
$safeName = $itemName ?: 'este elemento';
$modalTitle = $deleteTitle ?: 'Eliminar';
$modalMessage = $deleteMessage ?: '¿Seguro que quieres eliminar <code>' . $safeName . '</code>?';
@endphp

<div class="inline-flex items-center gap-2">
    {{-- EDIT --}}
    @if($showEdit && $editUrl)
    <x-admin.admin-button
        type="edit"
        :href="$editUrl"
        title="Editar" />
    @endif

    {{-- DELETE --}}
    @if($showDelete && $deleteUrl)
    <div
        x-data="{
                open: false,
                focusables() {
                    return this.$el.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex=&quot;-1&quot;])')
                },
                onKeydown(e) {
                    if (!this.open) return;

                    if (e.key === 'Escape') {
                        this.open = false;
                        return;
                    }

                    if (e.key !== 'Tab') return;

                    const list = Array.from(this.focusables()).filter(el => !el.hasAttribute('disabled') && el.offsetParent !== null);
                    if (!list.length) return;

                    const first = list[0];
                    const last = list[list.length - 1];

                    if (e.shiftKey && document.activeElement === first) {
                        e.preventDefault();
                        last.focus();
                    } else if (!e.shiftKey && document.activeElement === last) {
                        e.preventDefault();
                        first.focus();
                    }
                }
            }"
        x-on:keydown="onKeydown($event)"
        class="inline-block">
        <x-admin.admin-button
            type="delete"
            title="Eliminar"
            :disabled="$disabledDelete"
            x-on:click.prevent="open = true; $nextTick(() => $refs.cancel?.focus())" />

        {{-- MODAL --}}
        <div
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true">
            {{-- Overlay --}}
            <div
                class="fixed inset-0 bg-black/50"
                x-on:click="open = false"></div>

            {{-- Panel --}}
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div
                    class="w-full max-w-lg rounded-lg bg-white shadow-xl"
                    x-on:click.stop>
                    <div class="p-6">
                        <h2 id="modal-title" class="text-lg font-semibold text-gray-900">
                            {{ $modalTitle }}
                        </h2>

                        <p class="mt-2 text-sm text-gray-600">
                            {!! $modalMessage !!}
                        </p>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <button
                                type="button"
                                class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
                                x-ref="cancel"
                                x-on:click="open = false">
                                {{ $cancelText }}
                            </button>

                            <form action="{{ $deleteUrl }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-md border border-red-600 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    {{ $confirmText }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- /MODAL --}}
    </div>
    @endif
</div>