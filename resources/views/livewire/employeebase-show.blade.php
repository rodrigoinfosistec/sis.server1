<div class="container">
	<x-layout.alert/>

	@include('components.' .  $config['name'] . '.modals.mail')
	@include('components.' .  $config['name'] . '.modals.add-registry')
	@include('components.' .  $config['name'] . '.modals.detail')

	<div class="container" style="margin:10px 0 50px 0;">
		@if(!empty($employee))
			<div class="alert alert-primary text-center fw-bold" role="alert">
				{{ $employee->name }}
			</div>

			<br>

			<div style="font-size: 13pt;">
				<i class="bi-clock-fill text-black" style="font-size: 18px;"></i>

				<span class="text-dark fw-bold">BANCO DE HORAS</span>
				
				<span class="badge rounded-pill bg-black" style="font-size:11pt;">
					{{ App\Models\Clock::minutsToTimeSignal((int)$employee->datatime) }}
				</span>

				<br>

				<div class="text-muted fw-normal" style="font-size: 8pt; line-height: 1.2;">
					@if(!empty($clockbase))
						ÚLTIMO PERÍODO ATUALIZADO
						<br>
						{{ date_format(date_create($clockbase->start), 'd/m/Y') }}
						a
						{{ date_format(date_create($clockbase->end), 'd/m/Y') }}
					@else
						NÃO EXISTEM PERÍODOS CONSOLIDADOS.
					@endif
				</div>
			</div>

			@if(App\Models\Rhsearch::exists())
				@if(App\Models\Rhsearch::first()->status)
					@php
						$rhsearh = App\Models\Rhsearch::first() ?? 'none';
					@endphp

					<hr style="color: #C0C0C0;">

					<div style="line-height: 1.3;">
						<a type="button" href="{{ $rhsearh->link ?? '#' }}" style="padding: 0; margin: 0;" target="_BLANK" title="{{ $rhsearh->name ?? 'Pesquisa' }}">
							<i class="bi-{{ $rhsearh->icon ?? 'search' }}" style="font-size: 18px; color: {{ $rhsearh->color ?? '#0D6EFD' }};"></i>
						</a>

						<a type="button" href="{{ $rhsearh->link ?? '#' }}" target="_BLANK" class="btn btn-link btn-sm text-decoration-none fw-bold" style="font-size: 13pt; padding: 0; margin: 0;  color: {{ $rhsearh->color ?? '#0D6EFD' }};" title="{{ $rhsearh->name ?? 'Pesquisa' }}">
							{{ $rhsearh->name ?? 'Pesquisa' }}
						</a>

						<a type="button" href="{{ $rhsearh->link ?? '#' }}" target="_BLANK" class="btn btn-link btn-sm text-muted" style="font-size: 10pt;" title="{{ $rhsearh->name ?? 'Pesquisa' }}">
							Clique aqui
						</a>
					</div>
				@endif
			@endif

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
				<hr style="color: #C0C0C0;">

				<div style="line-height: 1.3;">
					<x-layout.card.card-header-button-action-add-registry/>

					<a type="button" wire:click="addRegistry({{ (int)Auth()->User()->employee_id }})" class="btn btn-link btn-sm text-danger text-decoration-none fw-bold" style="font-size: 13pt; padding: 0; margin: 0;" data-bs-toggle="modal" data-bs-target="#addRegistryModal" title="Registrar Ponto">
						Registrar Ponto
					</a>

					@if(App\Models\Clockregistry::where(['employee_id' => $employee->id, 'date' => date('Y-m-d')])->exists())
						<br>

						<div style="line-height: 1.3;">
							<span class="text-muted" style="font-size: 8pt;">REGISTROS DE HOJE - {{ date('d/m/Y') }}</span>

							<br>

							@foreach(App\Models\Clockregistry::where(['employee_id' => $employee->id, 'date' => date('Y-m-d')])->orderBy('time', 'ASC')->get() as $key => $clockregistry)
								<span class="badge rounded-pill bg-danger" style="font-size:8pt;">
									{{ $clockregistry->time }}
								</span>
							@endforeach
						</div>
					@else
						<br>

						<div style="line-height: 1.3;">
							<span class="text-muted" style="font-size: 9pt;">NENHUM REGISTRO DE HOJE - {{ date('d/m/Y') }}</span>
						</div>
					@endif
				</div>
			@endif

			@if(App\Models\Rhnews::where('status', true)->exists())
				<hr style="color: #C0C0C0;">

				<h6 class="text-primary" style="font-size: 13pt;">
					<i class="bi-info-circle"></i>
					RH Informa
				</h6>

				<div class="accordion accordion-flush" id="accordionFlushExample" style="max-width: 500px;">
					@foreach(App\Models\Rhnews::where('status', true)->orderBy('created_at', 'DESC')->get() as $key => $news)
						<div class="accordion-item">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" style="padding-top: 5px; padding-bottom: 5px;" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $news->id }}" aria-expanded="false" aria-controls="flush-collapse{{ $news->id }}">
									<i class="bi-info-circle-fill text-primary"></i>&nbsp;&nbsp;
									{{ $news->name }}
								</button>
							</h2>

							<div id="flush-collapse{{ $news->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
								<div class="accordion-body">{{ $news->description }}</div>
							</div>
						</div>
					@endforeach
				</div>
			@endif

			<div style="line-height: 1.3;">
				<hr style="color: #C0C0C0;">

				<i class="bi-emoji-sunglasses text-black" style="font-size: 18px;"></i>

				<span class="fw-bold text-black" style="font-size: 13pt;">Folgas</span> <span class="text-muted">(Últimas 6)</span>

				<br>

				<span style="font-size: 11pt;">
					@foreach(App\Models\Employeeeasy::where('employee_id', $employee->id)->orderBy('date', 'DESC')->limit(6)->get() as $key => $employeeeasy)
						{{ date_format(date_create($employeeeasy->date), 'd/m/Y') }} <span class="text-muted">{{ App\Models\General::decodeWeek(date_format(date_create($employeeeasy->date), 'l')) }}</span>

						<br>

					@endforeach
				</span>
			</div>
		@else
			<div style="font-size: 15pt;">
				<i class="bi-archive text-muted"></i>

				<span class="text-muted">
					Usuário não está vinculado a nenhum Funcionário.
				</span>
			</div>
		@endif

		<hr style="color: #C0C0C0;">

		<div style="line-height: 1.3;">
			<i class="bi-clock-history text-muted"></i>

			<a type="button" wire:click="mail" class="btn btn-link btn-sm text-black text-decoration-none" style="font-size: 13pt;" data-bs-toggle="modal" data-bs-target="#mailModal" title="Sugest/1達o">
				Deixe-nos sua Sugestão
			</a>

			<x-layout.card.card-header-button-action-mail-suggestion/>
		</div>

		<br>

		<div style="line-height: 1.3">
			<i class="bi-archive text-muted"></i>

			<a type="button" wire:click="detail({{ (int)Auth()->User()->employee_id }})" class="btn btn-link btn-sm text-black text-decoration-none" style="font-size: 13pt;" data-bs-toggle="modal" data-bs-target="#detailModal" title="Documentos">
				Meus Documentos
			</a>

			<x-layout.card.card-header-button-action-detail-docs/>
		</div>
	</div>
</div>
