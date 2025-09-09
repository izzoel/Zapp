<?php

namespace App\Livewire\Backend;

use App\Models\Role as ModelRole;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;

class Role extends Component
{
    public $editFieldRowId;
    public $index, $role;

    public bool $tampil_tambah = false;

    protected $rules = [
        'role' => 'required',
    ];

    protected $messages = [
        'role.required' => 'Harus diisi.',
    ];

    public function render()
    {
        $data['roles'] = ModelRole::all();
        $data['fallback'] = class_basename(ModelRole::class);
        return view('livewire.backend.role', $data);
    }

    public function tambah()
    {
        $this->tampil_tambah = !$this->tampil_tambah;
        $this->index = ModelRole::count() + 1;
    }

    public function ubah($id, $field, $value)
    {
        $data = ModelRole::find($id);

        if (!$data) {
            return;
        }

        $data->update([
            $field => $value,
        ]);

        $this->editFieldRowId = null;
        $this->dispatch('toast_success', $this->role . ' berhasil diubah.');
        $this->reset(['tampil_tambah', 'role']);
    }

    public function editRow($id, $field, $value)
    {
        $this->editFieldRowId = $id . '-' . $field;

        if ($field === 'role') {
            $this->role = $value;
        }
    }

    #[On('simpanRole')]
    public function simpan()
    {
        $this->validate();
        ModelRole::create([
            'role' => $this->role,
        ]);

        $this->dispatch('toast_success', 'Role ' . $this->role . ' berhasil ditambahkan.');
        $this->reset(['tampil_tambah', 'role']);
    }

    #[On('hapusRole')]
    public function hapus($id)
    {
        ModelRole::findOrFail($id)->delete();
        $this->dispatch('toast_success', 'Role ' . $this->role . ' berhasil dihapus.');
    }
}
