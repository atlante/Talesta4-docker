<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: gerer_quete_proposee.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/04 20:47:30 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $gererSesQuetes;

if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}


if(!isset($etape)){$etape=0;}

if($etape==2){
	//if($MJ->aDroit($liste_flags_mj["ModifierQuetePJ"])){
	if ($PERSO->pnj==1) {
                $result=ModifierQuetePJ_Etape2($id_cible);
		//Ici on ajoute
		if ($result) {
			$valeurs=array();
			$valeurs[1]= $nom;	
			$PERSO->OutPut(GetMessage("modifQuetePJOK",$valeurs),true);
		}	
		else $template_main .= $db->erreur;	
		
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
	$SQL = "SELECT * FROM ".NOM_TABLE_QUETE." q,".NOM_TABLE_PERSO_QUETE." T1 WHERE T1.id_perso = ".$id_cible." and T1.id_quete=q.id_quete and q.proposepar = ".$PERSO->ID." and q.proposepartype=2 ORDER BY T1.id_quete";
	$resultPJQ = $db->sql_query($SQL);
	if($db->sql_numrows($resultPJQ) > 0){
		$ListQuete = null;
		$compteur=0;
	        $valeurs=array();
	        $valeurs[1]=$libelle;		
		$template_main .= GetMessage("quetesdePJ",$valeurs)."<br />";
		$i=0;
		while($rowQuetePJ = $db->sql_fetchrow($resultPJQ)) {
				$ListQuete[$compteur]=new QuetePJ($rowQuetePJ["id_quete"],$rowQuetePJ["id_persoquete"], $rowQuetePJ["etat"], $rowQuetePJ["debut"], $rowQuetePJ["fin"]);
				if ($ListQuete[$compteur]!=null)
					$compteur++;					
				$i++;	
		}
		include('../admin/forms/quetePJ.form.'.$phpExtJeu);
		
	} else {
	        $valeurs=array();
	        $valeurs[1]=$libelle;
		$template_main .= GetMessage("pjsansQuete",$valeurs);
	}
	$template_main .= "<input type='hidden' name='nom' value='".$libelle."' />";
	$template_main .= '<input type="hidden" name="chaine" value="" />';
	$template_main .= "<br /><input type='submit' value='Envoyer' onclick=\"return confirm('".GetMessage("ConfirmerSupprimerQuete")."')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "</form>";
	include('../admin/forms/quetepj2.form.'.$phpExtJeu);
	$template_main .= "</div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "Select concat(concat(T1.id_perso,'$sep'),T1.nom) as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1, ".NOM_TABLE_QUETE." q,".NOM_TABLE_PERSO_QUETE." T2 where T2.id_perso=T1.id_perso and T2.id_quete=q.id_quete and q.proposepar = ".$PERSO->ID." and q.proposepartype=2 ORDER BY T1.nom ASC";
	$var= faitSelect("id_cible",$SQL,"",-1);
        if ($var[0]>0) {
	        $template_main .= GetMessage("modifQuetePJ")."<br />";
	        $template_main .= $var[1];
	        $template_main .= "<br />".BOUTON_ENVOYER;	        
	        $template_main .= "<input type='hidden' name='etape' value='1' />";
        }
        else $template_main .= GetMessage("AucunPJpourTesQuetes")."<br />";	        
	$template_main .= "</form></div>";
}




if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>