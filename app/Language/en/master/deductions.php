<?php

return array(
    'Deductions' => array(
        'title' => 'Master Deductions',
        'filter' => array(
            'search_label' => 'Kata Kunci',
            'search_placeholder' => 'Search By Deduction Name'
        ),
        'inquiry' => array(
            'effective_date' => 'Effective Date',
            'deduction_name' => 'Deduction Name',
            'default_value' => 'Default Value',
            'is_active' => 'Is Active',
        ),
        'form' => array(
            'deduction_code' => 'Deduction Code',
            'deduction_name' => 'Deduction Name',
            'default_value' => 'Default Value',
            'effective_date' => 'Effective Date',
            'minimum_work_period' => 'Minimum Work Period',
            'calculation_type' => 'Calculation Type',
            'calculation_mode' => 'Calculation Mode',
            'area_and_group' => 'Area & Group',
            'list_of_data_area' => 'List of Area Data',
            'area_name' => 'Area Name',
            'list_of_data_area_group' => 'List of Group Area Data',
            'rules' => 'Rules',
            'area_group_name' => 'Group Area',
            'list_of_payroll_rules' => 'List of Payroll Rules',
        ),
        'modal' => array(
            'delete' => array(
                'title' => 'Delete Deduction Master',
                'confirm' => 'Are you sure?',
                'submit' => 'Delete',
                'back' => 'Back',
            ),
            'delete_all' => array(
                'title' => 'Delete <span id="selected">{selected_delete, number, integer}</span> Selected Deduction Master',
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