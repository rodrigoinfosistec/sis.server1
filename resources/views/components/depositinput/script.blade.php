<script>
    window.addEventListener('close-modal', event => {
        $('#generateModal').modal('hide');
        $('#mailModal').modal('hide');

        $('#addModal').modal('hide');
        $('#addXmlModal').modal('hide');
        $('#addProductModal').modal('hide');
        $('#editProductAmountModal').modal('hide');
        $('#editItemRelatesModal').modal('hide');
        $('#editItemAmountModal').modal('hide');

        $('#eraseModal').modal('hide');
    })
</script>
