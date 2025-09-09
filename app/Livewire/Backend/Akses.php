<?php

namespace App\Livewire\Backend;

use App\Models\Akses as ModelAkses;
use App\Models\Menu as ModelMenu;
use App\Models\Role as ModelRole;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;

class Akses extends Component
{
    public $editFieldRowId;
    public $index,
        $akses,
        $aksesList,
        $roles,
        $id_menu,
        $id_role = [];
    public $create = 0,
        $read = 0,
        $update = 0,
        $delete = 0;
    public bool $tampil_tambah = false;

    protected $rules = [
        'id_menu' => 'required',
        'id_role' => 'required',
    ];

    protected $messages = [
        'id_menu.required' => 'Pilih opsi.',
        'id_role.required' => 'Pilih role.',
    ];

    public function mount()
    {
        $this->aksesList = ModelAkses::with('role')->get();
        $this->roles = ModelRole::all();
    }

    public function render()
    {
        $data['aksess'] = ModelAkses::select('akses.id_menu', 'akses.create', 'akses.read', 'akses.update', 'akses.delete')
            ->selectRaw('GROUP_CONCAT(DISTINCT roles.role) as role_names')
            ->selectRaw('GROUP_CONCAT(akses.id_role) as role_ids')
            ->join('roles', 'roles.id', '=', 'akses.id_role')
            ->groupBy('akses.id_menu', 'akses.create', 'akses.read', 'akses.update', 'akses.delete')
            ->with('menu')
            ->get()
            ->map(function ($item) {
                $item->role_ids = $item->role_ids ? array_map('intval', explode(',', $item->role_ids)) : [];
                return $item;
            });

        $data['akses'] = ModelAkses::with('role')->get();
        $data['menu'] = ModelMenu::whereNotNull('parent_id')->get();
        $data['role'] = ModelRole::all();
        $data['fallback'] = class_basename(ModelAkses::class);

        return view('livewire.backend.akses', $data);
    }

    public function tambah()
    {
        $this->tampil_tambah = !$this->tampil_tambah;
        $this->index = ModelAkses::select('id_menu')->selectRaw('GROUP_CONCAT(id_role) as roles')->groupBy('id_menu')->with('menu')->count();
    }

    public function ubah($id, $field, $value)
    {
        $data = ModelAkses::find($id);

        if (!$data) {
            return;
        }

        $data->update([
            $field => $value,
        ]);

        $this->editFieldRowId = null;
        $this->reset(['tampil_tambah', 'id_role']);
    }

    #[On('simpanAkses')]
    public function simpanAkses()
    {
        $this->validate();

        $menu = ModelMenu::find($this->id_menu)?->menu;
        $role = ModelRole::find($this->id_role)?->role;

        $akses = ModelAkses::where('id_menu', $this->id_menu)->where('id_role', $this->id_role);

        if ($akses->exists()) {
            $this->dispatch('toast_error', $menu . ' dengan role ' . $role . ' sudah ada!');
            $this->reset(['tampil_tambah', 'id_menu', 'id_role']);
            return;
        }

        ModelAkses::create([
            'id_menu' => $this->id_menu,
            'id_role' => $this->id_role,
            'create' => 0,
            'read' => 0,
            'update' => 0,
            'delete' => 0,
        ]);

        $this->reset(['tampil_tambah', 'id_menu', 'id_role']);
        $this->dispatch('refresh', $menu . ' dengan role ' . $role . ' berhasil ditambah!');
    }

    #[On('toast')]
    public function toast($message)
    {
        $this->dispatch('toast_success', $message);
    }

    public function editRow($id, $field, $value)
    {
        $this->editFieldRowId = $id . '-' . $field;

        if ($field === 'id_menu') {
            $this->id_menu = $value;
        }
        if ($field === 'id_role') {
            $this->id_role = $value;
        }
    }

    #[On('saveRole')]
    public function saveRole($menuId, array $roleIds)
    {
        $existingMenu = ModelAkses::where('id_menu', $menuId)->get();
        $existingRoles = $existingMenu->pluck('id_role')->toArray();

        $crudDefaults = [
            'create' => 0,
            'read' => 0,
            'update' => 0,
            'delete' => 0,
        ];

        if ($existingMenu->isNotEmpty()) {
            $crudDefaults = $existingMenu->first()->only(['create', 'read', 'update', 'delete']);
        }

        foreach ($roleIds as $roleId) {
            if (!in_array($roleId, $existingRoles)) {
                ModelAkses::create(
                    array_merge(
                        [
                            'id_menu' => $menuId,
                            'id_role' => $roleId,
                        ],
                        $crudDefaults,
                    ),
                );
            }
        }
        ModelAkses::where('id_menu', $menuId)->whereNotIn('id_role', $roleIds)->delete();
        $this->dispatch('toast_success', 'Akses berhasil diupdate!');
    }

    public function updatePermissionGroup($menuId, $roleIds, $field, $value)
    {
        if (!in_array($field, ['create', 'read', 'update', 'delete'])) {
            return;
        }

        $roleIds = is_array($roleIds) ? $roleIds : [$roleIds];

        ModelAkses::where('id_menu', $menuId)
            ->whereIn('id_role', $roleIds)
            ->update([
                $field => $value ? 1 : 0,
            ]);

        $this->dispatch('toast_success', 'Akses berhasil diupdate!');
    }

    //  #[On('hapusAkses')]
    // public function hapus($id)
    // {
    //     ModelAkses::findOrFail($id)->delete();
    //     $this->dispatch('toast_success', 'Akses berhasil dihapus.');
    // }

    #[On('hapusAkses')]
    public function hapus($menu_id, $role_ids)
    {
        ModelAkses::where('id_menu', $menu_id)->whereIn('id_role', $role_ids)->delete();
    }
}
