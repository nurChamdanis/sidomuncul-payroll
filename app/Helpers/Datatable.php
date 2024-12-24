<?php

namespace App\Helpers;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

class Datatable {
    protected string|int $draw = 0;
    protected string|int $start = 0;
    protected string|int $length = 25;
    protected string $orderBy = '';
    protected string $orderValue = '';
    protected array $filters = array();
    protected array $likeFilters = array();

    protected mixed $repository;
    protected ?array $params;

    public function __construct($repository, ?array $params)
    {
        $this->draw     = isset($params['draw']) ? $params['draw'] : 0;
        $this->start    = isset($params['start']) ? $params['start'] : 0  ; 
        $this->length   = isset($params['length']) ? $params['length'] : 25;
        
        $this->repository = $repository;
    }
    
    public function setFilters(callable $callback) : Datatable
    {
        if (is_callable($callback)) {
            $this->filters = $callback() ?: [];
        }
        return $this;
    }

    public function setFiltersLike(callable $callback) : Datatable
    {
        if (is_callable($callback)) {
            $this->likeFilters = $callback() ?: [];
        }
        return $this;
    }

    public function setOrderBy($orderBy){
        $this->orderBy = $orderBy;
        return $this;
    }
    
    public function setOrderValue($orderValue){
        $this->orderValue = $orderValue;
        return $this;
    }

    public function getRows(callable $callback) : array
    {
        $records = array();
        $countFiltered = 0;
        $totalRecords = 0;
        if(is_callable($callback))
        {
            if(!empty($this->orderBy)){
                if(!empty($this->orderValue)){
                    $records = $callback($this->repository->findAllPaginate($this->start, $this->length, $this->filters, $this->likeFilters, $this->orderBy, $this->orderValue));
                    $countFiltered = $this->repository->countFilteredRecords($this->start, $this->length, $this->filters, $this->likeFilters, $this->orderBy, $this->orderValue);
                } else {
                    $records = $callback($this->repository->findAllPaginate($this->start, $this->length, $this->filters, $this->likeFilters, $this->orderBy));
                    $countFiltered = $this->repository->countFilteredRecords($this->start, $this->length, $this->filters, $this->likeFilters, $this->orderBy);
                }
            } else {
                $records = $callback($this->repository->findAllPaginate($this->start, $this->length, $this->filters, $this->likeFilters));
                $countFiltered = $this->repository->countFilteredRecords($this->start, $this->length, $this->filters, $this->likeFilters);
            }
        } 
        else 
        {
            
            if(!empty($this->orderBy)){
                if(!empty($this->orderValue)){
                    $records = $this->repository->findAllPaginate($this->start, $this->length, $this->filters, $this->likeFilters, $this->orderBy, $this->orderValue);
                    $countFiltered = $this->repository->countFilteredRecords($this->start, $this->length, $this->filters, $this->likeFilters, $this->orderBy, $this->orderValue);
                } else {
                    $records = $this->repository->findAllPaginate($this->start, $this->length, $this->filters, $this->likeFilters, $this->orderBy);
                    $countFiltered = $this->repository->countFilteredRecords($this->start, $this->length, $this->filters, $this->likeFilters, $this->orderBy);
                }
            } else {
                $records = $this->repository->findAllPaginate($this->start, $this->length, $this->filters, $this->likeFilters);
                $countFiltered = $this->repository->countFilteredRecords($this->start, $this->length, $this->filters, $this->likeFilters);
            }
        }
        
        $totalRecords = $this->repository->countTotalRecords($this->filters, $this->likeFilters);

        if(!empty($records))
        {
            return array(
                'draw' => htmlspecialchars($this->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $countFiltered,
                'data' => $records
            );
        } else {
            return array(
                'draw' => 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            );
        }
    }
}