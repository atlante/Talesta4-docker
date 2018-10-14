<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: index.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.11 $
$Date: 2006/01/31 12:26:22 $

*/

require_once("../include/extension.inc");
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$template_main .= "<p>Bienvenue dans la BDC (base de connaissance) de ".NOM_JEU.".</p>";
$template_main .= "<p> Ici vous trouverez toutes les informations sur les objets, les sorts, les sp&eacute;cialisations et les &eacute;tats temporaires du jeu.</p>";
$template_main .= "<p>Pour des raisons &eacute;videntes de securit&eacute;, ces informations ne sont disponibles que via votre fiche de personnage ou via l'interface MJ. Cliquez sur un nom d'objet, de sort, de sp&eacute;cialisation ou d'&eacute;tat temporaire et, magie, vous verrez apparaitre ses d&eacute;tails dans une petite fen&ecirc;tre.</p>";
if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>