<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: fctAdminGame.php,v $
*/

/**
Fichier regroupant des fonctions utilisées a la fois dans admin et dans game
.\file
$Revision: 1.2 $
$Date: 2010/01/24 19:33:11 $

*/

require_once("../include/extension.inc");

function annulerQuete($id_perso, $id_persoquete) {
	global $template_main;
	global $phpExtJeu;
	global $db;
	$PJ = new Joueur($id_perso,false,false,false,false,false,false,true);
	$Quete=null;
	$compteur= $PJ->Quetes;
	$i=0;
	$trouve=false;
	while($i <= $compteur && (!$trouve)) {
		if ($PJ->Quetes[$i]->id_persoquete==$id_persoquete) {
			$Quete= $PJ->Quetes[$i];			
			$trouve=true;
		}		
		else
		 $i++;	
	}
	if($Quete!=null) {
		$valeurs=array();
		$valeurs[1]=$Quete->acteurProposant->nom;
		$valeurs[2]=$Quete->nom_quete;
		
		if ($Quete->proposantAnonyme)
		        $mess = GetMessage("queteAnonymeAnnulee",$valeurs);
                else $mess= GetMessage("queteAnnulee",$valeurs);		        
		$PJ->OutPut($mess,false,true);	
        }
 	else $template_main .= GetMessage("noparam");	       
}        


function reussirQuete($id_perso, $id_persoquete) {
	global $template_main;
	global $phpExtJeu;
	global $db;
	
	$PJ = new Joueur($id_perso,false,false,false,false,false,false,true);
	$Quete=null;
	$compteur= $PJ->Quetes;
	$i=0;
	$trouve=false;
	while($i <= $compteur && (!$trouve)) {
		if ($PJ->Quetes[$i]->id_persoquete==$id_persoquete) {
			$Quete= $PJ->Quetes[$i];			
			$trouve=true;
		}		
		else
		 $i++;	
	}
	if($Quete!=null) {
		$valeurs=array();
		$valeurs2=array();
		$valeurs[1]=$Quete->nom_quete;
		$valeurs[2]=$Quete->acteurProposant->nom;		
		$valeurs2[1]=$PJ->recevoirRecompensesQuete($Quete);
		
                if ($Quete->proposantAnonyme)
		        $mess = GetMessage("queteAnonymeValidee",$valeurs);
                else $mess= GetMessage("queteValidee",$valeurs);				
		
		$PJ->OutPut($mess. " ". GetMessage("queteRecompenses",$valeurs2),false,true);
	}	
	else $template_main .= GetMessage("noparam");	
}	

function recoitNouvelleQuete($id_perso, $id_persoquete) {
	global $template_main;
	global $phpExtJeu;
	$PJ = new Joueur($id_perso,false,false,false,false,false,false,true);
	$Quete=null;
	$compteur= $PJ->Quetes;
	$i=0;
	$trouve=false;
	while($i <= $compteur && (!$trouve)) {
		if ($PJ->Quetes[$i]->id_persoquete==$id_persoquete) {
			$Quete= $PJ->Quetes[$i];			
			$trouve=true;
		}		
		else
		 $i++;	
	}
	if($Quete!=null) {
		$valeurs=array();
		$valeurs[1]=$Quete->acteurProposant->nom;
		$valeurs[2]=$Quete->texteProposition;
		$valeurs[5]=$Quete->duree_quete;
		if ($Quete->refusPossible==0){
		        if ($Quete->proposantAnonyme)
		                $message=GetMessage("queteAnonymeProposee",$valeurs);
			else $message=GetMessage("queteImposee",$valeurs);
		}	
		else 	{
		        if ($Quete->proposantAnonyme)
		                $message=GetMessage("queteAnonymeProposee",$valeurs);
		        else $message=GetMessage("queteProposee",$valeurs);
		}        
		if ($Quete->duree_quete!=-1)
			$message.=GetMessage("queteLimitee",$valeurs);
		//if ($Quete->refusPossible!==0)	
		//	$message.= "<form name='refusequete". $Quete->id_persoquete ."' action='repondreQuete.".$phpExtJeu. "' method='post'>".GetMessage("queteQuestion")."<select name='accepte'><option value='ChoixNonFait'>&nbsp;</option><option value='0'>Non</option><option value='1'>Oui</option></select><input type='hidden' name='id_persoquete' value='".$Quete->id_persoquete."' /><input type='submit' value='Repondre' /></form>";
			
		$PJ->OutPut($message,false,true);	
		
	}	
	else $template_main .= GetMessage("noparam");	
}	

