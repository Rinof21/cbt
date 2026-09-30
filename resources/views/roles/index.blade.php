@extends('layouts.app')
@section('title', 'Peran & Hak Akses (Roles)')
@section('subtitle', 'Manajemen role dan alokasi permission Spatie')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-2">
        <a href="{{ route('roles.index') }}" class="px-4 py-2 text-sm font-semibold bg-blue-600 text-white rounded-xl shadow-xs">
            Peran (Roles)
        </a>
        <a href="{{ route('permissions.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition">
            Daftar Permissions
        </a>
        <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition">
            Pengguna (Users)
        </a>
    </div>
    @can('manage_users')
    <a href="{{ route('roles.create') }}" id="btn-create-role"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Role Baru
    </a>
    @endcan
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
                <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">NO</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Peran (Role)</th>
                <th class="text-center px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Hak Akses (Permissions)</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($roles as $index => $role)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                <td class="text-center px-4 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $index + 1 }}</td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wide text-xs px-2.5 py-1 rounded-lg {{ $role->name === 'super_admin' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800' : ($role->name === 'operator' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300') }}">
                            {{ $role->name }}
                        </span>
                        @if($role->name === 'super_admin')
                        <span class="text-[10px] bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 px-2 py-0.5 rounded-md font-semibold border border-amber-200 dark:border-amber-700/50">
                            Sistem Utama
                        </span>
                        @endif
                    </div>
                </td>
                <td class="px-5 py-4 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                        {{ $role->users_count }} user
                    </span>
                </td>
                <td class="px-5 py-4">
                    <div class="flex flex-wrap gap-1.5 max-w-xl">
                        @if($role->name === 'super_admin')
                            <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 px-2.5 py-1 rounded-lg">
                                ★ Memiliki Semua Akses ({{ $role->permissions_count }} permissions)
                            </span>
                        @else
                            @forelse($role->permissions as $p)
                                <span class="text-[11px] font-mono bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 px-2 py-0.5 rounded-md border border-slate-200 dark:border-slate-600">
                                    {{ $p->name }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-400 italic">Belum ada permission yang ditautkan</span>
                            @endforelse
                        @endif
                    </div>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('roles.edit', $role) }}"
                           class="px-3 py-1.5 text-xs font-medium text-amber-600 border border-amber-300 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20 transition">
                            Edit
                        </a>
                        @if($role->name !== 'super_admin')
                        <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirmForm(event, { title: 'Hapus Role?', text: 'Role {{ $role->name }} akan dihapus.', confirmButtonText: 'Ya, Hapus!', confirmButtonColor: '#dc2626' })">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-red-600 border border-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition cursor-pointer">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                    Belum ada data Role
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
