<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: guilde.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.13 $
$Date: 2006/02/21 18:27:19 $

*/

	$template_main .= "<table class='detailscenter' width='60%'>";
	$template_main .= "<tr><td>nom de la Guilde</td><td><input type='text' name='nomGuilde' size='35' maxlength='50' value='' /></td></tr>";
	if(defined("IN_FORUM")&& IN_FORUM==1) {
		$template_main .= "<tr><td>G&eacute;rant</td><td>";
			$SQL = $forum->selectMembres();
			$var=faitSelect("gerant",$SQL,"",$gerant);
			$template_main .= $var[1];
		$template_main .= "</td></tr>";
	}	
	$template_main .= "<tr><td colspan='2'>description</td></tr>";
	$template_main .= "<tr><td colspan='2'><textarea name='DescGuilde' cols='40' rows='10'></textarea></td></tr>";
	$template_main .= "</table><br />";
	$template_main .= "<table class='detailscenter' width='60%'>";
	$template_main .= "<tr><td colspan='2'>Lieu principal de la Guilde et chemin pour rattacher la guilde au monde 'officiel' &agrave; cr&eacute;er (optionnel) </td></tr></table>";
	include ("chemin.form.".$phpExtJeu);

	include ("lieu.form.".$phpExtJeu);
	
	$template_main .= "<table class='detailscenter' width='60%'>";	
	$template_main .= "<tr><td colspan='2'>Valider ? : <select name='valider'><option value='0'>Non</option><option value='1'>Oui</option></select></td></tr>";
	$template_main .= "</table><br />";

?>