<script>
    window.addEventListener('close-modal', event => {
        $('#generateModal').modal('hide');
        $('#mailModal').modal('hide');

        $('#addXmlModal').modal('hide');
        $('#addProductModal').modal('hide');
        $('#editItemRelatesModal').modal('hide');
        $('#editItemAmountModal').modal('hide');

        $('#eraseModal').modal('hide');
    })
</script>
