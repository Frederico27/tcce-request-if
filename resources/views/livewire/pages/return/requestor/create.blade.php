<div>
    <!-- Main Container -->
    <div class="flex min-h-screen bg-gray-50">
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header with Breadcrumbs -->
            <header class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 py-4 shadow-sm sticky top-0 z-10">
                <!-- Page Title -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center">
                        <a href="{{ route('transactions.requestor.index') }}" wire:navigate class="mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 hover:text-gray-700"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <h1 class="text-2xl font-bold text-gray-900">Return</h1>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <!-- Form Container -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-4 py-5 sm:p-6">
                            <form wire:submit.prevent="save">

                                <!-- Dynamic Inputs Container -->
                                <div class="space-y-6">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-medium text-gray-900">Detail Item</h3>
                                        <button type="button" wire:click="addItem"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                                            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Tambah Item
                                        </button>
                                    </div>

                                    @foreach($items as $index => $item)
                                        <div class="border border-gray-200 rounded-md p-4 bg-gray-50 relative">
                                            <div class="flex justify-between items-center mb-4">
                                                <h4 class="font-medium text-gray-700">Item #{{ $index + 1 }}</h4>
                                                @if(count($items) > 1)
                                                    <button type="button" wire:click="removeItem({{ $index }})"
                                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150">
                                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <!-- Image Upload -->
                                                <div class="space-y-2" x-data="{ showModal: false, modalImage: '' }">
                                                    <label for="image-{{ $index }}"
                                                        class="block text-sm font-medium text-gray-700 mb-1">
                                                        Gambar <span class="text-red-500">*</span>
                                                    </label>
                                                    <div class="flex items-center space-x-4">
                                                        <div class="relative">
                                                            <!-- Hidden File Input -->
                                                            <input type="file" wire:model.live="items.{{ $index }}.image" 
                                                                id="image-{{ $index }}" class="sr-only" accept="image/*">

                                                            <!-- Upload Box -->
                                                            <label for="image-{{ $index }}"
                                                                class="cursor-pointer flex items-center justify-center w-32 h-32 rounded-md border-2 border-dashed border-gray-300 bg-white hover:border-blue-400 transition duration-200">
                                                                @if(isset($items[$index]['image']) && $items[$index]['image'] && is_object($items[$index]['image']) && method_exists($items[$index]['image'], 'temporaryUrl'))
                                                                    <img src="{{ $items[$index]['image']->temporaryUrl() }}"
                                                                        class="object-cover w-full h-full rounded-md" />
                                                                @else
                                                                    <div class="space-y-1 text-center">
                                                                        <svg class="mx-auto h-8 w-8 text-gray-400"
                                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                        </svg>
                                                                        <p class="text-xs text-gray-500">Upload</p>
                                                                    </div>
                                                                @endif
                                                            </label>
                                                        </div>

                                                        <!-- View Image Link -->
                                                        @if(isset($items[$index]['image']) && $items[$index]['image'] && is_object($items[$index]['image']) && method_exists($items[$index]['image'], 'temporaryUrl'))
                                                            <button type="button"
                                                                @click="modalImage = '{{ $items[$index]['image']->temporaryUrl() }}'; showModal = true"
                                                                class="text-blue-600 text-sm underline hover:text-blue-800 transition duration-150">
                                                                Lihat Gambar
                                                            </button>
                                                        @endif
                                                    </div>

                                                    <!-- Image Modal -->
                                                    <div x-show="showModal" x-transition
                                                        class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50"
                                                        @click.away="showModal = false">
                                                        <div class="bg-white rounded-lg overflow-hidden max-w-3xl max-h-[90vh] p-4 relative">
                                                            <button @click.prevent.stop="showModal = false"
                                                                class="absolute top-2 right-2 text-black text-2xl hover:text-gray-700">&times;</button>
                                                            <img :src="modalImage" class="max-w-full max-h-[80vh] rounded-md" />
                                                        </div>
                                                    </div>

                                                    @error("items.{$index}.image")
                                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Description Input -->
                                                <div class="space-y-2">
                                                    <label for="description-{{ $index }}"
                                                        class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                                    <textarea wire:model="items.{{ $index }}.description" 
                                                        id="description-{{ $index }}" rows="4" 
                                                        class="shadow-sm block w-full sm:text-sm rounded-md px-4 py-3
                                                        border border-gray-300 
                                                        focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                                        placeholder-gray-400
                                                        transition duration-200 ease-in-out
                                                        hover:border-blue-300
                                                        resize-none" 
                                                        placeholder="Enter item description"></textarea>
                                                    @error("items.{$index}.description")
                                                        <span class="text-red-500 text-xs block">{{ $message }}</span>
                                                    @enderror

                                                    <!-- Activity Images Upload (Optional) -->
                                                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md" x-data="{ 
                                                        showActivityModal: false, 
                                                        activityModalImage: ''
                                                    }">
                                                        <div class="flex items-center justify-between mb-3">
                                                            <label class="block text-sm font-medium text-gray-700">
                                                                <svg class="inline-block w-5 h-5 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                Gambar Aktivitas
                                                                <span class="text-xs text-gray-500 font-normal">(Opsional)</span>
                                                            </label>
                                                            <span class="text-xs text-gray-600 italic">Upload multiple images if needed</span>
                                                        </div>
                                                        
                                                        <!-- Multiple Image Upload -->
                                                        <div class="space-y-3">
                                                            <input type="file" 
                                                                wire:model.live="items.{{ $index }}.activity_images" 
                                                                id="activity-images-{{ $index }}" 
                                                                class="block w-full text-sm text-gray-500
                                                                    file:mr-4 file:py-2 file:px-4
                                                                    file:rounded-md file:border-0
                                                                    file:text-sm file:font-semibold
                                                                    file:bg-blue-100 file:text-blue-700
                                                                    hover:file:bg-blue-200
                                                                    cursor-pointer transition duration-150"
                                                                accept="image/*" multiple>
                                                            
                                                            <!-- Preview Activity Images -->
                                                            @if(isset($items[$index]['activity_images']) && is_array($items[$index]['activity_images']) && count($items[$index]['activity_images']) > 0)
                                                                <div class="grid grid-cols-3 gap-2 mt-3">
                                                                    @foreach($items[$index]['activity_images'] as $actIndex => $actImage)
                                                                        @if($actImage && is_object($actImage) && method_exists($actImage, 'temporaryUrl'))
                                                                            <div class="relative group">
                                                                                <img src="{{ $actImage->temporaryUrl() }}"
                                                                                    class="object-cover w-full h-20 rounded-md border border-gray-300 cursor-pointer hover:opacity-90 transition"
                                                                                    @click="activityModalImage = '{{ $actImage->temporaryUrl() }}'; showActivityModal = true" />
                                                                                <button type="button"
                                                                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition duration-200 hover:bg-red-600"
                                                                                    wire:click="removeActivityImage({{ $index }}, {{ $actIndex }})">
                                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                                    </svg>
                                                                                </button>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>

                                                                <!-- Activity Image Modal -->
                                                                <div x-show="showActivityModal" x-transition
                                                                    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50"
                                                                    @click.away="showActivityModal = false">
                                                                    <div class="bg-white rounded-lg overflow-hidden max-w-3xl max-h-[90vh] p-4 relative">
                                                                        <button @click.prevent.stop="showActivityModal = false"
                                                                            class="absolute top-2 right-2 text-black text-2xl hover:text-gray-700">&times;</button>
                                                                        <img :src="activityModalImage" class="max-w-full max-h-[80vh] rounded-md" />
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @error("items.{$index}.activity_images.*")
                                                                <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        
                                                        <p class="text-xs text-gray-600 mt-2">
                                                            <svg class="inline-block w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Tambahkan foto aktivitas untuk dokumentasi tambahan (opsional)
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Right Column -->
                                                <div class="space-y-4">
                                                    <!-- Amount Input -->
                                                    <div class="space-y-2">
                                                        <label for="amount-{{ $index }}"
                                                            class="block text-sm font-medium text-gray-700">Dana yang dibelanjakan</label>
                                                        <div class="relative rounded-md shadow-sm">
                                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                <span class="text-gray-500 sm:text-sm font-medium">$</span>
                                                            </div>
                                                            <input type="number" wire:model="items.{{ $index }}.amount" 
                                                                id="amount-{{ $index }}"
                                                                class="block w-full pl-12 pr-12 py-3 sm:text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:border-blue-300 transition duration-200 ease-in-out placeholder-gray-400"
                                                                placeholder="0.00" step="0.01">
                                                        </div>
                                                        @error("items.{$index}.amount")
                                                            <span class="text-red-500 text-xs block">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <!-- Category Input -->
                                                    <div class="space-y-2">
                                                        <label for="category-{{ $index }}"
                                                            class="block text-sm font-medium text-gray-700">Kategori</label>
                                                        <select wire:model.live="items.{{ $index }}.category" 
                                                            id="category-{{ $index }}"
                                                            class="block w-full py-3 pl-3 pr-10 text-base border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:border-blue-300 transition duration-200 ease-in-out">
                                                            <option value="">Pilih Kategori</option>
                                                            @foreach($categories ?? [] as $cat)
                                                                <option value="{{ $cat->id_category }}">
                                                                    {{ $cat->category_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error("items.{$index}.category")
                                                            <span class="text-red-500 text-xs block">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <!-- Sub Category Input -->
                                                    <div class="space-y-2">
                                                        <label for="sub_category-{{ $index }}"
                                                            class="block text-sm font-medium text-gray-700">Sub Kategori</label>
                                                        <select wire:model="items.{{ $index }}.id_sub_category" 
                                                            id="sub_category-{{ $index }}"
                                                            class="block w-full py-3 pl-3 pr-10 text-base border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:border-blue-300 transition duration-200 ease-in-out">
                                                            <option value="">Pilih Sub Kategori</option>
                                                            @if(isset($items[$index]['filteredSubCategories']))
                                                                @foreach($items[$index]['filteredSubCategories'] as $subCategory)
                                                                    <option value="{{ $subCategory['id_sub_category'] }}">
                                                                        {{ $subCategory['sub_category_name'] }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @error("items.{$index}.id_sub_category")
                                                            <span class="text-red-500 text-xs block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Form Actions -->
                                    <div class="mt-6 flex justify-end">
                                        <a href="{{ route('transactions.requestor.index') }}" wire:navigate
                                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Batalkan
                                        </a>
                                        <button type="submit"
                                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Submit Bukti Return
                                        </button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
