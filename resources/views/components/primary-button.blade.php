<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-red-600 border border-transparent rounded-xl font-bold text-sm text-white tracking-wide hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-zinc-900 transition duration-200 shadow-lg shadow-red-600/25']) }}>
    {{ $slot }}
</button>
