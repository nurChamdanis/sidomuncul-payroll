const formId = $('form').attr('id');
const form = $(`#${formId}`);
const btnSubmit = '#btn_submit';
const btnSubmitText = $(btnSubmit).html();
const loadingText = `${lang.Shared.please_wait} <i class="fa fa-spinner fa-pulse fa-fw"></i>`;

function formInitialize(formId, customMessage, customOptions) {
    var rules = {};
    var messages = customMessage ? customMessage : {};
    var defaultOptions = {
        ignore: [],
        errorElement: 'label',
        rules: rules,
        messages: messages,
        errorPlacement: function (error, element) {
            // Customize error placement logic if necessary
            if (element.attr('type') === 'radio') {
                element.parent().parent().append(error);
            }else if (element[0].input != undefined) {
                error.insertAfter(element);
            } else if (element.is('.select2-hidden-accessible')) {
                error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
            } else {
                var _parent = element.parent();
                $(_parent).append(error);
            }
        },
        highlight: function(element, errorClass) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element, errorClass) {
            $(element).closest('.form-group').removeClass('has-error');
        }
    };  

    defaultOptions = customOptions ? customOptions : defaultOptions;
    var validator = $(formId).validate(defaultOptions);

    // Return the validator object
    return validator;
}

function toggleSubmit(toggle = 'enabled', newSubmit){
    btnSubmitId = newSubmit || btnSubmit;

    if(toggle === 'enabled'){
        $(btnSubmitId).html(btnSubmitText);
        $(btnSubmitId).removeAttr('disabled');
    } else {
        $(btnSubmitId).html(loadingText);
        $(btnSubmitId).attr('disabled','disabled');
    }
}

function serializeArray(form) {
    const formData = new FormData();
    const serializedArray = form.serializeArray();

    serializedArray.forEach(item => {
         // Check if the item is a file input
        const isFileInput = form.find(`[name="${item.name}"]`).prop('type') === 'file';

        // If it's a file input, append its files
        if (isFileInput) {
            const files = form.find(`[name="${item.name}"]`)[0].files;
            for (let i = 0; i < files.length; i++) {
                formData.append(item.name, files[i]);
            }
        } else {
            formData.append(item.name, item.value);
        }
    });

    return formData;
}

function defaultChecked(id, checkedClass){
    const componentCheckBox = $(`.${checkedClass}:enabled`);
    const componentCheckBoxChecked = $(`.${checkedClass}:checked`);
    const componentCheckBoxAll = $(`#${id}`);

    if(componentCheckBox.length == componentCheckBoxChecked.length){
        componentCheckBoxAll.prop('checked',true);
    } else {
        componentCheckBoxAll.prop('checked',false);
    }
}

// Numeric only control handler
jQuery.fn.ForceNumericOnly = function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (
                key == 8 || 
                key == 9 ||
                key == 13 ||
                key == 46 ||
                key == 110 ||
                key == 190 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
        });
    });
};

$(function(){
    $('.numericOnly').ForceNumericOnly();
    $('.nominal').autoNumeric('init', {              
        aSep: '.',              
        aDec: ',',
        mDec: 0,
    });
    
    $('.datepicker').datepicker();
});