<x-layout.modal.modal-add modal="addTxt" method="registerTxt" size="">
    <x-layout.modal.modal-add-header icon="bi-filetype-txt" modal="addTxt">
        {{ $config['title'] }}

        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerTxt">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="company_id" title="EMPRESA" plus="company"/>

        <select wire:model="company_id" class="form-select form-select-sm text-uppercase" id="company_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Company::get() as $key => $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="company_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="txt" title="TXT" plus="none"/>

        <input type="file" wire:model="txt" class="form-control form-control-sm" id="txt">

        <x-layout.modal.modal-add-body-group-item-error item="txt" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
