<a type="button" wire:click="detailAlert({{ $id }})" class="btn btn-link btn-sm position-relative" style="padding: 0px 5px 0px 5px;" data-bs-toggle="modal" data-bs-target="#detailAlertModal" title="Alertas">
    <i class="bi-exclamation-circle text-secondary" style="font-size: 20px;"></i>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        {{ App\Models\Invoice::alerts($id)['amount'] }}
    </span>
</a>
