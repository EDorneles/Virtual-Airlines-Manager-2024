<?php
/**
* @Project: Virtual Airlines Manager (VAM)
* @Author: Alejandro Garcia
* @New_template: Jonatha Silva
* @Web http://virtualairlinesmanager.net
* Copyright (c) 2013 - 2016 Alejandro Garcia
* VAM is licensed under the following license:
*   Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
*   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/4.0/
*/
?>

<?php
include('./db_login.php');
$db_map = new mysqli($db_host , $db_username , $db_password , $db_database);
$db_map->set_charset("utf8");
if ($db_map->connect_errno > 0) {
die('Unable to connect to database [' . $db_map->connect_error . ']');
}
$sql_map = "select plane_type,ias,flight_id,u.gvauser_id as gvauser_id,u.callsign as callsign,u.name as name,gs,altitude,surname,departure,arrival,latitude,longitude,flight_status,heading,perc_completed,
pending_nm, a1.latitude_deg as dep_lat, a1.longitude_deg as dep_lon , a2.latitude_deg as arr_lat, a2.longitude_deg as arr_lon , network
from vam_live_flights lf, gvausers u , airports a1, airports a2 where u.gvauser_id=lf.gvauser_id and lf.departure=a1.ident and lf.arrival=a2.ident";
$sql_map = "select r.flight as flightnum,plane_type,ias,flight_id,u.gvauser_id as gvauser_id,u.callsign as callsign,u.name as name, gs,altitude,surname,lf.departure, lf.arrival,lf.latitude,lf.longitude,lf.flight_status,lf.heading,lf.perc_completed,
pending_nm, a1.latitude_deg as dep_lat, a1.longitude_deg as dep_lon , a2.latitude_deg as arr_lat, a2.longitude_deg as arr_lon , network
from vam_live_flights lf, gvausers u , airports a1, airports a2, routes r where u.gvauser_id=lf.gvauser_id  and lf.departure=a1.ident and lf.arrival=a2.ident and r.route_id= u.route_id";
if (!$result = $db_map->query($sql_map)) {
die('There was an error running the query  [' . $db_map->error . ']');
}
unset($flights_coordinates);
unset($flight);
unset($liveflights);
unset($datos);
unset($jsonarray);
$flights_coordinates = array();
$datos = array ();
$flight = array();
$liveflights = array ();
$jsonarray = array ();
$index = 0;
$index2=0;
$flightindex=0;
while ($row = $result->fetch_assoc()) {
$flight["gvauser_id"]=$row["gvauser_id"];
$flight["callsign"]=$row["callsign"];
      $flight["flightnum"]=$row["flightnum"];
$flight["name"]=$row["name"];
$flight["gs"]=$row["gs"];
$flight["ias"]=$row["ias"];
$flight["altitude"]=$row["altitude"];
$flight["surname"]=$row["surname"];
$flight["departure"]=$row["departure"];
$flight["arrival"]=$row["arrival"];
$flight["latitude"]=$row["latitude"];
$flight["longitude"]=$row["longitude"];
$flight["flight_status"]=$row["flight_status"];
$flight["heading"]=$row["heading"];
$flight["dep_lat"]=$row["dep_lat"];
$flight["dep_lon"]=$row["dep_lon"];
$flight["arr_lat"]=$row["arr_lat"];
$flight["arr_lon"]=$row["arr_lon"];
$flight["perc_completed"]=$row["perc_completed"];
$flight["pending_nm"]=$row["pending_nm"];
$flight["network"]=$row["network"];
$flight["plane_type"]=$row["plane_type"];
$liveflights[$flightindex] =$flight;
$sql_map2 = "select * from vam_live_acars where flight_id='".$row["flight_id"]."' order by id desc";  
if (!$result2 = $db_map->query($sql_map2)) {
die('There was an error running the query  [' . $db_map->error . ']');
}
while ($row2 = $result2->fetch_assoc()) {
$flights_coordinates ["gvauser_id"] = $row2["gvauser_id"];
$flights_coordinates ["latitude"] = $row2["latitude"];
$flights_coordinates ["longitude"] = $row2["longitude"];
$flights_coordinates ["heading"] = $row2["heading"];
$datos [$index2][$index] = $flights_coordinates;
$index ++;
}
$index=0  ;
$index2 ++;
$flightindex ++;
}
$jsonarray[0]=$liveflights;
$jsonarray[1]=$datos;
?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_PT/sdk.js#xfbml=1&version=v2.10";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>



