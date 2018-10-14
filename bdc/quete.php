<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: quete.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/04 20:46:16 $

*/

require_once("../include/extension.inc");
include('../include/http_get_post.'.$phpExtJeu);

if(isset($for_mj)){
	if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
	if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
}
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($num_quete)){$num_quete = 1;}
$peutvoir=false;
$Quete_vu = new Quete($num_quete);
if(isset($MJ)|| isset($for_mj)){
	$peutvoir=true;
}
if(isset($PERSO)){
        if ($PERSO->ID== $Quete_vu->acteurProposant->ID && $proposepartype==2)
                $peutvoir=true;
        if (!$peutvoir){
        	$trouve=false;
        	$i=0;
        	$nbQuetes = count($PERSO->Quetes);
        	while($i<$nbQuetes && $peutvoir==false){
        		if ($num_quete == $PERSO->Quetes[$i]->id_quete) {
        			$peutvoir=true;
        			$Quete_vu = $PERSO->Quetes[$i];
        		}	
        		else $i++;
        	}
        }
}
if($peutvoir){
	if ($Quete_vu == false)
		$template_main .= GetMessage("QueteInexistante");
	else {			
		$template_main .= "<div class ='centerSimple'>";
		$template_main .= "Details de la Quete ".span($Quete_vu->nom_quete,"quete").", Num&eacute;ro <b>".$Quete_vu->id_quete."</b>";
		
		$template_main .= "<table class='detailscenter'>";
		$template_main .= "<tr>";
			/*if( ($Quete_vu->image != "") && (file_exists("../templates/$template_name/images/".$Quete_vu->image) ) ){
				$template_main .= "<td><img src='../templates/$template_name/images/".$Quete_vu->image."' border='0' alt='image du Quete' /></td>";
			}else{
				$template_main .= "<td>".GetImage($Quete_vu->type)."</td>";
			}*/
			$template_main .= "<td colspan='2'>".span($Quete_vu->nom_quete,"Quete")."</td>";
			$template_main .= "<td rowspan='12'>".$Quete_vu->texteProposition."</td></tr>";
			$template_main .= "<tr><td colspan='2'>".span($liste_type_quete[$Quete_vu->type_quete],"comp")." ".span($Quete_vu->detail_type_quete->nom,"comp")."</td></tr>";
/*
			if($Quete_vu->Soustype == "Soin"){
				$template_main .= "<tr><td>Gain de PV</td><td>entre ".span($Quete_vu->Degats[0],"degats")." et ".span($Quete_vu->Degats[1],"degats")."</td></tr>";
			} else {
				$template_main .= "<tr><td>Degats</td><td>entre ".span($Quete_vu->Degats[0],"degats")." et ".span($Quete_vu->Degats[1],"degats")."</td></tr>";
			}
*/			
                        $template_main .= "<tr><td>Propose par</td><td>";
                        if (isset($MJ) || (!$Quete_vu->proposantAnonyme) || (isset($PRSO) && $PERSO->ID == $Quete_vu->acteurProposant->ID)) {
                                if ($Quete_vu->proposepartype==1)
                                        $template_main .= span(" MJ ".$Quete_vu->acteurProposant->nom,"mj"); 
                                else $template_main .= span(" PJ ".$Quete_vu->acteurProposant->nom,"pj");
                                if ($Quete_vu->proposantAnonyme)
                                        $template_main .= "(anonymement)";
                                $template_main .= "</td></tr>";
                        }
                        else $template_main .= "Anonyme</td></tr>";
			if( ($Quete_vu->duree_quete == -1) ){
					$template_main .= "<tr><td colspan='2'>".span("dure illimite","mun")."</td></tr>";
			}else{
					$template_main .= "<tr><td>Temps pour agir</td><td>".span($Quete_vu->duree_quete,"cha")." Jours</td></tr>";
			}
			$template_main .= "<tr><td>cyclique</td>";
			if( ($Quete_vu->cyclique == 0) ){
				$template_main .= "<td>Non</td></tr>";
			}else{
				$template_main .= "<td>OUI</td></tr>";
			}
			$template_main .= "<tr><td>Publique</td>";
			if( ($Quete_vu->public == 0) ){
				$template_main .= "<td>NON</td></tr>";
			}else{
				$template_main .= "<td>OUI (dans lieu ".$Quete_vu->lieuAffiche->nom . ")</td></tr>";
			}

/*			$template_main .= "<tr><td>";
			$template_main .= "Rserve pour</td><td>";			
			if ($Quete_vu->EtatTempSpecifique!=null)
				$template_main .= span($Quete_vu->EtatTempSpecifique->nom,"etattemp");
			else $template_main .= "&nbsp;";	
			$template_main .= "</td></tr>";



			$template_main .= "<tr><td>";
			$template_main .= "Ncessite les composantes de Quete: </td><td>";			
			if ($Quete_vu->composantes!="") {
				$Quete_vu->setObjetsComposantesQuete();
				$nb_composantes = count($Quete_vu->ObjetsComposantesQuete);
				for($i=0;$i<$nb_composantes;$i++) {
					$temp = $Quete_vu->ObjetsComposantesQuete[$i];
					$template_main .= span($temp[0]->nom,"objet");
					if ($temp[1]==1)
						$template_main .= " " . GetMessage("composanteConservee");
					else
						$template_main .= " " . GetMessage("composanteDetruiteAuLancement");
					if ($i<$nb_composantes-1)
						$template_main .= ", ";
				}	
			}	
			else $template_main .= "&nbsp;";	
			$template_main .= "</td></tr>";
*/			


		$template_main .= "</table>\n";
//		if(!isset($MJ)){$template_main .= "<a href='Quete.$phpExtJeu?for_mj=1&amp;num_quete=".$num_quete."'>Plus de details (".span("MJ","mj")." uniquement)</a>";}
		$template_main .= "</div>";
	} 
} else {
	$template_main .= GetMessage("voirQueteKO");
}
if(!defined("__BARREGENERALE.PHP")){include("../include/barregenerale.".$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>