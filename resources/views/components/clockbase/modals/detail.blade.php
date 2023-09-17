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

    <x-layout.modal.modal-detail-body>
        @foreach(App\Models\Clockbase::where('employee_id', $employee_id)->get() as $key => $clockbase)
            <x-layout.modal.modal-detail-body-line>

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
    <div class="" style="width: 15px; font-size: 12pt;">
        {{-- ... --}}
    </div>
</td>

{{-- DESCRIÇÃO --}}
<td class="align-middle" style="line-height: 1; padding: 0;">
    <div class="fw-bold" style="min-width: 200px; font-size: 9pt;">
        {{ $clockbase->description }}
    </div>
</td>

{{-- PERÍODO --}}
<td class="align-middle" style="line-height: 1;">
    <div class="fw-bold" style="width: 80px; font-size: 9pt">
        {{ date_format(date_create($clockbase->start), 'd/m/Y') }}
        <i class="bi-caret-right-fill text-muted"></i>
        {{ date_format(date_create($clockbase->end), 'd/m/Y') }}
    </div>
</td>

{{-- BANCO DE HORAS --}}
<td class="align-middle" style="line-height: 1;">
    <div class="fw-bold" style="width: 80px; font-size: 10pt">
        @if($clockbase->time > 0) <span class="text-primary">
        @elseif($clockbase->time < 0) <span class="text-danger">
        @else <span class="text-muted"> @endif
            {{ App\Models\Clock::minutsToTimeSignal((int)$clockbase->time) }}
        </span>
    </div>
</td>

{{-- CADASTRO --}}
<td class="align-middle" style="line-height: 1;">
    <div class="fw-bold text-muted" style="width: 80px; font-size: 8pt">
        {{ $clockbase->user->name }} 
        <i class="bi-caret-right-fill text-muted"></i>
        {{ $clockbase->created_at->format('d/m/Y') }} 
    </div>
</td>
    

{{-- conteúdo --}}
                
            </x-layout.modal.modal-detail-body-line>
        @endforeach
    </x-layout.modal.modal-detail-body>
</x-layout.modal.modal-detail>