<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-114757685-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-114757685-1');
</script>
</head></html>

<?php
    include ('./vam_index_header.php');
    include ('./helpers/conversions.php');
    require('./vam_index_header1.php');
    if (!isset($_GET["page"]) || trim($_GET["page"]) == "") {
        ?>



<!-- 
<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i>WELCOME TO VIRTUAL LH</h4>
                <b>We are integrated with SimBried and Flight Plan Database</b>
              </div> -->
<div class="row"></div> <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $num_planes; ?></h3>

              <p>Aeronaves na frota</p>
            </div>
            <div class="icon">
<div style="margin-top:15px;"><i class="fa fa-plane fa-fw"></i></div>
            </div>
            <a href="./index.php?page=fleet_public" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
              <h3><?php echo $num_routes; ?></h3>

              <p>Rotas</p>
            </div>
            <div class="icon">
<div style="margin-top:15px;"><i class="fa fa-globe fa-fw"></i></div>
            </div>
            <a href="./index.php?page=route_public" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $num_fskeeper + $num_pireps + $num_reports + $num_vamacars - $num_fsacars_rejected - $num_fskeeper_rejected - $num_pireps_rejected - $num_vamacars_rejected ; ?></h3>

              <p>Vôos realizados</p>
            </div>
            <div class="icon">
<div style="margin-top:15px;"><i class="fa fa-suitcase fa-fw"></i></div>
            </div>
            <a href="./index.php?page=stats" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $num_pilots; ?></h3>

              <p>Pilotos</p>
            </div>
            <div class="icon">
<div style="margin-top:15px;"><i class="ion ion-person"></i></div>
            </div>
            <a href="./index.php?page=pilots_public" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
        <?php
            $sql = 'select callsign, arrival, departure, flight_id, flight_status, name, surname, pending_nm, plane_type from vam_live_flights vf, gvausers gu where gu.gvauser_id = vf.gvauser_id ';
            if (!$result = $db->query($sql)) {
                die('There was an error running the query [' . $db->error . ']');
            }
            $row_cnt = $result->num_rows;
            $sql = "SELECT flight_id FROM `vam_live_flights` WHERE UNIX_TIMESTAMP (now())-UNIX_TIMESTAMP (last_update)>180";
            if (!$result = $db->query($sql)) {
                die('There was an error running the query [' . $db->error . ']');
            }
            while ($row = $result->fetch_assoc())
            {
                $sql_inner = "delete from vam_live_acars where flight_id='".$row["flight_id"]."'";
                if (!$result_acars = $db->query($sql_inner))
                {
                die('There was an error running the query [' . $db->error . ']');
                }
                $sql_inner = "delete from vam_live_flights where flight_id='".$row["flight_id"]."'";
                if (!$result_acars = $db->query($sql_inner))
                {
                die('There was an error running the query [' . $db->error . ']');
                }
            }
            if ($row_cnt>0){
        ?> <!--
testar depois

if ($flight["plane_type"] == 'A319' || 'A320' || 'A321' || 'E195' || 'CRJ9' || 'B748' || 'B744' || 'B744C' || 'A332C' || 'A333' || 'A333C' || 'A346' || 'A346C' || 'A388')
echo '<img src="images/logo/lhlogo1.png" WIDTH="75" HEIGHT="25" BORDER=0 ALT="20">';
if ($flight["flightnum"] == 'CHECK')
echo '<img src="images/logo/lhlogo1.png" WIDTH="75" HEIGHT="25" BORDER=0 ALT="20">';
if ($flight["plane_type"] == 'A319E' || 'A320E' || 'B763E' || 'A332E')
echo '<img src="images/logo/Eurowings logo.png" WIDTH="95" HEIGHT="25" BORDER=0 ALT="20">';
if ($flight["plane_type"] == 'B77L' || 'MD11F' || 'B777C' || 'MD11FC' )
echo '<img src="images/logo/geclogo1.png" WIDTH="95" HEIGHT="25" BORDER=0 ALT="20">';
if ($flight["plane_type"] == 'E195D')
echo '<img src="images/logo/airdolo.png" WIDTH="95" HEIGHT="25" BORDER=0 ALT="20">';
if ($flight["plane_type"] == 'B77F')
echo '<img src="images/logo/aero.png" WIDTH="95" HEIGHT="25" BORDER=0 ALT="20">';

-->
        <div class="row" id="live_flights">
            <div class="col-md-12">
                <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
              <i class="fa fa-location-arrow"></i>
              <h3 class="box-title"><?php echo "LIVE FLIGHS"; ?></h3>
						
              </div>
                    <div class="panel-body">
                        <div class="table-responsive">
													<table class="table table-hover" id="live_flights_table">
                            <?php
                                    echo "<tr><th>" . 'Pilot' . "</th><th>" . 'Name' . "</th><th>" . LF_DEPARTURE . "</th><th>" . LF_ARRIVAL . "</th><th>" . FLIGHT_STAGE . "</th><th>". BOOK_ROUTE_ARICRAFT_TYPE . "</th><th>" . PERC_DONE ."</th><th>" . PENDING_NM . "</th></tr>";
                            				echo '<td>';										
																		echo $flight["callsign"] . '</td><td>';
																		echo $flight["name"] . '</td><td>';
																		echo $flight["departure"] . '</td><td>';
																		echo $flight["arrival"] . '</td><td>';
																		echo $flight["flight_status"] . '</td><td>';
																		echo $flight["plane_type"] . '</td><td>';
																		echo '<div class="progress progress-sm active">
                                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: ' . $flight["perc_completed"] . '%">
                                    <span><data-label="90% completed"> '. $flight["perc_completed"] . '%' .' </span>
                                    </div>
                                    </div>' . '</td><td>';	
																		echo $flight["pending_nm"] . 'NM ' . '</td>';
														?>
                            </table>
                        <?php include ('./vam_live_flights_map.php') ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
?></div> 
<div class="row">
<div class="col-md-12">
          <div class="box box-primary direct-chat direct-chat-primary">
<div class="box-header with-border">
            <div class="box-header with-border">
               <i class="fa fa-comment"></i>
              <h3 class="box-title"><?php echo WELCOME_VA . ' ' . $va_name; ?></h3>

              <div class="box-tools pull-right">
                
                
           
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<?php
                            $db = new mysqli($db_host , $db_username , $db_password , $db_database);
                            $db->set_charset("utf8");
                            if ($db->connect_errno > 0) {
                                die('Unable to connect to database [' . $db->connect_error . ']');
                            }
                            $sql = "select welcome_text from web_configurations";
                            if (!$result = $db->query($sql)) {
                                die('There was an error running the query [' . $db->error . ']');
                            }
                            while ($row = $result->fetch_assoc()) {
                                        echo $row["welcome_text"];
                            }
                        ?>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->

            <!-- /.box-footer -->
          </div></div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>       <div class="row">
        <!-- Left col -->
        <div class="col-md-8">
          <!-- MAP & BOX PANE -->

          <!-- /.box -->
          <div class="row">
            <div class="col-md-6">
              <!-- DIRECT CHAT -->
              <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
                  <i class="fa fa-bullhorn"></i>
                  <h3 class="box-title"><?php echo "NOTAMS"; ?></h3>

                  <div class="box-tools pull-right">
                    
                   
                  </div>
                </div>
                <!-- /.box-header -->
            <div class="box-body box-profile"><br>
              <?php
                            $db = new mysqli($db_host , $db_username , $db_password , $db_database);
                            $db->set_charset("utf8");
                            if ($db->connect_errno > 0) {
                                die('Unable to connect to database [' . $db->connect_error . ']');
                            }
                            $sql = "select notam_id,notam_name,DATE_FORMAT(publish_date,'%d-%m-%Y') as publish_date_web ,DATE_FORMAT(publish_date,'%Y%m%d') as publish_date,DATE_FORMAT(hide_date,'%Y%m%d') as hide_date, DATE_FORMAT(now(),'%Y%m%d') as currdat
from notams order by publish_date asc limit 5";
                            if (!$result = $db->query($sql)) {
                                die('There was an error running the query [' . $db->error . ']');
                            }
                        ?>
                        <div class="box-body no-padding">
                  <ul class="users-list clearfix">
                            <?php
                                
                                
      
      while ($row = $result->fetch_assoc()) { 
                                 if (($row["publish_date"]-$row["currdat"] <=0) && ($row["hide_date"]-$row["currdat"]>0))
echo "<td>";
echo ' <li>
                      <img src="images/notam.png" alt="User Image"  WIDTH="98" HEIGHT="98" BORDER=0 ALT="20"><br>
                        <a href="./index.php?page=notam&notam_id=' . $row["notam_id"] . '">' . $row["notam_name"] . '</a></td><td><br>';
                        echo $row["publish_date_web"] . '</tr></li></td>';
                                }
?></ul></div>
            </div>
                <!-- /.box-body -->

                <!-- /.box-footer-->
              </div>
              <!--/.direct-chat -->
            </div>
            <!-- /.col -->

            <div class="col-md-6">
              <!-- USERS LIST -->
              <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
                  <i class="fa fa-user-plus"></i>
                  <h3 class="box-title"><?php echo "Novos Pilotos"; ?></h3>

                  <div class="box-tools pull-right">
                    <?php
                      if ($num_pilots <= '8'){    ?>                    
                        <span class="label label-danger"><?php echo $num_pilots; ?> novos pilotos</span>
                     <?php } else {     ?>                   
                        <span class="label label-danger">8 new pilots</span>
                     <?php }
                    ?>
                    
            
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                        <?php
                            $db = new mysqli($db_host , $db_username , $db_password , $db_database);
                            $db->set_charset("utf8");
                            if ($db->connect_errno > 0) {
                                die('Unable to connect to database [' . $db->connect_error . ']');
                            }
                            $sql = "select gvauser_id, pilot_image, concat(name) as pilot , DATE_FORMAT(register_date,'$va_date_format') as register_date from gvausers where activation=1 order by DATE_FORMAT(register_date,'%Y%m%d') desc limit 8";
                            if (!$result = $db->query($sql)) {
                                die('There was an error running the query [' . $db->error . ']');
                            }
                        ?>
                        <div class="box-body no-padding">
                            <ul class="users-list clearfix">
                                <?php 
                                
                                while ($row = $result->fetch_assoc()) {
                                    echo "<td>";
                                        if ($row["pilot_image"]==null){
								                        $row["pilot_image"]="pilot_default.png";
                                        } 
                                    echo ' <li>          
                                    <img src="uploads/' . $row["pilot_image"] . '" style="height:98px;width:98px;" class="img-circle"><br>
                                    <a href="./index.php?page=pilot_details&pilot_id=' . $row["gvauser_id"] . '">' . $row["pilot"] . '</a></td><td><br>';
                                    echo $row["register_date"] . '</tr></li></td>';
                                }
                              ?>
                            </ul>
                        </div>
                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="./index.php?page=pilots_public" class="uppercase">Ver todos os Pilotos</a>
                </div>
                <!-- /.box-footer -->
              </div>
              <!--/.box -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
              <i class="fa fa-plane"></i> 
              <h3 class="box-title"><?php echo "Últimos voos realizados"; ?></h3>

              <div class="box-tools pull-right">
                
                </div>
            </div>
            <!-- /.box-header -->
            <div class="panel-body">
						<?php
							$db = new mysqli($db_host , $db_username , $db_password , $db_database);
							$db->set_charset("utf8");
							if ($db->connect_errno > 0) {
								die('Unable to connect to database [' . $db->connect_error . ']');
							}
							$sql = "select gvauser_id,a1.name as dep_name, a2.name as arr_name, a1.iso_country as dep_country,a2.iso_country as arr_country,
							callsign,pilot_name,departure,arrival,DATE_FORMAT(date,'$va_date_format') as date_string, date, format(time,2) as time
							from v_last_5_flights v, airports a1, airports a2
							where v.departure=a1.ident and v.arrival=a2.ident and time is not null order by date desc";
							if (!$result = $db->query($sql)) {
								die('There was an error running the query [' . $db->error . ']');
							}
						?>
						<div class="table-responsive">
							<table class="table table-hover">
								<?php
									echo "<thead><tr><th>" . LF_CALLSIG . "</th><th>" . LF_PILOT . "</th><th>" . LF_DEPARTURE . "</th><th>" . LF_ARRIVAL . "</th><th>" . LF_FLIGHTDATE . "</th><th>" . LF_FLIGHTTIME . "</th></tr></thead>";
									while ($row = $result->fetch_assoc()) {
										echo '<td>';
										echo '<a href="./index.php?page=pilot_details&pilot_id=' . $row["gvauser_id"] . '">' . $row["callsign"] . '</a></td><td>';
										echo $row["pilot_name"] . '</td><td>';
										echo '<IMG src="images/icons/ic_flight_takeoff_black_18dp_2x.png" WIDTH="20" HEIGHT="20" BORDER=0 ALT="">&nbsp;<IMG src="images/country-flags/'.$row["dep_country"].'.png" WIDTH="25" HEIGHT="20" BORDER=0 ALT="">&nbsp;<a href="./index.php?page=airport_info&airport=' . $row["departure"] . '">' . $row["departure"] . '</a></td><td>';
										echo '<IMG src="images/icons/ic_flight_land_black_18dp_2x.png" WIDTH="20" HEIGHT="20" BORDER=0 ALT="">&nbsp;<IMG src="images/country-flags/'.$row["arr_country"].'.png" WIDTH="25" HEIGHT="20" BORDER=0 ALT="">&nbsp;<a href="./index.php?page=airport_info&airport=' . $row["arrival"] . '">' . $row["arrival"] . '</a> </td><td>';
										echo $row["date_string"] . '</td><td>';
										echo '<i class="fa fa-clock-o"></i>&nbsp;'.convertTime($row["time"],$va_time_format). '</td></tr>';
									}
								?>
							</table>
						</div>
            </div></div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-4">
          <!-- Info Boxes Style 2 -->
          <div class="info-box bg-red">
<span class="info-box-icon"><div style="margin-top:20px;"><i class="fa fa-exchange fa-fw"></i></div></span>

            <div class="info-box-content">
              <span class="info-box-text"><?php echo "VOOS REGULARES"; ?></span>
              <span class="info-box-number"><?php echo $num_fskeeper_reg + $num_pireps_reg + $num_reports_reg + $num_vamacars_reg - $num_pireps_reg_rejected - $num_fskeeper_reg_rejected - $num_fsacars_reg_rejected - $num_vamacars_reg_rejected; ?></span>

              <div class="progress">
                <div class="progress-bar" style="width: 100%"></div>
              </div>
              <span class="progress-description">
                   
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          <div class="info-box bg-blue">
<span class="info-box-icon"><div style="margin-top:20px;"><i class="fa fa-clock-o fa-fw"></i></div></span>

            <div class="info-box-content">
              <span class="info-box-text"><?php echo "TOTAL DE HORAS"; ?></span>
              <span class="info-box-number"><?php echo convertTime($va_hours,$va_time_format); ?></span>

              <div class="progress">
                <div class="progress-bar" style="width: 100%"></div>
              </div>

            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          <div class="info-box bg-green">
<span class="info-box-icon"><div style="margin-top:20px;"><i class="fa fa-code-fork fa-fw"></i></div></span>

            <div class="info-box-content">
              <span class="info-box-text"><?php echo "VOOS FRETADOS"; ?></span>
              <span class="info-box-number"><?php echo $num_pireps + $num_fskeeper + $num_fsacars + $num_vamacars - $num_pireps_reg - $num_fskeeper_reg - $num_fsacars_reg - $num_vamacars_reg ; ?></span>

              <div class="progress">
                <div class="progress-bar" style="width: 0%"></div>
              </div>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          <div class="info-box bg-red">
<span class="info-box-icon"><div style="margin-top:20px;"><i class="fa fa-bar-chart fa-fw"></i></div></span>

            <div class="info-box-content">
              <span class="info-box-text"><?php echo "% VOOS REGULARES"; ?></span>
              <span class="info-box-number"><?php if (($num_fskeeper + $num_pireps + $num_reports + $num_vamacars - $num_fsacars_rejected - $num_fskeeper_rejected - $num_pireps_rejected - $num_vamacars_rejected) < 1) {
                                        echo '0 %';
                                    } else {
                                        echo number_format((100 * ($num_pireps_reg + $num_fskeeper_reg + $num_fsacars_reg + $num_vamacars_reg - $num_pireps_reg_rejected - $num_fskeeper_reg_rejected - $num_fsacars_reg_rejected - $num_vamacars_reg_rejected)) / ($num_pireps + $num_fskeeper + $num_fsacars + $num_vamacars - $num_fsacars_rejected - $num_fskeeper_rejected - $num_pireps_rejected - $num_vamacars_rejected) , 2) . ' %';
                                    }?></span>

              <div class="progress">
                <div class="progress-bar" style="width: 100%"></div>
              </div>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->

          <!-- /.box -->

          <!-- PRODUCT LIST -->
          <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
              <i class="fa fa-map"></i>
              <h3 class="box-title"><?php echo "Eventos"; ?></h3>

              <div class="box-tools pull-right">
                
              </div>
            </div>
            <!-- /.box-header -->
                <div class="box-body">
                  <?php
                      $db = new mysqli($db_host , $db_username , $db_password , $db_database);
                      $db->set_charset("utf8");
                          if ($db->connect_errno > 0) {
                          die('Unable to connect to database [' . $db->connect_error . ']');
                          }
                      $sql = "select event_id,event_name,DATE_FORMAT(publish_date,'$va_date_format') as publish_date_web ,DATE_FORMAT(publish_date,'%Y%m%d') as publish_date,DATE_FORMAT(hide_date,'%Y%m%d') as hide_date, DATE_FORMAT(now(),'%Y%m%d') as currdat
                      from events order by publish_date asc limit 5";
                          if (!$result = $db->query($sql)) {
                          die('There was an error running the query [' . $db->error . ']');
                          }
                      ?>
                      <table class="table table-hover">
                      <?php
                          echo "<thead><tr><th>" . EVENT_NAME . "</th><th>" . EVENT_DATE . "</th></tr></thead>";
                          while ($row = $result->fetch_assoc()) {
                              if (($row["publish_date"]-$row["currdat"] <=0) && ($row["hide_date"]-$row["currdat"]>0))
                              {
                              echo '<tr><td>';
                              echo '<a href="index.php?page=event&event_id=' . $row["event_id"] . '">' . $row["event_name"] . '</a>' . '</td><td>';
                              echo $row["publish_date_web"] . '</td></tr>';
                              }
                         }
                      ?>
                      </table>

                  <!-- /.direct-chat-pane -->
                </div>
            <!-- /.box-body -->
            
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>


        
       
<div class="row">
  <div class="col-md-4">
    <div class="box box-primary direct-chat direct-chat-primary">
      <div class="box-header with-border">
        <i class="fa fa-money" aria-hidden="true"></i>
        <div class="box-tools pull-right">
          <span data-toggle="tooltip" title=":)" class="badge bg-blue">Ajude-nos!</span>
        </div>
        <h3 class="box-title" style="margin-top: 0;">Doações</h3>
        <img src="/vam/images/paypal/qrcode.png" alt="Código QR do PayPal" width="200" style="display: block; margin: 0 auto;">
      </div>
      <div class="box-body">
        <!-- Conversations are loaded here -->
        <center>
          <a href="https://paypal.me/donatePPT" target="_blank">
            <button>Doar via PayPal</button>
          </a>
        </center>
      </div>
    </div>
  
</div>


<div class="col-md-4">
              <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
                  <i class="fa fa-envelope"></i>
                  <h3 class="box-title">Nosso e-mail para Contato</h3>

                  <div class="box-tools pull-right">
                    
                   
                  </div>
                </div>
                <!-- /.box-header -->
                <a href="mailto:equipepptvirtual@gmail.com">equipepptvirtual@gmail.com</a>
              </div>
     
            </div>
           <div class="col-md-4"> 
              <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
                  <i class="fa fa-microphone"></i>
                  <h3 class="box-title">Nosso Discord</h3>

                  <div class="box-tools pull-right">
                    
                   
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body"><br>
                  <center><iframe src="https://discordapp.com/widget?id=919766793587916830&theme=dark" width="350" height="500" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe></center>
                  <!-- Conversations are loaded here 
<center><iframe src="https://discordapp.com/widget?id=PASTE_YOUR_DISCORD_ID_HERE&theme=dark" width="300" height="500" allowtransparency="true" frameborder="0"></iframe></center>

                  <!-- /.direct-chat-pane -->
                </div>
                <!-- /.box-body -->

                <!-- /.box-footer-->
              </div>
              <!--/.direct-chat -->
            </div>
           
        </div>


        <!-- HOME PAGE End -->
    <?php
    }
    if (!isset($_GET["page"]) || trim($_GET["page"]) == "") {
    } else {
        $Existe = file_exists($_GET["page"] . ".php");
        if ($Existe == true) {
            include($_GET["page"] . ".php");
        } else {
            echo "Page Not Found";
        }
    }
?>
</div>

</body>
</html>
