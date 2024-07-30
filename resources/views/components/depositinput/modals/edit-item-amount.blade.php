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
                    <div class="text-center" style="width: 60px;">
                        PRODUTO
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="">
                        INT<span class="text-muted">&#187;</span>REFERÊNCIA<span class="text-muted">&#187;</span>BARRAS
                    </div>
                </th>
            </tr>
        </thead>

        <tbody style="font-size: 7pt;">
            @foreach(App\Models\Depositinputitem::where('depositinput_id', $depositinput_id)->get() as $key => $depositinputitem)
                @if(App\Models\Depositinputproduct::where(['depositinput_id' => $depositinput_id, 'identifier' => $depositinputitem->identifier])->doesntExist())
                    <tr>
                        {{-- ITEM --}}
                        <td rowspan="2" class="align-middle" style="line-height: 1; padding: 0;">
                            <div class="text-break text-center" style="width: 40px; font-size: 9pt;">
                                <span class="badge rounded-pill bg-secondary">
                                    {{ str_pad($loop->iteration, Str::length(App\Models\Depositinputitem::where('depositinput_id', $depositinput_id)->get()->count()), '0', STR_PAD_LEFT); }}
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
                            <div class="fw-bolder" style="font-size: 8pt;" title="{{ $depositinputitem->provideritem->name }}">
                                <span class="text-muted">
                                    {{ $depositinputitem->provideritem->code }}
                                </span>
                                <i class="bi-caret-right-fill text-muted"></i>
                                {{ mb_strimwidth($depositinputitem->provideritem->name, 0, 90, "...") }}
                            </div>
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #ddd;">
                        {{-- EAN/NCM/CEST --}}
                        <td class="align-middle" style="line-height: 1; padding: 0;">
                            <div class="" style="width: 90px;">
                                {{ $depositinputitem->provideritem->ean }}
                                <br>
                                {{ $depositinputitem->provideritem->ncm }}
                                <br>
                                {{ $depositinputitem->provideritem->cest }}
                            </div>
                        </td>

                        {{-- PRODUTO --}}
                        <td class="align-middle" style="line-height: 1; padding: 0;">
                            <div class="" style="width: 50px;">
                                <input wire:model="array_product_id.{{ $depositinputitem->id }}" type="text" class="form-control form-control-sm" list="array_products_{{ $depositinputitem->id }}" id="array_product_id_{{ $depositinputitem->id }}">
                                @php
                                    $arr_prod = [];
                                    foreach(App\Models\Depositinputproduct::where('depositinput_id', $depositinput_id)->get() as $key => $produ):
                                        $arr_produ[] = $produ->product_id;
                                    endforeach;

                                    $ar_pro = [];
                                    foreach(App\Models\Provideritem::where(['provider_id' => $provider_id, 'product_id' => $produ->product_id])->get() as $key => $pro):
                                        if(!empty($pro->product_id)):
                                            $ar_pro[] = $pro->product_id;
                                        endif;
                                    endforeach;
                                @endphp
                                <datalist id="array_products_{{ $depositinputitem->id }}">
                                    @foreach(App\Models\Product::where(['company_id' => auth()->user()->company_id, 'status' => true])->orderBy('name', 'ASC')->get() as $key => $product)
                                        @if(!in_array($product->id, $array_product_id))
                                            @if(!in_array($product->id, $arr_produ))
                                                @if(!in_array($product->id, $ar_pro))
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->code }}
                                                        &#187;
                                                        {{ $product->name }}
                                                        &#187;
                                                        {{ $product->ean }}
                                                        &#187;
                                                        {{ $product->reference }}
                                                        <br>
                                                    </option>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                </datalist>
                            </div>
                        </td>

                        <td class="align-middle" style="line-height: 1; padding: 0;">
                            <div class="text-primary" style="font-size: 8pt;">
                                <span class="text-danger">
                                    @if(!empty($array_product_id[$depositinputitem->id]))
                                        {{ @App\Models\Product::find($array_product_id[$depositinputitem->id])->code }}
                                        <span class="text-muted">&#187;</span>
                                        {{ @App\Models\Product::find($array_product_id[$depositinputitem->id])->reference }}
                                        <span class="text-muted">&#187;</span>
                                        {{ @App\Models\Product::find($array_product_id[$depositinputitem->id])->ean }}
                                    @endif
                                </span>
                                <br>
                                {{ @App\Models\Product::find($array_product_id[$depositinputitem->id])->name }}
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
