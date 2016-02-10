<table class="table table-striped table-hover">
	<thead>
		<!--tr>
			<th>Descrizione</th>
			<th>Valore</th>
		</tr-->
	</thead>
	<tbody>
		<?php foreach ($tabella as $key => $value) { ?>
			<tr>
				<?php if($value['valore'] == '#'){ ?>
				<th class="dot-list"><?php echo $value['descrizione']; ?></th>
				<th></th>
				<?php }else{ ?>
				<td><?php echo $value['descrizione']; ?></td>
				<td><?php echo $value['valore']; ?></td>
				<?php } ?>
			</tr>
		<?php } ?>
	</tbody>
</table>