<div>
    <!-- Main Container -->
    <div class="flex min-h-screen bg-gray-50">
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header with Breadcrumbs -->
            <header class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 py-4 shadow-sm sticky top-0 z-10">
                <!-- Page Title -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ route('roles.index') }}" wire:navigate>
                        <h1 class="text-2xl font-bold text-gray-900 mb-4 sm:mb-0">Manajemen Role</h1>
                    </a>
                    <a href="{{ route('roles.create') }}" wire:navigate class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
                        rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2
                        focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Role
                    </a>
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
                                        placeholder="Cari role..."
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Table Header (visible on larger screens) -->
                        <div class="hidden sm:block">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nama Role
                                            </th>

                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="table-body">
                                        @forelse ($roles as $role)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <!-- <div
                                                            class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                            <span
                                                                class="text-gray-600 font-medium">{{ substr($role->name, 0, 1) }}</span>
                                                        </div> -->
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $role->name }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>


                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('roles.edit', $role->id) }}" wire:navigate
                                                        class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-50 transition-colors inline-block"
                                                        title="Edit User">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>

                                                    <a wire:click="deleteConfirmation({{ $role->id }})"
                                                        class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-50 transition-colors inline-block ml-2"
                                                        title="Delete User">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </a>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                                    Tidak ada pengguna yang tersedia.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mobile Card Layout -->
                        <div class="sm:hidden">
                            @forelse ($roles as $role)
                                <div class="p-4 border-b border-gray-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <!-- <div
                                                class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-gray-600 font-medium">{{ substr($role->name, 0, 1) }}</span>
                                            </div> -->
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('roles.edit', $role->id) }}" wire:navigate
                                                class="text-blue-600 hover:text-blue-900 text-sm px-2 py-1 rounded hover:bg-blue-50 transition-colors">Edit</a>
                                            <a wire:click="deleteConfirmation({{ $role->id }})"
                                                class="text-red-600 hover:text-red-900 text-sm px-2 py-1 rounded hover:bg-red-50 transition-colors">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 p-6">
                                    Tidak ada pengguna yang tersedia.
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination Laravel -->
                        <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                            <div class="flex items-center justify-between flex-col sm:flex-row gap-4">
                                <div class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $roles->firstItem() ?? 0 }}</span>
                                    to
                                    <span class="font-medium">{{ $roles->lastItem() ?? 0 }}</span>
                                    of
                                    <span class="font-medium">{{ $roles->total() }}</span>
                                    results
                                </div>
                                <div>
                                    {{ $roles->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>