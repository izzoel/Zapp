<?php

namespace App\Livewire\Backend;

use App\Models\Role as ModelRole;
use App\Models\User as ModelUser;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;

class User extends Component
{
    public $editFieldRowId;
    public $index, $name, $role;

    public bool $tampil_tambah = false;

    protected $rules = [
        'name' => 'required',
        'role' => 'required',
    ];

    protected $messages = [
        'name.required' => 'Harus diisi.',
        'role.required' => 'Pilih role.',
    ];

    public function render()
    {
        $data['roles'] = ModelRole::all();
        $data['penggunas'] = ModelUser::all();
        $data['fallback'] = class_basename(ModelUser::class);

        return view('livewire.backend.user', $data);
    }

    public function tambah()
    {
        $this->tampil_tambah = !$this->tampil_tambah;
        $this->index = ModelUser::count() + 1;
    }

    public function ubah($id, $field, $value)
    {
        $data = ModelUser::find($id);

        if (!$data) {
            return;
        }

        $data->update([
            $field => $value,
        ]);

        $this->editFieldRowId = null;
        $this->dispatch('toast_success', $this->name . ' berhasil diubah.');
        $this->reset(['tampil_tambah', 'name']);
    }

    public function editRow($id, $field, $value)
    {
        $this->editFieldRowId = $id . '-' . $field;

        if ($field === 'name') {
            $this->name = $value;
        }
    }

    #[On('simpanPengguna')]
    public function simpan()
    {
        $this->validate();
        ModelUser::create([
            'name' => $this->name,
            'password' => Hash::make($this->name),
            'role' => $this->id_role,
        ]);

        $this->dispatch('toast_success', 'User ' . $this->name . ' berhasil ditambahkan.');
        $this->reset(['tampil_tambah', 'name', 'role']);
    }

    #[On('hapusPengguna')]
    public function hapus($id)
    {
        ModelUser::findOrFail($id)->delete();
        $this->dispatch('toast_success', 'User ' . $this->name . ' berhasil dihapus.');
    }
}
