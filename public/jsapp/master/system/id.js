$(function(){
    formInitialize(form);
});

function showDeleteModal(){
    $('#deleteModal').modal('show');
}

function handleDelete()
{
    if(!form.valid()) {
        toastr['error']('Please check your submission form');
        return;
    }
        
    const submitter = new FormSubmitter('master_system/remove', serializeArray(form));

    submitter
        .success(function(data) {
            if(data.status){
                $('#deleteModal').modal('hide');
                redirectTo(data.message,'success', data.redirect_link);
            }
        })
        .error(function(error) {
            toastr['error'](error);
            return;
        });
}