<x-layout.modal.modal-add modal="addFunded" method="registerFunded" size="modal-fullscreen">
    <x-layout.modal.modal-add-header icon="bi-database-fill-check" modal="addFunded">
        Banco de Horas

        <x-slot:identifier>
            <span class="text-primary fw-bold">{{ $company_name }}
            <br>
            {{ $start_decode }}<i class="bi-caret-right-fill text-muted"></i>{{ $end_decode }}</span>
            <br><br>
            <span class="text-danger fw-bold" style="font-size: 13pt">
                Consolidar em Banco de Horas?
            </span>
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerFunded">

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
                    FUNCIONÁRIO
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 80px;">
                    ABONO
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 80px;">
                    ATRASO
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 80px;">
                    EXTRA
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 80px;">
                    SALDO
                </div>
            </th>
        </thead>

        </tbody>
            @foreach(App\Models\Clockemployee::where('clock_id', $clock_id)->get() as $key => $clockemployee)

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

    {{-- FUNCIONÁRIO --}}
    <td class="align-middle" style="line-height: 1; padding: 0;">
        <div class="fw-bold" style="min-width: 200px; font-size: 9pt;">
            {{ $clockemployee->employee_name }}
        </div>
    </td>

    {{-- ABONO --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="fw-bold" style="width: 80px; font-size: 9pt">
            {{ $clockemployee->allowance_total }}
        </div>
    </td>

    {{-- ATRASO --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="fw-bold" style="width: 80px; font-size: 9pt">
            {{ $clockemployee->delay_total }}
        </div>
    </td>

    {{-- EXTRA --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="fw-bold" style="width: 80px; font-size: 9pt">
            {{ $clockemployee->extra_total }}
        </div>
    </td>

    {{-- SALDO --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="fw-bold" style="width: 80px; font-size: 9pt">
            <span class="text-primary">{{ $clockemployee->balance_total }}</span>
        </div>
    </td>
</tr>
{{-- dia --}}
            @endforeach
        </tbody>
    </table>
</div>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
