<?php

return array(
    'Allowances' => array(
        'title' => 'Master Allowances',
        'filter' => array(
            'search_label' => 'Kata Kunci',
            'search_placeholder' => 'Cari Berdasarkan Nama Tunjangan'
        ),
        'inquiry' => array(
            'effective_date' => 'Tanggal Efektif',
            'allowance_name' => 'Nama Tunjangan',
            'default_value' => 'Nilai Default',
            'is_active' => 'Is Active',
            'gl_account' => 'GL Account',
        ),
        'form' => array(
            'allowance_code' => 'Kode Tunjangan',
            'allowance_name' => 'Nama Tunjangan',
            'default_value' => 'Nilai Default',
            'effective_date' => 'Tanggal Efektif',
            'effective_date_end' => 'Tanggal Efektif Akhir',
            'minimum_work_period' => 'Minimal Masa Kerja',
            'calculation_type' => 'Jenis Perhitungan',
            'calculation_mode' => 'Tipe Kalkulasi',
            'area_and_group' => 'Area & Grup',
            'list_of_data_area' => 'Daftar Data Area',
            'area_name' => 'Nama Area',
            'list_of_data_area_group' => 'Daftar Data Area Grup',
            'rules' => 'Rules',
            'area_group_name' => 'Area Grup',
            'list_of_payroll_rules' => 'Daftar Data Payroll Rule',
            'label_upload' => 'Upload Template File',
            'gl_account' => 'GL Account',
        ),
        'modal' => array(
            'delete' => array(
                'title' => 'Hapus Master Allowance',
                'confirm' => 'Anda yakin?',
                'submit' => 'Hapus',
                'back' => 'Kembali',
            ),
            'delete_all' => array(
                'title' => 'Hapus <span id="selected">{selected_delete, number, integer}</span> Master Allowance dipilih',
                'confirm' => 'Anda yakin?',
                'submit' => 'Hapus',
                'back' => 'Kembali',
            ),
            'import_excel' => array(
                'title' => 'Import Data Excel',
                'confirm' => 'Anda yakin?',
                'submit' => 'Import',
                'back' => 'Kembali',
            )
        ),
        "upload" => array(
            "process_id" => "Proses ID",
            "update_flg" => "Update",
            "valid_flg" => "Valid",
            "validation" => array(
                "notfound" => "tidak ada dalam databae"
            ),
            "information" => array(
                "informationrow" => "Informasi Baris",
                "new" => "Data Baru",
                "update" => "Data Update",
                "invalid" => "Data Tidak Sesuai",
            )
        )
    )
);