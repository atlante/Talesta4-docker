<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: competence.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.6 $
$Date: 2006/01/31 12:26:20 $

*/


$template_main .= "<table class='detailscenter'>";
for($i=0;$i<$compteur;$i++){
	$template_main .= "<tr>";
	$template_main .= "<td>Supprimer<input type='checkbox' name='del_[".$ListeObj[$i]."]' /></td>";
	$template_main .= "<td width='2'>".GetImage($ListeObj[$i])."</td>";
	$template_main .= "<td>".$ListeObj[$i]."</td>";

	$template_main .= "</tr>";
}
$template_main .= "</table>";
?>