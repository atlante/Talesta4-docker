<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: barregenerale.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.9 $
$Date: 2006/01/31 12:26:26 $

*/

if(!defined("__BARREGENERALE.PHP") ) {
	Define("__BARREGENERALE.PHP",	0);
	$barre = "<div id='barre_container'><div id='barre_vide'>";
	if ((NOM_SCRIPT=="lien_map_voirLieu.".$phpExtJeu ||NOM_SCRIPT=="voir_lieu.".$phpExtJeu) && isset($libelle)) {
		$a_afficher=array(
					"&nbsp;",
					"&nbsp;",
					"&nbsp;",
					"&nbsp;",
					span ($libelle,"lieu"),
					"&nbsp;",
					// Les barres qui vont avec ...
					"&nbsp;",
					"&nbsp;",
					"&nbsp;",
					"&nbsp;",
					"&nbsp;",
					"&nbsp;"
					
			);
		
		$barre .= makeTableau(6,"center","",$a_afficher);
	}
	$barre .= "<table width='100%'><tr><td>&nbsp;</td></tr></table>";
	$barre .= "</div></div>";
	
}
?>