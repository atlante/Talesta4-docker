<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: modifier_etat.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.20 $
$Date: 2010/02/28 22:58:07 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $mod_etat;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!isset($etape)){$etape=0;}

$liste_champs=array(
		"nom", "rpi", "rpo", "rpa", "rpv", "visible", "id_typeetattemp", "id_lieudepart"
		,"objetsfournis", "sortsfournis"
	);



if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierEtat"])){
		if($id_cible != 1){
		        
        		$tmp2 = explode(";",$chaineObjets);
        		if($tmp2[0]) {
        			sort($tmp2);
        			array_shift($tmp2);//supprime le null du debut
        			$chaineObjets = implode(";",$tmp2).";";
        		}
        		$objets=$chaineObjets;
        	        $objetsfournis=$objets;
        		$tmp2 = explode(";",$chaineSorts);
        		if($tmp2[0]) {
        			sort($tmp2);
        			array_shift($tmp2);//supprime le null du debut
        			$chaineSorts = implode(";",$tmp2).";";
        		}
        		$sorts=$chaineSorts;	
        		$sortsfournis=$sorts;
        		$nom=$nom_etat;		        
        		$SQL = "UPDATE ".NOM_TABLE_ETATTEMPNOM." SET ";
        		$nbchamps = count($liste_champs);
        		for($i=0;$i<$nbchamps;$i++){
        				$SQL.=$liste_champs[$i]."= '".ConvertAsHTML($$liste_champs[$i])."'";
        				if($i != ($nbchamps -1) ){$SQL .= ",";}
        		}
        		$SQL .= " WHERE id_etattemp = ".$id_cible;

			if ($result = $db->sql_query($SQL,"",BEGIN_TRANSACTION_JEU)) {
				$SQL = "DELETE FROM ".NOM_TABLE_ETATTEMP." WHERE id_etattemp = '".$id_cible."'";
				$result = $db->sql_query($SQL);
				logDate("comp" . isset($comp));
				logDate("result" . ($result!==false));
				
				if(isset($comp)&& ($result!==false)){
					$toto = array_keys($comp);
					$tata = array_values($comp);
					$debutSQL = "INSERT INTO ".NOM_TABLE_ETATTEMP." (id_etattemp,id_comp,bonus) VALUES ";
					$nb_comp=count($comp);
					for($i=0;($i<$nb_comp) && ($result!==false);$i++){
						if($tata[$i] != 0){
							$SQL = $debutSQL . "('".$id_cible."','".$toto[$i]."','".$tata[$i]."')";
							$result=$db->sql_query($SQL);
						}
					}
				}
			}	
			if ($type==""&& ($result!==false)) {
				$MJ->OutPut("Etat ".span(ConvertAsHTML($nom_etat),"etattemp")." correctement modif&eacute;",true);
				$etape=0;
			}	

		}
		if ($result===false) {
			$template_main .= $db->erreur;
			$etape="1bis";
		}	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
}
if($etape=="1"){
	$SQL = "SELECT * FROM ".NOM_TABLE_ETATTEMP." T1, ".NOM_TABLE_ETATTEMPNOM." T2 WHERE T1.id_etattemp = T2.id_etattemp AND T1.id_etattemp = ".$id_cible;
	$SQL2 = "SELECT * FROM ".NOM_TABLE_ETATTEMPNOM." T2 WHERE T2.id_etattemp = ".$id_cible;
	$result = $db->sql_query($SQL);
	$result2 = $db->sql_query($SQL2);
	$row2 = $db->sql_fetchrow($result2);
	$nom_etat = $row2["nom"];
	$rpi = $row2["rpi"];
	$rpa = $row2["rpa"];
	$rpo = $row2["rpo"];
	$rpv = $row2["rpv"];
	$id_typeetattemp=$row2["id_typeetattemp"];
	$visible = $row2["visible"];
	$utilisableinscription= $row2["utilisableinscription"];
	$id_lieudepart = $row2["id_lieudepart"];
	$objets= $row2['objetsfournis'];
	$sorts= $row2['sortsfournis'];
	while($row = $db->sql_fetchrow($result)) {
		$comp[$row["id_comp"]]=$row["bonus"];
	}



}


