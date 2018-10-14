<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: question.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.5 $
$Date: 2006/01/31 12:26:21 $

*/

	if (NOM_SCRIPT==("supprimer_question.".$phpExtJeu))
		{ $readonly= " readonly='readonly'  ";$disabled= " disabled='disabled'  "; }
	else { $readonly= "";$disabled= ""; }

	$template_main .= "<table class='detailscenter'>\n";
	$template_main .= "<tr><td>Question</td><td><input type='text' name='question' $readonly size='50' maxlength='128' value='".$question."' /></td></tr>\n";
	$template_main .= "<tr><td>Reponse1</td><td><input type='text' name='reponse1' $readonly size='50' maxlength='128' value='".$reponse1."' /></td></tr>\n";
	$template_main .= "<tr><td>Reponse2</td><td><input type='text' name='reponse2' $readonly size='50' maxlength='128' value='".$reponse2."' /></td></tr>\n";
	$template_main .= "<tr><td>Reponse3</td><td><input type='text' name='reponse3' $readonly size='50' maxlength='128' value='".$reponse3."' /></td></tr>\n";
	$template_main .= "<tr><td>Reponse4</td><td><input type='text' name='reponse4' $readonly size='50' maxlength='128' value='".$reponse4."' /></td></tr>\n";
	$template_main .= "<tr><td>Bonne Réponse</td><td><input type='text' name='bonne' $readonly size='2' maxlength='1' value='".$bonne."' /></td></tr>\n";

	if (NOM_SCRIPT<>("supprimer_question.".$phpExtJeu))
		$template_main .= "<tr><td>Valider ? : </td><td><select name='valider'><option value='1'>Oui</option><option value='0'>Non</option></select></td></tr>\n";
	
	$template_main .= "</table><br />\n";

?>