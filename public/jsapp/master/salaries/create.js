let payloadCompanyOptions = {};
let payloadAreaOptions = {};
let payloadEmployeeOptions = {};
let payloadSystemOptions = {};
let allowanceMaster = {};
let deductionMaster = {};
let basicSalary = 0;
let totalAllowance = 0;
let totalDeduction = 0;
let totalAllowanceWithTax = 0;
let totalDeductionWithTax = 0;
let totalAllowanceNoTax = 0;
let totalDeductionNoTax = 0;
let thp = 0;
let company_id;
let work_unit_id;
let employee_group;

let today = new Date();
let formattedDate = today.getFullYear() + '-' + 
    ('0' + (today.getMonth() + 1)).slice(-2) + '-' + 
    ('0' + today.getDate()).slice(-2);

$(function(){

     /** Company Options */
    OptionsSelect({id: 'company_id', url: 'master_salaries/options/company', payload: payloadCompanyOptions});
    payloadAreaOptions.company_id = $(this).val();
    $('#company_id').change(function(){
        payloadAreaOptions.company_id = $(this).val();
        payloadEmployeeOptions.company_id = $(this).val();
        $('#work_unit_id').select2('val','');
        $('#employee_id').select2('val','');
        company_id = $(this).val();
        showComponenSection();
    });

    /** Area Options */
    OptionsSelect({id: 'work_unit_id', url: 'master_salaries/options/area', payload: payloadAreaOptions});
    $('#work_unit_id').change(function(){
        payloadEmployeeOptions.work_unit_id = $(this).val();
        $('#employee_id').select2('val','');
        work_unit_id = $(this).val();
        showComponenSection();
    });

        
    /** Employee Options */
    OptionsSelect({id: 'employee_id', url: 'master_salaries/options/employee', payload: payloadEmployeeOptions});

    /** Ptkp Option */
    OptionsSelect({id: 'ptkp', url: 'master_salaries/options/system', payload: payloadSystemOptions = {
        system_type : 'status_ptkp',
    }});

    /** Employee Group Option */
    OptionsSelect({id: 'employee_group', url: 'master_salaries/options/system', payload: payloadSystemOptions = {
        system_type : 'area_group',
    }});

    $('#employee_group').change(function(){
        employee_group = $(this).val();
        showComponenSection();
    });
    
    // Set the default date value
    $('#effective_date').val(formattedDate);
    $('#effective_date_bpjs').val(formattedDate);

    $('#effective_date, #effective_date_bpjs').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        orientation: 'bottom',  // Change the orientation to bottom
        showOnFocus: true       // Show the calendar when the input is focused
    });

    handleCalculate();
    formInitialize(form);
});

function handleSubmit()
{
    if(!form.valid()) {
        toastr['error']('Please check your submission form');
        return;
    }

    let pph21_flg = $('#pph21_flg').is(':checked') ? '1' : '0';
    let gross_up_flg =  $('#grossup_flg').is(':checked') ? '1' : '0';
    $('#calc_pph21_flg').val(pph21_flg);
    $('#calc_grossup_flg').val(gross_up_flg);

    // allowances
    var allowances = new Array();
    $('.input-allowance').each(function () {
        var allowance = {};
        const isChecked = $(this).find('.is_allowance').is(':checked');
        allowance.is_active = (isChecked) ? "1" : "0";
        allowance.allowance_id  = $(this).find('.allowance_id').val();
        allowance.calculation_type = $(this).find('.choice_allowance:checked').val();
        allowance.allowances_value = $(this).find('.value_allowance').val();
        allowance.pph21_flg = $(this).find('.allowance_pph21_flg').val();
        allowances.push(allowance);                 
    });
    $('#allowances').val(JSON.stringify(allowances));

    // deductions
    var deductions = new Array();
    $('.input-deduction').each(function () {
        var deduction = {};
        const isChecked = $(this).find('.is_deduction').is(':checked');
        deduction.is_active = (isChecked) ? "1" : "0";
        deduction.deduction_id  = $(this).find('.deduction_id').val();
        deduction.calculation_type = $(this).find('.choice_deduction:checked').val();
        deduction.deductions_value = $(this).find('.value_deduction').val();
        deduction.pph21_flg = $(this).find('.deduction_pph21_flg').val();
        deductions.push(deduction);                 
    });
    $('#deductions').val(JSON.stringify(deductions));

    const submitter = new FormSubmitter('master_salaries/store', serializeArray(form));

    submitter
        .success(function(data) {
            if(data.status){
                redirectTo(data.message,'success', data.redirect_link);
            }
        })
        .error(function(error) {
            toastr['error'](error);
            return;
        });

    return;        
}

function showComponenSection() {
    if(company_id && work_unit_id && employee_group){
        var data = new FormData();
        data.append('company_id', company_id);
        data.append('work_unit_id', work_unit_id);
        data.append('employee_group', employee_group);
        data.append('cpms_token', $('#cpms_token').val());
        $.ajax({
            type: 'POST',
            url: SITE_URL + "master_salaries/getDetailAllowancesDeductions",
            contentType: false,
            processData: false,
            dataType: "json",
            data: data,
            async: false,
            success: function (result) {
                allowanceMaster = result.data.allowance;
                deductionMaster = result.data.deduction;
                generateAllowanceDeductionList();
            }
        });
    } else {
        $('.component-section').hide();
    }
}

