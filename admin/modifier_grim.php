<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: modifier_grim.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.16 $
$Date: 2010/02/28 22:58:07 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}

if(!isset($pnj)){$pnj=0;}

if ($pnj==0)
	$titrepage = $mod_grim;
else 
        $titrepage = $mod_grim_bestiaire;

if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierInv"])){
			$result=true;
			if(isset($del)){
				$toto = array_keys($del);
				$tata = array_values($del);
				$SQL = "DELETE FROM ".NOM_TABLE_PERSOMAGIE." WHERE id_perso = '".$id_cible."' AND (";
				for($i=0;$i<count($del)&&$result;$i++){
					if($tata[$i] == "on"){
						$SQL.= " id_clef = '".$toto[$i]."' OR";
					}
				}
				$SQL = substr($SQL,0,strlen($SQL)-2).")";;
				$result=$db->sql_query($SQL);
				
			}
			
			if(isset($cha) && $result){
				$toto = array_keys($cha);
				$tata = array_values($cha);
				for($i=0;$i<count($cha)&&$result;$i++){
					$SQL = "UPDATE ".NOM_TABLE_PERSOMAGIE." SET charges = '".$tata[$i]."' WHERE id_perso = '".$id_cible."' AND id_clef = '".$toto[$i]."'";
					$result=$db->sql_query($SQL);
				}
			}

			//La on a efface et mis a jour
			if(isset($chaine)&&$result){
				$liste = explode(";",$chaine);
				for($i=0;($i<count($liste)-1)&&$result;$i++){
					$SQL = "SELECT * FROM ".NOM_TABLE_MAGIE." WHERE id_magie = ".$liste[$i];
					$result = $db->sql_query($SQL);
					if($db->sql_numrows($result) > 0){
						$row = $db->sql_fetchrow($result);
						$SQL = "INSERT INTO ".NOM_TABLE_PERSOMAGIE." (id_perso,id_magie,charges) VALUES ('".$id_cible."','".$liste[$i]."','".$row["charges"]."')";
						$result=$db->sql_query($SQL);
					}
					
				}
			}
			//Ici on ajoute
			if ($result)
				$MJ->OutPut("Grimoire de  ".span($nom,"pj")." correctement modif&eacute;",true);
			else $template_main .= $db->erreur;	
		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$id_cible=$id_cible.$sep.$nom;
	$etape=1;
}
if($etape=="1"){
	$pos = strpos($id_cible, $sep);
	$libelle=ConvertAsHTML(substr($id_cible, $pos+strlen($sep))); 
	$id_cible=substr($id_cible, 0,$pos); 
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_PERSOMAGIE." T1 WHERE T1.id_perso = ".$id_cible." ORDER BY T1.id_clef";
	$result = $db->sql_query($SQL);
		if($db->sql_numrows($result) > 0){
		$ListeSort = null;
		$compteur=0;
		//ObjetPJ
		$template_main .= "Modifier le grimoire de ".span($libelle,"pj")."<br />";
		//for($i=0;$i<$db->sql_numrows($result);$i++){
		$i=0;
		while($row = $db->sql_fetchrow($result)) {
				$ListeSort[$compteur]=new MagiePJ($row["id_magie"],$row["id_clef"],$i, $row["charges"]);
				if ($ListeSort[$compteur]!=null)
					$compteur++;					
				$i++;	
		}
		include('forms/grimoire.form.'.$phpExtJeu);
		
	} else {
		$template_main .= span($libelle,"pj")." n'a pas de sorts dans son grimoire";
	}
	$template_main .= "<input type='hidden' name='nom' value='".$libelle."' />";
	$template_main .= '<input type="hidden" name="chaine" value="" />';
	$template_main .= "<br /><input type='submit' value='Envoyer' onclick=\"return confirm('Etes vous sur de vouloir effacer les sorts eventuellement selectionnes ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "</form>";
	include('forms/grimoire2.form.'.$phpExtJeu);
	$template_main .= "</div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Le grimoire de quel PJ voulez vous vous modifier ?<br />";
	$SQL = "Select concat(concat(T1.id_perso,'$sep'),T1.nom) as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 ";

	if ($pnj==2)
		$SQL .=" where T1.pnj=2 ";
        else $SQL .=" where T1.pnj<>2 ";
	$SQL .=" ORDER BY T1.nom ASC";
	$var= faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>