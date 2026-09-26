@extends('layouts.app')
@section('title', 'Edit Sesi CBT: ' . $session->title)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-8">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
            <div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Edit Sesi CBT</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbarui informasi dan parameter sesi ujian</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold {{ $session->getStatusColorClass() }}">
                {{ $session->getStatusLabel() }}
            </span>
        </div>

        <form action="{{ route('sessions.update', $session) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Nama Kegiatan / Ujian <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="edit-session-title" value="{{ old('title', $session->title) }}"
                    placeholder="Contoh: UTBK 2026 Gelombang 1"
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    required>
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
                <textarea name="description" id="edit-session-desc" rows="3"
                    placeholder="Informasi tambahan tentang sesi ujian ini..."
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none">{{ old('description', $session->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Jadwal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="scheduled_start" id="edit-session-start"
                        value="{{ old('scheduled_start', $session->scheduled_start ? $session->scheduled_start->format('Y-m-d\TH:i') : '') }}"
                        class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    @error('scheduled_start') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Estimasi Selesai</label>
                    <input type="datetime-local" name="scheduled_end" id="edit-session-end"
                        value="{{ old('scheduled_end', $session->scheduled_end ? $session->scheduled_end->format('Y-m-d\TH:i') : '') }}"
                        class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('scheduled_end') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Jumlah PC yang Dibutuhkan <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="participants_needed" id="edit-session-participants"
                        value="{{ old('participants_needed', $session->participants_needed) }}"
                        min="1" max="88"
                        class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    @error('participants_needed') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status Sesi</label>
                    <select name="status" id="edit-session-status"
                        class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="scheduled" {{ old('status', $session->status) === 'scheduled' ? 'selected' : '' }}>Terjadwal (Scheduled)</option>
                        <option value="ongoing" {{ old('status', $session->status) === 'ongoing' ? 'selected' : '' }}>Berlangsung (Ongoing)</option>
                        <option value="completed" {{ old('status', $session->status) === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                        <option value="cancelled" {{ old('status', $session->status) === 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('sessions.show', $session) }}"
                   class="flex-1 py-3 text-center border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </a>
                <button type="submit" id="btn-save-edit-session"
                    class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
