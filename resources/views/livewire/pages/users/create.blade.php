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
                        <a href="{{ route('users.index') }}" wire:navigate class="mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 hover:text-gray-700"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <h1 class="text-2xl font-bold text-gray-900">Buat Pengguna Baru</h1>
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
                                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                     <!-- Name -->
                                    <div class="sm:col-span-3">
                                        <label for="full_name"
                                            class="block text-sm font-medium text-gray-700">Nama</label>
                                        <div class="w-full max-w-sm min-w-[200px]">
                                            <input type="text" wire:model="full_name" id="full_name"
                                                autocomplete="full_name" class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow
        @error('full_name') 
            border-red-300 text-red-900 placeholder-red-300 focus:border-red-500
        @enderror" placeholder="Masukan nama pengguna">
                                        </div>
                                        @error('full_name')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- NIK -->
                                    <div class="sm:col-span-3">
                                        <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                                        <div class="w-full max-w-sm min-w-[200px]">
                                            <input type="text" wire:model="nik" id="nik" autocomplete="nik" class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow
                                                @error('nik') 
                                                    border-red-300 text-red-900 placeholder-red-300 focus:border-red-500
                                                @enderror" placeholder="Masukan NIK">
                                        </div>
                                        @error('nik')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Positon Name -->
                                    <div class="sm:col-span-3">
                                        <label for="position_name" class="block text-sm font-medium text-gray-700">Posisi</label>
                                        <div class="w-full max-w-sm min-w-[200px]">
                                            <input type="text" wire:model="position_name" id="position_name"
                                                autocomplete="position_name" class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow
                                                @error('position_name') 
                                                    border-red-300 text-red-900 placeholder-red-300 focus:border-red-500
                                                @enderror" placeholder="Masukan Posisi Sekarang">
                                        </div>
                                        @error('position_name')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="sm:col-span-3">
                                        <label for="nik" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                        <div class="w-full max-w-sm min-w-[200px]">
                                            <input type="text" wire:model="phone_number" id="phone_number"
                                                autocomplete="phone_number" class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow
                                                @error('phone_number') 
                                                    border-red-300 text-red-900 placeholder-red-300 focus:border-red-500
                                                @enderror" placeholder="Masukan Nomor Telepon">
                                        </div>
                                        @error('phone_number')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Role Selection -->
                                    <div class="sm:col-span-3">
                                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                                                                               <div class="w-full max-w-sm min-w-[200px]">
                                            <select wire:model="role" id="role"
                                                class="w-full bg-transparent text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow
                                                @error('role') 
                                                    border-red-300 text-red-900 focus:border-red-500
                                                @enderror">
                                                <option value="">Pilih Role</option>
                                                @forelse ($roles as $role)
                                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                                @empty
                                                    <option value="">Tidak ada role</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        @error('role')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div class="sm:col-span-3">
                                        <label for="status"
                                            class="block text-sm font-medium text-gray-700">Status</label>
                                                                                <div class="w-full max-w-sm min-w-[200px]">
                                            <select wire:model="status" id="status"
                                                class="w-full bg-transparent text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow
                                                @error('status') 
                                                    border-red-300 text-red-900 focus:border-red-500
                                                @enderror">
                                                <option value="">Pilih Status</option>
                                                <option value="active">Active</option>
                                                <option value="unactive">Unactive</option>
                                            </select>
                                        </div>
                                        @error('status')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Sub-unit -->
                                    <div class="sm:col-span-3" x-data="{}" x-show="$wire.role !== 'asman' && $wire.role !== 'superadmin'">
                                        <label for="status"
                                            class="block text-sm font-medium text-gray-700">Sub Unit</label>
                                                                                <div class="w-full max-w-sm min-w-[200px]">
                                            <select wire:model="id_sub_unit" id="id_sub_unit"
                                                class="w-full bg-transparent text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow
                                                @error('id_sub_unit') 
                                                    border-red-300 text-red-900 focus:border-red-500
                                                @enderror">
                                                <option value="">Pilih Sub Unit</option>
                                                @forelse ($subUnits as $subUnit)
                                                    <option value="{{ $subUnit->id_sub_unit }}">{{ $subUnit->nama_sub_unit }}</option>
                                                @empty
                                                    <option value="">Tidak ada sub unit</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        @error('id_sub_unit')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                     <!-- Admin Selection (shown only when role is asman) -->
                                     <div class="sm:col-span-3" x-data="{}" x-show="$wire.role === 'asman'">
                                        <label for="admin_id" 
                                            class="block text-sm font-medium text-gray-700">Admin</label>
                                        <div class="w-full max-w-sm min-w-[200px]">
                                            <select wire:model="admin_id" id="admin_id"
                                                class="w-full bg-transparent text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow
                                                @error('admin_id') 
                                                    border-red-300 text-red-900 focus:border-red-500
                                                @enderror">
                                                <option value="">Pilih Admin</option>
                                                @foreach($admins as $admin)
                                                    <option value="{{ $admin->id }}">{{ $admin->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('admin_id')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    

                                </div>

                                <!-- Form Actions -->
                                <div class="mt-6 flex justify-end">
                                    <a href="{{ route('users.index') }}" wire:navigate
                                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Batalkan
                                    </a>
                                    <button type="submit"
                                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                         Buat Pengguna
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