<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: voirQueteLieu.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.2 $
$Date: 2010/01/24 17:44:04 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $voirQueteLieu;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}		
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}



if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'>";

	if ($dbmsJeu!="mysql" || $db->versionServerDiscriminante()>=4.1) {
		//requete a utiliser en sql 4.1 et + pour mysql ou pour les autres bases
        	$SQL ="select q.id_quete as idselect, q.nom_quete as labselect from ".NOM_TABLE_QUETE ." q where ".$PERSO->Lieu->ID ." = q.id_lieu and q.public=1
        	        and not exists (select 1 from ".NOM_TABLE_PERSO_QUETE." where id_perso = ".$PERSO->ID." and id_quete= q.id_quete)
        	        and ( q.id_etattempspecifique is null or not exists (select 1 from ".NOM_TABLE_PERSOETATTEMP." where id_perso = ".$PERSO->ID." and id_etattemp= q.id_etattempspecifique)) ";
        }
        else
        {
                $SQL ="select  q.id_quete as idselect, q.nom_quete as labselect from (".NOM_TABLE_QUETE ." q 
                left join ".NOM_TABLE_PERSO_QUETE." pq on  pq.id_perso = ".$PERSO->ID." and pq.id_quete= q.id_quete)
                left join  ".NOM_TABLE_PERSOETATTEMP."  pe on pe.id_perso = ".$PERSO->ID." and pe.id_etattemp= q.id_etattempspecifique
                where ".$PERSO->Lieu->ID ." = q.id_lieu and q.public=1
                and pq.id_quete is null
                and ( q.id_etattempspecifique is null and pe.id_etattemp is null)";        
        }                

	$var=faitSelect("id_quete",$SQL);
	if ($var[0]>0) {
	        $template_main .="<form action='".NOM_SCRIPT."' method='post'>";
		$template_main .= GetMessage("queteAconsulter")."<br />";
		$template_main .= $var[1];
		$template_main .= "<br />".BOUTON_ENVOYER;
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form>";
	}
	else $template_main .= GetMessage("PasQuetePublique")."<br />";		
	
	$template_main .= "</div>";
	$etape=0;
} 
else {
        if($etape=="2"){
        	
        	if( isset($id_quete) ){       
        	        if ($accepte=="ChoixNonFait") {
        	                $template_main .= GetMessage("ChoixQueteNonFait");
        	                $etape=1;
        	        }               	          		
			else {
			        if ($accepte=="1") {
        			        $Quete = new Quete($id_quete);
        				if ($Quete->duree_quete!=-1) {
        					$SQL = "INSERT INTO ".NOM_TABLE_PERSO_QUETE." (id_perso,id_quete,etat,debut,fin) VALUES (".$PERSO->ID.",".$id_quete.",2,". time() .", ".time()."+".$Quete->duree_quete."*60*60*24)";
        				}
        				else {
        					$SQL = "INSERT INTO ".NOM_TABLE_PERSO_QUETE." (id_perso,id_quete,etat,debut,fin) VALUES (".$PERSO->ID.",".$id_quete.",2,". time() .",-1)";
        				}			  
        		     
        				if ($db->sql_query($SQL)) {
                				$valeurs=array(); 
                				$valeurs[1] = $Quete->nom_quete;
                				$valeurs[2] = $Quete->acteurProposant->nom;
                				$valeurs[3] = $PERSO->nom;	
                			        if ( $Quete->proposantAnonyme)
                				        $messAcceptation = GetMessage("queteAnonymeAcceptee",$valeurs);
                				else $messAcceptation = GetMessage("queteAcceptee",$valeurs);
        				        $PERSO->OutPut($messAcceptation,true,true);
        				        $Quete->acteurProposant->OutPut(GetMessage("queteAccepteeProposant",$valeurs),false,true);
        				}        
        			}
        			else {
        				$valeurs=array(); 
        				$valeurs[1] = $nom_quete;
        				$messRefus = GetMessage("queteSansInteret",$valeurs);        				
        				$PERSO->OutPut($messRefus,true,true);
        			}	
			}
        	} else {
        		if( (!isset($id_quete)) ){
        			$template_main .= GetMessage("noparam");
        		} else {
        			if ($PERSO->RIP())
        				$template_main .= GetMessage("nopvs");
        			else	
        			if ($PERSO->Archive)
        				$template_main .= GetMessage("archive");
        		}
        	}
        	
        	$template_main .= "<br /><p>&nbsp;</p>";
        	
        }        
        if($etape=="1"){
               	if( isset($id_quete) ){
        		$Quete = new Quete($id_quete);
        		if ($Quete == null)
        			$template_main .= GetMessage("noparam");
        		else {                
                        	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
                        	$template_main .= " Dtails de la qute ". span($Quete->nom_quete,"quete");
                        	
                        	
                        	
                        	$detail = $Quete->description();
                        	$template_main .="<table class='details'>";
                                $template_main .="<tr><td>Propose par</td><td> ". $detail[1]. "</td></tr>";
                                $template_main .="<tr><td>Type de quete</td><td> ". $detail[2]. "</td></tr>";
                                $template_main .="<tr><td>Proposition</td><td> ". $detail[3]. "</td></tr>";
                                $template_main .="</table>";
                		$template_main .= GetMessage("queteQuestion")."<br />";
                		$template_main .="<select name='accepte'><option value='ChoixNonFait'>&nbsp;</option><option value='0'>Non</option><option value='1'>Oui</option></select>";
                		$template_main .= "<br />".BOUTON_ENVOYER;
                        	$template_main .= "<input type='hidden' name='etape' value='2' />";
                        	$template_main .= "<input type='hidden' name='id_quete' value='".$id_quete."' />";
                        	$template_main .= "<input type='hidden' name='nom_quete' value='".$Quete->nom_quete."' />";
                        	$template_main .= "</form></div>";
                        }	
        	} else {
        		if( (!isset($id_quete)) ){
        			$template_main .= GetMessage("noparam");
        		} else {
        			if ($PERSO->RIP())
        				$template_main .= GetMessage("nopvs");
        			else	
        			if ($PERSO->Archive)
        				$template_main .= GetMessage("archive");
        		}
        	}        	
        } 
        

}

if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>