<?php

namespace App\Services\Master\PKH;

use App\Repositories\Master\PKH\PKHProductAreaRepository;
use App\Repositories\Master\PKH\PKHProductRepository;
use App\Services\BaseService;

class PKHProductService extends BaseService
{
    protected mixed $pkh_productRepository; 
    protected mixed $pkh_productRepositoryArea;

 
    public function __construct()
    {
        parent::__construct();
        $this->pkh_productRepository = new PKHProductRepository();
        $this->pkh_productRepositoryArea = new PKHProductAreaRepository();
    }

    /**
     * @var array $payload
     * @return array
     * -----------------------------------------------------
     * name: create($payload)
     * desc: Service to create new allowance data
     */
    public function create(?array $payload): array
    {
        $this->serviceAction = '[MASTER_PKHPRODUCT][CREATE]';

        $repository = $this;
        $error = null;

        $result = queryTransaction(
            function () use ($payload, $repository) {
                if ($this->pkh_productRepository->checkDataExists(array(
                    'company_id' => $payload['company_id'],
                    'allowance_code' => $payload['allowance_code']
                )) > 0) {
                    throw new \Exception('Data already exists in our system, please send another request.');
                }

                /**
                 * PKH Product
                 */
                $pkh_product = array();
                $pkh_product['company_id'] = $payload['company_id'];
                $pkh_product['work_unit_id'] = $payload['allowance_code'];
                $pkh_product['role_id'] = $payload['allowance_name'];
                $pkh_product['product_name'] = $payload['gl_id'];
                $pkh_product['product_name'] = $payload['gl_id']; 
                $pkh_product['effective_date'] = std_date($payload['effective_date']);
                $pkh_product['effective_date_end'] = '2999-12-31';
                //$pkh_product['is_active'] = isset($payload['is_active']) ? '1' : '0';
                $pkh_product['created_by'] = $this->S_NO_REG;
                $pkh_product['created_dt'] = date('Y-m-d H:i:s');
                $allowanceResult = $repository->pkh_productRepository->save($pkh_product);

                /**
                 * Allowance Area
                 */
                if (!empty($payload['area'])) :
                    $i = 0;
                    $allowanceArea = array();
                    foreach ($payload['area'] as $area) {
                        $allowanceArea[$i]['allowance_id'] = $allowanceResult['id'];
                        $allowanceArea[$i]['area_type'] = '0';
                        $allowanceArea[$i]['area_id'] = $area;
                        $allowanceArea[$i]['created_by'] = $this->S_NO_REG;
                        $allowanceArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                        $allowanceArea[$i]['changed_by'] = $this->S_NO_REG;
                        $allowanceArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                        $i++;
                    }
                    $repository->pkh_productRepositoryArea->insertBatch($allowanceArea);
                endif;

                /**
                 * Allowance Area Grup
                 */
                if (!empty($payload['areagrup'])) :
                    $i = 0;
                    $allowanceArea = array();
                    foreach ($payload['areagrup'] as $area) {
                        $allowanceArea[$i]['allowance_id'] = $allowanceResult['id'];
                        $allowanceArea[$i]['area_type'] = '1';
                        $allowanceArea[$i]['area_id'] = $area;
                        $allowanceArea[$i]['created_by'] = $this->S_NO_REG;
                        $allowanceArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                        $allowanceArea[$i]['changed_by'] = $this->S_NO_REG;
                        $allowanceArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                        $i++;
                    }
                    $repository->pkh_productRepositoryArea->insertBatch($allowanceArea);
                endif;
 
                return $allowanceResult;
            },
            $error
        );

        if ($result === false) {
            return $this->dataError(
                log: true,
                code: 500,
                data: null,
                message: $error,
            );
        } 

        return $this->dataSuccess(
            log: true,
            code: 201,
            data: array('redirect_link' => 'pkh_product/id/' . $result['id']),
            message: 'Successfully Created Master Allowances',
        );
    }
}