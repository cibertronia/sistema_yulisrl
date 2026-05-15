<?php
	include 'includes/conexion.php';
	include 'includes/date.class.php';
	mysqli_query($MySQLi,"SET lc_time_names= 'es_BO' ");
	$idUser 	=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>CORREOS ENVIADOS</title>
		<?php include 'php/meta.php'; ?>
		<link href="assets/css/apple/app.min.css" rel="stylesheet">
		<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
		<link href="assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet">
		<link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet">
	</head>
	<body>
		<?php include 'php/loader.php'; ?>
		<div id="page-container" class="fade page-sidebar-fixed page-header-fixed page-content-full-height">
			<?php
				include 'php/top_menu.php';
				include 'php/left_menu.php';
			?>
			<div class="sidebar-bg"></div>
			<div id="content" class="content content-full-width bg-silver inbox">
				<div class="vertical-box with-grid">
					<div class="vertical-box-column width-200">
						<div class="vertical-box">
							<div class="wrapper">
								<div class="d-flex align-items-center justify-content-center">
									<a href="#emailNav" data-toggle="collapse" class="btn btn-inverse btn-sm mr-auto d-block d-lg-none">
										<i class="fa fa-cog"></i>
									</a>
									<a href="email_compose.html" class="btn btn-inverse p-l-40 p-r-40 btn-sm">
										Compose
									</a>
								</div>
							</div>
							<div class="vertical-box-row collapse d-lg-table-row" id="emailNav">
								<div class="vertical-box-cell">
									<div class="vertical-box-inner-cell">
										<div data-scrollbar="true" data-height="100%">
											<div class="wrapper p-0">
												<div class="nav-title"><b>FOLDERS</b></div>
												<ul class="nav nav-inbox">
													<!-- <li class="active"><a href="email_inbox.html"><i class="fa fa-inbox fa-fw m-r-5"></i> Inbox <span class="badge pull-right">52</span></a></li> -->
													<!-- <li><a href="email_inbox.html"><i class="fa fa-flag fa-fw m-r-5"></i> Important</a></li> -->
													<li><a href="email_inbox.html"><i class="fa fa-envelope fa-fw m-r-5"></i> Enviados</a></li>
													<!-- <li><a href="email_inbox.html"><i class="fa fa-pencil-alt fa-fw m-r-5"></i> Drafts</a></li> -->
													<!-- <li><a href="email_inbox.html"><i class="fa fa-trash fa-fw m-r-5"></i> Trash</a></li> -->
												</ul>
												<!-- <div class="nav-title"><b>LABEL</b></div>
												<ul class="nav nav-inbox">
													<li><a href="javascript:;"><i class="fa fa-fw f-s-10 m-r-5 fa-circle text-inverse"></i> Admin</a></li>
													<li><a href="javascript:;"><i class="fa fa-fw f-s-10 m-r-5 fa-circle text-blue"></i> Designer & Employer</a></li>
													<li><a href="javascript:;"><i class="fa fa-fw f-s-10 m-r-5 fa-circle text-success"></i> Staff</a></li>
													<li><a href="javascript:;"><i class="fa fa-fw f-s-10 m-r-5 fa-circle text-warning"></i> Sponsorer</a></li>
													<li><a href="javascript:;"><i class="fa fa-fw f-s-10 m-r-5 fa-circle text-danger"></i> Client</a></li>
												</ul> -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="vertical-box-column">
						<div class="vertical-box">
							<div class="wrapper">
								<div class="btn-toolbar align-items-center">
									<div class="custom-control custom-checkbox mr-2">
										<input type="checkbox" class="custom-control-input" data-checked="email-checkbox" id="emailSelectAll" data-change="email-select-all">
										<label class="custom-control-label" for="emailSelectAll"></label>
									</div>
									<div class="dropdown mr-2">
										<button class="btn btn-white btn-sm" data-toggle="dropdown">
											View All <span class="caret m-l-3"></span>
										</button>
										<div class="dropdown-menu">
											<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2"></i> All</a>
											<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2 text-muted"></i> Unread</a>
											<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2 text-blue"></i> Contacts</a>
											<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2 text-success"></i> Groups</a>
											<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2 text-warning"></i> Newsletters</a>
											<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2 text-danger"></i> Social updates</a>
											<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2 text-indigo"></i> Everything else</a>
										</div>
									</div>
									<button class="btn btn-sm btn-white mr-2"><i class="fa fa-redo"></i></button>
									<!-- begin btn-group -->
									<div class="btn-group">
										<button class="btn btn-sm btn-white hide" data-email-action="delete"><i class="fa fa-times mr-2"></i> <span class="hidden-xs">Delete</span></button>
										<button class="btn btn-sm btn-white hide" data-email-action="archive"><i class="fa fa-folder mr-2"></i> <span class="hidden-xs">Archive</span></button>
										<button class="btn btn-sm btn-white hide" data-email-action="archive"><i class="fa fa-trash mr-2"></i> <span class="hidden-xs">Junk</span></button>
									</div>
									<!-- end btn-group -->
									<!-- begin btn-group -->
									<div class="btn-group ml-auto">
										<button class="btn btn-white btn-sm">
											<i class="fa fa-chevron-left"></i>
										</button>
										<button class="btn btn-white btn-sm">
											<i class="fa fa-chevron-right"></i>
										</button>
									</div>
								</div>
							</div>

							<!-- begin vertical-box-row -->
							<div class="vertical-box-row">
								<div class="vertical-box-cell">
									<div class="vertical-box-inner-cell bg-white">
										<div data-scrollbar="true" data-height="100%">
											<ul class="list-group list-group-lg no-radius list-email">
												<li class="list-group-item unread">
													<div class="email-checkbox">
														<div class="custom-control custom-checkbox">
															<input type="checkbox" class="custom-control-input" data-checked="email-checkbox" id="emailCheckbox1">
															<label class="custom-control-label" for="emailCheckbox1"></label>
														</div>
													</div>
													<a href="email_detail.html" class="email-user bg-blue">
														<span class="text-white">F</span>
													</a>
													<div class="email-info">
														<a href="email_detail.html">
															<span class="email-sender">Facebook Blueprint</span>
															<span class="email-title">Newly released courses, holiday marketing tips, how-to video, and more!</span>
															<span class="email-desc">Sed scelerisque dui lacus, quis pellentesque lorem tincidunt rhoncus. Nulla accumsan elit pharetra, lacinia turpis nec, varius erat.</span>
															<span class="email-time">Today</span>
														</a>
													</div>
												</li>
												
												<li class="list-group-item">
													<div class="email-checkbox">
														<div class="custom-control custom-checkbox">
															<input type="checkbox" class="custom-control-input" data-checked="email-checkbox" id="emailCheckbox18">
															<label class="custom-control-label" for="emailCheckbox18"></label>
														</div>
													</div>
													<a href="email_detail.html" class="email-user">
														<img src="assets/img/user/user-5.jpg" alt="">
													</a>
													<div class="email-info">
														<a href="email_detail.html">
															<span class="email-sender">Nadine Barnes</span>
															<span class="email-title">Simple Line Icons is available on Color Admin v1.4</span>
															<span class="email-desc">Maecenas auctor dui sit amet tristique congue. Pellentesque lobortis nulla quam. Etiam in vulputate magna...</span>
															<span class="email-time">3 months ago</span>
														</a>
													</div>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>

							<div class="wrapper clearfix d-flex align-items-center">
								<div class="text-inverse f-w-600">1,232 Mensajes</div>
								<div class="btn-group ml-auto">
									<button class="btn btn-white btn-sm">
										<i class="fa fa-fw fa-chevron-left"></i>
									</button>
									<button class="btn btn-white btn-sm">
										<i class="fa fa-fw fa-chevron-right"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top">
				<i class="fa fa-angle-up"></i>
			</a>
			<?php //include 'php/footer.php'; ?>
		</div>
		<script src="assets/js/app.min.js"></script>
		<script src="assets/js/theme/apple.min.js"></script>
		<script src="assets/plugins/flot/jquery.flot.js"></script>
		<script src="assets/plugins/flot/jquery.flot.time.js"></script>
		<script src="assets/plugins/flot/jquery.flot.resize.js"></script>
		<script src="assets/plugins/flot/jquery.flot.pie.js"></script>
		<script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
		<script src="assets/plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
		<script src="assets/plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
		<script src="assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
		<script src="assets/js/demo/dashboard.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
	</body>
</html>
