<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: post.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.2 $
$Date: 2006/01/31 12:26:26 $

*/

require_once("../include/extension.inc");
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
  $url = makeUrl($chemin);
  
  switch($chemin) {
  	case "/../admin/modifier_armurerie.php":
  		$data["etape"]=1;
  		$data["id_cible"]=$id_cible;

  		break;
  	default:
  		logdate("chemin non prevu dans include/post");
  }
  envoi ($data,$url);  

  
?>   


