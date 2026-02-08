{{-- resources/views/utilisateurs/gest_utilisateurs.blade.php --}}
@extends('layouts.admin_layout')

@section('content')
    <h2 class="text-xl font-semibold mb-4">Gestion des utilisateurs</h2>

    {{-- Messages de statut / erreurs --}}
    @if(session('status'))
        <div class="bg-green-100 text-green-800 px-3 py-2 rounded mb-3">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-3 py-2 rounded mb-3">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="min-w-full border-collapse">
        <thead>
            <tr class="border-b">
                <th class="text-left p-2">Nom</th>
                <th class="text-left p-2">Prénom</th>
                <th class="text-left p-2">E‑mail</th>
                <!--<th class="text-left p-2">Rôle</th>
                <th class="text-left p-2">Abonnement</th>-->
                <th class="text-left p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr class="border-b align-top">
                {{-- Formulaire de mise à jour par ligne --}}
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <td class="p-2">
                        <input type="text" name="nom" class="w-full border rounded px-2 py-1"
                               value="{{ old('nom', $user->nom) }}" required>
                    </td>

                    <td class="p-2">
                        <input type="text" name="prenom" class="w-full border rounded px-2 py-1"
                               value="{{ old('prenom', $user->prenom) }}" required>
                    </td>

                    <td class="p-2">
                        <input type="email" name="email" class="w-full border rounded px-2 py-1"
                               value="{{ old('email', $user->email) }}" required>
                    </td>
             
                    <td class="p-2 space-x-2">
                        <button type="submit"
                                class="inline-block bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                        </button>
                </form>

                        {{-- Formulaire de suppression --}}
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Supprimer cet utilisateur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-block bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m-4 0h14"/>
                        </svg>
                            </button>
                        </form>
                    </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="p-3 text-center text-gray-500">Aucun utilisateur.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection