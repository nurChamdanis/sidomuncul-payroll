<?php

return array(
    'Loan' => array(
        'title' => 'Master Of Cooperatives and Organizations',
        'filter' => array(
            'role' => 'Org. Unit',
            'employee' => 'Employee',
            'period' => 'Period',
            'to' => 'To',  
            'cost_center' => 'Cost Center',
        ),
        'all' => array(
            'role' => 'All Org. Unit',
            'employee' => 'All Employee',
            'cost_center' => 'All Cost Center',
        ),
        'inquiry' => array(
            'employee_id' => 'Employee ID',
            'employee_name' => 'Employee Name',
            'loan_type' => 'Loan Type',
            'loan_term' => 'Loan Term',
            'loan_amount' => 'Loan Amount',
            'start_period' => 'Start Period',
            'end_period' => 'End Period',
            'monthly_deduction' => 'Monthly Deduction',
            'remark' => 'Remark',
            'remaining_loan' => 'Remaining Loan',
        ),
        'modal' => array(
            'delete' => array(
                'title' => 'Delete Loan Master',
                'confirm' => 'Are you sure?',
                'submit' => 'Delete',
                'back' => 'Back',
            ),
            'delete_all' => array(
                'title' => 'Delete <span id="selected">{selected_delete, number, integer}</span> Selected Loan Master',
                'confirm' => 'Are you sure?',
                'submit' => 'Delete',
                'back' => 'Back',
            )
        ),
            "upload" => array(
            "process_id" => "Process ID",
            "update_flg" => "Update",
            "valid_flg" => "Valid",
            "validation" => array(
                "notfound" => "does not exist in the database",
                "not_in_list" => "does not exist within the list of"
            ),
            "information" => array(
                "informationrow" => "Row Information",
                "new" => "New Data",
                "update" => "Update Data",
                "invalid" => "Invalid Data",
            )
        )
    )
);