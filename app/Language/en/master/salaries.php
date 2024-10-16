<?php

return array(
    'Salaries' => array(
        'title' => 'Master Salaries',
        'filter' => array(
            'status' => 'Status',
            'contract_type' => 'Contract Type',
        ),
        'all' => array(
            'contract_type' => 'All Contract Type',
            'status' => 'All Status',
        ),
        'inquiry' => array(
            'period' => 'Period',
            'basic_salary' => 'Basic salary',
            'dedduction' => 'Dedduction',
            'allowance' => 'Allowance',
            'attendance' => 'Attendance',
            'THP' => 'THP',
            'effective_date' => 'Effective Date',
            'PTKP' => 'PTKP',
            'employee_group' => 'Employee Group',
            'gross_up' => 'Gross Up',
            'count_pph21' => 'Count PPH21',
            'bpjs' => 'BPJS',
            'addition_components' => 'Addition Components',
            'meal_allowance' => 'Meal Allowance',
            'nominal' => 'Nominal',
            'percentage' => 'Percentage',
            'transport_allowance' => 'Transport Allowance',
            'deduction_components' => 'Deduction Components',
            'attendace_deduction' => 'Attendance Deduction',
            'lateness_deduction' => 'Lateness Deduction',
            'total_deduction' => 'Total Deduction',
            'total_addition' => 'Total Addition',
        ),
        'modal' => array(
            'delete' => array(
                'title' => 'Delete Salaries Master',
                'confirm' => 'Are you sure?',
                'submit' => 'Delete',
                'back' => 'Back',
            ),
            'delete_all' => array(
                'title' => 'Delete <span id="selected">{selected_delete, number, integer}</span> Selected Salaries Master',
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
                "notfound" => "does not exist in the database"
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