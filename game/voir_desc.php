<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: voir_desc.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.20 $
$Date: 2010/01/24 17:44:05 $

*/

require_once("../include/extension.inc");	
include('../include/http_get_post.'.$phpExtJeu);

if(isset($for_mj)){
	if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
	if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
}
else 	if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}

if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $voir_desc;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}	
if(!isset($id_perso)) 
        $template_main .= GetMessage("noparam");
else {
        if ( defined("PAGE_ADMIN") )
                $PERSO_VU=new Joueur($id_perso,true,true,true,true,true,true);
        else {        
                
        	if (defined("PAGE_EN_JEU")) {
        		if($PERSO->Archive){
        			//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
        			$etape="Archive";	
        			$template_main .= GetMessage("archive");
        		}	        	        
        	        else {
        	                if ($id_perso == $PERSO->ID)
        		                $PERSO_VU=$PERSO;        		
        		        $PERSO_VU=new Joueur($id_perso,true,true,true,true,true,true);
        		        if ($PERSO->Lieu->ID != $PERSO_VU->Lieu->ID || ($PERSO_VU->Archive)) {
        		                unset($PERSO_VU);
        		        }        
        		}
        	}
        }
	if (isset($PERSO_VU)) {
		/*$nom_fichier = "../pjs/descriptions/desc_".$id_perso.".txt";
		if(file_exists($nom_fichier)){
			$temp[1]= $nom_fichier;
		} else {
			$temp[1]= "../pjs/descriptions/nodesc.txt";
		}
		$content_array = file($temp[1]);
		$content = implode("", $content_array);
		$temp[1]= nl2br($content);
		*/
		$temp[1]=$PERSO_VU->getDescription();
		$template_main .= "description de ".span($PERSO_VU->nom,"pj")."<br />";
		$template_main .= $PERSO_VU->DescriptionAvatar();
		$template_main .= "<table class='details'>";
		$template_main .= "<tr><td>".stripslashes(nl2br($temp[1]))."</td></tr>";
		$template_main .= "</table>";
	
		$str1 = "<br />Vous devinez que ".span($PERSO_VU->nom,"pj")." possède les caractéristiques suivantes :";	
		$str2 = "<br />Et qu'il est atteint par les etats temporaires suivants :";
		$ok1=false;
		$ok2=false;
		$nbEtats= count($PERSO_VU->EtatsTemp);
		for($i=0;$i<$nbEtats;$i++){			
			if(((!defined("PAGE_ADMIN")) && $PERSO_VU->EtatsTemp[$i]->Visible==1) ||  defined("PAGE_ADMIN")) {
				if ($PERSO_VU->EtatsTemp[$i]->TypeEstCritereinscription ==0) {
					$str2.="<br />-".  span($PERSO_VU->EtatsTemp[$i]->nom,"etattemp");
					$ok2=true;
				}	
				else {
					$str1.="<br />-".  span($PERSO_VU->EtatsTemp[$i]->nom,"etattemp");	
					$ok1=true;
				}	
			}		
				
		}	
		if ($ok1)
			$template_main .= $str1."<br />";
		if ($ok2)
			$template_main .= $str2."<br />";
		$nbSpec=count($PERSO_VU->Specs);
		if ($nbSpec>0) {
			$okSpec=false;
			$strSpec= "Vous devinez que ".span($PERSO_VU->nom,"pj")." possede les sp&eacute;cialisations suivantes :";
		
			for($i=0;$i<$nbSpec;$i++){
				if(((!defined("PAGE_ADMIN")) && $PERSO_VU->Specs[$i]->Visible == 1) ||  defined("PAGE_ADMIN")) {
					$okSpec=true;
					$strSpec.= "<br />-".  span($PERSO_VU->Specs[$i]->nom,"specialite");
				}					
			}	
			if ($okSpec)
				$template_main .= $strSpec;
		}	
	
		if($PERSO_VU->PV <= 0)
			$template_main .= "<br />". GetMessage("nopvs_spect");
		else {
			$coeff= $PERSO_VU->PV*100/$PERSO_VU->GetPVMax();
			if($coeff >= POURCENTAGE_PV_PERSO_AUTOP)
				$template_main .= "<br />". GetMessage("pjautop_spect");		
			else if($coeff <= POURCENTAGE_PV_PERSO_CRITIQUE)	
				$template_main .= "<br />". GetMessage("pjcritique_spect");
			else if($coeff <= POURCENTAGE_PV_PERSO_ABIME)	
				$template_main .= "<br />". GetMessage("pjabime_spect");
			else if($coeff <= POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE)
				$template_main .= "<br />". GetMessage("pjlegerementblesse_spect");		
			if($PERSO_VU->PA <= 0)
				$template_main .= "<br />". GetMessage("nopas_spect");
			
			$i=0;
			$trouve=false;
			$equip = GetMessage("pjequip_spect");		
			while (isset($PERSO_VU->Objets[$i])){
				if ($PERSO_VU->Objets[$i]->equipe) {
					$trouve=true;
					$equip .= $PERSO_VU->Objets[$i]->nom.", ";
				}
				$i++;	
			}
			//$arme = $PERSO_VU->getnomArmePreferee();	
			//$equip.= $arme;
			//if ($trouve || $arme!="") $template_main .= "<br />".	$equip;
			if ($trouve) $template_main .= "<br />".	$equip;
		}	
	}
	else $template_main .= "Vous ne voyez pas ce Personnage";	
        $template_main .= "</div>";
}
if(!defined("__BARREGENERALE.PHP")){include("../include/barregenerale.".$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>