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
                                    </div>


                                    <div class="border border-gray-200 rounded-md p-4 bg-gray-50">
                                        <div class="flex justify-between items-center mb-4">
                                            <h4 class="font-medium text-gray-700">Upload Bukti Pembayaran</h4>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <!-- Loop through data items -->
                                            @foreach($transactionDetails as $index => $dataItem)
                                            <div class="border-b border-gray-200 pb-6 mb-6 last:border-0">
                                                <div class="flex justify-between items-center mb-3">
                                                    <h3 class="text-lg font-medium text-gray-900">Item {{ $index + 1 }}</h3>
                                                    <button type="button" wire:click="deleteConfirmation({{ $index }})"
                                                        class="text-red-600 hover:text-red-800 transition duration-150"
                                        >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Image Upload -->
                                                <div class="space-y-2" x-data="{ showModal: false, modalImage: '' }">
                                                    <label for="image-{{ $index }}"
                                                        class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
                                                    <div class="flex items-center space-x-4">
                                                        <div class="relative">
                                                            <!-- Hidden File Input -->
                                                            <input type="file" wire:model.live="images.{{ $index }}"
                                                                id="image-{{ $index }}" class="sr-only"
                                                                accept="image/*">

                                                            <!-- Upload Box -->
                                                            <label for="image-{{ $index }}"
                                                                class="cursor-pointer flex items-center justify-center w-32 h-32 rounded-md border-2 border-dashed border-gray-300 bg-white hover:border-blue-400 transition duration-200">
                                                                @if(isset($images[$index]) && $images[$index] && is_object($images[$index]) && method_exists($images[$index], 'temporaryUrl'))
                                                                    <img src="{{ $images[$index]->temporaryUrl() }}"
                                                                        class="object-cover w-full h-full rounded-md" />
                                                                @elseif(isset($imageSrcs[$index]) && $imageSrcs[$index])
                                                                    <img src="{{ $imageSrcs[$index] }}"
                                                                        class="object-cover w-full h-full rounded-md" />
                                                                @else
                                                                    <div class="space-y-1 text-center">
                                                                        <svg class="mx-auto h-8 w-8 text-gray-400"
                                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                        </svg>
                                                                        <p class="text-xs text-gray-500">Upload</p>
                                                                    </div>
                                                                @endif
                                                            </label>
                                                        </div>

                                                        <!-- View Image Link -->
                                                        @if(isset($images[$index]) && $images[$index] && is_object($images[$index]) && method_exists($images[$index], 'temporaryUrl'))
                                                            <button type="button"
                                                                @click="modalImage = '{{ $images[$index]->temporaryUrl() }}'; showModal = true"
                                                                class="text-blue-600 text-sm underline hover:text-blue-800 transition duration-150">
                                                                Lihat Gambar
                                                            </button>
                                                        @elseif(isset($imageSrcs[$index]) && $imageSrcs[$index])
                                                            <button type="button"
                                                                @click="modalImage = '{{ $imageSrcs[$index] }}'; showModal = true"
                                                                class="text-blue-600 text-sm underline hover:text-blue-800 transition duration-150">
                                                                Lihat Gambar
                                                            </button>
                                                        @endif
                                                    </div>

                                                    <!-- Image Modal -->
                                                    <div x-show="showModal" x-transition
                                                        class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50"
                                                        @click.away="showModal = false">
                                                        <div
                                                            class="bg-white rounded-lg overflow-hidden max-w-3xl max-h-[90vh] p-4 relative">
                                                            <button @click.prevent.stop="showModal = false"
                                                                class="absolute top-2 right-2 text-black text-2xl hover:text-gray-700">&times;</button>
                                                            <img :src="modalImage"
                                                                class="max-w-full max-h-[80vh] rounded-md" />
                                                        </div>
                                                    </div>

                                                    @error("images.{$index}")
                                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Description Input -->
                                                <div class="space-y-2">
                                                    <label for="description-{{ $index }}"
                                                        class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                                    <textarea wire:model="descriptions.{{ $index }}"
                                                        id="description-{{ $index }}" rows="4" class="shadow-sm block w-full sm:text-sm rounded-md px-4 py-3
                                                border border-gray-300 
                                                focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                                placeholder-gray-400
                                                transition duration-200 ease-in-out
                                                hover:border-blue-300
                                                resize-none" placeholder="Enter item description">{{ $dataItem->used_for ?? '' }}</textarea>
                                                    @error("descriptions.{$index}")
                                                        <span class="text-red-500 text-xs block">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Right Column -->
                                                <div class="space-y-4">
                                                    <!-- Amount Input -->
                                                    <div class="space-y-2">
                                                        <label for="amount-{{ $index }}"
                                                            class="block text-sm font-medium text-gray-700">Dana yang
                                                            dibelanjakan</label>
                                                        <div class="relative rounded-md shadow-sm">
                                                            <div
                                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                <span
                                                                    class="text-gray-500 sm:text-sm font-medium">$</span>
                                                            </div>
                                                            <input type="number" wire:model="amounts.{{ $index }}"
                                                                id="amount-{{ $index }}"
                                                                class="block w-full pl-12 pr-12 py-3 sm:text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:border-blue-300 transition duration-200 ease-in-out placeholder-gray-400"
                                                                placeholder="0.00" step="0.01"
                                                                value="{{ $dataItem->amount ?? '' }}">
                                                        </div>
                                                        @error("amounts.{$index}")
                                                            <span class="text-red-500 text-xs block">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <!-- Category Input -->
