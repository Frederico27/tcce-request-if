@props(['field', 'sortField', 'sortDirection'])

<th {{ $attributes->merge(['class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 select-none']) }} 
    wire:click="sortBy('{{ $field }}')">
    <div class="flex items-center gap-2">
        {{ $slot }}
        <span class="flex flex-col">
            <svg class="w-3 h-3 {{ $sortField === $field && $sortDirection === 'asc' ? 'text-blue-600' : 'text-gray-400' }}" 
                fill="currentColor" viewBox="0 0 20 20">
                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                    clip-rule="evenodd" fill-rule="evenodd" transform="rotate(180 10 10)"></path>
            </svg>
            <svg class="w-3 h-3 -mt-1 {{ $sortField === $field && $sortDirection === 'desc' ? 'text-blue-600' : 'text-gray-400' }}" 
                fill="currentColor" viewBox="0 0 20 20">
                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                    clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </span>
    </div>
</th>
