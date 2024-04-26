<script>
    window.addEventListener('close-modal', event => {
        $('#generateModal').modal('hide');
        $('#mailModal').modal('hide');

        $('#addTxtModal').modal('hide');
        $('#addXmlModal').modal('hide');
        $('#addModal').modal('hide');

        $('#detailModal').modal('hide');
        $('#editModal').modal('hide');
        $('#editLimitModal').modal('hide');
        $('#eraseModal').modal('hide');
    })
</script>
