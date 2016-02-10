<body>
	<div id="container">
		<div class="fixed-alert">
			<div class="row">
				<div id="alert" class="col-lg-4 col-lg-offset-4">
				</div>
			</div>
		</div>
		<div class="row space"></div>
		<div class="row space"></div>
		<div class="row no-margin">
			<div class="col-lg-4 col-lg-offset-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Login<span class="ui-icon ui-icon-key pull-right"></span></h3>
					</div>
					<div class="panel-body">
						<form method="POST" action="<?php echo site_url('login/login_result'); ?>">
							<div class="form-group">
								<label>Nome utente</label>
								<input name="username" type="text" class="form-control" placeholder="Nome utente">
							</div>
							<div class="form-group">
								<label>Password</label>
								<input name="password" type="password" class="form-control" placeholder="Password">
							</div>
							<div class="row">
								<div class="col-lg-12">
									<button type="submit" class="btn btn-default pull-right">Entra</button>
								</div>
							</div>
						</form>

					</div>
				</div>
				<div class="row space"></div>
				<div class="row space"></div>
				<?php if($retry == 1){ ?>
				<script type="text/javascript">
				my_alert('Nome utente o password non corretti', 1);
				</script>
				<?php } ?>