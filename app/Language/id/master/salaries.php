<?php

return array(
    'Salaries' => array(
        'title' => 'Master Gaji Pokok',
        'filter' => array(
            'status' => 'Status',
            'contract_type' => 'Jenis Kontrak',
        ),
        'all' => array(
            'contract_type' => 'Semua Jenis Kontrak',
            'status' => 'All Status',
        ),
        'inquiry' => array(
            'period' => 'Periode',
            'basic_salary' => 'Gaji Pokok',
            'dedduction' => 'Potongan',
            'allowance' => 'Tunjangan',
            'attendance' => 'Kehadiran',
            'THP' => 'THP',
            'effective_date' => 'Effective Date',
            'PTKP' => 'PTKP',
            'employee_group' => 'Grup Karyawan',
            'gross_up' => 'Gross Up',
            'count_pph21' => 'Perhitungan PPH21',
            'bpjs' => 'BPJS',
            'addition_components' => 'Komponen Penambahan',
            'meal_allowance' => 'Tunjangan Makanan',
            'nominal' => 'Nominal',
            'percentage' => 'Presentase',
            'transport_allowance' => 'Tunjangan Transportasi',
            'deduction_components' => 'Komponen Pengurangan',
            'attendace_deduction' => 'Potongan Kehadiran',
            'lateness_deduction' => 'Potongan Keterlambatan',
            'total_deduction' => 'Total Potongan',
            'total_addition' => 'Total Tambahan',
        ),
        'modal' => array(
            'delete' => array(
                'title' => 'Hapus <span id="selected">{selected_delete, number, integer}</span> Master Gaji Pokok dipilih',
                'confirm' => 'Anda yakin?',
                'submit' => 'Hapus',
                'back' => 'Kembali',
            ),
            'delete_all' => array(
                'title' => 'Hapus <span id="selected">{selected_delete, number, integer}</span> Master Gaji Pokok dipilih',
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