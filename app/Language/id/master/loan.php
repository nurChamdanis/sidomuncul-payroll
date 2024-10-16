<?php

return array(
    'Loan' => array(
        'title' => 'Master Koperasi & Organisasi',
        'filter' => array(
            'role' => 'Org. Unit',
            'employee' => 'Pegawai',
            'period' => 'Periode',
            'to' => 'Sd',  
            'cost_center' => 'Cost Center',
        ),
        'all' => array(
            'role' => 'Semua Org. Unit',
            'employee' => 'Semua Pegawai',
            'cost_center' => 'Semua Cost Center',
        ),
        'inquiry' => array(
            'employee_id' => 'Nomor Karyawan',
            'employee_name' => 'Nama Karyawan',
            'loan_type' => 'Jenis Pinjaman',
            'loan_term' => 'Lama Pinjaman',
            'loan_amount' => 'Besar Pinjaman',
            'start_period' => 'Periode Awal',
            'end_period' => 'Periode Akhir',
            'monthly_deduction' => 'Potongan Perbulan',
            'remark' => 'Keterangan',
            'remaining_loan' => 'Sisa Pelunasan',
        ),
        'modal' => array(
            'delete' => array(
                'title' => 'Hapus <span id="selected">{selected_delete, number, integer}</span> Master Pinjaman dipilih',
                'confirm' => 'Anda yakin?',
                'submit' => 'Hapus',
                'back' => 'Kembali',
            ),
            'delete_all' => array(
                'title' => 'Hapus <span id="selected">{selected_delete, number, integer}</span> Master Pinjaman dipilih',
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
                "notfound" => "tidak ada dalam database",
                "not_in_list" => "tidak sesuai dengan pilihan "
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