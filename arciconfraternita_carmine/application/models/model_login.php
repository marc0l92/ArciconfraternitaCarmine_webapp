<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_login extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function checkLogin($username, $password){
        $result = $this->db->query('SELECT * FROM utenti WHERE nomeutente LIKE '.$this->db->escape($username).' AND password LIKE '.$this->db->escape($password).'');
        $result = $result->result_array();
        if(isset($result[0])){
            return $result[0];
        }else{
            return false;
        }
    }

    function getUtenti(){
        $result = $this->db->query('SELECT id_utente, nomeutente FROM utenti WHERE enabled=1');
        $result = $result->result_array();
        return $result;
    }

    function deleteUser($id_utente = 0){
        $this->db->trans_start(); // da il via ad un transaction
        //$this->logRipristinoClear();
        // setto a 0 l'id dei suoi log
        $where = 'id_utente = '.$this->db->escape($id_utente);
        $str = $this->db->update_string('log_ripristino', array('id_utente' => 0), $where);
        //$this->logRipristinoAdd('log_ripristino', 'UPDATE', $where); // in questa query vengono potenzialmente modificate troppe righe, meglio non permettere di tornare indietro
        $this->db->query($str);
        // cancello l'utente
        //$this->logRipristinoAdd('utenti', 'INSERT', 'id_utente = '.$this->db->escape($id_utente)); // non posso creare un log dove l'utente che lo crea viene successivamente cancellato
        $this->db->query('DELETE FROM utenti WHERE id_utente = '.$this->db->escape($id_utente));
        
        //$this->logRipristinoExecute();
        $this->db->trans_complete(); // conclude la transaction
    }

    function modificaUtente($id_utente, $username, $password){
        $array = array('nomeutente' => strtoupper($username), 'password' => $password);
        if($id_utente == 0){
            $this->db->insert('utenti', $this->db->escape($array));
            $this->logRipristinoExecuteOne('utenti', 'DELETE', 'id_utente = '.$this->db->insert_id());
        }else{
            $where = "id_utente = ".$this->db->escape($id_utente);
            $str = $this->db->update_string('utenti', $array, $where);
            $this->logRipristinoExecuteOne('utenti', 'UPDATE', $where);
            $this->db->query($str);
        }
    }
}