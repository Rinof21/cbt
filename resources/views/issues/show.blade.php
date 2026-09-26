@extends('layouts.app')
@section('title', 'Tiket #' . $issue->id)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-5">
        <div class="flex items-center gap-3 mb-5">
            <span class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-semibold {{ $issue->getStatusColorClass() }}">
                {{ $issue->getStatusLabel() }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-semibold {{ $issue->getSeverityColorClass() }}">
                {{ ucfirst($issue->severity) }}
            </span>
        </div>

        <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-1">PC #{{ $issue->computer->pc_number }}</h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm mb-6">
            {{ $issue->getCategoryLabel() }} &mdash; Baris {{ $issue->computer->row_position }}, Kolom {{ $issue->computer->col_position }}
        </p>

        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4 mb-6">
            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $issue->issue_description }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm mb-6">
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Dilaporkan oleh</p>
                <p class="font-medium text-slate-800 dark:text-slate-200">{{ $issue->reporter->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Tanggal Laporan</p>
                <p class="font-medium text-slate-800 dark:text-slate-200">{{ $issue->created_at->format('d M Y H:i') }}</p>
            </div>
            @if($issue->resolver)
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Diselesaikan oleh</p>
                <p class="font-medium text-slate-800 dark:text-slate-200">{{ $issue->resolver->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Tanggal Selesai</p>
                <p class="font-medium text-slate-800 dark:text-slate-200">{{ $issue->resolved_at?->format('d M Y H:i') }}</p>
            </div>
            @endif
        </div>

        @if($issue->resolution_notes)
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl p-4 mb-6">
            <p class="text-xs font-semibold text-green-700 dark:text-green-400 mb-1">Catatan Resolusi</p>
            <p class="text-sm text-green-800 dark:text-green-300">{{ $issue->resolution_notes }}</p>
        </div>
        @endif
    </div>

    {{-- Update Status --}}
    @can('update_issue')
    @if($issue->status !== 'resolved')
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <h4 class="font-bold text-slate-800 dark:text-slate-100 mb-4">Perbarui Status Tiket</h4>
        <form action="{{ route('issues.update-status', $issue) }}" method="POST" class="space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status Baru</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['open' => 'Terbuka', 'in_progress' => 'Dalam Proses', 'resolved' => 'Selesai'] as $val => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="{{ $val }}" class="sr-only"
                            {{ $issue->status === $val ? 'checked' : '' }}>
                        <div class="py-2.5 rounded-xl text-sm font-medium text-center border-2 transition cursor-pointer {{ $issue->status === $val ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/30 text-blue-700' : 'border-slate-200 dark:border-slate-600 text-slate-500 hover:border-slate-400' }}">
                            {{ $label }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Catatan Resolusi</label>
                <textarea name="resolution_notes" rows="3"
                    placeholder="Apa yang dilakukan untuk menyelesaikan masalah ini?"
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 resize-none">{{ $issue->resolution_notes }}</textarea>
            </div>
            <button type="submit" id="btn-update-issue" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow">
                Simpan Perubahan
            </button>
        </form>
    </div>
    @endif
    @endcan

    <div class="mt-4">
        <a href="{{ route('issues.index') }}" class="text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
            ← Kembali ke daftar tiket
        </a>
    </div>
</div>
@endsection
