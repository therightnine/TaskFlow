@extends('layouts.admin_layout')

@section('title', 'Gestion des utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

@section('content')
@php
    $totalUsers = $users->count();
    $activeSubs = $users->filter(fn($u) => $u->currentAbonnement)->count();
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-5 shadow">
            <p class="text-xs text-gray-500">Utilisateurs total</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow">
            <p class="text-xs text-gray-500">Avec abonnement actif</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $activeSubs }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow">
            <p class="text-xs text-gray-500">Sans abonnement actif</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ max(0, $totalUsers - $activeSubs) }}</p>
        </div>
    </div>

    @if(session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-6 shadow">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Liste des utilisateurs</h2>
                <p class="text-sm text-gray-500">Edition rapide des informations essentielles (mode modification inline).</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Utilisateur</th>
                        <th class="px-4 py-3 text-left font-semibold">Nom</th>
                        <th class="px-4 py-3 text-left font-semibold">Prenom</th>
                        <th class="px-4 py-3 text-left font-semibold">E-mail</th>
                        <th class="px-4 py-3 text-left font-semibold">Role</th>
                        <th class="px-4 py-3 text-left font-semibold">Abonnement</th>
                        <th class="px-4 py-3 text-left font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $item)
                        @php $updateFormId = 'update-user-' . $item->id; @endphp
                        <tr class="hover:bg-gray-50/70 align-top">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->photo ? asset($item->photo) : asset('images/default-avatar.png') }}"
                                         class="w-9 h-9 rounded-full object-cover" alt="avatar">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $item->prenom }} {{ $item->nom }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $item->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <input form="{{ $updateFormId }}" type="text" name="nom"
                                       class="w-full rounded-lg border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                       value="{{ old('nom', $item->nom) }}" required>
                            </td>

                            <td class="px-4 py-3">
                                <input form="{{ $updateFormId }}" type="text" name="prenom"
                                       class="w-full rounded-lg border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                       value="{{ old('prenom', $item->prenom) }}" required>
                            </td>

                            <td class="px-4 py-3">
                                <input form="{{ $updateFormId }}" type="email" name="email"
                                       class="w-full rounded-lg border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                       value="{{ old('email', $item->email) }}" required>
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                {{ $item->role->role ?? '—' }}
                            </td>

                            <td class="px-4 py-3">
                                @if($item->currentAbonnement && $item->currentAbonnement->abonnement)
                                    <span class="inline-flex rounded-full bg-cyan-50 px-2.5 py-1 text-xs font-semibold text-cyan-700">
                                        {{ $item->currentAbonnement->abonnement->abonnement }}
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">Aucun</span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <form id="{{ $updateFormId }}" action="{{ route('admin.users.update', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="inline-flex items-center rounded-lg bg-primary p-2 text-white hover:opacity-90">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.destroy', $item->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-lg bg-red-600 p-2 text-white hover:bg-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m-4 0h14"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">Aucun utilisateur trouve.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

