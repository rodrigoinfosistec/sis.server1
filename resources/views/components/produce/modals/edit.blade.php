<x-layout.modal.modal-edit modal="edit" size="">
    <x-layout.modal.modal-edit-header icon="bi-pencil-square" modal="edit">
        {{ $config['title'] }}

        <x-slot:identifier>
            <span class="text-primary fw-bold" style="font-size: 11pt;">
                {{ $name }}
            </span>
            <br>
            <span class="text-muted">
                {{ $producebrand_name }}
            </span>
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernize">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="producebrand_id" title="MARCA" plus="producebrand"/>

        <select wire:model="producebrand_id" class="form-select form-select-sm text-uppercase" id="producebrand_id">
            <x-layout.modal.modal-edit-body-group-item-option-muted/>

            @foreach(App\Models\Producebrand::where('status', true)->orderBy('name', 'ASC')->get() as $key => $producebrand)
                <option value="{{ $producebrand->id }}">{{ $producebrand->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-edit-body-group-item-error item="producebrand_id" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="producemeasure_id" title="EMBALAGEM" plus="producemeasure"/>

        <select wire:model="producemeasure_id" class="form-select form-select-sm text-uppercase" id="producemeasure_id">
            <x-layout.modal.modal-edit-body-group-item-option-muted/>

            @foreach(App\Models\Producemeasure::where('status', true)->orderBy('name', 'ASC')->get() as $key => $producemeasure)
                <option value="{{ $producemeasure->id }}">{{ $producemeasure->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-edit-body-group-item-error item="producemeasure_id" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <x-layout.modal.modal-edit-body-group-item-label item="name" title="NOME" plus="none"/>

        <input type="text" wire:model="name" class="form-control form-control-sm" id="name">

        <x-layout.modal.modal-edit-body-group-item-error item="name" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="reference" title="REFERÊNCIA" plus="none"/>

        <input type="text" wire:model="reference" class="form-control form-control-sm" id="reference">

        <x-layout.modal.modal-edit-body-group-item-error item="reference" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="ean" title="EAN" plus="none"/>

        <input type="text" wire:model="ean" class="form-control form-control-sm" id="ean">

        <x-layout.modal.modal-edit-body-group-item-error item="ean" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <textarea wire:model="observation" class="form-control form-control-sm" id="observation" rows="6"></textarea>

        <x-layout.modal.modal-mail-body-group-item-count :comment="$observation"/>

        <x-layout.modal.modal-mail-body-group-item-error item="observation" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
