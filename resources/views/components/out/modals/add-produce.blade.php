<x-layout.modal.modal-add modal="addProduce" method="registerProduce" size="">
    <x-layout.modal.modal-add-header icon="bi-basket" modal="addProduce">
        Produto na Saída

        <x-slot:identifier>
            SAÍDA
            <i class="bi bi-caret-right-fill"></i>
            <span class="text-dark fw-bold">
                #{{ $out_id }}
            </span>
            <br>
            DEPÓSITO
            <i class="bi bi-caret-right-fill"></i>
            <span class="text-primary">
                {{ $deposit_name }}
            </span>
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerProduce">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="8">
        <x-layout.modal.modal-add-body-group-item-label item="produce_id" title="PRODUTO" plus="none"/>

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

        <input type="text" wire:model="quantity" class="form-control form-control-sm" id="quantity" onKeyUp="maskFloat2(this, event)" required>

        <x-layout.modal.modal-add-body-group-item-error item="quantity" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <textarea class="form-control form-control-sm bg-light" rows="2" readonly required>{{ @App\Models\Produce::find($produce_id)->name }}</textarea>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
