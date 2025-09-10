<?php

namespace App\Livewire\Pages\Roles;

use Livewire\Component;

class Create extends Component
{
    public $name;
    public $guard_name = 'web';

    public function save(){
        try{
            $this->validate([
                'name' => 'required|string|max:255|unique:roles,name',
                'guard_name' => 'required|string|max:255',
            ]);

            \Spatie\Permission\Models\Role::create([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);

            flash()->success('Role berhasil dibuat.');
            return $this->redirect(route('roles.index'), navigate: true);
        } catch (\Exception $e) {
            flash()->error('Gagal membuat role: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire..pages.roles.create');
    }
}
