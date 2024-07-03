<x-layout.modal.modal-add modal="addCsv" method="registerCsv" size="">
    <x-layout.modal.modal-add-header icon="bi-plus-circle-fill" modal="addCsv">
        {{ $config['title'] }} em Lote

        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerCsv">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="csv" title="CSV" plus="none"/>

        <input type="file" wire:model="csv" class="form-control form-control-sm" id="csv">

        <x-layout.modal.modal-add-body-group-item-error item="csv" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="provider_id" title="FORNECEDOR" plus="provider"/>

        <select wire:model="provider_id" class="form-select form-select-sm text-uppercase" id="provider_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Provider::orderBy('name', 'ASC')->get() as $key => $provider)
                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="provider_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
