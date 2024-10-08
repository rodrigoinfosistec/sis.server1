<x-layout.modal.modal-add modal="addEasy" method="registerEasy" size="">
    <x-layout.modal.modal-add-header icon="bi-emoji-sunglasses" modal="addEasy">
        Folga do Funcionário

        <x-slot:identifier>
            <span class="text-primary">{{ $name }}</span>
            <br>
            {{ $pis }}
            <br><br>
            <span class="fw-bold" style="font-size: 12pt">
                @if($datatime > 0) <span class="text-primary">
                @elseif($datatime < 0) <span class="text-danger">
                @else <span class="text-dark"> @endif
                    {{ App\Models\Clock::minutsToTimeSignal((int)$datatime) }}
                </span>
            </span>
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerEasy">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="8">
        <x-layout.modal.modal-add-body-group-item-label item="date" title="DATA" plus="none"/>

        <input type="date" wire:model="date" class="form-control form-control-sm" id="date">

        <x-layout.modal.modal-add-body-group-item-error item="date" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-bool model="discount">

            <div style="margin-top: 5px;">
                <span class="" style="font-size: 9pt;">DESCONTAR DO BANCO DE HORAS?</span>
            </div>
            <input wire:model="discount" class="form-check-input" type="checkbox" role="switch" id="discount">

            <x-slot:label>
                @if($discount)
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
