<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: modifier_etat_pj.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.17 $
$Date: 2006/09/05 06:41:20 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}

if(!isset($pnj)){$pnj=0;}

if ($pnj==0)
	$titrepage = $mod_etat_pj;
else 
  $titrepage = $mod_etat_bestiaire;

if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierEtatPJ"])){
			
			if(isset($del)){
				$toto = array_keys($del);
				$tata = array_values($del);
				$SQL = "DELETE FROM ".NOM_TABLE_PERSOETATTEMP." WHERE id_perso = '".$id_cible."' AND (";
				for($i=0;$i<count($del);$i++){
					if($tata[$i] == "on"){
						$SQL.= " id_clef = '".$toto[$i]."' OR";
					}
				}
				$SQL = substr($SQL,0,strlen($SQL)-2) .")";
				$db->sql_query($SQL);
				
			}

			//La on a efface et mis a jour
			if(isset($chaine)){
				$liste = explode(";",$chaine);
				for($i=0;$i<count($liste)-1;$i++){
					$temp = explode("|",$liste[$i]);
					$SQL = "SELECT * FROM ".NOM_TABLE_ETATTEMPNOM." WHERE id_etattemp = ".$temp[0];
					$result = $db->sql_query($SQL);
										
					if($db->sql_numrows($result) > 0){
						//$JOUEUR = new Joueur($id_cible,true);
						$JOUEUR = new Joueur($id_cible,false,false,false,true,false,false);
						$JOUEUR->AjouterEtatTemp($temp[0],$temp[1]);
						if (isset($Commentaires) && $Commentaires<>"")	
							$JOUEUR->OutPut($Commentaires,false,true);
					}
					
				}
			}
			//Ici on ajoute
			$MJ->OutPut("Etats de  ".span(ConvertAsHTML($nomPJ),"pj")." correctement modif&eacute;",true);
		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$id_cible=$id_cible.$sep.$nomPJ;
	$etape=1;
}
if($etape=="1"){
	$pos = strpos($id_cible, $sep);
	$libelle=ConvertAsHTML(substr($id_cible, $pos+strlen($sep))); 
	$id_cible=substr($id_cible, 0,$pos); 	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT T1.*, T3.nom, T3.visible FROM ".NOM_TABLE_PERSOETATTEMP." T1, ".NOM_TABLE_ETATTEMPNOM." T3 WHERE T1.id_perso = ".$id_cible." AND T1.id_etattemp = T3.id_etattemp ORDER BY T1.id_clef";
	$result = $db->sql_query($SQL);
		if($db->sql_numrows($result) > 0){
		$template_main .= "Modifier les etats temporaires de ".span($libelle,"pj")."<br />";
		$template_main .= "<table class='detailscenter'>";
		//for($i=0;$i<$db->sql_numrows($result);$i++){
		while($row = $db->sql_fetchrow($result)) {
			$template_main .= "<tr>";
			$template_main .= "<td>Supprimer<input type='checkbox' name='del[".$row["id_clef"]."]' /></td>";
			$template_main .= "<td><a href=\"javascript:a('../bdc/etat.$phpExtJeu?for_mj=1&amp;num_etat=".$row["id_etattemp"]."')\">".span($row["nom"],"etattemp")."</a></td>";
			if($row["fin"] != -1){
				$template_main .= "<td> se termine le ".span(faitDate($row["fin"],true),"date")."</td>";
			} else {
				$template_main .= "<td>permanent</td>";
			}
			if($row["visible"] == 0){
				$template_main .= "<td>Invisible dans la description de ".span($libelle,"pj")."</td>";
			} else {
				$template_main .= "<td>Visible dans la description de ".span($libelle,"pj")."</td>";
			}
			$template_main .= "</tr>";
		}
		$template_main .= "</table>";
	} else {
		$template_main .= $libelle." n'a pas d'Etat temporaire <br />";
	}
	$template_main .= "<input type='hidden' name='nomPJ' value='".$libelle."' />";
	$template_main .= '<input type="hidden" name="chaine" value="" /><br />';	
	$template_main .= "<table class='detailscenter'><tr><td>Commentaires envoy&eacute;s au joueur : </td><td><textarea name='Commentaires' cols='40' rows='10'></textarea></td></tr></table>";
	$template_main .= "<br /><input type='submit' value='Envoyer' onclick=\"return confirm('Etes vous sur de vouloir effacer les etats temporaires eventuellement selectionnes ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "</form>";
	include('forms/etattemporaire2.form.'.$phpExtJeu);
	$template_main .= "</div>";
	
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Les etats temporaire de quel PJ voulez vous vous modifier ?<br />";
	$SQL = "Select  concat(concat(T1.id_perso,'$sep'),T1.nom) as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 ";
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