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
    <x-layout.modal.modal-add-body-group-item columms="8">
        <x-layout.modal.modal-add-body-group-item-label item="employee_id" title="FUNCIONÁRIO" plus="employee"/>

        <select wire:model="employee_id" class="form-select form-select-sm text-uppercase" id="employee_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Employee::get() as $key => $employee)
                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="employee_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="date" title="DATA" plus="none"/>

        <input type="date" wire:model="date" class="form-control form-control-sm" id="date">

        <x-layout.modal.modal-add-body-group-item-error item="date" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="start" title="INÍCIO" plus="none"/>

        <input type="time" wire:model="start" class="form-control form-control-sm" id="start">

        <x-layout.modal.modal-add-body-group-item-error item="start" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="end" title="FINAL" plus="none"/>

        <input type="time" wire:model="end" class="form-control form-control-sm" id="end">

        <x-layout.modal.modal-add-body-group-item-error item="end" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-bool model="merged">

            <div style="margin-top: 5px;">
                <span class="" style="font-size: 9pt;">JUSTIFICADO?</span>
            </div>
            <input wire:model="merged" class="form-check-input" type="checkbox" role="switch" id="merged">

            <x-slot:label>
                @if($merged)
                    <x-layout.modal.modal-add-body-group-item-bool-true title="SIM"/>
                @else
                    <x-layout.modal.modal-add-body-group-item-bool-false title="NÃO" />
                @endif
            </x-slot>

        </x-layout.modal.modal-add-body-group-item-bool>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
