<x-layout.modal.modal-edit modal="editClockEmployee" size="modal-fullscreen">
    <x-layout.modal.modal-edit-header icon="bi-fingerprint" modal="editClockEmployee">
       Horas do Funcionário

        <x-slot:identifier>
            {{ $clockemployee_employee_pis }}
            <br>
            <span class="text-primary">{{ $clockemployee_employee_name }}</span>
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeClockEmployee">

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
        </thead>

        </tbody>
            @php $date = $this->clock_start; @endphp
            @while($date <= $this->clock_end)
                <tr>
                    {{-- CHECKBOX --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 22px;">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" style="width: 15px; height: 15px;" onchange="closest('tr').classList.toggle('row_selected')">
                            </div>
                        </div>
                    </td>
                </tr>

                @php $date = date('Y-m-d', strtotime('+1 days', strtotime($date))); @endphp
            @endwhile
        </tbody>
    </table>
</div>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
