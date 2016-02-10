<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>ID</th>
			<th>Utente</th>
			<th>Descrizione</th>
			<th>Date time</th>
			<th>Query di ripristino</th>
			<th>Azioni</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($tabella as $key => $value) { ?>
		<tr value="<?php echo $value['id_log']; ?>">
			<td><?php echo $value['id_log']; ?></td>
			<td><?php echo $value['nomeutente']; ?></td>
			<td><?php echo $value['description']; ?></td>
			<td><?php echo $value['datetime']; ?></td>
			<td><?php 
				$temp = $value['query'];
				$temp = str_replace(";", ";</br>", $temp);
				$temp = str_replace("INSERT INTO", "<strong>INSERT INTO</strong>", $temp);
				$temp = str_replace("DELETE FROM", "<strong>DELETE FROM</strong>", $temp);
				$temp = str_replace("UPDATE", "<strong>UPDATE</strong>", $temp);
				$temp = str_replace("VALUES", "<strong>VALUES</strong>", $temp);
				$temp = str_replace("WHERE", "<strong>WHERE</strong>", $temp);
				echo $temp; 
				?></td>
				<td>
					<button name="ripristina" type="button" class="btn btn-default btn-mini" value="<?php echo $value['id_log']; ?>" data-loading-text="Caricamento...">Ripristina</button>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<div class="row space"></div>
	<input type="button" value="Svuota il log" class="btn btn-default" id="svuota_log">
	<div class="row space"></div>
	<script type="text/javascript">
		$('button[name=ripristina]').click(function(){
			var btn = $(this);
			var id = $(this).val();
			btn.button('loading');
			$.ajax({
				url: "<?php echo site_url('altro/ajax_ripristina_log'); ?>",
				type: "POST",
				dataType: "json",
				data: "id_log="+id,
				success: function(msg) {
					my_alert('Ripristino effettuato', 0);
					$('tr[value='+id+']').hide(1500);
					btn.button('reset');
				},
				error : function (msg) {
					my_alert('Ripristino fallito', 1);
					ajax_error_show(msg);
					btn.button('reset');
				}
			});
		});

		<?php
	// svuota log
		$array = array(
			'tabella' => 'log_ripristino',
			'where' => array('1'),
			'script_tag' => 0,
			'redirect' => 'altro/log',
			'button_id' => '#svuota_log'
			);
		$this->load->view('parts/view_elimina_script.php', $array); 
		?>
	</script>