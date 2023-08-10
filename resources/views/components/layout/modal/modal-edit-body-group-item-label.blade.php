<label for="{{ $item }}" style="font-size: 9pt; @if($plus != 'none') margin-bottom: -10px; @endif">
    {{ $title }}

    @if($plus != 'none')
        <a type="button" href="/{{ $plus }}" target="_blank" class="btn btn-link btn-sm" style="margin-bottom: 5px;" title="Cadastrar">
            <i class="bi-plus-circle-fill text-success opacity-75" style="font-size: 17px;"></i>
        </a>
    @endif
</label>
