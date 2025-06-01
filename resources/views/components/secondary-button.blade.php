<button {{ $attributes->merge(['type' => 'button', 'class' => 'flex items-start justify-center self-start w-2/5 bg-gray-800 text-gray-500 font-semibold border border-gray-700 px-4 py-3 text-sm gap-2 mt-2 cursor-pointer rounded-md hover:bg-white hover:text-black hover:border-white transition-colors']) }}>
    {{ $slot }}
</button>
