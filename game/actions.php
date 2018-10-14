<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: actions.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.52 $
$Date: 2010/05/13 21:38:59 $

*/

require_once("../include/extension.inc");
if(!defined("ACTIONS.PHP") ) {
	Define("ACTIONS.PHP",	0);	
	/** 
	    \brief riposteGroupe declenche lorsque $ADVERSAIRE a recu une action de $ATTAQUANT. Dtermine si $ADVERSAIRE fait partie d'un groupe. Dans ce cas, tous les membres du groupes vont reagir, sinon c'est le PJ seul qui va reagir
	    \param[in] $ADVERSAIRE Le PJ qui vient de se faire aggress
	    \param[in] $ATTAQUANT celui qui vient d'aggress
	    \param[in] $Attaque1type indique le type de cette aggression (dans le cas d'un soin par exemple, on ne vas pas fracasser la tete de celui qui nous l'a fait. Quoique :) )
	    \param[in] $Attaque1anonyme indique si l'agreession etait anonyme ou non
	Si on fait une action sur un membre d'un groupe, et que cette action n'est pas anonyme
	Tous les membres du groupes peuvent ragir sauf si c'est une bataille entre 2 groupes diffrents
	On supprime le riposte de groupe si c'est une bataille entre 2 groupes (ou entre 2 membres d'un meme groupe)
	pour que le groupe defenseur n'ait pas autant de ripostes que de membres a chaque attaque d'un membre du groupe attaquant
	*/
	function riposteGroupe ( $ADVERSAIRE,$ATTAQUANT,$Attaque1type, $Attaque1anonyme ) {	
		if(defined("RIPOSTE_AUTO") && RIPOSTE_AUTO==1) {
			if (defined("RIPOSTE_GROUPEE") && RIPOSTE_GROUPEE==1 && $ADVERSAIRE->Groupe!="" && ($ATTAQUANT->Groupe=="" || $ATTAQUANT->Groupe==null)) {
				$GroupeAttaqu = new Groupe($ADVERSAIRE->Groupe,true);
				$i=0;
				$nb_pnjs =count($GroupeAttaqu->Persos);
				while ($i<$nb_pnjs) {
					riposte ( $GroupeAttaqu->Persos[$i],$ATTAQUANT,$Attaque1type, $Attaque1anonyme, $ADVERSAIRE->Reaction );			
					$i++;
				}	
			}	
			else 
				riposte ( $ADVERSAIRE,$ATTAQUANT,$Attaque1type, $Attaque1anonyme );
		}			
	}

	/**
		\brief riposte automatique d'un PJ lorsqu'il a recu une aggression ou groupe de PJ lorsqu'un de ses membres a recu une aggression.
		\param $ADVERSAIRE est le PJ qui a recu une aggression (ou un membre de son groupe) de ATTAQUANT et qui riposte
		\param $ATTAQUANT est le PJ qui a commit l'aggression
		\param $Attaque1type indique le type de cette aggression (dans le cas d'un soin par exemple, on ne vas pas fracasser la tete de celui qui nous l'a fait. Quoique :) )
		\param $Attaque1anonyme indique si cette aggression tait anonyme ou non
		\param $reactiongroupe force tous les membres du groupe a reagir de la meme facon (si possible sinon, le PJ ne fait rien) 
		que la personne attaque.
		\todo les ripostes par sort de teleportation de l'aggresseur ou du magicien ne sont pas codees (Le probleme est de determiner automatiquement le lieu de teleportation)
		
	*/
	function riposte ( $ADVERSAIRE,$ATTAQUANT,$Attaque1type, $Attaque1anonyme, $reactiongroupe = "" ) {	
		global $liste_flags_lieux;
		global $liste_pas_actions;
		global $liste_pis_actions;
		global $liste_reactions;
		global $liste_comp_full;
		global $db;
		
		if ($ADVERSAIRE->RIP())
			return;
				
		if ($reactiongroupe <> "")
			$reaction =$reactiongroupe; 
		else	$reaction = $ADVERSAIRE->Reaction;
			
			$tata = array_keys($liste_reactions);							
			logDate ( "React".$reaction);				 
			if ($tata[0]== $reaction) { 
				//tenter de fuir
				if ($Attaque1type=="ATT_OK" ||$Attaque1type=="MAG_OK") {
					if ((!defined("ENGAGEMENT")) || ENGAGEMENT==0)
						proposer($ADVERSAIRE,4,0,1, GetMessage("demanderFuite"),false, $ATTAQUANT); 
					else {
						if ($ADVERSAIRE->GetNiveauComp($liste_comp_full["Force"],true) >= $ADVERSAIRE->GetNiveauComp($liste_comp_full["Intelligence"],true)) {
							if ($ADVERSAIRE->GetNiveauComp($liste_comp_full["Force"],true) >= $ADVERSAIRE->GetNiveauComp($liste_comp_full["Dexterite"],true)) 
								//force
								Desengagement($ADVERSAIRE,$ATTAQUANT, "force", true);
							else //dexte
								Desengagement($ADVERSAIRE,$ATTAQUANT, "dexte", true);
						}	
						else {
							if ($ADVERSAIRE->GetNiveauComp($liste_comp_full["Dexterite"],true) >= $ADVERSAIRE->GetNiveauComp($liste_comp_full["Intelligence"],true)) 
								Desengagement($ADVERSAIRE,$ATTAQUANT, "dexte", true);
							else 
							//$des=reussite_desengagment_ruse($ADVERSAIRE,$ADVERSAIRE);
							Desengagement($ADVERSAIRE,$ATTAQUANT, "ruse", true);
						}	
						
					}	
				}	
				return;
			}
			elseif ($tata[1]== $reaction) {
				parler ($ADVERSAIRE,"lieu",GetMessage("SOS"),array(),false);					
				//appeler a l'aide
				return;
			}
			elseif ($tata[2]== $reaction) {				
				if (! $Attaque1anonyme) {
					$arme= array();
					$nbobj_ADV =count($ADVERSAIRE->Objets); 
					for($i=0;$i<$nbobj_ADV;$i++){				
						if ($ADVERSAIRE->Objets[$i]->equipe && ($ADVERSAIRE->Objets[$i]->type=='ArmeJet' || $ADVERSAIRE->Objets[$i]->type=='ArmeMelee')) {
							array_push ($arme, $ADVERSAIRE->Objets[$i]->id_clef);
						}	
					}		
					
					if (count($arme) ==1)				
						attaquer ( $ATTAQUANT,array_pop($arme),$ADVERSAIRE,false,false,true,false,"");	
					else 	{
						attaquer ( $ATTAQUANT,array_pop($arme),$ADVERSAIRE,false,false,true,true,"MALUS_ATTAQUE1_ATTAQUESENCHAINEES");	
						attaquer ( $ATTAQUANT,array_pop($arme),$ADVERSAIRE,false,false,true,true,"MALUS_ATTAQUE2_ATTAQUESENCHAINEES");	
					}	
						
				}	
			}		
			elseif ($tata[3]== $reaction) { 
				logDate ( "dans sort");
				//sort
				$SQL = "SELECT * FROM ".NOM_TABLE_PERSOMAGIE." WHERE id_clef =".$ADVERSAIRE->SortPrefere;
				$requete=$db->sql_query($SQL);
				$row =  $db->sql_fetchrow($requete);
				$i=0;
				$nom_sort="";
				$nbSortADV = count($ADVERSAIRE->Sorts);
				while ($i<$nbSortADV && $nom_sort=="") {
					if ($ADVERSAIRE->Sorts[$i]->ID == $row["id_magie"])
						$nom_sort=$ADVERSAIRE->Sorts[$i]->nom;
						$type_sort=strtolower($ADVERSAIRE->Sorts[$i]->Soustype);
					$i++;	
				}
				logDate ( " nom sort ".$nom_sort ."/".$type_sort);
				if (! isset($type_sort))
					return;					
				logDate (  "attaque recue anonyme". $Attaque1anonyme);

				//impossible de riposter puisque on ne sait pas qui 
				if ($Attaque1anonyme && $type_sort=="attaque")
					return;
				
				if ($type_sort=="attaque")	
					magie ($ADVERSAIRE,$type_sort,$ADVERSAIRE->SortPrefere,$ATTAQUANT->ID,null,null,null,null,null,null,null, false,false,true);
				elseif ($type_sort=="soin")	
					magie ($ADVERSAIRE,$type_sort,null,null,$ADVERSAIRE->SortPrefere,$ADVERSAIRE->ID,null,null,null,null,null, false,false,true);
/*  
				elseif ($type_sort=="teleport")	
					magie ($ADVERSAIRE,$type_sort,null,null,null,null,$ADVERSAIRE->SortPrefere,$ADVERSAIRE->ID,null,null,null, false,false,true);
				elseif ($type_sort=="autoteleport")	
					magie ($ADVERSAIRE,$type_sort,null,null,null,null,null,null,null,$ADVERSAIRE->SortPrefere,$ADVERSAIRE->ID, false,false,true);			
*/					
				return;
			}				
			elseif ($tata[4]== $reaction) { 
				//Pas de reaction
			}	
			elseif ($tata[5]== $reaction) { 
				//Vol
				if (! $Attaque1anonyme) {
					voler ( $ATTAQUANT,$ADVERSAIRE,false,false,true);
				}	
			}		
}	
	
	
	function affiche_resultat($PERSO_CONNECTE,$ADVERSAIRE,$MSG_PERSO_CONNECTE,$MSG_ADVERSAIRE,$msg_spect,$date) {
		global $db;		
		if ($msg_spect!="") {
			//MODIF Hixcks : recherche des personnes presentes et conscientes
			$SQL = $PERSO_CONNECTE->listePJsDuLieuDuPerso(1, false, false,1). " AND id_perso <> '".$ADVERSAIRE->ID."'";
			$result = $db->sql_query($SQL);
						//for($i=0;$i<$db->sql_numrows($result);$i++){
			while($row =  $db->sql_fetchrow($result)) {
				$pjtemp = new Joueur($row["idselect"],false,false,false,false,false,false);
				$pjtemp->OutPut($msg_spect,false,$date);
			}	
		}
		if ($MSG_PERSO_CONNECTE!="")
			$PERSO_CONNECTE->OutPut($MSG_PERSO_CONNECTE,true,$date);
		

		if ($MSG_ADVERSAIRE!="")
			//test pour les sorts de soin ou de self teleport
			if ($PERSO_CONNECTE->ID!=$ADVERSAIRE->ID) 
				$ADVERSAIRE->OutPut($MSG_ADVERSAIRE,false,$date);
	}	



	/**
		\brief fait apparaitre les monstres dans le lieu lorsque $PERSO arrive dans ce lieu
		\param $PERSO est le PJ qui arrive dans le lieu
		
	*/	
	function apparitionMonstre($PERSO) {
	        global $db;
	       $SQLtypeMonstre="select am.*, monstre.nom from ".NOM_TABLE_APPARITION_MONSTRE." am, ". NOM_TABLE_REGISTRE ." monstre where am.id_perso = monstre.id_perso and am.nb_max_apparition>0 and monstre.pnj=2 and am.chance_apparition>0 and am.id_typelieu = ". $PERSO->Lieu->type_lieu_apparition;
	       $resulttypeMonstre = $db->sql_query($SQLtypeMonstre);
	       while($rowtypeMonstre =  $db->sql_fetchrow($resulttypeMonstre)) {
	                logdate('rowtypeMonstre ' .$rowtypeMonstre['nom']);
	                $nbMaxApparitions = $rowtypeMonstre['nb_max_apparition'];
                        if ($rowtypeMonstre['nb_max_lieu']!=-1) {
                                $SQLquantiteDejaPresente = "select count(id_perso) as c from ". NOM_TABLE_REGISTRE ." where pnj=3 and nom like '".$rowtypeMonstre['nom']."%' and id_lieu = ".$PERSO->Lieu->ID;
                                $resultquantiteDejaPresente = $db->sql_query($SQLquantiteDejaPresente);
                                $rowquantiteDejaPresente =  $db->sql_fetchrow($resultquantiteDejaPresente);
                                if ( $rowquantiteDejaPresente['c'] >= $rowtypeMonstre['nb_max_lieu'])
                                        $nbMaxApparitions = 0;
                                else $nbMaxApparitions =   min ($nbMaxApparitions,$rowtypeMonstre['nb_max_lieu'] - $rowquantiteDejaPresente['c']);      
                        }        
                        if ($nbMaxApparitions>0) {
                                $nbApparitions = calculApparitionMonstre($rowtypeMonstre['chance_apparition'], $nbMaxApparitions, $PERSO->Lieu, $PERSO);
                                if ($nbApparitions>=0) {
                                        $i=0;
                                        while ($i< $nbApparitions) {
                                                creationMonstre( $rowtypeMonstre['id_perso'], $PERSO->Lieu->ID, "PJ");
                                                //action_surprise($PERSO);
                                                $i++;
                                        }        
                                }        
                        }        
	       } 
	}        
	
	/**
		\brief calcule combien de monstres arrivent dans le lieu lorsque $PERSO arrive dans ce lieu
		\param $PERSO est le PJ qui arrive dans le lieu(on ne s'en sert pas pour le moment)
		
	*/
  	function calculApparitionMonstre($chanceApparition, $nbMaxApparitions, $Lieu, $PERSO) {
	        $nbMonstres=0;

	        if ($chanceApparition >0 && $nbMaxApparitions!=0 && $Lieu->apparitionMonstre) {
	                $degats = 1+$chanceApparition-lancede(100);
	                if ($degats>0) {
	                        $nbMonstres = min($degats * (lancede($nbMaxApparitions)),  $nbMaxApparitions);
	                }        
	        }
	        logdate("calculApparitionMonstre -> degats ". $degats ." nbMonstres " .$nbMonstres);
	        return $nbMonstres;
	}        
	

	/**
		\brief calcule si le PNJ voit (entend...) le PJ arriver dans le lieu et reagit
		\param $pourcentageReactionPNJ pourcentage de la fiche du PNJ
		
	*/
	function calculReactionArriveePerso($pourcentageReactionPNJ) {
                $degats = 1+$pourcentageReactionPNJ-lancede(100);
	        return $degats;
	}    	
	
	
	
	function action_surprise($PERSO) {
		global $liste_pas_actions;
		global $liste_pis_actions;
		global $liste_stype_sorts;
		global $liste_flags_lieux;
		global $db;
		global $dbmsJeu;
		//MODIF Hixcks : recherche des personnes presentes et conscientes qui agissent par defaut

		if ($dbmsJeu!="mysql" || $db->versionServerDiscriminante()>=4.1) {
			//requete a utiliser en sql 4.1 et + pour mysql ou pour les autres bases
			$SQL = "SELECT id_perso,relation,actionsurprise,pourcentage_reaction FROM ".NOM_TABLE_REGISTRE." WHERE PV >0  AND id_lieu = '".$PERSO->Lieu->ID."' AND id_perso <> '".$PERSO->ID."' and 
	                        NOT EXISTS (SELECT 1 FROM ".NOM_TABLE_ENGAGEMENT." WHERE id_adversaire = ".NOM_TABLE_REGISTRE.".id_perso or id_perso = ".NOM_TABLE_REGISTRE.".id_perso) ";
		}
		else  {
			$SQL = "SELECT id_perso,relation,actionsurprise,pourcentage_reaction FROM ".NOM_TABLE_REGISTRE." LEFT JOIN ".NOM_TABLE_ENGAGEMENT." ON id_adversaire = ".NOM_TABLE_REGISTRE.".id_perso or ".NOM_TABLE_ENGAGEMENT.".id_perso = ".NOM_TABLE_REGISTRE.".id_perso WHERE PV >0  AND id_lieu = '".$PERSO->Lieu->ID."' AND id_perso <> '".$PERSO->ID."' and 
	                         (id_adversaire is null and ".NOM_TABLE_ENGAGEMENT.".id_perso is null)";
		}
		$result = $db->sql_query($SQL);
		if ($db->sql_numrows($result)>0) {			
			while($row =  $db->sql_fetchrow($result)) {
			        if (calculReactionArriveePerso($row['pourcentage_reaction']) >0) {
				//ennemi attaque avec arme
				if ($PERSO->Lieu->permet($liste_flags_lieux["Attaquer"]) && (!$PERSO->RIP()) && $row["relation"]==4 && $row["actionsurprise"]==1) {
					$pjtemp = new Joueur($row["id_perso"],true,true,true,true,true,true);		
					//if ($pjtemp->ModPA($liste_pas_actions["Attaquer"]) && $pjtemp->ModPI($liste_pis_actions["Attaquer"])) {
						$arme= array();
						$nbObjtemp=count($pjtemp->Objets);
						for($i=0;$i<$nbObjtemp;$i++){				
							if ($pjtemp->Objets[$i]->equipe && ($pjtemp->Objets[$i]->type=='ArmeJet' || $pjtemp->Objets[$i]->type=='ArmeMelee')) {
								array_push ($arme, $pjtemp->Objets[$i]->id_clef);
							}	
						}		

						if (count($arme) ==1)				
							attaquer ( $PERSO,array_pop($arme),$pjtemp,true,false,false,false,"");	
						else 	{
							//empecher la riposte de la premiere attaque
							attaquer ( $PERSO,array_pop($arme),$pjtemp,true,false,false,true,"MALUS_ATTAQUE1_ATTAQUESENCHAINEES");	
							attaquer ( $PERSO,array_pop($arme),$pjtemp,true,false,false,true,"MALUS_ATTAQUE2_ATTAQUESENCHAINEES");	
						}	
					//}						
				}	
				//ennemi,inamical ou neutre voleur
				elseif ($PERSO->Lieu->permet($liste_flags_lieux["Voler"]) && $row["relation"]>=2 && $row["actionsurprise"]==0) {
					$pjtemp = new Joueur($row["id_perso"],true,true,true,true,true,true);		
					//if ($pjtemp->ModPA($liste_pas_actions["VolerPJ"]) && $pjtemp->ModPI($liste_pis_actions["VolerPJ"])) 
						voler ( $PERSO,$pjtemp,true,false,false);				
				}	
				//ennemi ou ami lancant un sort
				elseif ($PERSO->Lieu->permet($liste_flags_lieux["Magie"]) && $row["actionsurprise"]==2) {
					$pjtemp = new Joueur($row["id_perso"],true,true,true,true,true,true);		
					if ($pjtemp->SortPrefere) {
						$id_sort=$pjtemp->SortPrefere;
						$SQL = "SELECT ".NOM_TABLE_PERSOMAGIE.".id_magie, sous_type,coutpa,coutpi,coutpo,coutpv FROM ".NOM_TABLE_PERSOMAGIE.",". NOM_TABLE_MAGIE." WHERE ".NOM_TABLE_MAGIE.".id_magie=". NOM_TABLE_PERSOMAGIE.".id_magie and id_clef =".$pjtemp->SortPrefere;
						$requete = $db->sql_query($SQL);
						$rowMagie= $db->sql_fetchrow($requete);
                                		$coutPA = $rowMagie["coutpa"];
                                		$coutPI = $rowMagie["coutpi"];
                                		if ($coutPA=="") 
                                		        $coutPA=$liste_pas_actions["Magie"];
                                		if ($coutPI=="") 
                                		        $coutPI=$liste_pis_actions["Magie"];													
						if (($row["relation"]==4 && (strtolower($rowMagie["sous_type"])!= strtolower($liste_stype_sorts["Soin"]))
						 ||$row["relation"]==0 && strtolower($rowMagie["sous_type"])== strtolower($liste_stype_sorts["Soin"]))) {
						        //if ($pjtemp->ModPA($coutPA) && $pjtemp->ModPI($coutPI) && $pjtemp->ModPO($coutPO) && $pjtemp->ModPV($coutPV)) 
							        magie ($pjtemp,strtolower($rowMagie["sous_type"]),$id_sort,$PERSO->ID,$id_sort,$PERSO->ID,$id_sort,$PERSO->ID, null,$id_sort, null, true,false,false);
		
						}
					} else $pjtemp->OutPut(GetMessage("mauvais_param_sort"),false,true);		
				}	
				//parler
				elseif ($PERSO->Lieu->permet($liste_flags_lieux["Parler"]) && (!$PERSO->RIP()) && $row["actionsurprise"]==5) {
					$parleA= array(); 
					$parleA[$PERSO->ID]=$PERSO->ID;
					$pjtemp = new Joueur($row["id_perso"],true,true,true,true,true,true);		
					if ($pjtemp->phrasepreferee) {
						if ($pjtemp->ModPA($liste_pas_actions["Parler"]) && $pjtemp->ModPI($liste_pis_actions["Parler"])) 
 							parler ($pjtemp, "pjs",$pjtemp->phrasepreferee,$parleA, false,true);				
 					} else $pjtemp->OutPut(GetMessage("mauvais_param_phrase"),false,true);		
				}
				}
			}	
		}
	}	

	/**
		\brief demande l'accord d'un PJ lorsqu'il recoit un prix d'un marchand.
		\param $id_objet ne sert qu'a etre passe en parametre dans un hidden. Peut donc etre, un id_objet, un id_sort, un id_clef ....
		\param $prix le prix propos par le marchand
		\param $typeact le type de la transaction :  vente, achat, reparation ....
		\param $param un tableau de parametres passes aussi en hidden		
	*/
	function demandeAccord($id_objet,$prix,$typeact, $param= array()) {
		$str_temp="<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
		$str_temp.= GetMessage("magasin_prix") . $prix ."<br />";
		$str_temp.= "<input type='radio' name='accord' value='1' />".GetMessage("magasin_continuer")."<br />";
		$str_temp.= "<input type='radio' name='accord' value='0' />".GetMessage("magasin_abandonner")."<br />";
		$str_temp.= "<br />".BOUTON_ENVOYER;
		$str_temp.= "<input type='hidden' name='etape' value='2' />";
		$str_temp.= "<input type='hidden' name='typeact' value='$typeact' />";
		$str_temp.= "<input type='hidden' name='id_objet' value='$id_objet' />";
		$str_temp.= "<input type='hidden' name='prix' value='$prix' />";
		$nbParams=count($param);
		for($i=0;$i<$nbParams;$i++)
			$str_temp.= "<input type='hidden' name='p$i' value='$param[$i]' />";
		$str_temp.="</form></div>";
		return $str_temp;
	
	}

	/**
		\brief propose l'action au MJ
		\param $PERSO le PJ qui propose
		\param $sommePA la quantite de PA dcompte des PA du PJ (n'est pas identique a celui du PPA si on peut prier dans le lieu) 
		\param $sommePI la quantite de PI dcompte des PI du PJ (n'est pas identique a celui du PPA si on peut prier dans le lieu)
		\param $id_cible l'ID du MJ a qui on fait le PPA
		\param $msg le message du PPA
		\param $principal indique si c'est le PJ qui est connecte qui fait le PPA (pour savoir si on affiche le message a l'ecran ou non). Ce n'est pas le cas si le PJ a recu une attaque et qu'il a parametre son desir de fuite en cas d'agression)
		\param $ATTAQUANT le PJ qui a agresse $PERSO
	*/
	function proposer($PERSO, $sommePA, $sommePI,$id_cible, $msg,$principal=true, $ATTAQUANT = null) {
		global $liste_pas_actions;
		global $liste_pis_actions;
		global $liste_comp_full;
		global $liste_flags_lieux;
		global $template_main;
		global $db;
		logdate  ($principal."somme".$sommePA. "//".$sommePI);
		if ($sommePA<0 ||  $sommePI<0 || $sommePI=="" ||$sommePA=="" ||$sommePI+$sommePA==0) {
			if($principal) {
				$template_main.= GetMessage("noparam");
				return false;
			}
		}	
		if( (isset($id_cible)) && ($PERSO->ModPA(-$sommePA)) && ($PERSO->ModPI(-$sommePI))){
			if($PERSO->Lieu->permet($liste_flags_lieux["Prier"])){$sommePA *= 2;$sommePI *= 2;}
			$msg = str_replace("<","&lt;",$msg); 
			$msg = str_replace(">","&gt;",$msg); 			
			$MJ = new MJ($id_cible);
			$msg_mj = "**** Proposer action de ".span($PERSO->nom." (PJ)","pj")." d'une valeur de ".span($sommePA." PAs","pa")." et ". span($sommePI." PIs","pi")." *******<br />";
			$msg_mj .= "***".span($PERSO->nom." (PJ)","pj")." est &agrave; ".span($PERSO->Lieu->nom,"lieu").", &agrave; ".span($PERSO->PV." PVs","pv")." et ".span($PERSO->PA." PAs","pa")." et ".span($PERSO->PI." PIs","pi")."********<br />";
			$msg_mj .= $msg;
			$msg_pj = "**** Proposer Action envoy&eacute; &agrave; ".span($MJ->nom." (MJ)","mj")." d'une valeur de ".span($sommePA." PAs","pa")." et ".span($sommePI." PIs","pi")." *******<br />".$msg;
			$MJ->OutPut($msg_mj,false,true);
			$SQL="insert into ".NOM_TABLE_PPA." (id_perso,id_mj, date_ppa, detail_ppa, qte_pa,qte_pi) 
			        values ($PERSO->ID, $id_cible,". time() ." ,\"$msg_mj\",$sommePA, $sommePI)";
			$result = $db->sql_query($SQL);
			if (! $principal) {
				//proposer suite  une aggression
				$valeurs[0]=$PERSO->nom;
				affiche_resultat($ATTAQUANT,$PERSO,GetMessage("demanderFuiteSpec",$valeurs),$msg_pj,GetMessage("demanderFuiteSpec",$valeurs),false);				
			}	
			else $PERSO->OutPut($msg_pj,$principal,true);
			return true;
		} else {
			if($principal) {
				if ($PERSO->RIP())
					$template_main.=  GetMessage("nopvs");
				else	
				if ($PERSO->Archive)
					$template_main.=  GetMessage("archive");
				else		
				if( (!isset($id_cible)) ){
					$template_main.=  GetMessage("noparam");
				} else {
					$template_main.= GetMessage("nopas");
				}
				return false;
			}	
		}
	}


	function parler ($ACTEUR,$typeact,$msg,$pj,$principal=true,$actionsurprise=false) {		
		global $liste_pis_actions;
		global $liste_pas_actions;
		global $liste_flags_lieux;
		global $template_main;
		logDate ("dans parler");
		$ok = false;
		if(!$ACTEUR->Lieu->permet($liste_flags_lieux["Parler"])){
			$template_main.=  GetMessage("noright");
			return;
		}
		
		if(isset($typeact)){
			switch($typeact){
					case 'pjs':{if( (isset($pj)) && (isset($msg)) ){$ok=true;} break;}
					case 'lieu':{if( (isset($msg)) ){$ok=true;} break;}
					case 'voisin':{if( (isset($pj)) && (isset($msg)) ){$ok=true;} break;}
					default:{if( $typeact>0 && (isset($msg)) ){$ok=true;} break;}
			}
		}

		if(  ($ok) && ($ACTEUR->ModPA($liste_pas_actions["Parler"])) && ($ACTEUR->ModPI($liste_pis_actions["Parler"]))){
			$parleA = array_keys ($pj);
			$msg = str_replace("<","&lt;",$msg); 
			$msg = str_replace(">","&gt;",$msg); 
			$sortir = $ACTEUR->etreCache(0);
			if ($sortir) {
				$mess = GetMessage("semontrer_01");
				$valeursCache=array();
				$valeursCache[0]= $ACTEUR->nom;
				$mess_spect = GetMessage("semontrer_spect",$valeursCache);
			}	
			else {
				$mess="";	
				$mess_spect="";
			}	

			if($typeact == 'pjs'){
				$valeurs[0]= $ACTEUR->nom;
				$chaine = '';
				$nb_pnjs = count($pj);
				for($i=0;$i<$nb_pnjs;$i++){
					$pjtemp = new Joueur($parleA[$i],false,false,false,false,false,false);
					if (! $actionsurprise)
						$msg_cible = $mess_spect."***** Message de ".span($ACTEUR->nom,"pj")." ****** <br />".$msg;
					else {
						$valeurs[1]=$pjtemp->nom;
						$msg_cible =GetMessage("arrivee_lieu2",$valeurs).$mess_spect.GetMessage("arrivee_lieu2bis",$valeurs).$msg;
					}
					$pjtemp->OutPut($msg_cible,$actionsurprise,true);
					$chaine .= $pjtemp->nom;
					if($i != ($nb_pnjs-1) ){$chaine .= ", ";}		
				}
				
				
				if (! $actionsurprise)
					$msg_soi = $mess."***** Message envoy&eacute; &agrave; ".span($chaine,"pj")." ****** <br />".$msg;
				else	
					$msg_soi =GetMessage("arrivee_lieu",$valeurs).$mess.GetMessage("arrivee_lieubis",$valeurs).$msg;
			}
		        elseif($typeact == 'voisin'){
		                // dans ce cas pj content les lieux selectionns
		                $lst = $ACTEUR->listePJsDesLieuvoisins(1, false, false,1,$pj);
		                $msg_cible = $mess_spect."***** Message de ".span($ACTEUR->nom,"pj")." pour l'ensemble des personnes pr&eacute;sentes dans les lieux voisins ****** <br />".$msg;
		                $chaine = '';
		                global $PERSO;
		                foreach ($lst as $idperso => $value) {
		                    $pjtemp = new Joueur($idperso,false,false,false,false,false,false);
		                    // si le lieu est insonoris , on entend RIEN
		                    if ($pjtemp->Lieu->permet("EntendreCriExterieur")== true) {
			                    //En cas d'appel au secours, on doit afficher a l'ecran cet appel  la personne qui attaque
			                    //Pour les autres cela par uniquement dans le fa
			                    if ($pjtemp->ID== $PERSO->ID)
			                        $pjtemp->OutPut($msg_cible,true,true);
			                    else
			                        $pjtemp->OutPut($msg_cible,false,true);
			                    // si la personne qui entend est cache, on ne l'affiche pas a celui qui a parle
			                    if (! $pjtemp->dissimule){
			                    	if ($chaine != '')
			                    		$chaine .= ", ";
			                        $chaine .= $pjtemp->nom;
			                    }    
				    }	
		                }
		
		                $msg_soi = $mess."***** Message envoy&eacute; au lieux voisins  ****** <br />".$msg;
		                
		           }
			else
			if($typeact == 'lieu'){
				global $db;
				$SQL = $ACTEUR->listePJsDuLieuDuPerso(1, false, false,1);
				$result = $db->sql_query($SQL);
				$msg_cible = $mess_spect."***** Message de ".span($ACTEUR->nom,"pj")." pour l'ensemble des personnes pr&eacute;sentes en ".span($ACTEUR->Lieu->nom,"lieu")." ****** <br />".$msg;
				$chaine = '';
				global $PERSO;
				$nb_pjs = $db->sql_numrows($result);
				for($i=0;$i<$nb_pjs;$i++){
					$row = $db->sql_fetchrow($result);
					$pjtemp = new Joueur($row["idselect"],false,false,false,false,false,false);
					//En cas d'appel au secours, on doit afficher a l'ecran cet appel  la personne qui attaque 
					//Pour les autres cela par uniquement dans le fa
					if ($pjtemp->ID== $PERSO->ID)
						$pjtemp->OutPut($msg_cible,true,true);
					else	$pjtemp->OutPut($msg_cible,false,true);
					// si la personne qui entend est cache, on ne l'affiche pas a celui qui a parle
					if (! $pjtemp->dissimule) {
						$chaine .= $pjtemp->nom;
						if($i != ($nb_pjs-1) ){$chaine .= ", ";}		
					}	
				}
				
				$msg_soi = $mess."***** Message envoy&eacute; au lieu ".span($ACTEUR->Lieu->nom,"lieu")." , soit &agrave; ".span($chaine,"pj")." ****** <br />".$msg;
			}
			else {
				// parler aux membres du groupe
				$groupePJ=new Groupe ($typeact,true);
				$PJS=$groupePJ->Persos;
				$chaine = '';
				$nb_pnjs = count($PJS);
				for($j=0;$j<$nb_pnjs;$j++){
					if ($PJS[$j]->RIP()==false && $PJS[$j]->Archive==false && ($PJS[$j]->ID<>$ACTEUR->ID) && ($PJS[$j]->Lieu->ID==$ACTEUR->Lieu->ID)) {
						$chaine .= $PJS[$j]->nom . ", ";		
					}	
					if ($chaine<>'')
						$chaine=substr($chaine,0, strlen($chaine)-2);
				}

				$msg_cible = $mess_spect."***** Message de ".span($ACTEUR->nom,"pj")." pour l'ensemble des personnes prsentes du groupe ".$groupePJ->nom." , soit &agrave; ".span($chaine,"pj")." ****** <br />".$msg;
				for($j=0;$j<$nb_pnjs;$j++){
					if ($PJS[$j]->RIP()==false && $PJS[$j]->Archive==false && ($PJS[$j]->ID<>$ACTEUR->ID) && ($PJS[$j]->Lieu->ID==$ACTEUR->Lieu->ID)) {
						$PJS[$j]->OutPut($msg_cible,false,true);
					}	
				}
				$msg_soi = $mess."***** Message envoy&eacute; aux membres prsents du groupe ".span($groupePJ->nom,"etat_temp")." , soit &agrave; ".span($chaine,"pj")." ****** <br />".$msg;

			}	
		
			if ($principal)
				$ACTEUR->OutPut($msg_soi,true,true);
			else 
				$ACTEUR->OutPut($msg_soi,false,true);	
		} else {
			if ($principal) {
				if(!($ok))
					$template_main.=  GetMessage("noparam");
				else if ($ACTEUR->RIP())
					$template_main.=  GetMessage("nopvs");
				else	
				if ($ACTEUR->Archive)
					$template_main.=  GetMessage("archive");
				else					
					$template_main.=  GetMessage("nopas");
				return false;	
			}	
		}
		return true;
	}


	function rechercheDuSort($PERSO,$idSort) {
		$Sort = null;
		$i=0;
		$nbSorts=count($PERSO->Sorts);
		while ($i<$nbSorts && $Sort==null){
			//logDate(" idclef=".$PERSO->Sorts[$i]->id_clef . " / id_sort =" .$idSort);	
			if($PERSO->Sorts[$i]->id_clef == $idSort){$Sort = $PERSO->Sorts[$i];}
			else $i++;
		}
		 return $Sort;
	}			

	// RAPPEL Hixcks : l'id_sort n'est pas l'id_magie mais le id_clef !!!
	// PERSO est celui qui lance le sort, pas forcement celui qui est connecte
	function magie ($PERSO,$typeact,$id_sort_att,$id_cible_att,$id_sort_soin,$id_cible_soin,$id_sort_tel,$id_cible_tel, $id_lieu_tel,$id_sort_autotel, $id_lieu_autotel, $action_surprise=false,$riposteGroupe_autorisee=true,$riposteGroupe=true)  {
		global $liste_pas_actions;
		global $liste_pis_actions;
		global $liste_comp_full;
		global $liste_flags_lieux;
		global $template_main;
		
		if(!$PERSO->Lieu->permet($liste_flags_lieux["Magie"])){
		        if (!$action_surprise && !$riposteGroupe) 
			        $template_main.=  GetMessage("noright");
			return false;        
		} 
		
		if ($PERSO->RIP()) {
		        if (!$action_surprise && !$riposteGroupe) 
			        $template_main.=  GetMessage("nopvs");
			return false;        
		}	        
		
		if ($PERSO->Archive) {
		        if (!$action_surprise && !$riposteGroupe) 
			        $template_main.=  GetMessage("archive");
			return false;        
		}	        
	
		$ok=false;
		$sort=null;
		if(isset($typeact)){
			switch($typeact){
					case 'attaque':{ if( (isset($id_sort_att)) && (isset($id_cible_att)) && ($id_cible_att != $PERSO->ID)){$ok=true;$idSort=$id_sort_att; } break;}
					case 'soin':{if( (isset($id_sort_soin)) && (isset($id_cible_soin)) ){$ok=true;$idSort=$id_sort_soin;} break;}
					case 'teleport':{ if( (isset($id_sort_tel)) && (isset($id_cible_tel)) && (isset($id_lieu_tel)) && ($id_cible_tel != $PERSO->ID) ){$ok=true;$idSort=$id_sort_tel;} break;}
					case 'autoteleport':{if( (isset($id_sort_autotel)) && (isset($id_lieu_autotel)) ){$ok=true;$idSort=$id_sort_autotel;} break;}
					case 'Resurrection':{if( (isset($id_sort_soin)) && (isset($id_cible_soin)) ){$ok=true;$idSort=$id_sort_soin;} break;}
			}
		}

		if( !($ok) ){
			if (!$action_surprise && !$riposteGroupe) 
			        $template_main.=  GetMessage("noparam");
			return false;        
		} 

		$valeurs=array("","","","","","","","","");
		$Sort= rechercheDuSort($PERSO,$idSort);   //pour le recuperer apres pour le traceActions
		$GLOBALS["Sort"] = $Sort;		
		if ($Sort==null) {
			if (!$action_surprise && !$riposteGroupe)  {
				$PERSO->OutPut(GetMessage("noparam"));
			}	
			return false;			
		}	

		if ( ! $PERSO->peutUtiliserObjet($Sort)) {
			$valeurs=array();
			$valeurs[0]= $Sort->nom;
			$valeurs[1]= $Sort->EtatTempSpecifique->nom;
			$template_main.=  GetMessage("sort_inutilisable",$valeurs);
			return false;
		}		
		
		if( ! $Sort->Decharge()){
			if (!$action_surprise && !$riposteGroupe)  $template_main.=  GetMessage("nocha",$valeurs);
			return false;
		}
                $valeurs[0]=$Sort->nom;
                
		if (($PERSO->ModPA($Sort->coutPA)) && ($PERSO->ModPI($Sort->coutPI)) && ($PERSO->ModPO($Sort->coutPO)) && ($PERSO->ModPV($Sort->coutPV))){
			//0: sort, 1: soi, 2: adversaire, 3: degats PV, 4: gain PV, 5: perte PA, 6: effet+, 7: effet -, 8: lieu_dest
			$valeurs[1]=$PERSO->nom;
			$sortir=false;

			if( $Sort->anonyme == 0) 
				$sortir = $PERSO->etreCache(0);
			
			if($typeact == 'attaque'){
				$ADVERSAIRE = new Joueur($id_cible_att,true,true,true,true,true,true);
				if ($ADVERSAIRE->RIP()) {
					if (!$action_surprise && !$riposteGroupe)  {
						$valeurs=array();
						$valeurs[0]=$ADVERSAIRE->nom;
						$template_main.=  GetMessage("ennemimort",$valeurs);
					}	
					return false;
				}	
				else {	
					$valeurs[8]=$ADVERSAIRE->getPVMax();
					$i=0;
					
					$valeurs[2]=$ADVERSAIRE->nom;
	
					$reussite = reussite_sort_attaque($PERSO, $ADVERSAIRE,$Sort);
					
					$degats = lancede($Sort->Degats[1]-$Sort->Degats[0])+$Sort->Degats[0];
					if (($reussite > 5) || ($reussite < -5) ){$degats*=2;}
					if( ($Sort->anonyme == 1) && ($reussite > 0)){$valeurs[1]="Quelqu'un";}
					if($reussite > 0){ // Touch
						$ADVERSAIRE->etreCache(0);				
						switch($Sort->Soustype){
							case "Attaque":{
								$degats = $ADVERSAIRE->AbsorptionDegats($Sort->competence,$degats);
								if($ADVERSAIRE->ModPV(-$degats)){$msg_final = "sort_attaque_01";}else{$msg_final = "sort_attaque_02";}
								$valeurs[3]=$degats;$valeurs[4]=0;$valeurs[5]=0;
								bre			}
							case "Paralysie":{
								$degats = $ADVERSAIRE->AbsorptionDegats($Sort->competence,$degats);
								$msg_final = "sort_paralysie_01";
								$ADVERSAIRE->ModPA(-$degats,true);
								$valeurs[3]=0;$valeurs[4]=0;$valeurs[5]=$degats;
								break;
							}
							case "Transfert":{
								$degats = $ADVERSAIRE->AbsorptionDegats($Sort->competence,$degats);
								if( $ADVERSAIRE->ModPV(-$degats)){$msg_final = "sort_transfert_01";}else{$msg_final = "sort_transfert_02";}
								$PERSO->ModPV(round($degats/2));
								$valeurs[3]=$degats;$valeurs[4]=round($degats/2);$valeurs[5]=0;
								break;
							}
						}
						$retour = $ADVERSAIRE->GererChaineEtatTemp($Sort->provoqueetat);
						$valeurs[6] = $retour["ajouter"];$valeurs[7] = $retour["retirer"];
	
					} else { // rat
						switch($Sort->Soustype){
							case "Attaque":{
								if($PERSO->ModPV(-$degats)){$msg_final = "sort_attaque_03";}else{$msg_final = "sort_attaque_04";}
								$valeurs[3]=$degats;$valeurs[4]=0;$valeurs[5]=0;
								break;
							}						
							case "Paralysie":{
								$msg_final = "sort_paralysie_02";
								$PERSO->ModPA(-$degats,true);
								$valeurs[3]=0;$valeurs[4]=0;$valeurs[5]=$degats;
								break;
							}
							case "Transfert":{
								if($PERSO->ModPV(-$degats)){$msg_final = "sort_transfert_03";}else{$msg_final = "sort_transfert_04";}
								$valeurs[3]=$degats;$valeurs[4]=0;$valeurs[5]=0;
								break;
							}	
						}
						
					}
				}
			}
			
			// Fin des sorts d'attaque ET de paralysie
			
			if($typeact == 'teleport'){
				$ADVERSAIRE = new Joueur($id_cible_tel,true,true,true,true,true,true);
				$valeurs[8]=$ADVERSAIRE->getPVMax();
				$valeurs[0]=$Sort->nom;
				$valeurs[2]=$ADVERSAIRE->nom;
				if( $Sort->anonyme == 0) 
					$sortir = $PERSO->etreCache(0);	
					$reussite = reussite_sort_teleport($PERSO, $ADVERSAIRE,$Sort);
					if ($reussite) {
						$lieu = new Lieu ($id_lieu_tel);
						$reussite = $ADVERSAIRE->peutAccederLieu($lieu);
					}	
					$degats = lancede($Sort->Degats[1]-$Sort->Degats[0])+$Sort->Degats[0];
					if (($reussite > 5) || ($reussite < -5) ){$degats*=2;}
					if( ($Sort->anonyme == 1) && ($reussite > 0)){$valeurs[1]="Quelqu'un";}
					if($reussite > 0){ // Touch
						$sortir = $ADVERSAIRE->etreCache(0);	
						$msg_final = "sort_teleport_01";
						$ADVERSAIRE->DeplaceLieu($id_lieu_tel);
						$valeurs[3]=0;
						$valeurs[4]=0;
						$valeurs[5]=0;
						$valeurs[8]=$ADVERSAIRE->Lieu->nom;
						$retour = $ADVERSAIRE->GererChaineEtatTemp($Sort->provoqueetat);
						$valeurs[6] = $retour["ajouter"];
						$valeurs[7] = $retour["retirer"];
						if ($id_lieu_tel<> $PERSO->Lieu->ID)
							$riposteGroupe_autorisee=false;						
					} else { // rat
						$msg_final = "sort_teleport_02";
						$valeurs[3]=0;
						$valeurs[4]=0;
						$valeurs[5]=0;
						$valeurs[8]=$ADVERSAIRE->Lieu->nom;
					}
			}
	
			//Fin des sorts de teleportation
			
			if($typeact == 'soin'){
				$riposteGroupe_autorisee=false;
				if ($id_cible_soin == $PERSO->ID)
					$ADVERSAIRE=$PERSO;
				else $ADVERSAIRE = new Joueur($id_cible_soin,true,true,true,true,true,true);
				if ($ADVERSAIRE->RIP()) {
					if (!$action_surprise && !$riposteGroupe)  {
						$valeurs=array();
						$valeurs[0]=$ADVERSAIRE->nom;
						$template_main.=  GetMessage("ennemimort",$valeurs);
					}	
					return false;
				}		
				else {			
					if( $Sort->anonyme == 0) 
						$sortir = $PERSO->etreCache(0);
					$valeurs[0]=$Sort->nom;
					$valeurs[2]=$ADVERSAIRE->nom;
					$valeurs[8]=$ADVERSAIRE->getPVMax();
					$reussite = reussite_sort_soin($PERSO, $ADVERSAIRE,$Sort);
				
					$degats = lancede($Sort->Degats[1]-$Sort->Degats[0])+$Sort->Degats[0];
					if (($reussite > 5) || ($reussite < -5) ){$degats*=2;}
					if( ($Sort->anonyme == 1) && ($reussite > 0)){$valeurs[1]="Quelqu'un";}
					if($reussite > 0){ // Touch
						$msg_final = "sort_soin_01";
						$ADVERSAIRE->ModPV($degats);
						$valeurs[3]=0;
						$valeurs[4]=$degats;
						$valeurs[5]=0;
						$retour = $ADVERSAIRE->GererChaineEtatTemp($Sort->provoqueetat);
						$valeurs[6] = $retour["ajouter"];
						$valeurs[7] = $retour["retirer"];
					} else { // rat
					        /**
					        *   Sort de soin rate provoque des degats sur lanceur
					        */
						if($PERSO->ModPV(-round($degats/2))){$msg_final = "sort_soin_02";}else{$msg_final = "sort_soin_04";}
						$valeurs[3]=0;
						$valeurs[4]=round(-$degats/2);
						$valeurs[5]=0;
					}
				}
			}
	
			//Fin des sorts de soins
			
			if($typeact == 'Resurrection'){
				$riposteGroupe_autorisee=false;
				$ADVERSAIRE = new Joueur($id_cible_soin,true,true,true,true,true,true);
				if (! $ADVERSAIRE->RIP()) {
					$template_main.= GetMessage("sort_resurrection_imp");
				}
				else {
					if( $Sort->anonyme == 0) 
						$sortir = $PERSO->etreCache(0);
					$valeurs[0]=$Sort->nom;
					$valeurs[2]=$ADVERSAIRE->nom;
					$valeurs[8]=$ADVERSAIRE->getPVMax();
					$reussite = reussite_sort_resurrection($PERSO, $ADVERSAIRE,$Sort);
				
					$degats = lancede($Sort->Degats[1]-$Sort->Degats[0])+$Sort->Degats[0];
					if (($reussite > 5) || ($reussite < -5) ){$degats*=2;}
					if( ($Sort->anonyme == 1) && ($reussite > 0)){$valeurs[1]="Quelqu'un";}
					if($reussite > 0){ // Touch
						$msg_final = "sort_resurrection_01";
						$ADVERSAIRE->ModPV(-$ADVERSAIRE->PV+1 + $degats);
						$valeurs[3]=0;
						$valeurs[4]=$degats;
						$valeurs[5]=0;
						$retour = $ADVERSAIRE->GererChaineEtatTemp($Sort->provoqueetat);
						$valeurs[6] = $retour["ajouter"];
						$valeurs[7] = $retour["retirer"];
					} else { // rat
						$msg_final = "sort_resurrection_02";
						$PERSO->ModPV(round($degats/2));
						$valeurs[3]=0;
						$valeurs[4]=round($degats/2);
						$valeurs[5]=0;
					}
				}
			}
	
			//Fin des sorts de resurrection			 
	
			if($typeact == 'autoteleport'){
				$valeurs[0]=$Sort->nom;$valeurs[2]=$PERSO->nom;
				$ADVERSAIRE=$PERSO;
				$reussite = reussite_sort_autoteleport($PERSO,$Sort);
				if ($reussite) {
					$lieu = new Lieu ($id_lieu_autotel);
					$reussite = $ADVERSAIRE->peutAccederLieu($lieu);
				}					
				if( ($Sort->anonyme == 1) && ($reussite > 0)){$valeurs[1]="Quelqu'un";}				
				if($reussite > 0){ // Touch
					$msg_final = "sort_teleport_self_01";
					$PERSO->DeplaceLieu($id_lieu_autotel);
					$valeurs[3]=0;$valeurs[4]=0;$valeurs[5]=0;$valeurs[8]=$PERSO->Lieu->nom;
					$retour = $PERSO->GererChaineEtatTemp($Sort->provoqueetat);
					$valeurs[6] = $retour["ajouter"];
					$valeurs[7] = $retour["retirer"];
				} else { // rat
					$msg_final = "sort_teleport_self_02";
					$valeurs[3]=0;
					$valeurs[4]=0;
					$valeurs[5]=0;
					$valeurs[8]=$PERSO->Lieu->nom;
				}
			}
			//Fin des sorts d'auto teleportation
			if($reussite > 0)
				$attResult="ATT_OK";
			else 	$attResult="ATT_KO";
				
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

			$valeursSpect=array();
			$valeursSpect[0] = $ADVERSAIRE->nom;
                        $GLOBALS["ADVERSAIRE"]= $ADVERSAIRE;
			if ($action_surprise)//cas ou celui qui est connecte subit l'action d'un pnj qui a une action surprise
				affiche_resultat($ADVERSAIRE,$PERSO,GetMessage("arrivee_lieu2",$valeurs) . $mess_spect .GetMessage($msg_final."_adv",$valeurs),GetMessage("arrivee_lieuSpect",$valeursSpect) .$mess.GetMessage($msg_final,$valeurs),GetMessage("arrivee_lieuSpect",$valeursSpect) .$mess_spect .GetMessage($msg_final."_spect",$valeurs),true);
			else 			
			if (!$riposteGroupe)	//cas normal celui qui est connecte fait l'action
				affiche_resultat($PERSO,$ADVERSAIRE,$mess.GetMessage($msg_final,$valeurs),$mess_spect.GetMessage($msg_final."_adv",$valeurs),$mess_spect.GetMessage($msg_final."_spect",$valeurs),true);
			else   //cas riposte
				affiche_resultat($ADVERSAIRE,$PERSO,GetMessage("riposteGroupe",$valeurs).GetMessage($msg_final."_adv",$valeurs),GetMessage("riposteGroupe",$valeurs).GetMessage($msg_final,$valeurs),GetMessage("riposteGroupe",$valeurs).GetMessage($msg_final."_spect",$valeurs),false);

			if ($riposteGroupe_autorisee) 			
				riposteGroupe ($ADVERSAIRE,$PERSO,$attResult,false);	
			return true;			
		}	
		else {
		        $template_main.=  GetMessage("nopas");
		        return false;
		}        
	}


	function voler ($ADVERSAIRE,$ATTAQUANT,$action_surprise=false,$riposteGroupe_autorisee=true,$riposteGroupe=true ){
			global $liste_comp_full;
			global $liste_flags_lieux;
			global $liste_pas_actions;
			global $liste_pis_actions;
			global $liste_reactions;		
			global $db;
			global $template_main;
			if (!$action_surprise && !$riposteGroupe) {
				if(!$ATTAQUANT->Lieu->permet($liste_flags_lieux["Voler"])){
					$template_main.=  GetMessage("noright");
					return -1;
				} 
				
				if(  !isset($ADVERSAIRE) ) {
					$template_main.=  GetMessage("noparam");
					return -1;
				}	
				
				if ((! $ATTAQUANT->ModPA($liste_pas_actions["VolerPJ"])) || (! $ATTAQUANT->ModPI($liste_pis_actions["VolerPJ"]))) {
					if ($ATTAQUANT->RIP())
						$template_main.=  GetMessage("nopvs");
					else	
					if ($ATTAQUANT->Archive)
						$template_main.=  GetMessage("archive");
					else					
					$template_main.=  GetMessage("nopas");		
					return -1;
				}	
			}
	

			
			$valeurs[0] = $ADVERSAIRE->nom;
			$valeurs[1] = $ATTAQUANT->nom;
			$valeurs[2] = '';
			$mess="";	
			$mess_spect="";
			$reussite = reussite_voler_or($ATTAQUANT, $ADVERSAIRE);
			$somme=0;
			if($reussite > 0){
				$somme = ceil( ($ADVERSAIRE->PO /100) * 5);
				if($reussite > 5){
					$somme *= 2;
				}
				$somme = Min($somme,$ADVERSAIRE->PO);
				$ATTAQUANT->ModPO($somme);
				$ADVERSAIRE->ModPO(-$somme);
				$valeurs[2]=$somme;				
				$riposteGroupe_autorisee=false;
				if ($action_surprise)
						$ATTAQUANT->OutPut(GetMessage("arrivee_lieu",$valeurs) .GetMessage("voler_argent_01",$valeurs),false,false);					
				else {
					if (!$riposteGroupe)	
						$ATTAQUANT->OutPut(GetMessage("voler_argent_01",$valeurs),true,true);					
					else 
						$ATTAQUANT->OutPut(GetMessage("riposteGroupe",$valeurs).GetMessage("voler_argent_01",$valeurs),true,false);					
				}	
			} else {
			
				if($reussite < -5){
					$sortir = $ATTAQUANT->etreCache(0);
					if ($sortir) {
						$mess = GetMessage("semontrer_01");
						$valeursCache=array();
						$valeursCache[0]= $ATTAQUANT->nom;
						$mess_spect = GetMessage("semontrer_spect",$valeursCache);
					}	

					//echec critique => Tout le mode s'apercoit du vol + riposteGroupe + engagement
					$msg_vole = $mess_spect.GetMessage("voler_argent_02_adv",$valeurs);
					$msg_spect = $mess_spect.GetMessage("voler_argent_02_spectat",$valeurs);
					$degats=4;
					if ((defined("ENGAGEMENT")) && ENGAGEMENT==1)
						Engagement($ATTAQUANT,$ADVERSAIRE);
				}else{
					$riposteGroupe_autorisee=false;
					$degats=2;
					$msg_vole ="";
					$msg_spect ="";
				}
				
				$msg_voleur = $mess. GetMessage("voler_argent_02",$valeurs);
	
				$valeursSpect=array();
				$valeursSpect[0] = $ADVERSAIRE->nom;
	
				if ($action_surprise) {
					if ($msg_vole <>"")
						affiche_resultat($ADVERSAIRE,$ATTAQUANT,GetMessage("arrivee_lieu2",$valeurs) .$msg_vole,GetMessage("arrivee_lieuSpect",$valeursSpect) .$msg_voleur,GetMessage("arrivee_lieuSpect",$valeursSpect).$msg_spect,true);
					else affiche_resultat($ADVERSAIRE,$ATTAQUANT,"",GetMessage("arrivee_lieuSpect",$valeursSpect) .$msg_voleur,"",true);	
				}	
				else {
					if (!$riposteGroupe)	
						affiche_resultat($ATTAQUANT,$ADVERSAIRE,$msg_voleur,$msg_vole,$msg_spect,true);
					else 
						affiche_resultat($ADVERSAIRE,$ATTAQUANT,GetMessage("riposteGroupe",$valeurs).$msg_vole,GetMessage("riposteGroupe",$valeurs).$msg_voleur,GetMessage("riposteGroupe",$valeurs).$msg_spect,false);
		
				}

			}		
			if ($riposteGroupe_autorisee) 			
				riposteGroupe ($ADVERSAIRE,$ATTAQUANT,"VOL_KO",false);		
			return $somme;
	}

	/**
	ATTAQUANT est celui qui fait l'action sur le perso est cible.
	Si c'est une action surprise ATTAQUANT n'est pas les perso connect mais un pnj qui agit  l'arrive du perso (qui est la cible)
	*/

	function attaquer ( $cible,$id_arme,$ATTAQUANT,$action_surprise=false,$riposteGroupe_autorisee=true,$riposteGroupe=true, $attaqueEnchainee=false, $bonusmalus ="") {
		logDate ("1 attaque :$cible,$id_arme,$ATTAQUANT,$action_surprise,$riposteGroupe_autorisee,$riposteGroupe");
		global $liste_comp_full;
		global $liste_flags_lieux;
		global $liste_pas_actions;
		global $liste_pis_actions;
		global $liste_reactions;		
		global $db;
		global $template_main;
		
		if (!$action_surprise && !$riposteGroupe) {
			if(!$ATTAQUANT->Lieu->permet($liste_flags_lieux["Attaquer"])){
				$template_main.=  GetMessage("noright");
				return -1;
			} 
			
			if( (!isset($id_arme)) || (!isset($cible)) ) {
				$template_main.=  GetMessage("noparam");
				return -1;
			}	
			
			if ((!$ATTAQUANT->ModPA($liste_pas_actions["Attaquer"])) || (! $ATTAQUANT->ModPI($liste_pis_actions["Attaquer"]))) {
				if ($ATTAQUANT->RIP())
					$template_main.=  GetMessage("nopvs");
				else	
				if ($ATTAQUANT->Archive)
					$template_main.=  GetMessage("archive");
				else	
			
				$template_main.=  GetMessage("nopas");		
				return -1;
			}	
			 
		}
		
		$valeurs[0] = "";$valeurs[1] = "";$valeurs[2] = "";$valeurs[3] = "";
		$valeurs[4] = "";$valeurs[5] = "";$valeurs[6] = "";$valeurs[7] = "";
		
		//$ADVERSAIRE = new Joueur($id_cible,true,true,true,true,true,true);	
		$ADVERSAIRE = $cible;
		$valeurs[8] = $ADVERSAIRE->getPVMax();
		$Arme = null;
		$i=0;
		$nb_objets = count($ATTAQUANT->Objets);
		while ($Arme == null && $i<$nb_objets) {
			if($ATTAQUANT->Objets[$i]->id_clef == $id_arme)
				 $Arme = $ATTAQUANT->Objets[$i];
			else $i++;
		}
		
		if ($Arme==null)  {
				logDate("Arme==null dans actions/attaquer");
				return -1;
		}		
		
		$valeurs[0]=$Arme->nom;
		$valeurs[1]=$ADVERSAIRE->nom;
		$valeurs[2]=$ATTAQUANT->nom;
				
		if ( ! $ATTAQUANT->peutUtiliserObjet($Arme)) {
			$valeurs=array();
			$valeurs[0]= $Arme->nom;
			$valeurs[1]= $Arme->EtatTempSpecifique->nom;
			//$ATTAQUANT->OutPut(GetMessage("objet_inutilisable",$valeurs));
			$template_main.=  GetMessage("objet_inutilisable",$valeurs);
			return -1;
		}
		if( $ADVERSAIRE->RIP()){
			$valeurs[0] = $valeurs[1];
			if (!$action_surprise && !$riposteGroupe)
				$template_main.=  GetMessage("ennemimort",$valeurs);
			return -1;
		}
		
		if(! $Arme->Decharge()){
			if (!$action_surprise && !$riposteGroupe)
				$template_main.=  GetMessage("nomun",$valeurs);
			return -1;
		}
	
		if ($attaqueEnchainee) {
			$msg_attaquant = GetMessage("attaqueEnchainee_01") ;
			$msg_assaili = GetMessage("attaqueEnchainee_01");					
			$msg_spect = GetMessage("attaqueEnchainee_01");					
		}		
		else {
			$msg_attaquant ="";
			$msg_assaili ="";
			$msg_spect = "";
			// inclusion de engagement de Tidou Ici pour ne pas le faire 2 fois en cas d'attaque enchainee	
			if ($Arme->type != "ArmeJet"){
				if ((defined("ENGAGEMENT")) && ENGAGEMENT==1)
					Engagement($ATTAQUANT,$ADVERSAIRE);
			}

		}
	
		
		$reussite = reussite_attaquer($ATTAQUANT, $ADVERSAIRE, $Arme,$bonusmalus);
		$degats = lancede($Arme->Degats[1]-$Arme->Degats[0])+$Arme->Degats[0];
		if (($reussite > 5) || ($reussite < -5) ){$degats*=2;}

		if($Arme->competencespe == "Etourdissant"){$valeurs[3] = 0;$valeurs[4] = $degats;} else {$valeurs[3] = $degats;	$valeurs[4] = 0;}
		if($Arme->competencespe == "Vampire"){$valeurs[7] = $degats;} else {$valeurs[7] = 0;}
		if( ($Arme->anonyme == 1) && ($reussite > 0)){$valeurs[2]="Quelqu'un";}
		if( $Arme->anonyme == 0) {
			$sortir = $ATTAQUANT->etreCache(0);
			if ($sortir) {
				$mess = GetMessage("semontrer_01");
				$valeursCache=array();
				$valeursCache[0]= $ATTAQUANT->nom;
				$mess_spect = GetMessage("semontrer_spect",$valeursCache);
			}	
			else {
				$mess="";	
				$mess_spect="";
			}	
			
			$sortirADV = $ADVERSAIRE->etreCache(0);
		}	

		if($reussite > 0){ //BINGO
			$sortirADV = $ADVERSAIRE->etreCache(0);
			$attResult="ATT_OK";
			$degats = $ADVERSAIRE->AbsorptionDegats($Arme->competence,$degats);
			if($Arme->competencespe == "Etourdissant")
				{$valeurs[3] = 0;$valeurs[4] = $degats;} 
				else {$valeurs[3] = $degats;	$valeurs[4] = 0;}
			if($Arme->competencespe == "Vampire"){$valeurs[7] = $degats;} else {$valeurs[7] = 0;}
			
			$retour = $ADVERSAIRE->GererChaineEtatTemp($Arme->provoqueetat);
			$valeurs[6] = $retour["retirer"];
			$valeurs[5] = $retour["ajouter"];
			if($Arme->competencespe == "Etourdissant"){
				$mort = false;
				$ADVERSAIRE->ModPA(-$degats, true);
			} else {
				$mort = !($ADVERSAIRE->ModPV(-$degats));
				if($Arme->competencespe == "Vampire"){$ATTAQUANT->ModPV($degats);}
			}



			$msg_attaquant .= GetMessage("attaquer_01",$valeurs) ;
			$msg_assaili .= GetMessage("attaquer_adv_01",$valeurs);					

			if ( $valeurs[5]!="rien") {
				$msg_attaquant .= GetMessage("attaquer_01bis",$valeurs);
				$msg_assaili .= GetMessage("attaquer_adv_01bis",$valeurs);
			}	
			
			if($mort){					
					$msg_attaquant .= GetMessage("attaquer_01ter",$valeurs);								
					$msg_assaili .= GetMessage("attaquer_adv_01ter",$valeurs);					
					$msg_spect .= GetMessage("attaquer_spectat_03",$valeurs);					
					$riposteGroupe_autorisee=false;
			}	
			else $msg_spect.= GetMessage("attaquer_spectat_01",$valeurs);				

		} else { // Ooooooooh dommage
			$Arme->Abime();
			$attResult="ATT_KO";			
			$msg_attaquant .= GetMessage("attaquer_02",$valeurs);
			$msg_assaili .= GetMessage("attaquer_adv_02",$valeurs);
			$msg_spect .= GetMessage("attaquer_spectat_02",$valeurs);

		}

		$valeursSpect=array();
		$valeursSpect[0] = $ADVERSAIRE->nom;
		
		if ($action_surprise)
			affiche_resultat($ADVERSAIRE,$ATTAQUANT,GetMessage("arrivee_lieu2",$valeurs) .$mess_spect.$msg_assaili,GetMessage("arrivee_lieuSpect",$valeursSpect) .$mess.$msg_attaquant,GetMessage("arrivee_lieuSpect",$valeursSpect).$mess_spect.$msg_spect,true);
		else {
			if (!$riposteGroupe)	
				affiche_resultat($ATTAQUANT,$ADVERSAIRE,$mess.$msg_attaquant,$mess_spect.$msg_assaili,$mess_spect.$msg_spect,true);
			else 
				affiche_resultat($ADVERSAIRE,$ATTAQUANT,GetMessage("riposteGroupe",$valeurs).$msg_assaili,GetMessage("riposteGroupe",$valeurs).$msg_attaquant,GetMessage("riposteGroupe",$valeurs).$msg_spect,false);

		}
		
		if ($riposteGroupe_autorisee) {
			// riposteGroupe de l'assailli			
			riposteGroupe ($ADVERSAIRE,$ATTAQUANT,$attResult,$Arme->anonyme);		
			
		}	
		return $degats;
	}


	/**
	$ACTEUR est celui qui montre ce qui est cache
	$chemin, $objet et $pers sont de tableaux de ce qui est revele
	$pj est un tableau de persos a qui $ACTEUR montre les objets
	$typeact est le type d'action, revele a tous les persos presents, aux membres du groupe ...
	*/
	function reveler ($ACTEUR,$typeact,$chemin,$objet,$pers,$pj,$toutesEntites) {
		
		global $liste_pis_actions;
		global $liste_pas_actions;
		global $sep;
		global $db;

		$ok = false;		
		if(isset($typeact)){
			switch($typeact){
					case 'pjs':{if( (isset($pj)) ) {$ok=true;} break;}
					default:$ok=true; break;
			}
		}

		if(  ($ok) && ($ACTEUR->ModPA($liste_pas_actions["Reveler"])) && ($ACTEUR->ModPI($liste_pis_actions["Reveler"]))){
			$sortir = $ACTEUR->etreCache(0);
			if ($sortir) {
				$mess = GetMessage("semontrer_01");
				$valeursCache=array();
				$valeursCache[0]= $ACTEUR->nom;
				$mess_spect = GetMessage("semontrer_spect",$valeursCache);
			}	
			else {
				$mess="";	
				$mess_spect="";
			}	
			$msg_obj = "";
			$msg_chemin = "";			
			$msg_perso = "";	

			if ($toutesEntites) {
				$SQLentite = "Select E.id, E.id_entite, E.type, E.nom FROM ".NOM_TABLE_ENTITECACHEE." as E,"
					.NOM_TABLE_ENTITECACHEECONNUEDE."   as ECCD
				   WHERE  E.id= ECCD.id_entitecachee and ECCD.id_perso = ".$ACTEUR->ID.
					" AND E.id_lieu = ".$ACTEUR->Lieu->ID ." order by E.type";
				$resultEntite = $db->sql_query($SQLentite);
				while($row =  $db->sql_fetchrow($resultEntite)) {
					if ($row["type"]==1) 
						//objet
						$objet[$row["id"].$sep.$row["id_entite"].$sep.$row["nom"]]=1;
					elseif ($row["type"]==0) 
						//chemin
						$chemin[$row["id"].$sep.$row["id_entite"].$sep.$row["nom"]]=1;
					elseif ($row["type"]==2) 
						//pj
						$pers[$row["id"].$sep.$row["id_entite"].$sep.$row["nom"]]=1;
				}	
			}
			if( isset($pj))
				$parleA = array_keys ($pj);
			$nb_objets=count($objet);
			if ($nb_objets>0) {
				$obj_id = array_keys ($objet);
				for($i=0;$i<$nb_objets;$i++) {
					$pos1 = strpos($obj_id[$i], $sep);
					$obj_id_entitecachee[$i]=substr($obj_id[$i], 0,$pos1); 
					$pos2 = strrpos($obj_id[$i], $sep);					
					$libelle=substr($obj_id[$i], $pos2+strlen($sep)); 
					$obj_id[$i]=substr($obj_id[$i], $pos1+strlen($sep),$pos2-($pos1+strlen($sep))); 
					$msg_obj .= span($libelle,"objet").", ";;
				}	

			}		
			$nb_chemins= count($chemin);		
			if ($nb_chemins>0) {
				$chem_id = array_keys ($chemin);
				for($i=0;$i<$nb_chemins;$i++) {
					$pos1 = strpos($chem_id[$i], $sep);
					$chem_id_entitecachee[$i]=substr($chem_id[$i], 0,$pos1); 
					$pos2 = strrpos($chem_id[$i], $sep);
					$libelle=substr($chem_id[$i], $pos2+strlen($sep)); 
					$chem_id[$i]=substr($chem_id[$i], $pos1+strlen($sep),$pos2-($pos1+strlen($sep))); 
					$msg_chemin .= span($libelle,"lieu").", ";
				}	

				//$msg_chemin= substr($msg_chemin, 0, -1);  		
			}	
			$nb_pers = count($pers);
			if ($nb_pers>0) {
				$per_id = array_keys ($pers);
				for($i=0;$i<$nb_pers;$i++) {
					$pos1 = strpos($per_id[$i], $sep);
					$per_id_entitecachee[$i]=substr($per_id[$i], 0,$pos1); 
					$pos2 = strrpos($per_id[$i], $sep);
					$libelle=substr($per_id[$i], $pos2+strlen($sep)); 
					$per_id[$i]=substr($per_id[$i], $pos1+strlen($sep),$pos2-($pos1+strlen($sep))); 
					$msg_perso .= span($libelle,"pj").", ";
				}	
			}	

			$chaine="";
			$valeurs=array();			
			$valeurs[0]= $ACTEUR->nom;					
			$valeurs[1]= $msg_obj . $msg_chemin .$msg_perso;
			$valeurs[1]= substr($valeurs[1], 0, -2);  					
			if($typeact == 'lieu'){
				$SQL=$ACTEUR->listePJsDuLieuDuPerso(1, false, false,1);
				$result = $db->sql_query($SQL);
				$msg_cible = $mess_spect. getmessage("reveler_spect",$valeurs);
				$nb_perso = $db->sql_numrows($result);
				for($j=0;$j<$nb_perso;$j++){
					$row = $db->sql_fetchrow($result);
					$pjtemp = new Joueur($row["idselect"],false,false,false,false,false,false);
					//ne pas envoyer au joueur cache un message pour lui montrer sa propre cachette
					if ($valeurs[1]!= span($pjtemp->nom,"pj"))
						$pjtemp->OutPut($msg_cible,false,true);
					for($i=0;$i<$nb_chemins;$i++) 
						$pjtemp->decouvreEntiteCachee($chem_id_entitecachee[$i],0);
					for($i=0;$i<$nb_objets;$i++) 
						$pjtemp->decouvreEntiteCachee($obj_id_entitecachee[$i],1);
					for($i=0;$i<$nb_pers;$i++) {
						//ne pas faire decouvrir au perso cache sa propre cachette
						if ($per_id[$i]!=$pjtemp->ID)
							$pjtemp->decouvreEntiteCachee($per_id_entitecachee[$i],2);					
					}	
					// si la personne qui entend est cache, on ne l'affiche pas a celui qui a parle
					if (! $pjtemp->dissimule) {
						$chaine .= $pjtemp->nom;
						if($j != ($nb_perso-1) ){$chaine .= ", ";}		
					}	
				}
			}
			elseif($typeact == 'pjs'){
				$valeurs[0]= $ACTEUR->nom;
				$nbPJs = count($pj);
				for($j=0;$j<$nbPJs;$j++){
					$pjtemp = new Joueur($parleA[$j],false,false,false,false,false,false);
					$msg_cible = $mess_spect. getmessage("reveler_spect",$valeurs);
					//ne pas envoyer au joueur cache un message pour lui montrer sa propre cachette
					if ($valeurs[1]!= span($pjtemp->nom,"pj"))
						$pjtemp->OutPut($msg_cible,false,true);
					for($i=0;$i<$nb_chemins;$i++) 
						$pjtemp->decouvreEntiteCachee($chem_id_entitecachee[$i],0);
					for($i=0;$i<$nb_objets;$i++) 
						$pjtemp->decouvreEntiteCachee($obj_id_entitecachee[$i],1);
					for($i=0;$i<$nb_pers;$i++) {
						//ne pas faire decouvrir au perso cache sa propre cachette
						if ($per_id[$i]!=$pjtemp->ID)
							$pjtemp->decouvreEntiteCachee($per_id_entitecachee[$i],2);					
					}	
					$chaine .= $pjtemp->nom.", ";
					//if($i != (count($pj)-1) ){$chaine .= ", ";}		
				}
				if ($chaine<>"")
					$chaine=substr($chaine,0, strlen($chaine)-2);

			}
			else {
				// reveler aux membres du groupe
				$groupePJ=new Groupe ($typeact,true);
				$PJS=$groupePJ->Persos;
				$msg_cible = $mess_spect. getmessage("reveler_spect",$valeurs);			
				$nbPJs = count($PJS);
				for($j=0;$j<$nbPJs;$j++){
					if (($PJS[$j]->ID<>$ACTEUR->ID) && ($PJS[$j]->Lieu->ID==$ACTEUR->Lieu->ID)) {
						//ne pas envoyer au joueur cache un message pour lui montrer sa propre cachette
						if ($valeurs[1]!= span($PJS[$j]->nom,"pj"))
							$PJS[$j]->OutPut($msg_cible,false,true);
						for($i=0;$i<$nb_chemins;$i++) 
							$pjtemp->decouvreEntiteCachee($chem_id_entitecachee[$i],0);
						for($i=0;$i<$nb_objets;$i++) 
							$pjtemp->decouvreEntiteCachee($obj_id_entitecachee[$i],1);
						for($i=0;$i<$nb_pers;$i++) {
							//ne pas faire decouvrir au perso cache sa propre cachette
							if ($per_id[$i]!=$pjtemp->ID)
								$pjtemp->decouvreEntiteCachee($per_id_entitecachee[$i],2);					
						}
						$chaine .= $PJS[$j]->nom . ", ";		
					}	
					if ($chaine<>"")
						$chaine=substr($chaine,0, strlen($chaine)-2);
				}
			}	

			$valeurs[2] =$chaine;
			$msg_soi = $mess. getmessage("reveler",$valeurs);

			// on informe les pj caches qu'Acteur les a montre aux pjs de $chaine
			if ($nb_pers>0) {
				//$pjs = array_keys ($per_id);
				//for($i=0;$i<count($per_id);$i++) {
				for($i=0;$i<$nb_pers;$i++) {
					$pjvu = new Joueur($per_id[$i],false,false,false,false,false,false);
					$msg_adv = $mess_spect. getmessage("reveler_adv",$valeurs);
					$pjvu->OutPut($msg_adv,false,true);
				}	
			}	
		
			//if ($principal)
				$ACTEUR->OutPut($msg_soi,true,true);
			//else 
			//	$ACTEUR->OutPut($msg_soi,false,true);	
		} else {
			//if ($principal)
				if(!($ok))
					$template_main.=  GetMessage("noparam");
				else if ($ACTEUR->RIP())
					$template_main.=  GetMessage("nopvs");
				else	
				if ($ACTEUR->Archive)
					$template_main.=  GetMessage("archive");
				else					
					$template_main.=  GetMessage("nopas");
		}
	}


	function reussite_fouillerlieu_chemin($PERSO, $chemin) {
		global $liste_comp_full;
		$reussite = $PERSO->GetNiveauComp($liste_comp_full["Dissimulation"],true)
			+ $PERSO->GetNiveauComp($liste_comp_full["Observation"],true) 
			- lanceDe(20)
			- lanceDe($chemin->difficulte) ;
		logDate("reussite".$reussite);		
		return $reussite;	

	}	

	function reussite_fouillerlieu_pj($PERSO) {
		global $liste_comp_full;
		$reussite = $PERSO->GetNiveauComp($liste_comp_full["Dissimulation"],true)
			+ $PERSO->GetNiveauComp($liste_comp_full["Observation"],true) 
			- lanceDe(20);
			+ lanceDe($PERSO->Lieu->difficultedesecacher) ;
		logDate("reussite".$reussite);	
		return $reussite;	
	}	

	function reussite_fouillerlieu_objet($PERSO) {
		global $liste_comp_full;
		$reussite = $PERSO->GetNiveauComp($liste_comp_full["Dissimulation"],true)
			+ $PERSO->GetNiveauComp($liste_comp_full["Observation"],true) 
			- lanceDe(20)  ;	
		logDate("reussite".$reussite);	
		return $reussite;	
	}	

	function reussite_fouillerlieu($PERSO,$trouve_lieu,$trouve_objet,$trouve_pj) {
		global $liste_comp_full;
		$reussite = $trouve_lieu || $trouve_objet || $trouve_pj;
		if ($reussite)	{
			$PERSO->AugmenterComp($liste_comp_full["Dissimulation"],2);
			$PERSO->AugmenterComp($liste_comp_full["Observation"],1);
		} else {
			$PERSO->AugmenterComp($liste_comp_full["Observation"],1);
		}
		return $reussite;	
	}


	/*! 
	    \brief Pas de calcul de Reussite (automatique), augmente juste Aura et Charisme
	    \param $PERSO Le PJ qui donne de l'argent
	    \param $somme la quantit de Po donne
	*/
	function reussite_donner_argent($PERSO, $somme) {
		global $liste_comp_full;
		if($PERSO->ModPO(-$somme) ){
			$PERSO->AugmenterComp($liste_comp_full["Aura"],2);
			$PERSO->AugmenterComp($liste_comp_full["Charisme"],1);
			$reussite=true;
		}	
		else $reussite=false;
		logDate("reussite".$reussite);	
		return $reussite;	
	}	

	/*! 
	    \brief Pas de calcul de Reussite (automatique) si le receveur a suffisamment de place dans son sac, augmente juste Aura et Charisme
	    \param $DONNEUR Le PJ qui donne l'objet
	    \param $RECEVEUR Le PJ qui recoit l'objet
	    \param $Objet la l'objet qui change de propritaire
	*/
	function reussite_donner_objet($DONNEUR, $RECEVEUR, $Objet) {
		global $liste_comp_full;
		if ($RECEVEUR->sacPeutContenir($Objet->poids)) {
			$DONNEUR->AugmenterComp($liste_comp_full["Aura"],2);
			$DONNEUR->AugmenterComp($liste_comp_full["Charisme"],1);
			$reussite = true;
		} else {
			$DONNEUR->AugmenterComp($liste_comp_full["Aura"],1);
			$reussite = false;
		}	
		logDate("reussite".$reussite);	
		return $reussite;	
	}	


	/*! 
	    \brief Calcule la reussite de l'action se cacher
	    \param $PERSO Le PJ qui essaye de se cacher
	    Compare 2 competences avec un d20 et  Lieu->difficultedesecacher avec un d10	
	*/
	function reussite_secacher($PERSO) {
		global $liste_comp_full;

		$reussite = $PERSO->GetNiveauComp($liste_comp_full["Dissimulation"],true)
			+ $PERSO->GetNiveauComp($liste_comp_full["Observation"],true)
			+ lanceDe(10)
			- $PERSO->Lieu->difficultedesecacher
			- lanceDe(20);
		if ($reussite>0) {
			$PERSO->AugmenterComp($liste_comp_full["Dissimulation"],2);
			$PERSO->AugmenterComp($liste_comp_full["Observation"],1);
		}
		else {
			$PERSO->AugmenterComp($liste_comp_full["Dissimulation"],1);
			$PERSO->AugmenterComp($liste_comp_full["Observation"],1);
		}			
		logDate("reussite".$reussite);	
		return $reussite;	
	}	

	/*! 
	    \brief Calcule la reussite d'observation 
	    \param $PERSO Le PJ qui observe un aure PJ se cacher
	    \param $reussiteactionSeCacher La reussite de l'action se cacher du PJ cache
	    \warning surement quelque chose a modifier ici pour que ce soit moins facile	
	*/
	function reussite_Observersecacher($PERSO, $reussiteactionSeCacher) {
		global $liste_comp_full;
		$reussite = $PERSO->GetNiveauComp($liste_comp_full["Observation"],true)
			- $reussiteactionSeCacher;
		if ($reussite) 
			//PERSO a vu celui qui se dissimuler
			$PERSO->AugmenterComp($liste_comp_full["Observation"],2);		
		logDate("reussite".$reussite);	
		return $reussite;	
	}	


	/*! 
	    \brief Calcule la reussite de negociation lors d'un achat, d'une vente ou d'une reparation
	    \param $PERSO Le PJ qui negocie avec le marchand
	    \param $action est le sens de la transaction ("VENTE" => $PERSO vend, "ACHAT" => $PERSO achete ou recharge ou fait reparer)
	    \param $prixdebase Le prix stocke dans la BD pour l'objet de la transaction 	    
	    \warning suite  de nombreuses tricheries, les pourcentages de remises ont ete modifies: Un marchand vend toujours plus cher qu'il n'achete
	    Compare 2 competences avec d20
	*/
	function reussite_negociationprix($PERSO, $action, $prixdebase) {
		global $liste_comp_full;
		$reussite = $PERSO->GetNiveauComp($liste_comp_full["Charisme"],true)
			+ $PERSO->GetNiveauComp($liste_comp_full["Aura"],true) 
			- lanceDe(20);			
		logDate("reussite".$reussite);	
		if($reussite > 0){
				if($reussite > 5){
					$PERSO->AugmenterComp($liste_comp_full["Aura"],4);
					$PERSO->AugmenterComp($liste_comp_full["Charisme"],2);
					if ($action="VENTE")
						$prix =  round($prixdebase * 100 / 100);
					else 	$prix =  round($prixdebase * 100 / 100);
				} else {
					$PERSO->AugmenterComp($liste_comp_full["Aura"],2);
					$PERSO->AugmenterComp($liste_comp_full["Charisme"],1);
					if ($action="VENTE")
						$prix = round($prixdebase *90 / 100);
					else $prix = round($prixdebase *110 / 100);	
		} else {
				$PERSO->AugmenterComp($liste_comp_full["Aura"],1);
				if($reussite < -5){
					if ($action="VENTE")
						$prix = round($prixdebase * 70 / 100);
					else	$prix = round($prixdebase * 130 / 100);
				} else {
					if ($action="VENTE")
						$prix = round($prixdebase * 80 / 100);
					else $prix = round($prixdebase * 120 / 100);
				}
		}
		
		$prix = max ($prix,1);
		return $prix;	
	}	

	/*! 
	    \brief Calcule la reussite de l'action lire le livre \a $Livre par le perso \a $PERSO.
	    \param $PERSO Le lecteur
	    \param $Livre le livre que l'on lit
	    En cas de reussite, augmente les competences Alphabetisation, $Livre->caracteristique et $Livre->competence de $PERSO
	    \warning n'utilise que Intelligence et Alphabetisation pour le calcul de reussite et pas $Livre->competence, ce qui ne semble pas tres logique... 	    
	    Compare 2 competences avec d20 et $Livre->GetdifficulteUtilisation() avec d20
	*/
	function reussite_lire($PERSO,$Livre) {
		global $liste_comp_full;
		$reussite=$PERSO->GetNiveauComp($liste_comp_full["Intelligence"],true) 
			+ $PERSO->GetNiveauComp($liste_comp_full["Alphabetisation"],true)
			+ lanceDe(20)
			-($Livre->GetdifficulteUtilisation()+lanceDe(20));
		$degats = lanceDe($Livre->Degats[1]-$Livre->Degats[0])+$Livre->Degats[0];
		$degats = Max(1,$degats);			
		if($reussite > 0){
			$PERSO->AugmenterComp($liste_comp_full["Alphabetisation"],2);
			if($Livre->caracteristique != ""){
				$PERSO->AugmenterComp($liste_comp_full[$Livre->caracteristique],$degats);
			} else {
				if($Livre->competence != ""){
					$PERSO->AugmenterComp($liste_comp_full[$Livre->competence],$degats);
				} else {
					$PERSO->AugmenterComp($liste_comp_full["Sagesse"],$degats);
				}
			}
		}
		else
			$PERSO->AugmenterComp($liste_comp_full["Alphabetisation"],1);			
		logDate("reussite".$reussite);	
		return $reussite;	
	}

	/*! 
	    \brief Calcule la reussite de l'action fouiller le cadavre \a $CADAVRE  par le PJ \a $PERSO
	    \param $PERSO Celui qui fouille
	    \param $CADAVRE celui qui est mort
	    En cas de reussite, augmente les competences Observation, et Dexterite de $PERSO
	    Compare les competences Observation et Dexterite du fouilleur avec les competences Aura et Charisme du mort, et un d20 avec un autre d20
	*/
	function reussite_fouillercadavre($PERSO, $CADAVRE) {
		global $liste_comp_full;
		$reussite = ($PERSO->GetNiveauComp($liste_comp_full["Observation"],true)
			+ $PERSO->GetNiveauComp($liste_comp_full["Dexterite"],true)+lanceDe(20))
			-($CADAVRE->GetNiveauComp($liste_comp_full["Aura"],true)
				+$CADAVRE->GetNiveauComp($liste_comp_full["Charisme"],true)
				+lanceDe(20)
			);
		if($reussite > 0){
			$PERSO->AugmenterComp($liste_comp_full["Observation"],2);
			$PERSO->AugmenterComp($liste_comp_full["Dexterite"],1);
		}
		else 
			$PERSO->AugmenterComp($liste_comp_full["Observation"],1);						
		logDate("reussite".$reussite);	
		return $reussite;	
	}

	/*! 
	    \brief Calcule la reussite de l'action apprendre le sort \a $Sort par le PJ \a $PERSO
	    \param $PERSO Celui qui apprend
	    \param $Sort sort tudi
	    Compare Intelligence et d10, $Sort->GetdifficulteUtilisation() et d10,  $Sort->competence de $PERSO avec d10
	*/
	function reussite_apprentissageSort($PERSO, $Sort) {
		global $liste_comp_full;
			
		$reussite = $PERSO->GetNiveauComp($liste_comp_full["Intelligence"],true)
			- lanceDe(10) 
			- $Sort->GetdifficulteUtilisation()
			+ lanceDe(10) ;
		if (in_array ($Sort->competence, array_keys($liste_comp_full))) {
			$competence= $liste_comp_full[$Sort->competence];
			$reussite += $PERSO->GetNiveauComp($liste_comp_full[$Sort->competence],true) ;
			$reussite = $reussite - lanceDe(10);
		}	
		
		if ($reussite>0)
			$PERSO->AugmenterComp($liste_comp_full["Intelligence"],2);	
		logDate("reussite".$reussite);	
		return $reussite;	
	}
	
	function reussite_apprentissagecompetence($PERSO, $competence, $PAdepenses, $PIdepenses) {
		global $liste_comp_full;
		$reussite = ($PAdepenses+$PIdepenses ) ;
		if (in_array (competence, array_keys($liste_comp_full))) 
			$reussite =$reussite - (lanceDe((($PERSO->GetNiveauComp($liste_comp_full[$competence],true))+1)*10));

		logDate("reussite".$reussite);	
		return $reussite;	
	}

	/*! 
	    \brief Calcule la reussite de l'action nager par le PJ \a $PERSO pour emprunter le chemin d'ID \a $id_chemin
	    \param $PERSO Celui qui nage
	    \param $id_chemin chemin emprunt
	    Compare Nage et Dexterite et d20, Chemins[$id_chemin]->difficulte et d10		    
	*/
	function reussite_nage($PERSO,$id_chemin) {
		global $liste_comp_full;
		
		$reussite = $PERSO->GetNiveauComp($liste_comp_full["Nage"],true)
			+ $PERSO->GetNiveauComp($liste_comp_full["Dexterite"],true) 
			- lanceDe(20)
			- lanceDe($PERSO->Lieu->Chemins[$id_chemin]->difficulte)
			+ lanceDe(10);
		if($reussite > 0){
			$PERSO->AugmenterComp($liste_comp_full["Nage"],1);
			$PERSO->AugmenterComp($liste_comp_full["Dexterite"],1);
		} else {
			$PERSO->AugmenterComp($liste_comp_full["Nage"],1);
		}			

		logDate("reussite nage".$reussite);	
		return $reussite;	
	}

	/*! 
	    \brief Calcule la reussite de l'action escalader par le PJ \a $PERSO pour emprunter le chemin d'ID \a $id_chemin
	    \param $PERSO Celui qui escalade
	    \param $id_chemin chemin emprunt
	    Compare Nage et Dexterite et d20, Chemins[$id_chemin]->difficulte et d10
	*/
	function reussite_escalade($PERSO,$id_chemin) {

		global $liste_comp_full;
		
		$reussite = ($PERSO->GetNiveauComp($liste_comp_full["Escalade"],true)
			+ $PERSO->GetNiveauComp($liste_comp_full["Dexterite"],true) 
			- lanceDe(20)) 
			- (lanceDe($PERSO->Lieu->Chemins[$id_chemin]->difficulte))
			+ lanceDe(10) ;
		if($reussite > 0){
			$PERSO->AugmenterComp($liste_comp_full["Escalade"],1);
			$PERSO->AugmenterComp($liste_comp_full["Dexterite"],1);
		} else {
			$PERSO->AugmenterComp($liste_comp_full["Escalade"],1);
		}			
		logDate("reussite escalade".$reussite);	
		return $reussite;	
	}


	/*! 
	    \brief Calcule la reussite de l'action crocheter par le PJ \a $PERSO pour emprunter le chemin d'ID \a $id_chemin
	    \param $PERSO Celui qui crochete
	    \param $id_chemin chemin emprunt
	    Compare Nage et Dexterite et d20, Chemins[$id_chemin]->difficulte et d10
	*/
	function reussite_crochetage($PERSO, $id_chemin) {

		global $liste_comp_full;
		
		$reussite = ($PERSO->GetNiveauComp($liste_comp_full["Crochetage"],true)
			+ $PERSO->GetNiveauComp($liste_comp_full["Dexterite"],true) 
			- lanceDe(20)) 
			- (lanceDe($PERSO->Lieu->Chemins[$id_chemin]->difficulte)) 
			+ lanceDe(10);
		if($reussite > 0){
			$PERSO->AugmenterComp($liste_comp_full["Crochetage"],2);
			$PERSO->AugmenterComp($liste_comp_full["Dexterite"],1);
			if($reussite > 5){
				$PERSO->AugmenterComp($liste_comp_full["Crochetage"],2);
				$PERSO->AugmenterComp($liste_comp_full["Dexterite"],1);
			}
		} else {
			$PERSO->AugmenterComp($liste_comp_full["Crochetage"],1);
		}
		
		logDate("reussite".$reussite);	
		return $reussite;	
	}
	

	/*! 
	    \brief Calcule la reussite de l'utilisation d'un passe-partout.
	    utilise les comptences Crochetage et Dexterite du PJ
	    \param $PERSO Le PJ qui utilise le passe partout
	    \param $Objet le passe partout
	    \param $id_chemin l'ID du chemin 
	*/
	function reussite_passepartout($PERSO, $Objet, $id_chemin) {
		global $liste_comp_full;
		
		$reussite = ($PERSO->GetNiveauComp($liste_comp_full["Crochetage"],true)
			+ $PERSO->GetNiveauComp($liste_comp_full["Dexterite"],true) 
			+ lanceDe(20)
			+ $Objet->Degats[0] 
			+ lanceDe($Objet->Degats[1]-$Objet->Degats[0])) 
			- (lanceDe($PERSO->Lieu->Chemins[$id_chemin]->difficulte)) ;
		if($reussite > 0){
			$PERSO->AugmenterComp($liste_comp_full["Crochetage"],2);
			$PERSO->AugmenterComp($liste_comp_full["Dexterite"],1);
			if($reussite > 5){
				$PERSO->AugmenterComp($liste_comp_full["Crochetage"],2);
				$PERSO->AugmenterComp($liste_comp_full["Dexterite"],1);
			}
		} else {
			$PERSO->AugmenterComp($liste_comp_full["Crochetage"],1);
		}
			
		logDate("reussite".$reussite);	
		return $reussite;	
	}
	

	/*! 
	    \brief Calcule la reussite de l'action peage
	    \param $PJS un tableau de joueurs (pour les groupes)
	    \param $montantpassage  le montant en PO a payer pour passer
	    Pour chaque PJ, calcule le montant qu'il va devoir payer apres negociation.
	    Si chaque PJ a suffisement, le groupe passe. Sinon renvoie false.
	*/
	function reussite_peage($PJS, $montantpassage) {

		$reussite = true;
		$i=0;
		$montantApayer=array();
		$nbPJs=count($PJS);
		while ($i<$nbPJs && $reussite){
			$montantApayer[$i] = reussite_negociationprix($PJS[$i],"ACHAT",$montantpassage);
	    		if ($PJS[$i]->PO < $montantApayer[$i])
	    			$reussite=false;
	    		else $i++;
	    	}	
	    	if ($reussite)
	            	for($i=0;$i<$nbPJs;$i++)
	            		$PJS[$i]->ModPO(-$montantApayer[$i]);										

		logDate("reussite".$reussite);	
		return $reussite;	
	}

	/*! 
	    \brief Calcule la reussite de l'action aller
	    \param $PJS un tableau de joueurs (pour les groupes)
	    \param $difficulte  la difficulte du chemin
	    Compare la somme des moyennes de (Aura et Constitution) et d20, difficulte et d10
	*/
	function reussite_aller($PJS,$difficulte) {

		global $liste_comp_full;
		$i=0;
		$moy_competenceAura = 0;
		$moy_competenceConstit = 0;
		$nb_pnjs = count($PJS);
		while ($i<$nb_pnjs){
	    		$moy_competenceAura +=$PJS[$i]->GetNiveauComp($liste_comp_full["Aura"],true);
	    		$moy_competenceConstit +=$PJS[$i]->GetNiveauComp($liste_comp_full["Constitution"],true);
	    		$i++;
	    	}	
	    	$moy_competenceAura=$moy_competenceAura / $nb_pnjs;
	    	$moy_competenceConstit =$moy_competenceConstit /$nb_pnjs;
		$reussite = ($moy_competenceAura+ $moy_competenceConstit - lanceDe(10) )-(lanceDe($difficulte))+lanceDe(10);

            	$i=0;
            	while ($i<$nb_pnjs){
			if($reussite >= 0){
				$PJS[$i]->AugmenterComp($liste_comp_full["Aura"],4);
				$PJS[$i]->AugmenterComp($liste_comp_full["Constitution"],2);
				$PJS[$i]->AugmenterComp($liste_comp_full["Charisme"],1);
			} else {
				$PJS[$i]->AugmenterComp($liste_comp_full["Aura"],2);
			}
                        $i++;
             }   
				             		
		logDate("reussite".$reussite);	
		return $reussite;	
	}

	/*! 
	    \brief Calcule la reussite de l'action sort de teleportation
	    \param $LANCEUR le jeteur de sort
	    \param $CIBLE le PJ sur qui le sort lanc
	    \param $Sort le sort lanc
	    Compare les competences $Sort->caracteristique et $Sort->competence du lanceur et de la cible, $Sort->GetdifficulteUtilisation() et d10
	*/
	function reussite_sort_teleport($LANCEUR, $CIBLE,$Sort) {

		global $liste_comp_full;

		$cmp_att = 0;
		$cmp_def=0;
		if (in_array ($Sort->competence, array_keys($liste_comp_full))) {
			$competence = $liste_comp_full[$Sort->competence];
			$cmp_att+=$LANCEUR->GetNiveauComp($competence,true);
			$cmp_def+=$CIBLE->GetNiveauComp($competence,true);
		}	
		else $competence="";	

		if (in_array ($Sort->caracteristique, array_keys($liste_comp_full))) {
			$caracteristique = $liste_comp_full[$Sort->caracteristique];
			$cmp_att+=$LANCEUR->GetNiveauComp($caracteristique,true);
			$cmp_def+=$CIBLE->GetNiveauComp($caracteristique,true);
		}	
		else $caracteristique="";	
		$reussite = ($cmp_att + lanceDe(20)+ lanceDe($cmp_att)+ lanceDe(10))-($Sort->GetdifficulteUtilisation() + $cmp_def + lanceDe(20)+ lanceDe($cmp_def));
		if ($reussite>0) {
			if($reussite > 5){
				if ($competence!="")
					$LANCEUR->AugmenterComp($competence,4);
				if ($caracteristique!="")
					$LANCEUR->AugmenterComp($caracteristique,2);
			} else {
				if ($competence!="")
					$LANCEUR->AugmenterComp($competence,2);
				if ($caracteristique!="")
					$LANCEUR->AugmenterComp($caracteristique,1);
			}
		}
		else 
			if ($competence!="")
				$LANCEUR->AugmenterComp($competence,1);

		logDate("reussite".$reussite);	
		return $reussite;	
	}



	/*! 
	    \brief Calcule la reussite de l'action sort d'attaque
	    \param $LANCEUR le jeteur de sort
	    \param $CIBLE le PJ sur qui le sort lanc
	    \param $Sort le sort lanc
	    Compare les competences $Sort->caracteristique et $Sort->competence du lanceur et de la cible, $Sort->GetdifficulteUtilisation() et d10
	*/
	function reussite_sort_attaque($LANCEUR, $CIBLE,$Sort) {
		global $liste_comp_full;

		$cmp_att = 0;
		$cmp_def=0;
		if (in_array ($Sort->competence, array_keys($liste_comp_full))) {
			$competence = $liste_comp_full[$Sort->competence];
			$cmp_att+=$LANCEUR->GetNiveauComp($competence,true);
			$cmp_def+=$CIBLE->GetNiveauComp($competence,true);
		}	
		else $competence="";	

		if (in_array ($Sort->caracteristique, array_keys($liste_comp_full))) {
			$caracteristique = $liste_comp_full[$Sort->caracteristique];
			$cmp_att+=$LANCEUR->GetNiveauComp($caracteristique,true);
			$cmp_def+=$CIBLE->GetNiveauComp($caracteristique,true);
		}	
		else $caracteristique="";	
		$reussite = ($cmp_att + lanceDe(20)+ lanceDe(10)+ lanceDe($cmp_att))-($Sort->GetdifficulteUtilisation() + $cmp_def + lanceDe(20)+ lanceDe($cmp_def));
		if ($reussite>0) {
			if($reussite > 5){
				if ($competence!="")
					$LANCEUR->AugmenterComp($competence,4);
				if ($caracteristique!="")
					$LANCEUR->AugmenterComp($caracteristique,2);
			} else {
				if ($competence!="")
					$LANCEUR->AugmenterComp($competence,2);
				if ($caracteristique!="")
					$LANCEUR->AugmenterComp($caracteristique,1);
			}
		}
		else 
			if ($competence!="")
				$LANCEUR->AugmenterComp($competence,1);
		logDate("reussite".$reussite);	
		return $reussite;	
	}


	function reussite_sort_resurrection($LANCEUR, $CIBLE,$Sort) {
		global $liste_comp_full;

		$cmp_att = 0;
		$cmp_def=0;
		if (in_array ($Sort->competence, array_keys($liste_comp_full))) {
			$competence = $liste_comp_full[$Sort->competence];
			$cmp_att+=$LANCEUR->GetNiveauComp($competence,true);
			$cmp_def+=$CIBLE->GetNiveauComp($competence,true);
		}	
		else $competence="";	

		if (in_array ($Sort->caracteristique, array_keys($liste_comp_full))) {
			$caracteristique = $liste_comp_full[$Sort->caracteristique];
			$cmp_att+=$LANCEUR->GetNiveauComp($caracteristique,true);
			$cmp_def+=$CIBLE->GetNiveauComp($caracteristique,true);
		}	
		else $caracteristique="";	
		$reussite = ($cmp_att+$cmp_def + lanceDe(20)+ lanceDe($cmp_att)+lanceDe($cmp_def))-($Sort->GetdifficulteUtilisation() + lanceDe(40)+lanceDe(40));

		if ($reussite>0) {
			if($reussite > 5){
				if ($competence!="")
					$LANCEUR->AugmenterComp($competence,4);
				if ($caracteristique!="")
					$LANCEUR->AugmenterComp($caracteristique,2);
			} else {
				if ($competence!="")
					$LANCEUR->AugmenterComp($competence,2);
				if ($caracteristique!="")
					$LANCEUR->AugmenterComp($caracteristique,1);
			}
		}
		else 
			if ($competence!="")
				$LANCEUR->AugmenterComp($competence,1);

		logDate("reussite resurrection".$reussite);	
		return $reussite;	
	}




	function reussite_sort_soin($LANCEUR, $CIBLE,$Sort) {
		global $liste_comp_full;

		$cmp_att = 0;
		$cmp_def=0;
		if (in_array ($Sort->competence, array_keys($liste_comp_full))) {
			$competence = $liste_comp_full[$Sort->competence];
			$cmp_att+=$LANCEUR->GetNiveauComp($competence,true);
			$cmp_def+=$CIBLE->GetNiveauComp($competence,true);
		}	
		else $competence="";	

		if (in_array ($Sort->caracteristique, array_keys($liste_comp_full))) {
			$caracteristique = $liste_comp_full[$Sort->caracteristique];
			$cmp_att+=$LANCEUR->GetNiveauComp($caracteristique,true);
			$cmp_def+=$CIBLE->GetNiveauComp($caracteristique,true);
		}	
		else $caracteristique="";	
		$reussite = ($cmp_att+$cmp_def + lanceDe(20)+ lanceDe($cmp_att)+lanceDe($cmp_def))-($Sort->GetdifficulteUtilisation() + lanceDe(40)+lanceDe(40));

		if ($reussite>0) {
			if($reussite > 5){
				if ($competence!="")
					$LANCEUR->AugmenterComp($competence,4);
				if ($caracteristique!="")
					$LANCEUR->AugmenterComp($caracteristique,2);
			} else {
				if ($competence!="")
					$LANCEUR->AugmenterComp($competence,2);
				if ($caracteristique!="")
					$LANCEUR->AugmenterComp($caracteristique,1);
			}
		}
		else 
			if ($competence!="")
				$LANCEUR->AugmenterComp($competence,1);

		logDate("reussite".$reussite);	
		return $reussite;	
	}

	function reussite_sort_autoteleport($LANCEUR,$Sort) {
		global $liste_comp_full;

		$cmp_att = 0;
		$cmp_def=0;
		if (in_array ($Sort->competence, array_keys($liste_comp_full))) {
			$competence = $liste_comp_full[$Sort->competence];
			$cmp_att+=$LANCEUR->GetNiveauComp($competence,true);
			$cmp_def+=lanceDe(10);
		}	
		else $competence="";	

		if (in_array ($Sort->caracteristique, array_keys($liste_comp_full))) {
			$caracteristique = $liste_comp_full[$Sort->caracteristique];
			$cmp_att+=$LANCEUR->GetNiveauComp($caracteristique,true);
			$cmp_def+=lanceDe(10);
		}	
		else $caracteristique="";	

		$reussite = ($cmp_att+ lanceDe(20)+ lanceDe(10)+ lanceDe($cmp_att))
			-($cmp_def +$Sort->GetdifficulteUtilisation() + lanceDe(20)+ lanceDe($cmp_def));
		if ($reussite>0) {
			if($reussite > 5){
				if ($competence!="")
					$LANCEUR->AugmenterComp($competence,4);
				if ($caracteristique!="")
					$LANCEUR->AugmenterComp($caracteristique,2);
			} else {
				if ($competence!="")
					$LANCEUR->AugmenterComp($competence,2);
				if ($caracteristique!="")
					$LANCEUR->AugmenterComp($caracteristique,1);
			}
		}
		else 
			if ($competence!="")
				$LANCEUR->AugmenterComp($competence,1);
		logDate("reussite".$reussite);	
		return $reussite;	
	}

	/*! 
	    \brief Calcule la reussite d'un vol d'une bourse
	    utilise les comptences Vol et Dexterite du voleur
	    et Vigilance et  Dexterite du vol
	    \param $VOLEUR Le voleur
	    \param $VOLE celui qui risque de se faire voler
	*/
	function reussite_voler_or($VOLEUR, $VOLE) {
		global $liste_comp_full;
		$reussite = ($VOLEUR->GetNiveauComp($liste_comp_full["Vol"],true)
			+$VOLEUR->GetNiveauComp($liste_comp_full["Dexterite"],true)
			+lanceDe(20)) ;
		if (! $VOLE->RIP()) 				
			$reussite = $reussite 
				- ($VOLE->GetNiveauComp($liste_comp_full["Vigilance"],true)
				+$VOLE->GetNiveauComp($liste_comp_full["Dexterite"],true)
				+lanceDe(20)
			);

		if($reussite > 0){
			$VOLEUR->AugmenterComp($liste_comp_full["Vol"],2);
			$VOLEUR->AugmenterComp($liste_comp_full["Dexterite"],1);
			if($reussite > 5){
				$VOLEUR->AugmenterComp($liste_comp_full["Vol"],2);
				$VOLEUR->AugmenterComp($liste_comp_full["Dexterite"],1);
			}
		}
		else {
			$VOLE->AugmenterComp($liste_comp_full["Vigilance"],2);			
			$VOLEUR->AugmenterComp($liste_comp_full["Vol"],1);	
			if($reussite <=- 5)
				$VOLE->AugmenterComp($liste_comp_full["Vigilance"],2);			
		}	

		logDate("reussite".$reussite);		
		return $reussite;	
	}	

	function reussite_attaquer($ATTAQUANT, $ATTAQUE, $Arme, $bonusmalus="") {
		global $liste_comp_full;
		global $db;
		
		if ($bonusmalus=="")
			$bonusmalusChiffre = 0;
		else 
		if ($bonusmalus=="MALUS_ATTAQUE1_ATTAQUESENCHAINEES")
			$bonusmalusChiffre = MALUS_ATTAQUE1_LORS_PLUSIEURS_ATTAQUES
				+ max($ATTAQUANT->GetNiveauComp($liste_comp_full["Combat2Armes"],true)/10, -$bonusmalusChiffre);
		else 
		if ($bonusmalus=="MALUS_ATTAQUE2_ATTAQUESENCHAINEES") {
			$bonusmalusChiffre =MALUS_ATTAQUE1_LORS_PLUSIEURS_ATTAQUES 
				+MALUS_ATTAQUE2_LORS_PLUSIEURS_ATTAQUES;
			$bonusmalusChiffre = $bonusmalusChiffre 
				+ max($ATTAQUANT->GetNiveauComp($liste_comp_full["Combat2Armes"],true)/2, -$bonusmalusChiffre);
		}			
		
		$cmp_att = $bonusmalusChiffre;
		$cmp_def=0;
		if ( $ATTAQUANT->dissimule) { 
			$SQL= $ATTAQUE->listePJsDuLieuDuPerso(1, 0, 1,1);
      
			$requete=$db->sql_query($SQL);
			$trouve=false;
			while(($row = $db->sql_fetchrow($requete)) && ($trouve==false) ){
				if ($row["idselect"] == $ATTAQUANT->ID)
					$trouve=true;						
					//attaquant est cache mais attaqu le sait => pas de bonus
			}
			$attaquantdissimuleAvantAttaque = (!($trouve));
		}	
		else $attaquantdissimuleAvantAttaque=false;
		if (in_array ($Arme->competence, array_keys($liste_comp_full))) {
			$competence = $liste_comp_full[$Arme->competence];
			$cmp_att+=$ATTAQUANT->GetNiveauComp($competence,true);
			$cmp_def+= $ATTAQUE->GetNiveauComp($competence,true);
		}	
		else $competence="";	

		if (in_array ($Arme->caracteristique, array_keys($liste_comp_full))) {
			$caracteristique = $liste_comp_full[$Arme->caracteristique];
			$cmp_att+=$ATTAQUANT->GetNiveauComp($caracteristique,true);
			$cmp_def+= $ATTAQUE->GetNiveauComp($caracteristique,true);
		}	
		else $caracteristique="";	
		
		if ($attaquantdissimuleAvantAttaque) {
			$cmp_att+=$ATTAQUANT->GetNiveauComp("AttaqueSournoise",true);
		}	

		$sc_att = $cmp_att+lancede($cmp_att)+lancede(20)+lancede(10);
		$sc_def = $cmp_def+lancede($cmp_def)+lancede(20)+$Arme->GetdifficulteUtilisation();
		$reussite = $sc_att - $sc_def;
		if($reussite > 0){
			if($reussite > 5){
				if ($bonusmalus=="MALUS_ATTAQUE2_ATTAQUESENCHAINEES")
					$ATTAQUANT->AugmenterComp("Combat2Armes",2);
				if ($attaquantdissimuleAvantAttaque) 
					$ATTAQUANT->AugmenterComp("AttaqueSournoise",2);				
				if ($competence!="")
					$ATTAQUANT->AugmenterComp($competence,4);
				if ($caracteristique!="")
					$ATTAQUANT->AugmenterComp($caracteristique,2);
			} else {
				if ($bonusmalus=="MALUS_ATTAQUE2_ATTAQUESENCHAINEES")
					$ATTAQUANT->AugmenterComp("Combat2Armes",1);
				if ($attaquantdissimuleAvantAttaque) 
					$ATTAQUANT->AugmenterComp("AttaqueSournoise",1);				
				if ($competence!="")
					$ATTAQUANT->AugmenterComp($competence,2);
				if ($caracteristique!="")
					$ATTAQUANT->AugmenterComp($caracteristique,1);
			}
		}
		else {
			if ($competence!="")
				$ATTAQUANT->AugmenterComp($competence,1);
		}	
		logDate("reussite".$reussite);		
		return $reussite;	
	}	


	function reussite_soinobjet($SOIGNEUR, $SOIGNE, $PlanMed) {
		global $liste_comp_full;
		$cmp_att=0;
		if (in_array ($PlanMed->competence, array_keys($liste_comp_full))) {
			$competence = $liste_comp_full[$PlanMed->competence];
			$cmp_att+=$SOIGNEUR->GetNiveauComp($competence,true);
		}	
		else $competence="";	
		if (in_array ($PlanMed->caracteristique, array_keys($liste_comp_full))) {
			$caracteristique = $liste_comp_full[$PlanMed->caracteristique];
			$cmp_att+=$SOIGNEUR->GetNiveauComp($caracteristique,true);
		}	
		else $caracteristique="";	

		$reussite = ($cmp_att + lanceDe(20)+ lanceDe($cmp_att))
			- ($PlanMed->GetdifficulteUtilisation() + lanceDe(40)+lanceDe(40));
		if($reussite > 0){ // Touch
			if($reussite > 5){
				if ($competence!="")
					$SOIGNEUR->AugmenterComp($competence,4);
				if ($caracteristique!="")
					$SOIGNEUR->AugmenterComp($caracteristique,2);
			} else {
				if ($competence!="")
					$SOIGNEUR->AugmenterComp($competence,2);
				if ($caracteristique!="")
					$SOIGNEUR->AugmenterComp($caracteristique,1);
			}
		}
		else	if ($competence!="")
				$SOIGNEUR->AugmenterComp($competence,1);
		return $reussite;				
	}	





	function soin_vegetal($SOIGNEUR,$id_blesse,$id_obj) {
	// Dbut soin vgtal par Uriel
		global $liste_pas_actions;
		global $liste_pis_actions;
		global $liste_comp_full;
		global $template_main;
		global $liste_flags_lieux;

	    if( (isset($id_blesse)) && (isset($id_obj)) && $SOIGNEUR->Lieu->permet($liste_flags_lieux["SoignerAvecObjet"]) && ($SOIGNEUR->ModPA($liste_pas_actions["SoinObjet"])) && ($SOIGNEUR->ModPI($liste_pis_actions["SoinObjet"]))){

		$riposteGroupe_autorisee=false;
		if ($id_blesse == $SOIGNEUR->ID)
			$BLESSE=$SOIGNEUR;
		else $BLESSE = new Joueur($id_blesse,true,true,true,true,true,true);

		$PlanMed = null;
		$i=0;
                $nbObjSoigneur = count($SOIGNEUR->Objets);
		while ($PlanMed == null && $i<$nbObjSoigneur) {
			if($SOIGNEUR->Objets[$i]->id_clef == $id_obj)
				 $PlanMed = $SOIGNEUR->Objets[$i];
			else $i++;
		}

		if ($PlanMed==null)  {
			logDate("PlanteMedicinale==null dans actions/SoinVegetal");
			$template_main.=  GetMessage("noparam");
			return;
		}		

		if ($SOIGNEUR->peutUtiliserObjet($PlanMed)) {
			$valeurs[1]=$SOIGNEUR->nom;
			$valeurs[0]=$PlanMed->nom;
			$valeurs[2]=$BLESSE->nom;
			if ($PlanMed->type == "Soins")
			        $valeurs[8]=$BLESSE->getPVMax();
			else if ($PlanMed->type == "SoinsPI")
			        $valeurs[8]=$BLESSE->getPIMax();			        
	
	                //reussite automatique=> c'est la confection de l'objet soin qui sera soumis a une reussite
			//$reussite = reussite_soinobjet($SOIGNEUR, $BLESSE, $PlanMed);
			$reussite=1;
			
			$degats = lancede($PlanMed->Degats[1]-$PlanMed->Degats[0])+$PlanMed->Degats[0];
			//if (($reussite > 5) || ($reussite < -5) ){$degats*=2;}
				
			if($reussite > 0){ // Touch
				if ($PlanMed->type == "Soins") {
        				$msg_final = "soinobjetPV_01";
        				$msg_final_adv = "soinobjetPV_adv01";
        				$BLESSE->ModPV($degats);
                                }
				else if ($PlanMed->type == "SoinsPI") {
        				$msg_final = "soinobjetPI_01";
        				$msg_final_adv = "soinobjetPI_adv01";
        				$BLESSE->ModPI($degats);
                                }                                				
				$valeurs[3]=0;$valeurs[4]=$degats;$valeurs[5]=0;
				$retour = $BLESSE->GererChaineEtatTemp($PlanMed->provoqueetat);
				$valeurs[6] = $retour["ajouter"];$valeurs[7] = $retour["retirer"];
        		        traceAction("SoinObjet", $PERSO, "", $BLESSE, $degats, " avec ".$PlanMed->nom);				
			} else { // rat
	
				$msg_final = "soinobjet_02";
				$msg_final_adv = "soinobjet_adv02";
				$valeurs[3]=0;$valeurs[4]=0;$valeurs[5]=0;
				$valeurs[6]=0;$valeurs[7]=0;$valeurs[8]=0;
			}
	
	
			$SOIGNEUR->OutPut(GetMessage($msg_final,$valeurs));
			$BLESSE->OutPut(GetMessage($msg_final,$valeurs),false,true);
	
			$SOIGNEUR->EffacerObjet($id_obj);
		}
		else {
			$valeurs=array();
			$valeurs[0]= $PlanMed->nom;
			$valeurs[1]= $PlanMed->EtatTempSpecifique->nom;
			$SOIGNEUR->OutPut(GetMessage("objet_inutilisable",$valeurs));
		}
		
	    } else {
		if(!isset($id_obj)){
			$template_main.=  GetMessage("noparam");
		} else {
			if ($SOIGNEUR->RIP())
				$template_main.=  GetMessage("nopvs");
			else	
			if ($SOIGNEUR->Archive)
				$template_main.=  GetMessage("archive");
			else
			if ($SOIGNEUR->Lieu->permet($liste_flags_lieux["SoignerAvecObjet"]))
			        $template_main.=  GetMessage("noright");
			else 
			$template_main.=  GetMessage("nopas");
		}
	    }
        // Fin Soin Vegetal par Uriel
	}

	/*! 
	    \brief Calcule la reussite d'un desengagement par la force
	    utilise les comptences Force des 2 protagonistes
	    \param $PERSO celui qui tente le desengagement
	    \param $ADVERSAIRE son adversaire
	*/
	function reussite_desengagment_force($PERSO,$ADVERSAIRE) {
		global $liste_comp_full;
		$des=( $PERSO->GetNiveauComp($liste_comp_full["Force"],true)+LanceDe(20) ) 
			- ( $ADVERSAIRE->GetNiveauComp($liste_comp_full["Force"],true)+LanceDe(20) );
		return $des;
	}		
	

	/*! 
	    \brief Calcule la reussite d'un desengagement par la dextrit
	    utilise les comptences Dexterite des 2 protagonistes
	    \param $PERSO celui qui tente le desengagement
	    \param $ADVERSAIRE son adversaire
	*/
	function reussite_desengagment_dexte($PERSO,$ADVERSAIRE) {
		global $liste_comp_full;	
		$des=( $PERSO->GetNiveauComp($liste_comp_full["Dexterite"],true)+LanceDe(20) ) 
			- ( $ADVERSAIRE->GetNiveauComp($liste_comp_full["Dexterite"],true)+LanceDe(20) );
		return $des;
	}		
		
	/*! 
	    \brief Calcule la reussite d'un desengagement par la ruse
	    utilise les comptences Intelligence des 2 protagonistes
	    \param $PERSO celui qui tente le desengagement
	    \param $ADVERSAIRE son adversaire
	*/		
	function reussite_desengagment_ruse($PERSO,$ADVERSAIRE) {
		global $liste_comp_full;	
		$des=( $PERSO->GetNiveauComp($liste_comp_full["Intelligence"],true)+LanceDe(20) ) 
			- ( $ADVERSAIRE->GetNiveauComp($liste_comp_full["Intelligence"],true)+LanceDe(20) );
		return $des;
	}		

	function Engagement($ATTAQUANT,$ADVERSAIRE){
		global $db;
		$SQL = "SELECT id_adversaire, propdes FROM ".NOM_TABLE_ENGAGEMENT."  WHERE id_perso = ".$ATTAQUANT->ID;
		$result = $db->sql_query($SQL);
		$nb_engagement=$db->sql_numrows($result);
		$trouve=false;
		$proposition=0;
		while(($row = $db->sql_fetchrow($result)) && (!$trouve)){
			logdate("row".$row["id_adversaire"]."///". $ADVERSAIRE->ID);
			if ($row["id_adversaire"]==  $ADVERSAIRE->ID) {				
				$trouve=true;
				$proposition = $row["propdes"];
			}				
		}
		if ($trouve) {
			//deja engage avec le meme
			if ($proposition==1) {
				//RAZ de la proposition de degagement (elle est annulee par l'attaque de celui qui avait propose)
				$SQL = "update ".NOM_TABLE_ENGAGEMENT." set propdes=0 WHERE (id_perso = ".$ATTAQUANT->ID." AND id_adversaire = ".$ADVERSAIRE->ID.") OR (id_adversaire = ".$ATTAQUANT->ID." AND id_perso = ".$ADVERSAIRE->ID.")";
				$requete=$db->sql_query($SQL);
			}
		}
		else {
			$SQL= "INSERT INTO ".NOM_TABLE_ENGAGEMENT." (id_perso,id_adversaire,nom) VALUES ('".$ATTAQUANT->ID."','".$ADVERSAIRE->ID."','".$ADVERSAIRE->nom."')";
			if (($requete=$db->sql_query($SQL))!==false) {
				$SQL= "INSERT INTO ".NOM_TABLE_ENGAGEMENT." (id_perso,id_adversaire,nom) VALUES ('".$ADVERSAIRE->ID."','".$ATTAQUANT->ID."','".$ATTAQUANT->nom."')";
				$requete=$db->sql_query($SQL);
			}	
			if ($nb_engagement==0){
				//$SQL= "UPDATE ".NOM_TABLE_PERSO." SET engagement = '1' WHERE id_perso = ".$ATTAQUANT->ID;
				$ATTAQUANT->Engagement==1;
				//$requete=$db->sql_query($SQL);
			}	
			if ($ADVERSAIRE->Engagement ==0) {
				$ADVERSAIRE->Engagement==1;
				//$SQL= "UPDATE ".NOM_TABLE_PERSO." SET engagement = '1' WHERE id_perso = ".$ADVERSAIRE->ID;
				//$db->sql_query($SQL);
			}	
		}		
		
	}	

	function Desengagement($ACTEUR,$ADVERSAIRE, $typeact, $riposte=false){
		global $db;
		global $liste_pas_actions;
		global $liste_pis_actions;
		global $template_main;
		if ($ACTEUR->ModPA($liste_pas_actions["Attaquer"]) && $ACTEUR->ModPA($liste_pis_actions["Attaquer"])) {
			//$ADVERSAIRE = new Joueur($id_cible,true,true,true,true,true,true);
			$valeurs[0]=$ACTEUR->nom;
			$valeurs[1]=$ADVERSAIRE->nom;
			$ok = false;
			if(isset($typeact)){
				switch($typeact){
					case 'force':{if( (isset($req)) ){$ok=true;} break;}
					case 'dexte':{if( (isset($req)) ){$ok=true;} break;}
					case 'ruse':{if( (isset($req)) ){$ok=true;} break;}
					case 'propdes':{if( (isset($req)) ){$ok=true;} break;}
					case 'mort':{if( (isset($req)) ){$ok=true;} break;}
				}
			}
	
			if($typeact == 'force'){
				//$toto = array_keys($id_adversaire);
				$des=reussite_desengagment_force($ACTEUR,$ADVERSAIRE);
			}

		
			if($typeact == 'dexte'){
				//$toto = array_keys($ADVERSAIRE);
				$des=reussite_desengagment_dexte($ACTEUR,$ADVERSAIRE);
			}
		
			if($typeact == 'ruse'){
				//$toto = array_keys($ADVERSAIRE);
				$des=reussite_desengagment_ruse($ACTEUR,$ADVERSAIRE);
			}
				
			if($typeact == "propdes"){
				$SQL = "SELECT * FROM ".NOM_TABLE_ENGAGEMENT." WHERE id_perso = ".$ADVERSAIRE->ID." AND id_adversaire = ".$ACTEUR->ID;
				$result = $db->sql_query($SQL);
				$row = $db->sql_fetchrow($result);		
				$des = $row["propdes"];
			}	
				
			if($typeact == 'mort'){
				//$toto = array_keys($id_adversaire);
				if ($ADVERSAIRE->PV <= 0){
					$requete = $ACTEUR->SeDesengager($ADVERSAIRE);					
					if ($requete!==false) {				
						$des=1;
					}
					else $des=0;	
				}else {
					$des=0;
					//$ACTEUR->OutPut(GetMessage("des_mort_non",$valeurs));					
				}
			
			}
			if($des<=0) {
				if($typeact == 'mort') {
					//$ACTEUR->OutPut(GetMessage("des_mort_non",$valeurs));
					$msg_acteur = GetMessage("des_mort_non",$valeurs);
					$msg_spect = "";
					$msg_cible="";
				}	
				elseif($typeact == "propdes") {	
					$SQL = "update ".NOM_TABLE_ENGAGEMENT." set propdes=1 WHERE (id_perso = ".$ACTEUR->ID." AND id_adversaire = ".$ADVERSAIRE->ID.")";
					$result = $db->);
					//$ACTEUR->OutPut(GetMessage("propdes",$valeurs));
					//$ADVERSAIRE->OutPut(GetMessage("propdes_adv",$valeurs),false);
					$msg_acteur = GetMessage("propdes",$valeurs);
					$msg_cible = GetMessage("propdes_adv",$valeurs);
					$msg_spect =GetMessage("propdes_spec",$valeurs);

				}
				else {
					//$ACTEUR->OutPut(GetMessage("des_rater",$valeurs));
					//$ADVERSAIRE->OutPut(GetMessage("des_rater_adv",$valeurs),false);
					$msg_acteur = GetMessage("des_rater",$valeurs);
					$msg_cible = GetMessage("des_rater_adv",$valeurs);
					$msg_spect =GetMessage("des_rater_spec",$valeurs);
				}
			}	
			else {
				$requete = $ACTEUR->SeDesengager($ADVERSAIRE);
				if ($requete!==false) {
					if($typeact == 'mort') {
						//$ACTEUR->OutPut(GetMessage("des_mort",$valeurs));
						//$ADVERSAIRE->OutPut(GetMessage("des_mort_adv",$valeurs),false);
						$msg_acteur = GetMessage("des_mort",$valeurs);
						$msg_cible = GetMessage("des_mort_adv",$valeurs);
						$msg_spect ="";
					}
					elseif($typeact == "propdes") {	
						$msg_acteur = GetMessage("propdes_acc",$valeurs);
						$msg_cible = GetMessage("propdes_acc_adv",$valeurs);
						$msg_spect = GetMessage("propdes_acc_spec",$valeurs);
						//$ACTEUR->OutPut(GetMessage("propdes_acc",$valeurs));
						//$ADVERSAIRE->OutPut(GetMessage("propdes_acc_adv",$valeurs),false);
					}
					else {
	
						//$ACTEUR->OutPut(GetMessage("des_reussi",$valeurs));
						//$ADVERSAIRE->OutPut(GetMessage("des_reussi_adv",$valeurs),false);
						$msg_acteur = GetMessage("des_reussi",$valeurs);
						$msg_cible = GetMessage("des_reussi_adv",$valeurs);
						$msg_spect = "***** Vous voyez ".span($ACTEUR->nom,"pj")." s'esquiver de son combat avec ".span($ADVERSAIRE->nom,"pj")." et fuir. ****** <br />";
						/*$SQL = "SELECT * FROM ".NOM_TABLE_REGISTRE." WHERE ID_Lieu = '".$ACTEUR->Lieu->ID."' AND id_perso <> '".$ACTEUR->ID."' AND id_perso <> '".$ADVERSAIRE->ID."'  ";
						$result = $db->sql_query($SQL);
						$msg_cible = "***** Vous voyez ".span($ACTEUR->nom,"pj")." s'esquiver de son combat avec ".span($ADVERSAIRE->nom,"pj")." et fuir. ****** <br />";
						$chaine = '';
						$i=0;
						$nb_joueur=$db->sql_numrows($result);
						while($row = $db->sql_fetchrow($result)){
							$pjtemp = new Joueur($row["id_perso"],false,false,false,false,false,false);
							$pjtemp->OutPut($msg_cible,false,true);
							$chaine .= $pjtemp->nom;
							$i++;
							if($i != ($nb_joueur-1) ){$chaine .= ", ";}		
						}*/
					}			
				}
			}	
			if (!$riposte)	
				affiche_resultat($ACTEUR,$ADVERSAIRE,$msg_acteur,$msg_cible,$msg_spect,true);
			else 
				affiche_resultat($ADVERSAIRE,$ACTEUR,GetMessage("riposteGroupe",$valeurs).$msg_cible,GetMessage("riposteGroupe",$valeurs).$msg_acteur,GetMessage("riposteGroupe",$valeurs).$msg_spect,false);

		}else{
			if ($riposte)
				$template_main.=  GetMessage("nopas");
		}
	}

	/*! 
	    \brief Calcule la reussite de l'action combiner objet
	    \param $PERSO le PJ qui veut crer
	    \param $objet l'objet  crer
	    Compare les competences Intelligence et d10, $objet->GetDifficulteUtilisation() et d10
	*/
	function reussite_combiner_objet($PERSO,$objet) {
		
		global $liste_comp_full;
		global $liste_type_objs;
		$tmp=array($objet->type,$objet->Soustype);
		$tmp=implode(";",$tmp);
		$compuse= $liste_type_objs[$tmp][4];
		
		$reussite=$PERSO->GetNiveauComp($liste_comp_full["Intelligence"],true) 
			+ lanceDe(10)
			-($objet->GetDifficulteUtilisation()+lanceDe(10));
		if ($compuse<>"") {
			$reussite+=$PERSO->GetNiveauComp($liste_comp_full[$compuse],true);
	
			if($reussite > 0){
				$PERSO->AugmenterComp($liste_comp_full[$compuse],2);
			}
			else
				$PERSO->AugmenterComp($liste_comp_full[$compuse],1);			
		}	
		logDate("reussite".$reussite);	
		return $reussite;	
		
	}


	/*! 
	    retourne $reussite qui est la quantite recoltee
	    \brief Calcule la reussite de l'action recolte
	    \param $RECOLTANT le PJ qui veut recolter
	    \param $competence la comptence utilise pour le calcul de la russite de l'action
	    \param $Objet l'objet  recolter (de la classe ObjetMagasin et non pas Objet)
	    \param $Outil l'objet  utiliser (si besoin)
	*/		
	function reussite_recolte($RECOLTANT,$competence, $Objet,$Outil=null){
		if ($Objet->quantite>0 || $Objet->stockmax==-1) {
			global $liste_comp_full;
			if (in_array ($competence, array_keys($liste_comp_full))) {
				$competence = $liste_comp_full[$competence];
				$reussite=$RECOLTANT->GetNiveauComp($competence,true)- lanceDe(10);
				if ($reussite <0)
					$reussite+= lanceDe(5);
			}	
			else $reussite=0;
			
			/**
			   stockmax sert pour la raret de l'objet recherch.
			*/
			if ($Objet->stockmax <0)
				$reussite += lanceDe(10);
			else 	$reussite += lanceDe(10)- ($Objet->stockmax - $Objet->quantite);
			
	
			if ($Outil!=null) {
				$reussite = $reussite - $Outil->GetDifficulteUtilisation()+lanceDe(10);
				if (in_array ($Outil->caracteristique, array_keys($liste_comp_full))) {
					$caracteristique = $liste_comp_full[$Outil->caracteristique];
					$reussite+=$RECOLTANT->GetNiveauComp($caracteristique,true)- lanceDe(10);
					if ($reussite <0)
						$reussite+= lanceDe(5);
				}	
				else $caracteristique="";	
			}
			else {
				$competence="";	
				$caracteristique="";	
			}	
				
			if($reussite > 0){ 
				if($reussite > 5){
					if ($competence!="")
						$RECOLTANT->AugmenterComp($competence,4);
					if ($caracteristique!="")
						$RECOLTANT->AugmenterComp($caracteristique,2);
				} else {
					if ($competence!="")
						$RECOLTANT->AugmenterComp($competence,2);
					if ($caracteristique!="")
						$RECOLTANT->AugmenterComp($caracteristique,1);
				}
			}
			else	{
				if ($competence!="")
					$RECOLTANT->AugmenterComp($competence,1);
				if ($Outil!=null) 
					$Outil->Abime();	
			}	
		}
		else $reussite = -1;	
		logDate	("reussite recolte" . $reussite);
		return $reussite;				
	}	


	/*! 
	    \brief Calcule la reussite de l'action 
	    \param[in] $ARTISAN le PJ qui veut rparer
	    \param[in] $Outil l'outil utilis
	    \return $reussite qui est un nombre >0 si action est reussie
	    
	*/		
	function reussite_reparation($ARTISAN, $Outil) {

		global $liste_comp_full;
		$cmp_att=0;
		if (in_array ($Outil->competence, array_keys($liste_comp_full))) {
			$competence = $liste_comp_full[$Outil->competence];
			$cmp_att+=$ARTISAN->GetNiveauComp($competence,true)-lanceDe(10);
			if ($cmp_att <0)
				$cmp_att+= lanceDe(5);

		}	
		else $competence="";	
		if (in_array ($Outil->caracteristique, array_keys($liste_comp_full))) {
			$caracteristique = $liste_comp_full[$Outil->caracteristique];
			$cmp_att+=$ARTISAN->GetNiveauComp($caracteristique,true)-lanceDe(10);
			if ($cmp_att <0)
				$cmp_att+= lanceDe(5);
		}	
		else $caracteristique="";	

		$reussite = $cmp_att - $Outil->GetDifficulteUtilisation() + lanceDe(10);
		if($reussite > 0){ // Touch
			if($reussite > 5){
				if ($competence!="")
					$ARTISAN->AugmenterComp($competence,4);
				if ($caracteristique!="")
					$ARTISAN->AugmenterComp($caracteristique,2);
			} else {
				if ($competence!="")
					$ARTISAN->AugmenterComp($competence,2);
				if ($caracteristique!="")
					$ARTISAN->AugmenterComp($caracteristique,1);
			}
		}
		else	if ($competence!="")
				$ARTISAN->AugmenterComp($competence,1);
		logDate	("reussite reparer" . $reussite);
		return $reussite;				
	}	



	function reparation($ARTISAN,$typeact,$id_outil,$id_objetAreparer) {
	// Dbut artisanat par Uriel
		global $liste_pas_actions;
		global $liste_pis_actions;
		global $liste_comp_full;
		global $template_main;
		$reussite = 0;

	    /*switch ($typeact) {
		case 'Cuir'  : { $action_todo="ArtisanCuir" ; break ; }
		case 'Metal' : { $action_todo="Mineur" ; break ; }
		case 'Bois'  : { $action_todo="Bucheron" ; break ; }
	    }
	    */
	logdate("artisan".$ARTISAN. "  ".$typeact. "  ".$id_outil. "  ".$id_objetAreparer);
	    if( isset($id_outil) && isset($id_objetAreparer) 
	    	&& ($ARTISAN->ModPA($liste_pas_actions["ReparerObjet"])) 
	    	&& ($ARTISAN->ModPI($liste_pis_actions["ReparerObjet"]))){

		$Outil = null;
		$Objet_AR = null;
		$i=0;

		while (($Outil == null || $Objet_AR  == null) && $i<count($ARTISAN->Objets)) {
			if($ARTISAN->Objets[$i]->id_clef == $id_outil)
				 $Outil = $ARTISAN->Objets[$i];
			else
			if($ARTISAN->Objets[$i]->id_clef == $id_objetAreparer)
				 $Objet_AR = $ARTISAN->Objets[$i];
			$i++;
		}

		if ($Outil==null)  {
			logDate("Outil Artisanat==null dans actions/Artisanat");
			return;
		}

		if ($Objet_AR ==null)  {
			logDate("Objet_AR == null dans actions/Artisanat");
			return;
		}		

		if ($ARTISAN->peutUtiliserObjet($Outil)) {
			$valeurs[1]=$Outil->nom;
			$valeurs[0]=$Objet_AR->nom;
			$valeurs[2]=0;

			if(! $Outil->Decharge()){
				$template_main.=  GetMessage("nomunartisan",$valeurs);
				return;
			}

			$reussite = reussite_reparation($ARTISAN, $Outil);			
			if($reussite > 0){ // Touch
				if ($Objet_AR->Dur_actu >= $Objet_AR->durabilite) {
					$msg_final = "reparation_armure_03";	// pas besoin de rparer
				}
				else {
					$msg_final = "reparation_armure_01";	// reparation ok
					$valeurs[2]=$Outil->Degats[1];
					$Objet_AR->Reparer($Outil->Degats[1]);
				}
			} else { // rat
				$Outil->Abime();
				$msg_final = "reparation_armure_02";
			}
	
			$ARTISAN->OutPut(GetMessage($msg_final,$valeurs));
		}
		else {
			$valeurs=array();
			$valeurs[0]= $Outil->nom;
			$valeurs[1]= $Outil->EtatTempSpecifique->nom;
			$ARTISAN->OutPut(GetMessage("objet_inutilisable",$valeurs));
		}
		
	    } else {
		if(!isset($id_outil)){
			$template_main.=  GetMessage("noparam");
		} else {
			if ($ARTISAN->RIP())
				$template_main.=  GetMessage("nopvs");
			else	
			if ($ARTISAN->Archive)
				$template_main.=  GetMessage("archive");
			else	
			$template_main.=  GetMessage("nopas");
		}
	    }
        // Fin Artisanat par Uriel
	}

	/*! 
	    \brief Calcule la reussite de l'action 
	    \param $ARTISAN le PJ qui veut rparer
	    \param $Outil l'outil utilis
	    \param $Materiau l'objet produit naturel utilis
	    \param $ObjetAcreer l'objet que l'on veut creer
	    
	*/		
	function reussite_artisanat($ARTISAN, $Outil, $Materiau, $ObjetAcreer) {

		global $liste_comp_full;
		$cmp_att=0;
		if (in_array ($Outil->competence, array_keys($liste_comp_full))) {
			$competence = $liste_comp_full[$Outil->competence];
			$cmp_att+=$ARTISAN->GetNiveauComp($competence,true)-lanceDe(10);
			if ($cmp_att <0)
				$cmp_att+= lanceDe(5);

		}	
		else $competence="";	
		if (in_array ($Outil->caracteristique, array_keys($liste_comp_full))) {
			$caracteristique = $liste_comp_full[$Outil->caracteristique];
			$cmp_att+=$ARTISAN->GetNiveauComp($caracteristique,true)-lanceDe(10);
			if ($cmp_att <0)
				$cmp_att+= lanceDe(5);
		}	
		else $caracteristique="";	

		$reussite = $cmp_att - $Outil->GetDifficulteUtilisation() + lanceDe(10);
		
		$reussite = $reussite - $ObjetAcreer->GetDifficulteUtilisation() + lanceDe(10);
		
		$reussite += $Materiau->GetDifficulteUtilisation() - lanceDe(10);
		if($reussite > 0){ // Touch
			if($reussite > 5){
				if ($competence!="")
					$ARTISAN->AugmenterComp($competence,4);
				if ($caracteristique!="")
					$ARTISAN->AugmenterComp($caracteristique,2);
			} else {
				if ($competence!="")
					$ARTISAN->AugmenterComp($competence,2);
				if ($caracteristique!="")
					$ARTISAN->AugmenterComp($caracteristique,1);
			}
		}
		else	if ($competence!="")
				$ARTISAN->AugmenterComp($competence,1);
		logDate	("reussite artisanat" . $reussite);
		return $reussite;				
	}	

	function artisanat($ARTISAN, $id_outil, $id_materiau, $id_ObjetAcreer) {
		global $liste_pas_actions;
		global $liste_pis_actions;
		global $liste_comp_full;
		global $template_main;

		$reussite = 0;

	    if( isset($id_outil) && isset($id_ObjetAcreer) && isset($id_materiau) 
	    	&& ($ARTISAN->ModPA($liste_pas_actions["CreerObjet"])) 
	    	&& ($ARTISAN->ModPI($liste_pis_actions["CreerObjet"]))){

		$Outil = null;
		$ObjetMateriau = null;
		$i=0;

		while (($Outil == null || $ObjetMateriau  == null) && $i<count($ARTISAN->Objets)) {
			if($ARTISAN->Objets[$i]->id_clef == $id_outil)
				 $Outil = $ARTISAN->Objets[$i];
			else
			if($ARTISAN->Objets[$i]->id_clef == $id_materiau)
				 $ObjetMateriau = $ARTISAN->Objets[$i];
			$i++;
		}

		if ($Outil==null)  {
			logDate("Outil Artisanat==null dans actions/Artisanat");
			return;
		}

		if ($ObjetMateriau ==null)  {
			logDate("ObjetMateriau == null dans actions/Artisanat");
			return;
		}		

		if ($ARTISAN->peutUtiliserObjet($Outil)) {
			$ObjetAcreer = new Objet ($id_ObjetAcreer);
			$valeurs[1]=$Outil->nom;
			$valeurs[0]=$ObjetAcreer->nom;
			$valeurs[2]=$ObjetMateriau->nom;

			if(! $Outil->Decharge()){
				$template_main.=  GetMessage("nomunartisan",$valeurs);
				return;
			}

			$reussite = reussite_artisanat($ARTISAN, $Outil, $ObjetMateriau, $ObjetAcreer);
			if($reussite > 0){ // Touch
				if ($ObjetMateriau->Detruire()) {
					if ($ARTISAN->AcquerirObjet($ObjetAcreer)) {					
						$msg_final = "creation_objet_01";	//creation reussie
					}
					else {
						$msg_final = "sacplein";	// sac plein
					}
				}
			} else { // rat
				$Outil->Abime();
				if ($reussite < -5) {
					//rate + objet_materiau detruit
					$ObjetMateriau->Detruire();
					$msg_final = "creation_objet_03";
				}	
				else
					$msg_final = "creation_objet_02";
			}
	
			$ARTISAN->OutPut(GetMessage($msg_final,$valeurs));
		}
		else {
			$valeurs=array();
			$valeurs[0]= $Outil->nom;
			$valeurs[1]= $Outil->EtatTempSpecifique->nom;
			$ARTISAN->OutPut(GetMessage("objet_inutilisable",$valeurs));
		}
		
	    } else {
		if(!isset($id_outil)){
			$template_main.=  GetMessage("noparam");
		} else {
			if ($ARTISAN->RIP())
				$template_main.=  GetMessage("nopvs");
			else	
			if ($ARTISAN->Archive)
				$template_main.=  GetMessage("archive");
			else	
			$template_main.=  GetMessage("nopas");
		}
	    }
	}


	// RAPPEL Hixcks : l'id_sort n'est pas l'id_magie mais le id_clef !!!
	// PERSO est celui qui lance le sort, pas forcement celui qui est connecte
	function magieMajeure ($PERSO,$typeact,$id_sort_att,$id_cible_att,$id_sort_soin,$id_cible_soin,$id_sort_tel,$id_cible_tel, $id_lieu_tel,$id_sort_autotel, $id_lieu_autotel, $action_surprise=false,$riposteGroupe_autorisee=true,$riposteGroupe=true)  {
		global $liste_pas_actions;
		global $liste_pis_actions;
		global $liste_comp_full;
		global $liste_flags_lieux;
		global $template_main;
		
		$ok=false;
		$sort=null;
		if(isset($typeact)){
			switch($typeact){
					case 'attaque':{ if( (isset($id_sort_att)) && (isset($id_cible_att)) && ($id_cible_att != $PERSO->ID)){$ok=true;$idSort=$id_sort_att; } break;}
					case 'soin':{if( (isset($id_sort_soin)) && (isset($id_cible_soin)) ){$ok=true;$idSort=$id_sort_soin;} break;}
					case 'teleport':{ if( (isset($id_sort_tel)) && (isset($id_cible_tel)) && (isset($id_lieu_tel)) && ($id_cible_tel != $PERSO->ID) ){$ok=true;$idSort=$id_sort_tel;} break;}
					case 'autoteleport':{if( (isset($id_sort_autotel)) && (isset($id_lieu_autotel)) ){$ok=true;$idSort=$id_sort_autotel;} break;}
			}
		}
	
	
	
		if( ($ok) && ($PERSO->Lieu->permet($liste_flags_lieux["Magie"])) && ($PERSO->ModPA($liste_pas_actions["MagieMajeure"])) && ($PERSO->ModPI($liste_pis_actions["MagieMajeure"]))){
			$valeurs=array("","","","","","","","","");
			//0: sort, 1: soi, 2: adversaire, 3: degats PV, 4: gain PV, 5: perte PA, 6: effet+, 7: effet -, 8: lieu_dest
			$valeurs[1]=$PERSO->nom;		
			$sortir=false;

			$Sort= rechercheDuSort($PERSO,$idSort);
					
			if ($Sort==null) {
				if (!$action_surprise && !$riposteGroupe) 
					$PERSO->OutPut(GetMessage($mauvais_param_sort,$valeurs)); 
				return;			
			}	else 	$valeurs[0]=$Sort->nom;
			
			if ( ! $PERSO->peutUtiliserObjet($Sort)) {
				$valeurs=array();
				$valeurs[0]= $Sort->nom;
				$valeurs[1]= $Sort->EtatTempSpecifique->nom;
				$template_main.=  GetMessage("sort_inutilisable",$valeurs);
				return;
				}		
			
			if( ! $Sort->Decharge()){
				if (!$action_surprise && !$riposteGroupe)  $template_main.=  GetMessage("nocha",$valeurs);
				return;
			}	
			if( $Sort->anonyme == 0) 
				$sortir = $PERSO->etreCache(0);
			
			if($typeact == 'attaque'){
			
				$ADVERSAIRE = new Joueur($id_cible_att,true,true,true,true,true,true);
				if ($ADVERSAIRE->RIP()) {
					if (!$action_surprise && !$riposteGroupe)  {
						$valeurs=array();
						$valeurs[0]=$ADVERSAIRE->nom;
						$template_main.=  GetMessage("ennemimort",$valeurs);
					}	
					return;
				}	
				else {	
					$valeurs[8]=$ADVERSAIRE->getPVMax();
					$i=0;
					
					$valeurs[2]=$ADVERSAIRE->nom;
	
					$reussite = reussite_sort_attaque($PERSO, $ADVERSAIRE,$Sort);
					
					$degats = lancede($Sort->Degats[1]-$Sort->Degats[0])+$Sort->Degats[0];
					if (($reussite > 5) || ($reussite < -5) ){$degats*=2;}
					if( ($Sort->anonyme == 1) && ($reussite > 0)){$valeurs[1]="Quelqu'un";}
					if($reussite > 0){ // Touch
						$ADVERSAIRE->etreCache(0);				
						switch($Sort->Soustype){
							case "Attaque":{
								$degats = $ADVERSAIRE->AbsorptionDegats($Sort->competence,$degats);
								if($ADVERSAIRE->ModPV(-$degats)){$msg_final = "sort_attaque_01";}else{$msg_final = "sort_attaque_02";}
								$valeurs[3]=$degats;$valeurs[4]=0;$valeurs[5]=0;
								break;
							}
							case "Paralysie":{
								$degats = $ADVERSAIRE->AbsorptionDegats($Sort->competence,$degats);
								$msg_final = "sort_paralysie_01";
								$ADVERSAIRE->ModPA(-$degats,true);
								$valeurs[3]=0;$valeurs[4]=0;$valeurs[5]=$degats;
								break;
							}
							case "Transfert":{
								$degats = $ADVERSAIRE->AbsorptionDegats($Sort->competence,$degats);
								if( $ADVERSAIRE->ModPV(-$degats)){$msg_final = "sort_transfert_01";}else{$msg_final = "sort_transfert_02";}
								$PERSO->ModPV(round($degats/2));
								$valeurs[3]=$degats;$valeurs[4]=round($degats/2);$valeurs[5]=0;
								break;
							}
						}
						$retour = $ADVERSAIRE->GererChaineEtatTemp($Sort->provoqueetat);
						$valeurs[6] = $retour["ajouter"];$valeurs[7] = $retour["retirer"];
	
					} else { // rat
						switch($Sort->Soustype){
							case "Attaque":{
								if( $PERSO->ModPV(-$degats)){$msg_final = "sort_attaque_03";}else{$msg_final = "sort_attaque_04";}
								$valeurs[3]=$degats;$valeurs[4]=0;$valeurs[5]=0;
								break;
							}						
							case "Paralysie":{
								$msg_final = "sort_paralysie_02";
								$PERSO->ModPA(-$degats,true);
								$valeurs[3]=0;$valeurs[4]=0;$valeurs[5]=$degats;
								break;
							}
							case "Transfert":{
								if( $PERSO->ModPV(-$degats)){$msg_final = "sort_transfert_03";}else{$msg_final = "sort_transfert_04";}
								$valeurs[3]=$degats;$valeurs[4]=0;$valeurs[5]=0;
								break;
							}	
						}
						
					}
				}
			}
			
			// Fin des sorts d'attaque ET de paralysie
			
			if($typeact == 'teleport'){
				$ADVERSAIRE = new Joueur($id_cible_tel,true,true,true,true,true,true);
				$valeurs[8]=$ADVERSAIRE->getPVMax();
				$valeurs[0]=$Sort->nom;
				$valeurs[2]=$ADVERSAIRE->nom;
				if( $Sort->anonyme == 0) 
					$sortir = $PERSO->etreCache(0);	
					$reussite = reussite_sort_teleport($PERSO, $ADVERSAIRE,$Sort);
					if ($reussite) {
						$lieu = new Lieu ($id_lieu_tel);
						$reussite = $ADVERSAIRE->peutAccederLieu($lieu);
					}						
					$degats = lancede($Sort->Degats[1]-$Sort->Degats[0])+$Sort->Degats[0];
					if (($reussite > 5) || ($reussite < -5) ){$degats*=2;}
					if( ($Sort->anonyme == 1) && ($reussite > 0)){$valeurs[1]="Quelqu'un";}
					if($reussite > 0){ // Touch
						$sortir = $ADVERSAIRE->etreCache(0);	
						$msg_final = "sort_teleport_01";
						$ADVERSAIRE->DeplaceLieu($id_lieu_tel);
						$valeurs[3]=0;
						$valeurs[4]=0;
						$valeurs[5]=0;
						$valeurs[8]=$ADVERSAIRE->Lieu->nom;
						$retour = $ADVERSAIRE->GererChaineEtatTemp($Sort->provoqueetat);
						$valeurs[6] = $retour["ajouter"];
						$valeurs[7] = $retour["retirer"];
						if ($id_lieu_tel<> $PERSO->Lieu->ID)
							$riposteGroupe_autorisee=false;						
					} else { // rat
						$msg_final = "sort_teleport_02";
						$valeurs[3]=0;
						$valeurs[4]=0;
						$valeurs[5]=0;
						$valeurs[8]=$ADVERSAIRE->Lieu->nom;
					}
			}
	
			//Fin des sorts de teleportation
			
			if($typeact == 'soin'){
				$riposteGroupe_autorisee=false;
				if ($id_cible_soin == $PERSO->ID)
					$ADVERSAIRE=$PERSO;
				else $ADVERSAIRE = new Joueur($id_cible_soin,true,true,true,true,true,true);
				if( $Sort->anonyme == 0) 
					$sortir = $PERSO->etreCache(0);
				$valeurs[0]=$Sort->nom;
				$valeurs[2]=$ADVERSAIRE->nom;
				$valeurs[8]=$ADVERSAIRE->getPVMax();
				$reussite = reussite_sort_soin($PERSO, $ADVERSAIRE,$Sort);
			
				$degats = lancede($Sort->Degats[1]-$Sort->Degats[0])+$Sort->Degats[0];
				if (($reussite > 5) || ($reussite < -5) ){$degats*=2;}
				if( ($Sort->anonyme == 1) && ($reussite > 0)){$valeurs[1]="Quelqu'un";}
				if($reussite > 0){ // Touch
					$msg_final = "sort_soin_01";
					$ADVERSAIRE->ModPV($degats);
					$valeurs[3]=0;
					$valeurs[4]=$degats;
					$valeurs[5]=0;
					$retour = $ADVERSAIRE->GererChaineEtatTemp($Sort->provoqueetat);
					$valeurs[6] = $retour["ajouter"];
					$valeurs[7] = $retour["retirer"];
				} else { // rat
					$msg_final = "sort_soin_02";
					$PERSO->ModPV(round($degats/2));
					$valeurs[3]=0;
					$valeurs[4]=round($degats/2);
					$valeurs[5]=0;
				}
			}
	
			//Fin des sorts de soins
	
			if($typeact == 'autoteleport'){
				$valeurs[0]=$Sort->nom;$valeurs[2]=$PERSO->nom;
				$ADVERSAIRE=$PERSO;
				$reussite = reussite_sort_autoteleport($PERSO,$Sort);
				if ($reussite) {
					$lieu = new Lieu ($id_lieu_autotel);
					$reussite = $ADVERSAIRE->peutAccederLieu($lieu);
				}					
				if( ($Sort->anonyme == 1) && ($reussite > 0)){$valeurs[1]="Quelqu'un";}				
				if($reussite > 0){ // Touch
					$msg_final = "sort_teleport_self_01";
					$PERSO->DeplaceLieu($id_lieu_autotel);
					$valeurs[3]=0;$valeurs[4]=0;$valeurs[5]=0;$valeurs[8]=$PERSO->Lieu->nom;
					$retour = $PERSO->GererChaineEtatTemp($Sort->provoqueetat);
					$valeurs[6] = $retour["ajouter"];
					$valeurs[7] = $retour["retirer"];
				} else { // rat
					$msg_final = "sort_teleport_self_02";
					$valeurs[3]=0;
					$valeurs[4]=0;
					$valeurs[5]=0;
					$valeurs[8]=$PERSO->Lieu->nom;
				}
			}
			//Fin des sorts d'auto teleportation
			if($reussite > 0)
				$attResult="ATT_OK";
			else 	$attResult="ATT_KO";
				
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

			$valeursSpect=array();
			$valeursSpect[0] = $ADVERSAIRE->nom;
			if ($action_surprise)//cas ou celui qui est connecte subit l'action d'un pnj qui a une action surprise
				affiche_resultat($ADVERSAIRE,$PERSO,GetMessage("arrivee_lieu2",$valeurs) . $mess_spect .GetMessage($msg_final."_adv",$valeurs),GetMessage("arrivee_lieuSpect",$valeursSpect) .$mess.GetMessage($msg_final,$valeurs),GetMessage("arrivee_lieuSpect",$valeursSpect) .$mess_spect .GetMessage($msg_final."_spect",$valeurs),true);
			else 			
			if (!$riposteGroupe)	//cas normal celui qui est connecte fait l'action
				affiche_resultat($PERSO,$ADVERSAIRE,$mess.GetMessage($msg_final,$valeurs),$mess_spect.GetMessage($msg_final."_adv",$valeurs),$mess_spect.GetMessage($msg_final."_spect",$valeurs),true);
			else   //cas riposte
				affiche_resultat($ADVERSAIRE,$PERSO,GetMessage("riposteGroupe",$valeurs).GetMessage($msg_final."_adv",$valeurs),GetMessage("riposteGroupe",$valeurs).GetMessage($msg_final,$valeurs),GetMessage("riposteGroupe",$valeurs).GetMessage($msg_final."_spect",$valeurs),false);

			if ($riposteGroupe_autorisee) 			
				riposteGroupe ($ADVERSAIRE,$PERSO,$attResult,false);			
			
		} else {
			if (!$action_surprise && !$riposteGroupe) 
				if(!$PERSO->Lieu->permet($liste_flags_lieux["Magie"])){
					$template_main.=  GetMessage("noright");
				} else {
					if ($PERSO->RIP())
						$template_main.=  GetMessage("nopvs");
					else	
					if ($PERSO->Archive)
						$template_main.=  GetMessage("archive");
					else	
					if( !($ok) ){
						$template_main.=  GetMessage("noparam");
					} 
					else {
						$template_main.=  GetMessage("nopas");
					}
				}
		}
	}

	function secacher($PERSO) {
		global $db;
		global $liste_type_objetSecret;
		$vu=false;
		$reussiteaction= reussite_secacher($PERSO);
		// compare la reussiteaction avec la difficulte de se cacher dans ce lieu
		if ($reussiteaction>0) {
			$PERSO->etrecache(1);
			$PERSO->OutPut(GetMessage("secacher_01"));
			//OK on est cache, verifions qui nous a vu 		
			// compare la reussiteaction avec les capacites d'observation des Persos presents
			$SQL = $PERSO->listePJsDuLieuDuPerso(1, false, false,1);
			$result = $db->sql_query($SQL);
			$toto = array_keys($liste_type_objetSecret);
			while(	$row = $db->sql_fetchrow($result)){
				$pjtemp = new Joueur($row["idselect"],true,true,true,true,true,true);
				$reussiteObservation = reussite_Observersecacher($pjtemp, $reussiteaction);
				if ($reussiteObservation) {
					//pjtemp a vu perso se dissimuler
					$valeurs[0]=$PERSO->nom;
					$pjtemp->ConnaitPersosSecrets = 1;	
					$pjtemp->OutPut(GetMessage("secacher_spect",$valeurs),false);
					$SQLinsert1= "INSERT INTO ".NOM_TABLE_ENTITECACHEE." (ID_entite,id_lieu,type, nom) VALUES (".$PERSO->ID.",".$PERSO->Lieu->ID.",". $toto[2].", '".ConvertAsHTML($PERSO->nom)."')";
					if ($resultinsert1=$db->sql_query($SQLinsert1)) {					
						$result_id=$db->sql_nextid();
						$SQLinsert2 = "INSERT INTO ".NOM_TABLE_ENTITECACHEECONNUEDE."  (id_entitecachee, id_perso) values  (".$result_id.",  ".$pjtemp->ID.")";
						$resultinsert2=$db->sql_query($SQLinsert2);
					}
				}	
			}	
		}
		else {
			$PERSO->OutPut(GetMessage("secacher_02"));
		}		
		return $reussiteaction;
	}

	function traceAction($action, $acteur, $commentaireAction="", $adversaire=null, $complement1="", $complement2="")  {
		global $liste_actions_tracees;
		global $db;
		if (is_array($liste_actions_tracees) && isset($liste_actions_tracees[$action]) && $liste_actions_tracees[$action]==1) {
			$SQL="";
			switch($action) {
				case "FouillerCadavre":
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.", ".$acteur->Lieu->ID.",'fouille de ".ConvertAsHTML($adversaire->nom)."',".time().")";
					break;
				case "DonnerArgent":
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.", ".$acteur->Lieu->ID.",'donne  ".span($complement1 ." Pos","po") . "  ". ConvertAsHTML($adversaire->nom)."',".time().")";
					break;
				case "DonnerObjet":
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.", ".$acteur->Lieu->ID.",'donne  ".ConvertAsHTML(span($complement1,"objet")) . "  ". ConvertAsHTML($adversaire->nom)."',".time().")";
					break;
				case "RamasserObjet":
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.", ".$acteur->Lieu->ID.",'ramasse  ".ConvertAsHTML(span($complement1,"objet")) ."',".time().")";
					break;
				case "AbandonnerObjet":
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.", ".$acteur->Lieu->ID.",'abandonne  ".ConvertAsHTML(span($complement1,"objet")) . ConvertAsHTML($commentaireAction) ."',".time().")";
					break;
				case "CacherObjet":
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.", ".$acteur->Lieu->ID.",'cache  ".ConvertAsHTML(span($complement1,"objet")) . ConvertAsHTML($commentaireAction)."',".time().")";
					break;
				case "DetruireObjet":	
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.", ".$acteur->Lieu->ID.",'dtruit  ".ConvertAsHTML(span($complement1,"objet")) . ConvertAsHTML($commentaireAction)."',".time().")";
					break;					
				case "VolerPJ":
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.", ".$acteur->Lieu->ID.",'vole ".ConvertAsHTML(span($complement1 ." Pos","po"))."  ". ConvertAsHTML($adversaire->nom)."',".time().")";
					break;
				case "Attaquer":
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.",".$acteur->Lieu->ID.", 'inflige ".ConvertAsHTML($complement1) . " degats  ". ConvertAsHTML($adversaire->nom) . " ".ConvertAsHTML($complement2)."', ".time().")";
					break;
				case "SeCacher":
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.",".$acteur->Lieu->ID.", 'se dissimule dans l ombre ', ".time().")";
					break;
				case "SoinObjet":	
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.",".$acteur->Lieu->ID.", 'soigne '". ConvertAsHTML($adversaire->nom) . " ".ConvertAsHTML($complement2).", ".time().")";
					break;
				case "FouillerLieu":
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.",".$acteur->Lieu->ID.",' fouille le lieu ". ConvertAsHTML($acteur->Lieu->nom)."', ".time().")";
					break;
				case "Magie":			
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.",".$acteur->Lieu->ID.", ' lance le sort ".ConvertAsHTML($commentaireAction)." de type ". ConvertAsHTML($complement1)." sur ". ConvertAsHTML($adversaire->nom) . " ".ConvertAsHTML($complement2)."', ".time().")";
					break;
				case "Crocheter":			
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.",".$acteur->Lieu->ID.", ' crochette la porte menant  ".ConvertAsHTML($commentaireAction)."', ".time().")";
					break;
				case "MotPasse":			
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.",".$acteur->Lieu->ID.", ' teste le mot de passe menant  ".ConvertAsHTML($commentaireAction)."', ".time().")";
					break;
				case "SeDeplacer":			
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.",".$acteur->Lieu->ID.", ' se dplace  ".ConvertAsHTML($commentaireAction)."', ".time().")";
					break;
				case "Desengager":
               				switch($complement1){
        					case 'force':{$texte= "en utilisant sa force"; break;}
        					case 'dexte':{$texte= "en utilisant sa dextrit"; break;}
        					case 'ruse':{$texte= "en utilisant sa ruse"; break;}
        					case 'propdes':{$texte ="d'un commun accord"; break;}
        					case 'mort':{$texte = "car ce dernier est mort"; break;}
                			}
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.", ".$acteur->Lieu->ID.",'se dgage de son combat avec ". ConvertAsHTML($adversaire->nom)." ".ConvertAsHTML($texte)."', ".time().")";
					break;					
				case "Manger":
               				switch($commentaireAction){

                case 'manger': {$texte = " mange ";  break;}
                case 'dope':{$texte = " se dope avec "; break;}
                case 'stimule':{$texte = " se stimule avec "; break;}
                case 'consiste':{$texte = " se remet en forme avec "; break;}
                case 'vitamine':{$texte = " se vitamine avec "; break;}
                case 'revigore':{$texte = " se revigore avec "; break;}
                case 'rare':{$texte = " se regenere avec "; break;}

                			}
					$SQL="insert into ". NOM_TABLE_TRACE_ACTIONS ." (action, id_acteur, id_lieu,detail, heure_action) values ('".
					$action."',".$acteur->ID.", ".$acteur->Lieu->ID.",'". $texte .ConvertAsHTML($complement2)."', ".time().")";
					break;	

			}	
			logdate($SQL);
			if ($SQL<>"")
				$db->sql_query($SQL);
		}	
	}	
	

}
?>
