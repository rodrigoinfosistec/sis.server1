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
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="company_id" title="EMPRESA" plus="none"/>

        <select wire:model="company_id" class="form-select form-select-sm text-uppercase" id="company_id" disabled>
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Company::get() as $key => $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="company_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="deposit_id" title="DEPÓSITO" plus="none"/>

        <select wire:model="deposit_id" class="form-select form-select-sm text-uppercase" id="deposit_id" disabled>
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Deposit::where('company_id', auth()->user()->company_id)->get() as $key => $deposit)
                <option value="{{ $deposit->id }}">{{ $deposit->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="deposit_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="producebrand_id" title="MARCA" plus="none"/>

        <select wire:model="producebrand_id" class="form-select form-select-sm text-uppercase" id="producebrand_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Producebrand::get() as $key => $producebrand)
                <option value="{{ $producebrand->id }}">{{ $producebrand->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="producebrand_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="producemeasure_id" title="EMBALAGEM" plus="none"/>

        <select wire:model="producemeasure_id" class="form-select form-select-sm text-uppercase" id="producemeasure_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Producemeasure::get() as $key => $producemeasure)
                <option value="{{ $producemeasure->id }}">{{ $producemeasure->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="producemeasure_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="value" title="VALOR(R$)" plus="none"/>

        <input type="text" wire:model="value" class="form-control form-control-sm" id="value" onKeyUp="maskFloat2(this, event)">

        <x-layout.modal.modal-add-body-group-item-error item="value" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
