<?php

return array(
    'Deductions' => array(
        'title' => 'Master Deductions',
        'filter' => array(
            'search_label' => 'Kata Kunci',
            'search_placeholder' => 'Cari Berdasarkan Nama Potongan'
        ),
        'inquiry' => array(
            'effective_date' => 'Tanggal Efektif',
            'deduction_name' => 'Nama Potongan',
            'default_value' => 'Nilai Default',
            'is_active' => 'Is Active',
        ),
        'form' => array(
            'deduction_code' => 'Kode Potongan',
            'deduction_name' => 'Nama Potongan',
            'default_value' => 'Nilai Default',
            'effective_date' => 'Tanggal Efektif',
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
        ),
        'modal' => array(
            'delete' => array(
                'title' => 'Hapus Master Potongan',
                'confirm' => 'Anda yakin?',
                'submit' => 'Hapus',
                'back' => 'Kembali',
            ),
            'delete_all' => array(
                'title' => 'Hapus <span id="selected">{selected_delete, number, integer}</span> Master Potongan dipilih',
                'confirm' => 'Anda yakin?',
                'submit' => 'Hapus',
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