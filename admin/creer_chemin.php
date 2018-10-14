<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: creer_chemin.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.18 $
$Date: 2006/04/17 21:24:50 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_chemin;
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);

if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$liste_champs=array(
		"id_lieu_1","id_lieu_2","type","difficulte","pass","distance"
	);

if(!isset($etape)){$etape=0;}


if($etape=="1"|| $etape=="10"){
	if($MJ->aDroit($liste_flags_mj["CreerChemin"])){
		$pos = strpos($id_lieu_2, $sep);
		$libelle=substr($id_lieu_2, $pos+strlen($sep)); 
		$id_lieu_2=substr($id_lieu_2, 0,$pos); 

		$SQL = "INSERT INTO ".NOM_TABLE_CHEMINS." (";
		$SQL2="";
		$SQL3="";
		$nbchamps = count($liste_champs);
		for($i=0;$i<$nbchamps;$i++){
			if ($$liste_champs[$i]<>"") {
				if ($SQL2<>"")  {
					$SQL2.=",";
					$SQL3.=",";	
				}
				$SQL2.=$liste_champs[$i];
				$SQL3.="'".ConvertAsHTML($$liste_champs[$i])."'";	
			}	
		}
		$SQL=$SQL . $SQL2 .") VALUES (" . $SQL3.")";
		$result = $db->sql_query($SQL);
		if ($result) {
			$result_id =$db->sql_nextid();
			if( (isset($retour)) && ($retour == "on") ){
				$t = $id_lieu_1;
				$id_lieu_1=$id_lieu_2;
				$id_lieu_2=$t;
				//memorise le type aller
				$typeAller = $type;
				if($type == $liste_types_chemins["Lieu Guilde"] || $type == $liste_types_chemins["Lieu Peage"]|| $type == $liste_types_chemins["Lieu Secret"]){
					$type = $liste_types_chemins["Lieu Entrer"];
				}

	
				$SQL = "INSERT INTO ".NOM_TABLE_CHEMINS." (";
				$nbchamps = count($liste_champs);
				for($i=0;$i<$nbchamps;$i++){
					$SQL.=$liste_champs[$i];
					if($i != ($nbchamps -1) ){$SQL .= ",";}
				}
				$SQL.=") VALUES (";
				for($i=0;$i<$nbchamps;$i++){
						if ($$liste_champs[$i]==="") $SQL.="null"; else  $SQL.="'".ConvertAsHTML($$liste_champs[$i])."'";
						if($i != ($nbchamps -1) ){$SQL .= ",";}
				}
				$SQL.=")";
				$result=$db->sql_query($SQL);
				//remet le type correct et le bon ordre pour les chemins et lieux
				$type = $typeAller;				
				$id_lieu_2=$id_lieu_1;
				$id_lieu_1 = $t;
			}
			if ($result!=false) {
				if($type == $liste_types_chemins["Lieu Passage"]){
					$SQL = "INSERT INTO ".NOM_TABLE_OBJET." (type,sous_type,degats_min,degats_max,nom,description) VALUES ";
					$SQL .= "('Divers','Clef','".$id_lieu_1."','".$id_lieu_2."','Clef auto chemin ".$id_lieu_1."__".$id_lieu_2."','Clef genere automatiquement pour aller avec le chemin')";
					$result=$db->sql_query($SQL,"",END_TRANSACTION_JEU);
				}
				else 
				if($type == $liste_types_chemins["Lieu Secret"]){
					$toto = array_keys($liste_type_objetSecret);							
					$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEE." (id_entite,id_lieu,type, nom) VALUES (".$result_id.",'".$id_lieu_1."',". $toto[0].", ' chemin secret vers ".$libelle ."')";
					$result=$db->sql_query($SQL,"",END_TRANSACTION_JEU);			
				}
			}
			if( (isset($retour)) && ($retour == "on") ){
				$msg = "Chemin (et retour) correctement cree";
			} else {
				$msg = "Chemin correctement cree";
			}
			if($type == $liste_types_chemins["Lieu Passage"]){$msg .= ". une clef a ete genere pour aller avec le chemin";}
		}
		$id_lieu_2 = $id_lieu_2.$sep.$libelle;
		if ($etape=="1") {
			if ($result!==false) 
				$MJ->OutPut($msg,true);
			else {
				$MJ->OutPut("le chemin n'a pu tre cree (Raison: $db->erreur;)" ,true);	
				$etape=0.5;	
			}	
		}	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	if ($etape=="1")
		$etape=0;
}


if($etape===0){
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champs[$i] = '';
	}
}

if($etape==0 || $etape==0.5){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	
	include('forms/chemin.form.'.$phpExtJeu);

	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}



if($etape>=-1 && $etape <=1){
	if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
}
?>