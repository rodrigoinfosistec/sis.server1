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
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="date" title="DATA" plus="none"/>

        <input type="date" wire:model="date" class="form-control form-control-sm" id="date">

        <x-layout.modal.modal-add-body-group-item-error item="date" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="3">
        <x-layout.modal.modal-add-body-group-item-label item="time" title="HORAS" plus="none"/>

        <input type="number" wire:model="time" min="0" max="99" class="form-control form-control-sm" id="time">

        <x-layout.modal.modal-add-body-group-item-error item="time" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="3">
        <x-layout.modal.modal-add-body-group-item-label item="minut" title="MINUTOS" plus="none"/>

        <input type="number" wire:model="minut" min="0" max="60" class="form-control form-control-sm" id="minut">

        <x-layout.modal.modal-add-body-group-item-error item="minut" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
