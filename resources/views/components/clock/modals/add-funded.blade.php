<x-layout.modal.modal-add modal="addFunded" method="registerFunded" size="">
    <x-layout.modal.modal-add-header icon="bi-database-fill-check" modal="addFunded">
        Banco de Horas

        <x-slot:identifier>
            {{ $company_name }}
            <br>
            {{ $start_decode }}<i class="bi-caret-right-fill text-muted"></i>{{ $end_decode }}
            <br><br>
            <span class="text-danger fw-bold" style="font-size: 13pt">
                Consolidar em Banco de Horas?
            </span>
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerFunded">

{{-- conteúdo plus --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="employee_id" title="FUNCIONÁRIO" plus="employee"/>

        <select wire:model="employee_id" class="form-select form-select-sm text-uppercase" id="employee_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Employee::where('company_id', $company_id)->orderBy('name')->get() as $key => $employee)
                @if(App\Models\Clockemployee::where(['clock_id' => $clock_id, 'employee_id' => $employee->id])->doesntExist())
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endif
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="employee_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo plus --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
