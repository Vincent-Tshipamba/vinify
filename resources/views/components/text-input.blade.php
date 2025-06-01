@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-3 rounded-lg text-gray-800 bg-transparent border border-gray-700 placeholder-opacity-50 focus:outline-none focus:border-pink-500']) }}>
