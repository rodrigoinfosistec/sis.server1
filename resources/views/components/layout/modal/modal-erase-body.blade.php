<div class="modal-body">
    <h6 class="text-danger opacity-75">
        {{ $question }}
    </h6>

    <table class="table table-bordered">
        <thead>
            <tr class="text-muted" style="font-size: 9pt;">
                {{ $thead }}
            </tr>
        </thead>

        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
