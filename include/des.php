<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: des.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.7 $
$Date: 2006/09/05 06:44:43 $

*/

if(!defined("__DES.PHP") ) {
	Define("__DES.PHP",	0);
		srand((double)microtime()*1000000);
	function LanceDe($nbFaces=20){

		return 1+rand(0,$nbFaces);
	}

}
?>