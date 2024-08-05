<x-layout.modal.modal-detail modal="detail" size="modal-fullscreen">
    <x-layout.modal.modal-detail-header icon="bi-eye" modal="detail">
        Movimentação de Produto

        <x-slot:identifier>
            <span class="text-primary fw-bold" style="font-size: 10pt;">{{ $name }}</span>
            <br>
            <span class="text-dark">ESTOQUE</span>
            <i class="bi-caret-right-fill text-muted"></i>
            <span class="text-primary fw-bold" style="font-size: 9pt;">{{ $quantity }}</span>
            <br>
            {{ $code }}
            <i class="bi-caret-right-fill text-muted"></i>
            {{ $reference }}
            <i class="bi-caret-right-fill text-muted"></i>
            <span class="text-muted">{{ $ean }}</span>
            @foreach(App\Models\Deposit::where('company_id', auth()->user()->company_id)->get() as $key => $deposit)
                @if($deposit->id != App\Models\Company::find(auth()->user()->company_id)->depositdefault_id)
                    <br>
                    <span class="text-danger fw-bold">{{ $deposit->id }}.</span><span class="text-dark">{{ $deposit->name }}</span>
                    <i class="bi-caret-right-fill text-muted"></i>
                    <span class="text-dark fw-bold" style="font-size: 9pt;">
                        @if(App\Models\Productdeposit::where(['product_id' => $product_id, 'deposit_id' => $deposit->id])->exists())
                            {{ App\Models\General::decodeFloat2(App\Models\Productdeposit::where(['product_id' => $product_id, 'deposit_id' => $deposit->id])->first()->quantity) }}
                        @else
                            0,00
                        @endif
                    </span>
                @endif
            @endforeach
            <br>
            <span class="text-danger" style="font-size: 7pt;">
                * OBS: DEPÓSITO DA LOJA =
                <span class="fw-bold">{{ App\Models\Company::find(auth()->user()->company_id)->depositdefault_id }}</span>
                (NÃO CONTABILIZA NO TOTAL)
            </span>
        </x-slot>
    </x-layout.modal.modal-detail-header>

    <div class="modal-body">
        @if(App\Models\Productmoviment::where('product_id', $product_id)->exists())
            <div class="table-responsive">
                <table class="table table-sm table-borderless table-hover" wire:loading.class.deley="opacity-50">
                    <thead class="fw-bolder" style="border-bottom: #808080 1px solid; font-size: 7.5pt;">
                        <th class="" style="padding: 0;">
                            <div class="text-center" style="width: 22px;">
                                <div class="form-check" style="margin-bottom: -6px; margin-left: 2px;">
                                    <input type="checkbox" class="form-check-input" style="width: 12px; height: 12px;" disabled>
                                </div>
                            </div>
                        </th>

                        <th class="" style="padding: 0;">
                            <div class="" style="margin-right: 5px; min-width: 240px;">
                                DESCRIÇÃO
                            </div>
                        </th>

                        <th class="" style="padding: 0;">
                            <div class="" style="width: 90px;">
                                QUANTIDADE
                            </div>
                        </th>

                        <th class="" style="padding: 0;">
                            <div class="" style="width: 100px;">
                                CADASTRO
                            </div>
                        </th>
                    </thead>

                    <tbody style="font-size: 8pt;">
                        @foreach(App\Models\Productmoviment::where('product_id', $product_id)->orderBy('created_at', 'DESC')->get() as $key => $productmoviment)
                            <tr>

{{-- conteúdo --}}
{{-- CHECKBOX --}}
<td class="align-middle" style="line-height: 1; padding: 0;">
    <div class="" style="width: 22px;">
        <div class="form-check" style="margin: 3px 0 3px 0;">
            <input type="checkbox" class="form-check-input" style="width: 15px; height: 15px;" onchange="closest('tr').classList.toggle('row_selected')">
        </div>
    </div>
</td>

{{-- DESCRIÇÃO --}}
<td class="align-middle" style="line-height: 1; padding: 0;">
    <div class="fw-bold" style="margin-right: 5px; min-width: 240px; font-size: 10pt;">
        {{ $productmoviment->identification }}
    </div>
</td>

{{-- QUANTIDADE --}}
<td class="align-middle" style="line-height: 1; padding: 0;">
    <div class="fw-bold" style="width: 90px; font-size: 10pt;">
        {{ App\Models\General::decodeFloat2($productmoviment->quantity) }}
    </div>
</td>

{{-- CADASTRO --}}
<td class="align-middle" style="line-height: 1; padding: 0;">
    <div class="fw-bold" style="width: 100px;">
        <span class="text-muted" style="font-size: 7pt;">{{ App\Models\User::find($productmoviment->user_id)->name }}</span>
        <br>
        {{ date_format($productmoviment->created_at, 'd/m/Y') }}
    </div>
</td>
{{-- conteúdo --}}

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted fw-bold" style="">
                <i class="bi bi-archive-fill"></i>

                SEM MOVIMENTAÇÕES
            </div>
        @endif
    </div>
</x-layout.modal.modal-detail>
