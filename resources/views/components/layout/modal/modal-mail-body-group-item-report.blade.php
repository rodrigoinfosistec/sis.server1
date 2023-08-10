@foreach(App\Models\Report::where('folder', $folder)->orderBy('id', 'DESC')->limit(20)->get() as $key =>$report)
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
