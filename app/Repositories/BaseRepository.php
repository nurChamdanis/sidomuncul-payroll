<?php

namespace App\Repositories;

use App\Libraries\EncryptionLib;
use App\Repositories\Shared\Select2Repository;
use stdClass;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

class BaseRepository{
    protected $db;
    protected $table;
    protected $customTable;
    protected $model;
    protected $optionsFilter = array();
    protected $modifyTableAndCondition = false;

    protected string $secretKey;
    /**
     * initialize the repository
     * set db property
     */
    public function __construct()
    {
		$this->db = \Config\Database::connect();

        $this->setCustomTable();
    }

    public function getCustomTable(){
        return $this->customTable;
    }

    /**
     * @return db instance
     * ----------------------------------------------------
     * name: getTable(filters)
     * desc: get table 
     */
    protected function getTable(){
        if(!empty($this->customTable))
        {
            return $this->db->table($this->customTable);
        } else {
            return $this->db->table($this->table);
        }
    }

    /**
     * @return custom table
     * ----------------------------------------------------
     * name: setCustomTable(filters)
     * desc: get custom table 
     */
    public function setCustomTable(){
        $this->customTable = '';
    }

    /**
     * @return query string
     * ----------------------------------------------------
     * name: where(query)
     * desc: set where
     */
    public function whereAccessData($query){}

