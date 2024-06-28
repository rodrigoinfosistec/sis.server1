<x-layout.modal.modal-add modal="add" method="register" size="">
    <x-layout.modal.modal-add-header icon="bi-plus-circle-fill" modal="add">
        {{ $config['title'] }}

        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="register">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="employee_id" title="FUNCIONÁRIO" plus="employee"/>

        <select wire:model="employee_id" class="form-select form-select-sm text-uppercase" id="employee_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Employee::where(['company_id' => Auth()->user()->company_id, 'status' => true])->orderBy('name')->get() as $key => $employee)
                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="employee_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="date_start" title="INÍCIO" plus="none"/>

        <input type="date" wire:model="date_start" class="form-control form-control-sm" id="date_start">

        <x-layout.modal.modal-add-body-group-item-error item="date_start" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="date_end" title="FINAL" plus="none"/>

        <input type="date" wire:model="date_end" class="form-control form-control-sm" id="date_end">

        <x-layout.modal.modal-add-body-group-item-error item="date_end" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="type" title="TIPO" plus="none"/>

        <select wire:model="type" class="form-select form-select-sm text-uppercase" id="type">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            <option value="MATERNIDADE">MATERNIDADE</option>
            <option value="PATERNIDADE">PATERNIDADE</option>
            <option value="CASAMENTO">CASAMENTO</option>
            <option value="OBITO">OBITO</option>
            <option value="SAUDE">SAUDE</option>
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="type" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
