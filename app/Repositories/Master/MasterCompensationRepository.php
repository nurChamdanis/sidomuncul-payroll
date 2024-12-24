<?php

namespace App\Repositories\Master;

/**
 * @author fernanda.rizqi@arkamaya.co.id
 * @since May 2024
 */

use App\Models\Shared\OptionsAreaModel;
use App\Models\Shared\OptionsCompanyModel;
use App\Models\Shared\OptionsRoleModel;
use App\Models\Shared\OptionsEmployeeModel;
use App\Models\Master\CompensationTypeOptionsModel;
use App\Repositories\BaseRepository;
use App\Repositories\Shared\Select2Repository;
use stdClass;
use DateTime;

class MasterCompensationRepository extends BaseRepository
{
    protected $table = 'tb_r_payroll_compensation';
    protected $modelAreaOptions;
    protected $modelCompanyOptions;
    protected $modelCompensationTypeOptions;
    protected $modelEmployeeOptions;
    protected $modelRoleOptions;
    protected $modifyTableAndCondition = true;

    public function __construct()
    {
        parent::__construct();
        $this->modelAreaOptions = new OptionsAreaModel();
        $this->modelCompanyOptions = new OptionsCompanyModel();
        $this->modelCompensationTypeOptions = new CompensationTypeOptionsModel();
        $this->modelEmployeeOptions = new OptionsEmployeeModel();
        $this->modelRoleOptions = new OptionsRoleModel();
    }

    public function setCustomTable()
    {
        $query = $this->db->table($this->table);
        $query->select('tb_r_payroll_compensation.*, tb_m_company.company_name, tb_m_work_unit.name, tb_m_role.role_name, tb_m_employee.no_reg, tb_m_employee.role_id, tb_m_employee.employee_name, tb_m_system_payroll.system_type, tb_m_system_payroll.system_value_txt');
        $query->join('tb_m_company', 'tb_m_company.company_id = tb_r_payroll_compensation.company_id');
        $query->join('tb_m_work_unit', 'tb_m_work_unit.work_unit_id = tb_r_payroll_compensation.work_unit_id');
        $query->join('tb_m_employee', 'tb_m_employee.employee_id = tb_r_payroll_compensation.employee_id');
        $query->join('tb_m_role', 'tb_m_role.role_id = tb_m_employee.role_id');
        $query->join('tb_m_system_payroll', 'tb_m_system_payroll.system_code = tb_r_payroll_compensation.compensation_type');
        $this->customTable = "({$query->getCompiledSelect()}) master_compensation";
    }

    public function where($query, $filters)
    {
        $query->where("`system_type` = 'compensation_type'");
        if (!empty($filters)) {
            $validFrom = null;
            $validTo = null;

            // echo '<pre>';
            // print_r($filters);
            // die();

            foreach ($filters as $key => $value) {

                if (!empty($value) && $key != 'valid_from' && $key != 'valid_to') {
                    // karena keynya masih menggunakan tb_r_payroll_compensation
                    $keyParts = explode('.', $key);
                    $extractedKey = end($keyParts);
                    $query->where('master_compensation' . "." . $extractedKey, $this->db->escapeString($value));
                }
                if ($key == 'valid_from') {
                    $validFrom = $value;
                }
                if ($key == 'valid_to') {
                    $validTo = $value;
                }
            }

            // echo '<pre>';
            // print_r($validFrom);
            // die();

            if (!empty($validFrom) && !empty($validTo)) {
                $query->where("period BETWEEN '$validFrom' and '$validTo'");
            } else {
                if (!empty($validFrom) && empty($validTo)) {
                    $query->where("DATE_FORMAT(STR_TO_DATE(period, '%m/%Y'), '%Y-%m') >= DATE_FORMAT(STR_TO_DATE('$validFrom', '%m/%Y'),'%Y-%m')");
                }
                if (!empty($validTo) && empty($validFrom)) {
                    $query->where("DATE_FORMAT(STR_TO_DATE(period, '%m/%Y'), '%Y-%m') <= DATE_FORMAT(STR_TO_DATE('$validTo', '%m/%Y'),'%Y-%m')");
                }
            }
        }

        // echo $query->getCompiledSelect();
        // exit();
    }


