{{-- <resources>
<views>
<contributeur></contributeur>/messages.blade.php --}}
@extends('layouts.contributeur_layout')

@section('title', 'Messages Contributeur')
@section('page-title', 'Messages')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Messages du contributeur</h1>
    @forelse($messages as $message)
        <div class="mb-2 p-2 bg-gray-100 rounded">
            {{ $message }}
        </div>
    @empty
        <p>Aucun message pour le moment.</p>
    @endforelse
</div>
@endsection
