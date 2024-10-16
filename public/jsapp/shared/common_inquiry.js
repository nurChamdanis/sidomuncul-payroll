
const handleEdit = (url, checkedClass) => {
    const allowanceCheckedVal = $(`.${checkedClass}:checked`).val();
    window.location = `${SITE_URL}${url}/${allowanceCheckedVal}`
}

const handleDeleteAll = async(url, checkboxClass, btnId) => {
    const allowanceCheckedVal = $(`.${checkboxClass}:checked`);
    const btnSubmit = $(`#${btnId}`);
    const deleteBtnHtml = btnSubmit.html();
    
    const payload = [];
    
    allowanceCheckedVal.each(function(){
        payload.push($(this).val());
    });

    btnSubmit.html(`${lang.Shared.please_wait} <i class="fa fa-spinner fa-pulse fa-fw"></i>`);
    btnSubmit.attr('disabled');

    const formData = new FormData();
    formData.append('ids', payload.join(","));
    formData.append('cpms_token', $('#cpms_token').val());

    const response = await fetch(`${SITE_URL}${url}`, {
                        method: 'POST',
                        body: formData
                    });
    const result = await response.json();
    
    $('#deleteModal').modal('hide');
    
    btnSubmit.html(deleteBtnHtml);
    btnSubmit.removeAttr('disabled');

    if(result.status){
        const allowanceCheckedVal = $(`.${checkboxClass}:checked`);
        const defaultOptions = {
            title: `Success deleted ${allowanceCheckedVal.length} items`,
            icon: 'success',
            width: 400,
            customClass: {
                confirmButton: 'btn-custom btn-md waves-effect waves-light',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false,
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
        };

        Swal.fire(defaultOptions).then((result) => {
            if (result.isConfirmed) {
                handleSearch();
            } 
        });
    }
}

function toggleEditButton(btnId, checkboxClass){
    const checkboxLength = $(`.${checkboxClass}:checked`).length;
    if(checkboxLength == 1){
        $(`#${btnId}`).removeAttr('disabled');
    } else {
        $(`#${btnId}`).attr('disabled','disabled');
    }
}

function toggleDeleteButton(btnId, checkboxClass){
    const checkboxLength = $(`.${checkboxClass}:checked`).length;
    if(checkboxLength > 0){
        $(`#${btnId}`).removeAttr('disabled');
    } else {
        $(`#${btnId}`).attr('disabled','disabled');
    }
}

const selfChecked = (checkAll, btnEdit, btnDelete, checkedClass) => {
    defaultChecked(checkAll,checkedClass);
    toggleEditButton(btnEdit, checkedClass);
    toggleDeleteButton(btnDelete, checkedClass);
}

const showDeleteModalInquiry = (modal, checkedClass) => {
    const allowanceLength = $(`.${checkedClass}:checked`).length;
    if(allowanceLength <= 0){
        toastr['error'](`No selected data for deletion, please select at least 1 data`);
        return;
    }
    $('#selected').html(allowanceLength);
    $(`#${modal}`).modal('show');
}

$('#checkAll').on('change', function(){
    const btnEdit = $(this).attr('ex-attr-edit');
    const btnDelete = $(this).attr('ex-attr-delete');
    const checkedClass = $(this).attr('ex-attr-checked');
    toggleEditButton(btnEdit, checkedClass);
    toggleDeleteButton(btnDelete, checkedClass);
});

const handleDownload = (btnId, url) => {
    const btnHtml = $(`#${btnId}`).html();

    $.fileDownload(url, {
        httpMethod: "POST",
        data:  {...tableFilters()},
        successCallback: function (url) {
            toastr.success('File successfully download');
            $(`#${btnId}`).removeAttr('disabled');
            $(`#${btnId}`).html(btnHtml);
        },
        failCallback: function (html, url) {
            toastr.warning('Something went wrong');
            $(`#${btnId}`).removeAttr('disabled');
            $(`#${btnId}`).html(btnHtml);
            console.log('Your file download just failed.' + 'Here was the resulting error HTML: \r\n' + html);
        }
    });
}