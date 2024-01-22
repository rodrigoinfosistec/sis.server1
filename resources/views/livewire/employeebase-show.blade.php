<div class="container">
	<x-layout.alert/>

	@include('components.' .  $config['name'] . '.modals.mail')
	@include('components.' .  $config['name'] . '.modals.add-registry')
	@include('components.' .  $config['name'] . '.modals.detail')

	<div class="container" style="margin:10px 0 50px 0;">
		@if(!empty($employee))
			<span class="fw-bold">{{ $employee->name }}</span>
			<br>
			<div style="font-size: 12pt;">
				<i class="bi-clock-fill text-muted"></i>
				<span class="text-dark">BANCO DE HORAS</span><i class="bi-caret-right-fill text-muted"></i>
				<span class="fw-bold">
					@if($employee->datatime > 0) <span class="text-primary">
						@elseif($employee->datatime < 0) <span class="text-danger">
						@else <span class="text-muted"> @endif
						{{ App\Models\Clock::minutsToTimeSignal((int)$employee->datatime) }}
					</span>
				</span>
				<br><br>

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

			<hr>

			<div style="line-height: 1.3;">
				<i class="bi-search-heart text-muted"></i>

				<a type="button" href="https://forms.gle/wdtDPF7ALAVkqjsP6" target="_BLANK" class="btn btn-link btn-sm text-primary text-decoration-none" style="font-size: 13pt;" title="Pesquisa de Clima">
					Pesquisa de Clima
				</a>

				<br>

				<a type="button" href="https://forms.gle/wdtDPF7ALAVkqjsP6" target="_BLANK" class="btn btn-link btn-sm text-muted" style="font-size: 10pt;" title="Pesquisa de Clima">
					Clique aqui
				</a>
			</div>

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
				<hr>

				<div style="line-height: 1.3;">
					<a type="button" wire:click="addRegistry({{ (int)Auth()->User()->employee_id }})" class="btn btn-link btn-sm text-black text-decoration-none" style="font-size: 13pt;" data-bs-toggle="modal" data-bs-target="#addRegistryModal" title="Registrar Ponto">
						Registrar Ponto
					</a>
					<x-layout.card.card-header-button-action-add-registry/>

					@if(App\Models\Clockregistry::where(['employee_id' => $employee->id, 'date' => date('Y-m-d')])->exists())
						<br>
						<div style="line-height: 1.3;">
							<span class="text-muted" style="font-size: 8pt;">REGISTROS DE HOJE - {{ date('d/m/Y') }}</span>
							<br>
							@foreach(App\Models\Clockregistry::where(['employee_id' => $employee->id, 'date' => date('Y-m-d')])->orderBy('time', 'ASC')->get() as $key => $clockregistry)
								@if(!$loop->first)
									<i class="bi-caret-right-fill text-muted" style="font-size: 9pt;"></i>
								@endif

								<span class="fw-bold text-danger" style="font-size: 10pt;">{{ $clockregistry->time }}</span>
							@endforeach
						</div>
					@else
						<br>
						<div style="line-height: 1.3;">
							<span class="text-muted" style="font-size: 8pt;">NENHUM REGISTRO DE HOJE - {{ date('d/m/Y') }}</span>
						</div>
					@endif
				</div>
			@endif

			<hr>

			<i class="bi-emoji-sunglasses text-muted"></i>
			<span class="fw-bold">Folgas</span> <span class="text-muted">(últimas 6)</span>

			<br>
			@foreach(App\Models\Employeeeasy::where('employee_id', $employee->id)->orderBy('date', 'DESC')->limit(6)->get() as $key => $employeeeasy)
				{{ date_format(date_create($employeeeasy->date), 'd/m/Y') }} <span class="text-muted">{{ App\Models\General::decodeWeek(date_format(date_create($employeeeasy->date), 'l')) }}</span>
				<br>
			@endforeach
		@else
			<div style="font-size: 15pt;">
				<i class="bi-archive text-muted"></i>
				<span class="text-muted">Usuário não está vinculado a nenhum Funcionário.</span>
			</div>
		@endif
		<hr>

		<div style="line-height: 1.3;">
			<i class="bi-clock-history text-muted"></i>
			<a type="button" wire:click="mail" class="btn btn-link btn-sm text-black text-decoration-none" style="font-size: 13pt;" data-bs-toggle="modal" data-bs-target="#mailModal" title="Sugest/1達o">
				Deixe-nos sua sugestão
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
