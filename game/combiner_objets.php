<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: combiner_objets.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.9 $
$Date: 2010/01/24 17:44:00 $

*/

require_once("../include/extension.inc");
include('../include/http_get_post.'.$phpExtJeu);
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN"))
	if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $combiner_obj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

if(!isset($etape)){
	$etape=0;
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_PERSOOBJET." T1, ".NOM_TABLE_REGISTRE." T2 WHERE T1.id_perso = T2.id_perso AND equipe = 0 and T1.id_perso = ".$PERSO->ID." ORDER BY T1.id_clef";
	$result = $db->sql_query($SQL);
	$row = $db->sql_fetchrow($result);
	if($db->sql_numrows($result) > 0){
		$ListeObj = null;
		$compteur=0;
		$template_main .= "Choisissez les objets  combiner";
		$i=0;
		while($row = $db->sql_fetchrow($result)){
			$ListeObj[$compteur]= new ObjetPJ($row["id_objet"],$row["id_clef"],$i, ($row["equipe"] == 1),($row["temporaire"]==1),$row["munitions"],$row["durabilite"]);
			if  ($ListeObj[$compteur]!=null)
				$compteur++;					
			$i++;
		}
		$template_main .= "<table class='detailscenter'>";
		for($i=0;$i<$compteur;$i++){
			$template_main .= "<tr>";
			$template_main .= "<td>Slectionner<input type='checkbox' name='sel[".$ListeObj[$i]->id_clef."]' value=".$ListeObj[$i]->ID." /></td>";
			if($ListeObj[$i]->estPermanent()){
				$template_main .= "<td>#</td>";
			}else{
				$template_main .= "<td>-</td>";
			}
			if( ($ListeObj[$i]->image != "") &&(file_exists("../templates/$template_name/images/".$ListeObj[$i]->image)) ){
				$template_main .= "<td><img src='../templates/$template_name/images/".$ListeObj[$i]->image."' border='0' alt='image de l''objet' /></td>";
			}else{
				$template_main .= "<td>".getImage($ListeObj[$i]->Soustype)."</td>";
			}
		
			$template_main .= "<td>";
			if($ListeObj[$i]->temporaire == 0){ $span = "objet";}else{$span = "temporaire";}
			if($ListeObj[$i]->equipe == 1){$span = "equipe";}
			$temp = $ListeObj[$i]->nom." (".$ListeObj[$i]->Soustype.")";
			if($ListeObj[$i]->competencespe != ""){$temp .= " - ".$ListeObj[$i]->competencespe;}
			if($ListeObj[$i]->anonyme == 1){$temp .= " - Anonyme";}
			if($ListeObj[$i]->equipe == 1){$temp = "* ".$temp." *";}
		
			$template_main .= span($temp,"objet");
			$template_main .= "</td>";
			$template_main .= "</tr>\n";
		}
		$template_main .= "</table>";
	} else {
		$template_main .= "Vous n'avez pas d'objet dans votre inventaire";
	}
	$template_main .= "<br /><input type='submit' value='Envoyer'  />";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form>";
	$template_main .= "</div>";
}



