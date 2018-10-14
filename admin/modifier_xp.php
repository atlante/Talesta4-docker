<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: modifier_xp.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.18 $
$Date: 2010/02/28 22:58:09 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}

if(!isset($pnj)){$pnj=0;}

if ($pnj==0)
	$titrepage = $mod_xp;
else 
        $titrepage = $mod_xp;

if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierXP"])){
		
			$SQL = "DELETE FROM ".NOM_TABLE_COMP." WHERE id_perso = '".$id_cible."'";
			$result = $db->sql_query($SQL,"",BEGIN_TRANSACTION_JEU);
			if(isset($comp)&& ($result!==false)){
				$toto = array_keys($comp);
				$tata = array_values($comp);
				$debutSQL = "INSERT INTO ".NOM_TABLE_COMP." (id_perso,id_comp,XP) VALUES ";
				$nb_comp = count($comp);
				for($i=0;$i<$nb_comp && ($result!==false);$i++){
					if($tata[$i] != 0){
						$SQL = $debutSQL."('".$id_cible."','".$toto[$i]."','".$tata[$i]."')";
						$result=$db->sql_query($SQL);
					}
				}
				if ($result!==false)
					$MJ->OutPut("XP de  ".span(ConvertAsHTML($nom),"pj")." correctement modif&eacute;",true);
			}
			$MJ->OutPut($db->erreur);
		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape=="1"){
	$pos = strpos($id_cible, $sep);
	$libelle=substr($id_cible, $pos+strlen($sep)); 
	$id_cible=substr($id_cible, 0,$pos); 	

	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_COMP." T1 WHERE  T1.id_perso = ".$id_cible;
	$result = $db->sql_query($SQL);
	
	//$SQL = "SELECT * FROM ".NOM_TABLE_REGISTRE." T2 WHERE T2.id_perso = ".$id_cible;
	//$result2 = $db->sql_query($SQL);
	
	$template_main .= "Modifier l'XP de ".span(ConvertAsHTML($libelle),"pj")."<br />";
	//for($i=0;$i<$db->sql_numrows($result);$i++){
	while($row = $db->sql_fetchrow($result)){
			$comp[$row["id_comp"]]=$row["xp"];
	}
	include('forms/status.form.'.$phpExtJeu);

	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "<input type='hidden' name='nom' value='".ConvertAsHTML($libelle)."' />";
	$template_main .= "</form></div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel PJ voulez vous modifier ?<br />";
	$SQL = "Select concat(concat(T1.id_perso,'$sep'),T1.nom) as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 ";

	if ($pnj==2)
		$SQL .=" where T1.pnj=2 ";
        else $SQL .=" where T1.pnj<>2 ";
	$SQL .=" ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>