<?php

namespace App\Livewire\Pages\Roles;

use Akhaled\LivewireSweetalert\Confirm;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Index extends Component
{

    use WithPagination;
    use Confirm;

    public $search;
    public $roleFilter;
    
    public function deleteConfirmation($id){
        
        $this->confirm(
            title: 'Apakah yakin ingin menghapus data ini', 
            html: 'Jika tidak ingin hapus klik tombol hapus', 
            event: 'deleteRole', 
            options: [
                'confirmButtonText' => 'Hapus',
                'cancelButtonText' => 'Batal',
            ],
            data: ['id' => $id]
        );
       
    }

    #[On('deleteRole')]
    public function delete(array $data)
    {
        $role = Role::findOrFail($data['id']);
        $role->delete();
        flash()->success('Role berhasil dihapus.');   
    }

    public function render()
    {
        $query = Role::query();
    
        // Filter by search
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $role = $query->paginate(10);

        return view('livewire.pages.roles.index', [
            'roles' => $role
        ]);
    }
}
