@foreach($reports as $key => $report)
    <li>
        <a class="dropdown-item" href="{{ asset('storage/pdf/' . $config['name'] . '/' . $report->file) }}" target="_blank">
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
