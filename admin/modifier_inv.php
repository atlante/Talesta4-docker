<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: modifier_inv.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.18 $
$Date: 2010/02/28 22:58:07 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}

if(!isset($pnj)){$pnj=0;}

if ($pnj==0)
	$titrepage = $mod_inv;
else 
        $titrepage = $mod_inv_bestiaire;

if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}

if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierInv"])){
			$result=true;
			if(isset($del) && is_array($del)){
				$toto = array_keys($del);
				$tata = array_values($del);
				$SQL = "DELETE FROM ".NOM_TABLE_PERSOOBJET." WHERE id_perso = '".$id_cible."' AND (";
                                $nb=count($del);
				for($i=0;$i<$nb;$i++){				
					if($tata[$i] == "on"){
						$SQL.= " id_clef = '".$toto[$i]."' OR";
					}
				}
				$SQL = substr($SQL,0,strlen($SQL)-2).")";;
				$result=$db->sql_query($SQL,"",BEGIN_TRANSACTION_JEU);
				
			}

			if(isset($dur) && is_array($dur) && $result){
				$toto = array_keys($dur);
				$tata = array_values($dur);
				$oldtata = array_values($old_dur);
				$nb=count($dur);
				for($i=0;$i<$nb;$i++){
        			        //on ne fait les updates que si on n'a pas fait de del sur cet enregistrement
        			        if ((!isset($del)) || (!array_key_exists ( $toto[$i], $del))) {
        			                if ((!isset($oldtata[$i])) || $oldtata[$i]<>  $tata[$i]) {
						$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET durabilite = '".$tata[$i]."' WHERE id_perso = '".$id_cible."' AND id_clef = '".$toto[$i]."'";
						$result=$db->sql_query($SQL);
				}
			}
				}
			}

			if(isset($mun) && is_array($mun) && $result){
				$toto = array_keys($mun);
				$tata = array_values($mun);
				$oldtata = array_values($old_mun);
				$nb=count($mun);
				for($i=0;$i<$nb;$i++){
        			        //on ne fait les updates que si on n'a pas fait de del sur cet enregistrement
        			        if ((!isset($del)) || (!array_key_exists ( $toto[$i], $del))) {
        			                if ((!isset($oldtata[$i])) || $oldtata[$i]<>  $tata[$i]) {
						$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET munitions = '".$tata[$i]."' WHERE id_perso = '".$id_cible."' AND id_clef = '".$toto[$i]."'";
						$result=$db->sql_query($SQL);
				}
			}
				}
			}

			if(isset($eqp) && $result){
				$toto = array_keys($eqp);
				$tata = array_values($eqp);
				$oldtata = array_values($old_eqp);
                                $nb=count($eqp);
				for($i=0;$i<$nb;$i++){
        			        //on ne fait les updates que si on n'a pas fait de del sur cet enregistrement
        			        if ((!isset($del)) || (!array_key_exists ( $toto[$i], $del))) {
        			                if ((!isset($oldtata[$i])) || $oldtata[$i]<>  $tata[$i]) {
						        $SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET equipe = '".$tata[$i]."' WHERE id_perso = '".$id_cible."' AND id_clef = '".$toto[$i]."'";
						        $result=$db->sql_query($SQL);
						}        
                                        }
				}
			}

			//La on a efface et mis a jour
			if(isset($chaine) && $result){
				$liste = explode(";",$chaine);
				for($i=0;($i<count($liste)-1) && $result;$i++){
					$SQL = "SELECT * FROM ".NOM_TABLE_OBJET." WHERE id_objet = ".$liste[$i];
					$result = $db->sql_query($SQL);
					if($db->sql_numrows($result) > 0){
						$row = $db->sql_fetchrow($result);
						$SQL = "INSERT INTO ".NOM_TABLE_PERSOOBJET." (id_perso,id_objet,durabilite,munitions) VALUES ('".$id_cible."','".$liste[$i]."','".$row["durabilite"]."','".$row["munitions"]."')";
						$result=$db->sql_query($SQL);
					}
					
				}
			}
			//Ici on ajoute
			if ($result!==false) {
			        $valeurs=array();
			        $valeurs[1]=ConvertAsHTML($nom);
				$MJ->OutPut(GetMessage("InventairePJmodifie",$valeurs),true);
			}	
			else	$template_main .= $db->erreur;
		
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
	$SQL = "SELECT * FROM ".NOM_TABLE_PERSOOBJET." T1, ".NOM_TABLE_REGISTRE." T2 WHERE T1.id_perso = T2.id_perso AND T1.id_perso = ".$id_cible." ORDER BY T1.id_clef";
	$result = $db->sql_query($SQL);
	$valeurs=array();
	$valeurs[1]=span($libelle,"pj");
	if($db->sql_numrows($result) > 0){
		$ListeObj = null;
		$compteur=0;
		$template_main .= GetMessage("questionInventairePJAModifier",$valeurs)."<br />";
		$i=0;
		while($row = $db->sql_fetchrow($result)) {
				$ListeObj[$compteur]= new ObjetPJ($row["id_objet"],$row["id_clef"],$i, ($row["equipe"] == 1),($row["temporaire"]==1),$row["munitions"],$row["durabilite"]);
				if  ($ListeObj[$compteur]!=null)
					$compteur++;					
				$i++;
		}
		include('forms/inventaire.form.'.$phpExtJeu);
	} else {
		$template_main .= GetMessage("PJpossedeZeroObjet",$valeurs);
		
	}
	$template_main .= "<input type='hidden' name='nom' value='".$libelle."' />";
	$template_main .= "<input type='hidden' name='chaine' value='' />";
	$template_main .= "<br /><input type='submit' value='Envoyer' onclick=\"return confirm('".GetMessage("ConfirmerSupprimerObjet")."')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "</form>";
	include('forms/inventaire2.form.'.$phpExtJeu);
	$template_main .= "</div>";
}

if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= GetMessage("quesionInvPJ")."<br />";
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