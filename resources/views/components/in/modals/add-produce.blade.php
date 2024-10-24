<x-layout.modal.modal-add modal="addProduce" method="registerProduce" size="">
    <x-layout.modal.modal-add-header icon="bi-basket" modal="addProduce">
        Produto na Entrada

        <x-slot:identifier>
            MARCA
            <i class="bi bi-caret-right-fill"></i>
            <span class="text-primary fw-bold">
                {{ $producebrand_name }}
            </span>

            <br>

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
        <x-layout.modal.modal-add-body-group-item-label item="produce_id" title="CÓDIGO DO PRODUTO" plus="none"/>

        <input wire:model="produce_id" type="text" class="form-control form-control-sm" list="produces" id="produce_id">
        <datalist id="produces">
            @foreach(App\Models\Produce::where(['producebrand_name' => $producebrand_name, 'company_id' => auth()->user()->company_id, 'status' => true])->orderBy('name', 'ASC')->get() as $key => $produce)
                <option value="{{ $produce->id }}">
                    {{ $produce->producebrand_name }}
                    |
                    {{ $produce->name }}
                    |
                    {{ $produce->reference }}
                    |
                    {{ $produce->ean }}
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
@if(isset($produce_id))
    @if(App\Models\Produce::where(['id'=>$produce_id, 'status'=>true])->exists())
        @php
            $prod = App\Models\Produce::find($produce_id);
        @endphp
        @if($prod->producebrand_id == $producebrand_id)
            <span class="text-primary fw-bold">
                {{ $prod->name }}
            </span>
            <br>
            <span class="fw-bold">
                @if(@App\Models\Producedeposit::where(['produce_id' => $produce_id, 'deposit_id' => $deposit_id])->exists())
                    @php
                        $proddep = @App\Models\Producedeposit::where(['produce_id' => $produce_id, 'deposit_id' => $deposit_id])->first();
                    @endphp
                    {{ @App\Models\General::decodeFloat2($proddep->quantity) }} {{ $prod->producemeasure_name }}
                @else
                    0,00
                @endif
            </span>
        @else
            <span class="text-danger fw-bold">
                CÓDIGO INVÁLIDO
            </span>
        @endif
    @else
        <span class="text-danger fw-bold">
            CÓDIGO INVÁLIDO
        </span>
    @endif
@endif
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
