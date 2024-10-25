<x-layout.modal.modal-generate size="">
    <x-layout.modal.modal-generate-header>
        {{ $config['title'] }}
    </x-layout.modal.modal-generate-header>

    <div class="modal-body">
        @if($search != '')
            FILTRO: [
            <span class="text-danger">
                {{ $filter }}
            </span>

            <i class="bi-caret-right-fill text-muted" style="font-size: 11px;"></i>

            <span class="text-primary">
                @if($filter == 'status')
                    @if($search == 1)
                        ATIVO
                    @else
                        INATIVO
                    @endif
                @else
                    {{ $search }}
                @endif
            </span>

            ].

            <br>

            ITENS: <span class="fw-bold">{{ $list->total() }}</span>

            @if($list->total() < 1)
                <i class="bi-caret-right-fill text-muted" style="font-size: 11px;"></i>

                <span class="text-muted" style="font-size: 10pt;">
                    IMPOSSÍVEL GERAR RELATÓRIO
                </span>
            @endif

            .

        @else
            RELATÓRIO COMPLETO.
        @endif

        <br>
        DEPÓSITO
        <i class="bi-caret-right-fill"></i>
        @if($deposit_id != '')
            {{ App\Models\Deposit::find($deposit_id)->name }}
        @else
            TODOS
        @endif
    </div>

    <x-layout.modal.modal-generate-footer/>
</x-layout.modal.modal-generate>
