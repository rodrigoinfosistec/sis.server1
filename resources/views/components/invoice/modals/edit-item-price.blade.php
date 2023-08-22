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
                        <div class="form-check" style="margin-bottom: -6px; margin-left: 2px;">
                            <input type="checkbox" class="form-check-input" style="width: 12px; height: 12px;" disabled>
                        </div>
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 90px;">
                        EAN/NCM/CEST
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 90px;">
                        DADOS CSV
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 50px;">
                        EMB.
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 75px;">
                        INDEX
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 155px;">
                        PRODUTO CSV
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 125px;">
                        GRUPO PRODUTO
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 36px;">
                        EQUIP.
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 75px;">
                        VALOR(R$)
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 75px;">
                        QUANTIDADE
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 65px;">
                        IPI(R$)
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 65px;">
                        IPI(%)
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 75px;">
                        MARGEM(%)
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 75px;">
                        FRETE(%)
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
    
                    {{-- EMB. --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-center" style="width: 50px;">
                            {{ $invoiceitem->measure }}
                            <br>
                            <span class="text-danger">{{ $invoiceitem->signal }}</span>
                            <br>
                            {{ $invoiceitem->amount }}
                        </div>
                    </td>

                    {{-- INDEX(%) --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 75px;">
                            <input type="text" wire:model="array_item_index.{{ $invoiceitem->id }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 70px;" id="array_item_index_{{ $invoiceitem->id }}" onKeyUp="maskFloat2(this, event)" required>
                        </div>
                    </td>

                    {{-- PRODUTO CSV --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 155px;">
                            <select wire:model="array_item_invoicecsv_id.{{ $invoiceitem->id }}" class="form-select form-control-sm text-uppercase" style="font-size: 8pt;  padding: 0 30px 0 2px; width: 150px;" id="array_item_invoicecsv_id_{{ $invoiceitem->id }}" disabled>
                                <option value="" class="text-muted fw-bold" style="font-size: 7pt;">ESCOLHA...</option>
                                @foreach (App\Models\Invoicecsv::where('invoice_id', $invoice_id)->get() as $invoicecsv))
                                    <option value="{{ $invoicecsv->id }}">{{ $invoicecsv->reference }} &#187; {{ $invoicecsv->name }} &#187; {{ $invoicecsv->ean }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>

                    {{-- GRUPO PRODUTO --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 125px;">
                            <select wire:model="array_item_productgroup_id.{{ $invoiceitem->id }}" class="form-select form-control-sm text-uppercase" style="font-size: 8pt;  padding: 0 30px 0 2px; width: 120px;" id="array_item_productgroup_id_{{ $invoiceitem->id }}" disabled>
                                <option value="" class="text-muted fw-bold" style="font-size: 7pt;">ESCOLHA...</option>
                                @foreach (App\Models\Invoiceefisco::where('invoice_id', $invoice_id)->get() as $invoiceefisco))
                                    <option value="{{ $invoiceefisco->productgroup->id }}">{{ $invoiceefisco->productgroup->code }} &#187; {{ $invoiceefisco->productgroup->origin }}</option>
                                @endforeach
                            </select>
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

                    {{-- VALOR(R$) --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 75px;">
                            <input type="text" wire:model="array_item_value_final.{{ $invoiceitem->id }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 70px;" id="array_item_value_final_{{ $invoiceitem->id }}" onKeyUp="maskFloat3(this, event)" required disabled>
                        </div>
                    </td>

                    {{-- QUANTIDADE --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 75px;">
                            <input type="text" wire:model="array_item_quantity_final.{{ $invoiceitem->id }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 70px;" id="array_item_quantity_final_{{ $invoiceitem->id }}" onKeyUp="maskFloat3(this, event)" required disabled>
                        </div>
                    </td>

                    {{-- IPI(R$) --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 65px;">
                            <input type="text" wire:model="array_item_ipi_final.{{ $invoiceitem->id }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 60px;" id="array_item_ipi_final_{{ $invoiceitem->id }}" onKeyUp="maskFloat3(this, event)" required disabled>
                        </div>
                    </td>

                    {{-- IPI(%) --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 65px;">
                            <input type="text" wire:model="array_item_ipi_aliquot_final.{{ $invoiceitem->id }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 60px;" id="array_item_ipi_aliquot_final_{{ $invoiceitem->id }}" onKeyUp="maskFloat3(this, event)" required disabled>
                        </div>
                    </td>

                    {{-- MARGEM(%) --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 75px;">
                            <input type="text" wire:model="array_item_margin.{{ $invoiceitem->id }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 70px;" id="array_item_margin_{{ $invoiceitem->id }}" onKeyUp="maskFloat2(this, event)" required disabled>
                        </div>
                    </td>

                    {{-- FRETE(%) --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 75px;">
                            <input type="text" wire:model="array_item_shipping.{{ $invoiceitem->id }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 70px;" id="array_item_shipping_{{ $invoiceitem->id }}" onKeyUp="maskFloat2(this, event)" required disabled>
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
