$(function () {
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

// untuk mengambil data log
// get pakai where yang nomor compensation_idnya sama
