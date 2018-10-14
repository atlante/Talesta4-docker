<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: listeConstantePerso.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2010/01/24 16:37:09 $

*/


$template_main .= $nomListe."<br />";
$template_main .= "<table class='details'>";
for($i=0;$i<$compteur;$i++){
	$template_main .= "<tr>";
	$template_main .= "<td>Supprimer<input type='checkbox' name='del_". $nomListe . "[".$key."]' /></td>";
	$template_main .= "<td><input type='text' name='libelle". $nomListe . "[".$key."]' value='$value' />
	<input type='text' name='old_libelle". $nomListe . "[".$key."]' value='$value' /></td>";
	$template_main .= "</tr>\n";
}
$template_main .= "</table>";
?>