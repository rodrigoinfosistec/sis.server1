<x-layout.modal.modal-detail modal="detail" size="">
    <x-layout.modal.modal-detail-header icon="bi-eye" modal="detail">
        {{ $config['title'] }}

        <x-slot:identifier>
            BALANÇO<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $balance_id }}</span>
            <br>
            DEPÓSITO<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $deposit_name }}</span>
            <br>
            FORNECEDOR<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $provider_name }}</span>
        </x-slot>
    </x-layout.modal.modal-detail-header>

    <x-layout.modal.modal-detail-body>

{{-- conteúdo --}}
<ol class="list-group list-group-numbered">
    @foreach(App\Models\Balanceproduct::where('balance_id', $balance_id)->get() as $key => $balanceproduct)
        <li class="list-group-item d-flex justify-content-between align-items-start" style="font-size: 9pt;">
            <div class="ms-2 me-auto text-dark" style="font-size: 9pt;">
                {{ $balanceproduct->product->name }}
            </div>
            <span class="badge text-bg-secondary rounded-pill">{{ number_format($balanceproduct->quantity) }}</span>
        </li>
    @endforeach
</ol>
{{-- conteúdo --}}

    </x-layout.modal.modal-detail-body>
</x-layout.modal.modal-detail>
