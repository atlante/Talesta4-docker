<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: parler.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.25 $
$Date: 2006/01/31 12:26:24 $

*/

	require_once("../include/extension.inc");
	if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
	if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $parler;

	if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}	


if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}


if(!$PERSO->Lieu->permet($liste_flags_lieux["Parler"])){
	$template_main .= GetMessage("noright");
}
else {	
	if(!isset($etape)) {
		$etape=0;
		$msg="";
	}	
		
	if($etape=="1"){
		if ($typeact=='lieu')  $pj=array();
		if ($typeact=='voisin')  $pj=$lx;
		if (!(isset($pj))) $pj=null;
		if (parler ($PERSO,$typeact,$msg,$pj,true,false))
			$template_main .= "<br /><p>&nbsp;</p>";
		else $etape=0;	
	}
	$ok=false;
	if($etape===0){
		$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
		
			$SQL = $PERSO->listePJsDuLieuDuPerso(1, false, true,1);
			$result = $db->sql_query($SQL);
			
			$nb_pj = $db->sql_numrows($result);
			if ($nb_pj>0) {
				$ok=true;
				$template_main .= "<input type='radio' name='typeact' value='pjs' checked='checked' />Parler &agrave; ces personnes<br />";	
				$i=0;
				while(	$row = $db->sql_fetchrow($result)){
					$template_main .= "<input type='checkbox' name='pj[".$row["idselect"]."]' />".span($row["labselect"],"pj");
					if ($i<$nb_pj-1)
						$template_main .= ", ";
					$i++;
				}
				$template_main .= "<br /><input type='radio' name='typeact' value='lieu' />Parler &agrave; toutes les personnes du lieu<br />";
				
				if ($PERSO->Groupe!="") {
					$groupePJ=new Groupe ($PERSO->Groupe,true);
					$pjs =$groupePJ->Persos;
					$existPersoGroupe=false;
					$i=0;
					while ($i<count($pjs) && ($existPersoGroupe==false)) {
						logdate($i ." nom". $pjs[$i]->nom);
						if (($pjs[$i]->Lieu->ID ==$PERSO->Lieu->ID) && ($pjs[$i]->ID <>$PERSO->ID))
							$existPersoGroupe=true;
						else $i++;	
					}	
					if ($existPersoGroupe)
						$template_main .= "<br /> <input type='radio' name='typeact' value=".$PERSO->Groupe." />Parler aux membres du groupe <br />";
					else 	$template_main .= "<input type='hidden' name='groupe' value='0' />";
				}
				else $template_main .= "<input type='hidden' name='groupe' value='0' />";
			}
			else $template_main .= "Il n'y a personne ici. <br />";
		if ($ok) {
			$template_main .= "<hr /><textarea name='msg' rows='20' cols='50'>".$msg."</textarea>";
			$template_main .= "<br />".BOUTON_ENVOYER;
			
			$template_main .= "<input type='hidden' name='etape' value='1' />";
			//$template_main .= "<input type='hidden' name='action' value='$action' />";
		}		
		$template_main .= "</form></div>";
	} 
}	
	if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>