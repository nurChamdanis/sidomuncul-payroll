let payloadCompanyOptions = {};
let payloadGlAccountOptions = {};

$(function(){
    OptionsSelect({id: 'company_id', url: 'master_tunjangan/options/company', payload: payloadCompanyOptions});

    payloadGlAccountOptions.company_id = $('#company_id').val();
    $('#company_id').change(function(){
        payloadGlAccountOptions.company_id = $(this).val();
        $('#gl_id').select2('val','');
    });
    
    OptionsSelect({id: 'gl_id', url: 'master_tunjangan/options/glaccount', payload: payloadGlAccountOptions});
    formInitialize(form);
});

function handleSubmit()
{
    if(!form.valid()) {
        toastr['error']('Please check your submission form');
        return;
    }
    
    var areaChecked = 0;
    if($('.areaCheckbox')){
        areaChecked = $('.areaCheckbox:checked').length;
    }

    if(areaChecked < 1){
        toastr['error']('Please choose at least one area');
        return;
    }
    
    var areaGroupChecked = 0;
    if($('.areagrupCheckbox')){
        areaGroupChecked = $('.areagrupCheckbox:checked').length;
    }

    if(areaGroupChecked < 1){
        toastr['error']('Please choose at least one area group');
        return;
    }
    
    var payrollRulesChecked = 0;
    if($('.payrollrulesCheckbox')){
        payrollRulesChecked = $('.payrollrulesCheckbox:checked').length;
    }

    if(payrollRulesChecked < 1){
        toastr['error']('Please choose at least one payroll rule');
        return;
    }
        
    const submitter = new FormSubmitter('master_tunjangan/store', serializeArray(form));

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