<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: menu.template.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.17 $
$Date: 2006/01/31 12:26:26 $

*/

require_once("../include/extension.inc");
    $menu_site="";
    $menu_temp = "<div id='menu'>\n";
    $menu_temp .=  "<img src='../templates/".$template_name."/images/menu_top.png' border='0' height='10' width='135' alt='menu_top.png' />\n";
    $juste_h4 = false;
    for($i=0;$i<count($liens_menu);$i++){
           if($liens_menu[$i][4]) {
              if($liens_menu[$i][0]==1) {
                 if($i != 0) {$menu_site.= "\t\n";}
                 $menu_site .= "\t";
                 $menu_site .= "<h4>";
                 if($liens_menu[$i][1] != ''){
                    if ($liens_menu[$i][3]=="") {
                       $menu_site .= "<a class='t1' href=\"".$liens_menu[$i][1]."\">".$liens_menu[$i][2]."</a>";
                    } else {
                       $menu_site .= "<a class='t1' href=\"".$liens_menu[$i][1]."\" target='".$liens_menu[$i][3]."'>".$liens_menu[$i][2]."</a>";
                    }
                 } else {
                 $menu_site .= $liens_menu[$i][2];
                 }
                 $menu_site .= "</h4>\n";
              }
              if(($liens_menu[$i][0]==2)) { 
                 if($i != 0) {$menu_site.= "\t\n";}
                 $menu_site .= "\t";
                 $menu_site .= "<h4>";
                 if($liens_menu[$i][1] != ''){
                    if ($liens_menu[$i][3]=="") {
                       $menu_site .= "<a class='t2' href=\"".$liens_menu[$i][1]."\">".$liens_menu[$i][2]."</a>";
                    } else {
                       $menu_site .= "<a class='t2' href=\"".$liens_menu[$i][1]."\" target='".$liens_menu[$i][3]."'>".$liens_menu[$i][2]."</a>";
                    }
                 } else {
                 $menu_site .= $liens_menu[$i][2];
                 }
                 $menu_site .= "</h4>\n";
              }
           }
        
        
    }

    if (defined("AFFICHE_CONNECTES") && AFFICHE_CONNECTES==1) {
    	$menu_site .= "<br />";
        include ("../include/online.".$phpExtJeu);
    }
    if ($menu_site!="") {
    	$menu_site = $menu_temp. $menu_site;
    	$menu_site .=  "<img src='../templates/$template_name/images/menu_bottom.png' height='10' width='135' border='0' alt='menu_bottom.png' class='bottom' />\n";
    	$menu_site .= "</div>";
   }
?>