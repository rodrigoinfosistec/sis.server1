<x-layout.modal.modal-add modal="addHoliday" method="registerHoliday" size="">
    <x-layout.modal.modal-add-header icon="bi-receipt" modal="addEfisco">
        Feriado de {{ $config['title'] }}

        <x-slot:identifier>
            {{ $clock_id }}
            <br>
            {{ $start }}<i class="bi-caret-right-fill text-muted"></i>{{ $end }}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerHoliday">

{{-- conteúdo plus --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="8">
        <x-layout.modal.modal-add-body-group-item-label item="holiday_date" title="DATA" plus="none"/>

        <input type="date" wire:model="holiday_date" min="{{ $start }}" max="{{ $end }}" class="form-control form-control-sm" id="holiday_date">

        <x-layout.modal.modal-add-body-group-item-error item="holiday_date" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="holiday_name" title="DESCRIÇÃO" plus="none"/>

        <input type="text" wire:model="holiday_name" class="form-control form-control-sm" id="holiday_name">

        <x-layout.modal.modal-add-body-group-item-error item="holiday_name" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo plus --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
