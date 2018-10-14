<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: reveler_entitecachee.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.7 $
$Date: 2006/01/31 12:26:25 $

*/

require_once("../include/extension.inc");	
include('../include/http_get_post.'.$phpExtJeu);
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $reveler;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}	

if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

	
	if(!isset($etape)){
		$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
		$SQLentite = "Select E.id, E.id_entite, E.type, E.nom FROM ".NOM_TABLE_ENTITECACHEE."  E,"
			.NOM_TABLE_ENTITECACHEECONNUEDE."  ECCD
		   WHERE  E.id= ECCD.id_entitecachee and ECCD.id_perso = ".$PERSO->ID.
			" AND E.id_lieu = ".$PERSO->Lieu->ID ." order by E.type";
		$resultEntite = $db->sql_query($SQLentite);
		$nbEntiteCacheeConnue = $db->sql_numrows($resultEntite);
		if ($nbEntiteCacheeConnue>0) {
			$i=0;
			$template_main .= " <input type='radio' name='entite' value='uneouplus' checked='checked' /> Rvler  <br />";
			while ($nbEntiteCacheeConnue>$i) {
				$row = $db->sql_fetchrow($resultEntite);
				if ($row["type"]==1) {
					//objet
					//$sql = "select nom from " . NOM_TABLE_OBJET ." where id_objet = ".$row["id_entite"];
					//$resultObjet = $db->sql_query($SQL);
					$template_main .= "<input type='checkbox' name='obj[".$row["id"].$sep.$row["id_entite"].$sep.$row["nom"] ."]' ";
					if (isset($id_entitecachee) && $id_entitecachee == $row["id_entite"].$sep.$row["nom"])
						$template_main .= "checked='checked'";
					$template_main .= " />".span($row["nom"],"objet");
					$i++;	
				}
				elseif ($row["type"]==0) {
					//chemin
					//$sql = "select nom from " . NOM_TABLE_CHEMINS ." where id_clef = ".$row["id_entite"];
					//$resultChemin = $db->sql_query($SQL);
					$template_main .= "<input type='checkbox' name='chemin[".$row["id"].$sep.$row["id_entite"].$sep.$row["nom"]."]' ";
					if (isset($id_entitecachee) && $id_entitecachee == $row["id_entite"].$sep.$row["nom"])
						$template_main .= "checked='checked'";
					$template_main .= " />".span($row["nom"],"lieu");
					$i++;	
				}
				elseif ($row["type"]==2) {
					//pj
					//$sql = "select nom from " . NOM_TABLE_PERSO ." where id_perso = ".$row["id_entite"];
					//$resultPerso = $db->sql_query($SQL);
					$template_main .= "<input type='checkbox' name='pers[".$row["id"].$sep.$row["id_entite"].$sep.$row["nom"]."]' ";
					if (isset($id_entitecachee) && $id_entitecachee == $row["id_entite"].$sep.$row["nom"])
						$template_main .= "checked='checked'";
					$template_main .= " />".span($row["nom"],"perso");
					$i++;	
				}
					if ($i<$nbEntiteCacheeConnue)
						$template_main .= ", ";
			}	
			if ($nbEntiteCacheeConnue>1) {
				$template_main .= "<br /><input type='radio' name='entite' value='toutes' />tout ce qui est cach ici<br />";	
				
			}
			// affichage des PJ que l'on sait presents ici						
			$SQLpj=$PERSO->listePJsDuLieuDuPerso(1, false, true,1);
			$result = $db->sql_query($SQLpj);
			$nb_pj_presents = $db->sql_numrows($result);
			if ($nb_pj_presents>0) {
				$template_main .= "\n<hr />";
				$template_main .= "<input type='radio' name='typeact' value='pjs' checked='checked' />Rvler &agrave; ces personnes<br />";	
				for($i=0;$i<$nb_pj_presents;$i++){
					$row = $db->sql_fetchrow($result);
					$template_main .= "<input type='checkbox' name='pj[".$row["idselect"]."]' />".span($row["labselect"],"pj");
					if ($i<$nb_pj_presents-1)
						$template_main .= ", ";
				}
				$template_main .= "<br /><input type='radio' name='typeact' value='lieu' />Rvler &agrave; toutes les personnes du lieu<br />";
				
				
				
				if ($PERSO->Groupe<>"") {
					$groupePJ=new Groupe ($PERSO->Groupe,true);
					$pjs =$groupePJ->Persos;
					$existPersoGroupe=false;
					$i=0;
					while ($i<count($pjs) && ($existPersoGroupe==false)) {
						//logdate($i ." nom". $pjs[$i]->nom);
						if (($pjs[$i]->Lieu->ID ==$PERSO->Lieu->ID) && ($pjs[$i]->ID <>$PERSO->ID) && $pjs[$i]->dissimule==0)
							$existPersoGroupe=true;
						else $i++;	
					}	
					if ($existPersoGroupe)
						$template_main .= "<br /> <input type='radio' name='typeact' value='".$PERSO->Groupe."' />Rvler aux membres du groupe <br />";
					else 	$template_main .= "<input type='hidden' name='groupe' value='0' />";
				}
				else $template_main .= "<input type='hidden' name='groupe' value='0' />";
	
				//$template_main .= "<hr /><textarea name='msg' rows='20' cols='50'></textarea>";
				$template_main .= "<br />".BOUTON_ENVOYER;
			}
			else $template_main .= "Il n'y a personne ici. <br />";
		}	
		else $template_main .= "Il n'y a rien de cach ici  votre connaissance. <br />";
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form></div>";
//		$etape=0;
	} 
	
	elseif($etape=="1"){
		if ($typeact<>'pjs' ) $pj=array();
		if ($entite=='toutes' ) {
				$chemin=array();
				$obj=array();
				$pers=array();
		}
		else {
			if (!isset($chemin)) $chemin=array();
			if (!isset($obj))  $obj=array();
			if (!isset($pers))   $pers=array();
		}
			
		reveler ($PERSO,$typeact,$chemin,$obj,$pers,$pj, ($entite=='toutes')); 
		$template_main .= "<br /><p>&nbsp;</p>";

	}


	if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>