<div class="space-y-2">
    <label for="category-{{ $index }}"
        class="block text-sm font-medium text-gray-700">Kategori</label>
    <div wire:ignore>
        <select 
            id="category-select-{{ $index }}"
            class="select2-category block w-full py-3 pl-3 pr-10 text-base border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:border-blue-300 transition duration-200 ease-in-out"
            data-index="{{ $index }}">
            <option value="">Pilih Kategori</option>
            @foreach($categoryOptions ?? [] as $categoryOption)
                <option value="{{ $categoryOption->id_category }}" 
                    {{ isset($categories[$index]) && $categories[$index] == $categoryOption->id_category ? 'selected' : '' }}>
                    {{ $categoryOption->category_name }}
                </option>
            @endforeach
        </select>
    </div>
    @error("categories.{$index}")
        <span class="text-red-500 text-xs block">{{ $message }}</span>
    @enderror
</div>
<!-- Sub Category Input -->
<div class="space-y-2">
    <label for="sub_category-{{ $index }}"
           class="block text-sm font-medium text-gray-700">Sub Kategori</label>
    <div wire:ignore>
        <select 
            id="subcategory-select-{{ $index }}"
            class="select2-subcategory block w-full py-3 pl-3 pr-10 text-base border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:border-blue-300 transition duration-200 ease-in-out"
            data-index="{{ $index }}">
            <option value="">Pilih Sub Kategori</option>
            @foreach($subCategoryOptions[$index] ?? [] as $subCategory)
                <option value="{{ $subCategory['id_sub_category'] }}"
                        {{ isset($subCategories[$index]) && $subCategories[$index] == $subCategory['id_sub_category'] ? 'selected' : '' }}>
                    {{ $subCategory['sub_category_name'] }}
                </option>
            @endforeach
        </select>
    </div>
    @error("subCategories.{$index}")
        <span class="text-red-500 text-xs block">{{ $message }}</span>
    @enderror
</div>
</div>
</div>
@endforeach
</div>
</div>
                                                

                                    <!-- Form Actions -->
                                    <div class="mt-6 flex justify-end">
                                        <a href="{{ route('transactions.requestor.index') }}" wire:navigate
                                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Batalkan
                                        </a>
                                        <button type="submit"
                                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Edit Bukti Return
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', function() {
        initializeSelects();

        Livewire.hook('morph.updated', function() {
            initializeSelects();
        });

        // Listen for subcategory options update
        Livewire.on('subcategory-options-updated', ({ index }) => {
            updateSubCategorySelect(index);
        });
    });

    function initializeSelects() {
        // Initialize category selects
        $('.select2-category').each(function() {
            const el = $(this);
            const index = el.data('index');

            // Destroy if already initialized
            if (el.hasClass("select2-hidden-accessible")) {
                el.select2('destroy');
            }

            el.select2({
                placeholder: 'Pilih Kategori',
                width: '100%'
            }).on('select2:select', function(e) {
                const selectedValue = e.params.data.id;
                @this.set(`categories.${index}`, selectedValue);
                @this.call('handleCategories', selectedValue, index);
            });

            // Set initial value to match Livewire state
            const categoryValue = @this.get(`categories.${index}`);
            if (categoryValue) {
                el.val(categoryValue).trigger('change');
            }
        });

        // Initialize subcategory selects
        $('.select2-subcategory').each(function() {
            const el = $(this);
            const index = el.data('index');

            // Destroy if already initialized
            if (el.hasClass("select2-hidden-accessible")) {
                el.select2('destroy');
            }

            el.select2({
                placeholder: 'Pilih Sub Kategori',
                width: '100%'
            }).on('select2:select', function(e) {
                const selectedValue = e.params.data.id;
                @this.set(`subCategories.${index}`, selectedValue);
            });

            // Set initial value to match Livewire state
            const subCategoryValue = @this.get(`subCategories.${index}`);
            if (subCategoryValue) {
                el.val(subCategoryValue).trigger('change');
            }
        });
    }

    function updateSubCategorySelect(index) {
        const el = $(`#subcategory-select-${index}`);
        if (!el.length) return;

        // Destroy existing Select2 instance
        if (el.hasClass("select2-hidden-accessible")) {
            el.select2('destroy');
        }

        // Clear existing options
        el.empty();
        el.append('<option value="">Pilih Sub Kategori</option>');

        // Fetch new options from Livewire
        const subCategoryOptions = @this.get(`subCategoryOptions.${index}`) || [];

        // Add new options
        subCategoryOptions.forEach(option => {
            const isSelected = @this.get(`subCategories.${index}`) == option.id_sub_category ? 'selected' : '';
            el.append(`<option value="${option.id_sub_category}" ${isSelected}>${option.sub_category_name}</option>`);
        });

        // Reinitialize Select2
        el.select2({
            placeholder: 'Pilih Sub Kategori',
            width: '100%'
        }).on('select2:select', function(e) {
            const selectedValue = e.params.data.id;
            @this.set(`subCategories.${index}`, selectedValue);
        });

        // Set the current value
        const subCategoryValue = @this.get(`subCategories.${index}`);
        if (subCategoryValue) {
            el.val(subCategoryValue).trigger('change');
        }
    }

    // Clean up on navigation
    document.addEventListener('livewire:navigating', function() {
        $('.select2-category, .select2-subcategory').each(function() {
            if ($(this).hasClass("select2-hidden-accessible")) {
                $(this).select2('destroy');
            }
        });
    });
</script>
@endpush