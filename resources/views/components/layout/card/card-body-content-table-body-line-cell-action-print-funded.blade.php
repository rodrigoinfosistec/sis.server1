<div class="dropdown float-end" style="width: 48px; padding-top: 5px; padding-left: 5px;">
    <a type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi-printer-fill text-primary" style="font-size: 22px;" title="Imprimir relatório"></i>
    </a>
    <ul class="dropdown-menu">
        @foreach(App\Models\Report::where(['folder' => 'clockfunded', 'reference_1' => $id])->orderBy('id', 'DESC')->limit(20)->get() as $key => $report)
    <li>
        <a class="dropdown-item" href="{{ asset('storage/pdf/clockfunded/' . $report->file) }}" target="_blank">
            <span class="text-uppercase text-muted fw-bold" style="font-size: 8pt;">
                <i class="bi-file-pdf text-danger" style="font-size: 15px;"></i>

                <span class="fst-italic">
                    {{ str_pad($report->id , Str::length(App\Models\Report::where(['folder' => 'clockfunded', 'reference_1' => $id])->orderBy('id', 'DESC')->limit(20)->get()->count()), '0', STR_PAD_LEFT); }}
                </span>

                {{ $report->user->name }}

                {{ date_format($report->created_at, "d/m/Y H:i:s") }}
            </span>
        </a>
    </li>
@endforeach
    </ul>
</div>
