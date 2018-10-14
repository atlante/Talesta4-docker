<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: quetePJ.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/04 20:46:02 $

*/


$template_main .= "<table class='details'>";
	$template_main .= "<tr><td>&nbsp;</td><td>Nom Quete</td><td>Proposee par</td><td>Etat</td><td>Démarrée le</td><td>A terminer avant le</td><td>nouvelle durée (en j) -1 pour illimitée</td></tr>";

for($i=0;$i<$compteur;$i++){
	$template_main .= "<tr>";
	$template_main .= "<td>Supprimer<input type='checkbox' name='del[".$ListQuete[$i]->id_persoquete."]' /></td>";
	$template_main .= "<td><a href=\"javascript:a('../bdc/quete.$phpExtJeu?num_quete=".$ListQuete[$i]->id_quete;
	if (NOM_SCRIPT<> ("gerer_quete_proposee.".$phpExtJeu) )
	        $template_main .= "&amp;for_mj=1')\">";
	else $template_main .= "')\">";        
	$template_main .= span($ListQuete[$i]->nom_quete,"quete");
	$template_main .= "</a></td>";
	$template_main .= "<td>".$ListQuete[$i]->acteurProposant->nom."</td>";
	$template_main .= "<td><input type='hidden' value='".$ListQuete[$i]->etat."' name='old_etat[".$ListQuete[$i]->id_persoquete."]' /><select name='etat[".$ListQuete[$i]->id_persoquete."]'>";
		$toto = array_keys($liste_etat_quete);
		$tata = array_values($liste_etat_quete);
		$nbEtatQuete= count($liste_etat_quete);
		for($j=0;$j<$nbEtatQuete;$j++){
		        //supprime refusee ,reussie en attente de validation et echouee pour temps si besoin
		        if (((!$ListQuete[$i]->refusPossible) && $ListQuete[$i]->etat<>$toto[$j] && $tata[$j]==3) 
		        || ((!$ListQuete[$i]->validationquete) && $ListQuete[$i]->etat<>$toto[$j] && $tata[$j]==6)
		        || ((!$ListQuete[$i]->fin==-1) && $ListQuete[$i]->etat<>$toto[$j] && $tata[$j]==9)){
		        } 
		        else {
        			$template_main .= "\t<option value='".$toto[$j]."'";
        			if($toto[$j] == $ListQuete[$i]->etat){ $template_main .= " selected='selected'";}
        			$template_main .= ">".$tata[$j]."</option>\n";	
                        }
		}
		$template_main .= "</select></td>";	
	$template_main .= "<td>".span(strftime ("  %d %B %Y %Hh%Mmin%Ss",$ListQuete[$i]->debut),"po")."</td>";
	if ($ListQuete[$i]->fin!=-1)
	        $template_main .= "<td>".span(strftime ("  %d %B %Y %Hh%Mmin%Ss",$ListQuete[$i]->fin),"po")."</td>";
	else   $template_main .= "<td>".span("illimitée","po")."</td>";     
	$template_main .= "<td><input type='text' value='' name=duree[".$ListQuete[$i]->id_persoquete."]' /></td>";
	$template_main .= "</tr>\n";
}
$template_main .= "</table>";
?>