<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_update extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function getSetting($name){
        $result = $this->db->query('SELECT value FROM settings WHERE name LIKE '.$this->db->escape($name).' AND enabled = 1');
        return $result->result_array()[0]['value'];
    }

    function setSetting($name, $value){
        $where = "name LIKE ".$this->db->escape($name);
        $str = $this->db->update_string('settings', array('value' => $value), $where);
        $this->db->query($str);
    }

    function updateQuery($query_str){
        $this->db->query($query_str);
    }
}