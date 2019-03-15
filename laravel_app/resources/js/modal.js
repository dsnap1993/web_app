// modal of editing on dashboard
$(document).ready(function() {

    $(document).on('click', "#edit-button", function() {
        $(this).addClass('edit-button-trigger-clicked');
    
        var options = {
          'backdrop': 'static'
        };
        $('#modal-edit').modal(options)
    })

    $('#modal-edit').on('show.bs.modal', function () {
        var button = $(".edit-button-trigger-clicked");
        var row = button.closest(".data-row");

        var dataId = button.data('id');
        var dataName = row.children(".data-name").text();
        var dataSummary = row.children(".data-summary").text();
        var fileName = row.children(".file-name").text();

        $("#modal-data-name").val(dataName);
        $("#modal-data-summary").val(dataSummary);
        $("#modal-file-name").val(fileName);
        $("#edit-data-id").val(dataId);
    });

    $('#modal-edit').on('hide.bs.modal', function() {
        $('.edit-button-trigger-clicked').removeClass('edit-button-trigger-clicked')
        $("#edit-form").trigger("reset");
    })

    $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $("delete-button-trigger-clicked");
        var dataId = button.data('id');

    });
});

// modal of deleting on dashboard
$(document).ready(function() {

    $(document).on('click', "#delete-button", function() {
        $(this).addClass('delete-button-trigger-clicked');
    
        var options = {
          'backdrop': 'static'
        };
        $('#modal-delete').modal(options)
    })

    $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(".delete-button-trigger-clicked");
        var dataId = button.data('id');

        $("#delete-data-id").val(dataId);
    });

    $('#modal-delete').on('hide.bs.modal', function() {
        $('.edit-button-trigger-clicked').removeClass('delete-button-trigger-clicked')
        $("#delete-form").trigger("reset");
    })
});