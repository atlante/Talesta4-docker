<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: index.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.15 $
$Date: 2006/01/31 12:26:27 $

*/

require_once("../include/extension.inc");
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $accueil;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if (defined ("NOM_JEU"))
	$template_main .= "<p class='justify'><span class='fontSize3'>Le Monde de ".NOM_JEU."</span></p>";

$template_main .= "<p class='justify'>Si vous n'avez jamais jou&eacute; &agrave; un jdr ou &agrave; un pbem tout vous sera expliqu&eacute; dans la section <a href='./pbem.".$phpExtJeu."'>Qu'est-ce donc que tout ceci ?</a></p>
<p class='justify'>Avant d'aller cr&eacute;er son personnage, il serait bien d'en savoir un peu plus.</p>
<p class='justify'>Tout d'abord, allons regarder <a href='./aide_inscription.".$phpExtJeu."'> ici</a> où vous pouvez trouver de l'aide pour cr&eacute;er votre personnage et ainsi &eacute;viter des erreurs qui vous ferez refuser l'acc&egrave;s au jeu</p>";

if (defined ("IN_FORUM") && IN_FORUM==1) $template_main .= "
<p class='justify'>Vous pouvez aussi jeter un coup d'oeil au <a href='./forum.".$phpExtJeu."'>forum</a> pour ressentir l'ambiance.</p>";
if (defined ("IN_NEWS") && IN_NEWS==1) $template_main .= "
<p class='justify'> Voici les dernières <a href='../news'>actualités</a>  du site.</p>";

$template_main .= "<p class='justify'>Vous &ecirc;tes fin pr&ecirc;ts. Vous pouvez aller dans la section <a href='./inscription.".$phpExtJeu."'>Inscription</a></p>
<p class='justify'>En attendant que votre inscription soit prise en compte, nous pouvons regarder ensemble l'<a href='./aide_interface.".$phpExtJeu."'>interface</a> afin de mieux comprendre son fonctionenement.</p>
<p class='justify'>Pour finir, vous pouvez consulter <a href='./joueurs.".$phpExtJeu."'>la liste des personnages présents ici</a></p>";
if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>