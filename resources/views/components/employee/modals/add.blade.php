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

            @foreach(App\Models\Company::get() as $key => $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="company_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="8">
        <x-layout.modal.modal-add-body-group-item-label item="name" title="NOME" plus="none"/>

        <input type="text" wire:model="name" class="form-control form-control-sm" id="name">

        <x-layout.modal.modal-add-body-group-item-error item="name" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="pis" title="PIS" plus="none"/>

        <input type="text" wire:model="pis" class="form-control form-control-sm" id="pis" onKeyUp="maskPis(this, event)">

        <x-layout.modal.modal-add-body-group-item-error item="pis" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="registration" title="PIS" plus="none"/>

        <input type="number" wire:model="registration" class="form-control form-control-sm" id="registration">

        <x-layout.modal.modal-add-body-group-item-error item="registration" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="journey_start_week" title="JORNADA SEMANA (INÍCIO)" plus="none"/>

        <input type="time" wire:model="journey_start_week" class="form-control form-control-sm" id="journey_start_week">

        <x-layout.modal.modal-add-body-group-item-error item="journey_start_week" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="journey_end_week" title="JORNADA SEMANA (FINAL)" plus="none"/>

        <input type="time" wire:model="journey_end_week" class="form-control form-control-sm" id="journey_end_week">

        <x-layout.modal.modal-add-body-group-item-error item="journey_end_week" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="journey_start_saturday" title="JORNADA SÁBADO (INÍCIO)" plus="none"/>

        <input type="time" wire:model="journey_start_saturday" class="form-control form-control-sm" id="journey_start_saturday">

        <x-layout.modal.modal-add-body-group-item-error item="journey_start_saturday" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="journey_end_saturday" title="JORNADA SÁBADO (FINAL)" plus="none"/>

        <input type="time" wire:model="journey_end_saturday" class="form-control form-control-sm" id="journey_end_saturday">

        <x-layout.modal.modal-add-body-group-item-error item="journey_end_saturday" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
