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
                        <a href="{{ route('transactions.index') }}" wire:navigate class="flex-shrink-0">
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-0">Halaman Transaksi
                                Admin</h1>
                        </a>

                        <!-- Notifications Dropdown -->
                        <div x-data="{ open: false }" class="relative sm:mr-5">
                            <button @click="open = !open"
                                class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none"
                                aria-label="Notifications">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
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
                                    <a href="#"
                                        class="block px-4 py-3 hover:bg-gray-50 border-l-4 border-blue-500">
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

                    <div class="mt-3 sm:mt-0">
                        <!-- Balance Status -->
                        <div class="bg-gray-50 rounded-lg px-3 sm:px-4 py-2 shadow w-full">
                            <div class="flex items-center justify-between sm:justify-start">
                                <div class="mr-3">
                                    <span class="text-xs sm:text-sm font-medium text-gray-500">Saldo:</span>
                                    <span
                                        class="text-base sm:text-lg font-semibold text-gray-900">${{ $saldo->balance }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $saldo->balance > 500 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $saldo->balance > 500 ? 'Aman' : 'Perhatian' }}
                                    </span>
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
                                    <!-- Date Range Filter -->
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <div class="relative">
                                            <input type="date" wire:model.live="dateFrom" id="date-from"
                                                class="block w-full pl-3 pr-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white"
                                                placeholder="Dari tanggal">
                                        </div>
                                        <div class="relative">
                                            <input type="date" wire:model.live="dateTo" id="date-to"
                                                class="block w-full pl-3 pr-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white"
                                                placeholder="Sampai tanggal">
                                        </div>
                                        @if ($dateFrom || $dateTo)
                                            <button wire:click="clearDateFilter" type="button"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Type Filter -->
                                    <div class="relative">
                                        <select wire:model.live.debounce.300ms="typeFilter" id="type-filter"
                                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white">
                                            <option value="">Semua Tipe Transaksi</option>
                                            <option value="request">Request</option>
                                            <option value="return">Return</option>
                                        </select>
                                    </div>

                                    <!-- Status Filter -->
                                    <div class="relative">
                                        <select wire:model.live.debounce.300ms="statusFilter" id="status-filter"
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

                    <!-- Export Buttons -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4" x-data="{ get selectedCount() { return $wire.selectedTransactions ? $wire.selectedTransactions.length : 0 } }">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="text-sm text-gray-700">
                                <span x-text="selectedCount"></span> transaction(s) selected
                            </div>
                            <div class="flex gap-3">
                                <button wire:click="exportExcel" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="selectedCount === 0">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Export to Excel
                                </button>
                                <button wire:click="exportPdf" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="selectedCount === 0">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    Export to PDF
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left">
                                            <input type="checkbox" 
                                                wire:model.live="selectAll"
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        </th>
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
                                                <input type="checkbox" 
                                                    wire:model.live="selectedTransactions"
                                                    value="{{ $transaction->id_transactions }}"
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            </td>
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
                                                                $statusText = str_replace(
                                                                    '_',
                                                                    ' ',
                                                                    $transaction->status,
                                                                );
                                                                $statusText = ucwords($statusText);
                                                            @endphp

                                                            @if ($transaction->status == 'admin_approved')
                                                                <span
                                                                    class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                                    {{ $statusText }}
                                                                </span>
                                                            @elseif($transaction->status == 'manager_approved')
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
                                                style="background-color: rgba(0, 0, 0, 0.5);"
                                                @click.self="show = false">
                                                <!-- <-- Only triggers when clicking the background -->

                                                <!-- Modal Content -->
                                                <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                                                    <h3 class="text-lg font-medium mb-4">Alasan Penolakan</h3>

                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                                            Reason for rejection
                                                        </label>
                                                        <textarea wire:model.defer="rejectReason"
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-gray-700 
                                                     bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 
                                                     focus:ring-indigo-500 focus:border-transparent resize-none 
                                                     transition duration-200 ease-in-out"
                                                            rows="4" placeholder="Masukkan alasan penolakan..."></textarea>
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
                                                        <a wire:navigate
                                                            href="{{ route('transactions.admin.return.view', $transaction->id_transactions) }}">View</a>
                                                    </button>
                                                @else
                                                    <button
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-1">
                                                        <a wire:navigate
                                                            href="{{ route('transactions.view', $transaction->id_transactions) }}">View</a>
                                                    </button>
                                                @endif

                                                <!-- Verify button - shown only when status is 'admin_approved' -->
                                                @if ($transaction->status == 'manager_approved' && $transaction->action == 'request')
                                                    <button
                                                        wire:click="verifyConfirmation('{{ $transaction->id_transactions }}')"
                                                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-2 rounded mr-1">
                                                        Verify f
                                                    </button>
                                                @endif

                                                <!-- Verify button - shown only when status is 'admin_approved' -->
                                                @if ($transaction->status == 'pending' && $transaction->action == 'return')
                                                    <div x-data="{ modalOpen: false }"
                                                        @keydown.escape.window="modalOpen = false"
                                                        class="z-50 w-auto h-auto inline-block">
                                                        <button @click="modalOpen=true"
                                                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-2 rounded mr-1">
                                                            Verify l
                                                        </button>
                                                        <template x-teleport="body">
                                                            <div x-show="modalOpen"
                                                                class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen"
                                                                x-cloak>
                                                                <div x-show="modalOpen"
                                                                    x-transition:enter="ease-out duration-300"
                                                                    x-transition:enter-start="opacity-0"
                                                                    x-transition:enter-end="opacity-100"
                                                                    x-transition:leave="ease-in duration-300"
                                                                    x-transition:leave-start="opacity-100"
                                                                    x-transition:leave-end="opacity-0"
                                                                    @click="modalOpen=false"
                                                                    class="absolute inset-0 w-full h-full bg-black/40">
                                                                </div>
                                                                <div x-show="modalOpen"
                                                                    x-trap.inert.noscroll="modalOpen"
                                                                    x-transition:enter="ease-out duration-300"
                                                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                                    x-transition:leave="ease-in duration-200"
                                                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                                    class="relative px-7 py-6 w-full bg-white sm:max-w-lg sm:rounded-lg">
                                                                    <div
                                                                        class="flex justify-between items-center pb-2">
                                                                        <h3 class="text-lg font-semibold">Verifikasi
                                                                            Transaksi</h3>
                                                                        <button @click="modalOpen=false"
                                                                            class="flex absolute top-0 right-0 justify-center items-center mt-5 mr-5 w-8 h-8 text-gray-600 rounded-full hover:text-gray-800 hover:bg-gray-50">
                                                                            <svg class="w-5 h-5"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                fill="none" viewBox="0 0 24 24"
                                                                                stroke-width="1.5"
                                                                                stroke="currentColor">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M6 18L18 6M6 6l12 12" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                    <div class="relative w-auto">
                                                                        <div
                                                                            class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                                                                            <div class="flex">
                                                                                <div class="flex-shrink-0">
                                                                                    <svg class="h-5 w-5 text-blue-400"
                                                                                        viewBox="0 0 20 20"
                                                                                        fill="currentColor">
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                                            clip-rule="evenodd"></path>
                                                                                    </svg>
                                                                                </div>
                                                                                <div class="ml-3">
                                                                                    @if($transaction->additional_amount > 0)
                                                                                    <p class="text-sm text-blue-700">
                                                                                       Transaksi ini memiliki jumlah tambahan sebesar ${{ abs($transaction->additional_amount) }}.
                                                                                       Tolong kembalikan dana sesuai dengan total yang tertera di bawah.
                                                                                    </p>
                                                                                    @elseif ($transaction->remaining_amount > 0)
                                                                                        <p class="text-sm text-blue-700">
                                                                                       Transaksi ini memiliki sisa jumlah sebesar ${{ abs($transaction->remaining_amount) }}.
                                                                                       Dana tersisa akan terakumulasi ke saldo admin setelah diverifikasi.
                                                                                    </p>
                                                                                    @else
                                                                                     <p class="text-sm text-blue-700">
                                                                                        Tidak ada jumlah tambahan atau sisa dana untuk transaksi ini.
                                                                                     </p>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="text-sm space-y-1">
                                                                            @if ($transaction->action == 'return')
                                                                                @php
                                                                                    $detailsAmount = \App\Models\TransactionDetails::where(
                                                                                        'id_transactions',
                                                                                        $transaction->id_transactions,
                                                                                    )->sum('amount');
                                                                                    $totalReturned =
                                                                                        ($transaction->remaining_amount ??
                                                                                            0) +
                                                                                        ($transaction->additional_amount ??
                                                                                            0);
                                                                                @endphp
                                                                                <div class="flex justify-between">
                                                                                    <span class="text-gray-600">Dana
                                                                                        Diminta:</span>
                                                                                    <span
                                                                                        class="font-medium text-gray-900">${{ number_format($transaction->amount, 2) }}</span>
                                                                                </div>
                                                                                <div class="flex justify-between">
                                                                                    <span class="text-gray-600">Dana
                                                                                        Digunakan:</span>
                                                                                    <span
                                                                                        class="font-medium text-blue-600">${{ number_format($detailsAmount, 2) }}</span>
                                                                                </div>
                                                                                @if ($transaction->remaining_amount > 0)
                                                                                    <div class="flex justify-between">
                                                                                        <span
                                                                                            class="text-gray-600">Dana
                                                                                            Tersisa:</span>
                                                                                        <span
                                                                                            class="font-medium text-green-600">${{ number_format($transaction->remaining_amount, 2) }}</span>
                                                                                    </div>
                                                                                @endif
                                                                                @if ($transaction->additional_amount > 0)
                                                                                    <div class="flex justify-between">
                                                                                        <span
                                                                                            class="text-gray-600">Dana
                                                                                            Tambahan:</span>
                                                                                        <span
                                                                                            class="font-medium text-orange-600">${{ number_format($transaction->additional_amount, 2) }}</span>
                                                                                    </div>
                                                                                @endif
                                                                                @if ($transaction->additional_amount <= 0)
                                                                                    <div
                                                                                        class="flex justify-between pt-1 border-t border-gray-200">
                                                                                        <span
                                                                                            class="text-gray-700 font-semibold flex items-center">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                class="h-5 w-5 text-gray-600 mr-1"
                                                                                                viewBox="0 0 20 20"
                                                                                                fill="currentColor">
                                                                                                <path fill-rule="evenodd"
                                                                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                                                    clip-rule="evenodd"></path>
                                                                                            </svg>
                                                                                            Total Tersisa:
                                                                                        </span>
                                                                                        <span
                                                                                            class="font-bold text-purple-600">${{ number_format($transaction->remaining_amount, 2) }}</span>
                                                                                    </div>
                                                                                @endif


                                                                                @if ($transaction->additional_amount > 0)
                                                                                    <div
                                                                                        class="flex justify-between pt-1 border-t border-gray-200">
                                                                                        <span
                                                                                            class="text-gray-700 font-semibold">Total
                                                                                            Dikembalikan:</span>
                                                                                        <span
                                                                                            class="font-bold text-purple-600">${{ number_format($totalReturned, 2) }}</span>
                                                                                    </div>
                                                                                @endif
                                                                            @else
                                                                                <div class="text-gray-500 italic">-
                                                                                </div>
                                                                            @endif
                                                                        </div>

                                                                        @if ($transaction->additional_amount > 0 || $transaction->remaining_amount > 0)
                                                                        <div class="mt-6 border-t border-gray-200 pt-4">
                                                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                                                Upload Bukti Sisa atau Penambahan Dana
                                                                                <span class="text-red-500">*</span>
                                                                            </label>
                                                                            <div class="mt-1">
                                                                                <input type="file" 
                                                                                    wire:model="imageDishRebush"
                                                                                    accept="image/*"
                                                                                    class="block w-full text-sm text-gray-500
                                                                                        file:mr-4 file:py-2 file:px-4
                                                                                        file:rounded-md file:border-0
                                                                                        file:text-sm file:font-semibold
                                                                                        file:bg-blue-50 file:text-blue-700
                                                                                        hover:file:bg-blue-100
                                                                                        cursor-pointer">
                                                                            </div>
                                                                            @error('imageDishRebush')
                                                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                                            @enderror
                                                                            <p class="mt-1 text-xs text-gray-500">
                                                                                Format: JPG, PNG, JPEG (Max: 2MB)
                                                                            </p>
                                                                            
                                                                            @if ($imageDishRebush)
                                                                                <div class="mt-3">
                                                                                    <p class="text-sm text-gray-700 mb-2">Preview:</p>
                                                                                    <img src="{{ $imageDishRebush->temporaryUrl() }}" 
                                                                                        class="h-32 w-auto rounded-lg border border-gray-300"
                                                                                        alt="Preview">
                                                                                </div>
                                                                            @endif

                                                                            <div class="mt-3">
                                                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                                                    Deskripsi (Opsional)
                                                                                </label>
                                                                                <textarea wire:model="imageDishRebushDescription"
                                                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-gray-700 
                                                                                    bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 
                                                                                    focus:ring-indigo-500 focus:border-transparent resize-none 
                                                                                    transition duration-200 ease-in-out"
                                                                                    rows="3"
                                                                                    placeholder="Masukkan deskripsi..."></textarea>
                                                                            </div>
                                                                        </div>
                                                                        @endif

                                                                        <div class="flex justify-end space-x-3 mt-6">
                                                                            <button @click="modalOpen=false"
                                                                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                                                                Batal
                                                                            </button>
                                                                            <button wire:click="submitAdditionalAmount('{{ $transaction->id_transactions }}')"
                                                                                @click="modalOpen=false"
                                                                                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                                                                Verifikasi Transaksi
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                @endif

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
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
