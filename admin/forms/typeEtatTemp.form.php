<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: typeEtatTemp.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.5 $
$Date: 2006/01/31 12:26:22 $

*/

	if (NOM_SCRIPT==("supprimer_typeetat.".$phpExtJeu))
		{ $readonly= " readonly='readonly'  ";$disabled= " disabled='disabled'  "; }
	else { $readonly= "";$disabled= ""; }

	$template_main .= "<p>&nbsp;</p><table class='detailscenter'>";
	$template_main .= "<tr><td colspan='3'><a href=\"javascript:a('gethelp.$phpExtJeu?page=creer_typeEtat.htm')\">Aide</a></td></tr>";
	$template_main .= "<tr><td colspan='2'>nom du type : </td><td><input type='text' name='nomtype' $readonly value='".$nomtype."' size='35' maxlength='25' /></td></tr>\n";
	$template_main .= "<tr><td colspan='2'>critere d'inscription: </td><td>".faitOuiNon("critereinscription1",$disabled,$critereinscription1)."</td></tr>\n";
	$template_main .= "<tr><td>&nbsp;</td><td>Si critere d'inscription, choix obligatoire à l'inscription: </td><td>".faitOuiNon("choixObligatoire",$disabled,$choixObligatoire)."</td></tr>\n";	
	$template_main .= "<tr><td>&nbsp;</td><td>Si critere d'inscription, critere modifiable par le PJ Post-inscription: </td><td>".faitOuiNon("modifiableparpj",$disabled,$modifiableparpj)."</td></tr>\n";	
	$template_main .= "</table>\n";
?>