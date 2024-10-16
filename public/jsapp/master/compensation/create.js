let payloadCompanyOptions = {};
let payloadAreaOptions = {};
let payloadEmployeeOptions = {};
let payloadCompensationOptions = {};

$(function () {
    $(".dt_picker_compensation").datepicker({
        autoclose: true,
        todayHighlight: false,
        format: "yyyy-mm",
        startView: "year",
        minViewMode: "months",
    });

    OptionsSelect({id: 'company-options', url: 'master_kompensasi/options_company', payload: payloadCompanyOptions});
    payloadAreaOptions.company_id = $(this).val();
    payloadEmployeeOptions.company_id = $(this).val();
    $('#company-options').change(function(){
        payloadAreaOptions.company_id = $(this).val();
        payloadEmployeeOptions.company_id = $(this).val();
        $('#area-options').select2('val','');
        $('#employee-options').select2('val','');
    });

    OptionsSelect({id: 'area-options', url: 'master_kompensasi/options_area', payload: payloadAreaOptions});
    payloadEmployeeOptions .work_unit_id = $(this).val();
    $('#area-options').change(function(){
      payloadEmployeeOptions .work_unit_id = $(this).val();
        $('#employee-options').select2('val','');
    });
  

    // OptionsSelect({
    //     id: "area-options",
    //     url: "master_kompensasi/options_area",
    //     payload: payloadAreaOptions,
    // });

    OptionsSelect({
        id: "employee-options",
        url: "master_kompensasi/options_employee",
        payload: payloadEmployeeOptions,
    });

    OptionsSelect({
        id: "compensationType-options",
        url: "master_kompensasi/options_compensationType",
        payload: payloadCompensationOptions,
    });

    formInitialize(form);
});

function handleSubmit() {
    if (!form.valid()) {
        toastr["error"]("Please check your submission form");
        return;
    }

    const submitter = new FormSubmitter(
        "master_kompensasi/store",
        serializeArray(form)
    );

    submitter
        .success(function (data) {
            if (data.status) {
                redirectTo(data.message, "success", data.redirect_link);
            }
        })
        .error(function (error) {
            toastr["error"](error);
            return;
        });
}
