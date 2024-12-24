function select2options({id, url, placeholder, searchText, payload, minimumInputLength}){
    $(id).select2({
        ajax: {
            url: url,
            dataType: 'json',
            // delay: 200,
            data: function(params) {
                var query = {
                    search: params.term,
                    page: params.page || 1,
                }

                if(payload){
                    return {...query, ...payload};
                }
                
                return query;
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return data;
            },
            cache: true,
            minimumInputLength: minimumInputLength
        },
        placeholder: placeholder,
        language: {
            searching: function() {
                return searchText;
            }
        },
    });

    $(`select${id}`).on('change', function () {
        const value = $(this).val();
        if(Array.isArray(value)) {
            if(value.includes('-')){
                $(this).select2("val","");
            }
        } else {
            if(value == '-'){
                $(this).select2("val","");
            }
        }
    });
}