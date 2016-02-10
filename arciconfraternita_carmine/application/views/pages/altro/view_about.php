<p>Applicazione creata da <strong>Marco Lucarella</strong>.</p>
<p>Inizio sviluppo Agosto 2013 per <strong>Arciconfraternita del Carmine</strong> - Martina Franca.</p>
<p>- EMPEROR Group -</p>
<p></p>
<h4>Contatti</h4>
<p><strong>Mail: </strong><a href="mailto:lucarellamarco@gmail.com">lucarellamarco@gmail.com</a></p>
<p><strong>Tel: </strong>+39 393 2375663</p>

<?php if($this->session->userdata('admin_enabled') == '1'){ ?>
<div class="row space"></div>
<h4>Impostazioni avanzate ADMIN:</h4>
<p>- <a href="../../install.php">Installazione e aggiornamento</a></p>
<p>- <a href="<?php echo site_url('altro/database_fix'); ?>">Database Fix</a></p>
<p>- <a href="http://localhost/phpmyadmin/index.php">PHPMyAdmin</a></p>
<?php } ?>

<div class="row space"></div>
<h4>Rapporto modifiche:</h4>
<strong>1.0</strong><br>
- Aggiunta la possibilità di far acquistare ai defunti le cellette<br>
- Aggiunto help in alcune pagine<br>
- Aggiunta la possibilità di aggiornare il database in modo automatico<br>
- Risolto bug sulle pagine vuote<br>
- Risolti altri bug<br>
<strong>Beta</strong><br>
- Non tutte le funzioni sono state testate a pieno.<br>
- Non è ancora possibile assegnare ad un defunto una celletta acquistata.<br>
- In attesa di altre istruzioni, modifiche e segnalazione di bug dal committente<br>