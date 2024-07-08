<x-layout.modal.modal-add modal="addProduct" method="registerProduct" size="">
    <x-layout.modal.modal-add-header icon="bi-plus-circle-fill" modal="addProduct">
        {{ $config['title'] }}

        <x-slot:identifier>
            SAÍDA
            <i class="bi bi-caret-right-fill"></i>
            #{{ $output_id }}
            <br>
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

        <input wire:model="product_id" type="text" class="form-control form-control-sm" list="products" id="product_id">
        <datalist id="products">
            @foreach(App\Models\Product::where('status', true)->orderBy('name', 'ASC')->get() as $key => $product)
                <option value="{{ $product->id }}">
                    {{ $product->reference }}
                    |
                    {{ $product->name }}
                    |
                    {{ $product->ean }}
                </option>
            @endforeach
        </datalist>

        <x-layout.modal.modal-add-body-group-item-error item="product_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="quantity" title="QUANTIDADE" plus="none"/>

        <input type="number" wire:model="quantity" class="form-control form-control-sm" id="quantity">

        <x-layout.modal.modal-add-body-group-item-error item="quantity" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
