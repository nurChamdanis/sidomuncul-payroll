var filterCompany = $('#company_id');
let payloadCompanyOptions = {};

var filterArea = $('#work_unit_id');
let payloadAreaOptions = {};

var filterRole = $('#role_id');
let payloadRoleOptions = {};

$(function(){
    /** Company Options */
    OptionsSelect({id: 'company_id', url: 'generate_payroll/options/company', payload: payloadCompanyOptions});
    payloadAreaOptions.company_id = filterCompany.val();
    payloadRoleOptions.company_id = filterCompany.val();
    $('#company_id').change(function(){
        payloadAreaOptions.company_id = filterCompany.val();
        payloadRoleOptions.company_id = filterCompany.val();
        if($('#work_unit_id').val() != '') $('#work_unit_id').select2('val','');
        if($('#role_id').val() != '') $('#role_id').select2('val','');
    });

    /** Area Options */
    OptionsSelect({id: 'work_unit_id', url: 'generate_payroll/options/area', payload: payloadAreaOptions});

    /** Role Options */
    OptionsSelect({id: 'role_id', url: 'generate_payroll/options/role', payload: payloadRoleOptions});

    formInitialize(form);
});


function handleSubmit()
{
    if(!form.valid()) {
        toastr['error']('Please check your submission form');
        return;
    }
        
    const submitter = new FormSubmitter('generate_payroll/store', serializeArray(form));

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