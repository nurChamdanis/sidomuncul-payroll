<?php

return array(
    'Compensation' => array(
        'title' => 'Master Kompensasi',
        'filter' => array(
            'search_company' => 'Perusahaan',
            'search_work_unit' => 'Org. Unit',
            'search_area' => 'Area',
            'search_employee' => 'Pegawai',
            'search_period' => 'Periode',
            'search_compensation_type' => 'Jenis Kompensasi',
            'to' => 'Ke',
        ),
        'inquiry' => array(
            'company_name' => 'Company',
            'area_name' => 'Area',
            'work_unit' => 'Org. Unit',
            'employee_number' => 'Nomor Pegawai',
            'employee_name' => 'Nama Pegawai',
            'period' => 'Periode',
            'compensation_type' => 'Tipe Kompensasi',
            'total_compensation' => 'Jumlah Kompensasi',
            'created_by' => 'Dibuat Oleh',
            'created_dt' => 'Dibuat Pada',
            'changed_by' => 'Diubah Oleh',
            'changed_dt' => 'Diubah Pada',
        ),
        'create' => array(
            'compensation_description' => 'Deskripsi',
            'compensation_type' => 'Jenis',
            'employee_name' => 'Karyawan'
        ),
        'upload' => array(
            'validation' => array(
                'not_found' => 'tidak ditemukan dalam database.',
                'not_match_c' => 'tidak cocok dengan perusahaan yang dimasukkan.',
                'not_match_e' => 'Nomor pegawai tidak cocok dengan nama pegawai.',
                'wrong_format' => 'format salah. Silakan gunakan format yang benar (yyyy-mm).'
            )
        )
    )
);