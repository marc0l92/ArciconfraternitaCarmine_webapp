<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_altro extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function getStatistiche(){
        $array = array();
        $temp = array();
        //persone
        $temp['descrizione'] = 'Informazioni persone e confratelli';
        $temp['valore'] = '#';
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Numero di persone registrate nel database';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM persone WHERE id_persona != 0');
        $temp['valore'] = $result->row()->valore;
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Numero di confratelli registrati nel database';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM confratelli WHERE id_persona != 0');
        $temp['valore'] = $result->row()->valore;
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Codice più grande da confratello';
        $result = $this->db->query('SELECT MAX(codice) AS valore FROM confratelli');
        $temp['valore'] = $result->row()->valore;
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Confratelli professati nell\'anno corrente ['.date('Y').']';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM confratelli WHERE id_persona != 0 AND DATEDIFF(data_professione, \''.date('Y').'-01-01\')>=0 AND DATEDIFF(data_professione, \''.date('Y').'-12-31\')<=0');
        $temp['valore'] = $result->row()->valore;
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Numero confratelli maschi / femmine';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM confratelli_persone WHERE id_persona != 0 AND sesso = "M"');
        $temp['valore'] = $result->row()->valore;
        $temp['valore'] .= ' / ';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM confratelli_persone WHERE id_persona != 0 AND sesso = "F"');
        $temp['valore'] .= $result->row()->valore;
        $array[] = $temp;
        //defunti
        $temp['descrizione'] = 'Informazioni defunti';
        $temp['valore'] = '#';
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Numero di defunti registrati nel database';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM defunti WHERE id_defunto != 0');
        $temp['valore'] = $result->row()->valore;
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Defunti deceduti nell\'anno corrente ['.date('Y').']';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM defunti WHERE id_defunto != 0 AND DATEDIFF(data_decesso, \''.date('Y').'-01-01\')>=0 AND DATEDIFF(data_decesso, \''.date('Y').'-12-31\')<=0');
        $temp['valore'] = $result->row()->valore;
        $array[] = $temp;
        //cappelle
        $temp['descrizione'] = 'Informazioni cappelle';
        $temp['valore'] = '#';
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Numero di cappelle';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM cappelle WHERE id_cappella != 0');
        $temp['valore'] = $result->row()->valore;
        $array[] = $temp;
        //
        $result = $this->db->query('SELECT nome, COUNT(*) AS valore FROM cellette LEFT JOIN cappelle ON cellette.id_cappella = cappelle.id_cappella WHERE cellette.id_cappella != 0 AND id_celletta != 0 GROUP BY nome');
        $result = $result->result_array();
        foreach ($result as $key => $value) {
            $temp['valore'] = $value['valore'];
            $temp['descrizione'] = 'Cellette nella cappella "'.$value['nome'].'"';
            $array[] = $temp;
        }
        //cellette
        $temp['descrizione'] = 'Informazioni cellette';
        $temp['valore'] = '#';
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Numero di cellette nel database';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM cellette WHERE id_celletta != 0');
        $temp['valore'] = $result->row()->valore;
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Numero cellette di tipo "celletta"';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM cellette WHERE id_celletta != 0 AND tipo = "celletta"');
        $temp['valore'] = $result->row()->valore;
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Numero cellette di tipo "sepoltura"';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM cellette WHERE id_celletta != 0 AND tipo = "sepoltura"');
        $temp['valore'] = $result->row()->valore;
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Numero cellette di tipo "pilone"';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM cellette WHERE id_celletta != 0 AND tipo = "pilone"');
        $temp['valore'] = $result->row()->valore;
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Numero cellette acquistate / non acquistate';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM cellette WHERE id_celletta != 0 AND id_acquirente IS NOT NULL AND id_acquirente <> 0');
        $temp['valore'] = $result->row()->valore;
        $temp['valore'] .= ' / ';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM cellette WHERE id_celletta != 0 AND id_acquirente IS NULL OR id_acquirente = 0');
        $temp['valore'] .= $result->row()->valore;
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Cellette acquistate nell\'anno corrente ['.date('Y').']';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM cellette WHERE id_celletta != 0 AND DATEDIFF(data_concessione, \''.date('Y').'-01-01\')>=0 AND DATEDIFF(data_concessione, \''.date('Y').'-12-31\')<=0');
        $temp['valore'] = $result->row()->valore;
        $array[] = $temp;
        //
        $temp['descrizione'] = 'Numero cellette libere / occupate';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM cellette LEFT JOIN sepolture ON cellette.id_celletta = sepolture.id_celletta WHERE cellette.id_celletta != 0 AND id_defunto IS NULL OR id_defunto = 0');
        $temp['valore'] = $result->row()->valore;
        $temp['valore'] .= ' / ';
        $result = $this->db->query('SELECT COUNT(*) AS valore FROM cellette LEFT JOIN sepolture ON cellette.id_celletta = sepolture.id_celletta WHERE cellette.id_celletta != 0 AND id_defunto IS NOT NULL AND id_defunto <> 0');
        $temp['valore'] .= $result->row()->valore;
        $array[] = $temp;
        
        return $array;
    }

    function getLog(){
        $result = $this->db->query('SELECT id_log, datetime, description, query, log_ripristino.id_utente, utenti.nomeutente
            FROM log_ripristino LEFT JOIN utenti ON log_ripristino.id_utente = utenti.id_utente
            WHERE log_ripristino.enabled = 1 ORDER BY datetime DESC');
        return $result->result_array(); 
    }

    function ripristinaLog($idLog = 0){

        $idLog = $this->db->escape($idLog);
        //cerco la query da eseguire
        $result = $this->db->query('SELECT query FROM log_ripristino WHERE id_log = '.$idLog);
        $result = $result->result_array();
        $this->db->trans_start(); // da il via ad un transaction
        //disabilita la query dal log
        $where = "id_log = ".$idLog;
        $str = $this->db->update_string('log_ripristino', array('enabled' => 0), $where);
        $this->db->query($str);
        //eseguo la query
        $result = explode(";", $result[0]['query']);
        foreach ($result as $key => $value) {
            if(trim($value) != ''){
                $this->db->query($value);
            }
        }
        $this->db->trans_complete(); // conclude la transaction
        // NB la query viene eseguita alla fine senza transazione in modo che se la query fallisce allora la riga viene comunque eliminata
    }

    function databaseFix(){
        echo "<title>Database Fix</title>";
        // se non è un utente admin nascondo l'output
        if($this->session->userdata('admin_enabled') != '1'){
            echo '<style type="text/css">p, h2{display: none;}</style>';
        }
        echo "<h2>Database Fix</h2>";
        $errori = 0;
        // cancello i piloni senza corrispondenza
        echo "<p>- Cerco i piloni senza la corrispondente celletta: ";
        $result = $this->db->query('SELECT piloni.id_pilone, id_celletta  FROM piloni LEFT JOIN cellette ON piloni.id_pilone = cellette.id_pilone WHERE ISNULL(id_celletta)');
        echo $result->num_rows()." righe</p>";
        $errori += $result->num_rows();
        $result = $result->result_array();
        foreach ($result as $key => $value) {
            $this->db->delete('piloni', array('id_pilone' => $value['id_pilone']));
        }
        // inserisco la cappella nulla
        $dati = array("nome" => "NESSUNO", "note" => "cappella fittizzia creata per migliorare la stabilita del programma");
        $errori = $this->test_elemento_nullo('cappelle', 'id_cappella', $dati, $errori);
        // inserisco la persona nulla
        $dati = array("cognome" => "NESSUNO", "nome" => "NESSUNO", "luogo_nascita" => "NESSUNO", "data_nascita" => "0000-00-00", "indirizzo" => "NESSUNO", "citta" => "NESSUNO", "telefono" => "NESSUNO", "cellulare" => "NESSUNO", "sesso" => "M", "infermo" => "0", "stato_civile" => "celibe/nubile", "note" => "NESSUNO", "note" => "persona fittizzia creata per migliorare la stabilita del programma");
        $errori = $this->test_elemento_nullo('persone', 'id_persona', $dati, $errori);
        // inserisco la celletta nulla
        $dati = array("id_cappella" => "0", "piano" => "0", "sezione" => "0", "fila" => "0", "numero" => "0", "tipo" => "celletta", "data_concessione" => "0000-00-00", "id_acquirente" => "0", "codice_bolletta" => "NESSUNO", "somma_pagata" => "0", "descrizione_lapide" => "NESSUNO", "id_pilone" => NULL, "note" => "celletta fittizzia creata per migliorare la stabilita del programma");
        $errori = $this->test_elemento_nullo('cellette', 'id_celletta', $dati, $errori);
        // inserisco il confratello nullo
        $dati = array("codice" => "0", "paternita" => "NESSUNO", "maternita" => "NESSUNO", "data_professione" => "0000-00-00", "codice_capofamiglia" => "0", "id_persona" => "0");
        $errori = $this->test_elemento_nullo('confratelli', 'id_confratello', $dati, $errori);
        // inserisco il defunto nullo
        $dati = array("codice_confratello" => "0", "cognome" => "NESSUNO", "nome" => "NESSUNO", "luogo_nascita" => "NESSUNO", "data_nascita" => "0000-00-00", "data_decesso" => "0000-00-00", "indirizzo" => "NESSUNO", "citta" => "NESSUNO", "sesso" => "M", "data_professione" => "0000-00-00", "paternita" => "NESSUNO", "maternita" => "NESSUNO", "note" => "defunto fittizzio creato per migliorare la stabilita del programma");
        $errori = $this->test_elemento_nullo('defunti', 'id_defunto', $dati, $errori);
        // inserisco l'incarico nullo
        $dati = array("nome" => "NESSUNO", "note" => "incarico fittizzio creato per migliorare la stabilita del programma");
        $errori = $this->test_elemento_nullo('incarichi', 'id_incarico', $dati, $errori);
        // cancello gli incarico_confratello con un incarico nullo
        echo "<p>- Cerco gli incarichi_confratello con un incarico nullo: ";
        $result = $this->db->delete('incarico_confratello', array('id_incarico' => 0));
        echo " Done</p>";
        // elimino tutti i log disabilitati
        echo "<p>- Elimino tutti i log disabilitati: ";
        $this->db->delete('log_ripristino', array('enabled' => 0));
        echo " Done</p>";
        // per ogni celletta ci può essere un solo responsabile
        echo "<p>- Cerco le cellette con piu di un responsabile attivo: ";
        $result = $this->db->query('SELECT id_celletta, COUNT(*) AS errori, MAX(id) AS valido FROM responsabili_cellette WHERE ISNULL(data_fine) GROUP BY id_celletta HAVING errori > 1');
        echo $result->num_rows()." cellette</p>";
        $errori += $result->num_rows();
        $result = $result->result_array();
        foreach ($result as $key => $value) {
            $this->db->query('DELETE FROM responsabili_cellette WHERE id_celletta = '.$value['id_celletta'].' AND id <> '.$value['valido'].' AND ISNULL(data_fine)');
        }
        // per ogni defunto ci può essere un solo responsabile
        echo "<p>- Cerco i defunti con piu di un responsabile attivo: ";
        $result = $this->db->query('SELECT id_defunto, COUNT(*) AS errori, MAX(id) AS valido FROM responsabili_defunti WHERE ISNULL(data_fine) GROUP BY id_defunto HAVING errori > 1');
        echo $result->num_rows()." defunti</p>";
        $errori += $result->num_rows();
        $result = $result->result_array();
        foreach ($result as $key => $value) {
            $this->db->query('DELETE FROM responsabili_defunti WHERE id_defunto = '.$value['id_defunto'].' AND id <> '.$value['valido'].' AND ISNULL(data_fine)');
        }
        // per ogni defunto ci può essere una sola sepoltura
        echo "<p>- Cerco i defunti con piu di una sepoltura attiva: ";
        $result = $this->db->query('SELECT id_defunto, COUNT(*) AS errori, MAX(id) AS valido FROM sepolture WHERE ISNULL(data_fine) AND id_defunto != 0 GROUP BY id_defunto HAVING errori > 1');
        echo $result->num_rows()." defunti</p>";
        $errori += $result->num_rows();
        $result = $result->result_array();
        foreach ($result as $key => $value) {
            $this->db->query('DELETE FROM sepolture WHERE id_defunto = '.$value['id_defunto'].' AND id <> '.$value['valido'].' AND ISNULL(data_fine)');
        }
        // impostazioni predefinite
        //version
        echo "<p>- Verifico la presenza delle impostazioni predefinite: ";
        $dati = array("name" => "version", "value" => "1.0", "enabled" => "1");
        $result = $this->db->query('SELECT id FROM settings WHERE name = "'.$dati["name"].'"');
        if($result->num_rows() == 0){
            $errori++;
            echo "Errore, ";
            $this->db->insert("settings", $dati);
        }else{
            echo "OK, ";
            $str = $this->db->update_string("settings", $dati, 'name = "'.$dati["name"].'"');
            $this->db->query($str);
        }
        //update_url
        $dati = array("name" => "update_url", "value" => "http://emperorpro.altervista.com/update/json_data.php", "enabled" => "1");
        $result = $this->db->query('SELECT id FROM settings WHERE name = "'.$dati["name"].'"');
        if($result->num_rows() == 0){
            $errori++;
            echo "Errore, ";
            $this->db->insert("settings", $dati);
        }else{
            echo "OK, ";
            $str = $this->db->update_string("settings", $dati, 'name = "'.$dati["name"].'"');
            $this->db->query($str);
        }
        //project_name
        $dati = array("name" => "project_name", "value" => "arciconfraternita_carmine", "enabled" => "1");
        $result = $this->db->query('SELECT id FROM settings WHERE name = "'.$dati["name"].'"');
        if($result->num_rows() == 0){
            $errori++;
            echo "Errore, ";
            $this->db->insert("settings", $dati);
        }else{
            echo "OK, ";
            $str = $this->db->update_string("settings", $dati, 'name = "'.$dati["name"].'"');
            $this->db->query($str);
        }
        //update_base_url
        $dati = array("name" => "update_base_url", "value" => "http://emperorpro.altervista.com/update", "enabled" => "1");
        $result = $this->db->query('SELECT id FROM settings WHERE name = "'.$dati["name"].'"');
        if($result->num_rows() == 0){
            $errori++;
            echo "Errore, ";
            $this->db->insert("settings", $dati);
        }else{
            echo "OK, ";
            $str = $this->db->update_string("settings", $dati, 'name = "'.$dati["name"].'"');
            $this->db->query($str);
        }
        echo "</p>";
        // utente admin
        echo "<p>- Verifico la presenza dell'utente ADMIN: ";
        $dati = array("nomeutente" => "ADMIN", "password" => "d033e22ae348aeb5660fc2140aec35850c4da997", "enabled" => "1", "admin_enabled" => "1");
        $result = $this->db->query('SELECT id_utente FROM utenti WHERE nomeutente = "ADMIN"');
        if($result->num_rows() == 0){
            $errori++;
            echo "Non trovato</p>";
            $this->db->insert("utenti", $dati);
        }else{
            echo "OK</p>";
            $str = $this->db->update_string("utenti", $dati, 'nomeutente = "ADMIN"');
            $this->db->query($str);
        }
        // integrità nella tabella parenti
        echo "<p>- Verifico l'integrita della tabella parenti': ";
        $result = $this->db->query('SELECT * FROM parenti ORDER BY id DESC');
        $result = $result->result_array();
        $this->load->model('model_FKMS');
        foreach ($result as $key => $value) {
            $result1 = $this->model_FKMS->search_duplicate('parenti', array('id_persona1' => $value['id_persona2'], 'id_persona2' => $value['id_persona1']));
            $temp = $value['id_persona1'];
            $value['id_persona1'] = $value['id_persona2'];
            $value['id_persona2'] = $temp;
            $value['id'] = NULL;
            if($result1 == false){
                $errori++;
                $this->db->insert('parenti', $value);
            }else{
                $str = $this->db->update_string('parenti', $value, "id = ".$result1['id']);
                $this->db->query($str);
            }
        }
        echo "Fatto</p>";


        echo "<p><strong>Totale errori risolti:</strong> ".$errori."</p>";
        echo '<a href="'.site_url('home').'">Premi qui per continuare</p>';
        echo '<meta http-equiv="refresh" content="0; url='.site_url('home').'" />';
    }

    function test_elemento_nullo($tabella, $id, $dati, $errori){
        // inserisco la un elemento nullo
        echo "<p>- Verifico l'esistenza dell'elemento nullo nella tabella [".$tabella."]: ";
        $result = $this->db->query('SELECT '.$id.' FROM '.$tabella.' WHERE '.$id.' = 0');
        if($result->num_rows() == 0){
            $errori++;
            echo "Non trovata</p>";
            $dati[$id] = 0;
            $this->db->insert($tabella, $dati);
            // eseguo due volte la query perchè la prima volta la chiave primaria viene settatta ad AUTO INCREMENT
            $str = $this->db->update_string($tabella, $dati, $id." = ".$this->db->insert_id());
            $this->db->query($str);
        }else{
            echo "OK</p>";
            $str = $this->db->update_string($tabella, $dati, $id." = 0");
            $this->db->query($str);
        }

        return $errori;
    }
}