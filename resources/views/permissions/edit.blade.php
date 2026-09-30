@extends('layouts.app')
@section('title', 'Edit Permission: ' . $permission->name)
@section('subtitle', 'Ubah nama dan tautan peran')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8">
        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-6">Edit Permission: <span class="text-blue-600">{{ $permission->name }}</span></h3>

        <form action="{{ route('permissions.update', $permission) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Nama Permission <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="perm-name" value="{{ old('name', $permission->name) }}"
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    required>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-slate-200 dark:border-slate-700 pt-5">
                <label class="block text-sm font-bold text-slate-800 dark:text-slate-100 mb-2">
                    Tautkan ke Peran (Roles)
                </label>
                <p class="text-xs text-slate-400 mb-3">Pilih role yang memiliki izin ini.</p>

                <div class="grid grid-cols-2 gap-3">
                    @foreach($roles as $role)
                    <label class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-700/40 hover:bg-blue-50/50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-600/60 rounded-xl cursor-pointer transition">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                            {{ in_array($role->name, old('roles', $permissionRoles)) ? 'checked' : '' }}
                            class="rounded text-blue-600 focus:ring-blue-500 dark:bg-slate-700 dark:border-slate-600">
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $role->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                <a href="{{ route('permissions.index') }}"
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
