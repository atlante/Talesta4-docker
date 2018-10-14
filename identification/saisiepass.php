<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: saisiepass.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.12 $
$Date: 2006/01/31 12:26:25 $

*/

require_once("../include/extension.inc");

  $template_main .= "<div class ='centerSimple'><span class='texte_bigtitre'>Identification ";
  if ($Admin==1) $template_main .= "MJ"; else $template_main .= "PJ";$template_main .=" </span></div>";
  $template_main .= HR;
	$template_main .= "<span class='texte_titre'>Veuillez saisir votre nom et votre mot de passe</span>";
  $template_main .= HR;

$template_main .= "<div class ='centerSimple'>";
  $template_main .= "<form name='r' action='".NOM_SCRIPT."' method='post'>";
  $template_main .= "<table class='stats'>";
  $template_main .= "<tr><td class='stats' align='right'>Votre login : </td><td><input name='x0' type='text' size='30' maxlength='25' /></td></tr>";
  $template_main .= "<tr><td class='stats' align='right'>Votre mot de passe : </td><td><input name='x1' type='password' size='30' maxlength='50' /></td></tr>";
  $template_main .= "<tr><td class='stats' align='right' colspan='2'>Connection permanente<input type='checkbox' name='perm' /></td></tr>";
  $template_main .= "<tr><td><input type='hidden' name='Admin' value ="; 
  if (teste("Admin","1")) $template_main .= "'1' />"; 
  else $template_main .= "'0' />";
  $template_main .="</td></tr>";
  $template_main .= "<tr><td class='stats' align='center' colspan='2'>".BOUTON_ENVOYER."</td></tr>";
  $template_main .= "</table></form>";
  //$template_main .= "<a href='../commun/motdepasseperdu.$phpExtJeu?Admin=$Admin'> J'ai oubli&eacute; mon mot de passe</a></div>";
  $template_main .= "<form name='s' action='../commun/motdepasseperdu.$phpExtJeu' method='post'>";
  $template_main .= "<input type='hidden' name='Admin' value ="; 
  if (teste("Admin","1")) $template_main .= "'1' />"; 
  else $template_main .= "'0' />";
  $template_main .= "<input type='submit' value=\"J'ai oubli&eacute; mon mot de passe\" /></form></div>";
?>