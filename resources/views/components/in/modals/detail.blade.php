<x-layout.modal.modal-detail modal="detail" size="">
    <x-layout.modal.modal-detail-header icon="bi-eye" modal="detail">
        {{ $config['title'] }}

        <x-slot:identifier>
            MARCA
            <i class="bi bi-caret-right-fill"></i>
            <span class="text-primary fw-bold">
                {{ $producebrand_name }}
            </span>

            <br>

            ENTRADA<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $in_id }}</span>
            <br>
            DEPÓSITO<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $deposit_name }}</span>
        </x-slot>
    </x-layout.modal.modal-detail-header>

    <x-layout.modal.modal-detail-body>

{{-- conteúdo --}}
<ol class="list-group list-group-numbered">
    @foreach(App\Models\Inproduce::where('in_id', $in_id)->get() as $key => $inproduce)
        <li class="list-group-item d-flex justify-content-between align-items-start" style="font-size: 9pt;">
            <div class="ms-2 me-auto text-dark fw-bold" style="line-height: 1; font-size: 10pt;">
                {{ $inproduce->produce->name }}
                <br>
                <span class="text-muted" style="font-size: 8pt;">
                    {{ $inproduce->produce->reference }}
                    <i class="bi-caret-right-fill"></i>
                    {{ $inproduce->produce->ean }}
                    <i class="bi-caret-right-fill"></i>
                    {{ $inproduce->produce->producemeasure_name }}
                </span>
            </div>
            <span class="badge text-bg-dark rounded-pill" style="font-size: 10pt;">{{ number_format($inproduce->quantity) }}</span>
        </li>
    @endforeach
</ol>
{{-- conteúdo --}}

    </x-layout.modal.modal-detail-body>
</x-layout.modal.modal-detail>
