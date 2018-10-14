<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: recuperer_objet.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.21 $
$Date: 2010/01/24 17:44:04 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}

if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $recuperer;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}


if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL ="Select concat(concat(T3.ID,'$sep'),T3.nom) as idselect, T3.nom as labselect 
	from ".NOM_TABLE_ENTITECACHEECONNUEDE."   T1, ".NOM_TABLE_ENTITECACHEE." T3	
	WHERE T1.id_entitecachee = T3.ID AND (T1.id_perso = ".$PERSO->ID." OR T1.id_perso is null) and T3.type = 1 and T3.ID_lieu = ". $PERSO->Lieu->ID;
	$var= faitSelect("id_entitecachee",$SQL,"");
	if ($var[0]>0) {	
		$template_main .= "Quel objet voulez vous r&eacute;cup&eacute;rer ?<br />".$var[1];
		$template_main .= "<br /><input type='submit' value='valider' />";
	} else $template_main .= "Il n'y a aucun objet &agrave; r&eacute;cup&eacute;rer. <br />";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){

	if( (isset($id_entitecachee)) && ($PERSO->ModPA($liste_pas_actions["AbandonnerObjet"])) && ($PERSO->ModPI($liste_pis_actions["AbandonnerObjet"]))){
		$pos = strpos($id_entitecachee, $sep);
		$libelle=substr($id_entitecachee, $pos+strlen($sep)); 
		$id_entitecachee=substr($id_entitecachee, 0,$pos); 

		$SQL ="Select o.* from ".NOM_TABLE_ENTITECACHEE.",".NOM_TABLE_PERSOOBJET." o WHERE id=".$id_entitecachee ." and id_entite = o.id_clef";
		$result = $db->sql_query($SQL);
		$row = $db->sql_fetchrow($result);
		$id_obj=$row["id_objet"];

		$Objet=new ObjetPJ($row["id_objet"],$row["id_clef"],0,0,$row["temporaire"],$row["munitions"],$row["durabilite"]); 
		//$valeurs[0]=$Objet->nom;
		$valeurs[0]=$libelle;
		if ($Objet->type=="Argent") {
			$valeurs[1]=$row["munitions"];
			$result=$PERSO->ModPO($row["munitions"]);
		}	
		else {/*
			logdate ("classe = ". get_class ($Objet));
			logdate ("obj".is_a($Objet, 'Objet')); 
			logdate ("objpj".is_a($Objet, 'ObjetPJ')); 
			$result=false;
			if (is_a($Objet, 'Objet')
				$result = $PERSO->AcquerirObjet($Objet);
			else 	
			if (is_a($Objet, 'ObjetPJ')*/
				$result = $Objet->changeProprio($PERSO->ID);
			if (! $result) 
				$PERSO->OutPut(GetMessage("recuperer_objet02",$valeurs),true);	
		}
		if ($result) {
		        traceAction("RamasserObjet", $PERSO, "", "", $Objet->nom);
			// on efface cet objet cache connu de tous les persos
			$SQL = "delete from  ".NOM_TABLE_ENTITECACHEECONNUEDE." where  ID_entitecachee = " .$id_entitecachee;			
			if ($result = $db->sql_query($SQL)) {
				$SQL = "delete from  ".NOM_TABLE_ENTITECACHEE." where  ID = " .$id_entitecachee	;			
				if ($result = $db->sql_query($SQL)) {
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
					if ($Objet->type=="Argent") 
						$PERSO->OutPut($mess.GetMessage("recuperer_objet03",$valeurs),true);
					else	$PERSO->OutPut($mess.GetMessage("recuperer_objet01",$valeurs),true);					 
				}	
			}	
		}
	} else {
		if( (!isset($id_entitecachee))  ){
			$template_main .= GetMessage("noparam");
		} else {
			if ($PERSO->RIP())
				$template_main .= GetMessage("nopvs");
			else	
			if ($PERSO->Archive)
				$template_main .= GetMessage("archive");
			else	
			$template_main .= GetMessage("nopas");
		}
	}
	$template_main .= "<br /><p>&nbsp;</p>";

}

if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>