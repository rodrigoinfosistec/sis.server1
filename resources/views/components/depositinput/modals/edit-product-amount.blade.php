<x-layout.modal.modal-edit modal="editProductAmount" size="modal-fullscreen">
    <x-layout.modal.modal-edit-header icon="bi-basket" modal="editProductAmount">
        Itens 

        <x-slot:identifier>
            NFe {{ $number }}
            <br>
            {{ $provider_name }}
            <br>
            {{ $deposit_name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeProductAmount">

{{-- conteúdo --}}
<div class="table-responsive">
    <table class="table table-sm table-borderless table-hover" wire:loading.class.deley="opacity-50">
        <thead class="fw-bolder" style="border-bottom: #808080 1px solid; font-size: 7.5pt;">
            <tr class="text-dark">
                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 40px;">
                        #
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-right" style="width: 26px;">
                        <i class="bi-trash3-fill" style="font-size: 10pt;"></i>
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 150px;">
                        INT/DESC/QTD/EAN/REF
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 90px;">
                        QUANTIDADE
                    </div>
                </th>
            </tr>
        </thead>

        <tbody style="font-size: 7pt;">
            @foreach(App\Models\Depositinputproduct::where('depositinput_id', $depositinput_id)->get() as $key => $depositinputproduct)
                <tr>
                    {{-- # --}}
                    <td rowspan="2" class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-break text-center" style="width: 40px; font-size: 9pt;">
                            <span class="badge rounded-pill bg-secondary">
                                {{ str_pad($loop->iteration, Str::length(App\Models\Depositinputproduct::where('depositinput_id', $depositinput_id)->get()->count()), '0', STR_PAD_LEFT); }}
                            </span>
                        </div>
                    </td>

                    {{-- ERASE --}}
                    <td rowspan="2" class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 30px;">
                            <a type="button" wire:click="eraseProduct({{ $depositinputproduct->id }})" class="btn btn-link btn-sm" title="Excluir Produto">
                                <i class="bi-trash3-fill text-danger" style="font-size: 14pt;"></i>
                            </a>
                        </div>
                    </td>

                    <td colspan="100%" class="align-middle" style="line-height: 1; padding: 6px 0 0 0;">
                        {{-- CÓDIGO E DESCRIÇÃO --}}
                        <div class="fw-bolder" style="font-size: 8pt;" title="{{ $depositinputproduct->product->name }}">
                            <span class="text-muted">
                                {{ $depositinputproduct->product->code }}
                            </span>
                            <i class="bi-caret-right-fill text-muted"></i>
                            {{ $depositinputproduct->product->name }}
                        </div>
                    </td>
                </tr>

                <tr style="border-bottom: 1px solid #ddd;">
                    {{-- EAN/REF --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 150px;">
                            {{ $depositinputproduct->product->ean }}
                            &#187;
                            {{ $depositinputproduct->product->reference }}
                        </div>
                    </td>

                    {{-- QUANTIDADE --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-primary fw-bold" style="width: 90px; font-size: 10pt;">
                            {{ App\Models\General::decodeFloat3($depositinputproduct->quantity_final) }}
                        </div>
                    </td>
                </tr>
                @endforeach
        </tbody>
    </table>
</div>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <div class="modal-footer">
        <button wire:loading.attr="disabled" type="submit" class="btn btn-sm btn-primary">
            <span wire:loading class="spinner-border spinner-border-sm" role="status"></span>
            Atualizar
        </button>
    </div>
</x-layout.modal.modal-edit>
