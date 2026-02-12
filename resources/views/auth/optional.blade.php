@extends('layouts.app')

@section('title', 'Informations supplementaires - TaskFlow')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-14">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
        <div>
            <div class="rounded-3xl bg-white border border-slate-100 shadow-2xl shadow-slate-900/10 p-8 md:p-10">
                <div class="mb-8">
                    <p class="text-sm font-semibold text-cyan-700 uppercase tracking-wide">Etape 2</p>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mt-1">Finaliser votre profil</h1>
                    <p class="text-slate-500 mt-2">Ces champs sont optionnels sauf abonnement et role.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 text-sm">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.optional.store', ['user_id' => $user->id]) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Telephone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Profession</label>
                            <input type="text" name="profession" value="{{ old('profession') }}"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Bio</label>
                        <textarea name="bio" rows="3"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-400">{{ old('bio') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Photo de profil</label>
                        <input id="photoInput" type="file" name="photo" accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 file:mr-3 file:rounded-lg file:border-0 file:bg-cyan-50 file:px-3 file:py-2 file:text-cyan-700">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Abonnement</label>
                            <select name="id_abonnement" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                                <option value="">Choisir</option>
                                @foreach($abonnements as $abonnement)
                                    <option value="{{ $abonnement->id }}" {{ old('id_abonnement') == $abonnement->id ? 'selected' : '' }}>
                                        {{ $abonnement->abonnement }} ({{ $abonnement->prix }} DT)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                            <select name="id_role" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                                <option value="">Choisir un role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('id_role') == $role->id ? 'selected' : '' }}>
                                        {{ $role->role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 transition">
                        Terminer l'inscription
                    </button>
                </form>
            </div>
        </div>

        <div>
            <div class="rounded-3xl overflow-hidden border border-cyan-100 bg-gradient-to-br from-cyan-50 via-sky-50 to-blue-50 p-10 min-h-[420px]">
                <p class="text-sm font-semibold text-cyan-700 uppercase tracking-wide">Apercu photo</p>
                <div class="mt-5 flex items-center justify-center">
                    <img id="photoPreview" src="{{ asset('images/default-avatar.png') }}"
                        class="w-44 h-44 rounded-2xl object-cover border-4 border-white shadow-lg" alt="Apercu">
                </div>
                <p class="text-sm text-slate-600 mt-6 text-center">
                    Ajoutez une photo JPG/PNG pour personnaliser votre profil.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');

    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', () => {
            const file = photoInput.files?.[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (event) => {
                photoPreview.src = event.target?.result;
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endsection
