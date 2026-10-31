<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#8B0000] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#770000] focus:bg-[#770000] active:bg-[#5F0000] focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
