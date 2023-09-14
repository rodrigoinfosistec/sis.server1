<div class="dropdown float-end" style="width: 40px; padding-top: 6px; padding-left: 5px;">
    <a type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi-printer text-dark" style="font-size: 20px;" title="Imprimir relatório"></i>
    </a>
    <ul class="dropdown-menu">
        @php
            $reports = App\Models\Report::where(['folder' => 'clockemployee', 'reference_1' => 1, 'reference_2' => 34])->orderBy('id', 'DESC')->limit(20)->get();
        @endphp
        @foreach($reports as $key => $report)
    <li>
        <a class="dropdown-item" href="{{ asset('storage/pdf/clockemployee/' . $report->file) }}" target="_blank">
            <span class="text-uppercase text-dark fw-bold" style="font-size: 8pt;">
                <i class="bi-file-pdf text-danger" style="font-size: 15px;"></i>

                <span class="fst-italic">
                    {{ str_pad($report->id , Str::length($reports->count()), '0', STR_PAD_LEFT); }}
                </span>

                {{ $report->user->name }}

                {{ date_format($report->created_at, "d/m/Y H:i:s") }}
            </span>
        </a>
    </li>
@endforeach
    </ul>
</div>
