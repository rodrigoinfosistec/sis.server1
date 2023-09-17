<x-layout.modal.modal-detail modal="detail" size="">
    <x-layout.modal.modal-detail-header icon="bi-eye" modal="detail">
        Banco de Horas Funcionário

        <x-slot:identifier>
            <span class="text-primary">{{ $name }}</span>
            <br>
            {{ $pis }}
            <br><br>
            <span class="text-danger fw-bold" style="font-size: 13pt">
                {{ App\Models\Clock::minutsToTimeSignal($timebase) }}
            </span>
        </x-slot>
    </x-layout.modal.modal-detail-header>

    <x-layout.modal.modal-detail-body>

{{-- conteúdo --}}
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
                <div class="" style="width: 15px;">
                    {{--  --}}
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="min-width: 200px;">
                    DESCRIÇÃO
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 80px;">
                    PERÍODO
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 80px;">
                    BANCO DE HORAS
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 80px;">
                    CADASTRO
                </div>
            </th>
        </thead>

        </tbody>
            @foreach(App\Models\Clockbase::where('employee_id', $employee_id)->orderBy('id', 'DESC')->get() as $key => $clockbase)

{{-- dia --}}
<tr style="border-bottom: 1px solid #ddd; margin: 5px 0 5px 0;">
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
            {{ date_create(date_format($clockbase->start, 'd/m/Y')) }}
            <i class="bi-caret-right-fill text-muted"></i>
            {{ date_create(date_format($clockbase->start, 'd/m/Y')) }}
        </div>
    </td>

    {{-- BANCO DE HORAS --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="fw-bold" style="width: 80px; font-size: 10pt">
            @if($clockbase->time > 0) <span class="text-primary">
            @elseif($clockbase->time < 0) <span class="text-danger">
            @else <span class="text-muted"> @endif
                {{ App\Models\Clock::minutsToTimeSignal($clockbase->time) }}
            </span>
        </div>
    </td>

    {{-- CADASTRO --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="fw-bold text-muted" style="width: 80px; font-size: 8pt">
            {{ $calockbase->user->name }}
            <br>
            {{ $calockbase->created_at->format('d/m/Y') }}
        </div>
    </td>
</tr>
{{-- dia --}}
            @endforeach
        </tbody>
    </table>
</div>
{{-- conteúdo --}}

    </x-layout.modal.modal-detail-body>
</x-layout.modal.modal-detail>
