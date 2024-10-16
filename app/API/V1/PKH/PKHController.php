<?php

namespace App\API\V1\PKH;

use App\API\V1\APIController;

class PKHController extends APIController{
    public function registerFromRecruitment(){
        return $this->responseSuccess(array('hello' => 'world'));
    }
}