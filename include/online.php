<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: online.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.9 $
$Date: 2010/01/24 19:33:12 $

*/
 
//if(!defined("__BDD.PHP")){include('../include/bdd.'.$phpExtJeu);}

//$menu_site .=" <img src='../templates/$template_name/images/menu_top.jpg' border='0' height='35' width='135' alt='menu_top.jpg' />";
$menu_site .= "<p> &nbsp; </p><h4 class='centerSimple'><u>". GetMessage("LesConnectes")."</u><br /></h4>";

//Pour les joueurs
$noms_j =$db->sql_query("SELECT nom FROM ".NOM_TABLE_SESSIONS.", ".NOM_TABLE_REGISTRE." WHERE id_joueur=id_perso AND pj=1 and (datestart + duree > " . time() . " or permanent = 1)");
if ($db->sql_numrows($noms_j)) {
	$menu_site .= "<h4>&nbsp;&nbsp;". GetMessage("Joueurs").":</h4>";
	while ($nom_j=$db->sql_fetchrow($noms_j)) {
		$menu_site .= "<h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$nom_j["nom"]."</h4>";
	}
}

//Pour les MJs purs (on cache les MJ qui ne sont que des PJs avec des droits de MJs)

	if ((isset($dbmsJeu) && $dbmsJeu!="mysql") || $db->versionServerDiscriminante()>=4.1) {
		//requete a utiliser en sql 4.1 et + pour mysql ou pour les autres bases
		$noms_mj=$db->sql_query("SELECT nom FROM ".NOM_TABLE_SESSIONS.", ".NOM_TABLE_MJ." WHERE id_joueur=id_mj AND pj=0  and (datestart + duree > " . time() . " or permanent = 1)".
		" and not exists (select 1 from ". NOM_TABLE_REGISTRE." WHERE role_mj = id_mj)");
	}
	else {	
		//requete de merde en 4.0 et - pour mysql qui n'est pas compatible SQL92
		$noms_mj=$db->sql_query("SELECT ".NOM_TABLE_MJ.".nom FROM ".NOM_TABLE_SESSIONS.", ".NOM_TABLE_MJ." left join ". NOM_TABLE_REGISTRE
		." on role_mj = id_mj  WHERE id_joueur=id_mj AND pj=0  and (datestart + duree > " . time() . " or permanent = 1) and role_mj is null ");
	}


if ($db->sql_numrows($noms_mj)) {
	$menu_site .= "<h4>&nbsp;&nbsp;". GetMessage("MJs").":</h4>";
	while (($nom_mj = $db->sql_fetchrow($noms_mj))!==false) {
		$menu_site .= "<h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$nom_mj["nom"]."</h4>";
	}
}
//$db->sql_freeresult($noms_j);
//$db->sql_freeresult($noms_mj);
?>