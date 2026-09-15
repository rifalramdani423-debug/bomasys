@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-zinc-950 border-zinc-800 text-white placeholder-zinc-600 focus:border-red-600 focus:ring focus:ring-red-600/20 rounded-xl px-4 py-2.5 transition']) }}>
