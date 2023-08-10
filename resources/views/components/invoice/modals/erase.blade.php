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
        {{ $invoice_id }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        NÚMERO <i class="bi-caret-right-fill text-muted"></i> SÉRIE
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $number }}
        <i class="bi-caret-right-fill text-muted"></i>
        {{ $range }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        CHAVE
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $key }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        FORNECEDOR
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $provider_name }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        EMPRESA
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $company_name }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        TOTAL NFE
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        <span class="fst-italic">R$</span>
        {{ App\Models\General::decodeFloat2((float)$total) }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        EMISSÃO
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $issue }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        QUANTIDADE
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        ITENS NFE
        <i class="bi-caret-right-fill text-muted"></i>
        {{ App\Models\Invoiceitem::where('invoice_id', (int)$invoice_id)->get()->count() }}
        <br>
        ITENS CSV
        <i class="bi-caret-right-fill text-muted"></i>
        {{ App\Models\Invoicecsv::where('invoice_id', (int)$invoice_id)->get()->count() }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>
{{-- conteúdo --}}

    </x-layout.modal.modal-erase-body>

    <x-layout.modal.modal-erase-footer/>

</x-layout.modal.modal-erase>
