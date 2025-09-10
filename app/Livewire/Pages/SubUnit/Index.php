<?php

namespace App\Livewire\Pages\SubUnit;

use Akhaled\LivewireSweetalert\Confirm;
use App\Models\SubUnit;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use Confirm;
    public $search;

    public function deleteConfirmation($id){
        
        $this->confirm(
            title: 'Apakah yakin ingin menghapus data ini', 
            html: 'Jika tidak ingin hapus klik tombol hapus', 
            event: 'deleteSubUnit', 
            options: [
                'confirmButtonText' => 'Hapus',
                'cancelButtonText' => 'Batal',
            ],
            data: ['id' => $id]
        );
       
    }

    #[On('deleteSubUnit')]
    public function delete(array $data)
    {
      
        $subUnit = SubUnit::findOrFail($data['id']);
        $subUnit->delete();
        flash()->success('Sub Unit berhasil dihapus.');
       
    }

    public function render()
    {
        $query = SubUnit::query();
    
        // Filter by search
        if (!empty($this->search)) {
            $query->where('nama_sub_unit', 'like', '%' . $this->search . '%');
        }

        $subUnit = $query->paginate(10);

        return view('livewire.pages.sub-unit.index', [
            'subUnits' => $subUnit,
        ]);
    }
}
