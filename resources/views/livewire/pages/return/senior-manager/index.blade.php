<div>
    <style>
        .tooltip-text {
            width: max-content;
            max-width: 300px;
            white-space: normal;
        }
    </style>
    <!-- Main Container -->
    <div class="flex min-h-screen bg-gray-50">
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header with Breadcrumbs -->
            <header class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 py-4 shadow-sm sticky top-0 z-10">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full">
                    <div class="flex justify-between items-center w-full">
                        <a href="{{ route('transactions.senior.manager.index') }}" wire:navigate class="flex-shrink-0">
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-0">Halaman Transaksi
                                Senior Manager</h1>
                        </a>

                        <!-- Notifications Dropdown -->
                        <div x-data="{ open: false }" class="relative sm:mr-5">
                            <button @click="open = !open"
                                class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none"
                                aria-label="Notifications">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <!-- Notification Badge -->
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                    3
                                </span>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-screen max-w-xs sm:w-80 bg-white rounded-md shadow-lg py-1 z-50"
                                style="display: none;">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                                </div>

                                <!-- Notifications List -->
                                <div class="max-h-60 sm:max-h-64 overflow-y-auto">
                                    <!-- Unread Notification -->
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-l-4 border-blue-500">
                                        <p class="text-sm font-medium text-gray-900">Permintaan Baru</p>
                                        <p class="text-xs text-gray-500 mt-1">Ada permintaan baru dari departemen IT
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">5 menit yang lalu</p>
                                    </a>

                                    <!-- Read Notification -->
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                        <p class="text-sm text-gray-700">Status Transaksi Diperbarui</p>
                                        <p class="text-xs text-gray-500 mt-1">Transaksi #12345 telah disetujui</p>
                                        <p class="text-xs text-gray-400 mt-1">1 jam yang lalu</p>
                                    </a>

                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                        <p class="text-sm text-gray-700">Saldo Diperbarui</p>
                                        <p class="text-xs text-gray-500 mt-1">Saldo telah ditambahkan sebesar Rp
                                            500.000
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">1 hari yang lalu</p>
                                    </a>
                                </div>

                                <div class="px-4 py-2 border-t border-gray-100 text-center">
                                    <a href="#" class="text-xs text-blue-600 hover:text-blue-800">Lihat semua
                                        notifikasi</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <!-- Search and Filter Controls -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                        <div class="p-4 sm:p-6">
                            <div
                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 sm:space-x-4">
                                <!-- Search Input -->
                                <div class="relative flex-1 max-w-md w-full sm:max-w-md">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" wire:model.live.debounce.300ms="search" id="search-input"
                                        placeholder="Cari transaksi..."
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>

                                <!-- Filters Container -->
                                <div class="flex flex-col sm:flex-row gap-4 sm:gap-3">
                                    <!-- Type Filter -->
                                    <div class="relative">
                                        <select wire:model.live.debounce.300ms="typeFilter" id="type-filter"
                                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white">
                                            <option value="">Semua Tipe Transaksi</option>
                                            <option value="request">Request</option>
                                            <option value="return">Return</option>
                                        </select>
                                    </div>

                                    <!-- Type Filter -->
                                    <div class="relative">
                                        <select wire:model.live.debounce.300ms="statusFilter" id="type-filter"
                                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white">
                                            <option value="">Semua Tipe Status</option>
                                            <option value="admin_approved">Approved</option>
                                            <option value="pending">Pending</option>
                                            <option value="rejected">Rejected</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID Transaksi
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi Transaksi
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Deskripsi
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nominal
                                        </th>

                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Di Request oleh
                                        </th>

                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal
                                        </th>

                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>

                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="table-body">
                                    @forelse ($transactions as $transaction)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">

                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $transaction->id_transactions }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ ucfirst($transaction->action) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <a wire:navigate
                                                            href="{{ route('transactions.view', $transaction->id_transactions) }}"
                                                            class="text-sm font-medium text-gray-900 cursor-pointer group relative hover:text-blue-600">
                                                            {{ Str::limit($transaction->description, 10) }}
                                                            <span
                                                                class="tooltip-text invisible group-hover:visible absolute z-50 bg-black text-white p-2 rounded text-xs -mt-1 ml-2">
                                                                {{ $transaction->description }}
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            ${{ $transaction->amount }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>


                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $transaction->requested_by }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $transaction->created_at->format('d M Y H:i') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>


                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium">
                                                            @php
                                                                $statusText = str_replace('_', ' ', $transaction->status);
                                                                $statusText = ucwords($statusText);
                                                            @endphp

                                                            @if($transaction->status == 'manager_approved')
                                                                <span
                                                                    class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                                    {{ $statusText }}
                                                                </span>
                                                            @elseif($transaction->status == 'pending')
                                                                <span
                                                                    class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                                                    {{ $statusText }}
                                                                </span>
                                                            @elseif($transaction->status == 'completed')
                                                                <span
                                                                    class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                                    {{ $statusText }}
                                                                </span>
                                                            @elseif($transaction->status == 'rejected')
                                                                <span
                                                                    class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                                                    {{ $statusText }}
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                                                                    {{ $statusText }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>


                                            <!-- Rejection Modal -->
                                            <div x-data="{ show: @entangle('showRejectModal') }" x-show="show" x-cloak
                                                class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center"
                                                style="background-color: rgba(0, 0, 0, 0.5);" @click.self="show = false">
                                                <!-- <-- Only triggers when clicking the background -->

                                                <!-- Modal Content -->
                                                <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                                                    <h3 class="text-lg font-medium mb-4">Alasan Penolakan</h3>

                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                                            Reason for rejection
                                                        </label>
                                                        <textarea wire:model.defer="rejectReason" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-gray-700 
                                                         bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 
                                                         focus:ring-indigo-500 focus:border-transparent resize-none 
                                                         transition duration-200 ease-in-out" rows="4"
                                                            placeholder="Masukkan alasan penolakan..."></textarea>
                                                        @error('rejectReason')
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="flex justify-end space-x-3">
                                                        <button wire:click="$set('showRejectModal', false)"
                                                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                                            Batal
                                                        </button>
                                                        <button
                                                            wire:click="rejectConfirmation('{{ $transaction->id_transactions }}')"
                                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                            Ajukan Penolakan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <!-- View button - always shown -->
                                                @if ($transaction->action == 'return')
                                                    <button
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-1">
                                                        <a wire:navigate href="{{ route('transactions.senior.manager.return.view', $transaction->id_transactions) }}">View</a>
                                                    </button>
                                                @else
                                                    <button
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-1">
                                                        <a wire:navigate href="{{ route('transactions.senior.manager.request.view', $transaction->id_transactions) }}">View</a>
                                                    </button>
                                                @endif

                                                @if ($transaction->status == 'admin_approved' && $transaction->action == 'return')
                                                    <button
                                                        wire:click="approveConfirmation('{{ $transaction->id_transactions }}')"
                                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded mr-1">
                                                        Approve
                                                    </button>
                                                    <button
                                                        wire:click="$set('showRejectModal', true); $set('rejectTransactionId', '{{ $transaction->id_transactions }}')"
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                                                        Reject
                                                    </button>
                                                @endif


                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                                Tidak ada transaksi yang tersedia.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                        <!-- Pagination Laravel -->
                        <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                            <div class="flex items-center justify-between flex-col sm:flex-row gap-4">
                                <div class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $transactions->firstItem() ?? 0 }}</span>
                                    to
                                    <span class="font-medium">{{ $transactions->lastItem() ?? 0 }}</span>
                                    of
                                    <span class="font-medium">{{ $transactions->total() }}</span>
                                    results
                                </div>
                                <div>
                                    {{ $transactions->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>