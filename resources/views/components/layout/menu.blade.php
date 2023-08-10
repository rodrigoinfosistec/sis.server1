<div style="width: 85px; height: 50px; margin: -37px;" class="float-end">
    {{-- Botão --}}
    <button type="button" class="navbar-toggler bg-primary" style="padding: 5px; border-radius: 50%;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
        <i class="bi-list text-light" style="font-size: 25px;"></i>
    </button>

    {{-- Canvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-primary" id="offcanvasNavbarLabel">
                <span class="fw-normal" style="font-size: 8pt; margin-left: 15px;">
                    {{-- Versão --}}
                    VERSÃO 1.0
                </span>
            </h5>

            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body" style="margin-top: -20px;">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item">
                    <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" class="text-decoration-none">
                        <i class="{{ App\Models\Page::getIconByName('home') }}"></i> {{ App\Models\Page::getTitleByName('home') }}
                    </x-responsive-nav-link>
                </li>

                <li class="nav-item">
                    <div class="pt-2 pb-0 border-t border-gray-200">
                        {{-- Empresa --}}
                        <x-responsive-nav-link href="{{ route('company') }}" :active="request()->routeIs('company')" class="text-decoration-none">
                            <i class="{{ App\Models\Page::getIconByName('company') }}"></i> {{ App\Models\Page::getTitleByName('company') }}
                        </x-responsive-nav-link>

                        {{-- Fornecedor --}}
                        <x-responsive-nav-link href="{{ route('provider') }}" :active="request()->routeIs('provider')" class="text-decoration-none">
                            <i class="{{ App\Models\Page::getIconByName('provider') }}"></i> {{ App\Models\Page::getTitleByName('provider') }}
                        </x-responsive-nav-link>

                        {{-- Grupo de Produto --}}
                        <x-responsive-nav-link href="{{-- route('productgroup') --}}" :active="request()->routeIs('productgroup')" class="text-decoration-none">
                            <i class="{{ App\Models\Page::getIconByName('productgroup') }}"></i> {{ App\Models\Page::getTitleByName('productgroup') }}
                        </x-responsive-nav-link>
                    </div>
                </li>

                <li style="margin-top: 10px;" class="nav-item dropdown">
                    {{-- Responsive Settings Options --}}
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <div class="shrink-0 mr-3">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </div>
                            @endif

                            <div>
                                <div class="font-medium text-base text-gray-800 text-capitalize" style="font-size: 10pt;">{{ Auth::user()->name }}</div>
                                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            {{-- Profile --}}
                            <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" class="text-decoration-none">
                                <i class="bi-gear"></i> {{ __('Profile') }}
                            </x-responsive-nav-link>

                            {{-- Grupo de Usuário --}}
                            <x-responsive-nav-link href="{{ route('usergroup') }}" :active="request()->routeIs('usergroup')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('usergroup') }}"></i> {{ App\Models\Page::getTitleByName('usergroup') }}
                            </x-responsive-nav-link>

                            {{-- Usuário --}}
                            <x-responsive-nav-link href="{{ route('user') }}" :active="request()->routeIs('user')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('user') }}"></i> {{ App\Models\Page::getTitleByName('user') }}
                            </x-responsive-nav-link>

                            {{-- Auditoria --}}
                            <x-responsive-nav-link href="{{ route('audit') }}" :active="request()->routeIs('audit')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('audit') }}"></i> {{ App\Models\Page::getTitleByName('audit') }}
                            </x-responsive-nav-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')" class="text-decoration-none">
                                    {{ __('API Tokens') }}
                                </x-responsive-nav-link>
                            @endif

                            {{-- Logout --}}
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-responsive-nav-link href="{{ route('logout') }}"
                                    @click.prevent="$root.submit();" class="text-decoration-none text-danger">
                                    <i class="bi-box-arrow-left"></i> {{ __('Log Out') }}
                                </x-responsive-nav-link>
                            </form>

                            {{-- Team Management --}}
                            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                                <div class="border-t border-gray-200"></div>

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Team') }}
                                </div>

                                {{-- Team Settings --}}
                                <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                                    {{ __('Team Settings') }}
                                </x-responsive-nav-link>

                                @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                    <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                                        {{ __('Create New Team') }}
                                    </x-responsive-nav-link>
                                @endcan

                                <div class="border-t border-gray-200"></div>

                                {{-- Team Switcher --}}
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Switch Teams') }}
                                </div>

                                @foreach (Auth::user()->allTeams() as $team)
                                    <x-switchable-team :team="$team" component="responsive-nav-link" />
                                @endforeach
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