function generateAllowanceDeductionList() {
    if (allowanceMaster.length > 0 || deductionMaster.length > 0) {
        if (allowanceMaster.length > 0) {
            renderItems(allowanceMaster, '.list-allowance', 'allowance');
            $('.allowance-card').show();
        } else {
            $('.allowance-card').hide();
        }

        if (deductionMaster.length > 0) {
            renderItems(deductionMaster, '.list-deduction', 'deduction');
            $('.deduction-card').show();
        } else {
            $('.deduction-card').hide();
        }
        $('.component-section').show();
        handleCalculate();
    } else {
        $('.component-section').hide();
    }
}

function parseFormattedNumber(formattedNumber) {
    return parseFloat(formattedNumber.replace(/\./g, ""));
}

function calculateTotal(type) {
    let total = 0;
    if(type == 'allowance'){
        totalAllowanceWithTax = 0;
        totalAllowanceNoTax = 0;
    } else {
        totalDeductionWithTax = 0;
        totalDeductionNoTax = 0;
    }
    const basicSalary = parseFormattedNumber($('#basic_salary').val());
    const selector = `.input-${type}`;
    $(selector).each(function () {
        const isChecked = $(this).find(`.is_${type}`).is(':checked');
        const isCalcPph21 = $(this).find(`.${type}_pph21_flg`).val() == '1';
        if (isChecked) {
            const value = parseFormattedNumber($(this).find(`.value_${type}`).val());
            const isPercentage = $(this).find(`.choice_${type}:checked`).val() == "1";
            let totalTemp = isPercentage ? (value / 100) * basicSalary : value;
            if (type == 'allowance') {
                isCalcPph21 ? totalAllowanceWithTax += totalTemp : totalAllowanceNoTax += totalTemp;
            } else {
                isCalcPph21 ? totalDeductionWithTax += totalTemp : totalDeductionNoTax += totalTemp;
            }
            total += totalTemp;
        }
    });
    $(`#total_${type}`).val(total).blur();
    return total;
}

function calculateThp() {
    let basicSalary = parseFormattedNumber($('#basic_salary').val());
    let thp = isNaN(basicSalary) ? 0 : basicSalary + totalAllowance - totalDeduction;
    $('#THP').val(thp);
    $('#THP').blur();
    formatNumberWithThousandSeparator();
}

function handleCalculate() {
    updateCalculations();
    $('.choice_allowance, .choice_deduction, .is_allowance, .is_deduction').change(updateCalculations);
    $('.value_allowance, .value_deduction, #basic_salary').blur(updateCalculations);
    formatNumberWithThousandSeparator();
}

function formatNumberWithThousandSeparator() {
    $('.numericOnly').ForceNumericOnly();
    $('.nominal').autoNumeric('init', {              
        aSep: '.',              
        aDec: ',',
        mDec: 0,
    });
}

const renderItems = (items, containerSelector, type) => {
    $(containerSelector).html('');
    items.forEach((item, index) => {
        $(containerSelector).append(`
            <div class="row input-${type} m-b-20">
                <input type="hidden" class="${type}_id" id="${type}_id" name="${type}_id" value="${item[`${type}_id`]}">
                <input type="hidden" class="${type}_pph21_flg" id="${type}_pph21_flg" name="${type}_pph21_flg" value="${item[`pph21_flg`]}">
                <div class="col-md-12">
                    <input type="checkbox" class="form-check-input is_${type}" id="is_${type}${index + 1}" name="is_${type}${index + 1}" checked> 
                    <label class="form-check-label" for="is_${type}${index + 1}">
                        ${item[`${type}_name`]}
                    </label>
                </div>
                <div class="col-md-3 m-t-5">
                    <input class="form-check-input choice_${type}" type="radio" name="choice_${type}${index + 1}" id="choice_${type}_nominal${index + 1}" value="0" checked>
                    <label class="form-check-label" for="choice_${type}_nominal${index + 1}">
                        ${lang.Salaries.inquiry.nominal}
                    </label>
                </div>
                <div class="col-md-3 m-t-5">
                    <input class="form-check-input choice_${type}" type="radio" name="choice_${type}${index + 1}" id="choice_${type}_percentage${index + 1}" value="1">
                    <label class="form-check-label" for="choice_${type}_percentage${index + 1}">
                        ${lang.Salaries.inquiry.percentage}(%)
                    </label>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-12">
                    <input type="text" autocomplete="off" class="form-control nominal value_${type}" id="value_${type}" name="value_${type}" value="${item.default_value}" required>
                </div>
            </div>`
        );
    });
};

const updateCalculations = () => {
    totalAllowance = calculateTotal('allowance');
    totalDeduction = calculateTotal('deduction');
    $('#total_allowance_with_tax').val(totalAllowanceWithTax);
    $('#total_allowance_no_tax').val(totalAllowanceNoTax);
    $('#total_deduction_with_tax').val(totalDeductionWithTax);
    $('#total_deduction_no_tax').val(totalDeductionNoTax);
    calculateThp();
};

