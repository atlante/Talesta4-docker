<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: aide_inscription.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.8 $
$Date: 2006/01/31 12:26:27 $

*/

require_once("../include/extension.inc");
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $aide_inscription;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}


$template_main .="<div class ='centerSimple'><form action='inscription.$phpExtJeu' method='post'>S'inscrire<br />
<table class='detailscenter'><tr><td>nom du Personnage</td><td><input type='text' name='nom' size='35' maxlength='25' value='' /></td><td><span class='c0'>Le nom de votre PJ, (25 char max)</span></td></tr>
<tr><td>Mot de passe</td><td><input type='password' name='pass1' size='35' maxlength='50' /></td><td><span class='c0'>Le mot de passe à ne communiquer à personne </span></td></tr>
<tr><td>Retappez votre Mot de passe</td><td><input type='password' name='pass2' size='35' maxlength='50' /></td></tr>
<tr><td>email</td><td><input type='text' name='email' size='35' maxlength='80' value='' /></td><td><span class='c0'>Votre E-mail, pour vous prévenir d'actions faites sur votre PJ </span></td></tr>
<tr><td>Race</td><td><select name='id_race'>
	<option value='13'>Elfe</option>
	<option value='14'>Nain</option>
	<option value='16'>Humain</option>
	<option value='17'>Mannequin</option>
	<option value='18'>Gobelin</option>
</select></td><td><span class='c0'>La race du PJ, avec les bonus/malus que cela comporte et le background que cela implique</span></td></tr>
<tr><td>Sexe</td><td><select name='id_sexe'>
	<option value='9'>Femelle</option>
	<option value='10'>Male</option>
</select></td><td><span class='c0'>Le sexe du PJ, avec les bonus/malus que cela comporte et le background que cela implique</span></td>
</tr><tr><td>Cat&eacute;gorie d'âge</td><td><select name='id_categorieage'>
	<option value='28'>Enfant</option>
	<option value='29'>Jeune Adulte</option>
	<option value='30'>Adulte expérimenté</option>
	<option value='31'>Viellard</option>
</select></td><td><span class='c0'>L'âge du PJ, avec les bonus/malus que cela comporte et le background que cela implique</span></td></tr>
<tr><td>description <b>(visible par les autres PJS. environ 20 lignes. OBLIGATOIRE)	</b></td>
<td><textarea name='Desc' cols='40' rows='10'></textarea></td><td><span class='c0'>description (physique et morale)</span></td></tr>
<tr><td>Background <b>(invisible par les autres PJS. environ 20 lignes. OBLIGATOIRE)	</b></td>
<td><textarea name='Desc' cols='40' rows='10'></textarea></td><td><span class='c0'>Votre passé, vos projets dans le jeu </span></td></tr>


</table><input type='hidden' name='etape' value='1' /></form></div>";


if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
