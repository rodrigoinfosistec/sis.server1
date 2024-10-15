<x-layout.modal.modal-detail modal="detail" size="modal-fullscreen">
    <x-layout.modal.modal-detail-header icon="bi-eye" modal="detail">
        Movimentação de Produto

        <x-slot:identifier>
            <span class="text-primary fw-bold" style="font-size: 10pt;">{{ $name }}</span>
            <br>
            {{ $reference }}
            <i class="bi-caret-right-fill text-muted"></i>
            <span class="text-muted">{{ $ean }}</span>
            @foreach(App\Models\Producedeposit::where(['produce_id' => $item->id])->get() as $key => $producedeposit)
                <br>
                <span class="text-danger fw-bold">{{ $loop->iteration }}.</span><span class="text-dark">{{ $producedeposit->deposit->name }}</span>
                <i class="bi-caret-right-fill text-muted"></i>
                <span class="text-dark fw-bold" style="font-size: 9pt;">
                    {{ App\Models\Generate::decodeFloat2($producedeposit->quantity) }}
                </span>
            @endforeach
        </x-slot>
    </x-layout.modal.modal-detail-header>

    <div class="modal-body">
        @if(App\Models\Producemoviment::where('produce_id', $produce_id)->exists())
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
                        @foreach(App\Models\Producemoviment::where('produce_id', $produce_id)->orderBy('created_at', 'DESC')->get() as $key => $producemoviment)
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
        {{ $producemoviment->identification }}
    </div>
</td>

{{-- QUANTIDADE --}}
<td class="align-middle" style="line-height: 1; padding: 0;">
    <div class="fw-bold" style="width: 90px; font-size: 10pt;">
        {{ App\Models\General::decodeFloat2($producemoviment->quantity) }}
    </div>
</td>

{{-- CADASTRO --}}
<td class="align-middle" style="line-height: 1; padding: 0;">
    <div class="fw-bold" style="width: 100px;">
        <span class="text-muted" style="font-size: 7pt;">{{ App\Models\User::find($producemoviment->user_id)->name }}</span>
        <br>
        {{ date_format($producemoviment->created_at, 'd/m/Y') }}
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
