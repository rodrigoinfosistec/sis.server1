<x-layout.modal.modal-edit modal="editDeposit" size="">
    <x-layout.modal.modal-edit-header icon="bi-toggles" modal="editDeposit">
        Depósitos do {{ $config['title'] }}

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

    <x-layout.modal.modal-edit-body method="modernizeDeposit">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    @foreach(App\Models\Deposit::where(['company_id'=>auth()->user()->company_id, 'status'=>true])->orderBy("name", "ASC")->get() as $key => $deposit)
        @if(App\Models\Producedeposit::where(['produce_id'=>$produce_id, 'deposit_id'=>$deposit->id])->doesntExist())
            <x-layout.modal.modal-edit-body-group-item columms="12">
                <x-layout.modal.modal-edit-body-group-item-switch>
                    <input wire:model="array_deposit.{{ $deposit->id }}" class="form-check-input" type="checkbox" role="switch" id="array_deposit{{ $deposit->id }}">
                    <label class="form-check-label" for="array_deposit{{ $deposit->id }}">{{ $deposit->name }}</label>
                </x-layout.modal.modal-edit-body-group-item-switch>
            </x-layout.modal.modal-edit-body-group-item>
        @endif
    @endforeach
    <hr>
    @foreach(App\Models\Deposit::where(['company_id'=>auth()->user()->company_id, 'status'=>true])->orderBy("name", "ASC")->get() as $key => $deposit)
        @if(App\Models\Producedeposit::where(['produce_id'=>$produce_id, 'deposit_id'=>$deposit->id])->exists())
            <x-layout.modal.modal-edit-body-group-item columms="12">
                <i class="bi-check-all text-primary"></i>
                {{ $deposit->name }}
            </x-layout.modal.modal-edit-body-group-item>
        @endif
    @endforeach
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
