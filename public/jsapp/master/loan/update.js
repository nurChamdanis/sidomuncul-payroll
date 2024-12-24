let payloadCompanyOptions = {};
let payloadAreaOptions = {};
let payloadEmployeeOptions = {};
let payloadSystemOptions = {};

let today = new Date();
let formattedDate = today.getFullYear() + '-' + 
    ('0' + (today.getMonth() + 1)).slice(-2) + '-' + 
    ('0' + today.getDate()).slice(-2);

$(function(){
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
    let remaining_loan = parseFloat($('#remaining_loan').val()).toLocaleString('id-ID');
    let loan_total = $('#loan_total').val();

    if(!form.valid()) {
        toastr['error']('Please check your submission form');
        return;
    }

    if(loan_total < remaining_loan) {
        toastr['error']("Minimum loan total is " + remaining_loan);
        console.log("Loan total = " + loan_total);
        console.log("Remaining loan = " +remaining_loan);
        return;
    }
        
    const submitter = new FormSubmitter('master_loan/update', serializeArray(form));

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