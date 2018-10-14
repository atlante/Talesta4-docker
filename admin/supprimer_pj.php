<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: supprimer_pj.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.26 $
$Date: 2010/05/15 08:52:49 $

*/

require_once("../include/extension.inc");if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
if (!isset($pnj))
        $pnj=1;
        
if ($pnj==2)
        $titrepage = $supprimer_bestaire;
else 
        $titrepage = $suppr_pj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}


$liste_champs=array(
		"nom","pa","pv","po","banque","pi","id_lieu","email","interval_remisepa","interval_remisepi","wantmail","pnj","reaction","actionsurprise","relation","phrasepreferee","sortprefere", "dissimule","background","commentaires_mj","pourcentage_reaction"
	);

if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["SupprimerPJ"]) || ( NOM_SCRIPT=="supprimerBestiaire.".$phpExtJeu && $MJ->aDroit($liste_flags_mj["SupprimerBestiaire"]))){
                $pj = new Joueur($id_cible);
                if ($pj->supprimer())
                        $MJ->OutPut("PJ/PNJ ".span(ConvertAsHTML($nom),"objet")." correctement effac&eacute;",true);
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$MJ->OutPut($db->erreur);
	$etape=0;
}
if($etape=="1"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
/*	if(defined("IN_FORUM")&& IN_FORUM==1)  && $forum->champimage!=null  
		$SQL = "SELECT p.*, u.".$forum->champimage.",u.".$forum->champtypeimage." FROM ".NOM_TABLE_REGISTRE." p,".USERS_TABLE." u WHERE u.username = p.nom and p.id_perso = ".$id_cible;
	else $SQL = "SELECT * FROM ".NOM_TABLE_REGISTRE." WHERE id_perso = ".$id_cible;
	$result = $db->sql_query($SQL);
	
	$row = $db->sql_fetchrow($result);
*/

	if(defined("IN_FORUM")&& IN_FORUM==1 && $forum->champimage!=null)  
		$SQL = "SELECT p.* , T2.id_etattemp, T3.nom as nometat, T4.nomtype, u.".$forum->champimage.",u.".$forum->champtypeimage."
			FROM ".NOM_TABLE_REGISTRE." p
			LEFT JOIN 
			". NOM_TABLE_PERSOETATTEMP. " T2
			 ON T2.id_perso = p.id_perso 
			left join ".NOM_TABLE_ETATTEMPNOM." T3 on T3.id_etattemp = T2.id_etattemp
			left join  ".NOM_TABLE_TYPEETAT." T4 ON T4.id_typeetattemp = T3.id_typeetattemp
			left join ".$forum->nomtableUsers." u on u.username = p.nom 
			WHERE p.id_perso  =".$id_cible;
	else	$SQL = "SELECT p.* , T2.id_etattemp, T3.nom as nometat, T4.nomtype
			FROM ".NOM_TABLE_REGISTRE." p
			LEFT JOIN 
			". NOM_TABLE_PERSOETATTEMP. " T2
			 ON T2.id_perso = p.id_perso 
			left join ".NOM_TABLE_ETATTEMPNOM." T3 on T3.id_etattemp = T2.id_etattemp
			left join  ".NOM_TABLE_TYPEETAT." T4 ON T4.id_typeetattemp = T3.id_typeetattemp
			WHERE p.id_perso  =".$id_cible;
	$resultType = $db->sql_query($SQL);
	$rowType = $db->sql_fetchrow($resultType);
	$row = $rowType;

	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champs[$i] = $row[$liste_champs[$i]];
	}

	 do {
		$id_etat = "id_".preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$rowType['nomtype']);
		${$id_etat}=$rowType["id_etattemp"];
	 }
	 while(	$rowType = $db->sql_fetchrow($resultType));
	

	if(defined("IN_FORUM")&& IN_FORUM==1  && $forum->champimage!=null)  {
		$imagetype = $row[$forum->champtypeimage];
		$imageforum = $row[$forum->champimage];
	}
	else {
		$imagetype = "";
		$imageforum = "";
	}		
	//include('forms/pnj.form.'.$phpExtJeu);
	$nom_fichier = "../pjs/descriptions/desc_".$id_cible.".txt";
	if(file_exists($nom_fichier)){
		$content_array = file($nom_fichier);
		$content = implode("", $content_array);
		$description= stripslashes($content);
	}
	else $description="";	
	include('forms/infospj.form.'.$phpExtJeu);
	$template_main .= "<br /><input type='submit' value='effacer' onclick=\"return confirm('Etes vous sur de vouloir effacer le PJ/PNJ ".$nom." ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='nom' value='".$nom."' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "</form></div>";
}

if((NOM_SCRIPT=="supprimer_pj.".$phpExtJeu || NOM_SCRIPT=="supprimerBestiaire.".$phpExtJeu ) && $etape==0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	if (NOM_SCRIPT=="supprimerBestiaire.".$phpExtJeu ) 
	        $template_main .= GetMessage("BestiaireaSupprimer");
	else 
	        $template_main .= GetMessage("PJaSupprimer");
	$template_main .= "<br />";
	if (NOM_SCRIPT=="supprimerBestiaire.".$phpExtJeu )
	        $SQL = "Select T1.id_perso as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 where T1.pnj = 2 ORDER BY T1.nom ASC";
        else $SQL = "Select T1.id_perso as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 where T1.pnj <> 2 ORDER BY T1.nom ASC";	        
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}



if(NOM_SCRIPT=="supprimer_pj.".$phpExtJeu || NOM_SCRIPT=="supprimerBestiaire.".$phpExtJeu ) {
	if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
}	
?>