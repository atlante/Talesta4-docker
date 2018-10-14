<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: supprimer_objet.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.19 $
$Date: 2010/02/28 22:58:10 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $suppr_obj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
$liste_champs=array(
		"nom","type","sous_type","degats_min","degats_max","durabilite","prix_base","description","poids","image","permanent",
		"munitions","caracteristique","competence","provoqueetat","competencespe","anonyme","id_etattempspecifique","composantes"
	);

if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["SupprimerObjet"])){
		if($id_cible != 1){
			$toto = array_keys($liste_type_objetSecret);
			$SQL = "DELETE FROM ".NOM_TABLE_PERSOOBJET." WHERE id_objet = '".$id_cible."'";
			if ($db->sql_query($SQL,"",BEGIN_TRANSACTION_JEU)) {
				$SQL = "select id from ".NOM_TABLE_ENTITECACHEE ." where    nom = '".$nom ."' and type = ".$toto[1];
				$requete=$db->sql_query($SQL);	
				$requete2=true;
				if ($db->sql_numrows($requete)>0) {
					while($row = $db->sql_fetchrow($requete) && ($requete2!==false)) {
						$SQL="delete from ".NOM_TABLE_ENTITECACHEECONNUEDE ." where id_entitecachee=" . $row["id"];
						if($requete2=$db->sql_query($SQL)) {
							$SQL="delete from ".NOM_TABLE_ENTITECACHEE ." where id=" . $row["id"];
							$requete2=$db->sql_query($SQL);	
						}
					}
				}
				if($requete2) {
					$SQL = "DELETE FROM ".NOM_TABLE_MAGASIN." WHERE (type = '".$liste_types_magasins["Armurerie"]."' OR type = '".$liste_types_magasins["Quincaillerie"]."') AND pointeur = '".$id_cible."'";
					if ($db->sql_query($SQL)) {
						$SQL = "DELETE FROM ".NOM_TABLE_OBJET." WHERE id_objet = '".$id_cible."'";
						if ($db->sql_query($SQL, "", END_TRANSACTION_JEU))				
							$MJ->OutPut("Objet ".span(ConvertAsHTML($nom),"objet")." correctement effac&eacute;",true);
					}		
				}	
			}	
			$MJ->OutPut($db->erreur);
		}		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape=="1"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_OBJET." WHERE id_objet = ".$id_cible;
	$result=$db->sql_query($SQL);	
	$row = $db->sql_fetchrow($result);	
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
			$$liste_champs[$i] = $row[$liste_champs[$i]];
	}
	$type = $type.";".$sous_type;
	$template_main .= "<table class='detailscenter'>";
	include('forms/objet.form.'.$phpExtJeu);
	$template_main .= "<tr><td colspan='2' align='center'><input type='submit' value='effacer' onclick=\"return confirm('Etes vous sur de vouloir effacer l\'objet ".$row["nom"]." ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' /></td></tr>";
	$template_main .= "</table></form>";


	$tmp = explode("|",$provoqueetat);
	$i=0;
	$provoqueetatValue="";
	while ($tmp[$i]) {	
		$temp = explode(";",$tmp[$i]);
		//supprime le moins eventuel
		if ($temp[0]{0}=="-") {
			$operateur="etatsupprime";
			$temp[0]=substr($temp[0],1);
		}
		else $operateur="etatajout";			
		$SQL = "SELECT nom FROM ".NOM_TABLE_ETATTEMPNOM." WHERE id_etattemp = ".$temp[0];
		$result=$db->sql_query($SQL);	
		$row = $db->sql_fetchrow($result);
		$provoqueetatValue.="<option value=\"".$tmp[$i]."\" class=\"".$operateur."\">".$row["nom"]
		. ";" . $temp[1] . '%;' . $temp[2] ." h"
		."</option>";	
		$i++;		
	}

	$template_main .= '<input type="hidden" name="chaine" value="'.$provoqueetat.'" />';

	$composantesValue="";
	$tmp=explode(";",$composantes);
	$i=0;
	while ($tmp[$i]) {	
		$SQL = "SELECT nom FROM ".NOM_TABLE_OBJET." WHERE id_objet = ".$tmp[$i];
		$result=$db->sql_query($SQL);	
		$row = $db->sql_fetchrow($result);
		$composantesValue.="<option value=\"".$tmp[$i]."\">".$row["nom"]."</option>";	
		$i++;		
	}
	
	include('forms/objet2.form.'.$phpExtJeu);	
	
	include('forms/objet3.form.'.$phpExtJeu);	
	$template_main .= "</div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel objet voulez vous supprimer ?<br />";
	$SQL ="Select T1.id_objet as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type),case when anonyme<>0 then 'anonyme' else '' end)
	,'  --> '),T1.nom),'   - '),case when T1.type='Armure' then concat(concat(' (Protege de ',T1.competence),')') else '' end),' (Mun:'),T1.munitions),', Dur:'),T1.durabilite),', Degs '),T1.degats_min),'-'),T1.degats_max),', poids '),T1.poids),', Prix '),T1.prix_base),')') as labselect 
	from ".NOM_TABLE_OBJET." T1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>