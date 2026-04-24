<div class="flex items-center justify-center">
    @if(isset($imageUrl) && $imageUrl)
        <div class="relative group">
            <img src="{{ $imageUrl }}" 
                 alt="Preview" 
                 class="h-20 w-20 object-cover rounded-lg border border-gray-200 shadow-sm transition-all duration-200 group-hover:scale-105"
                 onerror="this.src='https://via.placeholder.com/150?text=Invalid+URL'"
            >
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-200 rounded-lg"></div>
        </div>
    @else
        <div class="h-20 w-20 flex flex-col items-center justify-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg text-gray-400 text-[10px] text-center p-1">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>No Preview</span>
        </div>
    @endif
</div>
