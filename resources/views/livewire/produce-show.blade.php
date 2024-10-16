<x-layout.container>

{{-- modal --}}
{{-- botões --}}
@include('components.' .  $config['name'] . '.modals.generate')
@include('components.' .  $config['name'] . '.modals.mail')

{{-- plus --}}
@include('components.' .  $config['name'] . '.modals.add')
@include('components.' .  $config['name'] . '.modals.add-xml')
@include('components.' .  $config['name'] . '.modals.add-txt')

{{-- info --}}


{{-- ações --}}
@include('components.' .  $config['name'] . '.modals.detail')
@include('components.' .  $config['name'] . '.modals.edit')
@include('components.' .  $config['name'] . '.modals.erase')
@include('components.' .  $config['name'] . '.modals.generate-moviment')
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

<x-layout.card.card-header-button-action-mail-muted/>
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
<option value="name">DESCRIÇÃO</option>
<option value="producebrand_name">MARCA</option>
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
            {{ $loop->iteration }}
        </x-layout.card.card-body-content-table-body-line-cell-id-badge>

        <x-layout.card.card-body-content-table-body-line-cell-id-start>
            <span class="text-muted">
                @if(isset($item->ean))
                    {{ $item->ean }}
                @endif
            </span>
        </x-layout.card.card-body-content-table-body-line-cell-id-start>

        <x-layout.card.card-body-content-table-body-line-cell-id-end>
            {{-- $item->producemeasure_name --}}
        </x-layout.card.card-body-content-table-body-line-cell-id-end>
    </x-layout.card.card-body-content-table-body-line-cell-id>

    <x-layout.card.card-body-content-table-body-line-cell-content>
        <div style="line-height: 1;">
            <span class="text-primary fw-bold" style="font-size:9pt;">
                {{ $item->name }}
            </span>

            <br>

            <span class="text-muted">
                @if(isset($item->reference))
                    {{ $item->reference }}

                    <i class="bi-caret-right-fill"></i>
                @endif

                {{ $item->producebrand_name }}
            </span>

            {{-- DEPÓSITO(S) --}}
            <div class="fw-bold text-dark" style="font-size: 9pt;">
                @foreach(App\Models\Producedeposit::where(['produce_id' => $item->id])->get() as $key => $producedeposit)
                    {{ $producedeposit->deposit->name }}<span class="text-muted"><i class="bi-caret-right-fill"></i></span>
                    <span class="text-primary" style="font-size: 10pt;">
                        {{ number_format($producedeposit->quantity) }}
                    </span>
                    {{ $item->producemeasure_name }}

                    @if(!$loop->last)
                        <br>
                    @endif
                @endforeach
            </div>
        </div>
    </x-layout.card.card-body-content-table-body-line-cell-content>
</x-layout.card.card-body-content-table-body-line-cell>

<x-layout.card.card-body-content-table-body-line-cell-action width="120">
    <x-layout.card.card-body-content-table-body-line-cell-action-detail :id="$item->id"/>

    <x-layout.card.card-body-content-table-body-line-cell-action-generate-produce-moviment :id="$item->id"/>
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
