@extends('layouts.app')
@section('title', 'Edit Role: ' . $role->name)
@section('subtitle', 'Ubah nama peran dan sesuaikan permissions')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Edit Peran: <span class="text-blue-600">{{ $role->name }}</span></h3>
            @if($role->name === 'super_admin')
            <span class="text-xs bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 px-3 py-1 rounded-lg font-semibold border border-amber-200 dark:border-amber-700">
                Nama role sistem dilindungi
            </span>
            @endif
        </div>

        <form action="{{ route('roles.update', $role) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Nama Role <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="role-name" value="{{ old('name', $role->name) }}"
                    {{ $role->name === 'super_admin' ? 'readonly' : '' }}
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition {{ $role->name === 'super_admin' ? 'opacity-60 cursor-not-allowed bg-slate-100 dark:bg-slate-800' : '' }}"
                    required>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-slate-200 dark:border-slate-700 pt-5">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <label class="block text-sm font-bold text-slate-800 dark:text-slate-100">
                            Pilih Permissions (Hak Akses)
                        </label>
                        <p class="text-xs text-slate-400">Pilih hak akses yang diizinkan untuk peran ini.</p>
                    </div>
                    <button type="button" onclick="toggleAllCheckboxes()" class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                        Pilih / Batal Semua
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($allPermissions as $p)
                    <label class="flex items-start gap-3 p-3 bg-slate-50 dark:bg-slate-700/40 hover:bg-blue-50/50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-600/60 rounded-xl cursor-pointer transition">
                        <input type="checkbox" name="permissions[]" value="{{ $p->name }}"
                            {{ in_array($p->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}
                            class="perm-checkbox mt-1 rounded text-blue-600 focus:ring-blue-500 dark:bg-slate-700 dark:border-slate-600">
                        <div>
                            <span class="text-xs font-mono font-bold text-slate-800 dark:text-slate-200 block">{{ $p->name }}</span>
                            <span class="text-[11px] text-slate-400">{{ ucwords(str_replace('_', ' ', $p->name)) }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                <a href="{{ route('roles.index') }}"
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

<script>
function toggleAllCheckboxes() {
    const checkboxes = document.querySelectorAll('.perm-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}
</script>
@endsection
