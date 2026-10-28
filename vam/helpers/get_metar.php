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
	function get_metar($location)
	{
		$url ="http://aviationweather.gov/adds/metars/?station_ids=".$location."&std_trans=translated&chk_metars=on&hoursStr=past+1+hours&chk_tafs=on&submitmet=Submit";
		$fileName = $url;
		$metar = '';
		$fileData = @file($fileName) or die('METAR not available');
		if ($fileData != false) {
			$date = array_shift($fileData);
			$utc = strtotime(trim($date));
			$time = date("D, F jS Y g:i A" , $utc);
			foreach ($fileData as $line) {
				$metar .= ' ' . trim($line);
			}
			$metar = trim(str_replace('  ' , ' ' , $metar));
			$metar = str_replace("Aviation Digital Data Service (ADDS)","",$metar);
			$metar = str_replace("Output produced by METARs form","",$metar);
			$metar = str_replace("found at","Data provider",$metar);
			echo "METAR FOR $location <br>$metar";
		}
	}
?>