    /**
     * @var int $page
     * @var string $search
     * @return array $data
     * ----------------------------------------------------
     * name: getOptions(page, search)
     * desc: Retrieving all data for options select
     */

    public function getOptionsArea(int $page, string $search): array
    {
        return (new Select2Repository($this->modelAreaOptions))
            ->groupBy('work_unit_id')
            ->getOptions(array(
                'page' => $page,
                'search' => $search
            ));
    }

    public function getOptionsCompany(int $page, string $search): array
    {
        return (new Select2Repository($this->modelCompanyOptions))
            ->getOptions(array(
                'page' => $page,
                'search' => $search
            ));
    }

    public function getOptionsCompensationType(int $page, string $search): array
    {
        return (new Select2Repository($this->modelCompensationTypeOptions))
            ->getOptions(array(
                'page' => $page,
                'search' => $search
            ));
    }

    public function getOptionsEmployee(int $page, string $search): array
    {
        return (new Select2Repository($this->modelEmployeeOptions))
            ->getOptions(array(
                'page' => $page,
                'search' => $search
            ));
    }

    public function getOptionsRole(int $page, string $search): array
    {
        return (new Select2Repository($this->modelRoleOptions))
            ->getOptions(array(
                'page' => $page,
                'search' => $search
            ));
    }

    /**
     * @var string $compensation_id
     * ----------------------------------------------------
     * name: isExists(compensation_id)
     * desc: Checking data exists or not
     */
    public function isExists(string $compensation_id): int
    {
        $queryBuilder = $this->db->table($this->table);

        $queryBuilder->where([
            'compensation_id' => $compensation_id,
        ]);

        return $queryBuilder->countAllResults();
    }

    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findByOtherKey(filters)
     * desc: Retrieving data with custom condition
     */
    public function findByOtherKey(array $filters): stdClass
    {
        // data_dump($filters);
        // die();
        $query = $this->db->table($this->table);
        $query->select("{$this->table}.*, tb_m_company.company_name, tb_m_company.company_code, tb_m_work_unit.name, 
        tb_m_role.role_name, tb_m_employee.no_reg, tb_m_employee.role_id, 
        tb_m_employee.employee_name, tb_m_system_payroll.system_type, tb_m_system_payroll.system_value_txt, 
        tb_m_system_payroll.system_code");
        $query->join('tb_m_company', 'tb_m_company.company_id = tb_r_payroll_compensation.company_id');
        $query->join('tb_m_work_unit', 'tb_m_work_unit.work_unit_id = tb_r_payroll_compensation.work_unit_id');
        $query->join('tb_m_employee', 'tb_m_employee.employee_id = tb_r_payroll_compensation.employee_id');
        $query->join('tb_m_role', 'tb_m_role.role_id = tb_m_employee.role_id');
        $query->join('tb_m_system_payroll', 'tb_m_system_payroll.system_code = tb_r_payroll_compensation.compensation_type and tb_m_system_payroll.system_type = "compensation_type" ');

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get()->getRow();
    }

    public function compensationCheck(string $employee_id, string $compensation_type, string $period) : int
    {
        $queryBuilder = $this->db->table($this->table);

        $queryBuilder->where([
            'employee_id' => $employee_id,
            'compensation_type' => $compensation_type,
            'period'  => $period
        ]);

        return $queryBuilder->countAllResults();
    }  

    public function compensationRow(string $employee_id, string $compensation_type, string $period) :stdClass
    {
        $queryBuilder = $this->db->table($this->table);

        $queryBuilder->where([
            'employee_id' => $employee_id,
            'compensation_type' => $compensation_type,
            'period'  => $period
        ]);

        return $queryBuilder->get()->getRow();
    }  


    // this function if use to get a name from a table from a foreign_key id
    // this function use table name, and foreign_key id as parameter
    // the 0utput of this function is a name from the table
    public function getNameFromTable(string $table_name, int $id, string $columnName, string $foreignKeyName) : string
    {
        $queryBuilder = $this->db->table($table_name);
        $queryBuilder->select($columnName);
        $queryBuilder->where($foreignKeyName, $id);
        return $queryBuilder->get()->getRow()->name;
    }

   
}
