<?php

namespace App\Livewire\Backend;

use App\Models\Role as ModelRole;
use App\Models\User as ModelUser;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;

class User extends Component
{
    use WithFileUploads;

    public $editFieldRowId, $customAvatar, $avatarBaru, $selectedUserId;
    public $modeTambah = false;
    public $index, $fallback, $avatars, $name, $role;

    public bool $tampil_tambah = false;

    protected $rules = [
        'name' => 'required',
        'role' => 'required',
        'customAvatar' => 'nullable|image|max:2048',
    ];

    protected $messages = [
        'name.required' => 'Harus diisi.',
        'role.required' => 'Pilih role.',
        'customAvatar.image' => 'File harus berupa gambar (jpeg, png, dll).',
        'customAvatar.max' => 'Ukuran gambar maksimal 2 MB.',
    ];

    public function mount()
    {
        $this->fallback = class_basename(ModelUser::class);
        for ($i = 0; $i <= 9; $i++) {
            $this->avatars[] = $i . '.png';
        }
    }

    public function render()
    {
        $data['avatars'] = $this->avatars;
        $data['fallback'] = $this->fallback;
        $data['penggunas'] = ModelUser::all();
        $data['roles'] = ModelRole::all();

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

        if ($field === 'password') {
            $data->update([
                'password' => Hash::make($value),
            ]);
            $this->dispatch('toast_success', 'Password berhasil diubah');
        } else {
            $data->update([
                $field => $value,
            ]);
            $this->dispatch('toast_success', $this->name . ' berhasil diubah');
            $this->reset(['tampil_tambah', 'name']);
        }

        $this->editFieldRowId = null;
    }

    public function ubahAvatar($avatar)
    {
        if ($this->selectedUserId) {
            $data = ModelUser::find($this->selectedUserId);
            $data->update([
                'avatar' => $avatar,
            ]);
        } else {
            $this->avatarBaru = $avatar;
            $this->dispatch('close_modal');
            return;
        }

        $this->dispatch('toast_success', 'Avatar berhasil diubah.');
        $this->dispatch('close_modal');
    }

    public function updatedCustomAvatar()
    {
        $this->validateOnly('customAvatar');

        // Simpan file ke storage/public/img/avatars
        $filename = 'custom-' . auth()->user()->name . '.' . $this->customAvatar->getClientOriginalExtension();
        $this->customAvatar->storeAs('img/avatars/' . auth()->user()->name, $filename, 'public');

        $data = ModelUser::find(auth()->user()->id);
        $data->update([
            'avatar' => $filename,
        ]);

        $this->dispatch('toast_success', 'Avatar berhasil diubah.');
        $this->dispatch('close_modal');
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
            'avatar' => $this->avatarBaru ?? '0.png',
            'name' => $this->name,
            'password' => Hash::make($this->name),
            'id_role' => $this->role,
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
