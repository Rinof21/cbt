@extends('layouts.app')
@section('title', 'Tambah Role Baru')
@section('subtitle', 'Buat peran baru dan tetapkan permissions')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8">
        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-6">Informasi Role & Hak Akses</h3>

        <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Nama Role (slug format) <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="role-name" value="{{ old('name') }}"
                    placeholder="Contoh: pengawas_ujian, teknisi_jaringan"
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    required>
                <p class="text-xs text-slate-400 mt-1">Gunakan huruf kecil dan garis bawah (snake_case).</p>
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
                            {{ in_array($p->name, old('permissions', [])) ? 'checked' : '' }}
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
                    Simpan Role
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
