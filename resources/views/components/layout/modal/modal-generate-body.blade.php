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

        ITENS: <span class="fw-bold">{{ $count }}</span>

        @if($count < 1)
            <i class="bi-caret-right-fill text-muted" style="font-size: 11px;"></i>

            <span class="text-muted" style="font-size: 10pt;">
                IMPOSSÍVEL GERAR RELATÓRIO
            </span>
        @endif

        .

    @else
        RELATÓRIO COMPLETO.
    @endif
</div>
