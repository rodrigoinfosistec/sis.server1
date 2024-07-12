<x-layout.modal.modal-erase modal="eraseProduct" method="excludeProduct" form="true" size="">
    <x-layout.modal.modal-erase-header icon="bi-trash3" modal="eraseProduct">
        Produto da saída

        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-erase-header>

    <x-layout.modal.modal-erase-body>
        <x-slot:question>
            Deseja realmente excluir?
        </x-slot>

        <x-slot:thead>
            {{-- ... --}}
        </x-slot>

{{-- conteúdo --}}
<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        ID
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $depositoutputproduct_id }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        DESCRIÇÃO
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $product_name }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        DEPÓSITO
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $deposit_name }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        QUANTIDADE
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $quantity }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        CADASTRO
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $created }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>
{{-- conteúdo --}}

    </x-layout.modal.modal-erase-body>

    <x-layout.modal.modal-erase-footer/>

</x-layout.modal.modal-erase>
