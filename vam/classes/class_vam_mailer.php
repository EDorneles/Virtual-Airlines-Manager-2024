<?php
	/**
	 * @Project: Virtual Airlines Manager (VAM)
	 * @Author: Alejandro Garcia
	 * @Web http://virtualairlinesmanager.net
	 * Copyright (c) 2013 - 2016 Alejandro Garcia
	 * VAM is licenced under the following license:
	 *   Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
	 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/4.0/
	 */
?>
<?php
	require 'PHPMailer-master/PHPMailerAutoload.php';
	class vam_mailer
	{
		function mail_register_compose($email_address)
		{
			include('db_login.php');
			$db = new mysqli($db_host , $db_username , $db_password , $db_database);
			$db->set_charset("utf8");
			if ($db->connect_errno > 0) {
				die('Unable to connect to database [' . $db->connect_error . ']');
			}
			// Send mail to the pilot
			//  Get VA email configuration
			$sql = "select * from va_parameters";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$va_name = $row["va_name"];
			}
			$sql = "select * from config_emails";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$staff_email = $row["staff_email"];
				$ceo_email = $row["ceo_email"];
				$cc_email_1 = $row["cc_email_1"];
				$register_text = $row["register_text"];
			}
			$mail = new PHPMailer;
			$mail->addReplyTo($staff_email , 'PPT system');
			$mail->From = $staff_email;
			$mail->FromName = $va_name . ' PPT system';
			$mail->addAddress($email_address);
			$mail->addCC($ceo_email);
			$mail->addCC($cc_email_1);
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'Welcome to ' . $va_name;
			$mail->Body = $register_text . '</b>';
			$mail->AltBody = $register_text;
			if (!$mail->send()) {
				echo 'Message could not be sent.';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				echo '';
			}
		}
		function mail_password_compose($email_address , $pass)
		{
         include('db_login.php');
         $db = new mysqli($db_host , $db_username , $db_password , $db_database);
         $db->set_charset("utf8");
         if ($db->connect_errno > 0) {
            die('Unable to connect to database [' . $db->connect_error . ']');
         }
         // Send mail to the pilot
         //  Get VA email configuration
         $sql = "select * from va_parameters";
         if (!$result = $db->query($sql)) {
            die('There was an error running the query [' . $db->error . ']');
         }
         while ($row = $result->fetch_assoc()) {
            $va_name = $row["va_name"];
         }
         $sql = "select * from config_emails";
         if (!$result = $db->query($sql)) {
            die('There was an error running the query [' . $db->error . ']');
         }
         while ($row = $result->fetch_assoc()) {
            $staff_email = $row["staff_email"];
            $ceo_email = $row["ceo_email"];
            $cc_email_1 = $row["cc_email_1"];
            $register_text = $row["register_text"];
         }
         $mail = new PHPMailer;
         $mail->From = $staff_email;
         $mail->FromName = $va_name . ' PPT system';
         $mail->addAddress($email_address);               // Name is optional
         $mail->isHTML(true);                                  // Set email format to HTML
         $mail->Subject = 'Password reset ' . $va_name;
         $mail->Body = 'Caro mebro <p> Sua nova senha é: ' . $pass . '<p>Regards<p>Private Plane system';
         $mail->AltBody = 'Caro mebro. Sua nova senha é:';
         if (!$mail->send()) {
            echo 'Message could not be sent';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
         } else {
            echo ' ';
         }
      }
		function mail_vampirep_compose($pilot,$action,$departure,$arrival,$comment)
		{
			include('../db_login.php');
			$db = new mysqli($db_host , $db_username , $db_password , $db_database);
			$db->set_charset("utf8");
			if ($db->connect_errno > 0) {
				die('Unable to connect to database [' . $db->connect_error . ']');
			}
			$sql = "select * from gvausers where gvauser_id=".$pilot;
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$email_address = $row["email"];
				$accept_emails =$row["accept_emails"];
			}
			$sql = "select * from va_parameters";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$va_name = $row["va_name"];
			}
			$sql = "select * from config_emails";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$staff_email = $row["staff_email"];
				$ceo_email = $row["ceo_email"];
				$cc_email_1 = $row["cc_email_1"];
				$register_text = $row["register_text"];
			}
			if ($accept_emails==1)
			{
				switch ($action) {
			    case "acceptflight":
			        $validation="ACEITO";
			        break;
			    case "rejectflight":
			        $validation="RECUSADO";
			        break;
			    case "deleteflight":
			        $validation="APAGADO";
			        break;
				}
				$mail = new PHPMailer;
				$mail->From = $staff_email;
				$mail->FromName = $va_name . ' PPT system';
				$mail->addAddress($email_address);               // Name is optional
				$mail->isHTML(true);
				$mail->Subject = 'PPT system - Validação de voos ' . $va_name . '  '.  $departure . '-'. $arrival;
				$mail->Body = 'Caro piloto <p> Seu voo de ' . $departure . ' para ' .$arrival . ' foi  ' .$validation.'.<P>'.'Comentários do validador:' . $comment;
				$mail->AltBody = 'Caro piloto <p> Seu voo de ' . $departure . ' para ' .$arrival . ' foi  ' .$validation.'.<P>'.'Comentários do validador:' . $comment;
				if (!$mail->send()) {
					echo 'Message could not be sent';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} else {
					echo ' ';
				}
			}
		}
		function mail_validation_compose($pilot,$action,$departure,$arrival,$comment)
		{
			include('db_login.php');
			$db = new mysqli($db_host , $db_username , $db_password , $db_database);
			$db->set_charset("utf8");
			if ($db->connect_errno > 0) {
				die('Unable to connect to database [' . $db->connect_error . ']');
			}
			$sql = "select * from gvausers where gvauser_id=".$pilot;
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$email_address = $row["email"];
				$accept_emails = $row["accept_emails"];
			}
			$sql = "select * from va_parameters";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$va_name = $row["va_name"];
			}
			$sql = "select * from config_emails";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$staff_email = $row["staff_email"];
				$ceo_email = $row["ceo_email"];
				$cc_email_1 = $row["cc_email_1"];
				$register_text = $row["register_text"];
			}
			if ($accept_emails==1)
			{
				switch ($action) {
			    case "acceptflight":
			        $validation="ACEITO";
			        break;
			    case "rejectflight":
			        $validation="REJEITADO";
			        break;
				}
				$mail = new PHPMailer;
				$mail->From = $staff_email;
				$mail->FromName = $va_name . ' PPT system';
				$mail->addAddress($email_address);               // Name is optional
				$mail->isHTML(true);
				$mail->Subject = ' DOV ' . $va_name . '  '.  $departure . '-'. $arrival;
				$mail->Body = 'Caro piloto <p> Seu voo de ' . $departure . ' para ' .$arrival . ' foi  ' .$validation.'<br>Clique <a href="https://www.privatevirtual.com.br/vam/index_vam_op.php?page=route_selection_stage1">AQUI</a> para reservar um novo voo.';
				$mail->AltBody = 'Caro piloto <p> Seu voo de ' . $departure . ' to ' .$arrival . ' foi  ' .$validation.'.<P>';
				if (!$mail->send()) {
					echo 'Message could not be sent';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} else {
					echo ' ';
				}
			}
		}
		function mail_flight_report_compose($pilot,$type,$departure,$arrival)
		{
			if ($type=='FSKEEPER PIREP')
			{
				include('../db_login.php');
			}
			else
			{
				include('db_login.php');
			}
			$db = new mysqli($db_host , $db_username , $db_password , $db_database);
			$db->set_charset("utf8");
			if ($db->connect_errno > 0) {
				die('Unable to connect to database [' . $db->connect_error . ']');
			}
			$sql = "select * from gvausers where gvauser_id=$pilot";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$callsign = $row["callsign"];
				$pilot_name = $row["name"].' '. $row["surname"];
			}
			$sql = "select * from va_parameters";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$va_name = $row["va_name"];
			}
			$sql = "select * from config_emails";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$staff_email = $row["staff_email"];
				$ceo_email = $row["ceo_email"];
				$cc_email_1 = $row["cc_email_1"];
			}
				switch ($type) {
			    case "acceptflight":
			        $validation="VALIDATED";
			        break;
			    case "rejectflight":
			        $validation="REJECTED";
			        break;
				}
				$mail = new PHPMailer;
				$mail->From = $staff_email;
				$mail->FromName = $va_name . ' PPT System';
				$mail->addAddress($staff_email);               // Name is optional
				$mail->addCC($ceo_email); // CEO como destinatário em cópia
				$mail->isHTML(true);
				$mail->Subject = 'Novo Reporte de Voo tipo: '. $type . ' De: ' . $callsign . ' '.  $departure . '-'. $arrival;
				$mail->Body = 'Caro examinador,<br><br>Um novo voo partindo de ' . $departure . ' para ' .$arrival . ' foi reportado.<br>Por: '.$callsign.' - '. $pilot_name.'<br><br><br>Clique <a href="http://www.privatevirtual.com.br/vam/index_vam_op.php?page=validate_flights">AQUI</a> para revisar o voo.';

				$mail->AltBody = 'Caro examinador,<br><br>Um novo voo partindo de ' . $departure . ' para ' .$arrival . ' foi reportado.<br>Por :'.$callsign.' - '. $pilot_name.'<br><br><br>Clique <a href="http://www.privatevirtual.com.br/vam/index_vam_op.php?page=validate_flights">AQUI</a> para revisar o voo.';
				if (!$mail->send()) {
					echo 'Message could not be sent';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} else {
					echo ' ';
				}
		}
		function mail_warning_inac_pilot_compose($pilot,$action)
		{
			$callsign = '';
			$pilot_name = '';
			$emil = '';
			$va_name = '';
			$staff_email = '';
			$ceo_email = '';
			$cc_email_1 = '';
			$Subject = '';
			$body = '';
			include('db_login.php');
			$db = new mysqli($db_host , $db_username , $db_password , $db_database);
			$db->set_charset("utf8");
			if ($db->connect_errno > 0) {
				die('Unable to connect to database [' . $db->connect_error . ']');
			}
			$sql = "select * from gvausers where gvauser_id=$pilot";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$callsign = $row["callsign"];
				$pilot_name = $row["name"].' '. $row["surname"];
				$email= $row["email"];
			}
			$sql = "select * from va_parameters";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$va_name = $row["va_name"];
			}
			$sql = "select * from config_emails";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$staff_email = $row["staff_email"];
				$ceo_email = $row["ceo_email"];
				$cc_email_1 = $row["cc_email_1"];
				$warning_text = $row["warning_text"];
				$inactivate_text = $row["inactivate_text"];
			}
			if ($action==1){
				$Subject = $va_name.' Private Virtual System - Aviso de inatividade do piloto';
				$body = $warning_text;
			}
			else
			{
				$Subject=$va_name.' Private Virtual System - Aviso de inatividade do piloto';
				$body = $inactivate_text;
			}
			$mail = new PHPMailer;
			$mail->From = $staff_email;
			$mail->FromName = $va_name . ' Private Virtual System';
			$mail->addAddress($staff_email);               // Name is optional
			$mail->addAddress($email);
			$mail->isHTML(true);
			$mail->Subject = $Subject;
			$mail->Body = $body;
			$mail->AltBody = $body;
			if (!$mail->send()) {
				echo 'Message could not be sent';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				echo ' ';
			}
		}
	}
?>
