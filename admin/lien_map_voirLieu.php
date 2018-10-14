<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: lien_map_voirLieu.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.2 $
$Date: 2006/01/31 12:26:17 $

*/
 
 
require_once("../include/extension.inc");
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
	if(!defined("__HTTPGETPOST.PHP"))
		{include('../include/http_get_post.'.$phpExtJeu);}

if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $map_jeu;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if (isset($lieu)) {
	$SQL = "SELECT concat(concat(concat(concat(concat(concat(id_lieu,'$sep'),trigramme),'-'),nom),'$sep'),ifnull( cheminfichieraudio , '' )) as idselect, concat(concat(trigramme,'-'),nom) as labselect FROM ".NOM_TABLE_LIEU." where id_lieu = ".$lieu;
	$result = $db->sql_query($SQL);
	if($db->sql_numrows($result) == 0) 
		$template_main .="<span class='c1'>Aucun lieu avec cet ID</span><br />";
	else {
		$row = $db->sql_fetchrow($result);
		$id_cible= $row["idselect"];
		$etape="1";
		include("./voir_lieu.".$phpExtJeu);
	}
}	
if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>