if($etape=="1"||$etape=="1bis"){


	$objetsValue="";
	$tmp=explode(";",$objets);
	$i=0;
	while ($tmp[$i]) {	
		$SQL = "SELECT nom FROM ".NOM_TABLE_OBJET." WHERE id_objet = ".$tmp[$i];
		$result=$db->sql_query($SQL);	
		$rowObj = $db->sql_fetchrow($result);
		$objetsValue.="<option value=\"".$tmp[$i]."\">".$rowObj["nom"]."</option>";	
		$i++;		
	}	

	$sortsValue="";
	$tmp=explode(";",$sorts);
	$i=0;
	while ($tmp[$i]) {	
		$SQL = "SELECT nom FROM ".NOM_TABLE_MAGIE." WHERE id_magie = ".$tmp[$i];
		$result=$db->sql_query($SQL);	
		$rowObj = $db->sql_fetchrow($result);
		$sortsValue.="<option value=\"".$tmp[$i]."\">".$rowObj["nom"]."</option>";	
		$i++;		
	}

	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "nom de l'etat : <input type='text' name='nom_etat' value='".ConvertAsHTML($nom_etat)."' size='25' /><br />";
	$template_main .= "rpo : <input type='text' name='rpo' value='".$rpo."' size='4' /> rpa : <input type='text' name='rpa' value='".$rpa."' size='4' /> rpv : <input type='text' name='rpv' value='".$rpv."' size='4' />rpi : <input type='text' name='rpi' value='".$rpi."' size='4' /><br />";
	$SQL ="Select T1.id_typeetattemp as idselect, T1.nomtype as labselect from ".NOM_TABLE_TYPEETAT." T1 ORDER BY T1.nomtype";
	$var = faitSelect("id_typeetattemp",$SQL, "",$id_typeetattemp);
	if ($var[0]>0) {		
		$template_main .= "type d'tat ";
		$template_main .= $var[1];	
		$template_main .= "<br />";
	}
	else $template_main .= "Aucun type d'tat temporaire.<br />";	

	$template_main .= "visible par les tiers :".faitOuiNon("visible","",$visible)."<br />";
	$template_main .= "Utilisable  l'inscription (PJ) :".faitOuiNon("utilisableinscription","",$utilisableinscription)."<br />";
	$template_main .= "Lieu de dpart du PJ avec cet tat";
	$SQL_lieu = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 ORDER BY T1.trigramme, T1.nom ASC";
	$var= faitSelect("id_lieudepart",$SQL_lieu,"",$id_lieudepart,array(),array("&nbsp;"));
	$template_main .= $var[1];
	
	include('forms/status.form.'.$phpExtJeu);

	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "<input type='hidden' name='type' value='".$type."' />";
	$template_main .= "<input type='hidden' name='objets' value='".$objets."' />";
	$template_main .= "<input type='hidden' name='sorts' value='".$sorts."' />";
	$template_main .= "<input type='hidden' name='chaineObjets' value='".$objets."' />";
	$template_main .= "<input type='hidden' name='chaineSorts' value='".$sorts."' />";	
	$template_main .= "<input type='submit' value='Dupliquer cet etat' onclick=\"document.forms[0].etape.value='0bis';document.forms[0].action='creer_etat.".$phpExtJeu."';document.forms[0].submit();\" />";
	
	$template_main .= "</form>";
	include('forms/objetFourniParEtat.form.'.$phpExtJeu);
        include('forms/sortFourniParEtat.form.'.$phpExtJeu);	
	$template_main .= "</div>";	
}
if($etape===0){
	if(!isset($type)){$type="";}
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	
	if ($type=="") {
		$template_main .= "Quel etat temporaire voulez vous modifier ?<br />";
		$SQL = "Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1, ".NOM_TABLE_TYPEETAT." t2 where T1.id_typeetattemp=t2.id_typeetattemp and T1.id_etattemp > 1 ORDER BY T1.nom ASC";
	
	}	
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "<input type='hidden' name='type' value='".$type."' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>