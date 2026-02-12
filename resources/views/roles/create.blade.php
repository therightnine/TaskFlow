@extends('layouts.admin_layout')

@section('title', 'Creer un role')
@section('page-title', 'Creer un role')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow p-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Nouveau role</h1>
            <p class="text-sm text-gray-500 mt-1">Ajoutez un role metier avec une description claire.</p>
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

        <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="role" class="block text-sm font-semibold text-gray-700">Nom du role</label>
                <input
                    id="role"
                    name="role"
                    type="text"
                    maxlength="150"
                    value="{{ old('role') }}"
                    placeholder="Ex: Superviseur, Analyste..."
                    class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('role') border-red-300 focus:ring-red-400 @enderror"
                    required>
                @error('role')
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
                    placeholder="Precisez les responsabilites principales du role..."
                    class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-red-300 focus:ring-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center rounded-xl bg-primary px-5 py-2.5 text-sm font-medium text-white hover:opacity-90">
                    Enregistrer
                </button>
                <a href="{{ route('admin.roles.gest_roles') }}" class="inline-flex items-center rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
