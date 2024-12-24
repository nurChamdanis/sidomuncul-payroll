<?php

namespace App\Repositories\Shared;

use App\Models\Shared\OptionsModel;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */
class Select2Repository
{
    protected $optionsModel;

    public function __construct(OptionsModel $optionsModel)
    {
        $this->optionsModel = $optionsModel;
    }

    public function getOptions($options = [])
    {
        return $this->optionsModel->options($options)->data();
    }

    public function groupBy($column)
    {
        $this->optionsModel->options(['group_by' => $column]);
        return $this;
    }
}
