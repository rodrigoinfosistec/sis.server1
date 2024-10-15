<x-layout.modal.modal-detail modal="detail" size="">
    <x-layout.modal.modal-detail-header icon="bi-eye" modal="detail">
        {{ $config['title'] }}

        <x-slot:identifier>
            BALANÇO<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $inventory_id }}</span>
            <br>
            DEPÓSITO<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $deposit_name }}</span>
            <br>
            MARCA<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $producebrand_name }}</span>
        </x-slot>
    </x-layout.modal.modal-detail-header>

    <x-layout.modal.modal-detail-body>

{{-- conteúdo --}}
<ol class="list-group list-group-numbered">
    @foreach(App\Models\Inventoryproduce::where('inventory_id', $inventory_id)->get() as $key => $inventoryproduce)
        <li class="list-group-item d-flex justify-content-between align-items-start" style="font-size: 9pt;">
            <div class="ms-2 me-auto text-primary fw-bold" style="line-height: 1; font-size: 10pt;">
                {{ $inventoryproduce->produce->name }}
                <br>
                <span class="text-muted" style="font-size: 8pt;">
                    {{ $inventoryproduce->produce->producebrand_name }}
                    <i class="bi-caret-right-fill"></i>
                    {{ $inventoryproduce->produce->producemeasure_name }}
                </span>
            </div>
            <span class="badge text-bg-dark rounded-pill" style="font-size: 10pt;">{{ number_format($inventoryproduce->quantity) }}</span>
        </li>
    @endforeach
</ol>
{{-- conteúdo --}}

    </x-layout.modal.modal-detail-body>
</x-layout.modal.modal-detail>
