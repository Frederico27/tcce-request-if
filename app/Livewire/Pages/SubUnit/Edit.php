<?php

namespace App\Livewire\Pages\SubUnit;

use App\Models\SubUnit;
use Livewire\Component;

class Edit extends Component
{

    public $name;
    public $subUnit;
    public $subUnitId;

    public function mount(){
        $this->subUnit = SubUnit::findOrFail($this->subUnitId);
        if (!$this->subUnit) {
            flash()->error('Sub Unit tidak ditemukan.');
            return;
        }

        $this->name = $this->subUnit->nama_sub_unit;
    }

    public function update(){

        try {
            $this->validate([
                'name' => 'required|string|max:255',
            ]);

            // Update the sub unit
            $this->subUnit->update([
                'nama_sub_unit' => $this->name,
            ]);

            flash()->success('Sub Unit berhasil diupdate.');
            return $this->redirect(route('sub-units.index'), navigate: true);
        } catch (\Exception $e) {
            flash()->error('Gagal mengupdate sub unit: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.sub-unit.edit');
    }
}
