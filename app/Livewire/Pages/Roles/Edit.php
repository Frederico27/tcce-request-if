<?php

namespace App\Livewire\Pages\Roles;

use App\Models\Role;
use Livewire\Component;

class Edit extends Component
{
    public $name;
    public $guard_name = 'web';
    public $roleId;
    public $role;

    public function mount(){
        $this->role = Role::findOrFail($this->roleId);
        if (!$this->role) {
            flash()->error('Role tidak ditemukan.');
            return;
        }

        $this->name = $this->role->name;

    }

    public function update(){
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
            ]);

            // Update the role
            $this->role->update([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);

            flash()->success('Role berhasil diupdate.');
            return $this->redirect(route('roles.index'), navigate: true);
        } catch (\Exception $e) {
            flash()->error('Gagal mengupdate role: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.roles.edit', [
        ]);
    }
}
