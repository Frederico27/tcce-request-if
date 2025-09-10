<?php

namespace App\Livewire\Pages\Users;

use App\Models\AdminAsman;
use App\Models\SubUnit;
use App\Models\User;
use DB;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    public $nik, $full_name, $position_name, $phone_number, $id_sub_unit, $role, $status, $admin_id;

    public function save()
    {
        $this->validate([
            'nik' => 'required|unique:users,nik|numeric',
            'full_name' => 'required|string',
            'position_name' => 'required|string',
            'phone_number' => 'required|string|max:15',
            'status' => 'required|in:active,unactive',
            'id_sub_unit' => 'integer|nullable',
            'role' => 'required|exists:roles,name',
        ]);

        try {
            DB::beginTransaction();

            // Prepare user data
            $userData = [
                'nik' => $this->nik,
                'full_name' => $this->full_name,
                'position_name' => $this->position_name,
                'phone_number' => $this->phone_number,
                'status' => $this->status,
                'id_sub_unit' => !empty($this->admin_id)
                    ? User::findOrFail($this->admin_id)->id_sub_unit
                    : $this->id_sub_unit,
            ];

            // Create the user
            if($this->role === 'asman' && empty($this->admin_id)) {
                flash()->error('Admin is required for Asman role.');
                return null;
            }

            //create user
            $user = User::create($userData);

            // Create admin-asman relationship if needed
            if (!empty($this->admin_id)) {
                AdminAsman::create([
                    'id_admin' => $this->admin_id,
                    'id_asman' => $user->id,
                ]);
            }

            // Assign role
            $user->assignRole($this->role);

            DB::commit();

            flash()->success('Berhasil Menambah Pengguna!');
            return $this->redirect(route('users.index'), navigate: true);

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Gagal menambah pengguna: ' . $e->getMessage());
            return null;
        }
    }
    public function render()
    {
        //sub units value
        $subUnits = SubUnit::get(['id_sub_unit', 'nama_sub_unit']);
        //roles value
        $roles = Role::get(['id', 'name']);

        return view('livewire..pages.users.create', [
            'subUnits' => $subUnits,
            'roles' => $roles,
            'admins' => User::role('admin')->select('id', 'full_name')->get(),
        ]);
    }
}
