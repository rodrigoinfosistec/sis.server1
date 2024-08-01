<x-layout.modal.modal-edit modal="editItemAmount" size="modal-fullscreen">
    <x-layout.modal.modal-edit-header icon="bi-basket" modal="editItemAmount">
        Itens 

        <x-slot:identifier>
            NFe {{ $number }}
            <br>
            {{ $provider_name }}
            <br>
            {{ $deposit_name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeItemAmount">

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
                    <div class="text-center" style="width: 120px;">
                        EMBALAGEM
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 90px;">
                        QTD NOVA
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
                            <a type="button" wire:click="excludeProduct({{ $depositinputproduct->id }})" class="btn btn-link btn-sm" title="Excluir Produto">
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
                            <i class="bi-caret-right-fill text-muted"></i>
                            <span class="text-primary">{{ App\Models\General::decodeFloat3($depositinputproduct->quantity) }}</span>
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

                    {{-- EMBALAGEM --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-center fw-bold" style="width: 120px; height: 25px;">
                            <div class="float-start" style="width: 45px;">
                                <select wire:model="array_product_signal.{{ $depositinputproduct->id }}" class="form-select form-control-sm text-uppercase text-danger" style="font-size: 8pt;  padding: 0 30px 0 5px;" id="array_product_signal_{{ $depositinputproduct->id }}" required>
                                    <option value="multiply" class="text-muted fw-bold" style="font-size: 6pt;">x</option>
                                    <option value="divide" class="text-muted fw-bold" style="font-size: 6pt;">/</option>
                                </select>
                            </div>
                            <div class="float-start" style="width: 68px;">
                                <input type="text" wire:model="array_product_amount.{{ $depositinputproduct->id }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px;" id="array_product_amount_{{ $depositinputproduct->id }}" onKeyUp="maskFloat3(this, event)" required>
                            </div>
                        </div>
                    </td>

                    {{-- FINAL --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-primary fw-bold" style="width: 90px; font-size: 10pt;">
                            @if(!empty($array_product_amount[$depositinputproduct->id]))
                                @if(App\Models\General::encodeFloat3($array_product_amount[$depositinputproduct->id]) > 0 && !empty(App\Models\General::encodeFloat3($array_product_amount[$depositinputproduct->id])))
                                    @if($array_product_signal[$depositinputproduct->id] == 'divide')
                                        {{ App\Models\General::decodeFloat3($depositinputproduct->quantity /  App\Models\General::encodeFloat3($array_product_amount[$depositinputproduct->id])) }}
                                    @else
                                        {{ App\Models\General::decodeFloat3($depositinputproduct->quantity *  App\Models\General::encodeFloat3($array_product_amount[$depositinputproduct->id])) }}
                                    @endif
                                @else
                                    <i class="bi-exclamation-triangle text-danger"></i>
                                @endif
                            @endif
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
        @php
            $ar = [];

            foreach($array_product_amount as $t):
                if(!(App\Models\General::encodeFloat3($t) > 0 && !empty($t))):
                    $ar[] = 1;
                endif;
            endforeach;
        @endphp 
        @if(empty($ar))
            <button wire:loading.attr="disabled" type="submit" class="btn btn-sm btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm" role="status"></span>
                Atualizar
            </button>
        @else
            <i class="bi-exclamation-triangle text-danger" style="font-size: 25pt;"></i>
        @endif
    </div>
</x-layout.modal.modal-edit>
