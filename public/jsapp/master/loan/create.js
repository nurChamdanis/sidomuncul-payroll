let payloadCompanyOptions = {};
let payloadAreaOptions = {};
let payloadEmployeeOptions = {};
let payloadSystemOptions = {};

let today = new Date();
let formattedDate = today.getFullYear() + '-' + 
    ('0' + (today.getMonth() + 1)).slice(-2) + '-' + 
    ('0' + today.getDate()).slice(-2);

$(function(){

     /** Company Options */
    OptionsSelect({id: 'company_id', url: 'master_loan/options/company', payload: payloadCompanyOptions});
    payloadAreaOptions.company_id = $(this).val();
    $('#company_id').change(function(){
        payloadAreaOptions.company_id = $(this).val();
        payloadEmployeeOptions.company_id = $(this).val();
        $('#work_unit_id').select2('val','');
        $('#employee_id').select2('val','');
    });

    /** Area Options */
    OptionsSelect({id: 'work_unit_id', url: 'master_loan/options/area', payload: payloadAreaOptions});
    $('#work_unit_id').change(function(){
        payloadEmployeeOptions.work_unit_id = $(this).val();
        $('#employee_id').select2('val','');
    });

        
    /** Employee Options */
    OptionsSelect({id: 'employee_id', url: 'master_loan/options/employee', payload: payloadEmployeeOptions});

    /** Loan Type Option */
    OptionsSelect({id: 'loan_type', url: 'master_loan/options/system', payload: payloadSystemOptions = {
        system_type : 'loan_type',
    }});

     /** Loan Duration Option */
    OptionsSelect({id: 'loan_duration', url: 'master_loan/options/system', payload: payloadSystemOptions = {
        system_type : 'loan_duration',
    }});

    $('#loan_duration').change(function(){
        calculateMonthlyDeduction();
    });

    // Set the default date value
    $('#periodFrom').val(formattedDate);

    $('#periodFrom').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', function(e) {
        calculateMonthlyDeduction();
    });

    $('#periodFrom').datepicker('update', $('#periodFrom').val()).trigger('changeDate');

    $('#loan_total').on('blur', function () {
        calculateMonthlyDeduction();
    });

    formInitialize(form);

});

function handleSubmit()
{
    if(!form.valid()) {
        toastr['error']('Please check your submission form');
        return;
    }
        
    const submitter = new FormSubmitter('master_loan/store', serializeArray(form));

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
}

function calculateMonthlyDeduction(){
    let loan_total = $('#loan_total').val().replace(/[^0-9]/g, '');
    let loan_duration =  $('#loan_duration').val();
    let loan_period_start = $('#periodFrom').val();
    let monthly_deduction;

    if(loan_total != "0" && loan_total != null && loan_duration != "" && loan_duration != null && loan_period_start){
        monthly_deduction = (loan_total / loan_duration).toFixed(0);
        let loan_period_end = new Date(new Date(loan_period_start).setMonth(new Date(loan_period_start).getMonth() + parseInt(loan_duration))).toISOString().split('T')[0];
        $('#periodTo').val(loan_period_end);
        $('#monthly_deduction').val(monthly_deduction);
        $('#monthly_deduction').autoNumeric('set', monthly_deduction);
    }
}