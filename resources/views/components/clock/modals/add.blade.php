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
        <x-layout.modal.modal-add-body-group-item-label item="company_id" title="EMPRESA" plus="company"/>

        <select wire:model="company_id" class="form-select form-select-sm text-uppercase" id="company_id" disabled>
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Company::where('id', Auth()->user()->company_id)->get() as $key => $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="company_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="start" title="INÍCIO" plus="none"/>

        <input type="date" wire:model="start" class="form-control form-control-sm" id="start">

        <x-layout.modal.modal-add-body-group-item-error item="start" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="end" title="FINAL" plus="none"/>

        <input type="date" wire:model="end" class="form-control form-control-sm" id="end">

        <x-layout.modal.modal-add-body-group-item-error item="end" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
