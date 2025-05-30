<div style="width: 85px; height: 50px; margin: -37px;" class="float-end">
    {{-- Botão --}}
    <button type="button" class="navbar-toggler bg-dark" style="padding: 5px; border-radius: 50%;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
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
                @if(Auth()->User()->usergroup_id != App\Models\Usergroup::where('name', 'FUNCIONARIO')->first()->id)
                    <li class="nav-item">
                        <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" class="text-decoration-none">
                            <i class="{{ App\Models\Page::getIconByName('home') }}"></i> {{ App\Models\Page::getTitleByName('home') }}
                        </x-responsive-nav-link>
                    </li>

                    <li class="nav-item">
                        <div class="pt-2 pb-0 border-t border-gray-200">
                            {{-- Controle Ponto --}}
                            <x-responsive-nav-link href="{{ route('employeepoint') }}" :active="request()->routeIs('employeepoint')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('employeepoint') }}"></i> {{ App\Models\Page::getTitleByName('employeepoint') }}
                            </x-responsive-nav-link>
                        </div>

                        <div class="pt-2 pb-0 border-t border-gray-200">
                            {{-- Empresa --}}
                            <x-responsive-nav-link href="{{ route('company') }}" :active="request()->routeIs('company')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('company') }}"></i> {{ App\Models\Page::getTitleByName('company') }}
                            </x-responsive-nav-link>

                            {{-- Fornecedor --}}
                            <x-responsive-nav-link href="{{ route('provider') }}" :active="request()->routeIs('provider')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('provider') }}"></i> {{ App\Models\Page::getTitleByName('provider') }}
                            </x-responsive-nav-link>
                        </div>

                        <div class="pt-2 pb-0 border-t border-gray-200">
                            {{-- RH Pesquisa --}}
                            <x-responsive-nav-link href="{{ route('rhsearch') }}" :active="request()->routeIs('rhsearch')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('rhsearch') }}"></i> {{ App\Models\Page::getTitleByName('rhsearch') }}
                            </x-responsive-nav-link>

                            {{-- RH Informa --}}
                            <x-responsive-nav-link href="{{ route('rhnews') }}" :active="request()->routeIs('rhnews')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('rhnews') }}"></i> {{ App\Models\Page::getTitleByName('rhnews') }}
                            </x-responsive-nav-link>
                        </div>

                        <div class="pt-2 pb-0 border-t border-gray-200">
                            {{-- Grupo de Produto --}}
                            <x-responsive-nav-link href="{{ route('productgroup') }}" :active="request()->routeIs('productgroup')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('productgroup') }}"></i> {{ App\Models\Page::getTitleByName('productgroup') }}
                            </x-responsive-nav-link>

                            {{-- Custo de Produto --}}
                            <x-responsive-nav-link href="{{ route('invoiceitem') }}" :active="request()->routeIs('invoiceitem')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('invoiceitem') }}"></i> {{ App\Models\Page::getTitleByName('invoiceitem') }}
                            </x-responsive-nav-link>
                        </div>

                        <div class="pt-2 pb-0 border-t border-gray-200">
                            {{-- Funcionário --}}
                            <x-responsive-nav-link href="{{ route('employee') }}" :active="request()->routeIs('employee')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('employee') }}"></i> {{ App\Models\Page::getTitleByName('employee') }}
                            </x-responsive-nav-link>
							
							{{-- Banco de Horas --}}
                            <x-responsive-nav-link href="{{ route('clockbase') }}" :active="request()->routeIs('clockbase')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('clockbase') }}"></i> {{ App\Models\Page::getTitleByName('clockbase') }}
                            </x-responsive-nav-link>

                            {{-- Registro de ponto --}}
                            <x-responsive-nav-link href="{{ route('clockregistry') }}" :active="request()->routeIs('clockregistry')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('clockregistry') }}"></i> {{ App\Models\Page::getTitleByName('clockregistry') }}
                            </x-responsive-nav-link>

                            {{-- Tratamento de ponto --}}
								{{-- <x-responsive-nav-link href="{{ route('clockregistryemployee') }}" :active="request()->routeIs('clockregistryemployee')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('clockregistryemployee') }}"></i> {{ App\Models\Page::getTitleByName('clockregistryemployee') }}
								</x-responsive-nav-link>--}}

                            {{-- Feriado --}}
                            <x-responsive-nav-link href="{{ route('holiday') }}" :active="request()->routeIs('holiday')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('holiday') }}"></i> {{ App\Models\Page::getTitleByName('holiday') }}
                            </x-responsive-nav-link>

                            {{-- Folga --}}
                            <x-responsive-nav-link href="{{ route('employeeeasy') }}" :active="request()->routeIs('employeeeasy')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('employeeeasy') }}"></i> {{ App\Models\Page::getTitleByName('employeeeasy') }}
                            </x-responsive-nav-link>

                            {{-- Recebimento Horas --}}
                            <x-responsive-nav-link href="{{ route('employeepay') }}" :active="request()->routeIs('employeepay')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('employeepay') }}"></i> {{ App\Models\Page::getTitleByName('employeepay') }}
                            </x-responsive-nav-link>

                            {{-- Horas Avulsas --}}
                            <x-responsive-nav-link href="{{ route('employeeseparate') }}" :active="request()->routeIs('employeeseparate')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('employeeseparate') }}"></i> {{ App\Models\Page::getTitleByName('employeeseparate') }}
                            </x-responsive-nav-link>

                            {{-- Férias --}}
                            <x-responsive-nav-link href="{{ route('employeevacation') }}" :active="request()->routeIs('employeevacation')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('employeevacation') }}"></i> {{ App\Models\Page::getTitleByName('employeevacation') }}
                            </x-responsive-nav-link>

                            {{-- Atestado --}}
                            <x-responsive-nav-link href="{{ route('employeeattest') }}" :active="request()->routeIs('employeeattest')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('employeeattest') }}"></i> {{ App\Models\Page::getTitleByName('employeeattest') }}
                            </x-responsive-nav-link>

                            {{-- Licença --}}
                            <x-responsive-nav-link href="{{ route('employeelicense') }}" :active="request()->routeIs('employeelicense')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('employeelicense') }}"></i> {{ App\Models\Page::getTitleByName('employeelicense') }}
                            </x-responsive-nav-link>

                            {{-- Abono --}}
                            <x-responsive-nav-link href="{{ route('employeeallowance') }}" :active="request()->routeIs('employeeallowance')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('employeeallowance') }}"></i> {{ App\Models\Page::getTitleByName('employeeallowance') }}
                            </x-responsive-nav-link>

                            {{-- Falta --}}
                            <x-responsive-nav-link href="{{ route('employeeabsence') }}" :active="request()->routeIs('employeeabsence')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('employeeabsence') }}"></i> {{ App\Models\Page::getTitleByName('employeeabsence') }}
                            </x-responsive-nav-link>
                        </div>

                        <div class="pt-2 pb-0 border-t border-gray-200">
                            {{-- Produto --}}
                            <x-responsive-nav-link href="{{ route('product') }}" :active="request()->routeIs('product')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('product') }}"></i> {{ App\Models\Page::getTitleByName('product') }}
                            </x-responsive-nav-link>

                            {{-- Estoque de Produto --}}
                            <x-responsive-nav-link href="{{ route('stock') }}" :active="request()->routeIs('stock')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('stock') }}"></i> {{ App\Models\Page::getTitleByName('stock') }}
                            </x-responsive-nav-link>

                            {{-- Balanço --}}
                            <x-responsive-nav-link href="{{ route('balance') }}" :active="request()->routeIs('balance')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('balance') }}"></i> {{ App\Models\Page::getTitleByName('balance') }}
                            </x-responsive-nav-link>

                            {{-- Saída Depósito --}}
                            <x-responsive-nav-link href="{{ route('depositoutput') }}" :active="request()->routeIs('depositoutput')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('depositoutput') }}"></i> {{ App\Models\Page::getTitleByName('depositoutput') }}
                            </x-responsive-nav-link>
                        </div>

                        <div class="pt-2 pb-0 border-t border-gray-200">
                            {{-- Atividade --}}
                            <x-responsive-nav-link href="{{ route('activity') }}" :active="request()->routeIs('activity')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('activity') }}"></i> {{ App\Models\Page::getTitleByName('activity') }}
                            </x-responsive-nav-link>

                            {{-- Tarefa --}}
                            <x-responsive-nav-link href="{{ route('task') }}" :active="request()->routeIs('task')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('task') }}"></i> {{ App\Models\Page::getTitleByName('task') }}
                            </x-responsive-nav-link>
                        </div>

                        <div class="pt-2 pb-0 border-t border-gray-200">
                            {{-- Banco --}}
                            <x-responsive-nav-link href="{{ route('bank') }}" :active="request()->routeIs('bank')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('bank') }}"></i> {{ App\Models\Page::getTitleByName('bank') }}
                            </x-responsive-nav-link>

                            {{-- Documento --}}
                            <x-responsive-nav-link href="{{ route('document') }}" :active="request()->routeIs('document')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('document') }}"></i> {{ App\Models\Page::getTitleByName('document') }}
                            </x-responsive-nav-link>

                            {{-- Destino Conta --}}
                            <x-responsive-nav-link href="{{ route('accountdestiny') }}" :active="request()->routeIs('accountdestiny')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('accountdestiny') }}"></i> {{ App\Models\Page::getTitleByName('accountdestiny') }}
                            </x-responsive-nav-link>

                            {{-- Concessionária --}}
                            <x-responsive-nav-link href="{{ route('concessionaire') }}" :active="request()->routeIs('concessionaire')" class="text-decoration-none">
                                <i class="{{ App\Models\Page::getIconByName('concessionaire') }}"></i> {{ App\Models\Page::getTitleByName('concessionaire') }}
                            </x-responsive-nav-link>
                        </div>
                    </li>
                @endif

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
                            @if(Auth()->User()->usergroup_id != App\Models\Usergroup::where('name', 'FUNCIONARIO')->first()->id)
                                {{-- Profile --}}
                                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" class="text-decoration-none">
                                    <i class="bi-gear-fill"></i> {{ __('Profile') }}
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

                                {{-- Eventos --}}
                                {{--<x-responsive-nav-link href="{{ route('pointevent') }}" :active="request()->routeIs('pointevent')" class="text-decoration-none">
                                    <i class="{{ App\Models\Page::getIconByName('pointevent') }}"></i> {{ App\Models\Page::getTitleByName('pointevent') }}
                                </x-responsive-nav-link>--}}
                            @endif

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
