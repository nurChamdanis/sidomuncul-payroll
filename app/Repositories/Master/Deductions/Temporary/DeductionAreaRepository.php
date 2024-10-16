<?php

namespace App\Repositories\Master\Deductions\Temporary;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class DeductionAreaRepository extends BaseRepository{
    protected $table = 'tb_t_payroll_deductions_area';

    public function __construct()
    {
        parent::__construct();
    }

    public function importExcelArea($process_id)
    {
        $sql = "DELETE FROM tb_m_payroll_deductions_area WHERE deduction_id IN (
                    SELECT 
                        B.deduction_id                   
                    FROM tb_t_payroll_deductions A
                    JOIN tb_m_payroll_deductions B ON B.process_id = CONCAT(A.process_id,A.transaction_id)
                    WHERE A.process_id = '{$process_id}' AND B.deduction_id IS NOT NULL AND B.is_active = '1'
                ) AND deduction_id IS NOT NULL AND area_type = '0'";
        
        $this->db->query($sql);

        $sql = "INSERT INTO tb_m_payroll_deductions_area (
                    deduction_id,
                    area_type,
                    area_id,
                    created_by,
                    created_dt,
                    changed_by,
                    changed_dt
                )
                SELECT 
                    B.deduction_id,
                    A.area_type,
                    A.area_id,
                    A.created_by,
                    A.created_dt,
                    A.changed_by,
                    A.changed_dt                    
                FROM tb_t_payroll_deductions_area A
                JOIN tb_m_payroll_deductions B ON B.process_id = CONCAT(A.process_id,A.transaction_id)
                WHERE A.area_type = '0' AND A.process_id = '{$process_id}' AND B.is_active = '1'
            ";
        $this->db->query($sql);
    }
    
    public function importExcelGrup($process_id)
    {
        $sql = "DELETE FROM tb_m_payroll_deductions_area WHERE deduction_id IN (
                    SELECT 
                        B.deduction_id                   
                    FROM tb_t_payroll_deductions A
                    JOIN tb_m_payroll_deductions B ON B.process_id = CONCAT(A.process_id,A.transaction_id)
                    WHERE A.process_id = '{$process_id}' AND B.deduction_id IS NOT NULL AND B.is_active = '1'
                ) AND deduction_id IS NOT NULL AND area_type = '1'";
        $this->db->query($sql);

        $sql = "INSERT INTO tb_m_payroll_deductions_area (
                deduction_id,
                area_type,
                area_id,
                created_by,
                created_dt,
                changed_by,
                changed_dt
            )
            SELECT 
                B.deduction_id,
                A.area_type,
                A.area_id,
                A.created_by,
                A.created_dt,
                A.changed_by,
                A.changed_dt                    
            FROM tb_t_payroll_deductions_area A
            JOIN tb_m_payroll_deductions B ON B.process_id = CONCAT(A.process_id,A.transaction_id)
            WHERE A.area_type = '1' AND A.process_id = '{$process_id}' AND B.is_active = '1'
        ";
        $this->db->query($sql);
    }
}