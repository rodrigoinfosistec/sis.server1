<x-layout.modal.modal-edit modal="editMonth" size="modal-fullscreen">
    <x-layout.modal.modal-edit-header icon="bi-calendar-check" modal="editMonth">
        Período de Ponto

        <x-slot:identifier>
            <span class="text-primary">{{ $name }}</span>
            <br>
            {{ $pis }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeMonth">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="8">
        <x-layout.modal.modal-add-body-group-item-label item="date" title="DATA" plus="none"/>

        <input type="date" wire:model="date" class="form-control form-control-sm" id="date" min="{{ $month }}-01" max="{{ $month }}-{{ $month_end }}">

        <x-layout.modal.modal-add-body-group-item-error item="date" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
@if(date_format(date_create($date), 'l') == 'Sunday')
    <x-layout.modal.modal-add-body-group-item columms="12">
        <span class="text-danger">
            Esta data é um Domingo.
        </span>
    </x-layout.modal.modal-add-body-group-item>

@elseif(App\Models\Holiday::where('date', $date)->exists())
    <x-layout.modal.modal-add-body-group-item columms="12">
        <span class="text-danger">
            Esta data é um Feriado.
            <br>
            <span class="text-muted" style="font-size: 9pt;">
                {{ App\Models\Holiday::where('date', $date)->first()->name ?? 'none' }}
            </span>
        </span>
    </x-layout.modal.modal-add-body-group-item>

@elseif(App\Models\Employeevacationday::where(['date' => $date, 'employee_id' => $employee_id])->exists())
    <x-layout.modal.modal-add-body-group-item columms="12">
        <span class="text-danger">
            Funcionário de Férias nesta data.
        </span>
    </x-layout.modal.modal-add-body-group-item>
@elseif(App\Models\Employeeeasy::where(['date' => $date, 'employee_id' => $employee_id])->exists())
    <x-layout.modal.modal-add-body-group-item columms="12">
        <span class="text-danger">
            Funcionário de Folga nesta data.
        </span>
    </x-layout.modal.modal-add-body-group-item>

@elseif(App\Models\Employeeattestday::where(['date' => $date, 'employee_id' => $employee_id])->exists())
    <x-layout.modal.modal-add-body-group-item columms="12">
        <span class="text-danger">
            Funcionário com Atestado nesta data.
        </span>
    </x-layout.modal.modal-add-body-group-item>

@elseif(App\Models\Employeelicenseday::where(['date' => $date, 'employee_id' => $employee_id])->exists())
    <x-layout.modal.modal-add-body-group-item columms="12">
        <span class="text-danger">
            Funcionário de Licença nesta data.
        </span>
    </x-layout.modal.modal-add-body-group-item>

@elseif(App\Models\Employeeabsenceday::where(['date' => $date, 'employee_id' => $employee_id])->exists())
    <x-layout.modal.modal-add-body-group-item columms="12">
        <span class="text-danger">
            Funcionário Faltou nesta data.
        </span>
    </x-layout.modal.modal-add-body-group-item>

@else
    <x-layout.modal.modal-add-body-group-item columms="3">
        <x-layout.modal.modal-add-body-group-item-label item="input" title="ENTRADA" plus="none"/>

        <input type="time" wire:model="input" class="form-control form-control-sm" id="input" @if(empty($date)) disabled @endif>

        <x-layout.modal.modal-add-body-group-item-error item="input" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    @if(!empty($date))
        @if(date_format(date_create($date), 'l') != 'Saturday')
            <x-layout.modal.modal-add-body-group-item columms="3">
                <x-layout.modal.modal-add-body-group-item-label item="break_start" title="PAUSA (INÍCIO)" plus="none"/>

                <input type="time" wire:model="break_start" class="form-control form-control-sm" id="break_start" @if(empty($date)) disabled @endif>

                <x-layout.modal.modal-add-body-group-item-error item="break_start" message="$message"/>
            </x-layout.modal.modal-add-body-group-item>

            <x-layout.modal.modal-add-body-group-item columms="3">
                <x-layout.modal.modal-add-body-group-item-label item="break_end" title="PAUSA (FINAL)" plus="none"/>

                <input type="time" wire:model="break_end" class="form-control form-control-sm" id="break_end" @if(empty($date)) disabled @endif>

                <x-layout.modal.modal-add-body-group-item-error item="break_end" message="$message"/>
            </x-layout.modal.modal-add-body-group-item>
        @endif
    @endif

    <x-layout.modal.modal-add-body-group-item columms="3">
        <x-layout.modal.modal-add-body-group-item-label item="output" title="SAÍDA" plus="none"/>

        <input type="time" wire:model="output" class="form-control form-control-sm" id="output" @if(empty($date)) disabled @endif>

        <x-layout.modal.modal-add-body-group-item-error item="output" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
@endif
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
