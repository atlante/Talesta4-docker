<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: cacher_objet.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.20 $
$Date: 2006/02/23 07:31:32 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $cacher_obj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}

if($etape=="1"){
	if($MJ->aDroit($liste_flags_mj["CacherObjet"])){
		$toto = array_keys($liste_type_objetSecret);
		if ($typeobj=='objet') {
			$pos = strpos($id_objet, $sep);
			$libelle=substr($id_objet, $pos+strlen($sep)); 
			$id_objet=substr($id_objet, 0,$pos); 
			$Objet= new Objet($id_objet);		
			$SQL = "INSERT into ".NOM_TABLE_PERSOOBJET." ( id_perso,  id_objet, durabilite, munitions ,
		   		temporaire,   equipe  ) values (null,".$Objet->ID.",". $Objet->durabilite.","
		   		.$Objet->munitions.",0,0)";		
		}
		else {
			$recherche_objet = null;
			$SQL = " select id_objet from ".NOM_TABLE_OBJET." where type = 'Argent' and sous_type = '$sousType'";
			if (($result=$db->sql_query($SQL))!==false)
			 	if ($row = $db->sql_fetchrow($result)) {
					$recherche_objet = $row['id_objet'];					
			}	
			$libelle = $sousType;
			if ($recherche_objet==null) {
				$SQL = "INSERT INTO ".NOM_TABLE_OBJET." (type, sous_type, nom, degats_min, degats_max,  prix_base) values";
				$SQL .=" ('Argent', '$sousType','$sousType',0,0,$montant)";
				if ($result=$db->sql_query($SQL)) {
					$recherche_objet=$db->sql_nextid();							
				}		
			}	
			$SQL = "INSERT into ".NOM_TABLE_PERSOOBJET." ( id_perso,  id_objet, durabilite, munitions ,
			   temporaire,   equipe  ) values (null,".$recherche_objet.",1,".$montant.",0,0)";		
		}   		
		if ($result=$db->sql_query($SQL)) {			
			$obj_id_clef=$db->sql_nextid();
			$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEE." (id_entite,id_lieu,type, nom) VALUES (".$obj_id_clef.",".$id_lieu.",". $toto[1].", '".ConvertAsHTML($libelle)."')";
			if ($result=$db->sql_query($SQL)) {			
				if (!isset($cacher) || $cacher==false) {
					$result_id=$db->sql_nextid();
					$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEECONNUEDE." (id_entitecachee,id_perso) VALUES (".$result_id.", null)";
					if ($db->sql_query($SQL)){
					if ($typeobj=='objet') 	
							$MJ->OutPut("Objet ".span(ConvertAsHTML($libelle),"objet")." correctement plac&eacute;",true);
						elseif ($typeobj=='po') 	
							$MJ->OutPut("Montant " .span($montant." POs","po")." correctement plac&eacute;",true);		
					}
				}	
				else {
					if ($typeobj=='objet') 	
						$MJ->OutPut("Objet ".span(ConvertAsHTML($libelle),"objet")." correctement cach&eacute;",true);
					elseif ($typeobj=='po') 	
						$MJ->OutPut("Montant " .span($montant." POs","po")." correctement cach&eacute;",true);
				}	
			}
		}
		$MJ->OutPut($db->erreur);
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
	unset($id_lieu);
}

if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL="Select concat(concat(T1.id_objet,'$sep'),T1.nom) as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type),
	case when anonyme<>0 then 'anonyme' else '' end)
	,'  --> '),T1.nom),'   - '),case when T1.type='Armure' then concat(concat(' (Protege de ',T1.competence),')') else '' end),' (Mun:'),T1.munitions),', Dur:'),T1.durabilite),', Degs '),T1.degats_min),'-'),T1.degats_max),', poids '),T1.poids),', Prix '),T1.prix_base),')') as labselect  from ".NOM_TABLE_OBJET." T1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
	$var=faitSelect("id_objet",$SQL,"",-1);
	$radioChecked=false;
	if ($var[0]>0) {
		$template_main .= "<input type='radio' name='typeobj' value='objet'  checked='checked' />";		
		$template_main .= "Quel objet voulez vous placer ?<br />";
		$template_main .= $var[1];
		$radioChecked=true;
	}
	
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
		$template_main .= "Quel montant voulez vous placer ?<br />";
		$template_main .= "<input type='text' name='montant' value='' />";
		$template_main .= "<input type='hidden' name='sousType' value='$tmp[1]' />";
	}		
	
	if ($radioChecked) {
		// id_lieu peut etre sette par voir_lieu
		if (!isset($id_lieu)) {
			$template_main .= "<br /><br />Dans quel lieu ?<br />";
			$SQL = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 ORDER BY T1.trigramme, T1.nom ASC";
			$var= faitSelect("id_lieu",$SQL,"",-1);	
			$template_main .= $var[1];
		}	
		else $template_main .= "<input type='hidden' name='id_lieu' value='".$id_lieu."' />";
		$template_main .= "<br /><br />Le dissimuler ou le laisser visible ?<br />";
		$template_main .= "Dissimuler : <input type='checkbox' name='cacher' /><br />";
		
		$template_main .= "<br />".BOUTON_ENVOYER;
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form>";
	}
	else $template_main .= "Aucun objet <br />";		
	$template_main .= "</div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>