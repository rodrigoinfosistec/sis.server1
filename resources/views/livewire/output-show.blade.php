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
@include('components.' .  $config['name'] . '.modals.detail')
@include('components.' .  $config['name'] . '.modals.edit')
@include('components.' .  $config['name'] . '.modals.delete')
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
<option value="deposit_name">DEPÓSITO</option>
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
                <x-layout.card.card-body-content-table>
                    <x-layout.card.card-body-content-table-body>
                        @if($list->count() > 0)
                            @foreach($list as $item)
                                <x-layout.card.card-body-content-table-body-line>

{{-- conteúdo --}}
<x-layout.card.card-body-content-table-body-line-cell width="">
    <x-layout.card.card-body-content-table-body-line-cell-id>
        <x-layout.card.card-body-content-table-body-line-cell-id-badge>
            {{ str_pad($item->id, Str::length($list->count()), '0', STR_PAD_LEFT); }}
        </x-layout.card.card-body-content-table-body-line-cell-id-badge>

        <x-layout.card.card-body-content-table-body-line-cell-id-start>
            {{ $item->user_name }}
        </x-layout.card.card-body-content-table-body-line-cell-id-start>

        <x-layout.card.card-body-content-table-body-line-cell-id-end>
            {{ $item->created_at->format('d/m/y') }}
        </x-layout.card.card-body-content-table-body-line-cell-id-end>
    </x-layout.card.card-body-content-table-body-line-cell-id>

    <x-layout.card.card-body-content-table-body-line-cell-content>
        <div style="line-height: 1;">
            {{ $item->deposit_name }}
            <br>
            <span class="text-muted" style="font-size: 7pt;">
                {{ $item->observation }}
            </span>
        </div>
    </x-layout.card.card-body-content-table-body-line-cell-content>
</x-layout.card.card-body-content-table-body-line-cell>

<x-layout.card.card-body-content-table-body-line-cell-action width="80">
    @if(!$item->finished)
        <x-layout.card.card-body-content-table-body-line-cell-action-edit-output :id="$item->id"/>

        <x-layout.card.card-body-content-table-body-line-cell-action-add-product :id="$item->id"/>
    @else
        <x-layout.card.card-body-content-table-body-line-cell-action-detail :id="$item->id"/>

        <x-layout.card.card-body-content-table-body-line-cell-action-print-output :id="$item->id"/>
    @endif
</x-layout.card.card-body-content-table-body-line-cell-action>
{{-- conteúdo --}} 

                                </x-layout.card.card-body-content-table-body-line>
                            @endforeach

                            @foreach($list as $item)
                                <x-layout.card.card-body-content-table-body-line>
                                    <div class="accordion accordion-flush" id="accordionOutput">
{{-- conteúdo --}}
<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button collapsed" style="padding-top: 5px; padding-bottom: 5px;" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $item->id }}" aria-expanded="false" aria-controls="flush-collapse{{ $item->id }}">
            <div class="w-100">
                <div class="float-start" style="height: 50px; width: 150px; background-color: #ddd">
                    {{ $item->id }} 
                    {{ $item->user->name }}
                    <br>
                    {{ $item->deposit->name }}
                </div>

                <div class="float-end" style="height: 50px; width: 75px; background-color: #ddd">
                    @if(!$item->finished)
                        <x-layout.card.card-body-content-table-body-line-cell-action-edit-output :id="$item->id"/>
                
                        <x-layout.card.card-body-content-table-body-line-cell-action-add-product :id="$item->id"/>
                    @else
                        <x-layout.card.card-body-content-table-body-line-cell-action-detail :id="$item->id"/>
                
                        <x-layout.card.card-body-content-table-body-line-cell-action-print-output :id="$item->id"/>
                    @endif
                </div>
            </div>
        </button>
    </h2>

    <div id="flush-collapse{{ $item->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionOutput">
        <div class="accordion-body" style="line-height: 1.2">
            @if(App\Models\Outputproduct::where('output_id', $item->id)->exists())
                <ol class="list-group list-group-numbered">
                    @foreach(App\Models\Outputproduct::where('output_id', $item->id)->get() as $key => $outputproduct)
                        <li class="list-group-item d-flex justify-content-between align-items-start" style="font-size: 9pt;">
                            <div class="ms-2 me-auto text-dark" style="font-size: 9pt;">
                                {{ $outputproduct->product->name }}
                            </div>
                            <span class="badge text-bg-secondary rounded-pill">{{ $outputproduct->product->quantity }}</span>
                        </li>
                    @endforeach
                </ol>
            @else
                <div class="text-muted text-center fw-bold" style="padding-bottom: 20px;">
                    <i class="bi bi-archive-fill"></i>
                    Nenhum Produto cadastrado nesta Saída
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
    