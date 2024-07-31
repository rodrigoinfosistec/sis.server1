<x-layout.container>

{{-- modal --}}
{{-- botões --}}
@include('components.' .  $config['name'] . '.modals.generate')
@include('components.' .  $config['name'] . '.modals.mail')

{{-- plus --}}
@include('components.' .  $config['name'] . '.modals.add-xml')

{{-- info --}}


{{-- ações --}}
@include('components.' .  $config['name'] . '.modals.add-product')
@include('components.' .  $config['name'] . '.modals.edit-item-relates')
@include('components.' .  $config['name'] . '.modals.edit-item-amount')
@include('components.' .  $config['name'] . '.modals.erase')
@include('components.' .  $config['name'] . '.modals.erase-product')

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
<x-layout.card.card-header-button-more-plus-xml/>

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
<option value="deposit_name">DEPÓSITO</option>
<option value="key">CHAVE NFE</option>
<option value="range">SÉRIE NFE</option>
<option value="issue">DATA EMISSÃO</option>
<option value="provider_name">FORNECEDOR</option>
<option value="user_name">USUÁRIO</option>
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
            {{ $item->created_at->format('d/m/y') }}
        </x-layout.card.card-body-content-table-body-line-cell-id-end>
    </x-layout.card.card-body-content-table-body-line-cell-id>

    <x-layout.card.card-body-content-table-body-line-cell-content>
        {{ $item->provider_name }}
        <br>

        <div class="text-muted" style="font-size: 7pt; line-height: 1;">
            {{ $item->deposit_name }}
            <br>
            <span class="text-primary" style="font-size: 9pt;">
                <span class="fst-italic">{{ $item->observation }}</span>
            </span>
        </div>
    </x-layout.card.card-body-content-table-body-line-cell-content>
</x-layout.card.card-body-content-table-body-line-cell>

<x-layout.card.card-body-content-table-body-line-cell-action width="150">
    <x-layout.card.card-body-content-table-body-line-cell-action-erase :id="$item->id"/>

    @php
        $arr = [];

        foreach(App\Models\Depositinputitem::where('depositinput_id', $item->id)->get() as $key => $depositinputitem):
            if(App\Models\Depositinputproduct::where(['depositinput_id' => $item->id, 'identifier' => $depositinputitem->identifier])):
                $arr[] = 1;
            endif;
        endforeach;
    @endphp

    @if(App\Models\Depositinputitem::where('depositinput_id', $item->id)->count() != App\Models\Depositinputproduct::where('depositinput_id', $item->id)->count())
        <x-layout.card.card-body-content-table-body-line-cell-action-edit-item-relates :id="$item->id"/>
    @else
        <x-layout.card.card-body-content-table-body-line-cell-action-edit-item-amount :id="$item->id"/>
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
