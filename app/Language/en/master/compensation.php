<?php

return array(
    'Compensation' => array(
        'title' => 'Master Compensation',
        'filter' => array(
            'search_company' => 'Company',
            'search_work_unit' => 'Org. Unit',
            'search_area' => 'Area',
            'search_employee' => 'Employee',
            'search_period' => 'Period',
            'search_compensation_type' => 'Compensation Type',
            'to' => 'To',
        ),
        'inquiry' => array(
            'company_name' => 'Company',
            'area_name' => 'Area',
            'work_unit' => 'Org. Unit',
            'employee_number' => 'Employee Number',
            'employee_name' => 'Employee Name',
            'period' => 'Period',
            'compensation_type' => 'Compensation Type',
            'total_compensation' => 'Compensation Amount',
            'created_by' => 'Created By',
            'created_dt' => 'Created Date',
            'changed_by' => 'Changed By',
        ),
        'create' => array(
            'compensation_description' => 'Description',
            'compensation_type' => 'Type',
            'employee_name' => 'Employee'
        ),
        'upload' => array(
            'validation' => array(
                'not_found' => 'does not exist in database.',
                'not_match_c' => 'does not match with the inserted company.',
                'not_match_e' => 'The number the employee does not match with employee name.',
                'wrong_format' => 'format is incorrect. Please use the correct format (yyyy-mm).'
            )
        )
    )
);