<!DOCTYPE html>
<html>
<head>
	<title>Installazione</title>
</head>
<body>
	<h3>Installazione arciconfraternita del camrmine</h3>
	<?php
	$username = 'root';
	$password = '';
	$hostname = 'localhost';
	if(isset($_GET['start'])){
		// avvia la procedura di inizializzazione
		$sql_filescript_basepath = './database/';
		$sql_filescript = array(
			'00_initial_settings.sql',
			'01_create_database.sql',
			'02_create_tables.sql',
			'03_create_views.sql',
			'04_insert_initial_rows.sql',
			'05_foreign_keys.sql',
			'06_database_update.sql',
			'07_insert_into.sql',
			'99_final_settings.sql');

		try {
			//open connection
			$dbh = new PDO('mysql:host='.$hostname, $username, $password);
			echo "<p>Connesso al server MySql</p>";

			foreach ($sql_filescript as $key => $value) {
				echo 'Eseguo il file <strong>"'.$value.'"</strong>: ';
				flush();
				ob_flush();
				$sql = file_get_contents($sql_filescript_basepath.$value);
				if($sql != ''){
					echo $dbh->exec($sql);
				}else{
					echo 'Empty';
				}
				echo '</p>';
			}

			// close connection
			echo '<p>Installazione completata</p>';
			echo '<a href="./index.php/altro/database_fix">Continua</a>';
			$dbh = null;
		} catch (PDOException $e) {
			echo '<p><strong>Errore:</strong> '.$e->getMessage().'</p>';
			die();
		}
	} else {
		if(isset($_GET['load'])){
			try {
				$value = $_GET['file'];
				//open connection
				$dbh = new PDO('mysql:host='.$hostname, $username, $password);
				echo "<p>Connesso al server MySql</p>";

				echo 'Eseguo il file <strong>"'.$value.'"</strong>: ';
				$sql = file_get_contents($value);
				if($sql != ''){
					echo $dbh->exec($sql);
				}else{
					echo 'Empty';
				}
				echo '</p>';

				// close connection
				echo '<p>Caricamento completato</p>';
				echo '<a href="./index.php">Vai alla home del programma</a>';
				$dbh = null;
			} catch (PDOException $e) {
				echo '<p><strong>Errore:</strong> '.$e->getMessage().'</p>';
				die();
			}
		} else {?>
		<form method="GET" action="install.php">
			<p>Questa pagina permette di inizializzare il database per poter avviare l'applicazione.</p>
			<p>Se si avvia questa procedura il database verra' distrutto per poi essere resettato.</p>
			<p>Se siete sicuri di voler continuare premete il pulsante avvia.</p>
			<input type="submit" name="start" value="Installazione">
			<p>Se si desidera caricare un file personale allora usare questa opzione:</p>
			<input type="text" name="file" value="./arciconfraternita_carmine-backup_database" size="80">
			<input type="submit" name="load" value="Carica">
		</form>
		<form method="GET" action="install.php">
			<p>Se si desidera eseguire l'aggiornamento del database allora usare questa opzione:</p>
			<input type="text" name="file" value="./database/06_database_update.sql" style="display:none;">
			<input type="submit" name="load" value="Aggiorna">
		</form>
		<form method="GET" action="install.php">
			<p>Se si desidera estrarre da un file backup solo le query INSERT INTO allora usare questa opzione:</p>
			<input type="text" name="file" value="./arciconfraternita_carmine-backup_database" size="80">
			<input type="submit" name="convert" value="Converti" disabled="disabled">
		</form>
		<?php }
	} ?>
</body>
</html>
