@extends('layouts.app')
@section('title', 'Denah Lab CBT')
@section('subtitle', 'Visualisasi interaktif 88 unit PC - Klik PC untuk ubah status atau buat tiket')

@section('content')
<div>
    @livewire('lab-floor-plan')
</div>
@endsection