//$temps indique si c'est le temps qui est depasse ou non
function EchecQuete($id_perso, $id_persoquete, $temps) {
	global $template_main;
	global $phpExtJeu;
	$PJ = new Joueur($id_perso,false,false,false,false,false,false,true);
	$Quete=null;
	$compteur= $PJ->Quetes;
	$i=0;
	$trouve=false;
	while($i <= $compteur && (!$trouve)) {
		if ($PJ->Quetes[$i]->id_persoquete==$id_persoquete) {
			$Quete= $PJ->Quetes[$i];			
			$trouve=true;
		}		
		else
		 $i++;	
	}
	if($Quete!=null) {
		$valeurs=array();
		$valeurs[1]=$Quete->acteurProposant->nom;
		$valeurs[2]=$Quete->nom_quete;
		if ($temps) {
		        $valeurs[3]=$Quete->fin;
			if ($Quete->proposantAnonyme)
			        $message=GetMessage("queteAnonymeEchecTemps",$valeurs);
			else $message=GetMessage("queteEchecTemps",$valeurs);        
		}	
		else {
		        $valeurs[3]="";
			if ($Quete->proposantAnonyme)
			        $message=GetMessage("queteAnonymeEchec",$valeurs);
			else $message=GetMessage("queteEchec",$valeurs);
		}	

		$PJ->recevoirPunitionsQuete($Quete);
		$PJ->OutPut($message,false,true);	
	}	
	else $template_main .= GetMessage("noparam");	
}	

        function ModifierQuetePJ_Etape2($id_cible ) {
                global $db;
                global $template_main;
                global $del;
                global $etat;
                global $duree;
                global $chaine;
                
		$result=true;
		if(isset($del)){
			$totodel = array_keys($del);
			$tatadel = array_values($del);
			$SQL = "DELETE FROM ".NOM_TABLE_PERSO_QUETE." WHERE id_perso = '".$id_cible."' AND (";
			$nb_del=count($del);
			for($i=0;$i<$nb_del&&$result;$i++){
				if($tatadel[$i] == "on"){
					$SQL.= " id_persoquete = '".$totodel[$i]."' OR";
				}
			}
			$SQL = substr($SQL,0,strlen($SQL)-2).")";;
			$result=$db->sql_query($SQL);
			
		}
		
		if(isset($etat) && $result){
			$toto = array_keys($etat);
			$tata = array_values($etat);
			$oldtata = array_values($old_etat);
			$nb_etats=count($etat);
			for($i=0;($i<$nb_etats) && $result;$i++){
			        //on ne fait les updates que si on n'a pas fait de del sur cet enregistrement
			        if ((!isset($del)) || (!array_key_exists ( $toto[$i], $del))) {
			                //logdate ("oldtata i" . $oldtata[$i]. " toto " . $toto[$i]." tata " . $tata[$i]);
			                if ($oldtata[$i]<>  $tata[$i]) {
        					$SQL = "UPDATE ".NOM_TABLE_PERSO_QUETE." SET etat = '".$tata[$i]."' WHERE id_perso = '".$id_cible."' AND id_persoquete = '".$toto[$i]."'";
        					$result=$db->sql_query($SQL);
        					switch($tata[$i]) {
        						case 1:   //proposee						
        						recoitNouvelleQuete($id_cible, $toto[$i]);
        						break;
        						case 2:   //acceptee
        						break;
        						case 3:   //refusee						
        						break;
        						case 4:   //Abandonnée						
        						break;
        						case 5:   //Echouée						
        						EchecQuete($id_cible, $toto[$i],false);
        						break;
        						case 6:   //Reussie en attente de validation
        						break;
        						case 7:  //Réussie (validée)
        						reussirQuete($id_cible, $toto[$i]);
        						break;
        						case 8:  //annulee par proposant					
        						AnnulerQuete($id_cible, $toto[$i]);
        						break;
        						case 9:   //Echouée temps
        						EchecQuete($id_cible, $toto[$i],true);
        						break;
        
        					}	
                                        }
				}
			}	
		}

		if(isset($duree) && $result){
			$toto = array_keys($duree);
			$tata = array_values($duree);
			$nb_duree=count($duree);
			for($i=0;($i<$nb_duree) && $result;$i++){
			        if ($tata[$i]<>"") {
				        if ($tata[$i]!=-1) 
					        $SQL = "UPDATE ".NOM_TABLE_PERSO_QUETE." SET fin = debut+".$tata[$i]."*24*60*60 WHERE id_perso = '".$id_cible."' AND id_persoquete = '".$toto[$i]."'";
					else         
					        $SQL = "UPDATE ".NOM_TABLE_PERSO_QUETE." SET fin = -1 WHERE id_perso = '".$id_cible."' AND id_persoquete = '".$toto[$i]."'";
					$result=$db->sql_query($SQL);
                                }
			}
		}


		//La on a efface et mis a jour
		if(isset($chaine)&&$result){
			$liste = explode(";",$chaine);
			for($i=0;($i<count($liste)-1)&&$result;$i++){
				$SQL = "SELECT * FROM ".NOM_TABLE_QUETE." WHERE id_quete = ".$liste[$i];
				$result = $db->sql_query($SQL);
				if($db->sql_numrows($result) > 0){
					$row = $db->sql_fetchrow($result);
					if($row['refuspossible']==1) 
						$etatInitial=1;
					else $etatInitial=2;
					if ($row['duree_quete']!=-1) {
						// version int(35)
						$SQL = "INSERT INTO ".NOM_TABLE_PERSO_QUETE." (id_perso,id_quete,etat,debut,fin) VALUES ('".$id_cible."','".$liste[$i]."',1,". time() .", ".time()."+".$row['duree_quete']."*60*60*24)";
						// version date
						//$SQL = "INSERT INTO ".NOM_TABLE_PERSO_QUETE." (id_perso,id_quete,etat,debut,fin) VALUES ('".$id_cible."','".$liste[$i]."',".$etatInitial.",curdate(),adddate( curdate(),".$row['duree_quete']." ))";
					}
					else {
						//laisse la date de fin a null
						// version date
						//$SQL = "INSERT INTO ".NOM_TABLE_PERSO_QUETE." (id_perso,id_quete,etat,debut) VALUES ('".$id_cible."','".$liste[$i]."',".$etatInitial.",curdate())";
						// version int(35)
						$SQL = "INSERT INTO ".NOM_TABLE_PERSO_QUETE." (id_perso,id_quete,etat,debut,fin) VALUES ('".$id_cible."','".$liste[$i]."',1,". time() .",-1)";
					}	
					$result=$db->sql_query($SQL);
					if ($result!==false) {
						$id_perso_quete = $db->sql_nextid();	
						recoitNouvelleQuete($id_cible, $id_perso_quete);
					}	
				}
				
			}
		}
	        return $result;
	}	

?>