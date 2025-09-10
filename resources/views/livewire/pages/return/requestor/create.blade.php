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
                                            <!-- Image Upload -->
                                            <div class="space-y-2" x-data="{ showModal: false, modalImage: '' }">
                                                <label for="image"
                                                    class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
                                                <div class="flex items-center space-x-4">
                                                    <div class="relative">
                                                        <!-- Hidden File Input -->
                                                        <input type="file" wire:model.live="image" id="image"
                                                            class="sr-only" accept="image/*">

                                                        <!-- Upload Box -->
                                                        <label for="image"
                                                            class="cursor-pointer flex items-center justify-center w-32 h-32 rounded-md border-2 border-dashed border-gray-300 bg-white hover:border-blue-400 transition duration-200">
                                                            @if(isset($image) && $image && is_object($image) && method_exists($image, 'temporaryUrl'))
                                                                <img src="{{ $image->temporaryUrl() }}"
                                                                    class="object-cover w-full h-full rounded-md" />
                                                            @elseif(isset($imageSrc) && $imageSrc)
                                                                <img src="{{ $imageSrc }}"
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
                                                    @if(isset($image) && $image && is_object($image) && method_exists($image, 'temporaryUrl'))
                                                        <button type="button"
                                                            @click="modalImage = '{{ $image->temporaryUrl() }}'; showModal = true"
                                                            class="text-blue-600 text-sm underline hover:text-blue-800 transition duration-150">
                                                            Lihat Gambar
                                                        </button>
                                                    @elseif(isset($imageSrc) && $imageSrc)
                                                        <button type="button"
                                                            @click="modalImage = '{{ $imageSrc }}'; showModal = true"
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

                                                @error("image")
                                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <!-- Description Input -->
                                            <div class="space-y-2">
                                                <label for="description"
                                                    class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                                <textarea wire:model="description" id="description" rows="4" class="shadow-sm block w-full sm:text-sm rounded-md px-4 py-3
        border border-gray-300 
        focus:ring-2 focus:ring-blue-500 focus:border-transparent
        placeholder-gray-400
        transition duration-200 ease-in-out
        hover:border-blue-300
        resize-none" placeholder="Enter item description"></textarea>
                                                @error("description")
                                                    <span class="text-red-500 text-xs block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Right Column -->
                                            <div class="space-y-4">
                                                <!-- Amount Input -->
                                                <div class="space-y-2">
                                                    <label for="amount"
                                                        class="block text-sm font-medium text-gray-700">Dana yang
                                                        dibelanjakan</label>
                                                    <div class="relative rounded-md shadow-sm">
                                                        <div
                                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-gray-500 sm:text-sm font-medium">$</span>
                                                        </div>
                                                        <input type="number" wire:model="amount" id="amount"
                                                            class="block w-full pl-12 pr-12 py-3 sm:text-sm  border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:border-blue-300 transition duration-200 ease-in-out placeholder-gray-400"
                                                            placeholder="0.00" step="0.01">
                                                    </div>
                                                    @error("amount")
                                                        <span class="text-red-500 text-xs block">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Category Input -->
                                                <div class="space-y-2">
                                                    <label for="category"
                                                        class="block text-sm font-medium text-gray-700">Kategori</label>
                                                    <div wire:ignore>
                                                        <select wire:model="category" id="category-select"
                                                            class="select2 block w-full py-3 pl-3 pr-10 text-base border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:border-blue-300 transition duration-200 ease-in-out">
                                                            <option value="">Pilih Kategori</option>
                                                            @foreach($categories ?? [] as $category)
                                                                <option value="{{ $category->id_category }}">
                                                                    {{ $category->category_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error("category")
                                                        <span class="text-red-500 text-xs block">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Sub Category Input -->
                                                <div class="space-y-2">
                                                    <label for="sub_category"
                                                        class="block text-sm font-medium text-gray-700">Sub
                                                        Kategori</label>
                                                    <div wire:ignore>
                                                        <select wire:model="id_sub_category" id="subcategory-select"
                                                            class="select2 block w-full py-3 pl-3 pr-10 text-base border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:border-blue-300 transition duration-200 ease-in-out"
                                                            required>
                                                            <option value="">Pilih Sub Kategori</option>
                                                            @foreach($subCategories as $subCategory)
                                                                <option value="{{ $subCategory['id_sub_category'] }}">
                                                                    {{ $subCategory['sub_category_name'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error("id_sub_category")
                                                        <span class="text-red-500 text-xs block">{{ $message }}</span>
                                                    @enderror
                                                </div>



                                            </div>
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

@push('scripts')
    <script>
        function initCategorySelect2() {
            // Initialize Category Select2
            $('#category-select').select2({
                placeholder: 'Pilih Kategori',
                width: '100%',
                allowClear: true
            });

            // Sync Category Select2 with Livewire
            $('#category-select').on('change', function (e) {
                @this.call('setCategory', $(this).val());
            });
        }

        function initSubCategorySelect2() {
            // Initialize Sub Category Select2
            $('#subcategory-select').select2({
                placeholder: 'Pilih Sub Kategori',
                width: '100%',
                allowClear: true
            });

            // Sync Sub Category Select2 with Livewire
            $('#subcategory-select').on('change', function (e) {
                @this.call('setSubCategory', $(this).val());
            });
        }

        document.addEventListener('livewire:initialized', function () {
            initCategorySelect2();
            initSubCategorySelect2();

            // Re-initialize Select2 after Livewire updates
            Livewire.hook('morph.updated', () => {
                initCategorySelect2();
                initSubCategorySelect2();
            });

            // Update Category Select2 when Livewire changes the value
            Livewire.on('categoryUpdated', function (value) {
                $('#category-select').val(value).trigger('change.select2');
            });

            // Update and refresh subcategory options when category changes
            Livewire.on('subcategoriesUpdated', function (subCategories) {
                var $subCategorySelect = $('#subcategory-select');

                // Clear current options
                $subCategorySelect.empty();

                subCategories[0].forEach(function (subCategory) {
                    $subCategorySelect.append(new Option(
                        subCategory.sub_category_name,
                        subCategory.id_sub_category,
                        false,  // Not selected by default
                        false   // Not default option
                    ));
                });


                // Refresh Select2
                $subCategorySelect.val('').trigger('change.select2');
            });
        });

        // Handle Livewire navigation events for SPA navigation
        document.addEventListener('livewire:navigating', () => {
            // Clean up Select2 instances before navigation
            if ($('#category-select').data('select2')) {
                $('#category-select').select2('destroy');
            }
            if ($('#subcategory-select').data('select2')) {
                $('#subcategory-select').select2('destroy');
            }
        });

        document.addEventListener('livewire:navigated', () => {
            // Delay initialization slightly to ensure DOM is ready
            setTimeout(() => {
                if ($('#category-select').length) {
                    initCategorySelect2();
                }
                if ($('#subcategory-select').length) {
                    initSubCategorySelect2();
                }
            }, 200);
        });
    </script>
@endpush