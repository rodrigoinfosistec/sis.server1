<x-layout.container>

{{-- modal --}}
{{-- botões --}}
@include('components.' .  $config['name'] . '.modals.generate')
@include('components.' .  $config['name'] . '.modals.mail')

{{-- plus --}}
@include('components.' .  $config['name'] . '.modals.add')

{{-- info --}}


{{-- ações --}}
@include('components.' .  $config['name'] . '.modals.detail-alert')
@include('components.' .  $config['name'] . '.modals.add-efisco')
@include('components.' .  $config['name'] . '.modals.edit-business')
@include('components.' .  $config['name'] . '.modals.edit-item')
@include('components.' .  $config['name'] . '.modals.edit-item-amount')
@include('components.' .  $config['name'] . '.modals.edit-item-price')
@include('components.' .  $config['name'] . '.modals.erase')
@include('components.' .  $config['name'] . '.modals.mail-price')

{{-- extra --}}

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

<x-layout.card.card-header-button-more-plus-down-muted/>
{{-- botão add --}}

                </x-layout.card.card-header-button-more>
            </x-layout.card.card-header-button>
        </x-layout.card.card-header>

        <x-layout.card.card-body>
            <x-layout.card.card-body-navigation>
                <x-layout.card.card-body-navigation-search :filter="$filter">
                    <x-layout.card.card-body-navigation-search-filter>

{{-- filtro nome --}}
<option value="number">NÚMERO NFE</option>
<option value="key">CHAVE NFE</option>
<option value="range">SÉRIE NFE</option>
<option value="issue">DATA EMISSÃO</option>
<option value="provider_name">FORNECEDOR</option>
<option value="company_name">EMPRESA</option>
{{-- filtro nome --}}

                        </x-layout.card.card-body-navigation-search-filter>

{{-- filtro tipo--}}
@if($filter == 'issue')
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
            {{ str_pad($item->id, Str::length($list->count()), '0', STR_PAD_LEFT); }}
        </x-layout.card.card-body-content-table-body-line-cell-id-badge>

        <x-layout.card.card-body-content-table-body-line-cell-id-start>
            {{ $item->number }}
        </x-layout.card.card-body-content-table-body-line-cell-id-start>

        <x-layout.card.card-body-content-table-body-line-cell-id-end>
            {{ App\Models\Invoice::decodeIssue($item->issue) }}
        </x-layout.card.card-body-content-table-body-line-cell-id-end>
    </x-layout.card.card-body-content-table-body-line-cell-id>

    <x-layout.card.card-body-content-table-body-line-cell-content>
        {{ $item->provider_name }}

        <br>

        <span class="text-muted">
            {{ $item->key }}
        </span>

        <br>

        <span class="text-primary">
            <span class="fst-italic">R$</span>
            {{ App\Models\General::decodeFloat2($item->total) }}
        </span>
    </x-layout.card.card-body-content-table-body-line-cell-content>
</x-layout.card.card-body-content-table-body-line-cell>

<x-layout.card.card-body-content-table-body-line-cell-action width="150">
    <x-layout.card.card-body-content-table-body-line-cell-action-edit-business :id="$item->id"/>

    @if(App\Models\Invoiceefisco::where('invoice_id', $item->id)->exists())
        <x-layout.card.card-body-content-table-body-line-cell-action-add-efisco-on :id="$item->id"/>
    @else
        <x-layout.card.card-body-content-table-body-line-cell-action-add-efisco :id="$item->id"/>
    @endif

    @if(App\Models\Invoiceefisco::where('invoice_id', $item->id)->exists() && App\Models\Invoiceitem::where('invoice_id', $item->id)->get()->count() == App\Models\Invoicecsv::where('invoice_id', $item->id)->get()->count())
        @if(App\Models\Invoiceitem::where(['invoice_id' => $item->id, 'index' => NULL])->exists())
            <x-layout.card.card-body-content-table-body-line-cell-action-edit-item :id="$item->id"/>
        @else
            <x-layout.card.card-body-content-table-body-line-cell-action-edit-item-amount :id="$item->id"/>
        @endif
    @else
        <x-layout.card.card-body-content-table-body-line-cell-action-edit-item-muted/>
    @endif

    <x-layout.card.card-body-content-table-body-line-cell-action-erase :id="$item->id"/>

    @if(App\Models\Invoice::alerts($item->id)['amount'] > 0)
        <x-layout.card.card-body-content-table-body-line-cell-action-detail-alert :id="$item->id"/>
    @else
        <x-layout.card.card-body-content-table-body-line-cell-action-edit-item-price :id="$item->id"/>
    @endif

    @if(App\Models\Csv::where(['folder' => 'zip/price', 'reference_1' => $item->id])->exists())
        <x-layout.card.card-body-content-table-body-line-cell-action-zip :id="$item->id"/>
    @else
        <x-layout.card.card-body-content-table-body-line-cell-action-zip-muted :id="$item->id"/>
    @endif

    @if(App\Models\Csv::where(['folder' => 'zip/price', 'reference_1' => $item->id])->exists())
        <x-layout.card.card-body-content-table-body-line-cell-action-mail :id="$item->id"/>
    @else
        <x-layout.card.card-body-content-table-body-line-cell-action-mail-muted :id="$item->id"/>
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
