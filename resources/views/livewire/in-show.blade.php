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
@include('components.' .  $config['name'] . '.modals.add-produce')
@include('components.' .  $config['name'] . '.modals.edit')
@include('components.' .  $config['name'] . '.modals.erase')
{{-- modal --}}

<x-layout.alert/>

<x-layout.card.card>
    <x-layout.card.card-header>
        <x-layout.card.card-header-button>
            <x-layout.card.card-header-button-action>
                <x-layout.card.card-header-button-action-refresh href="{{ $config['name'] }}"/>

{{-- botão relatório --}}
<x-layout.card.card-header-button-action-generate-muted/>

<x-layout.card.card-header-button-action-mail-muted/>
{{-- botão relatório --}}

                    <x-layout.card.card-header-button-action-print-muted/>
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
            {{ $item->deposit_name }}

            @if(!$item->finished)
                <span class="badge text-bg-danger fw-bold" style="">
                    ABERTO
                </span>
            @endif
        </x-layout.card.card-body-content-table-body-line-cell-id-start>

        <x-layout.card.card-body-content-table-body-line-cell-id-end>
            {{ $item->updated_at->format('d/m/y H:m') }}
        </x-layout.card.card-body-content-table-body-line-cell-id-end>
    </x-layout.card.card-body-content-table-body-line-cell-id>

    <x-layout.card.card-body-content-table-body-line-cell-content>
        <div style="line-height: 1;">
            @if($item->observation != '')
                <span class="text-muted" style="font-size: 7pt;">
                    {{ $item->observation }}
                </span>
            @endif
        </div>
    </x-layout.card.card-body-content-table-body-line-cell-content>
</x-layout.card.card-body-content-table-body-line-cell>

<x-layout.card.card-body-content-table-body-line-cell-action width="120">
    @if(!$item->finished)
        <x-layout.card.card-body-content-table-body-line-cell-action-add-produce :id="$item->id"/>

        @if(App\Models\Inproduce::where('in_id', $item->id)->exists())
            <x-layout.card.card-body-content-table-body-line-cell-action-edit :id="$item->id"/>
        @endif

        <x-layout.card.card-body-content-table-body-line-cell-action-erase :id="$item->id"/>
    @else
        <x-layout.card.card-body-content-table-body-line-cell-action-detail :id="$item->id"/>

        <a href="{{ asset('storage/pdf/in/' . @App\Models\Report::where(['folder' => 'in', 'reference_1' => $item->id, 'reference_2' => auth()->user()->company_id])->first()->file) }}" target="_blank" class="btn btn-link btn-sm" style="padding: 0px 5px 0px 5px;" title="PDF">
            <i class="bi-file-pdf text-secondary" style="font-size: 20px;"></i>
        </a>
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
