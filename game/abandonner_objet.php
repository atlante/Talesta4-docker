<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: abandonner_objet.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.21 $
$Date: 2010/01/24 17:44:00 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}

if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $abandonner_obj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}	


if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = $PERSO->listeObjets(array(), null, -1,-1,-1, -1);
	$var = faitSelect("id_obj",$SQL,"");
	$radioChecked=false;
	if ($var[0]>0) {		
		$template_main .= "<input type='radio' name='typeobj' value='objet'  checked='checked' />";
		$radioChecked=true;
		$template_main .= "Quel objet voulez vous abandonner ?<br />";
		$template_main .= $var[1];	
	}
	if ($PERSO->PO >0 ) {

		//verifie qu'on a bien un type d'objet Argent
		$tata = array_keys($liste_type_objs);
		$trouve=false;
		while( ! $trouve && (list($key, $val) = each($tata))) {
	    		$tmp=explode(";",$val);
	    		if ($tmp[0]=='Argent') {
	    			$trouve = true;
	    		}	    		
		}
		if ($trouve) {			
			$template_main .= "<br /><br /><input type='radio' name='typeobj' value='po' />";
			$radioChecked=true;
			$template_main .= "Quel montant voulez vous abandonner ?<br />";
			$template_main .= "<input type='text' name='montant' value='' />";
			$template_main .= "<input type='hidden' name='sousType' value='$tmp[1]' />";
		}
	}	
	
	
	if ($radioChecked) {
		$template_main .= "<br /><br />Le dissimuler ou le laisser visible ?<br />";
		$template_main .= "Le laisser visible : <input type='radio' name='typeact' value='visible' /><br />";
		$template_main .= "Le dissimuler : <input  type='radio' name='typeact' value='cacher' /><br />";
		$template_main .= "Le détruire : <input type='radio' name='typeact' value='detruire' /><br />";
		$template_main .= "<br /><input type='submit' value='envoyer' />";
		
	}
	else $template_main .= "Vous n'avez aucun objet &agrave; abandonner .<br />";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	if ($PERSO->RIP())
		$template_main .= GetMessage("nopvs");
	else	
	if ($PERSO->Archive)
		$template_main .= GetMessage("archive");
	else	
	if( isset($typeact) && 
	    ( $typeobj=='po'
		|| ($typeobj=='objet' && (isset($id_obj))) 
	    )	
	&& ($PERSO->ModPA($liste_pas_actions["AbandonnerObjet"])) && ($PERSO->ModPI($liste_pis_actions["AbandonnerObjet"]))){		
		$Objet = null;
		if ($typeobj=='objet') {
			$nb_objets =  count($PERSO->Objets);
			$i=0;
			while (($i<$nb_objets) && ($Objet == null)) {
				if($PERSO->Objets[$i]->id_clef == $id_obj){$Objet = $PERSO->Objets[$i];}
				else $i++;
			}
		}
		else {
			$recherche_objet = null;
			$montant = min ( $PERSO->PO , $montant);			
			$SQL = " select id_objet from ".NOM_TABLE_OBJET." where type = 'Argent' and sous_type = '$sousType'";
			if (($result=$db->sql_query($SQL))!==false)
			 	if ($row = $db->sql_fetchrow($result)) {
					$recherche_objet = $row['id_objet'];					
			}	
			if ($recherche_objet==null) {
				$SQL = "INSERT INTO ".NOM_TABLE_OBJET." (type, sous_type, nom, degats_min, degats_max,  prix_base) values";
				$SQL .=" ('Argent', '$sousType','$sousType',0,0,$montant)";
				if ($result=$db->sql_query($SQL)) {
					$recherche_objet=$db->sql_nextid();							
				}		
			}	
			$SQL = "INSERT into ".NOM_TABLE_PERSOOBJET." ( id_perso,  id_objet, durabilite, munitions ,
			   temporaire,   equipe  ) values (null,".$recherche_objet.",1,".$montant.",0,0)";		
			if ($result=$db->sql_query($SQL)) {			
				$obj_id_clef=$db->sql_nextid();
				$Objet = new ObjetPJ($recherche_objet,$obj_id_clef,0, 0, 0, $montant, 1);
			}	
		}	
		if ($Objet==null)
			$template_main .= GetMessage("noparam");
		else {	
			$valeurs[0]=$Objet->nom;
			if ($typeobj=='po') {
				$PERSO->ModPO(-$montant,true);
				$valeurs[1]=$montant;
			}	
			
			if ($typeact<>'detruire') {
				$toto = array_keys($liste_type_objetSecret);
				if ($typeobj=='objet') {					
					$Objet->changeProprio(null);
				}	

				$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEE." (ID_entite,id_lieu,type, nom) VALUES (".$Objet->id_clef.",".$PERSO->Lieu->ID.",". $toto[1].", '".ConvertAsHTML($Objet->nom)."')";
				if ($result=$db->sql_query($SQL)) {
					$result_id=$db->sql_nextid();
					if ($typeact<>'cacher') {
						$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEECONNUEDE." (ID_entitecachee,id_perso) VALUES (".$result_id.", null)";
						$result=$db->sql_query($SQL);
			
					}	
					else {
						$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEECONNUEDE." (ID_entitecachee,id_perso) VALUES (".$result_id.",$PERSO->ID)";
						$result=$db->sql_query($SQL);
					}
					if ($result!==false)
						$PERSO->ConnaitObjetsSecrets=true;
				}
			}
			else if ($typeobj=='objet') 
				$result=$Objet->Detruire();
			$sortir = $PERSO->etreCache(0);
			if ($sortir) {
				$mess = GetMessage("semontrer_01");
				$valeursCache=array();
				$valeursCache[0]= $PERSO->nom;
				$mess_spect = GetMessage("semontrer_spect",$valeursCache);
			}	
			else {
				$mess="";	
				$mess_spect="";
			}	
			if ($result!==false) {
				if ($typeact=='cacher') {
				        
					if ($typeobj=='objet') 	{
					        traceAction("CacherObjet", $PERSO, "", "", $Objet->nom);
						$PERSO->OutPut($mess . GetMessage("cacher_objet",$valeurs),true);
					}	
					elseif ($typeobj=='po') {	
						$PERSO->OutPut($mess . GetMessage("cacher_objet01",$valeurs),true);
						traceAction("CacherObjet", $PERSO, " contenant ". span($montant." POs","po"), "", $Objet->nom);
					}	
				}	
				elseif ($typeact=='visible') {					        
					if ($typeobj=='objet') 	{
					        traceAction("AbandonnerObjet", $PERSO, "", "", $Objet->nom);
						$PERSO->OutPut($mess . GetMessage("abandonner_objet",$valeurs),true);
					}	
					elseif ($typeobj=='po') {	
						$PERSO->OutPut($mess . GetMessage("abandonner_objet01",$valeurs),true);
						traceAction("AbandonnerObjet", $PERSO, " contenant ". span($montant." POs","po"), "", $Objet->nom);
					}	
				}	
				else {
					if ($typeobj=='objet') 	{
					        traceAction("DetruireObjet", $PERSO, "", "", $Objet->nom);
						$PERSO->OutPut($mess . GetMessage("detruire_objet",$valeurs),true);					
					}	
					elseif ($typeobj=='po') {	
						$PERSO->OutPut($mess . GetMessage("detruire_objet01",$valeurs),true);
						traceAction("DetruireObjet", $PERSO," contenant ". span($montant." POs","po"), "", $Objet->nom);
					}	
				}	
			}
			else $template_main .= $db->erreur;
		}
	} else {
		if( (!isset($id_typeact)) || (!isset($id_typeobj)) || ($id_typeobj='objet' && !isset($id_obj)) || ($id_typeobj='po' && !isset($montant))){
			$template_main .= GetMessage("noparam");
		} else {
			$template_main .= GetMessage("nopas");
		}
	}
	$template_main .= "<br /><p>&nbsp;</p>";
	
}

if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>