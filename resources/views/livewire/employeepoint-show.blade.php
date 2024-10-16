<x-layout.container>

{{-- modal --}}
{{-- botões --}}
@include('components.' .  $config['name'] . '.modals.generate')
@include('components.' .  $config['name'] . '.modals.mail')

{{-- plus --}}
@include('components.' .  $config['name'] . '.modals.add')
@include('components.' .  $config['name'] . '.modals.edit-employeegroup')
@include('components.' .  $config['name'] . '.modals.edit-employeeregistry')

{{-- info --}}


{{-- ações --}}
@include('components.' .  $config['name'] . '.modals.detail')
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
@if(date_format(date_create(date('Y-m-d')), 'l') != 'Saturday')
    <x-layout.card.card-header-button-modernize-employeegroup/>
@endif
<x-layout.card.card-header-button-modernize-employeeregistry/>
{{-- botão add --}}

                </x-layout.card.card-header-button-more>
            </x-layout.card.card-header-button>
        </x-layout.card.card-header>

        <x-layout.card.card-body>
            <x-layout.card.card-body-navigation>
                <x-layout.card.card-body-navigation-search :filter="$filter">
                    <x-layout.card.card-body-navigation-search-filter>

{{-- filtro nome --}}
<option value="name">NOME</option>
<option value="employeegroup_name">GRUPO</option>
{{-- filtro nome --}}

                        </x-layout.card.card-body-navigation-search-filter>

{{-- filtro tipo--}}
<x-layout.card.card-body-navigation-search-type-search/>
{{-- filtro tipo--}}

                </x-layout.card.card-body-navigation-search>

                <x-layout.card.card-body-navigation-info>
                    <x-layout.card.card-body-navigation-info-action>

{{-- info action --}}
<div class="row g-3" style="margin-top: -10px;">
    <div class="col">
        <input type="date" wire:model="date" class="form-control form-control-sm" style="font-size: 8pt; margin: 0;" id="date"/>
    </div>
</div>
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
            {{ str_pad($item->code, 4, '0', STR_PAD_LEFT); }}
        </x-layout.card.card-body-content-table-body-line-cell-id-badge>

        <x-layout.card.card-body-content-table-body-line-cell-id-start>
            <span class="text-muted">
                @if(!empty($item->employeegroup_name))
                    {{ $item->employeegroup_name }}
                @endif
            </span>
        </x-layout.card.card-body-content-table-body-line-cell-id-start>

        <x-layout.card.card-body-content-table-body-line-cell-id-end>
            {{--  --}}
        </x-layout.card.card-body-content-table-body-line-cell-id-end>
    </x-layout.card.card-body-content-table-body-line-cell-id>

    <x-layout.card.card-body-content-table-body-line-cell-content>
        {{ $item->name }}

        <br>

        <span class="text-muted">
            @if(date_format(date_create(date('Y-m-d')), 'l') == 'Saturday')
                {{ $item->journey_start_saturday }}
                <i class="bi-caret-right-fill"></i>
                {{ $item->journey_end_saturday }}
            @else
                {{ $item->journey_start_week }}
                <i class="bi-caret-right-fill"></i>
                {{ $item->journey_end_week }}
            @endif
        </span>
    </x-layout.card.card-body-content-table-body-line-cell-content>
</x-layout.card.card-body-content-table-body-line-cell>

<x-layout.card.card-body-content-table-body-line-cell-action width="120">
    <div style="line-height: 1.5;">
        @foreach(App\Models\Clockregistry::where(['employee_id' => $item->id, 'date' => $date])->orderBy('time', 'ASC')->get() as $key => $clockregistry)
            <span class="badge rounded-pill bg-danger" style="font-size:8.5pt;">
                {{ $clockregistry->time }}
            </span>
        @endforeach
    </div>
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
