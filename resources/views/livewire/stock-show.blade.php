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
{{-- modal --}}

    <x-layout.alert/>

    <x-layout.card.card>
        <x-layout.card.card-header>
            <x-layout.card.card-header-button>
                <x-layout.card.card-header-button-action>
                    <x-layout.card.card-header-button-action-refresh href="{{ $config['name'] }}"/>

{{-- botão relatório --}}
<x-layout.card.card-header-button-action-generate/>

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
<option value="name">DESCRIÇÃO</option>
<option value="code">CÓDIGO</option>
<option value="ean">EAN</option>
<option value="reference">REFERÊNCIA</option>
{{-- filtro nome --}}

                    </x-layout.card.card-body-navigation-search-filter>

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
            {{ $item->code }}
        </x-layout.card.card-body-content-table-body-line-cell-id-badge>
        
        <x-layout.card.card-body-content-table-body-line-cell-id-start>
            {{ $item->ean }} <span class="text-muted">{{ $item->reference }}</span>
        </x-layout.card.card-body-content-table-body-line-cell-id-start>

        <x-layout.card.card-body-content-table-body-line-cell-id-end>
            @php
                if(!empty(App\Models\Company::find(auth()->user()->company_id)->depositdefault_id)):
                    if(App\Models\Productdeposit::where(['product_id' => $item->id, 'deposit_id' => App\Models\Company::find(auth()->user()->company_id)->depositdefault_id])->exists()):
                        $quantity = App\Models\Productdeposit::where(['product_id' => $item->id, 'deposit_id' => App\Models\Company::find(auth()->user()->company_id)->depositdefault_id])->first()->quantity;
                    endif;
                else:
                    $quantity = 0.0;
                endif;
            @endphp
            <div class="fw-bold" style="font-size: 10pt;">
                @if($item->quantity > 0) <span class="text-primary">
                    @elseif($item->quantity < 0) <span class="text-danger">
                    @else <span class="text-muted"> @endif
                        @if((float)$item->quantity - (float)$quantity < 0)
                            {{ App\Models\General::decodeFloat2($item->quantity) }}
                        @else
                            {{ App\Models\General::decodeFloat2((float)$item->quantity - (float)$quantity) }}
                        @endif
                </span>
            </div>
        </x-layout.card.card-body-content-table-body-line-cell-id-end>
    </x-layout.card.card-body-content-table-body-line-cell-id>

    <x-layout.card.card-body-content-table-body-line-cell-content>
        <div style="line-height: 1;">
            <span class="text-primary fw-bold">
                {{ $item->name }}
            </span>
            <br>
            @foreach (App\Models\Deposit::where('company_id', auth()->user()->company_id)->whereNot('id', App\Models\Company::find(auth()->user()->company_id)->depositdefault_id)->get() as $key => $deposit)
                <div style="font-size: 9pt; margin-top: 5px;">
                    <span class="text-muted">{{ $loop->iteration }}.</span>
                    {{ $deposit->name }}<i class="bi-caret-right-fill text-muted"></i>
                    @if(App\Models\Productdeposit::where(['product_id' => $item->id, 'deposit_id' => $deposit->id])->exists())
                        @if(App\Models\Productdeposit::where(['product_id' => $item->id, 'deposit_id' => $deposit->id])->first()->quantity > 0)
                            <span class="text-success fw-bold">
                        @else
                            <span class="text-muted fw-bold">
                        @endif
                                {{ App\Models\General::decodeFloat2(App\Models\Productdeposit::where(['product_id' => $item->id, 'deposit_id' => $deposit->id])->first()->quantity) }}
                            </span>
                    @else
                        <span class="text-muted fw-bold">
                            0,00
                        </span>
                    @endif

                    @if(!$loop->last)
                        <br>
                    @endif
                </div>
            @endforeach
        </div>
    </x-layout.card.card-body-content-table-body-line-cell-content>
</x-layout.card.card-body-content-table-body-line-cell>

<x-layout.card.card-body-content-table-body-line-cell-action width="">
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
