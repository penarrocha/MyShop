@php
$styles = [
'edit' => [
'border' => 'border-indigo-600',
'text' => 'text-indigo-600',
'hover' => 'hover:bg-indigo-600 hover:text-white',
'ring' => 'focus:ring-indigo-500',
],
'delete' => [
'border' => 'border-red-600',
'text' => 'text-red-600',
'hover' => 'hover:bg-red-600 hover:text-white',
'ring' => 'focus:ring-red-500',
],
][$type] ?? [
'border' => 'border-gray-300',
'text' => 'text-gray-700',
'hover' => 'hover:bg-gray-100',
'ring' => 'focus:ring-gray-400',
];

$baseClasses = "
inline-flex items-center justify-center
border {$styles['border']} {$styles['text']}
{$styles['hover']}
focus:outline-none focus:ring-2 {$styles['ring']} focus:ring-offset-2
rounded-md p-2 transition-colors duration-150
";

$isLink = $attributes->has('href');
@endphp

@if ($isLink)
<a
    {{ $attributes->merge(['class' => $baseClasses]) }}
    title="{{ $title }}"
    aria-label="{{ $title }}">
    @if ($type === 'edit')
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.125 19.588
                       a4.5 4.5 0 01-1.897 1.13l-3.356 1.118
                       1.118-3.356a4.5 4.5 0 011.13-1.897L16.862 3.487z" />
    </svg>
    @elseif ($type === 'delete')
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2
                       m-7 0h8m-9 3v7a2 2 0 002 2h4
                       a2 2 0 002-2v-7" />
    </svg>
    @endif
</a>
@else
<button
    type="{{ $attributes->get('type', 'button') }}"
    {{ $attributes->merge(['class' => $baseClasses]) }}
    title="{{ $title }}"
    aria-label="{{ $title }}">
    @if ($type === 'edit')
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.125 19.588
                       a4.5 4.5 0 01-1.897 1.13l-3.356 1.118
                       1.118-3.356a4.5 4.5 0 011.13-1.897L16.862 3.487z" />
    </svg>
    @elseif ($type === 'delete')
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2
                       m-7 0h8m-9 3v7a2 2 0 002 2h4
                       a2 2 0 002-2v-7" />
    </svg>
    @endif
</button>
@endif