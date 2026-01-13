<div
    data-image-picker
    data-image-picker-id="{{ $id }}"
    data-has-real-image="{{ $hasRealImage ? '1' : '0' }}"
    data-unlinked="0"
    class="space-y-3">
    <div class="relative inline-block">
        <img
            data-image-picker-preview
            src="{{ $initialUrl }}"
            alt="{{ $alt }}"
            class="{{ $class }}"
            data-original-src="{{ $initialUrl }}"
            data-default-src="{{ $defaultUrl }}" />

        <input data-image-picker-remove-flag type="hidden" name="{{ $removeName }}" value="0">

        <button
            type="button"
            data-image-picker-trash
            title="Quitar imagen"
            class="hidden absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2
                   inline-flex items-center justify-center
                   rounded-full border border-red-500 bg-white/90
                   px-4 py-3 text-red-600 shadow-sm
                   hover:bg-red-50 hover:text-red-700
                   focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 text-red-600 stroke-current"
                viewBox="0 0 24 24"
                fill="none"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M3 6h18" />
                <path d="M8 6V4h8v2" />
                <path d="M19 6l-1 14H6L5 6" />
                <path d="M10 11v6" />
                <path d="M14 11v6" />
            </svg>
        </button>
    </div>

    <input
        data-image-picker-input
        type="file"
        name="{{ $name }}"
        accept="{{ $accept }}"
        class="block w-full text-sm
               file:mr-4 file:rounded-md file:border-0
               file:bg-gray-100 file:px-4 file:py-2
               file:text-sm file:font-semibold
               file:text-gray-700 hover:file:bg-gray-200
               focus:outline-none" />
</div>
{{--
@once
@push('scripts')
@vite('resources/js/image-picker.js')
@endpush
@endonce
--}}