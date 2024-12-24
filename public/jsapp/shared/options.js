function OptionsSelect({
    id, 
    url, 
    payload, 
    placeholder = 'Select Data', 
    searchText = 'Searching...',
    allowClear,
    allowSelectPlaceholder = false
}) {
    if (!$(`select#${id}`)) {
        toastr['error'](`Element with ID ${id} not found.`);
        return;
    }

    const defaultPlaceholder = $(`select#${id}`).attr('placeholder');

    if(defaultPlaceholder){
        placeholder = defaultPlaceholder;
    }

    // Initialize Select2
    $(`select#${id}`).select2({
        allowClear: allowClear,
        ajax: {
            url: `${SITE_URL}${url}`,
            dataType: 'json',
            data: function (params) {
                return {
                    search: params.term,
                    page: params.page || 1,
                    ...(payload || {}) // Merge payload if it exists
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return data;
            },
            cache: true,
        },
        placeholder: placeholder,
        language: {
            searching: function () {
                return searchText;
            }
        },
    });

    if(allowSelectPlaceholder === false){
        $(`select#${id}`).on('change', function () {
            const value = $(this).val();
            if(Array.isArray(value)) {
                if(value.includes('-')) $(this).select2("val","");
                return;
            }
                
            if(value == '-' || value == '') $(this).select2("val","");
        });
    }

    $(`select#${id}`).on("select2:close", function (e) {  
        $(this).valid(); 
    });
}
