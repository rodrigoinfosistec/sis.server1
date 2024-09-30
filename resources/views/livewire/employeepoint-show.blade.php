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
@include('components.' .  $config['name'] . '.modals.edit')
@include('components.' .  $config['name'] . '.modals.erase')
{{-- modal --}}

    <x-layout.alert/>

    <div class="text-center" style="width: 100%; height: 500px; background-color: #ddd;">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <div class="card">
                    <div class="card-header fw-bold text-primary text-center">
                        <i class="bi bi-info-circle"></i>

                        RH INFORMA
                    </div>{{-- card-header --}}

                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
							@foreach(App\Models\Rhnews::where('status', true)->orderBy('created_at', 'DESC')->get() as $key => $news)
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" style="padding-top: 5px; padding-bottom: 5px;" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $news->id }}" aria-expanded="false" aria-controls="flush-collapse{{ $news->id }}">
											<span class="fw-bold uppercase" style="font-size: 10.5pt;">
												<i class="bi-info-circle-fill text-primary"></i>&nbsp;
												{{ $news->name }}
											</span>
										</button>
									</h2>

									<div id="flush-collapse{{ $news->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body" style="line-height: 1.2">
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $news->description }}

											<p class="text-secondary" style="font-size: 11pt; margin-top: 10px;">
												@if(!empty($news->salute))
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													{{ $news->salute }}
													<br>
												@endif

												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<span style="font-size: 9pt;">
													{{ $news->updated_at->format('d/m/Y') }}
												</span>
											</p>
										</div>
									</div>
								</div>
							@endforeach
						</div>
                    </div>{{-- card-body --}}
                </div>{{-- card --}}
            </div>{{-- col --}}
        </div>{{-- row --}}
    </div>

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
<option value="company_name">EMPRESA</option>
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
            
        </x-layout.card.card-body-content-table-body-line-cell-id-start>

        <x-layout.card.card-body-content-table-body-line-cell-id-end>
            @if($item->trainee)
                <span class="text-danger">
                    ESTAGIÁRIO
                </span>
            @endif
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

<x-layout.card.card-body-content-table-body-line-cell-action width="100">
    <div style="line-height: 1.5;">
        @foreach(App\Models\Clockregistry::where(['employee_id' => $item->id, 'date' => $date])->orderBy('time', 'ASC')->get() as $key => $clockregistry)
            <span class="badge rounded-pill bg-danger" style="font-size:9pt;">
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
