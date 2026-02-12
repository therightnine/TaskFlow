@extends('layouts.admin_layout')

@section('title', 'Modifier un abonnement')
@section('page-title', 'Modifier un abonnement')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow p-8">
        <div class="mb-6 flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Modifier l'abonnement</h1>
                <p class="text-sm text-gray-500 mt-1">Mettez a jour les informations du plan.</p>
            </div>
            <span class="inline-flex rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">ID #{{ $abonnement->id }}</span>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                <p class="text-sm font-medium text-red-700 mb-1">Veuillez corriger les champs en erreur.</p>
                <ul class="list-disc pl-5 text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.abonnements.update', $abonnement->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="abonnement" class="block text-sm font-semibold text-gray-700">Nom de l'abonnement</label>
                <input
                    id="abonnement"
                    name="abonnement"
                    type="text"
                    maxlength="150"
                    value="{{ old('abonnement', $abonnement->abonnement) }}"
                    class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('abonnement') border-red-300 focus:ring-red-400 @enderror"
                    required>
                @error('abonnement')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700">Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    maxlength="500"
                    class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-red-300 focus:ring-red-400 @enderror">{{ old('description', $abonnement->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="max-w-xs">
                <label for="prix" class="block text-sm font-semibold text-gray-700">Prix (TND)</label>
                <input
                    id="prix"
                    name="prix"
                    type="number"
                    min="0"
                    step="0.01"
                    value="{{ old('prix', $abonnement->prix) }}"
                    class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('prix') border-red-300 focus:ring-red-400 @enderror"
                    required>
                @error('prix')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center rounded-xl bg-primary px-5 py-2.5 text-sm font-medium text-white hover:opacity-90">
                    Enregistrer les modifications
                </button>
                <a href="{{ route('admin.abonnements.gest_abonnements') }}" class="inline-flex items-center rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
