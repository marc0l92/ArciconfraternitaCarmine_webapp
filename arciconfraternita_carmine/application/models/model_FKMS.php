<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_FKMS extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    // verifica se il $value è già presente nella tabella $table alla colonna $column
    public function search_duplicate($table, $where, $not_where = array()){
        $query_str = "SELECT * FROM ".$table." WHERE 1 ";
        foreach ($where as $key => $value) {
            if($value == '#'){
                $query_str .= ' AND '.$key.' ';
            }else{
                $query_str .= ' AND '.$key.' = '.$this->db->escape($value);
            }
        }
        foreach ($not_where as $key => $value) {
            if($value == '#'){
                $query_str .= ' AND '.$key.' ';
            }else{
                $query_str .= ' AND '.$key.' != '.$this->db->escape($value);
            }
        }
        $result = $this->db->query($query_str);
        $result = $result->result_array();
        if(isset($result[0])){
            $result[0]['query'] = $query_str; // debug porpouses
            return $result[0];
        }else{
            return false;
        }
    }

    //estrai dei campi da una tabella attraveso un filtro where
    public function where_to_field($fields, $table, $where){
        $query_str = 'SELECT '.$fields.' FROM '.$table.' WHERE '.$where;
        $result = $this->db->query($query_str);
        $result = $result->result_array();
        return $result;
    }

}