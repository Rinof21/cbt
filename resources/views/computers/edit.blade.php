@extends('layouts.app')
@section('title', 'Edit PC #' . $computer->pc_number)

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-8">
        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-6">Edit Inventaris PC #{{ $computer->pc_number }}</h3>

        <form action="{{ route('computers.update', $computer) }}" method="POST" class="space-y-5">
            @csrf @method('PATCH')

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-3 text-center">
                    <p class="text-xs text-slate-400">Posisi Baris</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $computer->row_position }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-3 text-center">
                    <p class="text-xs text-slate-400">Posisi Kolom</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $computer->col_position }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Brand / Model PC</label>
                <input type="text" name="brand_model" value="{{ old('brand_model', $computer->brand_model) }}"
                    placeholder="Contoh: Dell OptiPlex 3000"
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Model Monitor</label>
                <input type="text" name="monitor_model" value="{{ old('monitor_model', $computer->monitor_model) }}"
                    placeholder="Contoh: Dahua 22 Inch"
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">IP Address</label>
                <input type="text" name="ip_address" value="{{ old('ip_address', $computer->ip_address) }}"
                    placeholder="192.168.1.xxx"
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Catatan</label>
                <textarea name="notes" rows="3"
                    placeholder="Catatan khusus untuk PC ini..."
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes', $computer->notes) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('computers.index') }}"
                   class="flex-1 py-3 text-center border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </a>
                <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
