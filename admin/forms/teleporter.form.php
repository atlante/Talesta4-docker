<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: teleporter.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.7 $
$Date: 2006/01/31 12:26:22 $

*/

$template_main .= "<table class='detailscenter'>";
	$template_main .= "<tr><td>Lieu actuel de ". span($nom,"pj").":</td><td>".span($trigramme."-".$nomlieu,"lieu")."</td></tr>";
	$template_main .= "<tr><td>Dissimulé dans ce lieu:</td><td>".faitOuiNon("Anciendissimule"," disabled='disabled'  ",$dissimule)."</td></tr>";
	$template_main .= "<tr><td>A déplacer vers :</td><td>";
	$SQL = "SELECT id_lieu as idselect, concat(concat(trigramme,'-'),nom) as labselect FROM ".NOM_TABLE_LIEU." where id_lieu <> ". $row["idlieu"] ." ORDER BY trigramme, nom";
	$var=faitSelect("id_lieu",$SQL,"",$idlieu);
	$template_main .= $var[1];
	$template_main .= "</td></tr>";
	$template_main .= "<tr><td>Le dissimuler dans le Lieu :</td><td>".faitOuiNon("dissimule","",$dissimule)."</td>";
	$template_main .= "</table>";
?>