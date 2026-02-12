@extends('layouts.admin_layout')

@section('title', 'Gestion des roles')
@section('page-title', 'Gestion des roles')

@section('content')
@php
    $totalRoles = $roles->count();
    $withDescription = $roles->filter(fn($r) => !empty($r->description))->count();
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-5 shadow">
            <p class="text-xs text-gray-500">Roles configures</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalRoles }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow">
            <p class="text-xs text-gray-500">Descriptions renseignees</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $withDescription }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Liste des roles</h2>
                <p class="text-sm text-gray-500">Definissez les permissions metier de la plateforme.</p>
            </div>
            <a href="{{ route('admin.roles.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white hover:opacity-90 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau role
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Role</th>
                        <th class="px-4 py-3 text-left font-semibold">Description</th>
                        <th class="px-4 py-3 text-left font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($roles as $role)
                        <tr class="hover:bg-gray-50/70">
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $role->role }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $role->description ?: '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.roles.edit', $role->id) }}"
                                       class="inline-flex items-center rounded-lg bg-emerald-600 p-2 text-white hover:bg-emerald-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232a2.5 2.5 0 113.536 3.536L7.5 20.036H4v-3.5L15.232 5.232z"/>
                                        </svg>
                                    </a>

                                    <button type="button"
                                            class="btn-delete-role inline-flex items-center rounded-lg bg-red-600 p-2 text-white hover:bg-red-700"
                                            data-id="{{ $role->id }}"
                                            data-url="{{ route('admin.roles.destroy', $role->id) }}"
                                            data-name="{{ $role->role }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m-4 0h14"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">Aucun role disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
