<x-layout.modal.modal-add modal="addAllowanceEmployee" method="registerAllowanceEmployee" size="">
    <x-layout.modal.modal-add-header icon="bi-clipboard-check" modal="addAllowanceEmployee">
        Abono de Horas do Funcionário

        <x-slot:identifier>
            <span class="text-primary">{{ $clockemployee_employee_name }}</span>
            <br>
            {{ $clockemployee_employee_pis }}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerAllowanceEmployee">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="date" title="DATA" plus="none"/>

        <input type="date" wire:model="date" min="{{ $clockemployee_clock_start }}" max="{{ $clockemployee_clock_end }}"  class="form-control form-control-sm" id="date">

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
