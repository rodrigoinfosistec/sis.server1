<x-layout.modal.modal-edit modal="editBusiness" size="">
    <x-layout.modal.modal-edit-header icon="bi-percent" modal="editBusiness">
        Negociação de Fornecedor

        <x-slot:identifier>
            CNPJ {{ $provider_cnpj }}
            <br>
            {{ $provider_name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeBusiness">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="4">
        <x-layout.modal.modal-edit-body-group-item-label item="business_multiplier_type" title="MULTIPLICADOR" plus="none"/>

        <select wire:model="business_multiplier_type" class="form-select form-select-sm text-uppercase" id="business_multiplier_type">
            <x-layout.modal.modal-edit-body-group-item-option-muted/>
            
            <option value="quantity">QUANTIDADE</option>
            <option value="value">VALOR</option>
        </select>

        <x-layout.modal.modal-edit-body-group-item-error item="business_multiplier_type" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="4">
        <x-layout.modal.modal-edit-body-group-item-label item="business_multiplier" title="NOTA FISCAL(%)" plus="none"/>

        <input type="text" wire:model="business_multiplier" class="form-control form-control-sm" id="business_multiplier" onKeyUp="maskPercent2(this, event)">

        <x-layout.modal.modal-edit-body-group-item-error item="business_multiplier" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="4">
        <x-layout.modal.modal-edit-body-group-item-label item="business_multiplier_ipi" title="IPI VALOR(%)" plus="none"/>

        <input type="text" wire:model="business_multiplier_ipi" class="form-control form-control-sm" id="business_multiplier_ipi" onKeyUp="maskPercent2(this, event)">

        <x-layout.modal.modal-edit-body-group-item-error item="business_multiplier_ipi" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="4">
        <x-layout.modal.modal-edit-body-group-item-label item="business_margin" title="MARGEM(%)" plus="none"/>

        <input type="text" wire:model="business_margin" class="form-control form-control-sm" id="business_margin" onKeyUp="maskPercent2(this, event)">

        <x-layout.modal.modal-edit-body-group-item-error item="business_margin" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="4">
        <x-layout.modal.modal-edit-body-group-item-label item="business_shipping" title="FRETE(%)" plus="none"/>

        <input type="text" wire:model="business_shipping" class="form-control form-control-sm" id="business_shipping" onKeyUp="maskPercent2(this, event)">

        <x-layout.modal.modal-edit-body-group-item-error item="business_shipping" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="4">
        <x-layout.modal.modal-edit-body-group-item-label item="business_multiplier_ipi_aliquot" title="IPI ALÍQUOTA(%)" plus="none"/>

        <input type="text" wire:model="business_multiplier_ipi_aliquot" class="form-control form-control-sm" id="business_multiplier_ipi_aliquot" onKeyUp="maskPercent2(this, event)">

        <x-layout.modal.modal-edit-body-group-item-error item="business_multiplier_ipi_aliquot" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="business_discount" title="DESCONTO(%)" plus="none"/>

        <input type="text" wire:model="business_discount" class="form-control form-control-sm" id="business_discount" onKeyUp="maskPercent2(this, event)" disabled>

        <x-layout.modal.modal-edit-body-group-item-error item="business_discount" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="business_addition" title="OUTROS(%)" plus="none"/>

        <input type="text" wire:model="business_addition" class="form-control form-control-sm" id="business_addition" onKeyUp="maskPercent2(this, event)" disabled>

        <x-layout.modal.modal-edit-body-group-item-error item="business_addition" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
