<x-layout.modal.modal-edit modal="edit" size="">
    <x-layout.modal.modal-edit-header icon="bi-pencil-square" modal="edit">
        {{ $config['title'] }}

        <x-slot:identifier>
            PIS {{ $pis }}
            <br>
            {{ $name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernize">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="company_id" title="EMPRESA" plus="company"/>

        <select wire:model="company_id" class="form-select form-select-sm text-uppercase" id="company_id">
            <x-layout.modal.modal-edit-body-group-item-option-muted/>

            @foreach(App\Models\Company::get() as $key => $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-edit-body-group-item-error item="company_id" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="employeegroup_id" title="GRUPO" plus=""/>

        <select wire:model="employeegroup_id" class="form-select form-select-sm text-uppercase" id="employeegroup_id">
            <x-layout.modal.modal-edit-body-group-item-option-muted/>

            @foreach(App\Models\Employeegroup::where('status', true)->get() as $key => $employeegroup)
                <option value="{{ $employeegroup->id }}">{{ $employeegroup->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-edit-body-group-item-error item="employeegroup_id" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="4">
        <x-layout.modal.modal-edit-body-group-item-label item="pis" title="PIS" plus="none"/>

            <input type="text" wire:model="pis" class="form-control form-control-sm" id="pis" onKeyUp="maskPis(this, event)" disabled>

        <x-layout.modal.modal-edit-body-group-item-error item="pis" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="8">
        <x-layout.modal.modal-edit-body-group-item-label item="name" title="NOME" plus="none"/>

            <input type="text" wire:model="name" class="form-control form-control-sm" id="name">

        <x-layout.modal.modal-edit-body-group-item-error item="name" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="4">
        <x-layout.modal.modal-edit-body-group-item-label item="registration" title="MATRÍCULA" plus="none"/>

            <input type="number" wire:model="registration" class="form-control form-control-sm" id="registration">

        <x-layout.modal.modal-edit-body-group-item-error item="registration" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="8">
        <x-layout.modal.modal-edit-body-group-item-label item="companyoriginal_id" title="EMPRESA ORIGEM" plus="none"/>

        <select wire:model="companyoriginal_id" class="form-select form-select-sm text-uppercase" id="companyoriginal_id">
            <x-layout.modal.modal-edit-body-group-item-option-muted/>

            @foreach(App\Models\Company::get() as $key => $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-edit-body-group-item-error item="companyoriginal_id" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="journey_start_week" title="JORNADA SEMANA (INÍCIO)" plus="none"/>

        <input type="time" wire:model="journey_start_week" class="form-control form-control-sm" id="journey_start_week">

        <x-layout.modal.modal-edit-body-group-item-error item="journey_start_week" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="journey_end_week" title="JORNADA SEMANA (FINAL)" plus="none"/>

        <input type="time" wire:model="journey_end_week" class="form-control form-control-sm" id="journey_end_week">

        <x-layout.modal.modal-edit-body-group-item-error item="journey_end_week" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="journey_start_saturday" title="JORNADA SÁBADO (INÍCIO)" plus="none"/>

        <input type="time" wire:model="journey_start_saturday" class="form-control form-control-sm" id="journey_start_saturday">

        <x-layout.modal.modal-edit-body-group-item-error item="journey_start_saturday" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="journey_end_saturday" title="JORNADA SÁBADO (FINAL)" plus="none"/>

        <input type="time" wire:model="journey_end_saturday" class="form-control form-control-sm" id="journey_end_saturday">

        <x-layout.modal.modal-edit-body-group-item-error item="journey_end_saturday" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="journey" title="JORNADA (HORAS)" plus="none"/>

        <input type="time" wire:model="journey" class="form-control form-control-sm" id="journey">

        <x-layout.modal.modal-edit-body-group-item-error item="journey" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="clock_type" title="TIPO PONTO" plus="none"/>

        <select wire:model="clock_type" class="form-select form-select-sm text-uppercase" id="clock_type">
            <x-layout.modal.modal-edit-body-group-item-option-muted/>

            <option value="EVENT">LOCAL</option>
            <option value="REGISTRY">ALTERNATIVO</option>
        </select>

        <x-layout.modal.modal-edit-body-group-item-error item="clock_type" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="code" title="CÓDIGO" plus="none"/>

        <input type="text" wire:model="code" class="form-control form-control-sm" id="code">

        <x-layout.modal.modal-edit-body-group-item-error item="code" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <x-layout.modal.modal-edit-body-group-item-status>
            <input wire:model="status" class="form-check-input" type="checkbox" role="switch" id="status">

            <x-slot:label>
                @if($status)
                    <x-layout.modal.modal-edit-body-group-item-status-active/>
                @else
                    <x-layout.modal.modal-edit-body-group-item-status-inactive/>
                @endif
            </x-slot>
        </x-layout.modal.modal-edit-body-group-item-status>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <div class="form-check form-switch">
            <input wire:model="trainee" class="form-check-input" type="checkbox" role="switch" id="trainee">
        
            <label class="form-check-label" for="trainee">
                <span class="" style="font-size: 9pt;">
                    ESTAGIÁRIO? 
                </span>

                @if($trainee)
                    <span class="text-danger fw-bold" style="font-size: 9pt;">
                        SIM
                    </span>
                @else
                    <span class="text-muted fw-bold" style="font-size: 9pt;">
                        NÃO
                    </span>
                @endif
            </label>
        </div>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <div class="form-check form-switch">
            <input wire:model="limit_controll" class="form-check-input" type="checkbox" role="switch" id="limit_controll">
        
            <label class="form-check-label" for="limit_controll">
                <span class="" style="font-size: 9pt;">
                    PONTO CONTROLADO? 
                </span>

                @if($limit_controll)
                    <span class="text-danger fw-bold" style="font-size: 9pt;">
                        SIM
                    </span>
                @else
                    <span class="text-muted fw-bold" style="font-size: 9pt;">
                        NÃO
                    </span>
                @endif
            </label>
        </div>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
