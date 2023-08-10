<x-layout.modal.modal-detail modal="detailAlert" size="">
    <x-layout.modal.modal-detail-header icon="bi-exclamation-circle" modal="detailAlert">
        dos Alertas de {{ $config['title'] }}

        <x-slot:identifier>
            NFe {{ $number }}
            <br>
            {{ $provider_name }}
        </x-slot>
    </x-layout.modal.modal-detail-header>

    <x-layout.modal.modal-detail-body>
        @foreach(App\Models\Invoice::alerts((int)$invoice_id)['message'] as $key => $alert)

{{-- conteúdo --}}
<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        {{  $loop->iteration }}
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        <span class="text-danger">
            {{ $alert }}
        </span>
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>
{{-- conteúdo --}}

        @endforeach
    </x-layout.modal.modal-detail-body>
</x-layout.modal.modal-detail>
