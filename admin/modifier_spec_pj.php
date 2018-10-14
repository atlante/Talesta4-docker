<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: modifier_spec_pj.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.16 $
$Date: 2006/09/05 06:41:21 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}

if(!isset($pnj)){$pnj=0;}

if ($pnj==0)
	$titrepage = $mod_spec_pj;
else 
        $titrepage = $mod_spec_bestiaire;


if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!isset($etape)){$etape=0;}

if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierSpecPJ"])){
			$result=true;
			if(isset($del)){
				$toto = array_keys($del);
				$tata = array_values($del);
				$SQL = "DELETE FROM ".NOM_TABLE_PERSOSPEC." WHERE id_perso = '".$id_cible."' AND (";
				for($i=0;$i<count($del);$i++){
					if($tata[$i] == "on"){
						$SQL.= " id_clef = '".$toto[$i]."' OR";
					}
				}
				$SQL = substr($SQL,0,strlen($SQL)-2).")";;
				$result=$db->sql_query($SQL);
				
			}

			//La on a efface et mis a jour
			if(isset($chaine) && ($result!==false)){
				$liste = explode(";",$chaine);
				for($i=0;$i<count($liste)-1;$i++){
					$SQL = "SELECT * FROM ".NOM_TABLE_SPECNOM." WHERE id_spec = ".$liste[$i];
					$result = $db->sql_query($SQL);
					if($db->sql_numrows($result) > 0){
						$SQL = "INSERT INTO ".NOM_TABLE_PERSOSPEC." (id_perso,id_spec) VALUES ('".$id_cible."','".$liste[$i]."')";
						$result=$db->sql_query($SQL);
					}
					
				}
			}
			if ($result!==false)
				$MJ->OutPut("sp&eacute;cialisations de  ".span(ConvertAsHTML($nomPJ),"pj")." correctement modif&eacute;",true);
			else 	$MJ->OutPut($db->erreur);
		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$id_cible=$id_cible.$sep.$nomPJ;
	$etape=1;
}
if($etape=="1"){
	$pos = strpos($id_cible, $sep);
	$libelle=ConvertAsHTML(substr($id_cible, $pos+strlen($sep))); 
	$id_cible=substr($id_cible, 0,$pos); 		
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT T1.*,  T3.nom, T3.visible FROM ".NOM_TABLE_PERSOSPEC." T1,  ".NOM_TABLE_SPECNOM." T3 WHERE T1.id_perso = ".$id_cible." AND T1.id_spec = T3.id_spec ORDER BY T1.id_clef";
	$result = $db->sql_query($SQL);
	if($db->sql_numrows($result) > 0){
		$template_main .= "Modifier les sp&eacute;cialisations de ".span(ConvertAsHTML($libelle),"pj")."<br />";
		$template_main .= "<table class='detailscenter'>\n";
		while(	$row = $db->sql_fetchrow($result)){
			$template_main .= "<tr>";
			$template_main .= "<td>Supprimer<input type='checkbox' name='del[".$row["id_clef"]."]' /></td>\n";
			$template_main .= "<td><a href=\"javascript:a('../bdc/spec.$phpExtJeu?for_mj=1&amp;num_spec=".$row["id_spec"]."')\">".span($row["nom"],"specialite")."</a></td>\n";
			if($row["visible"] == 0){
				$template_main .= "<td>Invisible dans la description de ".span($libelle,"pj")."</td>\n";
			} else {
				$template_main .= "<td>Visible dans la description de ".span($libelle,"pj")."</td>\n";
			}
			$template_main .= "</tr>";
		}
		$template_main .= "</table>\n";
	} else {
		$template_main .= span(ConvertAsHTML($libelle),"pj"). " n'a pas de sp&eacute;cialisations";
	}
	$template_main .= '<input type="hidden" name="chaine" value="" />';
	$template_main .= "<br /><input type='submit' value='Envoyer' onclick=\"return confirm('Etes vous sur de vouloir effacer les sp&eacute;cialisation eventuellement selectionnes ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "<input type='hidden' name='nomPJ' value='".$libelle."' />";
	$template_main .= "</form>\n";
	include('forms/specialisation2.form.'.$phpExtJeu);
	$template_main .= "</div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Les sp&eacute;cialisations de quel PJ voulez vous vous modifier ?<br />";
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