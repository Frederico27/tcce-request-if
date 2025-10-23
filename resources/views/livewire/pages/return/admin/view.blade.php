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
                        <a href="{{ route('transactions.index') }}" wire:navigate class="mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 hover:text-gray-700"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Transaksi</h1>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <!-- Request Detail Card -->
                <div class="bg-white rounded-lg shadow-md mb-6">
                    <!-- Header with Request ID and Status -->


                    <!-- Request Information -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Requester Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-700 mb-3">Informasi Requestor</h3>
                                <div class="space-y-2">
                                    <div class="flex">
                                        <span class="w-32 font-medium text-gray-500">Nama:</span>
                                        {{ $this->transaction->fromUser->full_name }}
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-medium text-gray-500">NIK:</span>
                                        {{ $this->transaction->fromUser->nik }}
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-medium text-gray-500">Sub Unit:</span>
                                        {{ $this->transaction->fromUser->subUnit->nama_sub_unit }}
                                    </div>

                                    <div class="flex">
                                        <span class="w-32 font-medium text-gray-500">Posisi:</span>
                                        {{ $this->transaction->fromUser->position_name }}
                                    </div>

                                    <div class="flex">
                                        <span class="w-32 font-medium text-gray-500">Nomor Telepon:</span>
                                        {{ $this->transaction->fromUser->phone_number }}
                                    </div>
                                </div>
                            </div>

                            <!-- Request Details -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-700 mb-3">Detail</h3>
                                <div class="space-y-2">
                                    <div class="flex">
                                        <span class="w-32 font-medium text-gray-500">ID Transaksi:</span>
                                        {{ ucfirst($this->transaction->id_transactions) }}
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-medium text-gray-500">Tipe Transaksi:</span>
                                        {{ ucfirst($this->transaction->action) }}
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-medium text-gray-500">Jumlah Diminta:</span>
                                        ${{ $this->transaction->amount }}
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-medium text-gray-500">Dana Tersisa:</span>
                                        ${{ $this->transaction->remaining_amount }}
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-medium text-gray-500">Dana Tambahan:</span>
                                        ${{ $this->transaction->additional_amount }}
                                    </div>
                                    <div class="flex items-center">
                                        <span class="w-32 font-medium text-gray-500">Status:</span>
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-medium
                                            @if ($this->transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($this->transaction->status === 'admin_approved') bg-blue-100 text-blue-800
                                            @elseif($this->transaction->status === 'rejected') bg-red-100 text-red-800
                                            @elseif($this->transaction->status === 'completed') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucwords(str_replace('_', ' ', $this->transaction->status)) }}
                                        </span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-medium text-gray-500">Disubmit pada:</span>
                                        {{ $this->transaction->updated_at->format('d M Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-700 mb-3">Deskripsi</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700">{{ $this->transaction->description }}</p>
                            </div>
                        </div>

                        @if ($this->transaction->status === 'rejected')
                            <!-- Rejection Reason -->
                            <div class="mt-6">
                                <h3 class="text-lg font-medium text-gray-700 mb-3">Alasan Penolakan</h3>
                                <div class="bg-red-50 p-4 rounded-lg">
                                    <p class="text-red-700">{{ $this->transaction->rejection_reason }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-700 mb-3">Detail Pengembalian</h3>
                            @php
                                $totalAmount = 0;
                            @endphp
                            @foreach ($detailReturn as $detail)
                                @php
                                    $totalAmount += $detail->amount;
                                @endphp
                                <div class="bg-gray-50 p-4 rounded-lg mb-2">
                                    <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                                        <div class="flex-1 space-y-2">
                                            <div class="grid grid-cols-[180px,auto] text-gray-700">
                                                <span class="font-semibold">Deskripsi Pembelian :</span>
                                                <span>{{ $detail->used_for }}</span>
                                            </div>
                                            <div class="grid grid-cols-[180px,auto] text-gray-700">
                                                <span class="font-semibold">Total Dana Pembelian :</span>
                                                <span>${{ $detail->amount }}</span>
                                            </div>
                                            <div class="grid grid-cols-[180px,auto] text-gray-700">
                                                <span class="font-semibold">Kategori :</span>
                                                <span>{{ $detail->subCategory->category->category_name }}</span>
                                            </div>
                                            <div class="grid grid-cols-[180px,auto] text-gray-700">
                                                <span class="font-semibold">Sub Kategori :</span>
                                                <span>{{ $detail->subCategory->sub_category_name }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0" x-data="{ showModal: false, imageSrc: '{{ asset('storage/' . $detail->transactionAttachments[0]->file_path) }}' }">
                                            <img src="{{ asset('storage/' . $detail->transactionAttachments[0]->file_path) }}"
                                                alt="Bukti Pembelian"
                                                class="w-32 h-32 object-cover cursor-pointer rounded-md shadow-sm hover:shadow-md transition"
                                                @click="showModal = true">

                                            <!-- Image Modal -->
                                            <div x-show="showModal" x-transition
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
                                                @click="showModal = false">
                                                <div class="max-w-3xl max-h-[90vh] p-2 bg-white rounded-lg" @click.stop>
                                                    <img :src="imageSrc" alt="Preview"
                                                        class="max-w-full max-h-[85vh] object-contain">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                            <!-- Activity Images Section -->
                            <div class="mt-8">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-medium text-gray-700">Gambar Aktivitas</h3>
                                    <span class="text-sm text-gray-500">
                                        {{ count($activityImages) }} gambar
                                    </span>
                                </div>

                                @if (!empty($activityImages) && count($activityImages) > 0)
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                        @foreach ($activityImages as $index => $image)
                                            <div class="group relative bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200"
                                                x-data="{ showModal: false, imageSrc: '{{ asset('storage/' . $image->image_path) }}' }">
                                                <!-- Thumbnail -->
                                                <div class="relative overflow-hidden bg-gray-100">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                                        alt="{{ $image->description ?? 'Gambar aktivitas' }}"
                                                        class="w-full h-32 object-cover transition-transform duration-200 group-hover:scale-105 cursor-pointer"
                                                        @click="showModal = true" loading="lazy">
                                                </div>

                                                <!-- Info -->
                                                <div class="p-2">
                                                    <p class="text-xs text-gray-500 truncate leading-tight">
                                                        {{ $image->description ?? 'Gambar ' . ($index + 1) }}
                                                    </p>
                                                </div>

                                                <!-- Image Modal -->
                                                <div x-show="showModal" x-transition
                                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
                                                    @click="showModal = false">
                                                    <div class="max-w-3xl max-h-[90vh] p-2 bg-white rounded-lg" @click.stop>
                                                        <img :src="imageSrc" alt="Preview"
                                                            class="max-w-full max-h-[85vh] object-contain">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Empty State -->
                                    <div
                                        class="bg-white border-2 border-dashed border-gray-200 rounded-lg p-8 text-center">
                                        <div class="w-16 h-16 mx-auto mb-4">
                                            <svg class="w-full h-full text-gray-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-4a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                            </svg>
                                        </div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">Belum ada gambar aktivitas
                                        </h4>
                                        <p class="text-sm text-gray-500">Gambar akan ditampilkan setelah diunggah</p>
                                    </div>
                                @endif
                            </div>


                            <!-- Total Amount Section -->
                            <div class="mt-4 bg-gray-100 p-4 rounded-lg border-t-2 border-gray-300">
                                <div class="grid grid-cols-[180px,auto] text-gray-700">
                                    <span class="font-bold text-lg">Total Keseluruhan:</span>
                                    <span class="font-bold text-lg">${{ number_format($totalAmount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
        </div>
        </main>
    </div>
</div>
