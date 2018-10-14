<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: dep_lx.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.29 $
$Date: 2010/01/24 17:44:01 $

*/

require_once("../include/extension.inc");
include('../include/http_get_post.'.$phpExtJeu);
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $depl;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}
else {
	if ($PERSO->Engagement) {
		$template_main .= GetMessage("engag");
		//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
		$etape="Engag";	
	}

	else  {
	if(!isset($etape)){
		$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
		$template_main .= GetMessage("allerQuestion")."<br />";
		$radioChecked = false;
		//lieu passage	
		$SQL = "Select distinct concat(concat(concat(concat(T1.id_clef,'$sep'),T3.trigramme),'-'),T3.nom) as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(T2.trigramme,'-'),T2.nom),'   --->   '),T3.trigramme),'-'),T3.nom),' ('),".$liste_pas_actions["LieuEntrer"]."),' PAS)') as labselect FROM ".NOM_TABLE_CHEMINS." T1,".NOM_TABLE_ENTITECACHEECONNUEDE." T4,".NOM_TABLE_ENTITECACHEE." T5, ".NOM_TABLE_LIEU." T2, ".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu AND T1.id_lieu_1 = ".$PERSO->Lieu->ID." AND (T1.type = ".$liste_types_chemins["Lieu Entrer"]. " OR (T1.type = ".$liste_types_chemins["Lieu Secret"]. " AND T4.id_perso =".$PERSO->ID . " AND T4.id_entitecachee = T5.ID AND T1.id_clef=T5.ID_entite  ))";
		$var=faitSelect("id_cible_entrer",$SQL,"");
		if (!($var[0]>0)) {
			$SQL = "Select concat(concat(concat(concat(T1.id_clef,'$sep'),T3.trigramme),'-'),T3.nom) as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(T2.trigramme,'-'),T2.nom),'   --->   '),T3.trigramme),'-'),T3.nom),' ('),".$liste_pas_actions["LieuEntrer"]."),' PAS)') as labselect FROM ".NOM_TABLE_CHEMINS." T1, ".NOM_TABLE_LIEU." T2, ".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu AND T1.id_lieu_1 = ".$PERSO->Lieu->ID." AND type = ".$liste_types_chemins["Lieu Entrer"];
			$var=faitSelect("id_cible_entrer",$SQL,"");		
		}	
		if ($var[0]>0) {
			$template_main .= "<table width='70%' class='stats'><tr><td width='100%' align='left'><input checked='checked' type='radio' name='typeact' value='entrer' />".GetMessage("allerEntrer");
			$template_main .= $var[1]."</td></tr></table><br /><hr />";
			$radioChecked=true;
		}	
		//lieu distant
		$SQL = "Select concat(concat(concat(concat(T1.id_clef,'$sep'),T3.trigramme),'-'),T3.nom) as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(T2.trigramme,'-'),T2.nom),'   --->   '),T3.trigramme),'-'),T3.nom),' ('),T1.distance),' PAS)')  as labselect FROM ".NOM_TABLE_CHEMINS." T1, ".NOM_TABLE_LIEU." T2, ".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu AND T1.id_lieu_1 = ".$PERSO->Lieu->ID." AND type = ".$liste_types_chemins["Lieu Aller"];
		$var=faitSelect("id_cible_aller",$SQL,"");
		if ($var[0]>0) {
			$template_main .= "<table width='70%' class='stats'><tr><td width='100%' align='left'><input ";
			if (!$radioChecked) {
					$radioChecked=true;
					$template_main .= "checked='checked'";
			}		
			$template_main .= " type='radio' name='typeact' value='aller' />".GetMessage("allerVers");
			$template_main .= $var[1]."</td></tr></table><br /><hr />";
		}	
	
		// ouvrir la porte
		$SQL = "Select concat(concat(concat(concat(T1.id_clef,'$sep'),T3.trigramme),'-'),T3.nom) as idselect, concat(concat(concat(concat(concat(concat(T2.trigramme,'-'),T2.nom),'   --->   '),T3.trigramme),'-'),T3.nom) as labselect FROM ".NOM_TABLE_CHEMINS." T1, ".NOM_TABLE_LIEU." T2, ".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu AND T1.id_lieu_1 = ".$PERSO->Lieu->ID." AND type = ".$liste_types_chemins["Lieu Passage"];
		$var= faitSelect("id_cible_porte",$SQL,"");
		if ($var[0]>0) {
			$template_main .= "<table width='70%' class='stats'><tr><td width='100%' align='left'><input ";
			if (!$radioChecked) {
					$radioChecked=true;
					$template_main .= "checked='checked'";
			}		
			$template_main .= " type='radio' name='typeact' value='porte' />". GetMessage("allerPorte");
			$template_main .= $var[1]."</td></tr></table><br />";
			//$SQL = "SELECT T1.id_clef as idselect, T2.nom as labselect FROM ".NOM_TABLE_PERSOOBJET." T1, ".NOM_TABLE_OBJET." T2 WHERE T1.id_perso = ".$PERSO->ID." AND T1.id_objet = T2.id_objet AND T2.sous_type = 'Clef' ORDER BY T2.nom";
			$SQL =$PERSO->listeObjets(array('Divers'),'Clef');
			$var=faitSelect("id_clef",$SQL,"");
			if ($var[0]>0) {
				$template_main .= "<input type='radio' name='choix' value='clef' />". GetMessage("allerClef");
				$template_main .= $var[1];
				$template_main .= "<br />";
			}	
			//$SQL = "SELECT T1.id_clef as idselect, T2.nom as labselect FROM ".NOM_TABLE_PERSOOBJET." T1, ".NOM_TABLE_OBJET." T2 WHERE T1.id_perso = ".$PERSO->ID." AND T1.id_objet = T2.id_objet AND T2.sous_type = 'passe Partout' ORDER BY T2.nom";
			$SQL =$PERSO->listeObjets(array('Divers'),'passe Partout');
			$var=faitSelect("id_passe",$SQL,"");
			if ($var[0]>0) {
				$template_main .= "<input type='radio' name='choix' value='passe' /> ".GetMessage("allerPassePartout");
				$template_main .= $var[1];
				$template_main .= "<br />";
			}	
			$template_main .= "<input type='radio' name='choix' value='crochetage' checked='checked' />".GetMessage("allerCrocheter")."<br />";
		
	
			$SQL=$PERSO->listePJsDuLieuDuPerso(1, false, true,1);
			$result = $db->sql_query($SQL);
	
			if ($db->sql_numrows($result)>0) {
				$template_main .= "<br />".GetMessage("tenirPorte");
				while(	$row = $db->sql_fetchrow($result)){
					$template_main .= "<input type='checkbox' name='pj[".$row["idselect"]."]' />".span($row["labselect"],"pj").", ";
				}
			}
			$template_main .= "<hr /><br />";		
		}
		
		// escalader
		$SQL = "Select concat(concat(concat(concat(T1.id_clef,'$sep'),T3.trigramme),'-'),T3.nom) as idselect, concat(concat(concat(concat(concat(concat(T2.trigramme,'-'),T2.nom),'   --->   '),T3.trigramme),'-'),T3.nom) as labselect FROM ".NOM_TABLE_CHEMINS." T1, ".NOM_TABLE_LIEU." T2, ".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu AND T1.id_lieu_1 = ".$PERSO->Lieu->ID." AND type = ".$liste_types_chemins["Lieu Escalader"];
		$var=faitSelect("id_cible_escalader",$SQL,"");
		if ($var[0]>0) {
			$template_main .= "<table width='70%' class='stats'><tr><td width='100%' align='left'><input ";
			if (!$radioChecked) {
				$radioChecked=true;
				$template_main .= "checked='checked'";
			}		
			$template_main .= " type='radio' name='typeact' value='escalader' />".GetMessage("allerEscalader");
			$template_main .= $var[1]."</td></tr></table><br /><hr />";
		}
			
		// nager	
		$SQL = "Select concat(concat(concat(concat(T1.id_clef,'$sep'),T3.trigramme),'-'),T3.nom) as idselect, concat(concat(concat(concat(concat(concat(T2.trigramme,'-'),T2.nom),'   --->   '),T3.trigramme),'-'),T3.nom) as labselect FROM ".NOM_TABLE_CHEMINS." T1, ".NOM_TABLE_LIEU." T2, ".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu AND T1.id_lieu_1 = ".$PERSO->Lieu->ID." AND type = ".$liste_types_chemins["Lieu Nager"];
		$var= faitSelect("id_cible_nager",$SQL,"");
		if ($var[0]>0) {
			$template_main .= "<table width='70%' class='stats'><tr><td width='100%' align='left'><input ";
			if (!$radioChecked) {
				$radioChecked=true;
				$template_main .= "checked='checked'";
			}		
			$template_main .= " type='radio' name='typeact' value='nager' />".GetMessage("allerNager");
			$template_main .= $var[1]."</td></tr></table><br /><hr />";
		}	
	
		$SQL = "Select concat(concat(concat(concat(T1.id_clef,'$sep'),T3.trigramme),'-'),T3.nom) as idselect, concat(concat(concat(concat(concat(concat(T2.trigramme,'-'),T2.nom),'   --->   '),T3.trigramme),'-'),T3.nom) as labselect FROM ".NOM_TABLE_CHEMINS." T1, ".NOM_TABLE_LIEU." T2, ".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu AND T1.id_lieu_1 = ".$PERSO->Lieu->ID." AND type = ".$liste_types_chemins["Lieu Guilde"];
		$var= faitSelect("id_cible_guilde",$SQL,"");
		if ($var[0]>0) {
			$template_main .= "<table width='70%' class='stats'><tr><td width='100%' align='left'><input ";
			if (!$radioChecked) {
				$radioChecked=true;
				$template_main .= "checked='checked'";
			}		
	
			$template_main .= " type='radio' name='typeact' value='guilde' />".GetMessage("allerProtege");
			$template_main .= $var[1]."</td></tr></table>";
			$template_main .= "<br />".GetMessage("questionMotPasse");
			$template_main .= "<input type='text' size='25' name='pass' /><br /><hr />";
		}	
	
		$SQL = "Select concat(concat(concat(concat(T1.id_clef,'$sep'),T3.trigramme),'-'),T3.nom) as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(T2.trigramme,'-'),T2.nom),'   --->   '),T3.trigramme),'-'),T3.nom), ' cote '),T1.difficulte),' PO' ) as labselect FROM ".NOM_TABLE_CHEMINS." T1, ".NOM_TABLE_LIEU." T2, ".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu AND T1.id_lieu_1 = ".$PERSO->Lieu->ID." AND type = ".$liste_types_chemins["Lieu Peage"];
		$var= faitSelect("id_cible_peage",$SQL,"");
		if ($var[0]>0) {
			$template_main .= "<table width='70%' class='stats'><tr><td width='100%' align='left'><input ";
			if (!$radioChecked) {
				$radioChecked=true;
				$template_main .= "checked='checked'";
			}		
	
			$template_main .= " type='radio' name='typeact' value='peage' /> ".GetMessage("allerVIP");
			$template_main .= $var[1]."</td></tr></table>";
			$template_main .= "<br /><hr />";
		}	
	
		
		if ($radioChecked) {
			
			if ($PERSO->Groupe!="") {
				$groupePJ=new Groupe ($PERSO->Groupe,true);
				$pjs =$groupePJ->Persos;
				$existPersoGroupe=false;
				$i=0;
				while ($i<count($pjs) && ($existPersoGroupe==false)) {
					if (($pjs[$i]->Lieu->ID ==$PERSO->Lieu->ID) && ($pjs[$i]->ID <>$PERSO->ID) && ($pjs[$i]->dissimule==false) && $pjs[$i]->Archive==false && $pjs[$i]->RIP()==false){
						$existPersoGroupe=true;
					}	
					else $i++;	
				}	
				if ($existPersoGroupe)
					$template_main .= "<br /> ".GetMessage("allerGroupe") ."<input type='checkbox' name='groupe' value='".$PERSO->Groupe ."' checked='checked' />";
			}		
			//else 	$template_main .= "<input type='hidden' name='groupe' value='0' />";
			if ($PERSO->dissimule) {
				$template_main .= GetMessage("allerCache")."<input type='checkbox' name='restercache' />";
			}
			else $template_main .= "<input type='hidden' name='restercache' value='0' />";
			$template_main .= "<br />".BOUTON_ENVOYER;
		} else $template_main .= GetMessage("allerImpossible")." <br />";
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form></div>";
		$etape=0;
	} 
	
	if($etape=="1"){
		
		if (!isset ($groupe)) {
			$PJS[0]=$PERSO;
			$valeurs2[0]="";
		}
		else {
			$groupePJ=new Groupe ($groupe,true);
			$PJS= $groupePJ->Persos;
			$valeurs2[0]=$PERSO->nom;
		}	
	        logdate("typeact" . $typeact  );
		if ($typeact=='entrer')  
				$id_cible=$id_cible_entrer;
		else 	if ($typeact=='aller')  
				$id_cible=$id_cible_aller;
		else 	if ($typeact=='porte')  
				$id_cible=$id_cible_porte;
		else 	if ($typeact=='nager')  
				$id_cible=$id_cible_nager;
		else 	if ($typeact=='escalader')  
				$id_cible=$id_cible_escalader;
		else 	if ($typeact=='guilde')  
				$id_cible=$id_cible_guilde;
		else 	if ($typeact=='peage') 
				$id_cible=$id_cible_peage;
		// RAPPEL : id_cible = ID chemin et non ID de lieu d'arrivee
		if (!isset($id_cible))  {
			$template_main .= GetMessage("noparam");
		}
		else { 
			
			$pos = strpos($id_cible, $sep);
			$libelle=substr($id_cible, $pos+strlen($sep)); 
			$id_cible=substr($id_cible, 0,$pos); 
			$id_chemin=-1;
			$i=0;
			$nb_lieu_chemin=count($PERSO->Lieu->Chemins);
			while (($i<$nb_lieu_chemin) && $id_chemin==-1) {
				if($PERSO->Lieu->Chemins[$i]->ID == $id_cible)
					$id_chemin = $i;
				else $i++;	
			}	
			
			if ($id_chemin==-1) 
				 $template_main .= GetMessage("noparam");	
			else {
				$i=0;
				$id_lieu=$PERSO->Lieu->ID;
				$nbPjsGroupe=count($PJS);
				$etattrouve=true;
				// verifie que tous les membres du groupe ont le bon etattemp
				if ($PERSO->Lieu->Chemins[$id_chemin]->Arrivee->EtatTempSpecifique!=null) {
					while ($i<$nbPjsGroupe && ($etattrouve==true)){
						$etattrouve = $PJS[$i]->peutAccederLieu($PERSO->Lieu->Chemins[$id_chemin]->Arrivee);
						$i++;
					}
					if (!$etattrouve) {
						$valeurs=array();				
						$valeurs[0]= $PERSO->Lieu->Chemins[$id_chemin]->Arrivee->EtatTempSpecifique->nom;
						$template_main .= GetMessage("lieuInaccessible",$valeurs);
					}	
				}
				if ($etattrouve) {
					//vrifie que tous les membres du groupe sont au meme endroit de depart et non engags
					$i=0;
					$engage=false;
					while ($i<$nbPjsGroupe && ($id_lieu==$PERSO->Lieu->ID) && ($engage==false)){
						$id_lieu = $PJS[$i]->Lieu->ID;
						$engage = $PJS[$i]->Engagement;
						$i++;
					}	
		
					if ($i< $nbPjsGroupe) {
						if ($id_lieu<>$PERSO->Lieu->ID )
							$template_main .= GetMessage("nodepgroupe1");
						else $template_main .= GetMessage("nodepgroupe2");
					}	
					else {
						// tous les persos du groupe sont la. On peut tester les PA maintenant.
						$i=0;
						$ok=1;		
						while ($i<$nbPjsGroupe && $ok==1){
							if ($PJS[$i]->ID==$PERSO->ID && $restercache) {
								$paSuppl = $liste_pas_actions["SurcoutDeplacementDiscret"];
								$piSuppl = $liste_pis_actions["SurcoutDeplacementDiscret"];
							}	
							else {
								$paSuppl = 0;
								$piSuppl = 0;
							}
							if ((($typeact=='aller')
								&&((!($PJS[$i]->ModPA($paSuppl-$PJS[$i]->Lieu->Chemins[$id_chemin]->distance)))
								   ||(!($PJS[$i]->ModPI($piSuppl)))))
							|| (($typeact=='guilde') 
								&&((!($PJS[$i]->ModPA($paSuppl+$liste_pas_actions["LieuGuilde"])))
									||(!($PJS[$i]->ModPI($piSuppl+$liste_pis_actions["LieuGuilde"])))))
							|| ((($typeact=='entrer')||($typeact=='peage')) 
								&&((!($PJS[$i]->ModPA($paSuppl+$liste_pas_actions["LieuEntrer"]))) 
								||(!($PJS[$i]->ModPI($piSuppl+$liste_pis_actions["LieuEntrer"])))))
							|| (($typeact=='escalader') 
								&&((!($PJS[$i]->ModPA($paSuppl+$liste_pas_actions["LieuEscalader"])))  
								||(!($PJS[$i]->ModPI($piSuppl+$liste_pis_actions["LieuEscalader"])))))
							|| (($typeact=='nager') 
								&&((!($PJS[$i]->ModPA($paSuppl+$liste_pas_actions["LieuNager"])))
								||(!($PJS[$i]->ModPI($piSuppl+$liste_pis_actions["LieuNager"])))))
							|| (($typeact=='porte') 
								&&((!($PJS[$i]->ModPA($paSuppl+$liste_pas_actions["LieuPassage"]))) 
								||(!($PJS[$i]->ModPI($piSuppl+$liste_pis_actions["LieuPassage"])))))
							) {
								$ok=0;
							}	
							else $i++;		
						}	
			
						if( $ok==0 ) {
							if (!isset($groupe)) {
								if ($PERSO->RIP())
									$template_main .= GetMessage("nopvs");
								else	
								if ($PERSO->Archive)
									$template_main .= GetMessage("archive");
								else	
								$template_main .= GetMessage("nopas");
							}	
							else $template_main .= GetMessage("nodepgroupe1");
						}	
						else {
							$valeurs[0]=$PERSO->nom;
							$valeurs[1]=$PERSO->Lieu->Chemins[$id_chemin]->Arrivee->nom;
							$valeurs[2]="Personne";
							if ($typeact=='guilde') 
								$valeurs[3]=$pass;
							else $valeurs[3]='';
							$lieuDepart=$PERSO->Lieu->ID;
							$lieuArrivee=$PERSO->Lieu->Chemins[$id_chemin]->Arrivee->ID;
							$apasser=false;
					
							if ($typeact=='entrer') 
								$apasser=true;
							else if ($typeact=='peage') {
								$apasser = reussite_peage($PJS, $PERSO->Lieu->Chemins[$id_chemin]->difficulte);
							}
							else if ($typeact=='aller') {
								$apasser=true;							
								$reussite = reussite_aller($PJS,$PERSO->Lieu->Chemins[$id_chemin]->difficulte);
						            	$i=0;
						            	while ($i<$nbPjsGroupe){
									if($reussite < 0){
										if($reussite < 5){$degats=4;}else{$degats=2;}
										$valeurs[3]=$degats;
										$PJS[$i]->ModPV(-$degats);
									}
						                        $i++;
						             }           
							}
							else if ($typeact=='guilde') {
							        traceAction("MotPasse",$PERSO , $PERSO->Lieu->Chemins[$id_chemin]->Arrivee->nom);
								if(ConvertAsHTML($pass) == $PERSO->Lieu->Chemins[$id_chemin]->pass)
									$apasser=true;
					                }
							else if ($typeact=='escalader') {
								//Testons la difficult&eacute; de l'op&eacute;ration
								$ok=1;
								$i=0;
								while ($i<$nbPjsGroupe && $ok==1){
									$reussite = reussite_escalade($PJS[$i],$id_chemin);
									if($reussite <= 0)
										$ok=0;
									else 
										$i++;
								}
								$apasser=$ok;
							}
									
							else if ($typeact=='nager') {
								$ok=1;
								$i=0;
								while (($i<$nbPjsGroupe) && ($ok==1)){	
									//Testons la difficult&eacute; de l'op&eacute;ration
									$reussite = reussite_nage($PJS[$i], $id_chemin);
									if($reussite <= 0)
										$ok=0;
									else 
										$i++;
								}
								$apasser=$ok;
							}			
							else if ($typeact=='porte') {
							        logdate("choix" . $choix  );
								//Faut traiter les 3 cas de figure, clef, passe et crochetage.
								if($choix == 'clef'){
									//On a une clef, verifions la.
									$Objet = null;
									$nbObj = count($PERSO->Objets);
									$i=0;
									while(($i<$nbObj) && ($Objet == null)) {
										if($PERSO->Objets[$i]->id_clef == $id_clef)
											$Objet = $PERSO->Objets[$i];
										else $i++;	
									}	
	
									if ($Objet==null) 
										$apasser=false;	
									else									
									if( (($Objet->Degats[0] == $PERSO->Lieu->Chemins[$id_chemin]->Depart->ID) &&  ($Objet->Degats[1] == $PERSO->Lieu->Chemins[$id_chemin]->Arrivee->ID)) || (($Objet->Degats[1] == $PERSO->Lieu->Chemins[$id_chemin]->Depart->ID) &&  ($Objet->Degats[0] == $PERSO->Lieu->Chemins[$id_chemin]->Arrivee->ID)) ){
											//La clef est bonne
											//$PERSO->DeplaceLieu($PERSO->Lieu->Chemins[$id_chemin]->Arrivee->ID);
											$apasser = true;
											$valeurs[3] = $Objet->nom;
									}
								}
					
								else if($choix == 'crochetage'){
								        traceAction("Crocheter",$PERSO , $PERSO->Lieu->Chemins[$id_chemin]->Arrivee->nom);
									//Testons la durete de la porte
									$reussite = reussite_crochetage($PERSO,$id_chemin);
									if($reussite > 0)
										$apasser = true;
								}
					
								else if($choix == 'passe'){
									//On a un pass, chopons le.
									$Objet = null;
									$i=0;
									$nb_objets=count($PERSO->Objets);
									while(($i<$nb_objets) && $Objet == null){
										if($PERSO->Objets[$i]->id_clef == $id_passe)
											$Objet = $PERSO->Objets[$i];
										else $i++;
									}
									if ($Objet==null) 
										$apasser=false;				
									else {
										$valeurs[3] = $Objet->nom;
										$reussite = reussite_passepartout($PERSO, $Objet,$id_chemin);
										if($reussite > 0){
											$Objet->Abime(1);
											$apasser = true;
										} else {
											$Objet->Abime(2);
										}
									}												
								}
							}
							if(($apasser) && (isset($pj)) && ($typeact=='porte')) {
								$SQL = "SELECT * FROM ".NOM_TABLE_OBJET." WHERE sous_type = 'Clef' AND ((degats_min = '".$lieuDepart."' AND degats_max = '".$lieuArrivee."') OR (degats_max = '".$lieuDepart."' AND degats_min = '".$lieuArrivee."'))";
								$result = $db->sql_query($SQL);
								
								if($db->sql_numrows($result) > 0){
									$valeurs[2]="";
									$row = $db->sql_fetchrow($result);
									$id_objet = $row["id_objet"];
									$toto = array_keys($pj);
									$SQL = "INSERT INTO ".NOM_TABLE_PERSOOBJET." (id_perso,id_objet,temporaire) VALUES ";
									$j=0;
									$nbPJsTientLaPorte=count($pj);
									for($i=0;$i<$nbPJsTientLaPorte;$i++){
										$pjtemp = new Joueur($toto[$i],false,false,false,false,false,false);
										// ne reprend pas les persos du groupe du joueur si on est dans un mouvement de groupe
										if (! isset($groupe) || $pjtemp->Groupe <> $PERSO->Groupe) {
											if ($j>0) {$SQL .=', '; $valeurs[2] .= ", ";}		
											$SQL .= "('".$toto[$i]."','".$id_objet."','1')";				
											$valeurs[2] .= $pjtemp->nom;
											$j++;
											$pjtemp->OutPut(GetMessage("deplacer_lp_portetenue",$valeurs),false,true);
										}	
									}
									$db->sql_query($SQL);
								} else 
									$template_main .= GetMessage("prbPorte");
								
							}
							if($apasser){						        
								$i=0;
								while ($i<$nbPjsGroupe){
									$sortir = $PJS[$i]->etreCache(0);
									if ($typeact!='guilde' && !(  $typeact=='porte' && $choix == 'crochetage'))
									        traceAction("SeDeplacer",$PERSO , $PERSO->Lieu->Chemins[$id_chemin]->Arrivee->nom);
									//si on fait l'action
									if ($PERSO->ID == $PJS[$i]->ID) {
										//reactualise les PA, PO, XP et PV pour affichage dans le menu
										$PERSO->PA = $PJS[$i]->PA;							
										$PERSO->PV = $PJS[$i]->PV;
										$PERSO->PO = $PJS[$i]->PO;
										$PERSO->XP = $PJS[$i]->XP;
										if ($typeact=='peage') {
											$resultat ="";
											if (isset($groupe))
												$resultat = GetMessage("deplacer_groupe", $valeurs2);
											$resultat .=GetMessage("deplacer_lpeage_01",$valeurs);
											$PERSO->OutPut($resultat,true);
										}	
										elseif ($typeact=='aller') {
											$resultat ="";
											if (isset($groupe))
												$resultat = GetMessage("deplacer_groupe", $valeurs2);
											if ($reussite >0)
												$resultat .=GetMessage("deplacer_la_01",$valeurs);
											else {
												$resultat .=GetMessage("deplacer_la_02",$valeurs);
												if ($PERSO->RIP())
													$resultat .= GetMessage("deplacer_la_02mort");
											}	
		
											$PERSO->OutPut($resultat,true);
										}	
										else if ($typeact=='guilde')  {									        
											if (isset($groupe)) 
												if ($valeurs[2]<>"Personne")
													$PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lg_01b",$valeurs),true); 
												else $PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lg_01",$valeurs),true); 	
										      else if ($valeurs[2]<>"Personne")
										      		$PERSO->OutPut(GetMessage("deplacer_lg_01b",$valeurs),true); 
										      	   else $PERSO->OutPut(GetMessage("deplacer_lg_01",$valeurs),true); 	
										}	
										else if ($typeact=='entrer')  {
											if (isset($groupe))
												$PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_le",$valeurs),true);
											else 
												$PERSO->OutPut(GetMessage("deplacer_le",$valeurs),true);	
										}
										else if ($typeact=='nager')  {
											if (isset($groupe)) 
												if ($valeurs[2]<>"Personne")
													$PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_ln_03",$valeurs),true); 
												else $PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_ln_03b",$valeurs),true); 	
										      else if ($valeurs[2]<>"Personne")
										      		$PERSO->OutPut(GetMessage("deplacer_ln_03",$valeurs),true); 
										      	   else $PERSO->OutPut(GetMessage("deplacer_ln_03b",$valeurs),true); 	
										}	
										else if ($typeact=='escalader')  {
											if (isset($groupe)) 
												if ($valeurs[2]<>"Personne")
													$PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_les_03",$valeurs),true); 
												else $PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_les_03b",$valeurs),true); 	
										      else if ($valeurs[2]<>"Personne")
										      		$PERSO->OutPut(GetMessage("deplacer_les_03",$valeurs),true); 
										      	   else $PERSO->OutPut(GetMessage("deplacer_les_03b",$valeurs),true); 	
										}	
										else if ($typeact=='porte')  {
											switch($choix){
												case 'clef' : if (isset($groupe)) 
														if ($valeurs[2]<>"Personne")
															$PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lp_01",$valeurs),true); 
														else $PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lp_01b",$valeurs),true); 	
													      else if ($valeurs[2]<>"Personne")
													      		$PERSO->OutPut(GetMessage("deplacer_lp_01",$valeurs),true); 
													      	   else $PERSO->OutPut(GetMessage("deplacer_lp_01b",$valeurs),true); 	
												break;
												case 'passe' : if (isset($groupe)) 
															if ($valeurs[2]<>"Personne")
																$PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lp_02",$valeurs),true); 
															else $PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lp_02b",$valeurs),true); 	
														else if ($valeurs[2]<>"Personne")
															$PERSO->OutPut(GetMessage("deplacer_lp_02",$valeurs),true); 
														     else $PERSO->OutPut(GetMessage("deplacer_lp_02b",$valeurs),true); 	
												break;
												case 'crochetage' : 											               
												                if (isset($groupe)) 
															if ($valeurs[2]<>"Personne")	
																$PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lp_03",$valeurs),true); 
															else $PERSO->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lp_03b",$valeurs),true); 	
														else if ($valeurs[2]<>"Personne")
															$PERSO->OutPut(GetMessage("deplacer_lp_03",$valeurs),true); 
														     else $PERSO->OutPut(GetMessage("deplacer_lp_03b",$valeurs),true); 
												break;
											}
										}
							            		$retour= $PERSO->DeplaceLieu($lieuArrivee);                        
		            							$valeurs[6] = $retour["ajouter"];
										$valeurs[7] = $retour["retirer"];
										if ($retour["ajouter"]!="rien")
										    $PERSO->OutPut(GetMessage("etattemplieu1",$valeurs),true);
										if ($retour["retirer"]!="rien")
										    $PERSO->OutPut(GetMessage("etattemplieu2",$valeurs),true);
										if ($restercache)
											secacher($PERSO);
		
									}	
									else 	{
							            		$retour=$PJS[$i]->DeplaceLieu($lieuArrivee);                        
										if ($typeact=='aller')  
											if ($reussite >0)
												$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_la_01",$valeurs),false);
											else {
												$resultat = GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_la_02",$valeurs);
												$resultat .= GetMessage("deplacer_la_02groupeblesse");
												if ($PERSO->RIP())
													$resultat .= GetMessage("deplacer_la_02groupemort");
												$PJS[$i]->OutPut($resultat,false);
											}	
							            		else if ($typeact=='peage')  
											$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lpeage_01",$valeurs),false);
							            		else if ($typeact=='entrer')  
											$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_le",$valeurs),false);
										else if ($typeact=='guilde')  
											if ($valeurs[2]<>"Personne")	
												$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lg_01",$valeurs),false); 
											else 	$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lg_03",$valeurs),false); 
										else if ($typeact=='nager')  
											if ($valeurs[2]<>"Personne")	
												$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_ln_01",$valeurs),false); 
											else 	$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_ln_01b",$valeurs),false); 
										else if ($typeact=='escalader')  
											if ($valeurs[2]<>"Personne")	
												$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_les_01",$valeurs),false); 
											else 	$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_les_01b",$valeurs),false); 
										else if ($typeact=='porte')  {
											switch($choix){
												case 'clef' : if ($valeurs[2]<>"Personne")	
															$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lp_01",$valeurs),false); 
														else 	$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lp_01b",$valeurs),false); 
														break;
												case 'passe' : if ($valeurs[2]<>"Personne")
															$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lp_02",$valeurs),false); 
														else $PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lp_02b",$valeurs),false); 
														break;
												case 'crochetage' : if ($valeurs[2]<>"Personne")
															$PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lp_03",$valeurs),false); 
														    else $PJS[$i]->OutPut(GetMessage("deplacer_groupe", $valeurs2) .GetMessage("deplacer_lp_03b",$valeurs),false); 
														break;
											}
										}
										$valeurs[6] = $retour["ajouter"];
										$valeurs[7] = $retour["retirer"];
										if ($retour["ajouter"]!="rien")
										    $PJS[$i]->OutPut(GetMessage("etattemplieu1",$valeurs),true);
										if ($retour["retirer"]!="rien")
										    $PJS[$i]->OutPut(GetMessage("etattemplieu2",$valeurs),true);  										
									}		
									$i++;
								}
	
	                                                        $i=0;
	                                                        if ($PJS[$i]->Lieu->apparitionMonstre)
								        while ($i<$nbPjsGroupe){
								                apparitionMonstre($PJS[$i]);
								                $i++;
								        }        
								//dans tous les cas, on ne fait l'action surprise que sur le perso en cours 
								//sinon, le pnj qui a une action surprise peut lancer plein d'actions simultanees
								//on fat l'action surprise apres l'apparition pour que les monstres agissent
				        		        action_surprise($PERSO);
							}
							else 	//echecs
								if ($typeact=='guilde')
									$PERSO->OutPut(GetMessage("deplacer_lg_02",$valeurs),true);
								else if ($typeact=='peage')
									$PERSO->OutPut(GetMessage("deplacer_lpeage_02",$valeurs),true);
								else if ($typeact=='porte')
									$PERSO->OutPut(GetMessage("deplacer_lp_04",$valeurs),true);
								else if ($typeact=='escalader')
									$PERSO->OutPut(GetMessage("deplacer_les_04",$valeurs),true);
								else if ($typeact=='nager')
									$PERSO->OutPut(GetMessage("deplacer_ln_04",$valeurs),true);
						}			
					}
				}	
			}
		}
		$template_main .= "<br /><p>&nbsp;</p>";
	    }
      }
}
if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>