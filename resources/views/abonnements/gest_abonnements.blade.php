@extends('layouts.admin_layout')

@section('title', 'Gestion des abonnements')
@section('page-title', 'Gestion des abonnements')

@section('content')
@php
    $totalOffers = $abonnements->count();
    $avgPrice = $totalOffers > 0 ? $abonnements->avg('prix') : 0;
    $maxPrice = $totalOffers > 0 ? $abonnements->max('prix') : 0;
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-5 shadow">
            <p class="text-xs text-gray-500">Offres disponibles</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalOffers }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow">
            <p class="text-xs text-gray-500">Prix moyen</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format((float) $avgPrice, 2, ',', ' ') }} TND</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow">
            <p class="text-xs text-gray-500">Offre la plus chere</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format((float) $maxPrice, 2, ',', ' ') }} TND</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Liste des abonnements</h2>
                <p class="text-sm text-gray-500">Gerer les plans, descriptions et tarifs.</p>
            </div>
            <a href="{{ route('admin.abonnements.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white hover:opacity-90 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvel abonnement
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Abonnement</th>
                        <th class="px-4 py-3 text-left font-semibold">Description</th>
                        <th class="px-4 py-3 text-left font-semibold">Prix</th>
                        <th class="px-4 py-3 text-left font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($abonnements as $ab)
                        <tr class="hover:bg-gray-50/70">
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $ab->abonnement }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $ab->description ?: '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-cyan-50 px-2.5 py-1 text-xs font-semibold text-cyan-700">
                                    {{ number_format((float) $ab->prix, 2, ',', ' ') }} TND
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.abonnements.edit', $ab->id) }}"
                                       class="inline-flex items-center rounded-lg bg-emerald-600 p-2 text-white hover:bg-emerald-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232a2.5 2.5 0 113.536 3.536L7.5 20.036H4v-3.5L15.232 5.232z"/>
                                        </svg>
                                    </a>

                                    <button type="button"
                                            class="btn-delete-ab inline-flex items-center rounded-lg bg-red-600 p-2 text-white hover:bg-red-700"
                                            data-id="{{ $ab->id }}"
                                            data-url="{{ route('admin.abonnements.destroy', $ab->id) }}"
                                            data-name="{{ $ab->abonnement }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m-4 0h14"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">Aucun abonnement disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
