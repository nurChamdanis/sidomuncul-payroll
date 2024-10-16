let payloadCompanyOptions = {};
let payloadGlAccountOptions = {};
let payloadPiecesOptions = {};

$(function () {
    OptionsSelect({ id: 'company_id', url: 'master_tunjangan/options/company', payload: payloadCompanyOptions });
    payloadGlAccountOptions.company_id = $('#company_id').val();
    $('#company_id').change(function () {
        payloadGlAccountOptions.company_id = $(this).val();
        $('#gl_id').select2('val', '');
    });

    /** Company Options */
    OptionsSelect({ id: 'PcsId', url: 'pkh_product/options/pieces', payload: payloadPiecesOptions });
    payloadPiecesOptions.system_code = $('#PcsId').val();
    $('#PcsId').change(function () {
        payloadPiecesOptions.system_code = $(this).val();
        $('#system_value_txt').select2('val', '');
    });

   formInitialize(form);
});

function handleSubmit() {
    if (!form.valid()) {
        toastr['error']('Please check your submission form');
        return;
    }

    var areaChecked = 0;
    if ($('.areaCheckbox')) {
        areaChecked = $('.areaCheckbox:checked').length;
    }

    if (areaChecked < 1) {
        toastr['error']('Please choose at least one area');
        return;
    }

    var areaGroupChecked = 0;
    if ($('.areagrupCheckbox')) {
        areaGroupChecked = $('.areagrupCheckbox:checked').length;
    }

    if (areaGroupChecked < 1) {
        toastr['error']('Please choose at least one area group');
        return;
    }

    var payrollRulesChecked = 0;
    if ($('.payrollrulesCheckbox')) {
        payrollRulesChecked = $('.payrollrulesCheckbox:checked').length;
    }

 
    const submitter = new FormSubmitter('pkh_product/store', serializeArray(form));

    submitter
        .success(function (data) {
            if (data.status) {
                redirectTo(data.message, 'success', data.redirect_link);
            }
        })
        .error(function (error) {
            toastr['error'](error);
            return;
        });
}