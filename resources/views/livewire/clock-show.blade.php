<x-layout.container>

{{-- modal --}}
{{-- botões --}}
@include('components.' .  $config['name'] . '.modals.generate')
@include('components.' .  $config['name'] . '.modals.mail')

{{-- plus --}}
@include('components.' .  $config['name'] . '.modals.add-txt')
@include('components.' .  $config['name'] . '.modals.add')

{{-- info --}}

{{-- ações --}}
@include('components.' .  $config['name'] . '.modals.add-employee')
@include('components.' .  $config['name'] . '.modals.add-holiday')
@include('components.' .  $config['name'] . '.modals.detail')
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
<x-layout.card.card-header-button-more-plus-txt/>
{{-- botão add --}}

                </x-layout.card.card-header-button-more>
            </x-layout.card.card-header-button>
        </x-layout.card.card-header>

        <x-layout.card.card-body>
            <x-layout.card.card-body-navigation>
                <x-layout.card.card-body-navigation-search :filter="$filter">
                    <x-layout.card.card-body-navigation-search-filter>

{{-- filtro nome --}}
<option value="company_name">EMPRESA</option>
<option value="start">INÍCIO</option>
<option value="end">FINAL</option>
{{-- filtro nome --}}

                        </x-layout.card.card-body-navigation-search-filter>

{{-- filtro tipo--}}
@if($filter == 'start' || $filter == 'end')
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
        <x-layout.card.card-body-content-table-body-line-cell-id-start>
            <span class="text-muted">
                {{ App\Models\General::decodeDate($item->start) }}
                <i class="bi-caret-right-fill text-muted"></i>
                {{ App\Models\General::decodeDate($item->end) }}
            </span>
        </x-layout.card.card-body-content-table-body-line-cell-id-start>

        <x-layout.card.card-body-content-table-body-line-cell-id-end>
            {{-- ... --}}
        </x-layout.card.card-body-content-table-body-line-cell-id-end>
    </x-layout.card.card-body-content-table-body-line-cell-id>

    <x-layout.card.card-body-content-table-body-line-cell-content>
        <span class="text-primary fw-bold">{{ $item->company_name }}</span>
    </x-layout.card.card-body-content-table-body-line-cell-content>
</x-layout.card.card-body-content-table-body-line-cell>

<x-layout.card.card-body-content-table-body-line-cell-action width="120">
    <x-layout.card.card-body-content-table-body-line-cell-action-add-employee :id="$item->id"/>

    <x-layout.card.card-body-content-table-body-line-cell-action-add-holiday :id="$item->id"/>

    <x-layout.card.card-body-content-table-body-line-cell-action-erase-fill :id="$item->id"/>
</x-layout.card.card-body-content-table-body-line-cell-action>
{{-- conteúdo --}} 

                                </x-layout.card.card-body-content-table-body-line>

                                {{-- nova entidade --}}
                                <x-layout.card.card-body-content-table>
                                    <x-layout.card.card-body-content-table-body>
                                        @php
                                            $clockemployees = App\Models\Clockemployee::where('clock_id', $item->id)->orderBy('employee_name', 'ASC')->get();
                                        @endphp
                                        @if($clockemployees->count() > 0)
                                            @foreach($clockemployees as $key => $clockemployee)
                                            <x-layout.card.card-body-content-table-body-line>

{{-- funcionários --}}
<x-layout.card.card-body-content-table-body-line-cell width="">
    <x-layout.card.card-body-content-table-body-line-cell-id>
        <x-layout.card.card-body-content-table-body-line-cell-id-badge>
            {{ str_pad($loop->iteration, Str::length($clockemployees->count()), '0', STR_PAD_LEFT); }}
        </x-layout.card.card-body-content-table-body-line-cell-id-badge>

        <x-layout.card.card-body-content-table-body-line-cell-id-start>
            <span class="text-muted">{{ $clockemployee->employee->pis }}</span>
        </x-layout.card.card-body-content-table-body-line-cell-id-start>

        <x-layout.card.card-body-content-table-body-line-cell-id-end>
            {{--  --}}
        </x-layout.card.card-body-content-table-body-line-cell-id-end>
    </x-layout.card.card-body-content-table-body-line-cell-id>

    <x-layout.card.card-body-content-table-body-line-cell-content>
        {{ $clockemployee->employee_name }}
    </x-layout.card.card-body-content-table-body-line-cell-content>
</x-layout.card.card-body-content-table-body-line-cell>

<x-layout.card.card-body-content-table-body-line-cell-action width="150">
    <x-layout.card.card-body-content-table-body-line-cell-action-add-employee :id="$clockemployee->id"/>

    <x-layout.card.card-body-content-table-body-line-cell-action-add-holiday :id="$clockemployee->id"/>

    <x-layout.card.card-body-content-table-body-line-cell-action-erase :id="$clockemployee->id"/>
</x-layout.card.card-body-content-table-body-line-cell-action>
{{-- funcionários --}}

                                                </x-layout.card.card-body-content-table-body-line>
                                            @endforeach
                                        @else

                                        @endif
                                    </x-layout.card.card-body-content-table-body>
                                </x-layout.card.card-body-content-table>
                                 {{-- nova entidade --}}
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
