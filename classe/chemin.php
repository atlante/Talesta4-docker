<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: chemin.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.11 $
$Date: 2006/01/31 12:26:22 $

*/

require_once("../include/extension.inc");
if(!defined("__CHEMIN.PHP") ) {
	Define("__CHEMIN.PHP",	0);

	class Chemin{
		
		var $ID;
		var $Arrivee;
		var $type;
		var $difficulte;
		var $pass;
		var $distance;
		var $Depart;

		function Chemin($ID_Chemin, $Lieu_1,$id_lieu_2,$type,$difficulte,$pass,$distance){
			$this->ID = $ID_Chemin;

			$this->Arrivee = new Lieu($id_lieu_2,false);
			$this->Depart = $Lieu_1;
			$this->type		 = $type;
			$this->difficulte = $difficulte;
			$this->pass = $pass;
			$this->distance = $distance;
		}
	}
}

?>