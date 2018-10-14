<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: logout.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.9 $
$Date: 2006/01/31 12:26:18 $

*/

require_once("../include/extension.inc");
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $logout_admin;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!defined("__ENDSESSIONMJ.PHP")){include('../identification/EndSessionMJ.'.$phpExtJeu);}
if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>