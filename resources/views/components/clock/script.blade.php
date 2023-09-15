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
        $('#addFundedModal').modal('hide');

        $('#editClockEmployeeModal').modal('hide');

        $('#addVacationEmployeeModal').modal('hide');
        $('#addAttestEmployeeModal').modal('hide');
        $('#addAbsenceEmployeeModal').modal('hide');
        $('#addAllowanceEmployeeModal').modal('hide');
        $('#addEasyEmployeeModal').modal('hide');
        $('#editNoteEmployeeModal').modal('hide');
        $('#eraseEmployeeModal').modal('hide');
        $('#mailEmployeeModal').modal('hide');
    })
</script>
