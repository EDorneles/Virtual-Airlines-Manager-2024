<?php
	/**
	 * @Project: Virtual Airlines Manager (VAM)
	 * @Author: Alejandro Garcia
	 * @Web http://virtualairlinesmanager.net
	 * Copyright (c) 2013 - 2016 Alejandro Garcia
	 * VAM is licensed under the following license:
	 *   Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
	 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/4.0/
	 */
?>
<?php
	session_start();
	include("captcha/simple-php-captcha.php");
	$_SESSION['captcha'] = simple_php_captcha();
	if (isset ($_GET['lang']))
	{
		$_SESSION['language'] = $_GET['lang'];
	}
	if (isset($_SESSION['id'])) {
		$user_logged = 1;
	} else {
		$user_logged = 0;
	}
	if (!isset($_GET['lang']) && $_SESSION['language'] == '') {
		$_SESSION['language'] = "en";
	}
	if (isset($_GET['lang'])) {
		$_SESSION['language'] = $_GET['lang'];
	} elseif (!isset($_SESSION['language'])) {
		$_SESSION['languages'] = "en";
	}
	include("./languages/lang_" . $_SESSION['language'] . ".php");
	include('db_login.php');
	include('./languagesdd.php');
	include('classes/class_vam_mailer.php');
	include('classes/security.php');
	include('./va_parameters.php');
	include('./get_va_index_data.php');
	include('./get_va_data.php');
	include('./get_va_parameters.php');
	include('./main_index.php');
	include('hangar_review.php');
	include('review_reserves.php');
	$secure = new SECURITY();
	$secure->parse_incoming();
?>
<?php
if ($_SERVER['REQUEST_URI'] == '/vam/index.php' && !isset($_COOKIE['popup_shown'])) {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pop-up de boas vindas</title>
	<style>
		#overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			display: flex;
			justify-content: center;
			align-items: center;
			z-index: 999;
		}

		#popup {
			background-image: url('http://www.privatevirtual.com.br/vam/login/images/bg_login1.jpg');
			background-size: cover;
			background-position: center;
			padding: 50px;
			text-align: center;
			border-radius: 10px;
			box-shadow: 0 0 50 rgba(0, 0, 0, 0.2);
			position: relative;
			max-width: 1000px;
			background-color: rgba(255, 255, 255, 0.5);
		}

		h1 {
			font-size: 35px;
			margin-top: 0;
			color: #fff;
		}

		p {
			font-size: 18px;
			margin-bottom: 0;
			background-color: rgba(255, 255, 255, 0.5);
			padding: 20px;
			border-radius: 5px;
		}

		#btn-close {
			background-color: transparent;
			color: #fff;
			padding: 5px;
			border: none;
			position: absolute;
			top: 10px;
			right: 10px;
			font-size: 24px;
			cursor: pointer;
			z-index: 1;
		}

		#btn-inscrever {
			background-color: #007bff;
			color: #fff;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			font-size: 16px;
			cursor: pointer;
			margin-top: 20px;
		}
	</style>
</head>
<body>
	<div id="overlay">
		<div id="popup">
			<h1>As inscrições para novos pilotos estão abertas!</h1>
			<p>Venha fazer parte da nossa equipe!</p>
			<a href="http://www.privatevirtual.com.br/vam/index.php?page=school" target="_blank" id="btn-inscrever">Inscreva-se</a>
			<button id="btn-close" onclick="closePopup()">&times;</button>
		</div>
	</div>

	<script>
		function closePopup() {
			document.getElementById('overlay').style.display = 'none';
			document.cookie = "popup_shown=1; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
		}
	</script>
</body>
</html>
<?php
}
?>
