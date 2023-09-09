<x-layout.modal.modal-add modal="addEmployee" method="registerEmployee" size="">
    <x-layout.modal.modal-add-header icon="bi-receipt" modal="addEmployee">
        Feriado de {{ $config['title'] }}

        <x-slot:identifier>
            {{ $company_name }}
            <br>
            {{ $start }}<i class="bi-caret-right-fill text-muted"></i>{{ $end }}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerEmployee">

{{-- conteúdo plus --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="employee_id" title="FUNCIONÁRIO" plus="none"/>

        <select wire:model="employee_id" class="form-select form-select-sm text-uppercase" id="employee_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Employee::where('company_id', $company_id)->get() as $key => $employee)
                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="employee_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo plus --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
