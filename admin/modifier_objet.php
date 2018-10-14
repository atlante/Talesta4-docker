<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: modifier_objet.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.22 $
$Date: 2010/02/28 22:58:08 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $mod_obj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$liste_champs=array(
		"nom","type","sous_type","degats_min","degats_max","durabilite","prix_base","description","poids","image","permanent",
		"munitions","caracteristique","competence","provoqueetat","competencespe","anonyme","id_etattempspecifique","composantes"
	);

if(!isset($etape)){$etape=0;}


if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierObjet"])){
		$SQL = "UPDATE ".NOM_TABLE_OBJET." SET ";
		$tmp = explode(";",$type);
		$type = $tmp[0];
		$sous_type = $tmp[1];
		if($munitions == 0){$munitions = -1;}
		if($durabilite == 0){$durabilite = -1;}
		if($old_munitions == 0){$old_munitions = -1;}
		if($old_durabilite == 0){$old_durabilite = -1;}
		if(($type != 'ArmeMelee') && ($type != 'ArmeJet')) {$anonyme = 0;}
		$provoqueetat = $chaine;
		// on trie $chaine2 par ID croissant pour combinet_objets
		$tmp2 = explode(";",$chaine2);
		if($tmp2[0]) {
			logdate("nb tmp2".count($tmp2));
			sort($tmp2);
			logdate("nb tmp2".count($tmp2));
			array_shift($tmp2);//supprime le null du debut
			logdate("nb tmp2".count($tmp2));
			$chaine2 = implode(";",$tmp2).";";

		}
		$composantes=$chaine2;		
		$nbchamps = count($liste_champs);
		for($i=0;$i<$nbchamps;$i++){
				$SQL.=$liste_champs[$i]."= '".ConvertAsHTML($$liste_champs[$i])."'";
				if($i != ($nbchamps -1) ){$SQL .= ",";}
		}
		$SQL .= " WHERE id_objet = ".$id_cible;
		$results=$db->sql_query($SQL);
		if ($results) {
		        /*on met a jour tous les objets des joueurs du meme type pour eviter
		          d'avoir un objet a 50/50 pendant qu'un autre est illimite par ex.
		          Si la nouvelle durabilite est illimitee => objets passent en illimites (bonus)
		          sinon on fait un produit en croix pour qu'un objet deja utilise le soit toujours 
		        */
		        $SQL_temp= "update ".NOM_TABLE_PERSOOBJET." set id_objet=id_objet ";
		        $SQL= $SQL_temp;
		        if ( $durabilite <> $old_durabilite) {
		                if ($durabilite == -1)
		                        $SQL.= ", durabilite= -1 ";
		                else $SQL.= ", durabilite=  ceil(durabilite*".$durabilite ."/".$old_durabilite .") ";
		        }
		        if ($munitions <> $old_munitions)  {
		                if ($munitions == -1)
		                        $SQL.= ", munitions= -1 ";
		                else $SQL.= ", munitions=  ceil(munitions*".$munitions ."/".$old_munitions .") ";
		        }
		        if ($SQL <> $SQL_temp) {
		               $SQL .=" WHERE id_objet = ".$id_cible;
		               $results=$db->sql_query($SQL);
		        }       
		}        
		if ($results) {
			$MJ->OutPut("Objet ".span(ConvertAsHTML($nom),"objet")." correctement modifi&eacute;",true);
			$etape=0;
		}	
		else {
			$template_main .= ($db->erreur);	
			// reconcatene le type et sous type
			$type = $tmp[0].";".$tmp[1];			
			$etape="1bis";
		}	
	}
	else $template_main .= GetMessage("droitsinsuffisants");	
}

if($etape=="1"){
	$SQL = "SELECT * FROM ".NOM_TABLE_OBJET." WHERE id_objet = ".$id_cible;
	$result = $db->sql_query($SQL);
	if (($row = $db->sql_fetchrow($result))) {
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
			$$liste_champs[$i] = $row[$liste_champs[$i]];
		}
	$type = $type.";".$sous_type;
	
	$tmp = explode("|",$provoqueetat);
	$i=0;
	$provoqueetatValue="";
        	while ($tmp[$i] && $row) {
		$temp = explode(";",$tmp[$i]);
		//supprime le moins eventuel
		if ($temp[0]{0}=="-") {
			$operateur="etatsupprime";
			$temp[0]=substr($temp[0],1);
		}
		else $operateur="etatajout";			
		$SQL = "SELECT nom FROM ".NOM_TABLE_ETATTEMPNOM." WHERE id_etattemp = ".$temp[0];
		$result=$db->sql_query($SQL);	
        		if ($row = $db->sql_fetchrow($result)) {
		$provoqueetatValue.="<option value=\"".$tmp[$i]."\" class=\"".$operateur."\">".$row["nom"]
		. ";" . $temp[1] . '%;' . $temp[2] ." h"
		."</option>";	
		$i++;		
	}
                        else {
				$valeurs[0] = $temp[0];
				$template_main .=  GetMessage("EtatTempKO",$valeurs);
                        }        
                        
        	}

		$composantesValue="";
		$tmp=explode(";",$composantes);
		$i=0;
        		while ($tmp[$i] && $row) {	
			$SQL = "SELECT nom FROM ".NOM_TABLE_OBJET." WHERE id_objet = ".$tmp[$i];
			$result=$db->sql_query($SQL);	
			$row = $db->sql_fetchrow($result);
			$composantesValue.="<option value=\"".$tmp[$i]."\">".$row["nom"]."</option>";	
			$i++;		
		}
        }
        else $template_main .= GetMessage("noparam");
}

if((($etape=="1") && $row)||$etape=="1bis"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "<table class='detailscenter' width='60%'>";	
	include('forms/objet.form.'.$phpExtJeu);
	$template_main .= "<tr><td align='center' colspan='2'>";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= '<input type="hidden" name="provoqueetatValue" value="'.ConvertAsHTML($provoqueetatValue).'" />';
	$template_main .= '<input type="hidden" name="chaine" value="'.$provoqueetat.'" />';
	$template_main .= '<input type="hidden" name="provoqueetat" value="'.$provoqueetat.'" />';
	$template_main .= '<input type="hidden" name="composantesValue" value="'.ConvertAsHTML($composantesValue).'" />';
	$template_main .= '<input type="hidden" name="chaine2" value="'.$composantes.'" />';
	$template_main .= '<input type="hidden" name="composantes" value="'.$composantes.'" />';

	$template_main .= "<input type='submit' value='Envoyer' onclick=\"return confirm('Etes vous sur de vouloir effacer les &eacute;tats eventuellement selectionnes ?')\" />";
	$template_main .= "<input type='submit' value='Dupliquer cet objet' onclick=\"document.forms[0].etape.value='0bis';document.forms[0].action='creer_objet.$phpExtJeu';document.forms[0].submit();\" />
	</td></tr>";
	$template_main .= "</table></form>";

	include('forms/objet2.form.'.$phpExtJeu);
	
	include('forms/objet3.form.'.$phpExtJeu);
	$template_main .= "</div>";
}


if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel objet voulez vous modifier ?<br />";
	$SQL ="Select T1.id_objet as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type),case when anonyme<>0 then 'anonyme' else '' end)
	,'  --> '),T1.nom),'   - '),case when T1.type='Armure' then concat(concat(' (Protege de ',T1.competence),')') else '' end),' (Mun:'),T1.munitions),', Dur:'),T1.durabilite),', Degs '),T1.degats_min),'-'),T1.degats_max),', poids '),T1.poids),', Prix '),T1.prix_base),')') as labselect 
	from ".NOM_TABLE_OBJET." T1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>