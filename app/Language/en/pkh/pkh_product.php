<?php

return array(
    'PKHProduct' => array(
        'title' => 'Upload Product',
        'filter' => array(
            'search_label' => 'Kata Kunci',
            'search_placeholder' => 'Search By Allowance Name'
        ),
        'inquiry' => array(
            'Category' => 'Category',
            'Product_Name' => 'Product Name',
            'Area' => 'Area',
            'Org_Unit' => 'Org Unit', 
        ),
        'placeholder' => array(
            'all' => array(
                'company' => 'Choose Company',
                'area'=>'All Area',
                'product_code' => 'Input of product code',
                'product_name' => 'Input of product name',
                'product_price' => 'Input of price',
                'daily_target' => 'Input of daily target',
                'pph_calculation' => 'Input of pph calculation'
            )
        ),
        'label' => array(
            'Company'=>'Company',
            'Area'=>'Area',
            'Org_Unit'=>'Org. Unit',
            'Product_Code'=>'Product Code',
            'Product_Name'=>'Product Name',
            'Pcs'=>'Pcs',
            'Price_Of_Product'=>'Price',
            'Daily_Target'=>'Daily Target',
            'Calculation_PPH'=>'Calcuation PPH',
            'Create_By'=>'Created By',
            'Create_Date'=>'Created Date',
            'Changed_By'=> 'changed_by',
            'Changed_Date'=> 'changed_by'
        ),
        'form' => array(
            'category_code' => 'Category Code',
            'category_name' => 'Category Name',
            'default_value' => 'Default Value',
            'effective_date' => 'Effective Date',
            'effective_date_end' => 'Effective Date End',
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
            'label_upload' => 'Upload File Template',
            'gl_account' => 'GL Account',
        ),
        'modal' => array(
            'delete' => array(
                'title' => 'Delete Allowance Master',
                'confirm' => 'Are you sure?',
                'submit' => 'Delete',
                'back' => 'Back',
            ),
            'delete_all' => array(
                'title' => 'Delete <span id="selected">{selected_delete, number, integer}</span> Selected Allowance Master',
                'confirm' => 'Are you sure?',
                'submit' => 'Delete',
                'back' => 'Back',
            ),
            'import_excel' => array(
                'title' => 'Import Excel Data',
                'confirm' => 'Are you sure?',
                'submit' => 'Import',
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
