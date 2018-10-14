<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creer_quete.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.2 $
$Date: 2010/02/28 22:58:04 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_Quete;
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);

if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$liste_champsQuete=array(
		"nom_quete","type_quete","detail_type_quete","duree_quete","public","proposepar",
			"proposepartype","cyclique","texteproposition","textereussite","texteechec",
			"refuspossible","abandonpossible","validationquete","proposant_anonyme","id_lieu","id_etattempspecifique"
	);

$liste_champsRecompenseQuete=array(
		"type_recompense","recompense"
	);


if(!isset($etape)){$etape=0;}


if($etape=="1"|| $etape=="10"){
	if($MJ->aDroit($liste_flags_mj["CreerQuete"])){
		$erreur="";
		switch ($type_quete) {
			case 1:  //lieu
			case 7: //Tuer monstres du lieu
				if ($detail_type_queteLieu=="")
					$erreur=GetMessage("erreurPasLieuQuete");
				else $detail_type_quete=$detail_type_queteLieu;
				break;	
			case 2:  //pj
			case 4:
			case 5:
				if ($detail_type_quetePJ=="")
					$erreur=GetMessage("erreurPasPJQuete");
				else $detail_type_quete=$detail_type_quetePJ;	
				break;
			case 3:  //objet
				if ($detail_type_queteOBJ=="")
					$erreur=GetMessage("erreurPasObjetQuete");
				else $detail_type_quete=$detail_type_queteOBJ;		
				break;
			case 6: //PO
				if ($detail_type_quetePO=="")
					$erreur=GetMessage("erreurPasPOQuete");
				else $detail_type_quete=$detail_type_quetePO;		
				break;		
			default:	
				$erreur=GetMessage("erreurPasTypeQuete");

		}
		switch ($proposepartype) {
			case 1:
			$proposepar = 	$id_proposeMJ;
			break;
			case 2:
			$proposepar = 	$id_proposePJ;
			break;
		}			
		if ($erreur=="") {
			$SQL = "INSERT INTO ".NOM_TABLE_QUETE." (";
			$SQL2="";
			$SQL3="";
			$nbchamps = count($liste_champsQuete);
			for($i=0;$i<$nbchamps;$i++){
				if ($$liste_champsQuete[$i]<>"") {
					if ($SQL2<>"")  {
						$SQL2.=",";
						$SQL3.=",";	
					}
					$SQL2.=$liste_champsQuete[$i];
					$SQL3.="'".ConvertAsHTML($$liste_champsQuete[$i])."'";	
				}	
			}
			$SQL=$SQL . $SQL2 .") VALUES (" . $SQL3.")";
			$result = $db->sql_query($SQL,"",BEGIN_TRANSACTION_JEU);
			if ($result) {
				$id_cible =$db->sql_nextid();
				
				//recompenses		
						
				if(isset($delRecompense)){
					$toto = array_keys($delRecompense);
					$tata = array_values($delRecompense);
					$nbDel = count($delRecompense);
					$SQL = "DELETE FROM ".NOM_TABLE_RECOMPENSE_QUETE." WHERE id_quete = '".$id_cible."' AND (";
					for($i=0;$i<$nbDel;$i++){
						if($tata[$i] == "on"){
							$SQL.= " id_recompensequete = '".$toto[$i]."' OR";
						}
					}
					$SQL = substr($SQL,0,strlen($SQL)-2).")";;
					$result=$db->sql_query($SQL);
					
				}

				//La on a efface et mis a jour
				if(isset($chaineRecompenses2) && $result){
					$liste = explode(";",$chaineRecompenses2);
					for($i=0;($i<count($liste)-1) && $result;$i++){
						$tmp = explode("|",$liste[$i]);
						$SQL = "INSERT INTO ".NOM_TABLE_RECOMPENSE_QUETE." (id_quete,type_recompense,recompense) VALUES ('".$id_cible."','".$tmp[0]."','".$tmp[1]."')";
						$result=$db->sql_query($SQL);
						
					}
				}				
				
				//punitions		
						
				if(isset($delPunition)){
					$toto = array_keys($delPunition);
					$tata = array_values($delPunition);
					$nbDel = count($delPunition);
					$SQL = "DELETE FROM ".NOM_TABLE_RECOMPENSE_QUETE." WHERE id_quete = '".$id_cible."' AND (";
					for($i=0;$i<$nbDel;$i++){
						if($tata[$i] == "on"){
							$SQL.= " id_recompensequete = '".$toto[$i]."' OR";
						}
					}
					$SQL = substr($SQL,0,strlen($SQL)-2).")";;
					$result=$db->sql_query($SQL);
					
				}

				//La on a efface et mis a jour
				if(isset($chainePunitions2) && $result){
					$liste = explode(";",$chainePunitions2);
					for($i=0;($i<count($liste)-1) && $result;$i++){
						$tmp = explode("|",$liste[$i]);
						$SQL = "INSERT INTO ".NOM_TABLE_RECOMPENSE_QUETE." (id_quete,type_recompense,recompense) VALUES ('".$id_cible."','".$tmp[0]."','-".$tmp[1]."')";
						$result=$db->sql_query($SQL);
						
					}
				}					
				/*
				if( (isset($retour)) && ($retour == "on") ){
					$t = $id_lieu_1;
					$id_lieu_1=$id_lieu_2;
					$id_lieu_2=$t;
					//memorise le type aller
					$typeAller = $type;
					if($type == $liste_types_Quetes["Lieu Guilde"] || $type == $liste_types_Quetes["Lieu Peage"]|| $type == $liste_types_Quetes["Lieu Secret"]){
						$type = $liste_types_Quetes["Lieu Entrer"];
					}
	
		
					$SQL = "INSERT INTO ".NOM_TABLE_QUETES." (";
					$nbchamps = count($liste_champsQuete);
					for($i=0;$i<$nbchamps;$i++){
						$SQL.=$liste_champsQuete[$i];
						if($i != ($nbchamps -1) ){$SQL .= ",";}
					}
					$SQL.=") VALUES (";
					for($i=0;$i<$nbchamps;$i++){
							if ($$liste_champsQuete[$i]==="") $SQL.="null"; else  $SQL.="'".ConvertAsHTML($$liste_champsQuete[$i])."'";
							if($i != ($nbchamps -1) ){$SQL .= ",";}
					}
					$SQL.=")";
					$result=$db->sql_query($SQL);
					//remet le type correct et le bon ordre pour les Quetes et lieux
					$type = $typeAller;				
					$id_lieu_2=$id_lieu_1;
					$id_lieu_1 = $t;
				}
				
				if ($result!=false) {
					if($type == $liste_types_Quetes["Lieu Passage"]){
						$SQL = "INSERT INTO ".NOM_TABLE_OBJET." (type,sous_type,degats_min,degats_max,nom,description) VALUES ";
						$SQL .= "('Divers','Clef','".$id_lieu_1."','".$id_lieu_2."','Clef auto Quete ".$id_lieu_1."__".$id_lieu_2."','Clef genere automatiquement pour aller avec le Quete')";
						$result=$db->sql_query($SQL,"",END_TRANSACTION_JEU);
					}
					else 
					if($type == $liste_types_Quetes["Lieu Secret"]){
						$toto = array_keys($liste_type_objetSecret);							
						$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEE." (id_entite,id_lieu,type, nom) VALUES (".$result_id.",'".$id_lieu_1."',". $toto[0].", ' Quete secret vers ".$libelle ."')";
						$result=$db->sql_query($SQL,"",END_TRANSACTION_JEU);			
					}
					
				}
				*/
				$valeurs=array();
				$valeurs[1]=$nom_quete;
				$msg = GetMessage("creationQueteOK",$valeurs);
			}
			//$id_lieu_2 = $id_lieu_2.$sep.$libelle;
			if ($etape=="1") {
				if ($result!==false) 
					$MJ->OutPut($msg,true);
				else {
					$MJ->OutPut(GetMessage("creationqueteImpossible") .$db->erreur.")" ,true);	
					$etape="0bis";	
				}	
			}	
		}
		else {
			logdate("erreur" . $erreur);
			$MJ->OutPut($erreur ,true);	
			$etape="0bis";	
		}		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	if ($etape=="1" && NOM_SCRIPT==("creer_quete.".$phpExtJeu))
		$etape=0;
}


if($etape===0){
	$nbchamps = count($liste_champsQuete);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champsQuete[$i] = '';
	}
	$nbchamps =count($liste_champsRecompenseQuete);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champsRecompenseQuete[$i] = '';
	}

	$detail_type_quetePO="";
	$detail_type_queteOBJ="";
	$detail_type_queteLieu="";
	$detail_type_queteSort="";
	$detail_type_quetePJ="";
	$recompenseSort	="";
	$recompenseMontant="";
	$recompenseOBJ="";
	$recompenseComp="";
	$recompenseEtat="";
	$id_proposeMJ="";
	$id_proposePJ="";
	$recompenses="";
	$punitions="";
	$punitionSort	="";
	$punitionMontant="";
	$punitionOBJ="";
	$punitionComp="";
	$punitionEtat="";
	$type_punition="";

}

if($etape==0 || $etape=="0bis"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	
	include('forms/quete.form.'.$phpExtJeu);

	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= '<input type="hidden" name="chaineRecompenses2" value="" /><br />';	
	$template_main .= '<input type="hidden" name="chainePunitions2" value="" /><br />';	
	//include('forms/recompenseQuete.form.'.$phpExtJeu);
	$template_main .= "</form>";
	$template_main .= "</div>";
}



if (NOM_SCRIPT==("creer_quete.".$phpExtJeu)) {
	if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
}
?>