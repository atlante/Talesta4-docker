<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: purgeFichierLog.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2010/02/28 22:58:09 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $purgerLogs;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}

if($etape===0){
       // if($MJ->aDroit($liste_flags_mj["PurgerLogs"])){
                if (defined("FICHIER_LOG"))
                        if (file_exists(FICHIER_LOG))
        			if((unlink(FICHIER_LOG))===false)
        				$template_main .= "Impossible d'effacer le fichier '".FICHIER_LOG."'";
        			else 	$template_main .= "Fichier '".FICHIER_LOG."' effacé";
        		else 	$template_main .= "Fichier '".FICHIER_LOG."' inexistant";
               	else 	$template_main .= "Variable 'FICHIER_LOG' non définie";
       //}
       //else $template_main .= GetMessage("droitsinsuffisants");                        		

}

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>