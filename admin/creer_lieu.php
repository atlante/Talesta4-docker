<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creer_lieu.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.25 $
$Date: 2010/02/28 22:58:03 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_lieu;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

// ne pas oublier d'ajouter les champs dans creerGuilde aussi
$liste_champs=array(
		"nom","flags","trigramme","accessible_telp","id_forum","provoqueetat","difficultedesecacher","cheminfichieraudio"
		//, "typemimefichieraudio"
		, "id_etattempspecifique","apparition_monstre","type_lieu_apparition"
	);

if(!isset($etape)){$etape=0;}
if($etape=="1" || $etape=="10"){
	if($MJ->aDroit($liste_flags_mj["CreerLieu"])){
		$fl = "";
		$erreur = false;
		if ($cheminfichieraudio<>"" && substr($cheminfichieraudio,0,4)<>"http") {
			if (! file_exists("../lieux/sons/".$cheminfichieraudio)) {
				$erreur = true;
			}			
		}
		
		if ($erreur) {
			$MJ->OutPut("Lieu ".span(ConvertAsHTML($nom),"lieu")." n'a pu tre cree (Raison: impossible de trouver '$cheminfichieraudio' dans /lieux/sons/)" ,true);	
			$etape=0.5;	
		}
		else {
			if ($id_forum =="")
				$id_forum = 0;
			for($i=0;$i<count($flags);$i++){
				$fl .= $flags[$i];
			}
			$fl .= '000000000000000000000';
			$flags = $fl;
			$provoqueetat = $chaine;
			$SQL = "INSERT INTO ".NOM_TABLE_LIEU." (";
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
				if ($etape=="10") {
					$id_lieu_2=$result_id . $sep. ConvertAsHTML($nom);
				}	
				//efface les anciennes images du lieu au cas ou elles existeraient
				if(file_exists("../lieux/vues/view".$result_id.".jpg"))
					if ((unlink ("../lieux/vues/view".$result_id.".jpg"))===false)
						$template_main .= "Impossible d'effacer le fichier '../lieux/vues/view".$result_id.".jpg'";
				if(file_exists("../lieux/vues/view".$result_id.".gif"))
					if((unlink ("../lieux/vues/view".$result_id.".gif"))===false)
						$template_main .= "Impossible d'effacer le fichier '../lieux/vues/view".$result_id.".gif'";
				if(file_exists("../lieux/vues/view".$result_id.".png"))
					if((unlink ("../lieux/vues/view".$result_id.".png"))===false)
						$template_main .= "Impossible d'effacer le fichier '../lieux/vues/view".$result_id.".png'";
				if(file_exists("../lieux/descriptions/desc_".$result_id.".txt"))
					if((unlink ("../lieux/descriptions/desc_".$result_id.".txt"))===false)
						$template_main .= "Impossible d'effacer le fichier '../lieux/descriptions/desc_".$result_id.".txt'";
				
                		if (isset($HTTP_POST_FILES['fichierImage']['tmp_name']) && isset($HTTP_POST_FILES['fichierImage']['name'])&& $HTTP_POST_FILES['fichierImage']['tmp_name']!="") {
                				$erreur=verif_EstImage($HTTP_POST_FILES['fichierImage']['name']);
                				$ext=strtolower(substr($HTTP_POST_FILES['fichierImage']['name'],strlen($HTTP_POST_FILES['fichierImage']['name'])-3));
                                 		if(! file_exists ("../lieux/vues/"))
                                 			if (! mkdir("../lieux/vues/",0744)) {
                                 				logDate ("impossible de crer le rep '../lieux/vues/'",E_USER_WARNING,1);
                                 				$erreur=1;
                                 			}	

                				if ($erreur=="") {
                					uploadImage($HTTP_POST_FILES['fichierImage']['tmp_name'],"../lieux/vues/view".$result_id.".".$ext);
                				}	
                				else 	$template_main .=$erreur;
                		}					
				
				if ($etape=="1") {
					$MJ->OutPut("Lieu ".span(ConvertAsHTML($nom),"lieu")." correctement cree",true);
					$etape=0;
				}	
			}
			else {
				$MJ->OutPut("Lieu ".span(ConvertAsHTML($nom),"lieu")." n'a pu tre cree (Raison: $db->erreur;)" ,true);	
				$etape=0.5;	
			}	
		}
	}
	else $template_main .= GetMessage("droitsinsuffisants");
}

if($etape===0){
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champs[$i] = '';
	}
	$provoqueetatValue="";
	$apparition_monstre=0;
	for($i=0;$i<count($liste_flags_lieux);$i++)
		$flags[$i]=0;	
}

if($etape==0||$etape==0.5){

	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."'  enctype='multipart/form-data' method='post'>";
	include('forms/lieu.form.'.$phpExtJeu);
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='provoqueetatValue' value='".ConvertAsHTML($provoqueetatValue)."' />";
	$template_main .= "<input type='hidden' name='chaine' value='".$provoqueetat."' />";
	$template_main .= "<input type='hidden' name='provoqueetat' value='".$provoqueetat."' />";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form>"; 
	include('forms/objet2.form.'.$phpExtJeu);
	$template_main .= "</div>";
}


if($etape>=-1 && $etape <=1){
	if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
}
?>