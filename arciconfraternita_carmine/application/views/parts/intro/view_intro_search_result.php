<script type="text/javascript">
$('#help_btn').removeClass('hidden');
$('#help_btn').click(function(){
	//
	var element = $('tbody > tr').eq(0);
	element.attr('data-step', 1);
	element.attr('data-position', 'bottom');
	element.attr('data-intro', 'Per ogni risultato è possibile eseguire due azioni:</br><strong>Modifica</strong> premette di modificare e visualizzare la scheda minima del risultato;</br><strong>Stampa</strong> permette di visualizzare una scheda estesa del risultato.');
	//
	var element = $('div.panel');
	element.attr('data-step', 2);
	element.attr('data-position', 'top');
	element.attr('data-intro', 'In questa sezione è possibile visualizzare il numero di risultati ottenuti e un riepilogo dei parametri inseriti per la ricerca.');
	//
	var intro = introJs();
	intro.setOption('showBullets', false);
	intro.start();
});
</script>