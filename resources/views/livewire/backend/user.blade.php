@push('css')
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

        /* Lingkaran upload dasar */
        .upload-plus {
            /* background-color: #0d6efd; */
            /* Biru Bootstrap */
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .avatar-option {
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        /* Hover: membesar dan bayangan */
        .avatar-option:hover {
            transform: scale(1.08);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-color: #0d6efd;
            /* Biru Bootstrap */
        }

        /* Active (klik): mengecil sedikit */
        .avatar-option:active {
            transform: scale(0.95);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) inset;
        }
    </style>
@endpush

<div>
    <h4 class="fw-bold py-3 mb-2">
        <span class="text-muted fw-light">Setting /</span>
        {{ strtolower(Request::segment(1)) === 'livewire' ? $data['fallback'] : ucfirst(Request::segment(1)) }}
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
                                <th class="col-1">Avatar</th>
                                <th class="col-auto text-start">Username</th>
                                <th class="col-2 text-start">Role</th>
                                <th class="col-2">Password</th>
                                <th class="col-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @if ($tampil_tambah)
                                <tr class="text-center" style="background-color: rgb(133 146 163 / 60%);">
                                    <td>{{ $index }}</td>
                                    <td><img src="" alt=""></td>
                                    <td class="text-start">
                                        <input wire:model="name" type="text"
                                            class="form-control form-control-md {{ $errors->has('name') ? 'is-invalid' : 'border border-secondary' }}" placeholder="username..">

                                        <div class="invalid-feedback">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <div class="d-flex justify-content-center">
                                            <div class="w-100">
                                                <select wire:model="role" class="form-select form-select-sm {{ $errors->has('role') ? 'is-invalid' : '' }}">
                                                    <option value="">Pilih role</option>
                                                    @foreach ($roles as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ ucfirst(str_replace('_', ' ', $item->role)) }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <div class="invalid-feedback">
                                                    @error('role')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input wire:model="name" type="text"
                                            class="form-control form-control-md {{ $errors->has('name') ? 'is-invalid' : 'border border-secondary' }}" placeholder="username..">

                                        <div class="invalid-feedback">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </td>
                                    <td>
                                        <button onclick="simpanPengguna()" type="button" class="btn btn-sm btn-primary rounded-1">
                                            <strong>Simpan</strong>
                                        </button>
                                    </td>
                                </tr>
                            @endif

                            @foreach ($data['penggunas'] as $pengguna)
                                <tr class="text-center">
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <div data-bs-toggle="modal" data-bs-target="#avatarModal" style="cursor: pointer;">
                                            <img src="{{ asset((Str::contains($pengguna->avatar, 'custom-') ? 'storage/img/avatars/' . $pengguna->name . '/' : 'img/avatars/') . $pengguna->avatar) }}"
                                                alt="avatar-{{ $pengguna->avatar }}" class="w-px-40 h-auto rounded-circle">
                                        </div>
                                    </td>
                                    <td class="text-start" x-data>
                                        @if ($editFieldRowId == $pengguna->id . '-name')
                                            <div class="d-flex justify-content-center">
                                                <input wire:blur="ubah('{{ $pengguna->id }}', 'name', $event.target.value)"
                                                    wire:keydown.enter="ubah('{{ $pengguna->id }}', 'name', $event.target.value)" class="form-control form-control-sm"
                                                    value="{{ $pengguna->name }}" @click.outside="$wire.set('editFieldRowId', null)" />
                                            </div>
                                        @else
                                            <div wire:click="editRow('{{ $pengguna->id }}', 'name', '{{ $pengguna->name }}')" class="edit-icon"
                                                style="cursor: pointer; position: relative;">
                                                {{ $pengguna->name ?? '---' }}
                                                <i class="bx bx-edit-alt text-warning icon-hover"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-start">
                                        @if ($editFieldRowId == $pengguna->id . '-id_role')
                                            <div class="d-flex justify-content-center">
                                                <select wire:blur="ubah('{{ $pengguna->id }}', 'id_role', $event.target.value)"
                                                    wire:keydown.enter="ubah('{{ $pengguna->id }}', 'id_role', $event.target.value)" class="form-select form-select-sm">
                                                    @foreach ($data['roles'] as $role)
                                                        <option value="{{ $role->id }}" {{ $pengguna->id_role === $role->id ? 'selected' : '' }}>
                                                            {{ ucfirst(str_replace('_', ' ', $role->role)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <div wire:click="editRow('{{ $pengguna->id }}', 'id_role', '{{ $pengguna->role->role }}')" class="edit-icon"
                                                style="cursor: pointer; position: relative;">
                                                {{ $pengguna->role->role ?? '---' }}
                                                <i class="bx bx-edit-alt text-warning icon-hover"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($editFieldRowId == $pengguna->id . '-password')
                                            <div class="d-flex justify-content-center">
                                                <input type="text" class="form-control form-control-sm" wire:blur="ubah('{{ $pengguna->id }}', 'password', $event.target.value)"
                                                    wire:keydown.enter="ubah('{{ $pengguna->id }}', 'password', $event.target.value)" value=""
                                                    placeholder="Masukkan password baru..." @click.outside="$wire.set('editFieldRowId', null)" />
                                            </div>
                                        @else
                                            {{-- Tombol reset --}}
                                            <div>
                                                <button type="button" class="btn btn-sm btn-primary rounded-1"
                                                    wire:click="editRow('{{ $pengguna->id }}', 'password', '{{ $pengguna->password }}')">
                                                    <strong>Reset</strong>
                                                </button>
                                            </div>
                                        @endif


                                    </td>
                                    <td>
                                        <div>
                                            <button onclick="konfirmasiHapus({{ $pengguna->id }}, '{{ $pengguna->name }}')" type="button"
                                                class="btn btn-sm btn-danger rounded-1"><strong>Hapus</strong></button>
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

    <div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="avatarModalLabel">Pilih Avatar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        @foreach ($data['avatars'] as $avatar)
                            <img src="{{ asset('img/avatars/' . $avatar) }}" alt="avatar-{{ $avatar }}" class="rounded-circle border border-2 p-1 avatar-option"
                                style="width:60px; cursor:pointer" wire:click="ubahAvatar('{{ $avatar }}')">
                        @endforeach
                        <span class="upload-plus avatar-option rounded-circle border border-2 bg-primary mt-2"
                            style="display:inline-block; width:60px; height:60px; line-height:53px; text-align:center; cursor:pointer;"
                            onclick="document.getElementById('customAvatar').click()">
                            <i class="bx bx-plus fs-3 text-white" style="vertical-align:middle"></i>
                        </span>
                        <input type="file" id="customAvatar" wire:model="customAvatar" accept="image/*" hidden>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



@push('js')
    <script>
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

            window.Livewire.on('close_modal', function(message) {
                $('#avatarModal').modal('hide');
            });
        });


        function simpanPengguna() {
            Livewire.dispatch('simpanPengguna');
        }


        function konfirmasiHapus(id, pengguna) {
            Swal.fire({
                title: 'Konfirmasi hapus',
                text: "Anda yakin ingin menghapus " + pengguna + " ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('hapusPengguna', {
                        id: id
                    });
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });

                    Toast.fire({
                        icon: "success",
                        title: pengguna + " telah dihapus."
                    });
                }
            });
        }
    </script>
@endpush
