let payloadCompanyOptions = {};
var routesPrefix = 'master_loan';
let basic_salary_id = $('#basic_salary_id').val();
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

$(function(){
    showComponenSection();
    formInitialize(form);
});

function showDeleteModal(){
    $('#deleteModal').modal('show');
}

function handleSingleDelete()
{
    if(!form.valid()) {
        toastr['error']('Please check your submission form');
        return;
    }
        
    const submitter = new FormSubmitter('master_salaries/remove', serializeArray(form));

    submitter
        .success(function(data) {
            if(data.status){
                $('#deleteModal').modal('hide');
                redirectTo(data.message,'success', data.redirect_link);
            }
        })
        .error(function(error) {
            toastr['error'](error);
            return;
        });
}

function showComponenSection() {
    var data = new FormData();
    data.append('basic_salary_id', basic_salary_id);
    data.append('cpms_token', $('#cpms_token').val());
    $.ajax({
        type: 'POST',
        url: SITE_URL + "master_salaries/getDetailAllowancesDeductionsSalary",
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
        let checkedItem = item.is_active == '1' ? "checked" : "";
        let checked_nominal = item.calculation_type == '0' ? "checked" : "";
        let checked_percentage = item.calculation_type == '1' ? "checked" : "";
        $(containerSelector).append(`
            <div class="row input-${type} m-b-20">
                <input type="hidden" class="${type}_id" id="${type}_id" name="${type}_id" value="${item[`${type}_id`]}">
                <input type="hidden" class="${type}_pph21_flg" id="${type}_pph21_flg" name="${type}_pph21_flg" value="${item[`pph21_flg`]}">
                <div class="col-md-12">
                    <input type="checkbox" disabled class="form-check-input is_${type}" ${checkedItem} id="is_${type}${index + 1}" name="is_${type}${index + 1}"> 
                    <label class="form-check-label" for="is_${type}${index + 1}">
                        ${item[`${type}_name`]}
                    </label>
                </div>
                <div class="col-md-3 m-t-5">
                    <input class="form-check-input disabled choice_${type}" ${checked_nominal} type="radio" name="choice_${type}${index + 1}" id="choice_${type}_nominal${index + 1}" value="0">
                    <label class="form-check-label" for="choice_${type}_nominal${index + 1}">
                        ${lang.Salaries.inquiry.nominal}
                    </label>
                </div>
                <div class="col-md-3 m-t-5">
                    <input class="form-check-input disabled choice_${type}" ${checked_percentage} type="radio" name="choice_${type}${index + 1}" id="choice_${type}_percentage${index + 1}" value="1">
                    <label class="form-check-label" for="choice_${type}_percentage${index + 1}">
                        ${lang.Salaries.inquiry.percentage}(%)
                    </label>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-12">
                    <input type="text" autocomplete="off" readonly class="form-control nominal value_${type}" id="value_${type}" name="value_${type}" value="${item.default_value}" required>
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