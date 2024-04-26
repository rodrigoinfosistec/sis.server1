<x-layout.container>

{{-- modal --}}
{{-- botões --}}
@include('components.' .  $config['name'] . '.modals.generate')
@include('components.' .  $config['name'] . '.modals.mail')

{{-- plus --}}
@include('components.' .  $config['name'] . '.modals.add')

{{-- info --}}


{{-- ações --}}
@include('components.' .  $config['name'] . '.modals.detail')
@include('components.' .  $config['name'] . '.modals.edit')
@include('components.' .  $config['name'] . '.modals.edit-password')
@include('components.' .  $config['name'] . '.modals.reset-password')
@include('components.' .  $config['name'] . '.modals.erase')
{{-- modal --}}

    <x-layout.alert/>

    <x-layout.card.card>
        <x-layout.card.card-header>
            <x-layout.card.card-header-button>
                <x-layout.card.card-header-button-action>
                    <x-layout.card.card-header-button-action-refresh href="{{ $config['name'] }}"/>

{{-- botão relatório --}}
@if($existsItem)
    <x-layout.card.card-header-button-action-generate/>
@else
    <x-layout.card.card-header-button-action-generate-muted/>
@endif

@if($existsReport)
    <x-layout.card.card-header-button-action-mail/>
@else
    <x-layout.card.card-header-button-action-mail-muted/>
@endif
{{-- botão relatório --}}

                    @if($existsReport)
                        <x-layout.card.card-header-button-action-print>
                            <x-layout.card.card-header-button-action-print-for :config="$config" :reports="$reports"/>
                        </x-layout.card.card-header-button-print>
                    @else
                        <x-layout.card.card-header-button-action-print-muted/>
                    @endif
                </x-layout.card.card-header-button-action>

                <x-layout.card.card-header-button-more>

{{-- botão add --}}
<x-layout.card.card-header-button-more-plus/>
{{-- botão add --}}

                </x-layout.card.card-header-button-more>                    
            </x-layout.card.card-header-button>
        </x-layout.card.card-header>

        <x-layout.card.card-body>
            <x-layout.card.card-body-navigation>
                <x-layout.card.card-body-navigation-search>
                    <x-layout.card.card-body-navigation-search-filter>

{{-- filtro nome --}}
<option value="name">NOME</option>
<option value="email">EMAIL</option>
<option value="usergroup_name">GRUPO USUÁRIO</option>
<option value="company_name">EMPRESA</option>
<option value="status">STATUS</option>
<option value="created_at">DATA CADASTRO</option>
{{-- filtro nome --}}

                        </x-layout.card.card-body-navigation-search-filter>

{{-- filtro tipo--}}
@if($filter == 'email')
    <x-layout.card.card-body-navigation-search-type-email/>
@elseif($filter == 'status')
    <x-layout.card.card-body-navigation-search-type-status/>
@elseif($filter == 'created_at')
    <x-layout.card.card-body-navigation-search-type-date/>
@else
    <x-layout.card.card-body-navigation-search-type-search/>
@endif
{{-- filtro tipo--}}
                </x-layout.card.card-body-navigation-search>

                <x-layout.card.card-body-navigation-info>
                    <x-layout.card.card-body-navigation-info-action>

{{-- info action --}}

{{-- info action --}}

                    </x-layout.card.card-body-navigation-info-action>

                    <x-layout.card.card-body-navigation-info-count :count="$list->total()"/>
                </x-layout.card.card-body-navigation-info>
            </x-layout.card.card-body-navigation>

            <x-layout.card.card-body-content>
                <x-layout.card.card-body-content-table>
                    <x-layout.card.card-body-content-table-body>
                        @if($list->count() > 0)
                            @foreach($list as $item)
                                <x-layout.card.card-body-content-table-body-line>

{{-- conteúdo --}}
<x-layout.card.card-body-content-table-body-line-cell width="">
    @if($item->profile_photo_path)
        <img src="{{ asset('storage/' . $item->profile_photo_path) }}" class="float-start rounded-circle" style="width: 30px; height: 30px;">
    @else
        <i class="bi-person-circle" style="font-size: 30px;"></i>
    @endif
</x-layout.card.card-body-content-table-body-line-cell>

<x-layout.card.card-body-content-table-body-line-cell width="">
    <x-layout.card.card-body-content-table-body-line-cell-id>
        <x-layout.card.card-body-content-table-body-line-cell-id-badge>
            {{ str_pad($item->id, Str::length($list->count()), '0', STR_PAD_LEFT); }}
        </x-layout.card.card-body-content-table-body-line-cell-id-badge>
        
        <x-layout.card.card-body-content-table-body-line-cell-id-start>
            @if($item->status)
                <span class="text-success">Ativo</span>
            @else
                <span class="text-danger">Inativo</span>
            @endif
        </x-layout.card.card-body-content-table-body-line-cell-id-start>

        <x-layout.card.card-body-content-table-body-line-cell-id-end>
            {{ $item->created_at->format('d/m/y') }}
        </x-layout.card.card-body-content-table-body-line-cell-id-end>
    </x-layout.card.card-body-content-table-body-line-cell-id>

    <x-layout.card.card-body-content-table-body-line-cell-content>
        {{ $item->name }}
        <br>
        <span class="text-muted">
            {{ $item->email }}
            <br>
            {{ $item->usergroup_name }}
            <br>
            {{ $item->company_name }}
        </span>
    </x-layout.card.card-body-content-table-body-line-cell-content>
</x-layout.card.card-body-content-table-body-line-cell>

<x-layout.card.card-body-content-table-body-line-cell-action width="180">
    <x-layout.card.card-body-content-table-body-line-cell-action-detail :id="$item->id"/>

    @if(Auth::user()->id == $item->id)
        <x-layout.card.card-body-content-table-body-line-cell-action-edit-muted/>

        <x-layout.card.card-body-content-table-body-line-cell-action-edit-password-muted/>

        <x-layout.card.card-body-content-table-body-line-cell-action-edit-reset-muted/>

        <x-layout.card.card-body-content-table-body-line-cell-action-erase-muted/>
    @else
        <x-layout.card.card-body-content-table-body-line-cell-action-edit :id="$item->id"/>

        <x-layout.card.card-body-content-table-body-line-cell-action-edit-password :id="$item->id"/>

        <x-layout.card.card-body-content-table-body-line-cell-action-edit-reset :id="$item->id"/>

        <x-layout.card.card-body-content-table-body-line-cell-action-erase :id="$item->id"/>
    @endif
</x-layout.card.card-body-content-table-body-line-cell-action>
{{-- conteúdo --}} 

                                </x-layout.card.card-body-content-table-body-line>
                            @endforeach
                        @else
                            <x-layout.card.card-body-content-table-body-item-none/>
                        @endif
                    </x-layout.card.card-body-content-table-body>
                </x-layout.card.card-body-content-table>

                <x-layout.card.card-body-content-pagination :list="$list"/>
            </x-layout.card.card-body-content>
        </x-layout.card.card-body>
    </x-layout.card.card>
</x-layout.container>
