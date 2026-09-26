@extends('layouts.app')
@section('title', 'Buat Tiket Kerusakan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Laporkan Kerusakan PC</h3>
        </div>

        <form action="{{ route('issues.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Unit PC <span class="text-red-500">*</span>
                </label>
                <select name="computer_id" id="issue-computer" required
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Pilih unit PC...</option>
                    @foreach($computers as $pc)
                    <option value="{{ $pc->id }}" {{ (old('computer_id', $computer?->id) == $pc->id) ? 'selected' : '' }}>
                        PC #{{ $pc->pc_number }} — Baris {{ $pc->row_position }}, Kolom {{ $pc->col_position }}
                        ({{ $pc->getStatusLabel() }})
                    </option>
                    @endforeach
                </select>
                @error('computer_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Kategori Kendala <span class="text-red-500">*</span>
                </label>
                <select name="category" id="issue-category" required
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Pilih kategori...</option>
                    <option value="monitor" {{ old('category') === 'monitor' ? 'selected' : '' }}>Monitor (Mati, flicker, garis)</option>
                    <option value="cpu" {{ old('category') === 'cpu' ? 'selected' : '' }}>Unit PC / CPU (Boot gagal, BSOD, power mati)</option>
                    <option value="network" {{ old('category') === 'network' ? 'selected' : '' }}>Jaringan / LAN (Kabel putus, IP konflik)</option>
                    <option value="peripherals" {{ old('category') === 'peripherals' ? 'selected' : '' }}>Periferal (Keyboard, Mouse, Audio)</option>
                    <option value="software" {{ old('category') === 'software' ? 'selected' : '' }}>Perangkat Lunak / OS / CBT Browser</option>
                </select>
                @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Tingkat Keparahan <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach(['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi', 'critical' => 'Kritis'] as $val => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="severity" value="{{ $val }}" class="sr-only severity-radio"
                            {{ old('severity', 'high') === $val ? 'checked' : '' }}>
                        <div class="py-2.5 rounded-xl text-sm font-medium text-center border-2 transition cursor-pointer severity-btn
                            {{ old('severity', 'high') === $val ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'border-slate-200 dark:border-slate-600 text-slate-500 dark:text-slate-400 hover:border-slate-400' }}">
                            {{ $label }}
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('severity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Deskripsi Masalah <span class="text-red-500">*</span>
                </label>
                <textarea name="issue_description" id="issue-desc" rows="4" required
                    placeholder="Jelaskan detail masalah yang terjadi, kapan mulai terjadi, dll..."
                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ old('issue_description') }}</textarea>
                @error('issue_description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('issues.index') }}"
                   class="flex-1 py-3 text-center border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </a>
                <button type="submit" id="btn-submit-issue"
                    class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition shadow">
                    Laporkan Kerusakan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.severity-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.severity-btn').forEach(btn => {
            btn.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/30', 'text-blue-700', 'dark:text-blue-300');
            btn.classList.add('border-slate-200', 'dark:border-slate-600', 'text-slate-500', 'dark:text-slate-400');
        });
        if (this.checked) {
            const btn = this.closest('label').querySelector('.severity-btn');
            btn.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/30', 'text-blue-700', 'dark:text-blue-300');
            btn.classList.remove('border-slate-200', 'dark:border-slate-600', 'text-slate-500', 'dark:text-slate-400');
        }
    });
});
</script>
@endpush
@endsection
