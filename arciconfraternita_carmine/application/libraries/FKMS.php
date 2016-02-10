<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Foreign Key Managment System

class FKMS extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_FKMS');
	}

	public function insert_persona($persona, $confratello, $control_fields){
		if($confratello != NULL){
			// controllo se il codice è già presente
			$result = $this->model_FKMS->search_duplicate('confratelli_persone', array('codice' => $confratello['codice']));
			if($result != false){
				$error_message = 'Il codice inserito è già presente nel database ed è associato alla persona: <a target="_blank" href="'.site_url('stampe/stampa_persona').'/'.$result['id_persona'].'">'.$result['cognome'].' '.$result['nome'].'</a>.<br>Scegli un altro codice per il confratello.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
			// controllo se esiste il capofamiglia
			$result = $this->model_FKMS->search_duplicate('confratelli_persone', array('codice' => $confratello['codice_capofamiglia']));
			if($confratello['codice_capofamiglia'] != '' && $confratello['codice_capofamiglia'] != '0' && $result == false){
				$error_message = 'Il codice inserito per il capofamiglia non esiste ancora nel database.<br>Scegli un altro codice per il capofamiglia, crea un confratello con quel codice oppure lascia il capo vuoto.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
		}
		// controllo se esiste una persona con lo stesso nome e cognome
		$result = $this->model_FKMS->search_duplicate('persone', array('cognome' => $persona['cognome'], 'nome' => $persona['nome']));
		if($result != false && $control_fields['ignore'] != '1'){
			$error_message = 'Attenzione, esiste già nel database una persona con lo stesso nome e cognome: <a target="_blank" href="'.site_url('stampe/stampa_persona').'/'.$result['id_persona'].'">'.$result['cognome'].' '.$result['nome'].'</a>.<br>Se desideri continuare ugualmente con l\'inserimento premi "Continua".';
			$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message, 'continue_btn' => $control_fields['function']));
			return false;
		}

		return true;
	}

	public function modifica_persona($id_persona, $persona, $confratello, $control_fields){
		if($confratello != NULL){
			// controllo se il codice è già presente
			$result = $this->model_FKMS->search_duplicate('confratelli_persone', array('codice' => $confratello['codice']), array('id_persona' => $id_persona));
			if($result != false){
				$error_message = 'Il codice inserito è già presente nel database ed è associato alla persona: <a target="_blank" href="'.site_url('stampe/stampa_persona').'/'.$result['id_persona'].'">'.$result['cognome'].' '.$result['nome'].'</a>.<br>Scegli un altro codice per il confratello.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
			// controllo se esiste il capofamiglia
			$result = $this->model_FKMS->search_duplicate('confratelli_persone', array('codice' => $confratello['codice_capofamiglia']));
			if($confratello['codice_capofamiglia'] != '' && $confratello['codice_capofamiglia'] != '0' && $result == false){
				$error_message = 'Il codice inserito per il capofamiglia non esiste ancora nel database.<br>Scegli un altro codice per il capofamiglia, crea un confratello con quel codice oppure lascia il capo vuoto.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
		}
		// controllo se esiste una persona con lo stesso nome e cognome
		$result = $this->model_FKMS->search_duplicate('persone', array('cognome' => $persona['cognome'], 'nome' => $persona['nome']), array('id_persona' => $id_persona));
		if($result != false && $control_fields['ignore'] != '1'){
			$error_message = 'Attenzione, esiste già nel database una persona con lo stesso nome e cognome: <a target="_blank" href="'.site_url('stampe/stampa_persona').'/'.$result['id_persona'].'">'.$result['cognome'].' '.$result['nome'].'</a>.<br>Se desideri continuare ugualmente con l\'inserimento premi "Continua".';
			$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message, 'continue_btn' => $control_fields['function']));
			return false;
		}

		return true;
	}

	public function elimina_persona($where){
		// converto la $where in id_persona
		$result = $this->model_FKMS->where_to_field('id_persona', 'persone', $where);
		foreach ($result as $key => $value) {
			// cerco se ci sono parenti associati a questa persona
			$result = $this->model_FKMS->search_duplicate('parenti', array('id_persona1' => $value['id_persona']));
			$result |= $this->model_FKMS->search_duplicate('parenti', array('id_persona2' => $value['id_persona']));
			if($result != false){
				$error_message = 'Ci sono ancora dei parenti associati a questa persona, elimina prima i rapporti di parentela.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
			// cerco se ci sono cellette associate a questa persona
			$result = $this->model_FKMS->search_duplicate('responsabili_cellette', array('id_responsabile' => $value['id_persona']));
			if($result != false){
				$error_message = 'Ci sono ancora delle cellette di cui questa persona è responsabile, prima di eliminare questa persona scegli un nuovo responsabile per quelle cellette.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
			// cerco se ci sono cellette associate a questa persona
			$result = $this->model_FKMS->search_duplicate('responsabili_defunti', array('id_responsabile' => $value['id_persona']));
			if($result != false){
				$error_message = 'Ci sono ancora dei defunti di cui questa persona è responsabile, prima di eliminare questa persona scegli un nuovo responsabile per quei defunti.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
			// cerco se ci sono cellette associate a questa persona
			$result = $this->model_FKMS->search_duplicate('cellette', array('id_acquirente' => $value['id_persona']));
			if($result != false){
				$error_message = 'Questa persona ha acquistato delle cellette, cambiare l\'acquirente di quelle cellette prima di continuare.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
		}
		return true;
	}

	public function elimina_confratello($where){
		// converto la $where in id_confratello
		$result = $this->model_FKMS->where_to_field('id_confratello', 'confratelli', $where);
		// cerco se ci sono incarichi associati a questo confratello
		foreach ($result as $key => $value) {
			$result = $this->model_FKMS->search_duplicate('incarico_confratello', array('id_confratello' => $value['id_confratello']));
			if($result != false){
				$error_message = 'Ci sono ancora degli incarichi associati a questo confratello, elimina prima gli incarichi usando la pagina <a target="_blank" href="'.site_url('incarichi/visualizza_incarichi').'">visualizza incarichi</a>.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
		}
		return true;
	}

	public function insert_defunto($defunto, $nuova_sepoltura, $nuovo_responsabile, $tipo, $control_fields){
		// controllo se esiste un defunto con lo stesso nome e cognome
		$result = $this->model_FKMS->search_duplicate('defunti', array('cognome' => $defunto['cognome'], 'nome' => $defunto['nome']));
		if($result != false && $control_fields['ignore'] != '1'){
			$error_message = 'Attenzione, esiste già nel database un defunto con lo stesso nome e cognome: <a target="_blank" href="'.site_url('stampe/stampa_defunto').'/'.$result['id_defunto'].'">'.$result['cognome'].' '.$result['nome'].'</a>.<br>Se desideri continuare ugualmente con l\'inserimento premi "Continua".';
			$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message, 'continue_btn' => $control_fields['function']));
			return false;
		}
		return true;
	}

	public function insert_cappella($cappella){
		// controllo se il nome è già presente
		$result = $this->model_FKMS->search_duplicate('cappelle', array('nome' => $cappella['nome']));
		if($result != false){
			$error_message = 'Il nome scelto per la cappella è già presente nel database. <br>Scegli un altro nome per la cappella.';
			$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
			return false;
		}
		return true;
	}

	public function elimina_cappella($id_cappella){
		// controllo se esiste una celletta che appertiene a questa cappella
		$result = $this->model_FKMS->search_duplicate('cellette', array('id_cappella' => $id_cappella));
		if($result != false){
			$error_message = 'Esistono ancora delle cellette associate a questa cappella. <br>Cancellare o modificare la posizione delle cellette.';
			$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
			return false;
		}
		return true;
	}

	public function insert_celletta($celletta, $pilone, $defunti){
		// controllo se la posizione della celletta esiste già
		$result = $this->model_FKMS->search_duplicate('dettagli_cellette', array('id_cappella' => $celletta['id_cappella'], 'piano' => $celletta['piano'], 'sezione' => $celletta['sezione'], 'fila' => $celletta['fila'], 'numero' => $celletta['numero']));
		if($result != false){
			$error_message = 'La posizione scelta per la celletta è già presente nel database: <a target="_blank" href="'.site_url('stampe/stampa_celletta').'/'.$result['id_celletta'].'">'.$result['cappella_nome'].' - '.$result['piano'].' - '.$result['sezione'].' - '.$result['fila'].' - '.$result['numero'].'</a> <br>Scegli un\'altra posizione.';
			$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
			return false;
		}
		// controllo se la posizione del pilone esiste già
		if($pilone != NULL){
			$result = $this->model_FKMS->search_duplicate('piloni', array('id_cappella' => $pilone['id_cappella'], 'piano' => $pilone['piano'], 'sezione' => $pilone['sezione'], 'numero' => $pilone['numero']));
			if($result != false){
				//cerco i dati della celletta associata al pilone
				$result = $this->model_FKMS->search_duplicate('dettagli_cellette', array('id_pilone' => $result['id_pilone']));

				$error_message = 'La posizione scelta per il pilone è già presente nel database: <a target="_blank" href="'.site_url('stampe/stampa_celletta').'/'.$result['id_celletta'].'">'.$result['cappella_nome'].' - '.$result['piano'].' - '.$result['sezione'].' - '.$result['fila'].' - '.$result['numero'].'</a> <br>Scegli un\'altra posizione.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
		}
		// controllo se i defunti inseriti in questa celletta si trovano già in altre cellette
		foreach ($defunti as $key => $value) {
			if($value['id'] != 0){
				// cerco le sepolture ancora attive per i defunti che voglio inserire in questa nuova celletta
				$result = $this->model_FKMS->search_duplicate('sepolture', array('id_defunto' => $value['id'], 'ISNULL(data_fine)' => '#'), array('id_celletta' => '0'));

				if($result != false){
					//cerco i dati del defunto
					$result = $this->model_FKMS->search_duplicate('defunti', array('id_defunto' => $value['id']));

					$error_message = 'Il defunto <a target="_blank" href="'.site_url('stampe/stampa_defunto').'/'.$value['id'].'">'.$result['cognome'].' '.$result['nome'].'</a> è al momento associato ad un\'altra celletta, eliminalo da quella celletta oppure usa la funzione <a target="_blank" href="'.site_url('defunti/sposta_defunto').'">sposta defunto</a>';
					$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
					return false;
				}
			}
		}

		return true;
	}

	public function modifica_celletta($id_celletta, $celletta, $pilone, $defunti){
		// controllo se la posizione della celletta esiste già
		$result = $this->model_FKMS->search_duplicate('dettagli_cellette', array('id_cappella' => $celletta['id_cappella'], 'piano' => $celletta['piano'], 'sezione' => $celletta['sezione'], 'fila' => $celletta['fila'], 'numero' => $celletta['numero']), array('id_celletta' => $id_celletta));
		if($result != false){
			$error_message = 'La posizione scelta per la celletta è già presente nel database: <a target="_blank" href="'.site_url('stampe/stampa_celletta').'/'.$result['id_celletta'].'">'.$result['cappella_nome'].' - '.$result['piano'].' - '.$result['sezione'].' - '.$result['fila'].' - '.$result['numero'].'</a> <br>Scegli un\'altra posizione.';
			$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
			return false;
		}
		// controllo se la posizione del pilone esiste già
		if($pilone != NULL){
			// cerco se la celletta aveva anche prima della modifica un pilone
			$result = $this->model_FKMS->search_duplicate('cellette', array('id_celletta' => $id_celletta));
			$not_where = array();
			if($result['id_pilone'] != NULL){
				$not_where['id_pilone'] = $result['id_pilone'];
			}
			$result = $this->model_FKMS->search_duplicate('piloni', array('id_cappella' => $pilone['id_cappella'], 'piano' => $pilone['piano'], 'sezione' => $pilone['sezione'], 'numero' => $pilone['numero']), $not_where);
			if($result != false){
				//cerco i dati della celletta associata al pilone
				$result = $this->model_FKMS->search_duplicate('dettagli_cellette', array('id_pilone' => $result['id_pilone']));

				$error_message = 'La posizione scelta per il pilone è già presente nel database: <a target="_blank" href="'.site_url('stampe/stampa_celletta').'/'.$result['id_celletta'].'">'.$result['cappella_nome'].' - '.$result['piano'].' - '.$result['sezione'].' - '.$result['fila'].' - '.$result['numero'].'</a> <br>Scegli un\'altra posizione.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
		}
		
		// controllo se i defunti inseriti in questa celletta si trovano già in altre cellette
		foreach ($defunti as $key => $value) {
			if($value['id'] != 0){
				// cerco le sepolture ancora attive per i defunti che voglio inserire in questa nuova celletta
				$result = $this->model_FKMS->search_duplicate('sepolture', array('id_defunto' => $value['id'], 'ISNULL(data_fine)' => '#'), array('id_celletta' => $id_celletta, 'id_celletta != 0' => '#'));
				if($result != false){
					//cerco i dati del defunto
					$result = $this->model_FKMS->search_duplicate('defunti', array('id_defunto' => $value['id']));

					$error_message = 'Il defunto <a target="_blank" href="'.site_url('stampe/stampa_defunto').'/'.$value['id'].'">'.$result['cognome'].' '.$result['nome'].'</a> è al momento associato ad un\'altra celletta, eliminalo da quella celletta oppure usa la funzione <a target="_blank" href="'.site_url('defunti/sposta_defunto').'">sposta defunto</a>';
					$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
					return false;
				}
			}
		}

		return true;
	}

	public function elimina_celletta($where){
		// converto la $where in id_celletta
		$result = $this->model_FKMS->where_to_field('id_celletta', 'cellette', $where);
		// cerco se ci sono sepolture con quella celletta
		foreach ($result as $key => $value) {
			$result = $this->model_FKMS->search_duplicate('sepolture', array('id_celletta' => $value['id_celletta'], 'ISNULL(data_fine)' => '#'), array('id_defunto' => '0'));
			if($result != false){
				$error_message = 'Ci sono ancora delle sepolture associate a quella celletta. <br>Spostare o eliminare i defunti dalla celletta.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
		}
		return true;
	}

	public function insert_utente($id_utente, $username){
		// controllo se l'utente esiste già
		if($id_utente == 0){
			$result = $this->model_FKMS->search_duplicate('utenti', array('nomeutente' => $username));
			if($result != false){
				$error_message = 'Il nome utente scelto è già presente nel database. <br>Scegliere un altro nome utente.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
		}
		return true;
	}

	public function assegna_incarico($query, $modifica_riassegna){
		// controllo se esiste un incarico_confratello con lo stesso id_incarico e data_inizio
		if($modifica_riassegna != 'modifica'){
			$result = $this->model_FKMS->search_duplicate('incarico_confratello', array('id_incarico' => $query['id_incarico'], 'data_inizio' => $query['data_inizio']));
			if($result != false){
				$error_message = 'Questo incarico è già stato assegnato ad un confratello nello stesso giorno, non puoi riassegnare un incarico usando una data già usata.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
		}
		return true;
	}
	
	public function elimina_incarico($where){
		// converto la $where in id_incarico
		$result = $this->model_FKMS->where_to_field('id_incarico', 'incarichi', $where);
		// cerco se ci sono incarichi confratello con questo id_incarico
		foreach ($result as $key => $value) {
			$result = $this->model_FKMS->search_duplicate('incarico_confratello', array('id_incarico' => $value['id_incarico']));
			if($result != false){
				$error_message = 'Ci sono ancora dei confratelli associati a questo incarico, elimina prima lo storico dei confratelli.';
				$this->load->view('parts/view_panel_fkms', array('error_message' => $error_message));
				return false;
			}
		}
		return true;
	}
}

/* End of file FKMS.php */