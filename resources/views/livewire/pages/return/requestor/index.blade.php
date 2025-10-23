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
                        <a href="{{ route('transactions.requestor.index') }}" wire:navigate class="flex-shrink-0">
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-0">Halaman Transaksi
                                Requestor</h1>
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

                                    <a href="{{ route('transactions.requestor.create') }}" wire:navigate class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
                        rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2
                        focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Buat Request
                                    </a>


                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Rows Per Page Filter -->
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <label for="perPage" class="text-sm text-gray-700">Show</label>
                                    <select wire:model.live="perPage" id="perPage"
                                        class="block w-20 pl-3 pr-8 py-1.5 text-sm border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md bg-white">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="{{ $transactions->total() }}">All</option>
                                    </select>
                                    <span class="text-sm text-gray-700">entries</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <x-sortable-header field="id_transactions" :sortField="$sortField" :sortDirection="$sortDirection">
                                            ID Transaksi
                                        </x-sortable-header>
                                        <x-sortable-header field="action" :sortField="$sortField" :sortDirection="$sortDirection">
                                            Aksi Transaksi
                                        </x-sortable-header>
                                        <x-sortable-header field="description" :sortField="$sortField" :sortDirection="$sortDirection">
                                            Deskripsi
                                        </x-sortable-header>
                                        <x-sortable-header field="amount" :sortField="$sortField" :sortDirection="$sortDirection">
                                            Nominal Request
                                        </x-sortable-header>
                                        <x-sortable-header field="additional_amount" :sortField="$sortField" :sortDirection="$sortDirection">
                                            Dana Tambahan
                                        </x-sortable-header>
                                        <x-sortable-header field="remaining_amount" :sortField="$sortField" :sortDirection="$sortDirection">
                                            Sisa Dana
                                        </x-sortable-header>
                                        <x-sortable-header field="requested_by" :sortField="$sortField" :sortDirection="$sortDirection">
                                            Di Request oleh
                                        </x-sortable-header>

                                        <x-sortable-header field="created_at" :sortField="$sortField" :sortDirection="$sortDirection">
                                            Tanggal
                                        </x-sortable-header>

                                        <x-sortable-header field="status" :sortField="$sortField" :sortDirection="$sortDirection">
                                            Status
                                        </x-sortable-header>

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
                                                        @if ($transaction->action == 'return')
                                                        <a wire:navigate
                                                            href="{{ route('transactions.requestor.return.view', $transaction->id_transactions) }}"
                                                            class="text-sm font-medium text-gray-900 cursor-pointer group relative hover:text-blue-600">
                                                            {{ Str::limit($transaction->description, 10) }}
                                                            <span
                                                                class="tooltip-text invisible group-hover:visible absolute z-50 bg-black text-white p-2 rounded text-xs -mt-1 ml-2">
                                                                {{ $transaction->description }}
                                                            </span>
                                                        </a>
                                                        @else
                                                        <a wire:navigate
                                                            href="{{ route('transactions.requestor.view', $transaction->id_transactions) }}"
                                                            class="text-sm font-medium text-gray-900 cursor-pointer group relative hover:text-blue-600">
                                                            {{ Str::limit($transaction->description, 10) }}
                                                            <span
                                                                class="tooltip-text invisible group-hover:visible absolute z-50 bg-black text-white p-2 rounded text-xs -mt-1 ml-2">
                                                                {{ $transaction->description }}
                                                            </span>
                                                        </a>
                                                        @endif
                                                      
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
                                                            ${{ $transaction->additional_amount }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            ${{ $transaction->remaining_amount }}
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

                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <!-- View button - always shown -->
                                                @if ($transaction->action == 'return')
                                                    <button
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-1">
                                                        <a wire:navigate
                                                            href="{{ route('transactions.requestor.return.view', $transaction->id_transactions) }}">View</a>
                                                    </button>
                                                @else
                                                <button
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-1">
                                                    <a wire:navigate
                                                        href="{{ route('transactions.requestor.view', $transaction->id_transactions) }}">View</a>
                                                </button>       
                                                @endif
                                             

                                                <!-- Edit button - shown only when status is 'pending' -->
                                                @if($transaction->status == 'draft')
                                                    <button
                                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded mr-1">
                                                        <a wire:navigate href="{{ route('transactions.asman.edit', $transaction->id_transactions) }}">Edit</a>
                                                    </button>
                                                    
                                                    <button wire:click="submitConfirmation('{{ $transaction->id_transactions }}')"
                                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">
                                                        Submit
                                                    </button>

                                                @endif


                                                <!-- Close button - shown only when status is 'verified' or 'rejected' -->
                                                @if($transaction->status == 'verified' || $transaction->status == 'rejected' && $transaction->action == 'return')
                                                    <button
                                                        class="bg-blue-800 hover:bg-blue-900 text-white font-bold py-1 px-2 rounded">
                                                        <a href="{{ route('transactions.asman.return.create', ['id' => $transaction->id_transactions]) }}">Upload</a>
                                                    </button>
                                                @endif

                                                @if($transaction->status == 'verified' || $transaction->status == 'rejected' && $transaction->action == 'return')
                                                    <button
                                                        class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-1 px-2 rounded">
                                                        <a href="{{ route('transactions.asman.return.edit', [$transaction->id_transactions]) }}">Edit</a>
                                                    </button>
                                                    <button
                                                    type="button" wire:click="returnConfirmation('{{ $transaction->id_transactions }}')"
                                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">
                                                        Return
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