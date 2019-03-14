require('./bootstrap');
require('./app');

$('#modal-edit').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var data = button.data('whatever')
    /**
     * data[0] => data_id, data_id[1] => user_id, data[2] => data_name
     * data[3] => data_summary, data[4] => created_at, data[5] => file_name
     */

    var modal = $(this)
    modal.find('#editModalLavel').text('Editing ' + data[2])
    modal.find('.modal-body input#data-name').val(data[2])
    modal.find('.modal-body input#data-summary').val(data[3])
    modal.find('.modal-body input#file-name').val(data[5])
})

$('#modal-delete').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var dataId = button.data('whatever')

    var modal = $(this)
    modal.find('#deleteModalLavel').text('Confirmation ' + dataId)
})