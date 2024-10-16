let payloadCompanyOptions = {};
const company = $("#company-options");
const processId = $("#process_id");
const btnUploadTemplate = $(`#btn_action_upload`);
const fileTemplate = $("#file_upload");
var dataTableTemporaryLoader;
var invalid = 0;

var tableFilters = () => {
  return {
    process_id: processId.val(),
    cpms_token: $("#cpms_token").val(),
  };
};

$(function () {
  OptionsSelect({
    id: "company-options",
    url: "master_kompensasi/options_company",
    payload: payloadCompanyOptions,
  });

  const additionalOptions = {
    scrollX: true,
    scrollCollapse: true,
    createdRow: function (row, data, dataIndex) {
        const dataRow = data[11].split("~");
        const validFlg = dataRow[0];
        const updateFlg = dataRow[1];

        if(validFlg == "0"){
            $(row).addClass('notvalid');
        } else {
            if(updateFlg == "1"){
                $(row).addClass('update');
            }
        }
    }
  };

  // show datatable
  dataTableTemporaryLoader = new DataTableLoader(
    "tableImportExcel",
    "master_kompensasi/upload_template/show",
    { ...tableFilters() },
    additionalOptions
  );

  dataTableTemporaryLoader.setColumnDefs([
    {
      targets: 0,
      width: "80px",
      render: (data, type, row) => {
        var onclick =
          data == "0" ? `onclick="showModalInvalid('${row[10]}')"` : "";
        if (data == "0") {
          invalid++;
        }
        return `<a class="author btn btn-sm ${
          data == "1" ? "btn-success" : "btn-danger"
        }" ${onclick}>
                      ${data == "1" ? "Valid" : "Invalid"}
                  </a>`;
      },
      name: "valid_flg",
    },
    {
      targets: 1,
      width: "80px",
      render: (data, type, row) => {
        return `<a class="author btn btn-sm ${
          data == "1" ? "btn-success" : "btn-default"
        }">
                      ${data == "1" ? "Yes" : "No"}
                  </a>`;
      },
      name: "update_flg",
      visible: false,
    },
    {
      targets: 2,
      render: (data) => `<div">${data}</div>`,
      name: "company_name",
    },
    {
      targets: 3,
      render: (data) => `<div class="text-center">${data}</div>`,
      name: "name",
    },
    { targets: 4, render: (data) => `<div>${data}</div>`, name: "role_name" },
    {
      targets: 5,
      render: (data) => `<div class="text-center">${data}</div>`,
      name: "no_reg",
    },
    {
      targets: 6,
      render: (data) => `<div>${data}</div>`,
      name: "employee_name",
    },
    {
      targets: 7,
      render: (data) => `<div class="text-center">${data}</div>`,
      name: "period",
    },
    {
      targets: 8,
      render: (data) => `<div class="text-center">${data}</div>`,
      name: "system_value_txt",
    },
    {
      targets: 9,
      render: (data) => `<div class="text-right">${data}</div>`,
      name: "total_compensation",
    },
    { targets: 10, name: "error_message", visible: false },
    { targets: 11, name: "rowstatus", visible: false },
  ]);
  dataTable = dataTableTemporaryLoader.load();
});

const handleResetTableUpload = () => {
  dataTableTemporaryLoader.update({ ...tableFilters() });
};

async function uploadExcelTemplate() {
  var btnHtml = btnUploadTemplate.html();
  const loadingText = `${lang.Shared.please_wait} <i class="fa fa-spinner fa-pulse fa-fw"></i>`;

  if (!company.val()) {
    toastr["error"]("Please choose a company.");
    return false;
  }

  if (fileTemplate.prop("files") && fileTemplate.prop("files").length > 0) {
    $(`#wrapperImportExcel`).hide();
    btnUploadTemplate.attr("disabled", "disabled");
    btnUploadTemplate.html(loadingText);
    try {
      var data = new FormData();
      data.append("company_id", company.val());
      data.append("process_id", processId.val());
      data.append("file_template", fileTemplate.prop("files")[0]);
      data.append("cpms_token", $("#cpms_token").val());

      const config = {
        method: "POST",
        headers: {
          Accept: "application/json",
        },
        body: data,
      };

      const response = await fetch(
        SITE_URL + "master_kompensasi/upload_template/read",
        config
      );
      const json = await response.json();

      $(`#wrapperImportExcel`).show();

      handleResetTableUpload();
      toggleInvalid();

      // btnUploadTemplate.prop('disabled', true);
      btnUploadTemplate.removeAttr('disabled');
      btnUploadTemplate.html(btnHtml);
    } catch (error) {
      console.log(error);
    }
  } else {
    toastr["error"]("Please choose a file.");
    return false;
  }
}

