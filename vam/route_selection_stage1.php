<?php
	require('check_login.php');
	if (is_logged()) {
		$db = new mysqli($db_host, $db_username, $db_password, $db_database);
		$db->set_charset("utf8");
		$route = '';
		if ($db->connect_errno > 0) {
			die('Unable to connect to the database [' . $db->connect_error . ']');
		}

		$sql = "SELECT route_id FROM gvausers gu WHERE gu.gvauser_id=$id";
		if (!$result = $db->query($sql)) {
			die('There was an error running the query [' . $db->error . ']');
		}

		while ($row = $result->fetch_assoc()) {
			$route = $row["route_id"];
		}
		if ($route <> '' && $route > 0) {
			$sql1 = "SELECT DISTINCT a3.iso_country AS alt_country, a3.name AS alt_name, a1.name AS dep_name, a2.name AS arr_name,
			a1.iso_country AS dep_country, a2.iso_country AS arr_country, flight, departure, arrival, alternative, registry, plane_icao,
			f.fleet_id, plane_icao
			 FROM routes ro, reserves re, fleets f, fleettypes ft , airports a1, airports a2, airports a3
			 WHERE a1.ident=ro.departure AND a2.ident=ro.arrival AND a3.ident=ro.alternative AND
			 ft.fleettype_id=f.fleettype_id AND f.fleet_id=re.fleet_id AND ro.route_id=$route AND ro.route_id=re.route_id AND re.gvauser_id=$id";
			if (!$result1 = $db->query($sql1)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			?>
			<div class="row">
				<div class="col-md-12">
					<!-- DIRECT CHAT -->
					<div class="box box-primary direct-chat direct-chat-primary">
						<div class="box-header with-border">
							<i class="fa fa-fighter-jet"></i>
							<h3 class="box-title"><?php echo ROUTES_BOOKED ?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="panel-body">
								<div class="callout callout-success">
									<h4><i class="icon fa fa-check"></i> Você já tem um voo reservado!</h4>
									<p>
										A reserva será cancelada automaticamente em 24 horas, mas você pode cancelá-la clicando no botão Cancelar abaixo.
									</p>
								</div>
							</div>
						</div>
						<!-- /.box-body -->

						<!-- /.box-footer-->
					</div>
					<!--/.direct-chat -->
				</div>
			</div>

			<div class="col-md-12">
				<!-- DIRECT CHAT -->
				<div class="box box-primary direct-chat direct-chat-primary">
					<div class="box-header with-border">
						<i class="fa fa-fighter-jet"></i>
						<h3 class="box-title"><?php echo "Flight information" ?></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="panel-body">
							<table id="route_select_booked" class="table table-hover">
								<?php
								echo "<tr><th>" . BOOK_ROUTE_FLIGHT . "</th><th>" . BOOK_ROUTE_DEPARTURE . "</th><th>" . BOOK_ROUTE_ARRIVAL . "</th><th>" . BOOK_ROUTE_ALTERNATIVE . "</th><th>" . BOOK_ROUTE_ARICRAFT_REG . "</th><th>" . BOOK_ROUTE_ARICRAFT_TYPE . "</th><th>" . BOOK_ROUTE_ROUTE . "</th><th>" . BOOK_ROUTE_CANCEL . "</th></tr>";
								$flightNumbers = array(); // Array para armazenar números de voo únicos
								while ($row1 = $result1->fetch_assoc()) {
									$flightNumber = $row1["flight"];
									if (!in_array($flightNumber, $flightNumbers)) { // Verifique se o número do voo já foi exibido
										array_push($flightNumbers, $flightNumber); // Adicione o número do voo ao array de números de voo
										echo "<td>";
										echo $flightNumber . '</td><td><IMG src="images/icons/ic_flight_takeoff_black_18dp_2x.png" WIDTH="20" HEIGHT="20" BORDER=0 ALT="">&nbsp;<IMG src="images/country-flags/' . $row1["dep_country"] . '.png" WIDTH="25" HEIGHT="20" BORDER=0 ALT="">&nbsp;';
										echo $row1["departure"] . '<br><font size="1">' . str_replace("Airport", "", $row1["dep_name"]) . '</font></td><td><IMG src="images/icons/ic_flight_land_black_18dp_2x.png" WIDTH="20" HEIGHT="20" BORDER=0 ALT="">&nbsp;<IMG src="images/country-flags/' . $row1["arr_country"] . '.png" WIDTH="25" HEIGHT="20" BORDER=0 ALT="">&nbsp;';
										echo $row1["arrival"] . '<br><font size="1">' . str_replace("Airport", "", $row1["arr_name"]) . '</font> </td><td><IMG src="images/icons/ic_flight_land_black_18dp_2x.png" WIDTH="20" HEIGHT="20" BORDER=0 ALT="">&nbsp;<IMG src="images/country-flags/' . $row1["alt_country"] . '.png" WIDTH="25" HEIGHT="20" BORDER=0 ALT="">&nbsp;';
										echo $row1["alternative"] . '<br><font size="1">' . str_replace("Airport", "", $row1["alt_name"]) . '</font> </td><td>';
										echo '<a target="_blank" href="./index_vam_op.php?page=plane_info_public&registry_id=' . $row1["registry"] . '">' . $row1["registry"] . '</td><td>';
										echo '<a target="_blank" href="./index_vam_op.php?page=fleet_public&plane_icao=' . $row1["plane_icao"] . '">' . $row1["plane_icao"] . '</td><td>';
										echo '<a href="https://dispatch.simbrief.com/options/custom?airline=PPT&fltnum=' . $flightNumber . '&type=' . $row1["plane_icao"] . '&pid=' . $callsign . '&cpt=' . $pilotsurname . '&manualrmk=OPR/PRIVATE PLANE TAXI CALL/PRIVATE RMK/TCAS&dxname=D.O.V. Private&callsign=' . $flightNumber . '&reg=' . $row1["registry"] . '&orig=' . $row1["departure"] . '&dest=' . $row1["arrival"] . '&cargo=' . $cargo . '&pax=' . $pax . '&deph=16&depm=30&steh=4&stem=30" target="_blank"><img src="http://www.simbrief.com/previews/sblogo_small.png" WIDTH="73" HEIGHT="28" BORDER=0 ALT="20"</td><td>';
										echo '<a href="./index_vam_op.php?page=cancel_reserve&route=' . $route . '&plane=' . $row1["fleet_id"] . '"><IMG src="images/KO32.png" WIDTH="20" HEIGHT="20" BORDER=0 ALT=""></a></td></tr>';
									}
								}
								?>
							</table>
						</div>
						<!-- /.box-body -->

						<!-- /.box-footer-->
					</div>
					<!--/.direct-chat -->
				</div>
			</div>
		<?php
		} else {
			$sql2 = "SELECT DISTINCT a3.iso_country AS alt_country, a3.name AS alt_name, a1.name AS dep_name, a2.name AS arr_name, a1.iso_country AS dep_country, a2.iso_country AS arr_country, r.route_id AS route, flight, r.departure, r.arrival, r.comments, ftr.fleettype_id, alternative, etd, eta, duration FROM
					fleets f INNER JOIN gvausers g ON g.location = f.location
					INNER JOIN routes r ON r.departure = f.location
					INNER JOIN fleettypes_routes ftr ON ftr.route_id = r.route_id
					INNER JOIN fleettypes_gvausers ftu ON ftu.fleettype_id = f.fleettype_id
					INNER JOIN airports a1 ON (a1.ident=r.departure)
					INNER JOIN airports a2 ON (a2.ident=r.arrival)
					INNER JOIN airports a3 ON (a3.ident=r.alternative)
					WHERE f.booked=0
					AND ftr.fleettype_id = f.fleettype_id
					AND g.gvauser_id=$id
					AND ftu.gvauser_id=$id";
			if (!$result2 = $db->query($sql2)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<h4><i class="icon fa fa-warning"></i> Nota IMPORTANTE!</h4>
						Se a tabela <strong>NÃO</strong> estiver mostrando voos, é possível que a aeronave em que você estava voando esteja em <strong>MANUTENÇÃO</strong>. Você pode conferir clicando em <a href="http://www.privatevirtual.com.br/vam/index.php?page=fleet_public"><strong>Frota</strong></a>.
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-header">
							<i class="fa fa-globe"></i>
							<h3 class="box-title"><?php echo AVAILABLE_ROUTES; ?></h3>
							<div class="box-tools">
								<div class="input-group input-group-sm" style="width: 150px;">
									<input type="text" id="destination-search" class="form-control pull-right" placeholder="Destino">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
									</div><script>
    $(document).ready(function() {
        $('#destination-search').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            $('#route_select_one tbody tr').each(function() {
                var rowText = $(this).text().toLowerCase();
                if (rowText.indexOf(searchText) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });
    });
</script>
								</div>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body table-responsive no-padding">
							<table id="route_select_one" class="table table-hover">
								<?php
								echo '<thead>';
								echo "<tr><th>" . BOOK_ROUTE_FLIGHT . "</th><th>" . BOOK_ROUTE_DEPARTURE . "</th><th>" . BOOK_ROUTE_ARRIVAL . "</th><th>" . BOOK_ROUTE_ALTERNATIVE . "</th><th>" . BOOK_ROUTE_DURATION . "</th><th>" . BOOK_ROUTE_TIME_DEP . "</th><th>" . BOOK_ROUTE_TIME_ARR . "</th><th>" . "Selecionar" . "</th></tr>";
								echo '</thead>';
								$flightNumbers = array(); // Array para armazenar números de voo únicos
								while ($row2 = $result2->fetch_assoc()) {
									$flightNumber = $row2["flight"];
									if (!in_array($flightNumber, $flightNumbers)) { // Verifique se o número do voo já foi exibido
										array_push($flightNumbers, $flightNumber); // Adicione o número do voo ao array de números de voo
										echo "<td>";
										echo $flightNumber . '</td><td><IMG src="images/icons/ic_flight_takeoff_black_18dp_2x.png" WIDTH="20" HEIGHT="20" BORDER=0 ALT="">&nbsp;<IMG src="images/country-flags/' . $row2["dep_country"] . '.png" WIDTH="25" HEIGHT="20" BORDER=0 ALT="">&nbsp;';
										echo '<a target="_blank" href="./index_vam_op.php?page=airport_info&airport=' . $row2["departure"] . '">' . $row2["departure"] . '</a><br><font size="1">' . str_replace("Airport", "", $row2["dep_name"]) . '</font></td><td><IMG src="images/icons/ic_flight_land_black_18dp_2x.png" WIDTH="20" HEIGHT="20" BORDER=0 ALT="">&nbsp;<IMG src="images/country-flags/' . $row2["arr_country"] . '.png" WIDTH="25" HEIGHT="20" BORDER=0 ALT="">&nbsp;';
										echo '<a target="_blank" href="./index_vam_op.php?page=airport_info&airport=' . $row2["arrival"] . '">' . $row2["arrival"] . '</a><br><font size="1">' . str_replace("Airport", "", $row2["arr_name"]) . '</font> </td><td><IMG src="images/icons/ic_flight_land_black_18dp_2x.png" WIDTH="20" HEIGHT="20" BORDER=0 ALT="">&nbsp;<IMG src="images/country-flags/' . $row2["alt_country"] . '.png" WIDTH="25" HEIGHT="20" BORDER=0 ALT="">&nbsp;';
										echo '<a target="_blank" href="./index_vam_op.php?page=airport_info&airport=' . $row2["alternative"] . '">' . $row2["alternative"] . '</a><br><font size="1">' . str_replace("Airport", "", $row2["alt_name"]) . '</font> </td><td><i class="fa fa-clock-o"></i>&nbsp;';
										echo $row2["duration"] . '</td><td>';
										echo $row2["etd"] . '</td><td>';
										echo $row2["eta"] . '</td><td>';
										echo '<a href="./index_vam_op.php?page=route_selection_stage2&route=' . $row2["route"] . '"><i class="fa fa-plane" style="font-size:24pt;color:#04B404;"></i></a></td></tr>';
										
									}
									
								}
								?>
							</table>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
		<?php
		}
		$db->close();
	} else {
		include("./notgranted.php");
	}
?>
