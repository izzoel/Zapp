@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}" />
    <style>
        .edit-icon .icon-hover {
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
            position: absolute;
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #6c757d;
        }

        .edit-icon:hover .icon-hover {
            opacity: 1;
        }
    </style>
@endpush

@php
    $aksesGrouped = \App\Models\Akses::with('role')->get()->groupBy('id_menu', 'create', 'read', 'update', 'delete');
@endphp

<div>
    <h4 class="fw-bold py-3 mb-2">
        <span class="text-muted fw-light">Setting /</span>
        {{ strtolower(Request::segment(1)) === 'livewire' ? $fallback : ucfirst(Request::segment(1)) }}
        <button wire:click="tambah" type="button" class="btn btn-xs btn-primary rounded-1"><strong>&#10010;</strong></button>
    </h4>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="p-4 table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th class="col-1">#</th>
                                <th class="col-auto text-start">Menu</th>
                                <th class="col-auto text-start">Role</th>
                                <th class="col-1">Create</th>
                                <th class="col-1">Read</th>
                                <th class="col-1">Update</th>
                                <th class="col-1">Delete</th>
                                <th class="col-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @if ($tampil_tambah)
                                <tr class="text-center" style="background-color: rgb(133 146 163 / 60%);">
                                    <td>{{ $index }}</td>
                                    <td class="text-start">
                                        <select wire:model="id_menu" class="form-select form-select-md @error('id_menu') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($menu as $m)
                                                <option value="{{ $m->id }}">{{ $m->menu }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('id_menu')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <select wire:model="id_role" class="form-select form-select-md @error('id_role') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($roles as $r)
                                                <option value="{{ $r->id }}">{{ $r->role }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('id_role')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <div class="form-check">
                                                <input wire:model="create" class="form-check-input" type="checkbox" disabled>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <div class="form-check">
                                                <input wire:model="read" class="form-check-input" type="checkbox" disabled>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <div class="form-check">
                                                <input wire:model="update" class="form-check-input" type="checkbox" disabled>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <div class="form-check">
                                                <input wire:model="delete" class="form-check-input" type="checkbox" disabled>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <button onclick="simpanAkses()" type="button" class="btn btn-sm btn-primary rounded-1">
                                            <strong>Simpan</strong>
                                        </button>
                                    </td>
                                </tr>

                            @endif
                            @foreach ($aksess as $akses)
                                <tr id="row-{{ $akses->id }}" wire:key="row-{{ $akses->id }}" class="text-center">
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="text-start">
                                        {{ $akses->menu->menu ?? '---' }}
                                    </td>

                                    <td class="text-start" x-data="{ editing: false }">
                                        <div x-show="!editing" @click="editing = true;" style="cursor:pointer; position:relative;" class="edit-icon">
                                            {{ $akses->role_names ?? '---' }}

                                            <i class="bx bx-edit-alt text-warning icon-hover"></i>
                                        </div>

                                        <div x-show="editing" x-cloak wire:ignore class="flex items-center gap-2">
                                            <select wire:key="{{ $akses->id_menu }}" data-id-menu="{{ $akses->id_menu }}" class="roles" multiple style="width:100%;"
                                                x-ref="select">
                                                @foreach ($role as $item)
                                                    <option value="{{ $item->id }}" @selected(in_array($item->id, $aksesGrouped[$akses->id_menu]->pluck('id_role')->toArray()))>
                                                        {{ $item->role }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <i class="bx bx-check text-primary ms-1" style="cursor:pointer;"
                                                @click="editing = false; $wire.saveRole({{ $akses->id_menu }},[...$refs.select.selectedOptions].map(o=>
                                                o.value));">
                                            </i>

                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                wire:change="updatePermissionGroup({{ $akses->id_menu }},@json($akses->role_ids),'create',$event.target.checked ? 1 : 0)"
                                                @checked($akses->create)>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                wire:change="updatePermissionGroup({{ $akses->id_menu }},@json($akses->role_ids),'read',$event.target.checked ? 1 : 0)"
                                                @checked($akses->read)>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                wire:change="updatePermissionGroup({{ $akses->id_menu }},@json($akses->role_ids),'update',$event.target.checked ? 1 : 0)"
                                                @checked($akses->update)>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                wire:change="updatePermissionGroup({{ $akses->id_menu }},@json($akses->role_ids),'delete',$event.target.checked ? 1 : 0)"
                                                @checked($akses->delete)>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="dropdown">
                                            <button onclick="konfirmasiHapus({{ $akses->id_menu }}, @json($akses->role_ids), @js($akses->menu->menu))" type="button"
                                                class="btn btn-sm btn-danger rounded-1">
                                                <strong>Hapus</strong>
                                            </button>
                                        </div>
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $('.tambah_roles').select2({
            allowClear: false,
            width: '100%'
        });

        $(document).ready(function() {
            if (window.location.pathname === "/akses") {
                if (!sessionStorage.getItem("aksesReloaded")) {
                    sessionStorage.setItem("aksesReloaded", "true");
                    location.reload();
                } else {
                    sessionStorage.removeItem("aksesReloaded");
                }
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('refresh', function(message) {
                localStorage.setItem('afterReload', 'true');
                localStorage.setItem('toastMessage', message);
                location.reload();
            });
        });

        window.addEventListener('load', function() {
            if (localStorage.getItem('afterReload') === 'true') {
                localStorage.removeItem('afterReload');

                const msg = localStorage.getItem('toastMessage');
                localStorage.removeItem('toastMessage');

                Livewire.dispatch('toast', {
                    message: msg
                });
            }
        });

        document.addEventListener('livewire:init', function() {
            $('.roles').each(function() {
                let idMenu = $(this).data('id-menu');
                $(this).select2({
                    allowClear: false,
                    width: '100%'
                })
            })
        });
        document.addEventListener('DOMContentLoaded', function() {
            window.Livewire.on('toast_success', function(message) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: message
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            window.Livewire.on('toast_error', function(message) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                Toast.fire({
                    icon: 'error',
                    title: message
                });
            });
        });

        function simpanAkses(idMenu) {
            let selectedRoles = $('#select-' + idMenu).val() || [];
            Livewire.dispatch('simpanAkses', idMenu, selectedRoles);
        }

        function konfirmasiHapus(menuId, roleIds, menuName) {
            Swal.fire({
                title: 'Konfirmasi hapus',
                text: "Anda yakin ingin menghapus akses untuk menu " + menuName + "?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('hapusAkses', {
                        menu_id: menuId,
                        role_ids: roleIds
                    });

                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: "Akses untuk " + menuName + " telah dihapus.",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            });
        }
    </script>
@endpush
