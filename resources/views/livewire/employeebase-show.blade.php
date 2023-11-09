<div class="container">
	<x-layout.alert/>

	<div class="container" style="margin:10px 0 50px 0;">
		@if(!empty(Auth()->User()->employee_id) && App\Models\Employee::find(Auth()->User()->employee_id)->status == true)
			<span class="fw-bold">{{ App\Models\Employee::find(Auth()->User()->employee_id)->name }}</span>
			<br>
			<div style="font-size: 12pt;">
				<span class="text-dark">HORAS EXTRAS</span><i class="bi-caret-right-fill text-muted"></i>
				<span class="fw-bold">
					@if(App\Models\Employee::find(Auth()->User()->employee_id)->datatime > 0) <span class="text-primary">
						@elseif(App\Models\Employee::find(Auth()->User()->employee_id)->datatime < 0) <span class="text-danger">
						@else <span class="text-muted"> @endif
						{{ App\Models\Clock::minutsToTimeSignal((int)App\Models\Employee::find(Auth()->User()->employee_id)->datatime) }}
					</span>
				</span>
				<br>
				<span class="text-muted fw-normal" style="font-size: 8pt;">
					ÚLTIMA VISUALIZAÇÃO EM
					{{ date('d/m/Y H:i') }}
				</span>
			</div>
			<hr>
			<span class="fw-bold">Folgas</span>
			<br>
			@foreach(App\Models\Employeeeasy::where('employee_id', Auth()->User()->employee_id)->orderBy('date', 'ASC')->get() as $key => $employeeeasy)
				{{ date_format(date_create($employeeeasy->date), 'd/m/Y') }} <span class="text-muted">{{ App\Models\General::decodeWeek(date_format(date_create($employeeeasy->date), 'l')) }}</span>
				<br>
			@endforeach
		@else
			<div style="font-size: 15pt;">
				<i class="bi-archive text-muted"></i>
				<span class="text-muted">Usuário não está vinculado e nenhum Funcionário.</span>
			</div>
		@endif
	</div>
</div>
