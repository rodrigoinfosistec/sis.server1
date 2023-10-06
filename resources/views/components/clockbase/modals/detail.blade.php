<x-layout.modal.modal-detail modal="detail" size="modal-fullscreen">
    <x-layout.modal.modal-detail-header icon="bi-eye" modal="detail">
        Banco de Horas do Funcionário

        <x-slot:identifier>
            <span class="text-primary">{{ $name }}</span>
            <br>
            {{ $pis }}
            <br><br>
            <span class="fw-bold" style="font-size: 12pt">
                @if($datatime > 0) <span class="text-primary">
                @elseif($datatime < 0) <span class="text-danger">
                @else <span class="text-dark"> @endif
                    {{ App\Models\Clock::minutsToTimeSignal((int)$datatime) }}
                </span>
            </span>
        </x-slot>
    </x-layout.modal.modal-detail-header>

    <div class="modal-body">
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
                        <div class="" style="width: 10px;">
                            {{--  --}}
                        </div>
                    </th>
        
                    <th class="" style="padding: 0;">
                        <div class="" style="margin-right: 5px; min-width: 100px;">
                            DESCRIÇÃO
                        </div>
                    </th>
        
                    <th class="" style="padding: 0;">
                        <div class="" style="width: 70px;">
                            PERÍODO
                        </div>
                    </th>
        
                    <th class="" style="padding: 0;">
                        <div class="" style="width: 60px;">
                            HORAS
                        </div>
                    </th>
        
                    <th class="" style="padding: 0;">
                        <div class="" style="width: 80px;">
                            CADASTRO
                        </div>
                    </th>
                </thead>

                <tbody>
                    @foreach(App\Models\Clockbase::where('employee_id', $employee_id)->orderBy('created_at', 'DESC')->get() as $key => $clockbase)
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

{{-- ... --}}
<td class="align-middle" style="line-height: 1; padding: 0;">
    <div class="" style="width: 10px; font-size: 12pt;">
        {{-- ... --}}
    </div>
</td>

{{-- DESCRIÇÃO --}}
<td class="align-middle" style="line-height: 1; padding: 0;">
    <div class="fw-bold" style="margin-right: 5px; min-width: 100px; font-size: 9pt;">
        {{ $clockbase->description }}
    </div>
</td>

{{-- PERÍODO --}}
<td class="align-middle" style="line-height: 1; padding: 0;">
    @if($clockbase->description == 'SALDO INICIAL')
        &nbsp;&nbsp;&nbsp;&nbsp;
        <i class="bi-clock-fill text-dark" style="font-size: 12pt;"></i>
    @elseif($clockbase->description == 'Folga')
        <div class="text-muted" style="width: 70px; font-size: 8pt;">
            {{ date_format(date_create($clockbase->start), 'd/m/Y') }}
        </div>
    @else
        <div class="text-muted" style="width: 70px; font-size: 8pt;">
            {{ date_format(date_create($clockbase->start), 'd/m/Y') }}
            <br>
            {{ date_format(date_create($clockbase->end), 'd/m/Y') }}
        </div>
    @endif
</td>

{{-- HORAS --}}
<td class="align-middle" style="line-height: 1; padding: 0;">
    <div class="fw-bold" style="width: 60px; font-size: 10pt;">
        @if($clockbase->time > 0) <span class="text-primary">
        @elseif($clockbase->time < 0) <span class="text-danger">
        @else <span class="text-muted"> @endif
            {{ App\Models\Clock::minutsToTimeSignal((int)$clockbase->time) }}
        </span>
    </div>
</td>

{{-- CADASTRO --}}
<td class="align-middle" style="line-height: 1; padding: 0;">
    <div class="text-muted" style="width: 150px; font-size: 7.5pt;">
        {{ $clockbase->user->name }}
        <br>
        {{ $clockbase->created_at->format('d/m/y') }} 
    </div>
</td>
{{-- conteúdo --}}

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout.modal.modal-detail>
