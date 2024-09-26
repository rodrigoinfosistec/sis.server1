<x-layout.modal.modal-edit modal="editEmployee" size="">
    <x-layout.modal.modal-edit-header icon="bi-people" modal="editEmployee">
        {{ $config['title'] }}

        <x-slot:identifier>
            {{ $company_name }}

            <br>

            {{ $date }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeEmployee">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    @foreach(App\Models\Presenceinemployee::where('presencein_id', $presencein_id)->orderBy("is_present", "DESC")->orderBy('employee_name', 'ASC')->get() as $key => $presenceinemployee)
        <x-layout.modal.modal-edit-body-group-item columms="12">
            <x-layout.modal.modal-edit-body-group-item-switch>
                <input wire:model="array_presenceinemployee.{{ $presenceinemployee->id }}" class="form-check-input" type="checkbox" role="switch" id="array_presenceinemployee_{{ $presenceinemployee->id }}">
                <label class="form-check-label" for="array_presenceinemployee_{{ $presenceinemployee->id }}">{{ $presenceinemployee->employee_name }}</label>
            </x-layout.modal.modal-edit-body-group-item-switch>
        </x-layout.modal.modal-edit-body-group-item>
    @endforeach
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
