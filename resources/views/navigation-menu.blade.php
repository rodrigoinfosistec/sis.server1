<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    {{-- Primary Navigation Menu --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                @if(Auth()->User()->usergroup_id != App\Models\Usergroup::where('name', 'FUNCIONARIO')->first()->id)
                    {{-- Nota Fiscal --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('invoice') }}" :active="request()->routeIs('invoice')" class="text-decoration-none" title="Nota Fiscal">
                            <i class="bi-receipt" style="font-size: 20pt;"></i>
                        </x-nav-link>
                    </div>

                    {{-- Ponto --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('clock') }}" :active="request()->routeIs('clock')" class="text-decoration-none" title="Ponto">
                            <i class="bi-clock" style="font-size: 20pt;"></i>
                        </x-nav-link>
                    </div>

                    {{-- Entrada Depósito --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('depositinput') }}" :active="request()->routeIs('depositinput')" class="text-decoration-none" title="Entrada Depósito">
                            <i class="bi-plus-square-dotted" style="font-size: 20pt;"></i>
                        </x-nav-link>
                    </div>

                    {{-- Transferêcnia Depósito --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('deposittransfer') }}" :active="request()->routeIs('deposittransfer')" class="text-decoration-none" title="Transferência Depósito">
                            <i class="bi-arrow-left-right" style="font-size: 20pt;"></i>
                        </x-nav-link>
                    </div>

                    {{-- Avarias --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('breakdow') }}" :active="request()->routeIs('breakdow')" class="text-decoration-none" title="Avarias">
                            <i class="bi-hand-thumbs-down" style="font-size: 20pt;"></i>
                        </x-nav-link>
                    </div>
                @endif
            </div>

            @if(Auth()->User()->usergroup_id != App\Models\Usergroup::where('name', 'FUNCIONARIO')->first()->id)
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    {{-- Teams Dropdown --}}
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="ml-3 relative">
                            <x-dropdown align="right" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                            {{ Auth::user()->currentTeam->name }}
    
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>
    
                                <x-slot name="content">
                                    <div class="w-60">
                                        {{-- Team Management --}}
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Manage Team') }}
                                        </div>
    
                                        {{-- Team Settings --}}
                                        <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                            {{ __('Team Settings') }}
                                        </x-dropdown-link>
    
                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-dropdown-link href="{{ route('teams.create') }}">
                                                {{ __('Create New Team') }}
                                            </x-dropdown-link>
                                        @endcan
    
                                        {{-- Team Switcher --}}
                                        @if (Auth::user()->allTeams()->count() > 1)
                                            <div class="border-t border-gray-200 dark:border-gray-600"></div>
    
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('Switch Teams') }}
                                            </div>
    
                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-switchable-team :team="$team" />
                                            @endforeach
                                        @endif
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
    
                    {{-- Settings Dropdown --}}
                    <div class="ml-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    {{-- Foto --}}
                                    <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}?123" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                            <span class="text-capitalize">{{ Auth::user()->name }}</span>
    
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>
    
                            <x-slot name="content">
                                {{-- Account Management --}}
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>
    
                                <x-dropdown-link href="{{ route('profile.show') }}" class="text-decoration-none">
                                    <i class="bi-gear-fill"></i> {{ __('Profile') }}
                                </x-dropdown-link>
    
                                <x-dropdown-link href="{{ route('employeebase') }}" class="text-decoration-none">
                                    <i class="{{ App\Models\Page::getIconByName('employeebase') }}"></i> {{ App\Models\Page::getTitleByName('employeebase') }}
                                </x-dropdown-link>
    
                                <x-dropdown-link href="{{ route('usergroup') }}" class="text-decoration-none">
                                    <i class="{{ App\Models\Page::getIconByName('usergroup') }}"></i> {{ App\Models\Page::getTitleByName('usergroup') }}
                                </x-dropdown-link>
    
                                <x-dropdown-link href="{{ route('user') }}" class="text-decoration-none">
                                    <i class="{{ App\Models\Page::getIconByName('user') }}"></i> {{ App\Models\Page::getTitleByName('user') }}
                                </x-dropdown-link>
    
                                <x-dropdown-link href="{{ route('audit') }}" class="text-decoration-none">
                                    <i class="{{ App\Models\Page::getIconByName('audit') }}"></i> {{ App\Models\Page::getTitleByName('audit') }}
                                </x-dropdown-link>
    
                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-dropdown-link>
                                @endif
    
                                <div class="border-t border-gray-200 dark:border-gray-600"></div>
    
                                {{-- Authentication --}}
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
    
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class=" text-danger text-decoration-none">
                                        <i class="bi-box-arrow-left"></i> {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
    
                {{-- Hamburger --}}
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Responsive Navigation Menu --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1" style="margin-left: 25px;">
            {{-- Nota Fiscal --}}
            <x-nav-link href="{{ route('invoice') }}" :active="request()->routeIs('invoice')" class="text-decoration-none" style="margin-right: 10px;">
                <i class="bi-receipt" style="font-size: 20pt;"></i>
            </x-nav-link>

             {{-- Ponto --}}
             <x-nav-link href="{{ route('clock') }}" :active="request()->routeIs('clock')" class="text-decoration-none" style="margin-right: 10px;">
                <i class="bi-clock" style="font-size: 20pt;"></i>
            </x-nav-link>

            {{-- Entrada Depósito --}}
             <x-nav-link href="{{ route('depositinput') }}" :active="request()->routeIs('depositinput')" class="text-decoration-none" style="margin-right: 10px;">
                <i class="bi-plus-square-dotted" style="font-size: 20pt;"></i>
            </x-nav-link>

            {{-- Transferência Depósito --}}
            <x-nav-link href="{{ route('deposittransfer') }}" :active="request()->routeIs('deposittransfer')" class="text-decoration-none" style="margin-right: 10px;">
                <i class="bi-arrow-left-right" style="font-size: 20pt;"></i>
            </x-nav-link>

            {{-- Avaria --}}
            <x-nav-link href="{{ route('breakdow') }}" :active="request()->routeIs('breakdow')" class="text-decoration-none" style="margin-right: 10px;">
                <i class="bi-hand-thumbs-down" style="font-size: 20pt;"></i>
            </x-nav-link>
        </div>

        {{-- Responsive Settings Options --}}
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 mr-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                        <span class="text-capitalize">{{ Auth::user()->name }}</span></div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                {{-- Account Management --}}
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" class="text-decoration-none">
                    <i class="bi-gear-fill"></i> {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('employeebase') }}" :active="request()->routeIs('employeebase')" class="text-decoration-none">
                    <i class="{{ App\Models\Page::getIconByName('employeebase') }}"></i> {{ App\Models\Page::getTitleByName('employeebase') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('usergroup') }}" :active="request()->routeIs('usergroup')" class="text-decoration-none">
                    <i class="{{ App\Models\Page::getIconByName('usergroup') }}"></i> {{ App\Models\Page::getTitleByName('usergroup') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('user') }}" :active="request()->routeIs('user')" class="text-decoration-none">
                    <i class="{{ App\Models\Page::getIconByName('user') }}"></i> {{ App\Models\Page::getTitleByName('user') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('audit') }}" :active="request()->routeIs('audit')" class="text-decoration-none">
                    <i class="{{ App\Models\Page::getIconByName('audit') }}"></i> {{ App\Models\Page::getTitleByName('audit') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                {{-- Authentication --}}
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class="text-danger text-decoration-none">
                        <i class="bi-box-arrow-left"></i> {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                {{-- Team Management --}}
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200 dark:border-gray-600"></div>

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

                    {{-- Team Switcher --}}
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
