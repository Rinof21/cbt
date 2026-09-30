@extends('layouts.app')
@section('title', 'Edit Pengguna: ' . $user->name)
@section('subtitle', 'Ubah data profil, role, dan hak akses pengguna')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8">
        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-6">Edit Pengguna: <span class="text-blue-600">{{ $user->name }}</span></h3>

        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="user-name" value="{{ old('name', $user->name) }}"
                        class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        required>
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="user-email" value="{{ old('email', $user->email) }}"
                        class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        required>
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Kata Sandi Baru (Opsional)
                </label>
                <input type="password" name="password" id="user-password"
                    placeholder="Kosongkan jika tidak ingin mengubah password"
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Assign Roles --}}
            <div class="border-t border-slate-200 dark:border-slate-700 pt-5">
                <label class="block text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">
                    Tugaskan Peran (Roles)
                </label>
                <p class="text-xs text-slate-400 mb-3">Pilih peran pengguna dalam sistem CBT Lab.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($roles as $role)
                    <label class="flex items-center gap-3 p-3.5 bg-slate-50 dark:bg-slate-700/40 hover:bg-blue-50/50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-600/60 rounded-xl cursor-pointer transition">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                            {{ in_array($role->name, old('roles', $userRoles)) ? 'checked' : '' }}
                            class="rounded text-blue-600 focus:ring-blue-500 dark:bg-slate-700 dark:border-slate-600">
                        <div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">{{ $role->name }}</span>
                            <span class="text-[11px] text-slate-400">{{ $role->permissions->count() }} permissions bawaan</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Assign Direct Permissions --}}
            <div class="border-t border-slate-200 dark:border-slate-700 pt-5">
                <label class="block text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">
                    Izin Khusus Tambahan (Direct Permissions)
                </label>
                <p class="text-xs text-slate-400 mb-3">Opsional: berikan hak akses langsung di luar peran yang dipilih.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 max-h-56 overflow-y-auto p-2 bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-slate-200 dark:border-slate-700">
                    @foreach($permissions as $p)
                    <label class="flex items-center gap-2 p-2 hover:bg-white dark:hover:bg-slate-800 rounded-lg cursor-pointer text-xs">
                        <input type="checkbox" name="permissions[]" value="{{ $p->name }}"
                            {{ in_array($p->name, old('permissions', $userDirectPermissions)) ? 'checked' : '' }}
                            class="rounded text-blue-600 focus:ring-blue-500 dark:bg-slate-700 dark:border-slate-600">
                        <span class="font-mono text-slate-700 dark:text-slate-300 text-[11px]">{{ $p->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                <a href="{{ route('users.index') }}"
                   class="flex-1 py-3 text-center border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
