<x-layout.modal.modal-edit modal="editEmployee" size="">
    <x-layout.modal.modal-edit-header icon="bi-people" modal="editEmployee">
        {{ $config['title'] }}

        <x-slot:identifier>
            {{ $company_name }}

            <br>

            <span class="text-danger fw-bold" style="font-size: 11pt;">{{ date_format(date_create($date), "d/m/Y") }}</span>
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeEmployee">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    @foreach(App\Models\Presenceinemployee::where('presencein_id', $presencein_id)->orderBy("is_present", "ASC")->orderBy('employee_name', 'ASC')->get() as $key => $presenceinemployee)
        @if(!$presenceinemployee->is_present)
            <x-layout.modal.modal-edit-body-group-item columms="12">
                <x-layout.modal.modal-edit-body-group-item-switch>
                    <input wire:model="array_presenceinemployee.{{ $presenceinemployee->id }}" class="form-check-input" type="checkbox" role="switch" id="array_presenceinemployee_{{ $presenceinemployee->id }}">
                    <label class="form-check-label text-danger" style="font-size: 10pt;" for="array_presenceinemployee_{{ $presenceinemployee->id }}">
                        {{ $presenceinemployee->employee_name }}

                        <br>

                        @if(App\Models\Clockregistry::where(['employee_id' => $presenceinemployee->employee->id, 'date' => $presenceinemployee->presencein->date])->exists())
                            @foreach(App\Models\Clockregistry::where(['employee_id' => $presenceinemployee->employee->id, 'date' => $presenceinemployee->presencein->date])->orderBy('time', 'ASC')->get() as $key => $clockregistry)
                                <span class="badge rounded-pill text-bg-secondary">
                                    {{ $clockregistry->time }}
                                </span>
                            @endforeach
                        @endif
                    </label>
                </x-layout.modal.modal-edit-body-group-item-switch>
            </x-layout.modal.modal-edit-body-group-item>
        @else
            <label class="form-check-label" style="font-size: 10pt;">
                <i class="bi-check2-circle text-success" style="font-size: 14pt;"></i>
                {{ $presenceinemployee->employee_name }}

                <br>

                @if(App\Models\Clockregistry::where(['employee_id' => $presenceinemployee->employee->id, 'date' => $presenceinemployee->presencein->date])->exists())
                    @foreach(App\Models\Clockregistry::where(['employee_id' => $presenceinemployee->employee->id, 'date' => $presenceinemployee->presencein->date])->orderBy('time', 'ASC')->get() as $key => $clockregistry)
                        <span class="badge rounded-pill text-bg-secondary">
                            {{ $clockregistry->time }}
                        </span>
                    @endforeach
                @endif
            </label>
        @endif
    @endforeach
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
