<script type="text/javascript">
$('#help_btn').removeClass('hidden');
$('#help_btn').click(function(){
	//
	var element = $('#cb_stampa').eq(0);
	element.attr('data-position', 'left');
	element.attr('data-intro', 'Disattivando questa casella è possibile scegliere quali contenuti visualizzare nell\'anteprima di stampa.');
	//
	var element = $('#modifica');
	element.attr('data-position', 'right');
	element.attr('data-intro', 'Questo link permette di modificare le informazioni di una persona.');
	//
	var element = $('#dettagli_capofamiglia');
	element.attr('data-position', 'right');
	element.attr('data-intro', 'Questo link permette di accedere alla scheda del capofamiglia.');
	//
	var element = $('#dettagli_celletta_acquistata').eq(0);
	element.attr('data-position', 'right');
	element.attr('data-intro', 'Questo link permette di visulaizzare i dettagli della celletta acquistata.');
	//
	var element = $('#dettagli_celletta_responsabile').eq(0);
	element.attr('data-position', 'right');
	element.attr('data-intro', 'Questo link permette di visualizzare i dettagli della celletta di cui è responsabile.');
	//
	var element = $('#dettagli_defunto_responsabile').eq(0);
	element.attr('data-position', 'right');
	element.attr('data-intro', 'Questo link permetta di visualizzare i dettagli del defunto di cui è responsabile.');
	//
	var element = $('#dettagli_parente').eq(0);
	element.attr('data-position', 'right');
	element.attr('data-intro', 'Questo link permetta di visualizzare i dettagli del parente.');
	//
	var intro = introJs();
	intro.setOption('showBullets', false);
	intro.start();
});
</script>