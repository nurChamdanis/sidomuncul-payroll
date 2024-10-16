<?php

namespace App\Models\Shared;

use CodeIgniter\Model;

class OptionsModel extends Model
{
    protected $perPage = 10;
    protected $pagination = true;
    protected $placeholder = 'Select Data';
    protected $table = '';
    protected $fieldsSelect = '';
    protected $joins = [];
    protected $dependOn = [];
    protected $fieldsSearch = [];
    protected $fieldsUsed = [];
    protected $options = [];
    protected $results = [];
    protected $resultsCount = 0;
    protected $formatText = 'default';
    protected $distinct = '';
    protected $groupBy = '';
    protected $showPlaceholder = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function options($options = [])
    {
        $this->options = $options;

        if (array_key_exists('placeholder', $options)) {
            $this->placeholder = $options['placeholder'];	
        }

        return $this;
    }
    
    public function data()
    {
        if (!empty($this->table)) {
            $this->results = $this->query($this->pagination)->get()->getResult();
            $this->resultsCount = $this->query(false)->countAllResults();
        }

        return [
            'results' => $this->mapOptions(),
            'pagination' => [
                'more' => ($this->pagination) ? (($this->options['page'] * $this->perPage) < $this->resultsCount) : false
            ],
        ];
    }

    public function query($hasLimit = true)
    {
        $offset = $this->options['page'] * $this->perPage - $this->perPage;

        $query = $this->db;
        $query = $query->table($this->table);
        $query = $query->select((!empty($this->fieldsSelect) ? $this->fieldsSelect : implode(",", $this->fieldsUsed)));
        if(!empty($this->distinct)){
            $query = $query->distinct($this->distinct);
        }
        $query = $this->joins($query);
        $query = $this->wheres($query);

        if ($hasLimit) {
            $query = $query->limit($this->perPage, $offset);
        }

        if(!empty($this->groupBy)){
            $query = $query->groupBy($this->groupBy);
        }
        
        return $query; 
    }

    public function joins($query)
    {
        if (!empty($this->joins)) {
            if ($this->isMultidimensional($this->joins)) {
                foreach ($this->joins as $join) {
                    $query = $query->join($join['table'], $join['on'], $join['type']);
                }
            } else {
                $query = $query->join($this->joins['table'], $this->joins['on'], $this->joins['type']);
            }
        }

        return $query;
    }

    public function wheres($query)
    {
        if (!empty($this->fieldsSearch)) {
            $sql = '(1 = 1';
            $i = 0;
            foreach ($this->fieldsSearch as $field) {
                if (!empty($this->options['search'])) {
                    if ($i == 0) {
                        $sql .= ' AND ';
                    }
                    $sql .= "$field LIKE '%" . $this->options['search'] . "%'";
                    if (count($this->fieldsSearch) > 1 && $i != count($this->fieldsSearch) - 1) {
                        $sql .= " OR ";
                    }
                    $i++;
                }
            }
            $sql .= ')';
            $query = $query->where($sql);
        }

        if (!empty($this->dependOn)) {
            foreach ($this->dependOn as $value) {
                if (!empty($this->options[$value])) {
                    $query = $query->where($value, $this->options[$value]);
                }
            }
        }
        
        $query = $this->where($query);

        return $query;
    }

    public function where($query)
    {
        return $query;
    }

    public function mapOptions()
    {
        $results = !empty($this->results) ? $this->results : [];
        $fields = !empty($this->fieldsUsed) ? $this->fieldsUsed : [];
        $page = array_key_exists("page",$this->options) ? $this->options["page"] : 1;
        $search = array_key_exists("search",$this->options) ? $this->options["search"] : '';

        $data = [];
        if ($page == 1 && empty($search)) {
            if($this->showPlaceholder === true){
                $data[0] = ['id' => '-', 'text' => $this->placeholder];
            }
        }

        if (!empty($results)) {
            foreach ($results as $r) {
                $option = ['id' => $r->{$fields[0]}, 'text' => $this->textFormat($r->{$fields[1]})];
                if (array_key_exists(2, $fields)) {
                    $option = ['id' => $r->{$fields[0]}, 'text' => $this->textFormat($r->{$fields[1]}) . ' - ' . $this->textFormat($r->{$fields[2]})];
                }

                if (!empty($selected_id)) {
                    if ($selected_id == $r->{$fields[0]}) {
                        $option['selected'] = true; 
                    }
                }
                $data[] = $option;
            }
        }

        return $data;
    }

    public function textFormat($word)
    {
        switch (strtolower($this->formatText)) {
            case 'capitalize':
                return ucwords(strtolower($word));
            case 'uppercase':
                return strtoupper($word);
            case 'lowercase':
                return strtolower($word);
            default:
                return $word;
        }
    }

    public function isMultidimensional(array $array)
    {
        return count($array) !== count($array, COUNT_RECURSIVE);
    }
}
