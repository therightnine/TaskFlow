@php
    $roleId = auth()->user()->id_role;

    switch ($roleId) {
        case 1:
            $layout = 'layouts.admin_layout';
            break;
        case 3:
            $layout = 'layouts.chef_layout';
            break;
        case 2:
            $layout = 'layouts.superviseur_layout';
            break;
        case 4:
            $layout = 'layouts.contributeur_layout';
            break;
        default:
            $layout = 'layouts.app';
    }
@endphp

@extends($layout)

@section('title', 'Equipe')
@section('page-title', 'Equipes')

@section('content')
<div class="p-6 space-y-6">
    @if($projets->isEmpty())
        <div class="bg-white rounded-3xl shadow-lg border border-slate-100 p-10">
            <div class="max-w-xl mx-auto text-center">
                <div class="mx-auto mb-4 w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-2xl">
                    <span>+</span>
                </div>
                <h2 class="text-2xl font-semibold text-slate-800">Aucune equipe disponible</h2>
                <p class="mt-2 text-slate-500">Vous n'etes associe a aucune equipe pour le moment.</p>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-lg border border-slate-100 p-6 md:p-8 space-y-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Equipe</h1>
                    <p class="text-slate-500 mt-1">Explorez les membres et leurs profils par projet.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <div class="relative w-full sm:w-64">
                        <input
                            id="memberSearch"
                            type="text"
                            placeholder="Rechercher un membre..."
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:bg-white"
                        />
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">⌕</span>
                    </div>

                    <select
                        id="projectSelect"
                        class="w-full sm:w-64 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400"
                    >
                        <option value="">Tous les projets</option>
                        @foreach($projets as $projet)
                            <option value="{{ $projet->id }}">{{ $projet->nom_projet }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Projets</p>
                    <p class="text-xl font-semibold text-slate-800">{{ $projets->count() }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Membres</p>
                    <p class="text-xl font-semibold text-slate-800">{{ $allMembers->count() }}</p>
                </div>
                <div class="rounded-2xl border border-cyan-200 bg-cyan-50 px-4 py-3">
                    <p class="text-xs text-cyan-700">Membres visibles</p>
                    <p id="visibleCount" class="text-xl font-semibold text-cyan-700">{{ $allMembers->count() }}</p>
                </div>
            </div>

            <div id="selectedProjectName" class="min-h-6 text-sm font-medium text-cyan-700"></div>

            <div id="teamContainer" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-5">
                @foreach($allMembers as $member)
                    @php
                        $memberProjects = $member->allProjects();
                        $memberProjectIds = $memberProjects->pluck('id')->implode(',');
                        $roleName = $member->role->role ?? 'Membre';
                        $roleSlug = \Illuminate\Support\Str::lower($roleName);

                        $accent = 'from-slate-500 to-slate-400';
                        $badge = 'bg-slate-100 text-slate-700';
                        if (\Illuminate\Support\Str::contains($roleSlug, 'chef')) {
                            $accent = 'from-indigo-500 to-blue-500';
                            $badge = 'bg-indigo-100 text-indigo-700';
                        } elseif (\Illuminate\Support\Str::contains($roleSlug, 'super')) {
                            $accent = 'from-cyan-500 to-sky-500';
                            $badge = 'bg-cyan-100 text-cyan-700';
                        } elseif (\Illuminate\Support\Str::contains($roleSlug, 'contrib')) {
                            $accent = 'from-emerald-500 to-green-500';
                            $badge = 'bg-emerald-100 text-emerald-700';
                        }
                    @endphp

                    <article
                        class="team-member group rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden"
                        data-user-id="{{ $member->id }}"
                        data-projet-ids="{{ $memberProjectIds }}"
                        data-name="{{ \Illuminate\Support\Str::lower(trim(($member->prenom ?? '') . ' ' . ($member->nom ?? ''))) }}"
                        data-role="{{ \Illuminate\Support\Str::lower($roleName) }}"
                    >
                        <div class="h-2 bg-gradient-to-r {{ $accent }}"></div>

                        <div class="p-5">
                            <div class="flex items-center gap-4">
                                <img
                                    src="{{ $member->photo ? asset($member->photo) : asset('images/default-avatar.png') }}"
                                    class="w-16 h-16 rounded-full object-cover ring-2 ring-slate-100"
                                    alt="Photo de {{ $member->prenom }} {{ $member->nom }}"
                                />
                                <div class="min-w-0">
                                    <h3 class="font-semibold text-slate-800 truncate">{{ $member->prenom }} {{ $member->nom }}</h3>
                                    <span class="inline-flex mt-1 text-xs px-2.5 py-1 rounded-full {{ $badge }}">
                                        {{ $roleName }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-sm text-slate-500 mt-4 truncate">{{ $member->email }}</p>

                            <div class="mt-4 flex items-center justify-between">
                                <p class="text-xs text-slate-400">
                                    {{ $memberProjects->count() }} projet(s)
                                </p>
                                <span class="text-xs font-medium text-cyan-600 group-hover:text-cyan-700">Voir profil</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div id="noResultState" class="hidden rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                <p class="text-slate-500">Aucun membre ne correspond aux filtres actuels.</p>
            </div>
        </div>

        <div id="profilePanel" class="fixed inset-0 bg-black/45 hidden items-center justify-center z-50 p-4">
            <div class="bg-white w-full max-w-5xl rounded-3xl shadow-2xl overflow-hidden relative">
                <button
                    id="closeProfileBtn"
                    type="button"
                    class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-white/90 hover:bg-white text-slate-600 hover:text-slate-900 border border-slate-200"
                >
                    ✕
                </button>

                <div id="profileContent"></div>
            </div>
        </div>
    @endif
</div>

<script>
    const projectSelect = document.getElementById('projectSelect');
    const memberSearch = document.getElementById('memberSearch');
    const selectedProjectName = document.getElementById('selectedProjectName');
    const visibleCount = document.getElementById('visibleCount');
    const noResultState = document.getElementById('noResultState');
    const teamMembers = document.querySelectorAll('.team-member');
    const profilePanel = document.getElementById('profilePanel');
    const profileContent = document.getElementById('profileContent');
    const closeProfileBtn = document.getElementById('closeProfileBtn');

    function applyFilters() {
        if (!projectSelect || !memberSearch) return;

        const selectedId = projectSelect.value;
        const query = (memberSearch.value || '').trim().toLowerCase();
        let shown = 0;

        teamMembers.forEach((member) => {
            const ids = (member.dataset.projetIds || '').split(',').filter(Boolean);
            const name = member.dataset.name || '';
            const role = member.dataset.role || '';

            const matchesProject = !selectedId || ids.includes(selectedId);
            const matchesSearch = query === '' || name.includes(query) || role.includes(query);
            const show = matchesProject && matchesSearch;

            member.style.display = show ? '' : 'none';
            if (show) shown += 1;
        });

        if (visibleCount) visibleCount.textContent = String(shown);
        if (noResultState) noResultState.classList.toggle('hidden', shown > 0);

        if (selectedProjectName) {
            const selectedOption = projectSelect.options[projectSelect.selectedIndex];
            selectedProjectName.textContent = selectedId ? `Projet actif: ${selectedOption.text}` : '';
        }
    }

    if (projectSelect) {
        projectSelect.addEventListener('change', applyFilters);
    }

    if (memberSearch) {
        memberSearch.addEventListener('input', applyFilters);
    }

    teamMembers.forEach((card) => {
        card.addEventListener('click', async () => {
            const userId = card.dataset.userId;
            if (!profilePanel || !profileContent || !userId) return;

            profilePanel.classList.remove('hidden');
            profilePanel.classList.add('flex');

            profileContent.innerHTML = `
                <div class="p-10">
                    <div class="animate-pulse space-y-4">
                        <div class="h-10 w-2/3 bg-slate-200 rounded"></div>
                        <div class="h-4 w-full bg-slate-100 rounded"></div>
                        <div class="h-4 w-5/6 bg-slate-100 rounded"></div>
                        <div class="h-40 w-full bg-slate-100 rounded-xl"></div>
                    </div>
                </div>
            `;

            try {
                const response = await fetch(`/equipe/membre/${userId}`);
                profileContent.innerHTML = await response.text();
            } catch (e) {
                profileContent.innerHTML = `
                    <div class="p-10 text-center text-red-500">
                        Erreur de chargement du profil.
                    </div>
                `;
            }
        });
    });

    function closeProfile() {
        if (!profilePanel) return;
        profilePanel.classList.add('hidden');
        profilePanel.classList.remove('flex');
    }

    if (closeProfileBtn) {
        closeProfileBtn.addEventListener('click', closeProfile);
    }

    if (profilePanel) {
        profilePanel.addEventListener('click', (event) => {
            if (event.target === profilePanel) {
                closeProfile();
            }
        });
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeProfile();
        }
    });

    applyFilters();
</script>
@endsection
