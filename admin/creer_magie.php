<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creer_magie.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.18 $
$Date: 2010/02/28 22:58:03 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_sort;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$liste_champs=array(
		"nom","type","sous_type","degats_min","degats_max","prix_base","description","place","image","permanent",
		"charges","caracteristique","competence","provoqueetat","anonyme","id_etattempspecifique","typecible",
		"sortdistant","composantes","coutpa","coutpi","coutpo","coutpv"
	);

if(!isset($etape)){$etape=0;}
if($etape=="1"){
	if($MJ->aDroit($liste_flags_mj["CreerMagie"])){
		if($charges == 0){$charges = -1;}
		$provoqueetat = $chaine;
		$tmp2 = explode(";",$chaine2);
		if($tmp2[0]) {
			sort($tmp2);
			array_shift($tmp2);//supprime le null du debut
			$chaine2 = implode(";",$tmp2).";";
		}
		$composantes=$chaine2;		

		$SQL = "INSERT INTO ".NOM_TABLE_MAGIE." (";
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
		if ($db->sql_query($SQL)) {
			$MJ->OutPut("Sort ".span(ConvertAsHTML($nom),"sort")." correctement cree",true);
			if (NOM_SCRIPT==("creer_magie.".$phpExtJeu))
				$etape="0";	
		}	
		else 	{
			$MJ->OutPut($db->erreur);
			if (NOM_SCRIPT==("creer_magie.".$phpExtJeu))
				$etape="0bis";
		}	

	}
	else $template_main .= GetMessage("droitsinsuffisants");
}
if($etape=="0"){
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champs[$i] = '';
	}
	$place=1;
	$charges=-1;
	$degats_min=0;
	$provoqueetatValue="";
	$permanent=1;
	$etape="0bis";
	$composantesValue="";
	$coutpo=0;
	$coutpv=0;

}

if($etape=="0bis"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";

	include('forms/magie.form.'.$phpExtJeu);

	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= '<input type="hidden" name="composantesValue" value="'.ConvertAsHTML($composantesValue).'" />';
	$template_main .= '<input type="hidden" name="chaine2" value="'.$composantes.'" />';
	$template_main .= '<input type="hidden" name="composantes" value="'.$composantes.'" />';
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "<input type='hidden' name='provoqueetatValue' value='".ConvertAsHTML($provoqueetatValue)."' />";
	$template_main .= "<input type='hidden' name='chaine' value='".$provoqueetat."' />";
	$template_main .= "<input type='hidden' name='provoqueetat' value='".$provoqueetat."' />";
	$template_main .= "</form>";
	
	include('forms/objet2.form.'.$phpExtJeu);

	include('forms/objetComposantesSort.form.'.$phpExtJeu);

	$template_main .= "</div>";	
}


if (NOM_SCRIPT==("creer_magie.".$phpExtJeu)) {
	if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
}	
?>