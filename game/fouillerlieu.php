<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: fouillerlieu.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.20 $
$Date: 2010/01/24 17:44:02 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}

if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $fouiller_lieu;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
unset($etape);

if(!$PERSO->Lieu->permet($liste_flags_lieux["FouillerLieu"])){
	$template_main .= GetMessage("noright");
}
else {
	if((!$PERSO->Archive) && $PERSO->ModPA($liste_pas_actions["FouillerLieu"]) && $PERSO->ModPI($liste_pis_actions["FouillerLieu"])	){
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
		$PERSO->OutPut($mess);
		$trouve_lieu=false;
		$trouve_objet=false;
		$trouve_pj=false;
		traceAction("FouillerLieu", $PERSO);
		//$toto = array_keys($liste_type_objetSecret);		
		//recherche des passages secrets
		$template_main .= "<div class ='centerSimple'>";
		for($i=0;$i<count($PERSO->Lieu->Chemins);$i++){
			if($PERSO->Lieu->Chemins[$i]->type == $liste_types_chemins["Lieu Secret"]){
				//$reussite = ($PERSO->GetNiveauComp($liste_comp_full["Dissimulation"],true)+$PERSO->GetNiveauComp($liste_comp_full["Observation"],true) -lanceDe(20)) - (lanceDe($PERSO->Lieu->Chemins[$i]->difficulte)) ;
				$reussite = reussite_fouillerlieu_chemin($PERSO, $PERSO->Lieu->Chemins[$i]);
				if($reussite > 0){
					$trouve_lieu=true;
					$valeurs[0]=$PERSO->Lieu->Chemins[$i]->Arrivee->nom;
					//$PERSO->OutPut(GetMessage("fouiller_lieu_01",$valeurs));
					$template_main .= "\n<form name='formlieu".$i."' method='post' action='./reveler_entitecachee.$phpExtJeu'>
					<!--<input type='hidden' name='etape' value='1' />-->
					<input type='hidden' name='id_entitecachee' value='".$PERSO->Lieu->Chemins[$i]->ID.$sep.$valeurs[0]."'>";
					$PERSO->OutPut( GetMessage("fouiller_lieu_chemin01",$valeurs));
					$template_main .= " Voulez-vous le dévoiler ? ".BOUTON_ENVOYER."</form>";
					$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEECONNUEDE."  (id_entitecachee, id_perso) select e.id,  ".$PERSO->ID." from ".NOM_TABLE_ENTITECACHEE ." e where e.id_entite = ".$PERSO->Lieu->Chemins[$i]->ID;
					$db->sql_query($SQL);
				} 
			}
		}
	
	
		//recherche des pjs dissimules
		//$SQL = "Select P.* FROM ".NOM_TABLE_REGISTRE." P, (".NOM_TABLE_ENTITECACHEE." E left join ".NOM_TABLE_ENTITECACHEECONNUEDE ." ECCD ON
		//E.id= ECCD.id_entitecachee and E.type=2) WHERE P.id_perso <> ".$PERSO->ID." AND P.archive=0 and P.id_lieu = ".$PERSO->Lieu->ID ."
		//and P.dissimule = 1 and P.id_perso= E.id_entite and P.id_lieu = E.id_lieu
		//and (ECCD.ID is null or (ECCD.id_perso is not null and  ECCD.id_perso <>".$PERSO->ID."))";
	
	
		$SQL = "SELECT E.ID as identite,P.* FROM 
		(".NOM_TABLE_ENTITECACHEE." E
		LEFT JOIN ".NOM_TABLE_ENTITECACHEECONNUEDE ." ECCD ON 
		E.ID = ECCD.id_entitecachee
		and (ECCD.id_perso = ".$PERSO->ID." or ECCD.id_perso is null)), ".NOM_TABLE_REGISTRE." P 
		where ECCD.id_entitecachee is null and E.type=2
		and P.id_perso <> ".$PERSO->ID." AND P.archive=0 and P.id_lieu = ".$PERSO->Lieu->ID ."
		and P.dissimule = 1 and P.id_perso= E.id_entite and P.id_lieu = E.id_lieu";
	
		$result = $db->sql_query($SQL);
		$nb_persos = $db->sql_numrows($result);
		for($i=0;$i<$nb_persos;$i++){
			$reussite = reussite_fouillerlieu_pj($PERSO);
			$row = $db->sql_fetchrow($result);
			if($reussite > 0){
				$trouve_pj=true;
				$valeurs[0]=$row["nom"];
				$template_main .= "\n<form name='formpj".$i."' method='post' action='./reveler_entitecachee.$phpExtJeu'>
				<!--<input type='hidden' name='etape' value='1' />-->
				<input type='hidden' name='id_entitecachee' value='".$row["identite"].$sep.$valeurs[0]."'>";
				$PERSO->OutPut( GetMessage("fouiller_lieu_01",$valeurs));
				$template_main .= " Voulez-vous le démasquer ? ".BOUTON_ENVOYER."</form>";
				$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEECONNUEDE."  (id_entitecachee, id_perso) values  (".$row["identite"].",  ".$PERSO->ID.")";
				$db->sql_query($SQL);
			} 
		}
	
		//recherche des objets secrets que l'on ne connait pas encore
		//$SQL = "Select * from ".NOM_TABLE_ENTITECACHEE .",".NOM_TABLE_ENTITECACHEECONUEDE ." where ID_entitecachee=ID and perso_id =". $PERSO->ID;
		//$SQL = "Select E.ID as identite, ".NOM_TABLE_OBJET.".nom,id_objet, e.type  from ".NOM_TABLE_ENTITECACHEE." E,".NOM_TABLE_OBJET." where  id_objet= E.id_entite  and E.type =". $toto[1];
		//$SQL = "Select E.ID as identite, O.nom,id_objet  from (".NOM_TABLE_ENTITECACHEE." E left join ".NOM_TABLE_ENTITECACHEECONNUEDE ." ECCD ON".
		//" E.id= ECCD.id_entitecachee and E.type =1), ".NOM_TABLE_OBJET." O  where  O.id_objet= E.id_entite  " 
		//." and (ECCD.ID is null or (ECCD.id_perso <> ". $PERSO->ID ." and ECCD.id_perso is not null)) and E.id_lieu = ".$PERSO->Lieu->ID ;
	
		$SQL = "SELECT E.ID as identite, E.nom,E.id_entite  FROM 
		(".NOM_TABLE_ENTITECACHEE." E
		LEFT JOIN ".NOM_TABLE_ENTITECACHEECONNUEDE ." ECCD ON 
		E.ID = ECCD.id_entitecachee
		and (ECCD.id_perso = ".$PERSO->ID." or ECCD.id_perso is null))
		where ECCD.id_entitecachee is null and E.type=1	 and E.id_lieu = ".$PERSO->Lieu->ID ; 
	
		$result = $db->sql_query($SQL);
		$nb_objets = $db->sql_numrows($result);
		for($i=0;$i<$nb_objets;$i++){
			$reussite = reussite_fouillerlieu_objet($PERSO);
			$row = $db->sql_fetchrow($result);
			if($reussite > 0){
				$trouve_objet=true;
				$valeurs[0]=$row["nom"];
				$template_main .= "\n<form name='formobjrecup".$i."' method='post' action='./recuperer_objet.$phpExtJeu'>
				<input type='hidden' name='etape' value='1' />
				<input type='hidden' name='id_entitecachee' value='".$row["identite"].$sep.$valeurs[0]."'>";
				$PERSO->OutPut( GetMessage("fouiller_lieu_01",$valeurs));
				$template_main .= " Voulez-vous le ramasser ? ".BOUTON_ENVOYER."</form>";
				$template_main .= "<form name='formobjdev".$i."' method='post' action='./reveler_entitecachee.$phpExtJeu'>
				<!--<input type='hidden' name='etape' value='1' />-->
				<input type='hidden' name='id_entitecachee' value='".$row["id_entite"].$sep.$valeurs[0]."'>";
				$template_main .= " Voulez-vous le dévoiler ? ".BOUTON_ENVOYER."</form>";			
				$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEECONNUEDE."  (id_entitecachee, id_perso) values  (".$row["identite"].",  ".$PERSO->ID.")";
				$db->sql_query($SQL);
			} 
		}
		$template_main .= "</div>";
		$reussite = reussite_fouillerlieu($PERSO,$trouve_lieu,$trouve_objet,$trouve_pj);
		if ( ! $reussite)
			$PERSO->OutPut(GetMessage("fouiller_lieu_02"));	
		
	} else {
		if ($PERSO->RIP())
			$template_main .= GetMessage("nopvs");
		else	
		if (! $PERSO->Archive)
			$template_main .= GetMessage("nopas");
	}
}	
	
if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>