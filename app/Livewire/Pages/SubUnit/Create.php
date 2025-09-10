<?php

namespace App\Livewire\Pages\SubUnit;

use Livewire\Component;

class Create extends Component
{
    public $name;

    public function save(){
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:sub_unit,nama_sub_unit',
            ]);

            \App\Models\SubUnit::create([
                'nama_sub_unit' => $this->name,
            ]);

            flash()->success('Sub Unit berhasil dibuat.');
            return $this->redirect(route('sub-units.index'), navigate: true);
        } catch (\Exception $e) {
            flash()->error('Gagal membuat Sub Unit: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.pages.sub-unit.create');
    }
}
