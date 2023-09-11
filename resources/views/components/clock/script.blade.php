<script>
    window.addEventListener('close-modal', event => {
        $('#generateModal').modal('hide');
        $('#mailModal').modal('hide');

        $('#addTxtModal').modal('hide');
        $('#addModal').modal('hide');

        $('#addEmployeeModal').modal('hide');
        $('#addHolidayModal').modal('hide');
        $('#detailModal').modal('hide');
        $('#eraseModal').modal('hide');

        $('#editNoteEmployeeModal').modal('hide');
        $('#eraseEmployeeModal').modal('hide');
    })
</script>
