<x-layout.modal.modal-mail size="" modal="mailFunded" method="sendFunded">
    <x-layout.modal.modal-mail-header modal="mailFunded">
        {{ $config['title'] }} Consolidado

        <x-slot:identifier>
            <span class="text-primary fw-bold">{{ $company_name }}
            <br>
            {{ $start_decode }}<i class="bi-caret-right-fill text-muted"></i>{{ $end_decode }}</span>
        </x-slot>
    </x-layout.modal.modal-mail-header>

    <x-layout.modal.modal-mail-body>

{{-- conteúdo --}}
<x-layout.modal.modal-mail-body-group>
    <x-layout.modal.modal-mail-body-group-item columms="12">
        <x-layout.modal.modal-mail-body-group-item-label item="report_id" title="RELATÓRIO ANEXADO" plus="none"/>

        <select wire:model="report_id" class="form-select form-select-sm text-uppercase" id="report_id">
            <x-layout.modal.modal-mail-body-group-item-option-muted/>

            @foreach(App\Models\Report::where(['folder' => 'clockfunded', 'reference_1' => $clock_id])->orderBy('id', 'DESC')->limit(20)->get() as $key =>$report)
                <option value="{{ $report->id }}">
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 8pt;">
                        &#10003;
                        <span class="fst-italic">
                            {{ str_pad($report->id , Str::length($list->count()), '0', STR_PAD_LEFT); }}
                        </span>

                        &#187;

                        {{ $report->user->name }}

                        &#187;

                        {{ date_format($report->created_at, "d/m/Y H:i:s") }}
                    </span>
                </option>
            @endforeach
        </select>

        <x-layout.modal.modal-mail-body-group-item-error item="report_id" message="$message"/>
    </x-layout.modal.modal-mail-body-group-item>
</x-layout.modal.modal-mail-body-group>

<x-layout.modal.modal-mail-body-group>
    <x-layout.modal.modal-mail-body-group-item columms="12">
        <x-layout.modal.modal-mail-body-group-item-label item="mail" title="E-MAIL" plus="none"/>

        <input type="text" wire:model="mail" class="form-control form-control-sm" id="mail">

        <x-layout.modal.modal-mail-body-group-item-error item="mail" message="$message"/>
    </x-layout.modal.modal-mail-body-group-item>
</x-layout.modal.modal-mail-body-group>

<x-layout.modal.modal-mail-body-group>
    <x-layout.modal.modal-mail-body-group-item columms="12">
        <x-layout.modal.modal-mail-body-group-item-label item="comment" title="COMENTÁRIO (opcional)" plus="none"/>

        <textarea wire:model="comment" class="form-control form-control-sm" id="comment" rows="6"></textarea>

        <x-layout.modal.modal-mail-body-group-item-count :comment="$comment"/>

        <x-layout.modal.modal-mail-body-group-item-error item="comment" message="$message"/>
    </x-layout.modal.modal-mail-body-group-item>
</x-layout.modal.modal-mail-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-mail-body>

    <x-layout.modal.modal-mail-footer/>
</x-layout.modal.modal-mail>