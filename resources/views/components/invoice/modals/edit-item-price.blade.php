<x-layout.modal.modal-edit modal="editItemPrice" size="modal-fullscreen">
    <x-layout.modal.modal-edit-header icon="bi-basket" modal="editItemPrice">
        Preço de itens de {{ $config['title'] }}

        <x-slot:identifier>
            NFe {{ $number }}
            <br>
            {{ $provider_name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeItemPrice">

{{-- conteúdo --}}
<div class="table-responsive">
    <table class="table table-sm table-borderless table-hover" wire:loading.class.deley="opacity-50">
        <thead class="fw-bolder" style="border-bottom: #808080 1px solid; font-size: 7.5pt;">
            <tr class="text-dark">
                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 40px;">
                        ITEM
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 22px;">
                        {{-- --}}
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 56px;">
                        <span class="text-danger">MANTER?</span>
                        <div class="form-check form-switch" style="margin-bottom: -10px;">
                            <input type="checkbox" wire:model="hold_all" class="form-check-input" style="font-size: 10pt; padding: 0;" id="hold_all" role="switch">
                            <label for="hold_all" class="form-check-label fw-bold" style="font-size: 6pt; margin-top: 5px; margin-left: -6px;">TODOS</label>
                        </div>
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center text-primary" style="width: 65px;">
                        FINAL(R$)
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center text-primary" style="width: 65px;">
                        CARTÃO(R$)
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center text-primary" style="width: 65px;">
                        VAREJO(R$)
                    </div>
                </th>
                
                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 55px;">
                        ÍNDICE
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 50px;">
                        MRG/FRT
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 80px;">
                        QTD/UNT/TOT
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 50px;">
                        EMB.
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 60px;">
                        IPI
                    </div>
                </th>
                
                <th class="" style="padding: 0;">
                    <div class="" style="width: 36px;">
                        EQUIP.
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 90px;">
                        DADOS CSV
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 90px;">
                        EAN/NCM/CEST
                    </div>
                </th>
            </tr>
        </thead>

        <tbody style="font-size: 7pt;">
            @foreach(App\Models\Invoiceitem::where('invoice_id', $invoice_id)->get() as $key => $invoiceitem)
                <tr>
                    {{-- ITEM --}}
                    <th rowspan="2" class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-break text-center" style="width: 40px; font-size: 9pt;">
                            <span class="badge rounded-pill bg-secondary">
                                {{ str_pad($invoiceitem->identifier, Str::length(App\Models\Invoiceitem::where('invoice_id', $invoice_id)->get()->count()), '0', STR_PAD_LEFT); }}
                            </span>
                        </div>
                    </td>

                    {{-- CHECKBOX --}}
                    <td rowspan="2" class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 22px;">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" style="width: 15px; height: 15px;" onchange="closest('tr').classList.toggle('row_selected')">
                            </div>
                        </div>
                    </td>

                    <td colspan="100%" class="align-middle" style="line-height: 1; padding: 6px 0 0 0;">
                        {{-- CÓDIGO E DESCRIÇÃO --}}
                        <div class="fw-bolder" style="width: 850px; font-size: 8pt;" title="{{ $invoiceitem->name }}">
                            <span class="text-muted">
                                {{ $invoiceitem->code }}
                            </span>
                            <i class="bi-caret-right-fill text-muted"></i>
                            {{ mb_strimwidth($invoiceitem->name, 0, 90, "...") }}
                        </div>
                    </td>
                </tr>

                <tr style="border-bottom: 1px solid #ddd;">
                    
                    {{-- MANTER? --}}
                    <td class="" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 46px; margin-left: 11px;">
                            <div class="form-check form-switch">
                                <input type="checkbox" wire:model="array_item_hold.{{ $invoiceitem->id }}" class="form-check-input" style="font-size: 10pt;" id="array_item_hold_{{ $invoiceitem->id }}" role="switch">
                                <label for="array_item_hold_{{ $invoiceitem->id }}" class="form-check-label"></label>
                            </div>
                        </div>
                    </td>

                    {{-- FINAL(R$) --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 75px;">
                            <span class="text-danger">R$ {{-- App\Models\General::decodeFloat3($invoiceitem->price_csv) ?? '' --}}</span>
                            <input type="text" wire:model="array_item_price.{{ $invoiceitem->id }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 70px;" id="array_item_price_{{ $invoiceitem->id }}" onKeyUp="maskFloat3(this, event)" required>
                        </div>
                    </td>

                    {{-- CARTÃO(R$) --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 65px;">
                            <span class="text-danger">R$ {{-- App\Models\General::decodeFloat3($invoiceitem->card_csv) ?? '' --}}</span>
                            <input type="text" wire:model="array_item_card.{{ $invoiceitem->id }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 60px;" id="array_item_card_{{ $invoiceitem->id }}" onKeyUp="maskFloat3(this, event)" required>
                        </div>
                    </td>

                    {{-- VAREJO(R$) --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 65px;">
                            <span class="text-danger">R$ {{-- App\Models\General::decodeFloat3($invoiceitem->retail_csv) ?? '' --}}</span>
                            <input type="text" wire:model="array_item_retail.{{ $invoiceitem->id }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 60px;" id="array_item_retail_{{ $invoiceitem->id }}" onKeyUp="maskFloat3(this, event)" required>
                        </div>
                    </td>

                    {{-- ÍNDICE --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-center" style="width: 55px; font-size: 9pt;">
                            {{ App\Models\general::decodeFloat2($invoiceitem->index) }} %
                        </div>
                    </td>

                    {{-- MRG/FRT --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 50px;">
                            {{ App\Models\general::decodeFloat2($invoiceitem->margin) }} %
                            <br>
                            {{ App\Models\general::decodeFloat2($invoiceitem->shipping) }} %
                        </div>
                    </td>

                    {{-- QTD/UNT/TOT --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 80px;">
                            @php
                                if($invoiceitem->signal == '/'):
                                    $qtd = $invoiceitem->quantity_final * $invoiceitem->amount;
                                    $vlr = $invoiceitem->value_final / $invoiceitem->amount;
                                else:
                                    $qtd = $invoiceitem->quantity_final / $invoiceitem->amount;
                                    $vlr = $invoiceitem->value_final * $invoiceitem->amount;
                                endif;
                            @endphp
                            {{ App\Models\general::decodeFloat3($qtd) }} <span class="text-danger">X</span>
                            <br>
                            R$ {{ App\Models\general::decodeFloat3($vlr) }}
                            <br>
                            <span class="text-danger">R$ {{ App\Models\general::decodeFloat3($qtd * $vlr) }}</span>
                        </div>
                    </td>

                    {{-- EMB. --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-center" style="width: 50px;">
                            {{ $invoiceitem->measure }}
                            <br>
                            <span class="text-danger">{{ $invoiceitem->signal }}</span>
                            <br>
                            {{ App\Models\general::decodeFloat3($invoiceitem->amount) }}
                        </div>
                    </td>

                    {{-- IPI --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 60px;">
                            R$ {{ App\Models\general::decodeFloat3($invoiceitem->ipi_final) }}
                            <br>
                            {{ App\Models\general::decodeFloat3($invoiceitem->ipi_aliquot_final) }} %
                        </div>
                    </td>
                    
                    {{-- EQUIP. --}}
                    <td class="" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 25px; margin-left: 11px;">
                            <div class="form-check form-switch">
                                <input type="checkbox" wire:model="array_item_equipment.{{ $invoiceitem->id }}" class="form-check-input" style="font-size: 10pt;" id="array_item_equipment_{{ $invoiceitem->id }}" role="switch" disabled>
                                <label for="array_item_equipment_{{ $invoiceitem->id }}" class="form-check-label"></label>
                            </div>
                        </div>
                    </td>

                    {{-- DADOS CSV --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 90px;">
                            REF<i class="bi-caret-right-fill text-muted"></i>{{ $invoiceitem->invoicecsv->reference }}
                            <br>
                            EAN<i class="bi-caret-right-fill text-muted"></i>{{ $invoiceitem->invoicecsv->ean }}
                            <br>
                            INT<i class="bi-caret-right-fill text-muted"></i>{{ $invoiceitem->invoicecsv->code }}
                        </div>
                    </td>
    
                    {{-- EAN/NCM/CEST --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 90px;">
                            {{ $invoiceitem->ean }}
                            <br>
                            {{ $invoiceitem->ncm }}
                            <br>
                            {{ $invoiceitem->cest }}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
