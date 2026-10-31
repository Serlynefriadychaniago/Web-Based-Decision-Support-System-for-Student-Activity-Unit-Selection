@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#8B0000] focus:ring-[#8B0000] rounded-md shadow-sm']) }}>
