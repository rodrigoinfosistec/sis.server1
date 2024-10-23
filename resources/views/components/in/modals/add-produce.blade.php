<x-layout.modal.modal-add modal="addProduce" method="registerProduce" size="">
    <x-layout.modal.modal-add-header icon="bi-basket" modal="addProduce">
        Produto na Entrada

        <x-slot:identifier>
            DEPÓSITO
            <i class="bi bi-caret-right-fill"></i>
            <span class="text-dark fw-bold">
                {{ $deposit_name }}
            </span>

            <br>

            ENTRADA
            <i class="bi bi-caret-right-fill"></i>
            <span class="text-dark fw-bold">
                #{{ $in_id }}
            </span>
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerProduce">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="8">
        <x-layout.modal.modal-add-body-group-item-label item="produce_id" title="PESQUISA" plus="none"/>

        <input wire:model="produce_id" type="text" class="form-control form-control-sm" list="produces" id="produce_id">
        <datalist id="produces">
            @foreach(App\Models\Produce::where(['company_id' => auth()->user()->company_id, 'status' => true])->orderBy('name', 'ASC')->get() as $key => $produce)
                <option value="{{ $produce->id }}">
                    {{ $produce->name }}
                    |
                    {{ $produce->ean }}
                    |
                    {{ $produce->reference }}
                </option>
            @endforeach
        </datalist>

        <x-layout.modal.modal-add-body-group-item-error item="produce_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="quantity" title="QUANTIDADE" plus="none"/>

        <input type="number" min="1" wire:model="quantity" class="form-control form-control-sm" id="quantity" required>

        <x-layout.modal.modal-add-body-group-item-error item="quantity" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <textarea class="form-control form-control-sm bg-light text-primary fw-bold" style="font-size: 12pt;" rows="4" readonly required>{{ @App\Models\Produce::find($produce_id)->name }}
@if(isset($produce_id))
    @if(@App\Models\Producedeposit::where(['produce_id' => $produce_id, 'deposit_id' => $deposit_id])->exists())
        @php
            $proddep = @App\Models\Producedeposit::where(['produce_id' => $produce_id, 'deposit_id' => $deposit_id])->first();
        @endphp
        {{ @App\Models\General::decodeFloat2($proddep->quantity) }} {{ @App\Models\Produce::find($produce_id)->producemeasure_name }}
    @else
        0,00
    @endif
@endif
        </textarea>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
