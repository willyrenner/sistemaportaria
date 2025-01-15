@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center border-b-2 border-transparent text-2xl font-bold text-white dark:text-white focus:outline-none focus:border-transparent transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-2xl font-bold leading-5 text-white dark:text-white hover:text-white dark:hover:text-white hover:border-transparent dark:hover:border-transparent focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-transparent dark:focus:border-transparent transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
