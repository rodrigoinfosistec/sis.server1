<x-layout.container>

    {{-- modal --}}
    {{-- botões --}}
    @include('components.' .  $config['name'] . '.modals.generate')
    @include('components.' .  $config['name'] . '.modals.mail')
    
    {{-- plus --}}
    @include('components.' .  $config['name'] . '.modals.add')
    
    {{-- info --}}
    
    {{-- ações --}}
    @include('components.' .  $config['name'] . '.modals.add-product')
    @include('components.' .  $config['name'] . '.modals.add-funded')
    @include('components.' .  $config['name'] . '.modals.erase')
    
    {{-- ações específicas --}}
    @include('components.' .  $config['name'] . '.modals.erase-product')
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
                    <x-layout.card.card-body-navigation-search :filter="$filter">
                        <x-layout.card.card-body-navigation-search-filter>
    
    {{-- filtro nome --}}
    <option value="origin_name">ORIGEM</option>
    <option value="destiny_name">DESTINO</option>
    <option value="user_name">USUÁRIO</option>
    <option value="observation">OBSERVAÇÃO</option>
    {{-- filtro nome --}}
    
                        </x-layout.card.card-body-navigation-search-filter>
    
    {{-- filtro tipo--}}
    <x-layout.card.card-body-navigation-search-type-search/>
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
                    @if(App\Models\Deposittransfer::where('funded', false)->exists())
                        <div style="width: 100%;">
                            <div class="float-start" style="width: 10px; height: 10px; margin-bottom: 5px; margin-right: 5px; background-color: #ffff7a; border: solid 1px #ff0000;"></div>
                            <div class="text-danger float-start fw-bold" style="font-size: 8pt; padding: 0; margin-top: -3px;">
                                ATENÇÃO: 
                                <span class="text-primary">
                                    {{ App\Models\Deposittransfer::where('funded', false)->count() }}
                                </span>
                                TRANSFERÊNCIA(S) NÃO CONSOLIDADA(S).
                            </div>
                        </div>
                    @endif
    
                    <x-layout.card.card-body-content-table>
                        <x-layout.card.card-body-content-table-body>
                            @if($list->count() > 0)
                                @foreach($list as $item)
                                    <x-layout.card.card-body-content-table-body-line>
                                        <div class="accordion accordion-flush" id="accordionOutput">
    
    {{-- conteúdo --}}
    <div class="accordion-item" style="border-bottom: 1px solid #ddd;">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" style="padding-top: 5px; padding-bottom: 5px; @if(!$item->funded) background-color: #ffff7a; border: solid 1px #ff0000; @endif" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $item->id }}" aria-expanded="false" aria-controls="flush-collapse{{ $item->id }}">
                <div class="w-100">
                    <div class="float-start" style="min-width: 240px; font-size: 8pt;">
                        <x-layout.card.card-body-content-table-body-line-cell-id-badge>
                            {{ str_pad($item->id, Str::length($list->count()), '0', STR_PAD_LEFT); }}
                        </x-layout.card.card-body-content-table-body-line-cell-id-badge>
                        &nbsp;&nbsp;{{ $item->user->name }}
                        <br>
                        <i class="bi bi-caret-left-fill text-danger"></i><span class="text-primary">{{ $item->origin->name }}</span>
                        <br>
                        <span class="text-muted">
                            {{ $item->created_at->format('d/m/y') }}
                            <i class="bi bi-caret-right-fill"></i>
                            {{ $item->observation }}
                        </span>
                        <br>
                        @if(!$item->funded)
                            <x-layout.card.card-body-content-table-body-line-cell-action-add-product :id="$item->id"/>
    
                            <x-layout.card.card-body-content-table-body-line-cell-action-erase :id="$item->id"/>
    
                            @if(App\Models\Deposittransferproduct::where('deposittransfer_id', $item->id)->exists())
                                <x-layout.card.card-body-content-table-body-line-cell-action-add-funded :id="$item->id"/>
                            @endif
                        @else
                            <x-layout.card.card-body-content-table-body-line-cell-action-print-deposittransfer :id="$item->id"/>
                        @endif
                    </div>
                </div>
            </button>
        </h2>
    
        <div id="flush-collapse{{ $item->id }}" class="accordion-collapse collapse @if($loop->first) show @endif" data-bs-parent="#accordionOutput">
            <div class="accordion-body" style="line-height: 1.2">
                @if(App\Models\Deposittransferproduct::where('deposittransfer_id', $item->id)->exists())
                    <span class="text-muted" style="font-size:8pt;">
                        #. INT | DESCRIÇÃO | EAN | REF
                    </span>
                    <ol class="list-group list-group-numbered">
                        @foreach(App\Models\Deposittransferproduct::where('deposittransfer_id', $item->id)->orderBy('product_name', 'ASC')->get() as $key => $deposittransferproduct)
                            <li class="list-group-item d-flex justify-content-between align-items-start" style="font-size: 9pt;">
                                <div class="ms-2 me-auto text-dark" style="font-size: 8.5pt;">
                                    {{ $deposittransferproduct->product->code }} |
                                    {{ $deposittransferproduct->product->name }} |
                                    {{ $deposittransferproduct->product->ean }} |
                                    {{ $deposittransferproduct->product->reference }}
                                    @if(!$item->funded)
                                        <x-layout.card.card-body-content-table-body-line-cell-action-erase-deposittransferproduct :id="$deposittransferproduct->id"/>
                                    @endif
                                </div>
                                <span class="badge text-bg-primary rounded-pill">{{ number_format($deposittransferproduct->quantity) }}</span>
    
                            </li>
                        @endforeach
                    </ol>
                @else
                    <div class="text-muted text-center fw-bold" style="padding-bottom: 20px;">
                        <i class="bi bi-archive-fill"></i>
                        Nenhum Produto encontrado.
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- conteúdo --}}

                                        </div>
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
    