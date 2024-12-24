let payloadSystemOptions = {};

$(function(){
    OptionsSelect({id: 'system_type', url: 'master_system/options', payload: payloadSystemOptions});
    formInitialize(form);
});

function handleSubmit()
{
    if(!form.valid()) {
        toastr['error']('Please check your submission form');
        return;
    }
        
    const submitter = new FormSubmitter('master_system/store', serializeArray(form));

    submitter
        .success(function(data) {
            if(data.status){
                redirectTo(data.message,'success', data.redirect_link);
            }
        })
        .error(function(error) {
            toastr['error'](error);
            return;
        });
}