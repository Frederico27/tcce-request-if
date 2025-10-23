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
                                        <span class="w-32 font-medium text-gray-500">Jumlah Dana:</span>
                                        ${{ $this->transaction->amount }}
                                    </div>
                                    <div class="flex items-center">
                                        <span class="w-32 font-medium text-gray-500">Status:</span>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            @if($this->transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($this->transaction->status === 'admin_approved') bg-blue-100 text-blue-800
                                            @elseif($this->transaction->status === 'rejected') bg-red-100 text-red-800
                                            @elseif($this->transaction->status === 'completed') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucwords(str_replace('_', ' ', $this->transaction->status)) }}
                                        </span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-medium text-gray-500">Dibuat pada:</span>
                                        {{ $this->transaction->created_at->format('d M Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                          <!-- Activity -->
                         <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-700 mb-3">Activity</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700">{{ $this->transaction->activity }}</p>
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


                    </div>

                    <!-- Rejection Modal -->
                    <div x-data="{ show: @entangle('showRejectModal') }" x-show="show" x-cloak
                        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center"
                        style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full" @click.outside="show = false">
                            <h3 class="text-lg font-medium mb-4">Alasan Penolakan</h3>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Reason
                                    for rejection</label>
                                <textarea wire:model.defer="rejectReason" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-gray-700 
                                        bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 
                                        focus:border-transparent resize-none transition duration-200 ease-in-out"
                                    rows="4" placeholder="Masukkan alasan penolakan..."></textarea>
                                @error('rejectReason') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button wire:click="$set('showRejectModal', false)"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Batal
                                </button>
                                <button wire:click="rejectConfirmation('{{ $transaction->id_transactions }}')"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Ajukan Penolakan
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- Admin Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-4">
                        @if($this->transaction->status === 'manager_approved')
                            <span class="text-green-600 font-medium flex items-center">
                                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Approved on {{ $this->transaction->updated_at->format('M d, Y') }}
                            </span>

                            <button type="button"
                                wire:click="verifyConfirmation('{{ $this->transaction->id_transactions }}')"
                                class="px-4 py-2 bg-purple-500 border border-transparent rounded-md font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Verify
                            </button>
                        @elseif($this->transaction->status === 'verified')
                            <span class="text-blue-600 font-medium flex items-center">
                                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Verified on {{ $this->transaction->updated_at->format('M d, Y') }}
                            </span>
                        @elseif ($this->transaction->status === 'rejected')
                            <span class="text-red-600 font-medium flex items-center">
                                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Rejected on {{ $this->transaction->updated_at->format('M d, Y') }}
                            </span>
                        @endif
                    </div>
                </div>
        </div>
        </main>
    </div>
</div>
</div>