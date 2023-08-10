<x-layout.modal.modal-erase modal="erase" method="exclude" form="true" size="">
    <x-layout.modal.modal-erase-header icon="bi-trash3" modal="erase">
        {{ $config['title'] }}
        
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
        {{ $company_id }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        CNPJ
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $cnpj }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        RAZÃO SOCIAL
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $name }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        NOME FANTASIA
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $nickname }}
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
