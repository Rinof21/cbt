@extends('layouts.app')
@section('title', 'Buat Sesi CBT Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-8">
        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-6">Detail Sesi Ujian</h3>

        <form action="{{ route('sessions.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Nama Kegiatan / Ujian <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="session-title" value="{{ old('title') }}"
                    placeholder="Contoh: UTBK 2026 Gelombang 1"
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    required>
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
                <textarea name="description" id="session-desc" rows="3"
                    placeholder="Informasi tambahan tentang sesi ujian ini..."
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Jadwal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="scheduled_start" id="session-start" value="{{ old('scheduled_start') }}"
                        class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    @error('scheduled_start') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Estimasi Selesai</label>
                    <input type="datetime-local" name="scheduled_end" id="session-end" value="{{ old('scheduled_end') }}"
                        class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Jumlah PC yang Dibutuhkan <span class="text-red-500">*</span>
                </label>
                <input type="number" name="participants_needed" id="session-participants" value="{{ old('participants_needed', 30) }}"
                    min="1" max="88"
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
                <p class="text-xs text-slate-400 mt-1">Tersedia: {{ $availableCount }} unit PC siap pakai</p>
                @error('participants_needed') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('sessions.index') }}"
                   class="flex-1 py-3 text-center border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </a>
                <button type="submit" id="btn-submit-session"
                    class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow">
                    Buat Sesi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
