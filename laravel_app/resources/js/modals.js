/* "modify", "delete" button */
$('#dashboard-modify').click(click_modify_btn);
$('#dashboard-delete').click(click_delete_btn);

/* Event which the "modify" button is clicked */
function click_modify_btn() {
    this.disabled = true;

    try {
        var data_ids = [];
        $('#checkbox-dashboard:checked').each(function() {
            data_ids.push($(this).val());
        });

        if (data_ids.length != 1) {
            // popup error dialog
        }
        $('#form-dashboard-modify input[name="data_id"]').val(data_ids[0]);
        $('#form-dashboard-modify').submit();
    } finally {
        this.disabled = false;
    }
}

/* Event which the "delete" button is clicked */
function click_delete_btn() {
    this.disabled = true;

    try {
        var data_ids = [];
        $('#checkbox-dashboard:checked').each(function() {
            data_ids.push($(this).val());
        });

        if (data_ids.length != 1) {
            // popup error dialog
        }
        $('#form-dashboard-delete input[name="data_id"]').val(data_ids[0]);
        $('#form-dashboard-delete').submit();
    } finally {
        this.disabled = false;
    }
}