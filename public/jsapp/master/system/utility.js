
function handleChangeFreeText(id)
{
    const component = $(`#${id}`);
    const systemType = $(`#system_type`);
    const systemTypeWrapper = $(`#system_type_wrapper`);
    const systemTypeFreeText = $(`#system_type_free_text`);
    const systemTypeFreeTextWrapper = $(`#system_type_free_text_wrapper`);

    if(component.is(':checked')){
        systemTypeWrapper.addClass('hide');
        systemTypeFreeTextWrapper.removeClass('hide');
        systemTypeFreeText.val(systemType.select2('val'));
    }
    else
    {
        systemTypeWrapper.removeClass('hide');
        systemTypeFreeTextWrapper.addClass('hide');
        setSelectedValue(systemTypeFreeText.val());
    }
}

function setSelectedValue(value) {
    console.log("Selected value:", value);
    var $select = $('#system_type');
    var optionExists = $select.find('option[value="' + value + '"]').length > 0;

    if (optionExists) {
        console.log("Option exists. Setting value...");
        $select.val(value).trigger('change');
    } else {
        console.log("Option does not exist. Adding new option...");
        var newOption = new Option(value, value, true, true);
        $select.append(newOption).trigger('change');
    }
}

function replaceSpaces(event) {
    let inputValue = event.target.value;
    inputValue = inputValue.toLowerCase();
    
    let replacedValue = inputValue.replace(/\s+/g, '_');
    replacedValue = replacedValue.replace(/[^a-z_]/g, '');

    event.target.value = replacedValue;
    
    if (replacedValue.endsWith('_')) {
        replacedValue = replacedValue.slice(0, -1);
    }

    setSelectedValue(replacedValue);
}

$('#system_type').change(function(){
    $(`#system_type_free_text`).val($(this).select2('val'));
});