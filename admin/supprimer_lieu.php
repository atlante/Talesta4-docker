<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: supprimer_lieu.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.20 $
$Date: 2010/02/28 22:58:10 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $suppr_lieu;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
$liste_champs=array(
		"nom","flags","trigramme","accessible_telp","id_forum","provoqueetat","difficultedesecacher","cheminfichieraudio" 
		//, "typemimefichieraudio"
		, "id_etattempspecifique","apparition_monstre","type_lieu_apparition"
	);

if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["SupprimerLieu"])){
		if($id_cible != 1){
			$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET LASTACTION = '".time()."', id_lieu=1 WHERE id_lieu = '".$id_cible."'";
			if ($db->sql_query($SQL,"",BEGIN_TRANSACTION_JEU)) {
				$SQL = "DELETE FROM ".NOM_TABLE_CHEMINS." WHERE id_lieu_1 = '".$id_cible."' OR id_lieu_2 = '".$id_cible."'";
				if ($db->sql_query($SQL)) {
					$SQL = "DELETE FROM ".NOM_TABLE_MAGASIN." WHERE id_lieu = '".$id_cible."'";
					if ($db->sql_query($SQL)) {
						$SQL = "DELETE FROM ".NOM_TABLE_LIEU." WHERE id_lieu = '".$id_cible."'";
						if ($db->sql_query($SQL,"",END_TRANSACTION_JEU)) {
							if(file_exists("../lieux/vues/view".$id_cible.".jpg"))
								if ((unlink ("../lieux/vues/view".$id_cible.".jpg"))===false)
									$template_main .= "Impossible d'effacer le fichier '../lieux/vues/view".$id_cible.".jpg'";
							if(file_exists("../lieux/vues/view".$id_cible.".gif"))
								if((unlink ("../lieux/vues/view".$id_cible.".gif"))===false)
									$template_main .= "Impossible d'effacer le fichier '../lieux/vues/view".$id_cible.".gif'";
							if(file_exists("../lieux/vues/view".$id_cible.".png"))
								if((unlink ("../lieux/vues/view".$id_cible.".png"))===false)
									$template_main .= "Impossible d'effacer le fichier '../lieux/vues/view".$id_cible.".png'";
							if(file_exists("../lieux/descriptions/desc_".$id_cible.".txt"))
								if((unlink ("../lieux/descriptions/desc_".$id_cible.".txt"))===false)
									$template_main .= "Impossible d'effacer le fichier '../lieux/descriptions/desc_".$id_cible.".txt'";
							$MJ->OutPut("Lieu ".span(ConvertAsHTML($nom),"objet")." correctement effac&eacute;",true);
						}
					}	
				}
			}
			$MJ->OutPut($db->erreur);
		}	
		else $MJ->OutPut("Impossible d'effacer le lieu d'ID =1");	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape=="1"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_LIEU." WHERE id_lieu = ".$id_cible;
	$result = $db->sql_query($SQL);
		$row = $db->sql_fetchrow($result);
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
			$$liste_champs[$i] = $row[$liste_champs[$i]];
	}
	include('forms/lieu.form.'.$phpExtJeu);
	$template_main .= "<br /><input type='submit' value='effacer' onclick=\"return confirm('Etes vous sur de vouloir effacer le Lieu ".$row["nom"]." ?')\">";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."'>";

	$tmp = explode("|",$provoqueetat);
	$i=0;
	$provoqueetatValue="";
	while ($tmp[$i]) {	
		$temp = explode(";",$tmp[$i]);
		$SQL = "SELECT nom FROM ".NOM_TABLE_ETATTEMPNOM." WHERE id_etattemp = ".$temp[0];
		$result=$db->sql_query($SQL);	
		$row = $db->sql_fetchrow($result);
		$provoqueetatValue.="<option value='".$tmp[$i]."'>".$row["nom"]
		. ";" . $temp[1] . '%;' . $temp[2] ." h"
		."</option>";	
		$i++;
	}
	$template_main .= '<input type="hidden" name="chaine" value="'.$provoqueetat.'" />';
	$template_main .= "</form>"; 

	include('forms/objet2.form.'.$phpExtJeu);
	$template_main .= "</div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel Lieu voulez vous supprimer ?<br />";
	$SQL = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 WHERE T1.id_lieu > 1 ORDER BY T1.trigramme, T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>