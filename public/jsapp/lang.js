$('.btn-toggle').click(function() {
    var lang_code = $(this).find('.btn-custom').text();
    $.ajax({
        url: SITE_URL + 'lang/set',
        type: 'GET',
        dataType: 'json',
        data: {
            lang_code: (lang_code == "EN") ? "ID" : "EN", 
            cpms_token: $('#cmps_token').val()
        },
        success: function (results) {
            console.log(results);
            // if(results == "EN"){
            //     $("#btn_lang_en").addClass('btn-custom').removeClass('btn-default');
            //     $("#btn_lang_id").addClass('btn-default').removeClass('btn-custom');
            // }else{
            //     $("#btn_lang_id").addClass('btn-custom').removeClass('btn-default');
            //     $("#btn_lang_en").addClass('btn-default').removeClass('btn-custom');
            // }
            window.location.reload();
        }
    });
});