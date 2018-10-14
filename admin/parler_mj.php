<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: parler_mj.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.14 $
$Date: 2006/02/21 21:47:03 $

*/

require_once("../include/extension.inc");if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $parler_mj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}

if($etape=="1"){
	if($MJ->aDroit($liste_flags_mj["ParlerMJ"])){
		$msg = str_replace("<","&lt;",$msg); 
		$msg = str_replace(">","&gt;",$msg); 		
		if ($typeact=='un') {
			$msg_mjdest = "**** Message de ".span($MJ->nom." (MJ)","mj")." *******<br />".$msg;
			$MJdest = new MJ($id_cible);
			$msg_mj = "**** Message envoy&eacute; &agrave; ".span($MJdest->nom." (MJ)","mj")." *******<br />".$msg;
			$MJ->OutPut($msg_mj,true);
			if ($id_cible<> $MJ->ID)
				$MJdest->OutPut($msg_mjdest,false,true);
		}
		else 
		if ($typeact=='tousMJ') {
			$chaine="";
			$SQL = "Select id_mj, nom from ".NOM_TABLE_MJ ." where id_mj <> ". $MJ->ID;
			$result = $db->sql_query($SQL);
					
			$nb_mj = $db->sql_numrows($result);
			$i=0;
			$msg_mj = "***** Message de ".span($MJ->nom." (MJ)","mj")." &agrave; l'ensemble des MJs:<br />".$msg;
			while(	$row = $db->sql_fetchrow($result)){
				$mjtemp = new MJ($row["id_mj"]);
				$chaine .= span($row["nom"],"mj");
				$mjtemp->OutPut($msg_mj,false,true);
				if($i != ($nb_mj-1) ){$chaine .= ", ";}		
				$i++;
			}
			$msg_soi = "***** Message envoy&eacute; &agrave; l'ensemble des MJs, soit &agrave; ".span($chaine,"mj")." ****** <br />".$msg;	
			$MJ->OutPut($msg_soi,true,true);
		}
		else if ($typeact=='tous') {			
			$chaine="";
			$SQL = "Select id_perso, nom  from ".NOM_TABLE_REGISTRE;
			$result = $db->sql_query($SQL);
					
			$nb_pj = $db->sql_numrows($result);
			$i=0;
			$msg_mj = "***** Message de ".span($MJ->nom." (MJ)","mj")." &agrave; l'ensemble des PJs et MJs:<br />".$msg;
			if ($nb_pj > 0 ) {
				while(	$row = $db->sql_fetchrow($result)){
					$mjtemp = new Joueur($row["id_perso"],false,false,false,false,false,false);
					$chaine .= span($row["nom"],"mj").", ";
					$mjtemp->OutPut($msg_mj,false,true);
					$i++;
				}
			}
			$SQL = "Select id_mj, nom from ".NOM_TABLE_MJ ." where id_mj <> ". $MJ->ID;
			$result = $db->sql_query($SQL);
					
			$nb_mj = $db->sql_numrows($result);
			$i=0;
			if ($nb_mj > 0 ) {
				while(	$row = $db->sql_fetchrow($result)){
					$mjtemp = new MJ($row["id_mj"]);
					$chaine .= span($row["nom"],"mj");
					$mjtemp->OutPut($msg_mj,false,true);
					if($i != ($nb_mj-1) ){$chaine .= ", ";}		
					$i++;
				}
			}			
			$msg_soi = "***** Message envoy&eacute; &agrave; l'ensemble des PJs et MJs, soit &agrave; ".span($chaine,"mj")." ****** <br />".$msg;	
			$MJ->OutPut($msg_soi,true,true);
		}		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape===0){
	if(!isset($msg)){$msg='';}
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "Select T1.ID_MJ as idselect, concat(concat(concat(T1.nom,'  ('),T1.titre),')') as labselect from ".NOM_TABLE_MJ." T1 ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	if ($var[0]>0) {
		$template_main .= "<br /><input type='radio' name='typeact' value='un' checked='checked' />Parler &agrave; ";
		//$template_main .= "A qui voulez vous parler ?<br />";
		$template_main .= $var[1];
		$template_main .= "<br /><input type='radio' name='typeact' value='tousMJ' />Parler &agrave; tous les MJ<br />";
	}	
	$template_main .= "<br /><input type='radio' name='typeact' value='tous' />Parler &agrave; tous les PJ et MJs<br />";

	$template_main .= "<br />Message:<br />";
	$template_main .= "<textarea name='msg' cols='50' rows='20'>".stripslashes($msg)."</textarea>";
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>