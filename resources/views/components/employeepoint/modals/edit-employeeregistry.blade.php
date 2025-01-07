<x-layout.modal.modal-edit modal="editEmployeeregistry" size="modal-lg">
    <x-layout.modal.modal-edit-header icon="bi-people-fill" modal="editEmployeeregistry">
        Limites de Ponto

        <x-slot:identifier>
            {{-- --}}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeEmployeeregistry">

{{-- conteúdo --}}
<table class="table table-hover">
    <thead>
        <tr class="text-muted" style="font-size: 8pt;">
            <th scope="col">FUNCIONÁRIO</th>
            <th scope="col">INÍCIO</th>
            <th scope="col">FINAL</th>
            <th scope="col">ATRASO</th>
        </tr>
    </thead>

    <tbody>
        @foreach(App\Models\Employee::where([
            ['company_id', Auth()->user()->company_id],
            ['limit_controll', true],
            ['clock_type', 'REGISTRY'],
            ['status', true],
            ['employeegroup_id', '!=', null],
        ])
        //->whereIn('employeegroup_id', [1, 2, 3, 9, 10, 12, 13])
        ->orderBy('name', 'ASC')->get() as $key => $employee)
            <tr class="" style="font-size: 9pt;">
                <td class="align-middle">
                    {{ $employee->name }}

                    <br>

                    <span class="text-muted" style="font-size: 9pt;">
                        @if(date_format(date_create(date('Y-m-d')), 'l') == 'Saturday')
                            {{ $employee->journey_start_saturday }}

                            <i class="bi-caret-right-fill"></i>

                            {{ $employee->journey_end_saturday }}
                        @else
                            {{ $employee->journey_start_week }}

                            <i class="bi-caret-right-fill"></i>

                            {{ $employee->journey_end_week }}
                        @endif
                    </span>
                </td>

                {{-- Verifica se é Sábado --}}
                @if(date_format(date_create(date('Y-m-d')), 'l') == 'Saturday')
                    <td class="align-middle">
                        <input type="time" wire:model="array_employee_start_sat.{{ $employee->id }}" 
                            class="form-control form-control-sm" 
                            style="font-size: 9pt; padding: 0 2px 0 5px; width: 80px;" id="array_employee_start_sat_{{ $employee->id }}" required>
                    </td>

                    <td class="align-middle">
                        <input type="time" wire:model="array_employee_end_sat.{{ $employee->id }}" 
                            class="form-control form-control-sm" 
                            style="font-size: 9pt; padding: 0 2px 0 5px; width: 80px;" id="array_employee_end_sat_{{ $employee->id }}" required>
                    </td>
                @else
                    <td class="align-middle">
                        <input type="time" wire:model="array_employee_start.{{ $employee->id }}" 
                            class="form-control form-control-sm" 
                            style="font-size: 9pt; padding: 0 2px 0 5px; width: 80px;" id="array_employee_start_{{ $employee->id }}" required>
                    </td>

                    <td class="align-middle">
                        <input type="time" wire:model="array_employee_end.{{ $employee->id }}" 
                            class="form-control form-control-sm" 
                            style="font-size: 9pt; padding: 0 2px 0 5px; width: 80px;" id="array_employee_end_{{ $employee->id }}" required>
                    </td>
                @endif

                <td class="align-middle">
                    <input type="time" wire:model="array_employee_delay.{{ $employee->id }}" 
                        class="form-control form-control-sm" 
                        style="font-size: 9pt; padding: 0 2px 0 5px; width: 80px;" id="array_employee_delay_{{ $employee->id }}" required>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
