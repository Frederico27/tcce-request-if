<?php

namespace App\Livewire\Pages\Users;

use Akhaled\LivewireSweetalert\Confirm;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination;
    use Confirm;
    public $search;
    public $statusFilter;
    public $roleFilter;

    // Sorting and pagination properties
    public $perPage = 10;
    public $sortField = 'full_name';
    public $sortDirection = 'asc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteConfirmation($id)
    {

        $this->confirm(
            title: 'Apakah yakin ingin menghapus data ini',
            html: 'Jika tidak ingin hapus klik tombol batal',
            event: 'deleteUser',
            options: [
                'confirmButtonText' => 'Hapus',
                'cancelButtonText' => 'Batal',
            ],
            data: ['id' => $id]
        );
    }

    #[On('deleteUser')]
    public function delete(array $data)
    {

        $user = User::findOrFail($data['id']);
        $user->roles()->detach();
        $user->permissions()->detach();
        $user->delete();
        flash()->success('Pengguna berhasil dihapus.');
    }

    public function render()
    {
        $query = User::query();

        // Filter by search
        if (!empty($this->search)) {
            $query->where('full_name', 'like', '%' . $this->search . '%');
        }

        // Filter by status
        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->roleFilter)) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $users = $query->paginate($this->perPage);


        return view('livewire.pages.users.index', [
            'users' => $users,
            'roles' => Role::get('name'), // Get only names
        ]);
    }
}
