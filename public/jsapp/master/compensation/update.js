let payloadCompanyOptions = {};
let payloadAreaOptions = {};
let payloadRoleOptions = {};
let payloadEmployeeOptions = {};
let payloadCompensationOptions = {};

$(function () {
  OptionsSelect({
    id: "company-options",
    url: "master_kompensasi/options_company",
    payload: payloadCompanyOptions,
  });
  OptionsSelect({
    id: "area-options",
    url: "master_kompensasi/options_area",
    payload: payloadAreaOptions,
  });
  OptionsSelect({
    id: "unit-options",
    url: "master_kompensasi/options_role",
    payload: payloadRoleOptions,
  });
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

  $("#company-options").prop("disabled", true);
  $("#area-options").prop("disabled", true);
  $("#employee-options").prop("disabled", true);

});

function handleSubmit() {
  if (!form.valid()) {
    toastr["error"]("Please check your submission form");
    return;
  }

  const submitter = new FormSubmitter(
    "master_kompensasi/update",
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
