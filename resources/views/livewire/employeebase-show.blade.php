<div class="container">
	<x-layout.alert/>

	@include('components.' .  $config['name'] . '.modals.mail')
	@include('components.' .  $config['name'] . '.modals.add-registry')
	@include('components.' .  $config['name'] . '.modals.detail')

    @if(!empty($employee))
		<div class="alert alert-primary text-center fw-bold" role="alert">
			@if(isset($employee->code))
				{{ $employee->code }}

				<i class="bi-caret-right"></i>
			@endif

			{{ Illuminate\Support\Str::limit($employee->name, 30, '') }}
		</div>

        @if(App\Models\Rhsearch::exists())
            @if(App\Models\Rhsearch::first()->status)
                @php
					$rhsearh = App\Models\Rhsearch::first() ?? 'none';
				@endphp

				<div style="height: 50px;">
					<div class="float-end" style="margin-right: 20px;">
						<a type="button" href="{{ $rhsearh->link ?? '#' }}" target="_BLANK" title="{{ $rhsearh->name ?? 'Pesquisa'}}">
							<i class="bi-{{ $rhsearh->icon ?? 'search' }}" style="font-size: 18px; color: {{ $rhsearh->color ?? '#0D6EFD' }};"></i>
						</a>

						<a type="button" href="{{ $rhsearh->link ?? '#' }}" target="_BLANK" class="btn btn-link btn-sm fw-bold" style="font-size: 13pt; padding: 0; margin: 0;  color: {{ $rhsearh->color ?? '#0D6EFD' }};" title="{{ $rhsearh->name ?? 'Pesquisa'}}">
							{{ $rhsearh->name ?? 'Pesquisa' }}
						</a>
					</div>
				</div>
			@endif
		@endif

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

        <div class="row">
			@if($employee->canonline)
				<div class="col-sm-4 mb-4">
					<div class="card">
						<div class="card-header fw-bold text-danger text-center">
							<i class="bi bi-fingerprint"></i>

							REGISTRO DE PONTO
						</div>{{-- card-header --}}

						<div class="card-body" style="height: 170px;">
							@if(
								($employee->clock_type == 'REGISTRY')
								&& (date('l') != 'Sunday')
								&& (App\Models\Holiday::where('date', date('Y-m-d'))->doesntExist())
								&& (App\Models\Employeevacationday::where(['employee_id' => $employee->id, 'date' => date('Y-m-d')])->doesntExist())
								&& (App\Models\Employeeeasy::where(['employee_id' => $employee->id, 'date' => date('Y-m-d')])->doesntExist())
								&& (App\Models\Employeelicenseday::where(['employee_id' => $employee->id, 'date' => date('Y-m-d')])->doesntExist())
								&& (App\Models\Employeeattestday::where(['employee_id' => $employee->id, 'date' => date('Y-m-d')])->doesntExist())
								&& (App\Models\Employeeabsenceday::where(['employee_id' => $employee->id, 'date' => date('Y-m-d')])->doesntExist())
							)
								@if(App\Models\Clockregistry::where(['employee_id' => $employee->id, 'date' => date('Y-m-d')])->exists())
									<h5 class="card-title text-muted" style="font-size: 11pt;">
										REGISTROS DE HOJE

										<i class="bi bi-caret-right-fill"></i>

										{{ date('d') }}
										de
										{{ App\Models\General::numberToMonth((string)date('m')) }}
									</h5>

									<p class="card-text">
										@foreach(App\Models\Clockregistry::where(['employee_id' => $employee->id, 'date' => date('Y-m-d')])->orderBy('time', 'ASC')->get() as $key => $clockregistry)
											<span class="badge rounded-pill bg-danger" style="font-size:11pt;">
												{{ $clockregistry->time }}
											</span>
										@endforeach
									</p>
								@else
									<h5 class="card-title text-muted" style="font-size: 11pt;">
										NENHUM REGISTRO DE HOJE 

										<i class="bi bi-caret-right-fill"></i>

										{{ date('d') }}
										de
										{{ App\Models\General::numberToMonth((string)date('m')) }}
									</h5>
								@endif

								<a type="button" wire:click="addRegistry({{ (int)Auth()->User()->employee_id }})" class="btn btn btn-outline-danger btn-sm fw-bold float-end" style="font-size: 13pt;" data-bs-toggle="modal" data-bs-target="#addRegistryModal" title="Registrar Ponto">
									Registrar <i class="bi bi-hand-index-thumb"></i>
								</a>
							@else
								<h5 class="card-title text-muted" style="font-size: 11pt;">
									USUÁRIO NÃO REGISTRA PONTO ON-LINE
								</h5>
							@endif
						</div>{{-- card-body --}}
					</div>{{-- card --}}
				</div>{{-- col --}}
			@endif

            <div class="col-sm-4  mb-4">
                <div class="card">
                    <div class="card-header fw-bold text-dark text-center">
                        <i class="bi bi-clock"></i>

                        BANCO DE HORAS
                    </div>{{-- card-header --}}

                    <div class="card-body" style="height: 170px;">
                        <h5 class="card-title text-muted text-center" style="font-size: 11pt;">
                            @if(!empty($clockbase))
								ÚLTIMO PERÍODO ATUALIZADO

								<i class="bi bi-caret-right-fill"></i>

								{{ App\Models\General::numberToMonthAbreviate((string)date_format(date_create($clockbase->end), 'm')) }}/{{ date_format(date_create($clockbase->end), 'y') }}
							@else
								NÃO EXISTEM PERÍODOS CONSOLIDADOS.
							@endif
						</h5>
                        
                        <br>

                        <p class="card-text text-center">
                            <span class="badge rounded-pill bg-dark mb-1" style="font-size:18pt;">
								{{ App\Models\Clock::minutsToTimeSignal((int)$employee->datatime) }}
							</span>
						</p>
                        
                    </div>{{-- card-body --}}
                </div>{{-- card- --}}
            </div>{{-- col --}}

            <div class="col-sm-4  mb-4">
                <div class="card z-0">
                    <div class="card-header fw-bold text-success text-center">
                        <i class="bi bi-emoji-sunglasses"></i>

                        FOLGAS
                    </div>{{-- card-header --}}

                    <div class="card-body overflow-auto" style="height: 170px;"> 
                        <div class="mb-3">
                            @if(App\Models\Employeeeasy::where('employee_id', $employee->id)->exists())
                                <ul class="list-group list-group-flush">
                                    @foreach(App\Models\Employeeeasy::where('employee_id', $employee->id)->orderBy('date', 'DESC')->limit(20)->get() as $key => $employeeeasy)
                                        <li class="list-group-item">
                                            <span class="float-start text-success">
											{{ date_format(date_create($employeeeasy->date), 'd/m/y') }}
											</span>
											
											<span class="text-muted float-end">
												{{ App\Models\General::decodeWeek(date_format(date_create($employeeeasy->date), 'l')) }}
											</span>
										</li>
									@endforeach
								</ul>
							@else
								<h5 class="card-title text-muted text-center" style="font-size: 11pt;">
									NÃO EXISTEM FOLGAS REGISTRADAS.
								</h5>
							@endif
						</div>
					</div>{{-- card-body --}}
                </div>{{-- card --}}
            </div>{{-- col --}}
        </div>{{-- row --}}
        
        <br>

		<div style="line-height: 1.3; margin-left: 20px;">
			<i class="bi-envelope text-muted"></i>
			<a type="button" wire:click="mail" class="btn btn-link btn-sm text-black text-decoration-none" style="font-size: 13pt;" data-bs-toggle="modal" data-bs-target="#mailModal" title="Sugest達o">
				Deixe-nos sua Sugestão
			</a>      			
			<x-layout.card.card-header-button-action-mail-suggestion/>
		</div>

		<br>

		<div style="line-height: 1.3; margin-left: 20px;">
			<i class="bi-archive text-muted"></i>
			<a type="button" wire:click="detail({{ (int)Auth()->User()->employee_id }})" class="btn btn-link btn-sm text-black text-decoration-none" style="font-size: 13pt;" data-bs-toggle="modal" data-bs-target="#detailModal" title="Documentos">
				Meus Documentos
			</a>
			<x-layout.card.card-header-button-action-detail-docs/>
		</div>

		<br>
    @else
        <div class="alert alert-danger text-center fw-bold" role="alert">
            <i class="bi-exclamation-circle"></i>

			USUÁRIO NÃO VINCULADO A NENHUM FUNCIONÁRIO.
		</div>
    @endif
</div>{{-- container --}}
