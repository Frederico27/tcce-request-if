<?php

namespace App\Livewire\Pages\Users;

use App\Models\SubUnit;
use App\Models\User;
use DB;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    public $userId;
    public $nik, $full_name, $position_name, $phone_number, $id_sub_unit, $status, $admin_id;
    public $role; // Default to 'user' if no role is assigned
    public $userRole; // To store the user's current role

    public function mount($userId)
    {
        $user = User::findOrFail($userId);

        $this->userId = $userId;
        $this->nik = $user->nik;
        $this->full_name = $user->full_name;
        $this->id_sub_unit = $user->id_sub_unit;
        $this->position_name = $user->position_name;
        $this->phone_number = $user->phone_number;
        $this->status = $user->status ?? 'active';
        $this->userRole = $user->getRoleNames()->first(); // Get the first role assigned to the user
        $this->role = $user->getRoleNames()->first() ?? 'superadmin'; // Default to 'user' if no role is assigned
    }

    public function update()
    {
        try {
            // Validate input
            $this->validate([
                'nik' => 'required|string|max:20|unique:users,nik,' . $this->userId,
                'full_name' => 'required|string|max:255',
                'position_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:15',
                'id_sub_unit' => 'required|integer',
                'status' => 'required|in:active,unactive',
            ]);

            DB::beginTransaction();
            // Find and update user
            $user = User::findOrFail($this->userId);
            $user->nik = $this->nik;
            $user->full_name = $this->full_name;
            $user->position_name = $this->position_name;
            $user->phone_number = $this->phone_number;

            if(!empty($this->id_sub_unit)) {
                $user->id_sub_unit = null;
            }
            $user->id_sub_unit = !empty($this->admin_id)
                ? User::findOrFail($this->admin_id)->id_sub_unit
                : $this->id_sub_unit;
            $user->status = $this->status;
            $user->save();

            // Handle admin-asman relationship if needed
            if (!empty($this->admin_id)) {
                // Check if the admin-asman relationship already exists
                $adminAsman = $user->asmanOf()->first();
                //ensure admin not related with himself if update to asman
                if ($this->admin_id == $user->id) {
                    flash()->error('Admin tidak dapat menjadi asman dirinya sendiri.');
                    return null;
                }

                if ($adminAsman) {
                    // Update existing relationship
                    $adminAsman->id_admin = $this->admin_id;
                    $adminAsman->save();
                } else {
                    // Create new relationship
                    $user->adminAsman()->create([
                        'id_admin' => $this->admin_id,
                        'id_asman' => $user->id,
                    ]);
                }
            } else {
                // If no admin is selected, ensure the relationship is removed
                $user->asmanOf()->delete();
            }

            // Role management
            try {
                // Detach all roles and reassign the selected role
                $user->roles()->detach();
                if ($this->role) {
                    $user->assignRole($this->role);
                }

                // Detach all permissions
                $user->permissions()->detach();
                // Optionally, you can assign permissions here if needed

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            flash()->success('Berhasil Edit data Pengguna!');
            return $this->redirect(route('users.index'), navigate: true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            $this->dispatch('validation-error', ['message' => 'Validasi gagal. Silakan periksa kembali data yang dimasukkan.']);
            throw $e;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle user not found
            \Log::error('User not found: ' . $this->userId);
            flash()->error('Pengguna tidak ditemukan.');
            return $this->redirect(route('users.index'), navigate: true);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Failed to update user: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            // Show error message to user
            flash()->error('Gagal mengubah data pengguna: ' . $e->getMessage());
            return $this->redirect(route('users.index'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.pages.users.edit', [
            'roles' => Role::get(['id', 'name']), // Get only id and name
            'subUnits' => SubUnit::get(['id_sub_unit', 'nama_sub_unit']),
            'admins' => User::role('admin')->select('id', 'full_name')->get(),
        ]);
    }
}
