<x-layout.modal.modal-add modal="addProduct" method="registerProduct" size="">
    <x-layout.modal.modal-add-header icon="bi-basket" modal="addProduct">
        Produto na Entrada

        <x-slot:identifier>
            ENTRADA
            <i class="bi bi-caret-right-fill"></i>
            <span class="text-dark fw-bold">
                #{{ $depositinput_id }}
            </span>
            <br>
            DEPÓSITO
            <i class="bi bi-caret-right-fill"></i>
            <span class="text-primary">
                {{ $deposit_name }}
            </span>
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerProduct">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="8">
        <x-layout.modal.modal-add-body-group-item-label item="product_id" title="PRODUTO" plus="none"/>

        @php
            // Inicializa variável.
            $array = [];

            // Monta o array.
            foreach(App\Models\Productdeposit::where('deposit_id', $origin_id)->get() as $key => $productdeposit):
                $array[] =  $productdeposit->product_id;
            endforeach;
        @endphp
        <input wire:model="product_id" type="text" class="form-control form-control-sm" list="products" id="product_id">
        <datalist id="products">
            @foreach(App\Models\Product::where(['company_id' => auth()->user()->company_id, 'status' => true])->whereIn('id', $array)->orderBy('name', 'ASC')->get() as $key => $product)
                <option value="{{ $product->id }}">
                    {{ $product->code }}
                    |
                    {{ $product->name }}
                    |
                    {{ $product->ean }}
                    |
                    {{ $product->reference }}
                </option>
            @endforeach
        </datalist>

        <x-layout.modal.modal-add-body-group-item-error item="product_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="quantity" title="QUANTIDADE" plus="none"/>

        <input type="text" wire:model="quantity" class="form-control form-control-sm" id="quantity" onKeyUp="maskFloat2(this, event)" required>

        <x-layout.modal.modal-add-body-group-item-error item="quantity" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <textarea class="form-control form-control-sm bg-light" rows="3" readonly required>{{ @App\Models\Product::find($product_id)->name }}
            @if(@App\Models\Productdeposit::where(['product_id' => $product_id, 'deposit_id' => $origin_id])->first()->quantity)
                {{ @App\Models\General::decodeFloat2(App\Models\Productdeposit::where(['product_id' => $product_id, 'deposit_id' => $origin_id])->first()->quantity) }}
            @endif
        </textarea>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
