@extends('layouts.app')
@section('title', 'Manajemen Pengguna (Users)')
@section('subtitle', 'Kelola akun staf, teknisi, dan alokasi peran')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
    <div class="flex items-center gap-2">
        <a href="{{ route('roles.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition">
            Peran (Roles)
        </a>
        <a href="{{ route('permissions.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition">
            Daftar Permissions
        </a>
        <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm font-semibold bg-blue-600 text-white rounded-xl shadow-xs">
            Pengguna (Users)
        </a>
    </div>

    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
        <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..."
                class="border border-slate-300 dark:border-slate-600 rounded-xl px-3.5 py-2 text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 w-44">
            <select name="role" onchange="this.form.submit()" class="border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                <option value="">Semua Role</option>
                @foreach($roles as $r)
                <option value="{{ $r->name }}" {{ request('role') === $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                @endforeach
            </select>
        </form>

        @can('manage_users')
        <a href="{{ route('users.create') }}" id="btn-create-user"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah User
        </a>
        @endcan
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
                <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">NO</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Peran (Roles)</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Izin Khusus (Direct)</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($users as $index => $user)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                <td class="text-center px-4 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400">
                    {{ (method_exists($users, 'firstItem') && $users->firstItem()) ? $users->firstItem() + $index : $index + 1 }}
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-xs">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 dark:text-slate-100">{{ $user->name }}</p>
                            @if($user->id === Auth::id())
                            <span class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold">(Akun Anda)</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 text-slate-600 dark:text-slate-300 font-mono text-xs">
                    {{ $user->email }}
                </td>
                <td class="px-5 py-4">
                    <div class="flex flex-wrap gap-1.5">
                        @forelse($user->roles as $role)
                            <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $role->name === 'super_admin' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800' }}">
                                {{ $role->name }}
                            </span>
                        @empty
                            <span class="text-xs text-slate-400 italic">Tanpa role</span>
                        @endforelse
                    </div>
                </td>
                <td class="px-5 py-4">
                    <div class="flex flex-wrap gap-1">
                        @forelse($user->permissions as $p)
                            <span class="text-[10px] font-mono bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 px-1.5 py-0.5 rounded border border-amber-200 dark:border-amber-700/50">
                                {{ $p->name }}
                            </span>
                        @empty
                            <span class="text-xs text-slate-400">—</span>
                        @endforelse
                    </div>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('users.edit', $user) }}"
                           class="px-3 py-1.5 text-xs font-medium text-amber-600 border border-amber-300 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20 transition">
                            Edit
                        </a>
                        @if($user->id !== Auth::id())
                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirmForm(event, { title: 'Hapus Pengguna?', text: 'Pengguna {{ $user->name }} akan dihapus secara permanen.', confirmButtonText: 'Ya, Hapus!', confirmButtonColor: '#dc2626' })">
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
                <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                    Belum ada data Pengguna
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
