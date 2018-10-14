<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: parler_lieu.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.21 $
$Date: 2006/02/22 07:26:03 $

*/

require_once("../include/extension.inc");if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $parler_lieu;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}

if($etape=="1"){
	if($MJ->aDroit($liste_flags_mj["ParlerLieu"])){
		$msg = str_replace("<","&lt;",$msg); 
		$msg = str_replace(">","&gt;",$msg); 
		$pos = strpos($id_cible, $sep);
		$libelle=substr($id_cible, $pos+strlen($sep)); 
		$id_cible=substr($id_cible, 0,$pos); 
		//$Lieu = new Lieu($id_cible);
		$SQL = "Select * from ".NOM_TABLE_REGISTRE." T1 WHERE T1.id_lieu = ".$id_cible." ORDER BY T1.nom ASC";
		$result=$db->sql_query($SQL);
		if ($db->sql_numrows($result)>0) {
			$msg_pj = "**** Message de ".span($MJ->nom." (MJ)","mj")." pour le lieu ".span($libelle,"lieu")." *******<br />".$msg;
			$msg_mj = "**** Message envoy&eacute; &agrave; ".span($libelle,"lieu"). " soit &agrave; ";
			//for($i=0;$i<$db->sql_numrows($result);$i++){
			while(	$row = $db->sql_fetchrow($result)){
				$JOUEUR = new Joueur($row["id_perso"],false,false,false,false,false,false);
				$JOUEUR->OutPut($msg_pj,false,true);
				$msg_mj .= $JOUEUR->nom.", ";
			}
			$msg_mj .=" *******<br />".$msg;
			$MJ->OutPut($msg_mj,true);
		} else $template_main .= "Il n'y a aucun PJ dans ce lieu. <br />";	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape===0){
	if(!isset($msg)){$msg='';}
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Dans quel lieu voulez vous parler ?<br />";
	$SQL = "SELECT concat(concat(concat(concat(id_lieu,'$sep'),trigramme),'-'),nom) as idselect, concat(concat(trigramme,'-'),nom) as labselect FROM ".NOM_TABLE_LIEU." ORDER BY trigramme, nom";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />Message:<br />";
	$template_main .= "<textarea name='msg' cols='50' rows='20'>".stripslashes($msg)."</textarea>";
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>