    /**
     * @return query string
     * ----------------------------------------------------
     * name: where(query)
     * desc: set where
     */
    public function where($query, $filters){
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if(!empty($value)):
                    $query->where($key, $this->db->escapeString($value));
                endif;
            }
        }
    }

    /**
     * @return query string
     * ----------------------------------------------------
     * name: whereLike(query)
     * desc: set where like
     */
    public function whereLike($query, $likeFilters){
        if (!empty($likeFilters)) {
            $query->groupStart(); // Start a group for OR conditions
            foreach ($likeFilters as $key => $value) {
                if(!empty($value)):
                    $query->orLike($key, $value);
                else:
                    $query->where("1=1");
                endif;
            }
            $query->groupEnd(); // End the group
        }
    }

    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findAll(filters)
     * desc: Retrieving all data with customized parameters
     */
    public function findAll(array $filters = array())
    {
        $query = $this->getTable();

        if(!empty($filters)) {
            foreach ($filters as $key => $value) {
                if(!empty($value) || $value >= 0):
                    $query->where($key, $this->db->escapeString((string) $value));
                endif;
            }
        }

        return $query->get()->getResult();
    }
    

    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findAll(filters)
     * desc: Retrieving all data with customized parameters
     */
    public function findAllWithLikeFilters(array $filters = array(), array $likeFilters = array())
    {
        $query = $this->getTable();

        if(!empty($filters)) {
            foreach ($filters as $key => $value) {
                if(!empty($value) || $value >= 0):
                    $query->where($key, $this->db->escapeString((string) $value));
                endif;
            }
        }
    
        if (!empty($likeFilters)) {
            $query->groupStart(); // Start a group for OR conditions
            foreach ($likeFilters as $key => $value) {
                if(!empty($value)):
                    $query->orLike($key, $value);
                else:
                    $query->where("1=1");
                endif;
            }
            $query->groupEnd(); // End the group
        }

        return $query->get()->getResult();
    }
    
    /**
     * @var int $start
     * @var int $length
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findAllPaginate(start, length, filters, likeFilter, orderBy, orderValue)
     * desc: Retrieving all data with customized parameters ( paginated version )
     */
    public function findAllPaginate
    (
        int $start, 
        int $length = 25, 
        array $filters = [], 
        array $likeFilters = [], 
        string $orderBy = '', 
        string $orderValue = ''
    ) : array
    {
        $query = $this->getTable();
    
        if($this->modifyTableAndCondition === true)
        {
            $this->where($query, $filters);
            $this->whereLike($query, $likeFilters);
            $this->whereAccessData($query);
        } 
        else 
        {
            if (!empty($filters)) {
                foreach ($filters as $key => $value) {
                    if(!empty($value)):
                        $query->where($key, $this->db->escapeString($value));
                    endif;
                }
            }
    
            if (!empty($likeFilters)) {
                $query->groupStart(); // Start a group for OR conditions
                foreach ($likeFilters as $key => $value) {
                    if(!empty($value)):
                        $query->orLike($key, $value);
                    else:
                        $query->where("1=1");
                    endif;
                }
                $query->groupEnd(); // End the group
            }
        }
    
        $query->limit($length, $start);

        if(!empty($orderBy)){
            if(!empty($orderValue)){
                $query->orderBy($orderBy, $orderValue);
            } else {
                $query->orderBy($orderBy);
            }
        }
    
        return $query->get()->getResult();
    }

    /**
     * @param array $filters
     * @return int $totalRecords
     * ----------------------------------------------------
     * name : countTotalRecords(filters, likeFilters)
     * desc: Count total number of records in the table
     */
    public function countTotalRecords(array $filters = [], array $likeFilters = []) : int
    {
        $query = $this->getTable();

        if($this->modifyTableAndCondition === true)
        {
            if(!empty($filters)){
                $this->where($query, $filters);
            }
            
            if(!empty($likeFilters)){
                $this->whereLike($query, $likeFilters);
            }
            
            $this->whereAccessData($query);
        } 
        else 
        {
            if (!empty($filters)) {
                foreach ($filters as $key => $value) {
                    if(!empty($value)):
                        $query->where($key, $this->db->escapeString($value));
                    endif;
                }
            }
    
            if (!empty($likeFilters)) {
                $query->groupStart(); // Start a group for OR conditions
                foreach ($likeFilters as $key => $value) {
                    if(!empty($value)):
                        $query->orLike($key, $value);
                    else:
                        $query->where("1=1");
                    endif;
                }
                $query->groupEnd(); // End the group
            }
        }
        return $query->countAllResults();
    }

    /**
     * @param int $start
     * @param int $length
     * @param array $filters
     * @return int $filteredRecords
     * ----------------------------------------------------------------
     * name: countFilteredRecords(start, length, filters, likeFilters)
     * desc: Count number of filtered records in the table
     */
    public function countFilteredRecords(int $start, int $length, array $filters = [], array $likeFilters = []) : int
    {
        $query = $this->getTable();

        if($this->modifyTableAndCondition === true)
        {
            if(!empty($filters)){
                $this->where($query, $filters);
            }

            if(!empty($likeFilters)){
                $this->whereLike($query, $likeFilters);
            }
            
            $this->whereAccessData($query);
        } 
        else 
        {
            if (!empty($filters)) {
                foreach ($filters as $key => $value) {
                    if(!empty($value)):
                        $query->where($key, $this->db->escapeString($value));
                    endif;
                }
            }
    
            if (!empty($likeFilters)) {
                $query->groupStart(); // Start a group for OR conditions
                foreach ($likeFilters as $key => $value) {
                    if(!empty($value)):
                        $query->orLike($key, $value);
                    else:
                        $query->where("1=1");
                    endif;
                }
                $query->groupEnd(); // End the group
            }
        }

        $query->limit($length, $start);

        return $query->countAllResults();
    }

    
    /**
     * @param int $start
     * @param int $length
     * @param array $filters
     * @return array $filteredRecords
     * ----------------------------------------------------------------
     * name: countFilteredRecords(start, length, filters, likeFilters)
     * desc: Count number of filtered records in the table
     */
    public function findAllFilteredRecords(array $filters = [], array $likeFilters = [], $selectFields = array()) : array
    {
        $query = $this->getTable();

        if(!empty($selectFields)){
            $query->select($selectFields);
        }

        if($this->modifyTableAndCondition === true)
        {
            if(!empty($filters)){
                $this->where($query, $filters);
            }

            if(!empty($likeFilters)){
                $this->whereLike($query, $likeFilters);
            }
        } 
        else 
        {
            if (!empty($filters)) {
                foreach ($filters as $key => $value) {
                    if(!empty($value)):
                        $query->where($key, $this->db->escapeString($value));
                    endif;
                }
            }
    
            if (!empty($likeFilters)) {
                $query->groupStart(); // Start a group for OR conditions
                foreach ($likeFilters as $key => $value) {
                    if(!empty($value)):
                        $query->orLike($key, $value);
                    else:
                        $query->where("1=1");
                    endif;
                }
                $query->groupEnd(); // End the group
            }
        }
        
        if(!empty($orderBy)){
            if(!empty($orderValue)){
                $query->orderBy($orderBy, $orderValue);
            } else {
                $query->orderBy($orderBy);
            }
        }

        return array_map('array_values', $query->get()->getResultArray());
    }


    /**
     * @var int|string $id
     * @return array $data
     * ----------------------------------------------------
     * name: findById(id)
     * desc: Retrieving data with id condition
     */
    public function findById(string|int $id) : stdClass
    {
        return $this->db->table($this->table)->where('id', $id)->get()->getRow();
    }
    
    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findByOtherKey(filters)
     * desc: Retrieving data with custom condition
     */
    public function findByOtherKey(array $filters) : ?stdClass
    {
        $query = $this->db->table($this->table);

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get()->getRow();
    }

    public function insertBatch($data){
        $inserts = array();
        foreach ($data as $dataKey => $value) {
            foreach ($value as $valueKey => $value) {
                if(!empty($value)){
                    $inserts[$dataKey][$valueKey] = $this->db->escapeString((string) $value);
                } else  {
                    $inserts[$dataKey][$valueKey] = $value;
                }
            }
        }

        try {
            return $this->db->table($this->table)->insertBatch($inserts);
        } catch (\Throwable $th) {
            throw new \Exception('No rows affected by insert batch.');
        }
    }

    /**
     * @var array $data
     * @var array $updateConditions
     * @return array $data
     * ----------------------------------------------------
     * name: save(data, updateConditions)
     * desc: create (if id not exists) or update data (if id exists)
     */
    public function save(array $data, array $updateConditions = []) : array
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->db->escapeString($value);
        }
        
        try {
            if (!empty($updateConditions)) {
                // Update existing record using custom conditions
                $sql = "UPDATE {$this->table} SET ";
                $fields = [];
                foreach ($data as $key => $value) {
                    if ($key !== 'id') {
                        $fields[] = "$key = ?";
                    }
                }
                $sql .= implode(', ', $fields);
                $sql .= " WHERE ";
                $conditionClauses = [];
                foreach ($updateConditions as $condition => $value) {
                    $conditionClauses[] = "$condition = ?";
                }
                $sql .= implode(' AND ', $conditionClauses);
        
                $params = array_merge(array_values($data), array_values($updateConditions));
        
                $query = $this->db->query($sql, $params);
                if ($query) {
                    return $data; // Return updated data
                } else {
                    throw new \Exception('No rows affected by update query.');
                }
            } else {
                // Insert new record if id is not provided
                $sql = "INSERT INTO {$this->table} (";
                $sql .= implode(', ', array_keys($data));
                $sql .= ") VALUES (";
                $placeholders = rtrim(str_repeat('?, ', count($data)), ', ');
                $sql .= $placeholders;
                $sql .= ")";
    
                $params = array_values($data);
    
                $query = $this->db->query($sql, $params);

                if ($query) {
                    $lastInsertID = $this->db->insertID();
                    if(!empty($lastInsertID)){
                        return array_merge($data, array("id" => $lastInsertID));
                    }
                    return $data; // Return inserted data
                } else {
                    throw new \Exception('No rows affected by insert query.');
                }
            }
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Database operation failed: ' . $e->getMessage());
        }
    }

    /**
     * @var array $data
     * @return array $data
     * ----------------------------------------------------
     * name: create(data)
     * desc: create data
     */
    public function create(array $data) : array
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->db->escapeString($value);
        }

        $sql = "INSERT INTO {$this->table} (";
        $sql .= implode(', ', array_keys($data));
        $sql .= ") VALUES (";
        $placeholders = rtrim(str_repeat('?, ', count($data)), ', ');
        $sql .= $placeholders;
        $sql .= ")";
        
        $params = array_values($data);

        $query = $this->db->query($sql, $params);
        if ($query) {
            $lastInsertID = $this->db->insertID();
            if(!empty($lastInsertID)){
                return array_merge($data, array("id" => $lastInsertID));
            }
            return $data; // Return inserted data
        } else {
            throw new \Exception('No rows affected by insert query.');
        }

        throw new \Exception('Failed to execute');
    }

    /**
     * @var array $data
     * @var array $updateConditions
     * @return array $data
     * ----------------------------------------------------
     * name: update(data, updateConditions)
     * desc: update data
     */
    public function update(array $data, array $updateConditions = []) : array
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->db->escapeString($value);
        }

        foreach ($updateConditions as $key => $value) {
            $updateConditions[$key] = $this->db->escapeString($value);
        }

        if (!empty($updateConditions)) {
            try {
                // Update existing record using custom conditions
                $sql = "UPDATE {$this->table} SET ";
                $fields = [];
                foreach ($data as $key => $value) {
                    if ($key !== 'id') {
                        $fields[] = "$key = ?";
                    }
                }
                $sql .= implode(', ', $fields);
                $sql .= " WHERE ";
                $conditionClauses = [];
                foreach ($updateConditions as $condition => $value) {
                    $conditionClauses[] = "$condition = ?";
                }
                $sql .= implode(' AND ', $conditionClauses);

                $params = array_merge(array_values($data), array_values($updateConditions));
                
                $query = $this->db->query($sql, $params);
                
                if ($query) {
                    return $data; // Return update data
                } else {
                    throw new \Exception('No rows affected by update query.');
                }
            } catch (\Throwable $th) {
                //throw $th;
                throw new \Exception($th->getMessage());
            }
        }

        throw new \Exception('Failed to execute');
    }

    /**
     * @var array|string|int $id
     * @return boolean false|true
     * ----------------------------------------------------
     * name: delete(id)
     * desc: delete data
     */
    public function delete(array|string|int $id) : bool
    {
        if (is_array($id)) 
        {
            return $this->db->table($this->table)->where($id)->delete();
        } 
        else 
        {
            $id = $this->db->escapeString($id);
            return $this->db->table($this->table)->where('id', $id)->delete();
        }
    }
    
    /**
     * @var string $id
     * @return boolean false|true
     * ----------------------------------------------------
     * name: deleteByCondition(id)
     * desc: delete data
     */
    public function deleteByCondition(string $condition) : bool
    {
        return $this->db->table($this->table)->where($condition)->delete();
    }
    
    /**
     * @var int $page
     * @var string $search
     * @return array $data
     * ----------------------------------------------------
     * name: getOptions(page, search)
     * desc: Retrieving all data for options select
     */
    public function getOptions(array $params) : array
    {
        $page = isset($params['page']) ? $params['page'] : 1;
        $search = isset($params['search']) ? $params['search'] : ''; 

        $newParams = array();
        if(!empty($this->optionsFilter)){
            foreach ($this->optionsFilter as $key => $value) {
                if(array_key_exists($value, $params)){
                    $newParams[$value] = $params[$value];
                }
            }
        }

        return (new Select2Repository($this->model))
        ->getOptions(array_merge(array(
            'page' => $page,
            'search' => $search
        ), $newParams));
    }
    
    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findByOtherKey(filters)
     * desc: Retrieving data with custom condition
     */
    public function findByCustomTable(array $filters) : stdClass
    {
        $query = $this->getTable();

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get()->getRow();
    }
    
    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findByOtherKey(filters)
     * desc: Retrieving data with custom condition
     */
    public function countAllResults(array $filters) : int
    {
        $query = $this->getTable();

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        return $query->countAllResults();
    }
    
    /**
     * @var array $payload
     * @return array $data
     * ----------------------------------------------------
     * name: checkDataExists(payload)
     * desc: Check If Data Exists
     */
    public function checkDataExists(array $payload = array(), array $ignoreKey = array())
    {
         // Start building the query
        $query = $this->db->table($this->table);

        foreach ($payload as $key => $value) {
            // Add the condition to ignore the specified key-value pair
            $query->where($key, $value);
        }

        // If ignoreKey is not empty, add the ignore conditions
        if (!empty($ignoreKey)) {
            $query->groupStart();
            foreach ($ignoreKey as $key => $value) {
                // Add the condition to ignore the specified key-value pair
                $query->where("$key !=", $value);
            }
            $query->groupEnd(); // End the group
        } 

        // Execute the query and return the count of results
        return $query->countAllResults();
    }
}