<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: logout.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.9 $
$Date: 2006/01/31 12:26:24 $

*/

require_once("../include/extension.inc");
//if(!defined("__HTTPGETPOST.PHP")) include('../include/http_get_post.'.$phpExtJeu);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $logout_perso;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!defined("__ENDSESSION.PHP")){include('../identification/EndSession.'.$phpExtJeu);}
if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>