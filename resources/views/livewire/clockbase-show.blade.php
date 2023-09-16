<x-layout.container>

    {{-- modal --}}
    {{-- botões --}}
    
    {{-- plus --}}
    
    {{-- info --}}
    
    
    {{-- ações --}}
    @include('components.' .  $config['name'] . '.modals.add-easy')
    @include('components.' .  $config['name'] . '.modals.detail')

    {{-- modal --}}
    
        <x-layout.alert/>
    
        <x-layout.card.card>
            <x-layout.card.card-header>
                <x-layout.card.card-header-button>
                    <x-layout.card.card-header-button-action>
                        <x-layout.card.card-header-button-action-refresh href="{{ $config['name'] }}"/>
    
    {{-- botão relatório --}}
    @if($existsItem)
        <x-layout.card.card-header-button-action-generate-muted/>
    @else
        <x-layout.card.card-header-button-action-generate-muted/>
    @endif
    
    @if($existsReport)
        <x-layout.card.card-header-button-action-mail-muted/>
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
    <x-layout.card.card-header-button-more-plus-muted/>
    {{-- botão add --}}
    
                    </x-layout.card.card-header-button-more>
                </x-layout.card.card-header-button>
            </x-layout.card.card-header>
    
            <x-layout.card.card-body>
                <x-layout.card.card-body-navigation>
                    <x-layout.card.card-body-navigation-search :filter="$filter">
                        <x-layout.card.card-body-navigation-search-filter>
    
    {{-- filtro nome --}}
    <option value="pis">PIS</option>
    <option value="name">NOME</option>
    <option value="created_at">DATA CADASTRO</option>
    {{-- filtro nome --}}
    
                            </x-layout.card.card-body-navigation-search-filter>
    
    {{-- filtro tipo--}}
    @if($filter == 'created_at')
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
        <x-layout.card.card-body-content-table-body-line-cell-id>
            <x-layout.card.card-body-content-table-body-line-cell-id-badge>
                {{ str_pad($loop->iteration, Str::length($list->count()), '0', STR_PAD_LEFT); }}
            </x-layout.card.card-body-content-table-body-line-cell-id-badge>
    
            <x-layout.card.card-body-content-table-body-line-cell-id-start>
                <span class="text-muted">{{ $item->pis }}</span>
            </x-layout.card.card-body-content-table-body-line-cell-id-start>
    
            <x-layout.card.card-body-content-table-body-line-cell-id-end>
                {{ $item->created_at->format('d/m/y') }}
            </x-layout.card.card-body-content-table-body-line-cell-id-end>
        </x-layout.card.card-body-content-table-body-line-cell-id>
    
        <x-layout.card.card-body-content-table-body-line-cell-content>
            {{ $item->name }}
        </x-layout.card.card-body-content-table-body-line-cell-content>
    </x-layout.card.card-body-content-table-body-line-cell>
    
    <x-layout.card.card-body-content-table-body-line-cell-action width="120">
        <x-layout.card.card-body-content-table-body-line-cell-action-add-easy :id="$item->id"/>

        <x-layout.card.card-body-content-table-body-line-cell-action-detail :id="$item->id"/>
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
    