function handleUpload() {
  if (!company.val()) {
    toastr["error"]("Please choose a company.");
    return false;
  }
}

function showModalInvalid(html) {
  $("#invalidModal").modal("show");
  $("#invalidModalBody").html(html);
}

company.on("change", function () {
  if ($(this).val()) {
    if ($(this).val() != "-") {
      $(`#file_template`).prop("disabled", false);
      return;
    }
  }
  $(`#file_template`).prop("disabled", true);
});

async function toggleInvalid() {
  var data = new FormData();
  data.append("process_id", processId.val());
  data.append("cpms_token", $("#cpms_token").val());

  const config = {
    method: "POST",
    headers: {
      Accept: "application/json",
    },
    body: data,
  };

  const response = await fetch(
    SITE_URL + "master_kompensasi/upload_template/get-invalid",
    config
  );
  const json = await response.json();

  if (json.totalInvalid > 0) {
    $(`#btn_submit_excel`).attr("disabled", "disabled");
  } else {
    $(`#btn_submit_excel`).removeAttr("disabled");
  }
}

function showModalImport() {
  if (!company.val()) {
    toastr["error"]("Please choose a company.");
    return false;
  }

  $("#uploadModal").modal("show");
}

async function handleSubmitImport() {
  if (!company.val()) {
    toastr["error"]("Please choose a company.");
    return false;
  }
  const btnSubmitExcel = $(`#btn_modal_import`);
  const btnHtml = btnSubmitExcel.html();
  const loadingText = `${lang.Shared.please_wait} <i class="fa fa-spinner fa-pulse fa-fw"></i>`;

  var data = new FormData();
  data.append("process_id", processId.val());
  data.append("company_id", company.val());
  data.append("cpms_token", $("#cpms_token").val());

  btnSubmitExcel.attr("disabled", "disabled");
  btnSubmitExcel.html(loadingText);

  const config = {
    method: "POST",
    headers: {
      Accept: "application/json",
    },
    body: data,
  };

  const response = await fetch(
    SITE_URL + "master_kompensasi/upload_template/submit",
    config
  );
  const json = await response.json();
  console.log(json);

  if (json.status) {
    $("#uploadModal").modal("hide");
    toastr["success"]("File uploaded successfully");
    redirectTo(json.message, "success", json.redirect_link);
  }

  btnSubmitExcel.removeAttr("disabled");
  btnSubmitExcel.html(btnHtml);

}

function downloadExcelTemplate()
{
    if(!company.val())
    {
        toastr['error']('Please choose a company.');
        return false;
    }
    const btnDownloadExcel = $(`#btn_download_template`);
    const btnHtml = btnDownloadExcel.html();

    $.fileDownload(`${SITE_URL}master_kompensasi/download_template/excel`, {
        httpMethod: "POST",
        data:  {
            company_id: company.val(),
            cpms_token: $('#cpms_token').val(),
        },
        successCallback: function (url) {
            toastr['success']('File successfully download');
            btnDownloadExcel.removeAttr('disabled');
            btnDownloadExcel.html(btnHtml);
        },
        failCallback: function (html, url) {
            toastr['warning']('Something went wrong');
            btnDownloadExcel.removeAttr('disabled');
            btnDownloadExcel.html(btnHtml);
            console.log('Your file download just failed.' + 'Here was the resulting error HTML: \r\n' + html);
        }
    });
}
