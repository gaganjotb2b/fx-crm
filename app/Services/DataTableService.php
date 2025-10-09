<?php 

namespace App\Services;

class DataTableService {

    public $data;
    public $request;
    public $draw,$start,$length,$search,$order,$orderDir;

    public function __construct($request) 
    {
        $this->request = $request;
        $this->data = $request->all();

        $this->draw = $this->data['draw'];
        $this->start = $this->data['start'];
        $this->length = $this->data['length'];
        $this->search =  $this->data['search']['value'];
        $this->order = $this->data['order'][0]["column"];
        $this->orderDir = $this->data["order"][0]["dir"];
    }

    public function get_param($param){
        return $this->request->input($param, false);
    }

    public function get_columns(){
        return $this->data['columns'];
    }

    public function orderBy(){
        $columns = $this->get_columns();
        $sc = array_filter($columns, function($v, $k) {
            return $k == $this->order;
        }, ARRAY_FILTER_USE_BOTH);
        return isset($sc[$this->order]['data']) ? $sc[$this->order]['data'] : false;
    }
}