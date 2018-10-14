<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: about.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.16 $
$Date: 2006/01/31 12:26:27 $

*/

require_once("../include/extension.inc");
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $about;

if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$template_main .= file_get_contents ('../templates/'.urldecode($template_name).'/about.tpl');

if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);} 
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);} 
?>