if($etape=="1"){
	if(isset($sel)){
		asort($sel);
		$select=array_values($sel);		
		$selection=";".implode(";;",$select).";;";
		$SQL = "Select concat(concat(T1.id_objet,'$sep'),composantes) as idselect, ";
		if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
			$SQL .= "concat(concat(";
		$SQL .= "concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type),
		CASE WHEN T1.anonyme = 1 THEN ' - (Anonyme)' ELSE '' END),'  --&gt; '),T1.nom),'   - '),
		CASE WHEN T1.type = 'Armure' THEN concat( concat( ' (Protege de ', T1.competence ) , ')' ) 
		ELSE '' END ) , ' - ' ) , CASE WHEN T1.type = 'Armure' THEN concat( T1.degats_min, ' pts de protect' ) 
		ELSE concat( 'Degats :', T1.degats_min ) 
		END ) , '  ' ) , T1.degats_max ) , ' - ' ) , substring( T1.description, 1, 40 ) ) , '... ' )";
		if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
			$SQL .= ", T1.prix_base ) , ' POs - ' )";
		$SQL .= ", T1.poids ) , ' kg' )  as labselect
		from ".NOM_TABLE_OBJET." T1 WHERE '".$selection."' like concat('%;',replace (composantes,';',';%;')) and composantes is not null and length( composantes ) >0 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
		$var=faitSelect("ID_OBJET",$SQL);
		$template_main .= "<div class ='centerSimple'>";
		if ( $var[0]>0) {
			$template_main .= "<form action='".NOM_SCRIPT."' method='post'>";
			$template_main .= "Voici les objets que vous pouvez crer :";
			$template_main .= $var[1];
			$template_main .= "<br /><input type='submit' value='Envoyer'  />";
			$template_main .= "<input type='hidden' name='etape' value='2' />";
			//$template_main .= "<input type='hidden' name='test' value='".$test."' />";
			$template_main .= "</form>";
		}
		else $template_main .= "Vous ne pouvez rien faire avec ca";
		$template_main .= "</div>";
	}
	else
	$template_main .= "vous devez slectionner quelque chose";
}
if($etape=="2"){
	if( $PERSO->ModPA($liste_pas_actions["CombinerObjets"]) && $PERSO->ModPI($liste_pis_actions["CombinerObjets"])	){
		$pos = strpos($ID_OBJET, $sep);
		$dels=ConvertAsHTML(substr($ID_OBJET, $pos+strlen($sep))); 
		$ID_OBJET=substr($ID_OBJET, 0,$pos); 		
		$del = explode(";",$dels);		
		$objetACreer = new Objet($ID_OBJET);	
		$ObjetCherche = null;
		$echec=false;
		$ObjetsComposants = array();
		$j=0;
		// count($del)-1 pour enlever le ; de fin qui fait chercher un objet d'ID 0...
		while ($j<count($del)-1 && $echec==false){
			$trouve=false;
			$i=0;
			$nb_obj = count($PERSO->Objets);
			while(($i<$nb_obj) && ($trouve===false)){
				if (($del[$j] == $PERSO->Objets[$i]->ID) && !(in_array ($PERSO->Objets[$i]->id_clef, $ObjetsComposants))) {
					$trouve=true;
					array_push($ObjetsComposants,$PERSO->Objets[$i]->id_clef);
				}	
				else $i++;
			}			
			if ((!$trouve))
				$echec=true;
			else $j++;
		}
		if ($echec==false) {
			if (count($del)-1>1)
				$reussite=reussite_combiner_objet($PERSO,$objetACreer);
			else 	{
				//reussite auto si un seul objet
				$reussite = 1;
			}	
			$valeurs=array();
			$valeurs[0]=$objetACreer->nom;
			if($reussite > 0){
				if ($PERSO->AcquerirObjet($objetACreer)) {
					$nbObjetsComposants = count($ObjetsComposants);
					for($i=0;$i<$nbObjetsComposants;$i++)
						$PERSO->EffacerObjet($ObjetsComposants[$i]);
					$PERSO->OutPut(GetMessage("combiner_01",$valeurs));	
				}				
				else $PERSO->OutPut(GetMessage("combiner_03",$valeurs));
			} else {
				$PERSO->OutPut(GetMessage("combiner_02",$valeurs));
			}
		}
		else
		$template_main .= GetMessage("composantesAbsentes");
	}
	else 
	$template_main .= GetMessage("nopas");
}

if(defined("PAGE_EN_JEU")) {
	$template_main .= "<br /><p>&nbsp;</p>";
	if(!defined("__MENU_JEU.PHP")){include('../game/menu.php');}
	if(!defined("__FOOTER.PHP")){include('../include/footer.php');}
}
?>