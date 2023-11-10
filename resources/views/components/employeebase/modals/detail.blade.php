<x-layout.modal.modal-detail modal="detail" size="">
    <x-layout.modal.modal-detail-header icon="bi-eye" modal="detail">
        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-detail-header>

    <x-layout.modal.modal-detail-body>

{{-- conteúdo --}}
<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        PIS
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $employee_pis }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

@if(!empty($employee_cpf))
    <x-layout.modal.modal-detail-body-line>
        <x-layout.modal.modal-detail-body-line-title>
            CPF
        </x-layout.modal.modal-detail-body-line-title>
        <x-layout.modal.modal-detail-body-line-content>
            {{ $employee_cpf }}
        </x-layout.modal.modal-detail-body-line-content>
    </x-layout.modal.modal-detail-body-line>
@endif

@if(!empty($employee_rg))
    <x-layout.modal.modal-detail-body-line>
        <x-layout.modal.modal-detail-body-line-title>
            RG
        </x-layout.modal.modal-detail-body-line-title>
        <x-layout.modal.modal-detail-body-line-content>
            {{ $employee_rg }}
        </x-layout.modal.modal-detail-body-line-content>
    </x-layout.modal.modal-detail-body-line>
@endif

@if(!empty($employee_cnh))
    <x-layout.modal.modal-detail-body-line>
        <x-layout.modal.modal-detail-body-line-title>
            HABILITAÇÃO
        </x-layout.modal.modal-detail-body-line-title>
        <x-layout.modal.modal-detail-body-line-content>
            {{ $employee_cnh }}
        </x-layout.modal.modal-detail-body-line-content>
    </x-layout.modal.modal-detail-body-line>
@endif

@if(!empty($employee_ctps))
    <x-layout.modal.modal-detail-body-line>
        <x-layout.modal.modal-detail-body-line-title>
            CARTEIRA DE TRABALHO
        </x-layout.modal.modal-detail-body-line-title>
        <x-layout.modal.modal-detail-body-line-content>
            {{ $employee_ctps }}
        </x-layout.modal.modal-detail-body-line-content>
    </x-layout.modal.modal-detail-body-line>
@endif
{{-- conteúdo --}}

    </x-layout.modal.modal-detail-body>
</x-layout.modal.modal-detail>
