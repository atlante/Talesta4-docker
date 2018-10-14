<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: joueur.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.56 $
$Date: 2010/01/24 16:36:41 $

*/

require_once("../include/extension.inc");
if(!defined("__JOUEUR.PHP") ) {
	Define("__JOUEUR.PHP",	0);

	//!	Classe representant un participant du jeu (PJ, PNJ ...)
	class Joueur{
		var $ID;
		var $nom;			///< Nom du PJ
		var $pass;			///< Mot de passe du PJ	(crypte)
		var $PA;			///< Points d'action du PJ
		var $PV;			///< Points de vie du PJ 
		var $PO;			///< Quantité d'or du PJ 
		var $PI;			///< Points d'intellect du PJ 
		var $XP;			///< Points d'experience du PJ 
		var $Lieu;			///< Lieu ou se trouve le PJ (objet de la classe Lieu)
		var $description="";		///< la description du PJ visible des autres joueurs
		var $email;			///< EMail du PJ
		var $interval_remisepa;
		var $Derniere_RemisePA;
		var $interval_remisepi;
		var $Derniere_RemisePI;
		var $type;
		var $ListeComp;
		var $Specs;
		var $EtatsTemp;
		var $Objets;			///< l'inventaire des objets du PJ
		var $Sorts;			///< le grimoire des sorts du PJ
		var $banque;			///< l'argent que le PJ a en banque
		var $FANonLu;
		var $lastaction;
		var $wantmail;
		var $wantmusic;			///< Flag qui indique si le PJ désire entendre les sons des lieux
		var $pnj;			///< Indique si PJ,PNJ,bestaire ou monstre (0 pour PJ, 1 pour PNJ,2 pour bestiaire, 3 pour monstre)
		var $Reaction;
		var $SortPrefere;
		var $phrasepreferee;
		var $Relation;
		var $actionsurprise;
		var $ConnaitLieuxSecrets;	///< Flag qui indique si le PJ sait qu'il y a au moins un chemin caché dans le lieu où il se trouve	
		var $ConnaitObjetsSecrets;	///< Flag qui indique si le PJ sait qu'il y a au moins un objet caché dans le lieu où il se trouve	
		var $Archive;			///< Flag qui indique si le PJ est archivé ou non
		var $Groupe="";
		var $EquipOccupe;
		var $IP_Joueur;
		var $dissimule;			///< Indique si le PJ est caché dans le lieu (0 pour visible, 1 pour caché)
		var $ConnaitPersosSecrets;	///< Flag qui indique si le PJ sait qu'il y a au moins un PJ caché dans le lieu où il se trouve
		var $imageforum="";		
		var $Engagement;                ///< Flag qui indique si le PJ est engagé dans un combat (ce qui l'empeche de quitter les lieux) 
	//	var $propdes;
		var $backGround;		///< le background du PJ indiqué à l'inscription
		var $roleMJ;
		var $Quetes;			///< la liste des quetes du PJ
		var $nb_deces;                    ///< Nb de fois quele PJ est mort
		var $momentMort;                ///< A quel moment le PJ est mort
		

		/*! 
		    \brief Constructeur
		    \param $id_joueur: ID du PJ.
		    \param $chargeSecrets: détermine s'il faut charger si le perso connait des secrets
		    \param $chargeCompetence: détermine s'il faut charger les competences du PJ
		    \param $chargeSpecs: détermine s'il faut charger les specialites du PJ
		    \param $chargeEtats: détermine s'il faut charger les etats temps du PJ
		    \param $chargeInventaire: détermine s'il faut charger l'inventaire du PJ
		    \param $chargeGrimoire: détermine s'il faut charger le grimoire du PJ
		    \param $chargeQuetes: détermine s'il faut charger les quetes du PJ
		*/		
		function Joueur($id_joueur,$chargeSecrets=false, $chargeCompetence=false,$chargeSpecs=false,$chargeEtats=false,$chargeInventaire=false,$chargeGrimoire=false, $chargeQuetes=false){
			global $phpExtJeu;
			$this->ID = $id_joueur;
			$this->UpdateFromDB($chargeSecrets, $chargeCompetence,$chargeSpecs,$chargeEtats,$chargeInventaire,$chargeGrimoire, $chargeQuetes);	
			//ne fait pas de remise si c'est une action MJ
			if (!defined("PAGE_ADMIN")) {
			        //ne fait pas la remise si les etats et la specs ne sont pas charges
			        //sinon la remise n'est pas correcte. De plus, elle n'est pas utile.
			        if ($chargeSpecs==true && $chargeEtats==true && $chargeCompetence==true) {
				//MODIF Hixcks pour que remise PA ne s'affiche pas 1000 fois et transfert de la remise dans joueur
				$this->RemisePAs();
				$this->RemisePIs();
			}
		}
		}


		function UpdateFromDB($chargeSecrets, $chargeCompetence,$chargeSpecs,$chargeEtats,$chargeInventaire,$chargeGrimoire, $chargeQuetes){
			//Charge le joueur a partir de la base de donnee
			global $db;
			global $forum;
			global $liste_reactions;
			//global $MJ;
			if(defined("IN_FORUM")&& IN_FORUM==1  && $forum->champimage<>null)  
				$SQL= $forum->requetePJ($this->ID);
			else $SQL = "SELECT * FROM ".NOM_TABLE_REGISTRE." WHERE id_perso = ".$this->ID;

			$requete=$db->sql_query($SQL);
			if ($requete!==false) {
				$row = $db->sql_fetchrow($requete);
				/*if ($row===FALSE) {
					$SQL = "SELECT * FROM ".NOM_TABLE_REGISTRE." WHERE id_perso = ".$this->ID;
					$requete=$db->sql_query($SQL);
					$row = $db->sql_fetchrow($requete);
				}
				*/
				if(defined("IN_FORUM")&& IN_FORUM==1 && ($row["pnj"]<2) && defined("AFFICHE_AVATAR_FORUM") &&  AFFICHE_AVATAR_FORUM==1  && $forum->champimage<>null)   {
					$this->imageforum =$forum->URLimageAvatar($row["$forum->champtypeimage"],$row["$forum->champimage"] );
				}	
				$this->nom = ConvertAsHTML($row["nom"]);
				$this->pass = $row["pass"];
				$this->PA = $row["pa"];
				$this->PV = $row["pv"];
				$this->PO = $row["po"];
				$this->PI = $row["pi"];				
				$this->dissimule = $row["dissimule"];
				$this->interval_remisepa = $row["interval_remisepa"];
				$this->Derniere_RemisePA = $row["derniere_remisepa"];
				$this->interval_remisepi = $row["interval_remisepi"];
				$this->Derniere_RemisePI = $row["derniere_remisepi"];
				$this->banque = $row["banque"];
				if ($row["pnj"]<>2)
				        $this->Lieu = new Lieu($row["id_lieu"],$chargeSecrets);
				else    $this->Lieu ="";
				$this->FANonLu = $row["fanonlu"];
				$this->email = $row["email"];
				$this->lastaction = $row["lastaction"];
				$this->wantmail = $row["wantmail"];
				$this->wantmusic = $row["wantmusic"];
				$this->pnj = $row["pnj"];
				$this->Reaction = $row["reaction"];
				/**
				*    Si la valeur de Reaction n'est pas renseignee ou est incorrecte, on force la valeur à 4 (le joueur ne reagit pas) 
				*/
				if (! array_key_exists($this->Reaction, $liste_reactions))
				 	$this->Reaction=4;
				//$this->ArmePreferee = $row["armePreferee"];
				$this->SortPrefere = $row["sortprefere"];
				$this->phrasepreferee= $row["phrasepreferee"];
				$this->Relation = $row["relation"];
				$this->actionsurprise = $row["actionsurprise"];
				$this->Archive = $row["archive"];			
				$this->Groupe = $row["id_groupe"];	
				$this->IP_Joueur= $row["ip_joueur"];	
				//$this->Engagement = $row["engagement"];
				//$this->propdes = $row["propdes"];
        			$SQLengagement = "SELECT count(id_adversaire) as c FROM ".NOM_TABLE_ENGAGEMENT."  WHERE id_perso = '".$this->ID."' or id_adversaire = '".$this->ID."' ";
        			$reqengagement = $db->sql_query($SQLengagement);
        			$rowengagement = $db->sql_fetchrow($reqengagement);
        			$nbEngagement = $rowengagement["c"];
        
        			if ($nbEngagement ==0){
        				//$SQL= "UPDATE ".NOM_TABLE_PERSO." SET engagement = '0' WHERE id_perso = ".$this->ID;
        				//$requete=$db->sql_query($SQL);
        				$this->Engagement=false;
        			}	
        			else $this->Engagement=true;		
        			$this->nb_deces =  $row['nb_deces'];
                                $this->momentMort =$row['moment_mort'];
				$this->backGround = $row["background"];
				$this->roleMJ= $row["role_mj"];				
				$this->ListeComp = null;
				$this->XP = 1;				
				$this->Specs = null;
				$this->EtatsTemp = null;
				$this->Objets = null;
				$this->EquipOccupe= array();
				$this->Sorts = null;
				//les monstres ne sont pas dans des lieux et ne connaissent pas les secrets
				if ($chargeSecrets && $row["pnj"]<>2) 
					$this->connaitSecrets();
				if ($chargeCompetence) {
					$SQL = "SELECT * FROM ".NOM_TABLE_COMP." WHERE id_perso = ".$this->ID;
					$requete=$db->sql_query($SQL);
					
					while($row = $db->sql_fetchrow($requete)){
						$this->ListeComp[$row["id_comp"]] = $row["xp"];
						$this->XP += $row["xp"];
					}
				}					
				if ($chargeSpecs) {
					$SQL = "SELECT * FROM ".NOM_TABLE_PERSOSPEC." WHERE id_perso = ".$this->ID;
					$requete=$db->sql_query($SQL);		
					
					if($db->sql_numrows($requete) > 0){
						$j=0;
						while($row = $db->sql_fetchrow($requete)){
							$this->Specs[$j] = new Specialite($row["id_spec"]);
							if($this->Specs[$j]!=null)
								$j++;
	
						}
					} else {
						$this->Specs[0] = new Specialite(1);
					}
				}
				
				if ($chargeEtats) {
					$SQL = "SELECT * FROM ".NOM_TABLE_PERSOETATTEMP." WHERE id_perso = ".$this->ID;
					$requete=$db->sql_query($SQL);		
					
					$nb_etats = $db->sql_numrows($requete);
					if($nb_etats > 0){
						$j=0;
						for($i=0;$i<$nb_etats;$i++){
							$row = $db->sql_fetchrow($requete);
							$this->EtatsTemp[$j] = new EtatTempPJ($row["id_etattemp"],$row["id_clef"],$row["fin"]);
							if($this->EtatsTemp[$j]!=null) {
								$j++;
							}	
						}
					} else {
						$this->EtatsTemp[0] = new EtatTempPJ(1);
					}
				}
				if ($chargeInventaire) {						
					$SQL = "SELECT * FROM ".NOM_TABLE_PERSOOBJET." WHERE id_perso = ".$this->ID." ORDER BY id_clef";
					$requete=$db->sql_query($SQL);								
					$j=0;
					$nb_objets = $db->sql_numrows($requete);
					for($i=0;$i<$nb_objets;$i++){
						$row = $db->sql_fetchrow($requete);
						$this->Objets[$j] = new ObjetPJ($row["id_objet"],$row["id_clef"],$i, ($row["equipe"] == 1),($row["temporaire"]==1),$row["munitions"],$row["durabilite"]);
						if ($this->Objets[$j]!=null) {
							if($this->Objets[$j]->equipe) {
								if (array_key_exists ($this->Objets[$j]->PartieCorps,$this->EquipOccupe))
									 $this->EquipOccupe[$this->Objets[$j]->PartieCorps]+=$this->Objets[$j]->QteOccupee;
								else 	 $this->EquipOccupe[$this->Objets[$j]->PartieCorps]=$this->Objets[$j]->QteOccupee;	
							}
							$j++;
						}				
					}
				}	
				
				if ($chargeGrimoire) {
					$SQL = "SELECT * FROM ".NOM_TABLE_PERSOMAGIE." WHERE id_perso = ".$this->ID." ORDER BY id_clef";
					$requete=$db->sql_query($SQL);
	
					$j=0;
					$nb_sorts = $db->sql_numrows($requete);
					for($i=0;$i<$nb_sorts;$i++){
						$row = $db->sql_fetchrow($requete);
						$this->Sorts[$j] = new MagiePJ($row["id_magie"],$row["id_clef"],$i,$row["charges"]);
						if($this->Sorts[$j]!=null)
							$j++;
					}
				}
				
				if ($chargeQuetes) {
					$SQL = "SELECT * FROM ".NOM_TABLE_PERSO_QUETE." WHERE id_perso = ".$this->ID." ORDER BY id_quete";
					$requete=$db->sql_query($SQL);
	
					$j=0;
					$nb_quetes = $db->sql_numrows($requete);
					for($i=0;$i<$nb_quetes;$i++){
						$row = $db->sql_fetchrow($requete);
						$this->Quetes[$j] = new QuetePJ($row["id_quete"],$row["id_persoquete"],$row["etat"],$row["debut"],$row["fin"]);
						if($this->Quetes[$j]!=null) {
							if ($this->Quetes[$j]->etat==2 && $this->Quetes[$j]->fin!=-1 && $this->Quetes[$j]->fin < time()) {
								$this->Quetes[$j]->EvolutionQuete($this->Quetes[$j]->id_persoquete, 9);
								$valeurs=array();
								$valeurs[1]=$this->Quetes[$j]->acteurProposant->nom;
								$valeurs[2]=$this->Quetes[$j]->nom_quete;
								$valeurs[3]=span(faitDate(time(),true),"date");
								$message=GetMessage("queteEchecTemps",$valeurs);
								$this->OutPut($message,false,true);	
							}
							$j++;
						}	
					}					
				}	
			}
		}



	function archiver() {
		global $db;
		$this->Archive = true;
		$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET LASTACTION = '".time()."', archive=1 WHERE id_perso = ".$this->ID;
		$result=$db->sql_query($SQL);
		if ($result!==false) {
			$SQL="insert into ". NOM_TABLE_ARCHIVE . " (id_perso,datearchivage ) values (". $this->ID .",now())";
			$result=$db->sql_query($SQL);
			if ($result!==false) 
				return true;
			else return false;	
		}	
		else return false;
	}

	function desarchiver() {
		global $db;
		$this->Archive = false;
		$remise=false;
		$result=true;
		if (!$this->RIP()) {
        		if ($this->Derniere_RemisePA < time() - $this->interval_remisepa *3600) {
        			$this->Derniere_RemisePA = time() - $this->interval_remisepa *3600;
        			$this->RemisePAs();
        			$remise=true;
        		}	
        		if ($this->Derniere_RemisePI < time() - $this->interval_remisepi *3600) {
        			$this->Derniere_RemisePI = time() - $this->interval_remisepi *3600;
        			$this->RemisePIs();
        			$remise=true;
        		}	
                }
		// pas d'update si remise car, archive=0 a deja ete mis la-bas
		if(!$remise) {
			$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET lastaction = '".time()."', archive=0 WHERE id_perso = ".$this->ID;
			$result=$db->sql_query($SQL); 
		}
		if ($result!==false) {
			$SQL="update ". NOM_TABLE_ARCHIVE . " set datedesarchivage = now() where datedesarchivage is null and id_perso =". $this->ID ;
			$result=$db->sql_query($SQL);
			if ($result!==false) 
				return true;
			else return false;	
		}	
		else return false;
	}

	function connaitSecrets () {
		global $db;
		$this->ConnaitLieuxSecrets =false;		
		$this->ConnaitObjetsSecrets =false;		
		$this->ConnaitPersosSecrets =false;		
		$SQL =  "SELECT e.type, ec.id_perso FROM ".NOM_TABLE_ENTITECACHEE." e,".NOM_TABLE_ENTITECACHEECONNUEDE." ec ";
 		$SQL .= " WHERE e.id_lieu = ".$this->Lieu->ID." and  ec.id_entiteCachee = e.ID AND (ec.id_perso is null OR ec.id_perso =".$this->ID .")";
		$result = $db->sql_query($SQL);
		$nb_secrets=$db->sql_numrows($result);
		$i=0;
		while ($i<$nb_secrets && ($this->ConnaitLieuxSecrets==false || $this->ConnaitObjetsSecrets==false || $this->ConnaitPersosSecrets==false)) {
			$row = $db->sql_fetchrow($result);
			if ($row["type"]=="0") 
				$this->ConnaitLieuxSecrets =true;
			elseif	($row["type"]=="1") 
				$this->ConnaitObjetsSecrets =true;
			elseif	($row["type"]=="2") 
				$this->ConnaitPersosSecrets =true;

			$i++;	
		}
	}


		function GetComp($IDComp){
			if(isset($this->ListeComp[$IDComp])){return $this->ListeComp[$IDComp];} else {return 0;}
		}

		function GetBonusComp($IDComp){
				$cla = 0;
				$nbSpecs=count($this->Specs);
				for($i=0;$i<$nbSpecs;$i++){
					$cla += $this->Specs[$i]->Getbonus($IDComp);
				}
				$etat = 0;
				$nbEtats=count($this->EtatsTemp);
				for($i=0;$i<$nbEtats;$i++){
					$etat += $this->EtatsTemp[$i]->Getbonus($IDComp);
				}
				return $cla+$etat;
		}
		
		function GetNiveauComp($IDComp,$bonus=false,$bonusneg=false){
			$base = $this->GetComp($IDComp);
			$max = 2;
			$niv = 0;
			while ($base > $max){
				$niv++;
				if($max >= PALIER_MAX_XP_EXPONENTIELLE){
					$max += PALIER_MAX_XP_EXPONENTIELLE;
				}else{
					$max = $max*2;
				}
			}
			
			if($bonus){
				$niv = $niv+$this->GetBonusComp($IDComp);
			}
			if($bonusneg){return $niv;}else{return Max(0,$niv);}
		}

/*
0 2  4  8 16 32  64 128 256  512  768 1024  1280 1536  1792

0 1  2  3  4  5  6    7   8   9   10   11    12   13    14 
*/

/*
		function GetNiveauComp($IDComp,$bonus=false,$bonusneg=false){
			$base = $this->GetComp($IDComp);
			$max = 2;
			$niv = 0;
			while ($base > $max){
				$niv++;
				if($max >= PALIER_MAX_XP_EXPONENTIELLE){
					$max += PALIER_MAX_XP_EXPONENTIELLE;
				}else{
					$max = $max*2;
				}
			}
			
			if($bonus){
				$niv = $niv+$this->GetBonusComp($IDComp);
			}
			if($bonusneg){return $niv;}else{return Max(0,$niv);}
		}*/

		function AugmenterNiveau($IDComp) {
			$comp=$this->GetComp($IDComp);
			logdate("comp=". $comp);
			if ($comp >PALIER_MAX_XP_EXPONENTIELLE) {
				return $this->AugmenterComp($IDComp,PALIER_MAX_XP_EXPONENTIELLE);
			}	
			else {
				$niveau = $this->GetNiveauComp($IDComp);
				logdate("niveau, comp=".$niveau."///". $comp);
				if ($niveau > 2) {
					return $this->AugmenterComp($IDComp,$comp);
				}	
				else 	{
					return $this->AugmenterComp($IDComp,2);
				}	
			}
		}
		
		
		function BaisserNiveau($IDComp) {
			$comp=$this->GetComp($IDComp);
			if ($comp >PALIER_MAX_XP_EXPONENTIELLE) {
				return $this->AugmenterComp($IDComp,PALIER_MAX_XP_EXPONENTIELLE);
			}	
			else {
				$niveau = $this->GetNiveauComp($IDComp);
				logdate("niveau, comp=".$niveau."///". $comp);
				if ($niveau > 2) {
					return $this->AugmenterComp($IDComp,$comp);
				}	
				else 	{
					return $this->AugmenterComp($IDComp,-2);
				}	
			}
		}
		
		/*! 
		    \brief augmente la competence IDComp du joueur avec $xp XP
		    \param $IDComp le numéro et non la chaine de caractere de la compétence issue de include/const.php		    
		    \param $xp le nombre d'Xp a ajouter pour cette competence
		    
		*/			
		function AugmenterComp($IDComp,$xp=1){
			global $db;
			$result=true;
			if(isset($this->ListeComp[$IDComp])){//UPDATE
				$this->ListeComp[$IDComp] += $xp;
				$this->ListeComp[$IDComp]= max(0,$this->ListeComp[$IDComp]);
				$SQL = "UPDATE ".NOM_TABLE_COMP." SET XP = ".$this->ListeComp[$IDComp]." WHERE id_perso = '".$this->ID."' AND id_comp = '".$IDComp."'";
				$result=$db->sql_query($SQL);
			} else {
			        if ($xp>0) { 
			                // INSERT
				        $SQL = "INSERT INTO ".NOM_TABLE_COMP." (id_perso,id_comp,XP) VALUES ('".$this->ID."','".$IDComp."','".$xp."')";
				        $this->ListeComp[$IDComp] = $xp;
				        $result=$db->sql_query($SQL);
				}
			}			
			return $result;
		}

		function DeplaceLieu($id_lieu){
			global $db;
			//Annuler les etats du lieu dans lequel on se trouve
			$etatSupprimes = $this->GererChaineEtatTemp($this->Lieu->provoqueetat,true,true);
			$this->Lieu = new Lieu($id_lieu,true,true);
			$SQL="DELETE FROM ".NOM_TABLE_ENGAGEMENT." WHERE id_perso = ".$this->ID." OR id_adversaire = ".$this->ID;
   			if ($db->sql_query($SQL)!==false) {
				$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET LASTACTION = '".time()."', id_lieu = ".$this->Lieu->ID." WHERE id_perso = ".$this->ID;
				if ($db->sql_query($SQL)) {
					$SQL = "DELETE FROM ".NOM_TABLE_PERSOOBJET." WHERE temporaire = 1 AND id_perso = ".$this->ID;
					if ($db->sql_query($SQL)) {
					        /**
					        *       Supprime les monstres morts depuis trop longtemps
					        */
					        if(defined("DELAI_SUPPRESSION_MONSTRESMORTS") && DELAI_SUPPRESSION_MONSTRESMORTS!="" && DELAI_SUPPRESSION_MONSTRESMORTS!=-1) {
        					        $SQL = "select id_perso FROM ".NOM_TABLE_REGISTRE." WHERE pnj=3 and pv<0 and id_lieu = ".$id_lieu." and ((moment_mort + 3600*" .DELAI_SUPPRESSION_MONSTRESMORTS.") < '".time()."')";
                					$requete=$db->sql_query($SQL);
                					
                					while($row = $db->sql_fetchrow($requete)){
                                                              $joueurTmp= new Joueur($row['id_perso']);
                                                              $joueurTmp->supprimer();                                                              
                					}	        				        
	        				 }       
					        
					        /**
					        *   Modifs les lieux ne declenchent plus systematiquement les etats temps 
					        */
						//Declencher les etats du nouveau lieu
						$etatAjoutes = $this->GererChaineEtatTemp($this->Lieu->provoqueetat,false);
						$this->connaitSecrets();
						$this->etreCache(0);
						$retour = array();
						$retour["retirer"]= $etatSupprimes["retirer"];
						$retour["ajouter"]= $etatAjoutes["ajouter"];
						return $retour;
					}
				}	
			}
			else return false;				

		}
				
		/** 
		    \param nocheck est a vrai lors d'une attaque recue qui prend des PA par exemple.
		    dans ce cas, il faut descendre les PA. S'il n'y en a pas assez, on limite &agrave; 0
		    \return retourne false s'il n'y avait pas assez de PA

		*/    
		function ModPA($valeur,$nocheck=false ){
			global $db;
			if($this->RIP()) { return false;}				
			if($this->Archive) { return false;}				
			if($valeur < 0){//ca coute des PAs
				if ($nocheck==false)
					if($this->PA < abs($valeur)){ return false;}
				else 	if($this->PA < abs($valeur)){
								//on ne descend pas plus que 0
								$valeur= -$this->PA;
								}
				//if($this->PA < 0){ return false;}				
			}
			
			if($valeur != 0){
				$this->PA = Min($this->PA + $valeur,$this->GetPAMax()+$this->getRPA());
				$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET LASTACTION = '".time()."', PA = ".$this->PA." WHERE id_perso = ".$this->ID;
				$result = $db->sql_query($SQL);
				return $result;
			}
			return true;
		}


		/** 
		    \param nocheck est a vrai lors d'une attaque recue qui prend des PI par exemple.
		    dans ce cas, il faut descendre les PI. S'il n'y en a pas assez, on limite &agrave; 0
		    \return retourne false s'il n'y avait pas assez de PI

		*/    
		function ModPI($valeur, $nocheck=false ){
			global $db;
			if($this->RIP()) { return false;}				
			if($this->Archive) { return false;}				
			if($valeur < 0){//ca coute des PIs
				if ($nocheck==false)
					if($this->PI < abs($valeur)){return false;}
				else 	if($this->PI < abs($valeur)){
								//on ne descend pas plus que 0
								$valeur= -$this->PI;
								}
				//if($this->PI < 0){ return false;}				
			}
			
			if($valeur != 0){
			        $temp = Min($this->PI + $valeur,$this->GetPIMax()+$this->getRPI());
				if ($this->PI !=  $temp) {
				        $this->PI=$temp;
        				$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET LASTACTION = '".time()."', PI = ".$this->PI." WHERE id_perso = ".$this->ID;
				$result=$db->sql_query($SQL);
				return $result;
			}
			}
			return true;
		}

		function RemisePAs(){
			global $db;
			global $PERSO, $id_joueur, $HTTP_SERVER_VARS;
			if(!$this->RIP() && $this->Archive == 0){
				if ($this->interval_remisepa==0)
					$nb_remiseAFaire =1;
				else	
				$nb_remiseAFaire = floor((time() - $this->Derniere_RemisePA)/($this->interval_remisepa * 3600));
				if ($nb_remiseAFaire>=1) {
				        logdate (" PV = " . $this->PV .  " RPV = " . $this->getRPV() ." PVMax = " . $this->GetPVMax());
              			        if (defined("REMISE_PV") && REMISE_PV == "TOTALE" ) {
                                                $this->PV = Max(0,$this->getRPV() + $this->GetPVMax());
                                                $this->PA = Max(0,$this->getRPA() + $this->GetPAMax());
                                        }        
                                        else {
					$this->PV = Min($this->getRPV()*$nb_remiseAFaire+$this->PV,$this->GetPVMax()+$this->getRPV());				
        					$this->PA = Max(0,Min($this->PA+$this->getRPA()*$nb_remiseAFaire ,$this->GetPAMax()+$this->getRPA()));
                                        }
					$this->PO= Max(0,$this->PO +$nb_remiseAFaire*$this->getRPO());
					$this->Derniere_RemisePA += ($this->interval_remisepa*3600*$nb_remiseAFaire);
					$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET ";
					if ($id_joueur == $this->ID) {
						$IP = substr($HTTP_SERVER_VARS['REMOTE_ADDR'],0,9);
						$SQL.=" IP_Joueur = '$IP', LASTACTION = '".time()."', ";
					}
					$SQL.=" po=". $this->PO.", pa = ".$this->PA.", pv = ".$this->PV.",pi = ".$this->PI.", derniere_remisepa = ".$this->Derniere_RemisePA.", archive=0 WHERE id_perso = ".$this->ID;
		   			if ($db->sql_query($SQL)!==false) {
						$this->OutPut(GetMessage("remisepa"), false);	
						return true;
					}	
					else return false;	
				}
				else return false;
			}
			return false;
		}

		function RemisePIs(){
			global $PERSO, $id_joueur, $HTTP_SERVER_VARS;
			global $db;
			if(!$this->RIP() && $this->Archive == 0){
				if ($this->interval_remisepi==0)
					$nb_remiseAFaire =1;
				else
					$nb_remiseAFaire = floor((time() - $this->Derniere_RemisePI)/($this->interval_remisepi * 3600));
				if ($nb_remiseAFaire >=1) {
        			        if (defined("REMISE_PI") && REMISE_PI == "TOTALE" ) 
                                                $this->PI = Max(0,$this->getRPI() + $this->GetPIMax());
                                        else 
					$this->PI = Max(0,Min($this->PI+$this->getRPI()*$nb_remiseAFaire ,$this->GetPIMax()+$this->getRPI()));
					$this->Derniere_RemisePI += ($this->interval_remisepi*3600*$nb_remiseAFaire);
					$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET ";
					if ($id_joueur == $this->ID) {
						$IP = substr($HTTP_SERVER_VARS['REMOTE_ADDR'],0,9);
						$SQL.=" IP_Joueur = '$IP', LASTACTION = '".time()."', ";
					}
					$SQL.=" po=". $this->PO.", pa= ".$this->PA.", pv= ".$this->PV.",pi = ".$this->PI.", derniere_remisepi = ".$this->Derniere_RemisePI.", archive=0 WHERE id_perso = ".$this->ID;
		   			if ($db->sql_query($SQL)!==false) {
						$this->OutPut(GetMessage("remisepi"), false);	
						return true;
					}	
					else return false;	
				}
				else return false;
			}
			else return false;
		}
		
		function RIP() {
		   if($this->PV > 0)
		   	return false;
		   	else return true;
		}

		function GetPIMax(){ 
			global $liste_comp_full; 
			if (defined("BASE_PIS"))
				$pi=BASE_PIS;
			else $pi=20;
			return $pi+($this->GetNiveauComp($liste_comp_full["Intelligence"],true)*2);
		}

                /**
                       /brief retourne la valeur Maxi que peuvent attenindre les PA du PJ
                */
		function GetPAMax(){ 
			global $liste_comp_full; 
			if (defined("BASE_PAS"))
				$pa=BASE_PAS;
			else $pa=20;
			return $pa+($this->GetNiveauComp($liste_comp_full["Sagesse"],true)*2);
		}
		
		function GetPVMax(){ 
			global $liste_comp_full; 
			if (defined("BASE_PVS"))
				$pv=BASE_PVS;
			else $pv=25;
			return $pv+($this->GetNiveauComp($liste_comp_full["Constitution"],true)*4);
		}
		
		function GetXPMax(){ return Max(10000,$this->XP+100);}

		function getRPO(){
			global $liste_caracs;
			if (defined("QUANTITE_REMISE_POS"))
				$rpo=QUANTITE_REMISE_POS;
			else $rpo="0";						
			$retour = $rpo+ floor($this->GetNiveauComp($liste_caracs["Charisme"],true,true)/5);
			$nbSpecs=count($this->Specs);
			for($i=0;$i<$nbSpecs;$i++){
				$retour += $this->Specs[$i]->rpo;
			}
			$nbEtats=count($this->EtatsTemp);
			for($i=0;$i<$nbEtats;$i++){
				$retour += $this->EtatsTemp[$i]->rpo;
			}			
			return $retour;
		}
		function getRPI(){
			global $liste_comp_full;
			if (defined("QUANTITE_REMISE_PIS"))
				$rpi=QUANTITE_REMISE_PIS;
			else $rpi="5";						
			$retour = $rpi+ round($this->GetNiveauComp($liste_comp_full["Intelligence"],true,true)/2);
			$nbSpecs=count($this->Specs);
			for($i=0;$i<$nbSpecs;$i++){
				$retour += $this->Specs[$i]->rpi;
			}
			$nbEtats=count($this->EtatsTemp);
			for($i=0;$i<$nbEtats;$i++){
				$retour += $this->EtatsTemp[$i]->rpi;
			}			
			return Max(0,$retour);
		}


		/** \brief retourne la quantite de PA a ajouter par remise. 
		        Ce nombre la somme de toutes les RPA des etats temps et des specialites du PJ
		                + bonus suivant la sagesse
		                + la quantite parametree dans l'admin du jeu QUANTITE_REMISE_PAS
		*/
		function getRPA(){
			global $liste_comp_full;
			if (defined("QUANTITE_REMISE_PAS"))
				$rpa=QUANTITE_REMISE_PAS;
			else $rpa="5";						
			$retour = $rpa+ round($this->GetNiveauComp($liste_comp_full["Sagesse"],true,true)/2);
			$nbSpecs=count($this->Specs);
			for($i=0;$i<$nbSpecs;$i++){
				$retour += $this->Specs[$i]->rpa;
			}
			$nbEtats=count($this->EtatsTemp);
			for($i=0;$i<$nbEtats;$i++){
				$retour += $this->EtatsTemp[$i]->rpa;
			}			
			return Max(0,$retour);
		}
		function getRPV(){
			global $liste_comp_full;
			if (defined("QUANTITE_REMISE_PVS"))
				$rpv=QUANTITE_REMISE_PVS;
			else $rpv="5";	
			$retour = $rpv+ round($this->GetNiveauComp($liste_comp_full["Constitution"],true,true)/2);
			$nbSpecs=count($this->Specs);
			for($i=0;$i<$nbSpecs;$i++){
				$retour += $this->Specs[$i]->rpv;
			}
			$nbEtats=count($this->EtatsTemp);
			for($i=0;$i<$nbEtats;$i++){
				$retour += $this->EtatsTemp[$i]->rpv;
			}			
			return $retour;
		}
	
		function ModPO($valeur,$nocheck=false){
			global $db;
			if ($valeur!=0) {
				if($nocheck){
					$this->PO = Max(0,$this->PO + $valeur);
				} else {
					if($valeur <= 0){//ca coute des POs
						if($this->PO < abs($valeur)){return false;}
						$this->PO += $valeur ;
					} else { // C du bonus, c plaisir
						$this->PO += $valeur;
					}
				}
				$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET lastaction = '".time()."', po = ".$this->PO." WHERE id_perso = ".$this->ID;
				$result=$db->sql_query($SQL);
				return $result;
			}
			return true;
		}

		function isPNJ() {
			return $this->pnj;		
		}

		function isEnnemi() {
			if ($this->Reaction == 4)
				return true;
			else return false;			
		}
		
		/**
		* \return retourne false s'il n'y avait pas assez de PV et que PERSO meurt
		*/
		function ModPV($valeur){
			global $db;
			if ($valeur!=0) {
			        $temp = Min($this->PV + $valeur,$this->GetPVMax() + $this->getRPV());
			        if ($temp !=$this->PV) {
        				$this->PV = $temp;
        				$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET LASTACTION = '".time()."', PV = ".$this->PV." WHERE id_perso = ".$this->ID;
				if ($db->sql_query($SQL))
					if(($valeur < 0) && ($this->PV <=0)){
						$this->Mourir();
        						return false;
        					}
					}
			}
		        if ($this->PV <=0)
			return false;
			return true;
		}

		function ModBanque($valeur,$nocheck=false){
			global $db;
				if($nocheck){
					$this->banque = Max(0,$this->banque + $valeur);
				} else {
					if($valeur <= 0){//on retire
						if($this->banque < abs($valeur)){return false;}
						$this->banque += $valeur ;
					} else { // On depose
						$this->banque += $valeur;;
					}
				}
			$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET LASTACTION = '".time()."', banque = ".$this->banque." WHERE id_perso = ".$this->ID;
   			if ($db->sql_query($SQL)!==false)
				return true;
			else return false;	
		}

		function Mourir(){
			global $liste_comp_full;
			global $db;
			$this->ModPA(-$this->PA-1);
			$nbSorts=count($this->Sorts);
			for($i=0;$i<$nbSorts;$i++){
				$this->Sorts[$i]->TestOublieMort(Min(7,$this->GetNiveauComp($liste_comp_full["Intelligence"],true)) );
			}
			$SQL="update ".NOM_TABLE_REGISTRE." set moment_mort = '".time()."' WHERE id_perso = ".$this->ID;
		        $db->sql_query($SQL);
			$SQL="DELETE FROM ".NOM_TABLE_ENGAGEMENT." WHERE id_perso = ".$this->ID." OR id_adversaire = ".$this->ID;
   			if ($db->sql_query($SQL)!==false)
				return true;
			else return false;	
		}

		function getPlaceSacMax(){ global $liste_comp_full; return 30+($this->GetNiveauComp($liste_comp_full["Force"],true)*15);}
		function getPlaceGrimoireMax(){ global $liste_comp_full; return 30+($this->GetNiveauComp($liste_comp_full["Intelligence"],true)*15);}
		function getPlaceSacUtilisee(){ 
			if($this->Objets == null){ return 0;}
			$total =0;
			$nbObjs=count($this->Objets);
			for($i=0;$i<$nbObjs;$i++){
				$total += $this->Objets[$i]->poids;
			}
			return $total;
		}
	
		function getPlaceGrimoireUtilisee(){ 
			$total =0;
			if($this->Sorts == null){ return 0;}
			$nbSorts=count($this->Sorts);
			for($i=0;$i<$nbSorts;$i++){
				$total += $this->Sorts[$i]->place;
			}
			return $total;
		}

		/** 
		*    \param $poidsSupprime est le poids des objets deja existants mais que l'on combine pour obtenir un autre objet. Comme ces objets vont etre supprimes, il ne faut plus les compter dans l'inventaire
		*/  
		function sacPeutContenir($poids=0, $poidsSupprime=0){
			return ( ( $this->getPlaceSacMax() - ($this->getPlaceSacUtilisee() -$poidsSupprime)) >= $poids);
		}
		function grimoirePeutContenir($poids=0){
			return ( ( $this->getPlaceGrimoireMax() - $this->getPlaceGrimoireUtilisee() ) >= $poids);
		}

		function GetCheminFA(){
			return "../fas/pj_".$this->ID.".fa";
		}

		
		function ArchiveFA($force=FALSE,$contenu='') {        
	                global $template_head;
			$fagz = $this->GetCheminFA();
			if(file_exists($fagz)){	
			        if ($contenu=='')
				$contenu= $this->LireFA(0);
				EnvoyerMail($this->email,$this->email,"[".NOM_JEU." - Votre FA a été archivé]","Bonjour ".$this->nom."; voici votre FA: <br />" . $contenu );
				if (defined("MAIL_FA_ARCHIVES") &&  MAIL_FA_ARCHIVES!="")
				        EnvoyerMail("",MAIL_FA_ARCHIVES,"[".NOM_JEU." - FA de ".$this->nom . " archivé]","Bonjour , voici le FA de ".$this->nom . ": <br />" . $contenu );
				if ((unlink($fagz))===false)
					logDate( "Impossible d'effacer le fichier '".$fagz."'",E_USER_WARNING,1);
				else {	
					$msg = "Fa ";
					if( defined("SESSION_POUR_MJ")){								
						$msg .= " de ". span($this->nom,"pj");
					}	
					if ($force)
						$msg .=" trop important => automatiquement ";
					$msg.= "  effac&eacute; et envoy&eacute; par mail.";
	/*				global $PERSO;
					if (isset($PERSO)) {
						if ($this->ID== $PERSO->ID)
							$this->OutPut($msg,true,true);
					}
					else $this->OutPut($msg,false,true);			*/
					if( defined("SESSION_POUR_MJ")){								
						global $MJ;
						$MJ->OutPut($msg,true,true);
					}		
					else $this->OutPut($msg,true,true);		
				}
			}	
		}

		function LireFA($boutoninclus=1) {
			$fagz = $this->GetCheminFA();
			
			if(file_exists($fagz)){	
				if (!extension_loaded('zlib')) {
					if (($f = fopen($fagz,"rb"))!==false) {					
						//le fois 2 en cas de debordement avant archivage
						//$contenu_tmp = fgets($f, TAILLE_MAX_FA*1024*2);
						//$contenu_tmp = fread($f, TAILLE_MAX_FA*1024*2);
						$contenu_tmp = '';
            while (!feof($f) && $contenu_tmp!==FALSE) {
              $contenu_tmp .= fread($f, 8192);
            }						
						if (fclose($f)===false)
							logDate( "Probleme à la fermeture de '".$fagz."'",E_USER_WARNING,1);
					}
					else logDate ("impossible d'ouvrir le fichier '".$fagz."'",E_USER_WARNING,1);
				} 
				else {
						$zp = gzopen($fagz, "rb");
						//$contenu_tmp = gzread($zp, TAILLE_MAX_FA*1024*2);
						$contenu_tmp = '';
            while (!gzeof($zp) && $contenu_tmp!==FALSE) {
              $contenu_tmp .= gzread($zp, 8192);
            }							
						if (gzclose($zp)===false)
							logDate( "Probleme à la fermeture de '".$fagz."'",E_USER_WARNING,1);						
				}
				
				$tailleFA = filesize ($fagz);
				//logDate("taille" . $tailleFA);
				
				if ($boutoninclus && !(! defined("SESSION_POUR_MJ") && ($tailleFA> TAILLE_MAX_FA*1024))) 
					$contenu = 	"<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>
								<input type='hidden' name='del' value='1' />
								<input type='hidden' name='etape' value='1' />
								<input type='hidden' name='id_cible' value='".$this->ID."' />
								<input type='submit' value='Reseter' /></form></div>";
        else $contenu="";								
				$contenu .= $contenu_tmp;
				//si ce n'est pas un MJ qui lit le fa du pj => archivage auto si trop gros
				if(! defined("SESSION_POUR_MJ")){					
					if ($tailleFA> TAILLE_MAX_FA*1024) {
						$this->ArchiveFA(TRUE,$contenu_tmp);
					}	
				}
			} 
			else 
				$contenu ="FA vide ou inexistant";
			return $contenu;
		}
	
		function EcrireFA($msg,$date=true,$filtreHTML=true){
			global $db;
			global $PERSO;
			$fagz=$this->GetCheminFA();
			if(! file_exists ( dirname($fagz)))
				if (! mkdir(dirname($fagz),0700))
					logDate ("impossible de créer le rep " .dirname($fagz),E_USER_WARNING,1);
			if ($filtreHTML)
				$msg= stripslashes(nl2br($msg));			
			if (!extension_loaded('zlib')) {
				if(file_exists($fagz)){
					if (($f = fopen($fagz,"rb"))!==false) {	
						//$contenu = fread($f, TAILLE_MAX_FA*1024*2);
						$contenu = '';
            while (!feof($f) && $contenu!==FALSE) {
              $contenu .= fread($f, 8192);
            }
						
						if (fclose($f)===false)
							logDate( "Probleme à la fermeture de '".$fagz."'",E_USER_WARNING,1);
					}
					else logDate ("impossible d'ouvrir le fichier '".$fagz."'",E_USER_WARNING,1);
				} else 	$contenu="";				
				if (($f = fopen($fagz,"r+b"))!==false) {				
					if($date){
						$writetemp= fwrite($f,span(faitDate(time(),true),"date")."<br />");
					}	
					if ($writetemp===false)
						logDate( "Probleme à l'écriture de '".$fagz."'",E_USER_WARNING,1);
					else {	
						if (fwrite($f,$msg."<br />&nbsp;<br />")===false) {
							logDate( "Probleme à l'écriture de '".$fagz."'",E_USER_WARNING,1);
						}
						else
						if (fwrite($f,$contenu)===false) {
							logDate( "Probleme à l'écriture de '".$fagz."'",E_USER_WARNING,1);
						}
						if (fclose($f)===false)
							logDate( "Probleme à la fermeture de '".$fagz."'",E_USER_WARNING,1);
					}
				}
				else logDate ("impossible d'ouvrir le fichier '".$fagz."'",E_USER_WARNING,1);
			} 
			else {
				if(file_exists($fagz)){
					$zp = gzopen($fagz, "rb");
					if ($zp!=FALSE) {
						//$contenu = gzread($zp, TAILLE_MAX_FA*1024*2);
						$contenu = '';
            while (!gzeof($zp) && $contenu!==FALSE) {
              $contenu .= gzread($zp, 8192);
            }						
						if (gzclose($zp)==FALSE)
							logDate("Probleme à la fermeture de ".$fagz);
					}		    else logDate("Probleme à l'ouverture de ".$fagz);			
				}	else $contenu="";
				$contenu=$msg."<br />&nbsp;<br />".$contenu;
				if($date)
					$contenu=span(faitDate(time(),true),"date")."<br />" . $contenu;

				$zp = gzopen($fagz, "w9");
				if ($zp!=FALSE) {
					    gzwrite($zp, $contenu);
						if (gzclose($zp)==FALSE)
							logDate("Probleme à la fermeture de ".$fagz);			
				}	    else 					logDate("Probleme à l'ouverture de ".$fagz);			
			
			}
			if($this->wantmail==1 && isset($PERSO) && $PERSO->ID<>$this->ID){
				if($this->FANonLu == 0){
					$this->FANonLu = 1;
				}
				if( ($this->lastaction < (time()-10*60)) && ($this->FANonLu == 1) ){
						$this->FANonLu = 2;
						$valeurFA=array();
						$valeurFA[1]=$this->nom;
						 EnvoyerMail("",$this->email,"[".NOM_JEU." - ". GetMessage("FaModifieSujetMail") ."]",GetMessage("mailPJFA",$valeurFA));
				}
				
				$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET LASTACTION = '".time()."', FANonLu = '".$this->FANonLu."' WHERE id_perso = ".$this->ID;
				$db->sql_query($SQL);
			}
		}

		function OutPut($msg,$echo=true,$date=true,$filtreHTML=true){
			global $template_main;
			$this->EcrireFA($msg,$date,$filtreHTML);
			if($echo){$template_main.= stripslashes($msg)."<br />";}
		}

		function SupprimerSortPrefere() {		
			global $db;
			unset($this->SortPrefere);
			$SQL = "update ".NOM_TABLE_PERSO." set sortprefere = '' where id_perso =".$this->ID;
			if ($db->sql_query($SQL)!==false)
				return true;
			else return false;	
		}	

		/** 
		    $poidsSupprime est le poids des objets deja existants mais que l'on combine pour obtenir un autre objet. Comme ces objets vont etre supprimes, il ne faut plus les compter dans l'inventaire
		*/  
		function AcquerirObjet($Objet,$poidsSupprime=0) {
			global $db;
			if(! $this->sacPeutContenir($Objet->poids, $poidsSupprime)) return false;
			$SQL = "INSERT into ".NOM_TABLE_PERSOOBJET." ( id_perso,  id_objet, durabilite, munitions ,
			   temporaire,   equipe  ) values (".$this->ID.",".$Objet->ID.",". $Objet->durabilite.","
			   .$Objet->munitions.",0,0)";
   			if ($db->sql_query($SQL)!==false)
				return true;
			else return false;	
		}


/*		function AcquerirObjet($IDObjet) {
			$Objet = new Objet($IDObjet);
			if(! $this->sacPeutContenir($Objet->poids)) return false;
			$SQL = "INSERT into ".NOM_TABLE_PERSOOBJET." ( id_perso,  id_objet, durabilite, munitions ,
			   temporaire,   equipe  ) values (".$this->ID.",".$IDObjet.",". $Objet->durabilite.","
			   .$Objet->munitions.",0,0)";
   			$db->sql_query($SQL);
		return true;
		}
*/
		function EffacerObjet($IDClef){
			global $db;
			$SQL = "DELETE FROM ".NOM_TABLE_PERSOOBJET." WHERE id_clef = ".$IDClef;
   			if ($db->sql_query($SQL)!==false)
				return true;
			else return false;	
		}

		function RetirerEtatTemp($id_etattemp){
			global $db;
			$SQL = "DELETE FROM ".NOM_TABLE_PERSOETATTEMP." WHERE id_etattemp = ".$id_etattemp." AND id_perso = ".$this->ID;
   			if ($db->sql_query($SQL)!==false)
				return true;
			else return false;	
		}

		function AjouterEtatTemp($id_etattemp,$duree=3){
			global $db;
			//On verifie deja qu'il existe pas
			$trouve = false;
			$nbEtats=count($this->EtatsTemp);
			$i=0;
			while (($i<$nbEtats) && ($trouve==false)) {
				if($this->EtatsTemp[$i]->ID == $id_etattemp){$trouve=true;}
				else $i++;
			}
			
			if($trouve){//UPDATE
				if($duree == -1){$fin = "-1";}else{$fin = "(Fin+".($duree*3600).")"; }
				$SQL = "UPDATE ".NOM_TABLE_PERSOETATTEMP." SET fin = ".$fin." WHERE id_etattemp = ".$id_etattemp." AND id_perso = ".$this->ID;
			} else { //INSERT
				if($duree == -1){$fin = "-1";}else{$fin = (time()+($duree*3600)); }
				$SQL = "INSERT INTO ".NOM_TABLE_PERSOETATTEMP." (id_perso,id_etattemp,fin) VALUES('".$this->ID."','".$id_etattemp."','".$fin."')";
			}
   			if ($db->sql_query($SQL)!==false)
				return true;
			else return false;	
		}

		function GererChaineEtatTemp($chaine,$reussite_auto=false,$forcer_retirer=false){ 
			logDate("dans gererchaineetat :  $chaine,$reussite_auto,$forcer_retirer");
			//Nous sert pour que les objets et sorts qui touchent ajoutent/annulent leurs etats
			//forcer_retirer nous dit si les etats doivent obligatoirement etre ajoutee/enleve et de facon permanente du fait que c un lieu ou une piece d'armure
			$retour=array("ajouter"=>"","retirer"=>"");
			$etats = $chaine;
			if($etats != ""){
				$etats=explode("|",$etats);
				$nb_etats = count($etats) -1; //-1 car il y a un | à la fin du dernier
				for($i=0;$i<$nb_etats;$i++){
			
					$temp = explode(";",$etats[$i]);
					if(!$reussite_auto){
						if(LanceDe(100) < ($temp[1] +1) ){ 
							$toto = new EtatTemp(abs($temp[0]));							
							if($toto->Existe){
								if( ($forcer_retirer) || ($temp[0] < 0)){
									$this->RetirerEtatTemp(abs($temp[0]));
									$retour["retirer"] .= $toto->nom.",";
								}else{
									$this->AjouterEtatTemp(abs($temp[0]),$temp[2]);
									$retour["ajouter"] .= $toto->nom.",";
								}
							}
							else {
							        logDate(GetMessage("EtatTempKO", array( abs($temp[0]))));
							}        
						}
					} else {
						$toto = new EtatTemp(abs($temp[0]));
						if($toto->Existe){
							if( ($forcer_retirer) || ($temp[0] < 0)){
								$this->RetirerEtatTemp(abs($temp[0]));
								$retour["retirer"] .= $toto->nom.",";
							}else{
								$this->AjouterEtatTemp(abs($temp[0]),-1);
								$retour["ajouter"] .= $toto->nom.",";
							}
						}
					}
				}
			}
			if ($retour["ajouter"] == ""){$retour["ajouter"] = "rien";}
			if ($retour["retirer"] == ""){$retour["retirer"] = "rien";}
			return $retour;
		}

		function AbsorptionDegats($typeDeg,$degats=1){
			$retour = $degats;
			$nbObjets=count($this->Objets);
			$i = 0;
			while ($i < $nbObjets && $retour >0) {
				if($this->Objets[$i]->equipe == 1){
					if($this->Objets[$i]->type=="Armure"){
						if($this->Objets[$i]->competence==$typeDeg){
							$degatsAbsorbes= $this->Objets[$i]->Degats[0] + lanceDe($this->Objets[$i]->Degats[1]-$this->Objets[$i]->Degats[0]);
							$retour -= $degatsAbsorbes;
							$this->Objets[$i]->Abime($degatsAbsorbes);
						}
					}
				}
				$i++;
			}
			$retour =Max(0,$retour);
			return $retour;
		}
		
		function PossedeObjet($id_objet){
        		$nb_objets=count($PERSO->Objets);
        		$trouve=false;
                        $i = 0;
			while ($i < $nbObjets && $trouve===false) {
        			if($PERSO->Objets[$i]->ID == $id_obj)
        			        $trouve=true;
        			else $i++;                        
        		}
        		return $trouve;
		}
				
		//SERIE DE VERIF
		function PossedeSortSoin($typeMagie=1){
		/**
			filtres:
				$typeMagie (1 pour Sort sur 1PJ dans le lieu du lanceur
					    2 pour Sort sur 1 lieu proche du lanceur	
					    3 pour Sort sur 1PJ eloigne du lanceur
		*/			
			$nbSorts = count($this->Sorts);
			$trouve=false;
			$i=0;
			while ((!$trouve) && $i< $nbSorts) {
				if($this->Sorts[$i]->Soustype == 'Soin'){ 
					if (($typeMagie==1) && ($this->Sorts[$i]->SortDistant==0) && ($this->Sorts[$i]->TypeCible==1 ||$this->Sorts[$i]->TypeCible==3))
						$trouve=true;
					else	
					if (($typeMagie==2) && ($this->Sorts[$i]->TypeCible==2))
						$trouve=true;						
					else	
					if (($typeMagie==3) && ($this->Sorts[$i]->SortDistant==1 && $this->Sorts[$i]->TypeCible==1))
						$trouve=true;						
					else $i++;
				}
				else $i++;	
			}
			return $trouve;
		}


		function PossedeSortResurrection($typeMagie=1){
		/**
			filtres:
				$typeMagie (1 pour Sort sur 1PJ dans le lieu du lanceur
					    2 pour Sort sur 1 lieu proche du lanceur	
					    3 pour Sort sur 1PJ eloigne du lanceur
		*/			
			$nbSorts = count($this->Sorts);
			$trouve=false;
			$i=0;
			while ((!$trouve) && $i< $nbSorts) {
				if($this->Sorts[$i]->Soustype == 'Resurrection'){ 
					if (($typeMagie==1) && ($this->Sorts[$i]->SortDistant==0) && ($this->Sorts[$i]->TypeCible==1 ||$this->Sorts[$i]->TypeCible==3))
						$trouve=true;
					else	
					if (($typeMagie==2) && ($this->Sorts[$i]->TypeCible==2))
						$trouve=true;						
					else	
					if (($typeMagie==3) && ($this->Sorts[$i]->SortDistant==1 && $this->Sorts[$i]->TypeCible==1))
						$trouve=true;						
					else $i++;
				}
				else $i++;	
			}
			return $trouve;
		}		

		function PossedeSortOffensif($typeMagie=1){
		/**
			filtres:
				$typeMagie (1 pour Sort sur 1PJ dans le lieu du lanceur
					    2 pour Sort sur 1 lieu proche du lanceur	
					    3 pour Sort sur 1PJ eloigne du lanceur
		*/
			$nbSorts = count($this->Sorts);
			$trouve=false;
			$i=0;
			while ((!$trouve) && ($i< $nbSorts)) {
				if( 
					($this->Sorts[$i]->Soustype == 'Attaque') ||
					($this->Sorts[$i]->Soustype == 'Paralysie')	||				
					($this->Sorts[$i]->Soustype == 'Transfert')
				)
				{

						/* Pour rappel:
						$liste_type_cible=array(
							1=>"1 PJ",
							2=>"1 Zone",
							3=>"Lanceur"	*/
					if (($typeMagie==1) && ($this->Sorts[$i]->SortDistant==0) && ($this->Sorts[$i]->TypeCible==1 ||$this->Sorts[$i]->TypeCible==3))
						$trouve=true;
					else	
					if (($typeMagie==2) && ($this->Sorts[$i]->TypeCible==2))
						$trouve=true;						
					else	
					if (($typeMagie==3) && ($this->Sorts[$i]->SortDistant==1 && $this->Sorts[$i]->TypeCible==1))
						$trouve=true;						
					else $i++;
				}
				else $i++;
			}
			return $trouve;
		}

		function PossedeSortTeleport($typeMagie=1){
		/**
			filtres:
				$typeMagie (1 pour Sort sur 1PJ dans le lieu du lanceur
					    2 pour Sort sur 1 lieu proche du lanceur 	
					    3 pour Sort sur 1PJ eloigne du lanceur
		*/
			$nbSorts = count($this->Sorts);
			$trouve=false;
			$i=0;
			while ((!$trouve) && $i< $nbSorts) {
				if($this->Sorts[$i]->Soustype == 'Teleport'){
					if (($typeMagie==1) && ($this->Sorts[$i]->SortDistant==0) && ($this->Sorts[$i]->TypeCible==1 ||$this->Sorts[$i]->TypeCible==3))
						$trouve=true;
					else	
					if (($typeMagie==2) && ($this->Sorts[$i]->TypeCible==2))
						$trouve=true;						
					else	
					if (($typeMagie==3) && ($this->Sorts[$i]->SortDistant==1 && $this->Sorts[$i]->TypeCible==1))
						$trouve=true;						
					else $i++;
				}
				else $i++;
			}
			return $trouve;
		}

		function PossedeSortTeleportSelf($typeMagie=1){
			$nbSorts = count($this->Sorts);
			$trouve=false;
			$i=0;
			while ((!$trouve) && $i< $nbSorts) {
				if($this->Sorts[$i]->Soustype == 'Teleport Self'){
					if (($typeMagie==1) && ($this->Sorts[$i]->SortDistant==0) && $this->Sorts[$i]->TypeCible==3)
						$trouve=true;
					else $i++;
				}
				else $i++;
			}
			return $trouve;
		}

		function getDescription() {
			if ($this->description=="") {
				$nom_fichier = "../pjs/descriptions/desc_".$this->ID.".txt";
				if(! file_exists($nom_fichier)){
					$nom_fichier= "../pjs/descriptions/nodesc.txt";
				}
				$content_array = file($nom_fichier);
				$content = implode("", $content_array);
				//$content= nl2br($content);
				$this->description=stripslashes($content);	
			}
			return $this->description;
		}

		function setDescription($descriptionSaisie) {
			$nom_fichier = "../pjs/descriptions/desc_".$this->ID.".txt";
			$description = str_replace("<?php","",$descriptionSaisie);
			$description = str_replace("?>","",$description);
			if (($f = fopen($nom_fichier,"w+b"))!==false) {
				if (fwrite($f,$description)===false) {
					logDate( "Probleme à l'écriture de '".$nom_fichier."'",E_USER_WARNING,1);
				}
				if (fclose ($f)===false)
					logDate( "Probleme à la fermeture de '".$nom_fichier."'",E_USER_WARNING,1);
			}	
			else logDate( "impossible d'ouvrir le fichier '".$nom_fichier."' en ecriture",E_USER_WARNING,1);
		
		}


		function DescriptionBackGround() {
			$temp = array();
			$temp[0]="<div class ='centerSimple'>Description</div>";
			$temp[1]="<div class ='centerSimple'>Background</div>";
			$temp[2]=nl2br($this->description);
			$temp[3]=$this->backGround;
			return $temp;		
		}	

		//FONCTION D'AFFICHAGE
		function DescriptionGenerale(){
			//Renvois les infos sous forme d'array pour le premier tableau du status
			global $phpExtJeu;
			global $template_name;
			$chaine_spec = "";
			$nbSpecs=count($this->Specs);
			for($i=0;$i<$nbSpecs;$i++){
				if($this->Specs[$i]->ID != 1){
					$chaine_spec .= "-<a href=\"javascript:a('../bdc/spec.$phpExtJeu?";
					if (defined("PAGE_ADMIN"))
						$chaine_spec .= "for_mj=1&amp;";
					$chaine_spec .="num_spec=".$this->Specs[$i]->ID."')\">".span($this->Specs[$i]->nom,"specialite")."</a><br />";
				}else {
					$chaine_spec .= "-".span($this->Specs[$i]->nom,"specialite")."<br />";
				}
			}

			$temp = array(
						"nom",span($this->nom,"pj")
						);

			$nbEtats=count($this->EtatsTemp);
			$i=0;
			while ($i<$nbEtats) {
				if($this->EtatsTemp[$i]->TypeEstCritereinscription>=1) {
					$str_etat = "<a href=\"javascript:a('../bdc/etat.$phpExtJeu?";
					if (defined("PAGE_ADMIN"))
						$str_etat .= "for_mj=1&amp;";			
					$str_etat .="num_etat=".$this->EtatsTemp[$i]->ID."')\">".span($this->EtatsTemp[$i]->nom,"race")."</a> ";
					if (file_exists("../templates/$template_name/images/".$this->EtatsTemp[$i]->nom.".gif"))
						$str_etat.="<img src='../templates/$template_name/images/".$this->EtatsTemp[$i]->nom.".gif' border='0' alt='".$this->EtatsTemp[$i]->nom."' />";				
		
					array_push($temp,$this->EtatsTemp[$i]->Type, $str_etat);					
				}	
				$i++;
			}

			array_push($temp,"Lieu",span($this->Lieu->nom,"lieu"));

			if($this->getRPA() < 0) array_push($temp,"PAs",span($this->PA."/".$this->GetPAMax()." ".$this->getRPA(),"pa"));
				else array_push($temp,"PAs",span($this->PA."/".$this->GetPAMax()." +".$this->getRPA(),"pa"));

			if($this->getRPI() < 0) array_push($temp,"PIs",span($this->PI."/".$this->GetPIMax()." ".$this->getRPI(),"pi"));
				else array_push($temp,"PIs",span($this->PI."/".$this->GetPIMax()." +".$this->getRPI(),"pi"));


			if ((AFFICHE_XP==1) ||  defined("SESSION_POUR_MJ"))  
				array_push($temp,"XP",span($this->XP."/".$this->GetXPMax(),"xp"));

			if ((AFFICHE_PV==1) ||  defined("SESSION_POUR_MJ"))  
				if($this->getRPV() < 0) array_push($temp,"PVs",span($this->PV."/".$this->GetPVMax()." ".$this->getRPV(),"pv"));
					else array_push($temp,"PVs",span($this->PV."/".$this->GetPVMax()." +".$this->getRPV(),"pv")); 

			if($this->getRPO() < 0) array_push($temp,"Argent",span($this->PO." ".$this->getRPO(),"po"));
				else array_push($temp,"Argent",span($this->PO." +".$this->getRPO(),"po"));
			array_push($temp,"En Banque",span($this->banque,"po"));
			array_push($temp,"Sp&eacute;cialit&eacute;",$chaine_spec);
			if($this->Groupe<>"" && (defined("GROUPE_PJS") && GROUPE_PJS==1)) {
				$NomGroupePJ = new Groupe($this->Groupe, false);				
				$GroupePJ = "<a href=\"javascript:a('../bdc/groupe.$phpExtJeu?num_groupe=".$this->Groupe."')\">".span($NomGroupePJ->nom,"etattemp")."</a> ";
				array_push($temp,"Groupe",span($GroupePJ,"etattemp"));
			}
			//else $GroupePJ="-" ;			
			return $temp;
			
		}

		function DescriptionAvatar(){
			if (defined("AFFICHE_AVATAR_FORUM") && AFFICHE_AVATAR_FORUM  ) {
				if ($this->imageforum<>"")
					$temp= $this->imageforum;
				else $temp= "../pjs/PasAvatar.png";	
					$temp="<img src='$temp' border='0' alt=\"image de l'avatar de ".$this->nom."\" />";
			}
			else $temp="";
			return $temp;		
		}	


		function DescriptionCaracteristiques(){
			global $liste_caracs;
			$toto = array_keys($liste_caracs);
			$tata = array_values($liste_caracs);
			$temp=array();
			$nbCarac=count($liste_caracs);
			for($i=0;$i<$nbCarac;$i++){
				$temp[($i*3)]=GetImage($toto[$i]);
				$temp[($i*3)+1]=$toto[$i];
				$temp[($i*3)+2]=span($this->GetNiveauComp($tata[$i],true),"comp");
				$bonus = $this->GetBonusComp($tata[$i]);
				if($bonus != 0){
					if($bonus > 0){
						$temp[($i*3)+2] .= span(" (+".$bonus.")","bonus");
					} else {
						$temp[($i*3)+2] .= span(" (".$bonus.")","malus");
					}
				}
			}
			return $temp;
		}


		function DescriptionArtisanales(){
			global $liste_artisanat;
			$toto = array_keys($liste_artisanat);
			$tata = array_values($liste_artisanat);
			$temp=array();
			$nbArtisanat=count($liste_artisanat);
			for($i=0;$i<$nbArtisanat;$i++){
				$temp[($i*3)]=GetImage($toto[$i]);
				$temp[($i*3)+1]=$toto[$i];
				$temp[($i*3)+2]=span($this->GetNiveauComp($tata[$i],true),"comp");
				$bonus = $this->GetBonusComp($tata[$i]);
				if($bonus != 0){
					if($bonus > 0){
						$temp[($i*3)+2] .= span(" (+".$bonus.")","bonus");
					} else {
						$temp[($i*3)+2] .= span(" (".$bonus.")","malus");
					}
				}
			}
			return $temp;
		}



		function DescriptionCompetences(){
			global $liste_competences;
			$toto = array_keys($liste_competences);
			$tata = array_values($liste_competences);
			$temp=array();
			$nbComp=count($liste_competences);
			for($i=0;$i<$nbComp;$i++){
				$temp[($i*3)]=GetImage($toto[$i]);
				$temp[($i*3)+1]=$toto[$i];
				$temp[($i*3)+2]=span($this->GetNiveauComp($tata[$i],true),"comp");
				$bonus = $this->GetBonusComp($tata[$i]);
				if($bonus != 0){
					if($bonus > 0){
						$temp[($i*3)+2] .= span(" (+".$bonus.")","bonus");
					} else {
						$temp[($i*3)+2] .= span(" (".$bonus.")","malus");
					}
				}
			}
			return $temp;
		}

		function DescriptionMagie(){
			global $liste_magie;
			$temp=array();
			$toto = array_keys($liste_magie);
			$tata = array_values($liste_magie);
			$nbMagie=count($liste_magie);
			for($i=0;$i<$nbMagie;$i++){
				$temp[($i*3)]=GetImage($toto[$i]);
				$temp[($i*3)+1]=$toto[$i];
				$temp[($i*3)+2]=span($this->GetNiveauComp($tata[$i],true),"comp");
				$bonus = $this->GetBonusComp($tata[$i]);
				if($bonus != 0){
					if($bonus > 0){
						$temp[($i*3)+2] .= span(" (+".$bonus.")","bonus");
					} else {
						$temp[($i*3)+2] .= span(" (".$bonus.")","malus");
					}
				}
			}
			return $temp;
		}

		function DescriptionEtatsTemporaires(){
			global $phpExtJeu;
			$temp[0]="";
			$temp[1]="<div class ='centerSimple'>Etats temporaires recus</div>";
			$temp[2]="description";
			$temp[3]="Date de Fin";
			$j=1;
			$nbEtats=count($this->EtatsTemp);
			for($i=0;$i<$nbEtats;$i++){
				if ($this->EtatsTemp[$i]->TypeEstCritereinscription==0) {
					if($this->EtatsTemp[$i]->ID != 1){						
						$temp[(($j+1)*2)] = "<a href=\"javascript:a('../bdc/etat.$phpExtJeu?";
						if (defined("PAGE_ADMIN"))
							$temp[(($j+1)*2)] .= "for_mj=1&amp;";									
						$temp[(($j+1)*2)] .="num_etat=".$this->EtatsTemp[$i]->ID."')\">".span($this->EtatsTemp[$i]->nom,"etattemp")."</a>";
					} else {
						$temp[(($j+1)*2)] = span($this->EtatsTemp[$i]->nom,"etattemp");
					}
					$temp[(($j+1)*2)+1] = span(faitDate($this->EtatsTemp[$i]->Fin),"date");
					$j++;
				}
			}
			return $temp;
		}

		function DescriptionInventaire(){
			$temp[0]="";$temp[1]="";$temp[2]="";$temp[3]="";$temp[4]="";$temp[5]="";$temp[6]="";$temp[7]="";$temp[8]="";$temp[9]="";
			$temp[10]="<div class ='centerSimple'>Sac a dos (".$this->getPlaceSacUtilisee()."/".$this->getPlaceSacMax().")</div>";
			$temp[11]="";$temp[12]="";$temp[13]="&nbsp;";$temp[14]="nom (cliquez pour d&eacute;tails)";$temp[15]="";$temp[16]="";$temp[17]="";$temp[18]="";
			$temp[19]="description";$temp[20]="poids";
			if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
				$temp[21]="Prix";
			else 	$temp[21]="";

			$compteur=count($temp);
			$nb_objets = count($this->Objets);
			for($i=0;$i<$nb_objets ;$i++){
				$t = $this->Objets[$i]->description();
				$nb_t = count($t);
				for($j=0;$j<$nb_t;$j++){
					$temp[$compteur] = $t[$j];
					$compteur++;
				}				
			}
			return $temp;
		}
		
        function recevoirRecompensesQuete($Quete) {
                global $db;
                global $liste_type_recompense;
                global $liste_comp_full;
		$nbRecompenses=count($Quete->recompenses);
		$texteRecompense="";
		for ($i=0;$i<$nbRecompenses;$i++) {
			switch($Quete->recompenses[$i]->type_recompense) {
			/** Impossible a coder car les XP sont la somme des xp des competences... Il faudrait tout revoir ou coder une competence vide a ne pas afficher...
			*case 1: //gain d'XP
			*$this->recompenseID = $recompense;
			*$this->recompense=null;
			*break;			
			*/
			case 2: //gain de PO			        
				$this->ModPO($Quete->recompenses[$i]->recompenseID);
				$texteRecompense.=$liste_type_recompense[$Quete->recompenses[$i]->type_recompense]. " (".$Quete->recompenses[$i]->recompenseID."), ";
				break;
			case 3: //gain d'objet
				if ($this->AcquerirObjet($Quete->recompenses[$i]->recompense,0))
					$texteRecompense.=$liste_type_recompense[$Quete->recompenses[$i]->type_recompense]. " (".$Quete->recompenses[$i]->recompense->nom."), ";
				break;
			case 4: //gain de sort
				if($this->grimoirePeutContenir($Quete->recompenses[$i]->recompense->place)){
					$SQL = "INSERT INTO ".NOM_TABLE_PERSOMAGIE." (id_perso,id_magie,charges) VALUES ('".$this->ID."','".$Quete->recompenses[$i]->recompense->ID."','".$Quete->recompenses[$i]->recompense->charges."')";
					if ($db->sql_query($SQL))
						$texteRecompense.=$liste_type_recompense[$Quete->recompenses[$i]->type_recompense]. " (".$Quete->recompenses[$i]->recompense->nom."), ";
				} else {
					$valeurs2=array();
					$valeurs2[0] = $Quete->recompenses[$i]->nom;
					$template_main.=GetMessage("grimoireplein",$valeurs2);
				}
				break;
			case 5: //gain de competence
			        logdate("Quete->recompenses[$i]->recompenseID" .$Quete->recompenses[$i]->recompenseID);
			        if ($this->AugmenterNiveau($Quete->recompenses[$i]->recompenseID))
			        	$texteRecompense.=$liste_type_recompense[$Quete->recompenses[$i]->type_recompense]. " (". array_search($Quete->recompenses[$i]->recompenseID, $liste_comp_full)."), ";
			        break;
			case 6: //etat
				if ($this->AjouterEtatTemp($Quete->recompenses[$i]->recompense,-1))
					$texteRecompense.=$liste_type_recompense[$Quete->recompenses[$i]->type_recompense]. " (".$Quete->recompenses[$i]->recompense->nom."), ";
				break;
			}
			
		}	
		if ($texteRecompense!="")
			return substr($texteRecompense,0, strlen($texteRecompense)-1);
		else return 	$texteRecompense;
	}	


        function recevoirPunitionsQuete($Quete) {
                global $db;
		$nbPunitions=count($Quete->punitions);
		$textePunition="";
		logdate("recevoirPunitionsQuete :".$Quete->id_quete . "nbpunition".$Quete->punitions);
		for ($i=0;$i<$nbPunitions;$i++) {
       	                logdate($i." type_recompense :".$Quete->punitions[$i]->type_recompense. "---".$Quete->punitions[$i]->recompenseID);
			switch($Quete->punitions[$i]->type_recompense) {
        			/** Impossible a coder car les XP sont la somme des xp des competences... Il faudrait tout revoir ou coder une competence vide a ne pas afficher...
        			*case 1: //perte d'XP
        			*$this->recompenseID = $recompense;
        			*$this->recompense=null;
        			*break;			
        			*/
        			case 2: //perte de PO
        			        //rappel le champ est <0 en base, mais pas dans la classe
        				if ($this->ModPO(-$Quete->punitions[$i]->recompenseID))
        					$textePunition.=$liste_type_recompense[$Quete->punitions[$i]->type_recompense]. " (".$Quete->recopunitionsmpenses[$i]->recompenseID."), ";
        				break;
        			case 3: //perte d'objet.. Attention, le perso peut avoir plusieurs objets identiques, on en supprime un au hasard
        				$Objet = null;
        				$nb_objets =  count($this->Objets);
        				$j=0;
        				while (($j<$nb_objets) && ($Objet == null)) {
        					if($this->Objets[$j]->ID == $Quete->punitions[$i]->recompenseID){$Objet = $this->Objets[$j];}
        					else $j++;
        				}
        				if ($Objet!=null) {
        					  $SQL = "DELETE FROM ".NOM_TABLE_PERSOOBJET." WHERE id_objet = ".$Quete->punitions[$i]->recompenseID." and id_perso = ".$this->ID;
        		   			if ($db->sql_query($SQL))
        		   				$textePunition.=$liste_type_recompense[$Quete->punitions[$i]->type_recompense]. " (".$Quete->punitions[$i]->recompense->nom."), ";
        			        }	
        				break;
             			case 4: //perte de sort Attention, le perso peut avoir plusieurs sorts identiques, on en supprime un au hasard
        					$nbSorts = count($this->Sorts);
        					$Sort=null;
        					$j=0;
        					while ($Sort==null && $j<$nbSorts) {
        						if($this->Sorts[$j]->ID == $Quete->punitions[$i]->recompenseID){
        							$Sort = $this->Sorts[$j];
        					        }
        						else {
        							$j++;
        						}	
        					}
        					if ($Sort !=null) {
        						$SQL = "DELETE FROM ".NOM_TABLE_PERSOMAGIE." where id_magie = ".$Quete->punitions[$i]->recompenseID." and id_perso = ".$this->ID;
        		   			if ($db->sql_query($SQL))
        		   				$textePunition.=$liste_type_recompense[$Quete->punitions[$i]->type_recompense]. " (".$Quete->punitions[$i]->recompense->nom."), ";
        				        }
        				break;
        			case 5: //perte de competence			        
        			        if ($this->BaisserNiveau($Quete->punitions[$i]->recompenseID))
        			        	$textePunition.=$liste_type_recompense[$Quete->punitions[$i]->type_recompense]. " (". array_search($Quete->punitions[$i]->recompenseID, $liste_comp_full)."), ";
        			        break;
        			case 6: //perte d'etat temp
        				if ($this->RetirerEtatTemp($Quete->punitions[$i]->recompenseID,-1))
        					$textePunition.=$liste_type_recompense[$Quete->punitions[$i]->type_recompense]. " (".$Quete->punitions[$i]->recompense->nom."), ";
        				break;
			}
			
		}	
		if ($textePunition!="")
			return substr($textePunition,0, strlen($textePunition)-1);
		else return 	$textePunition;		
	}	


		function AutoValidation($Quete) {
			global $db;
			switch ($Quete->type_quete) {
				case 1:  //trouver lieu
					if ($this->Lieu->ID==$Quete->detail_type_queteID)
					        return true;
					else return false;        
					break;	
				case 2:  //Trouver pj
					if ($this->Lieu->ID==$Quete->detail_type_quete->Lieu->ID)
					        return true;
					else return false;        
					break;	
				case 4: //Tuer pj
					if ($Quete->detail_type_quete->RIP())
					        return true;
					else return false;        
					break;	
				
				case 5: //voler PJ
					/** /TODO mais comment valider que le PJ a bien vole l'autre ????
					*/
					break;
				case 3:  //Trouver objet
					if ($this->PossedeObjet($Quete->detail_type_queteID))
					        return true;
					else return false;      					
					break;
				case 7: //Tuer monstres du lieu"
				  if ($this->Lieu->ID==$Quete->detail_type_queteID) {
					  $SQL=" select count(id_perso) as c from ". NOM_TABLE_PERSO ." where pnj=3";
					  if (($result=$db->sql_query($SQL))!==false) {
							 $row = $db->sql_fetchrow($result);
							 if ($row["c"]>0)
						        return false;
	  					else return true;      					
					  }	
					}
					else return false;  
				  break;	
				default:	
					$erreur=GetMessage("erreurPasTypeQuete");
	
			}			        
		}    


		function DescriptionQuetes(){
			$temp[0]="";$temp[1]="";$temp[2]="";$temp[3]="";$temp[4]="";$temp[5]="";$temp[6]="";$temp[7]="";$temp[8]="";
			$temp[9]="<div class ='centerSimple'>Quetes</div>";
			$temp[10]="nom (cliquez pour d&eacute;tails)";$temp[11]="Proposée par";$temp[12]=" Objectif";
			$temp[13]="Détails";$temp[14]="Débutée le";
			$temp[15]="A terminer avant";$temp[16]="";
			$temp[17]="Quete Publique";$temp[18]="Abandon possible";$temp[19]="etat";
			$compteur=count($temp);
			$nbQuetes=count($this->Quetes);
			for($i=0;$i<$nbQuetes;$i++){
				$t = $this->Quetes[$i]->description();
				for($j=0;$j<count($t);$j++){
					$temp[$compteur] = $t[$j];
					$compteur++;
				}				
			}
			return $temp;
		}		
		
		function DescriptionPreferences(){
			global $liste_reactions;				
			global $liste_ActionSurprise;
			$temp[0]="";
			$temp[1]="<div class ='centerSimple'>Pr&eacute;f&eacute;rences</div>";
			//$temp[2]="Arme pr&eacute;f&eacute;r&eacute;e (utilis&eacute;e pour la riposte)";			
			$temp[2]="Sort pr&eacute;f&eacute;r&eacute;e (utilis&eacute; pour la riposte)";
			$temp[4]="R&eacute;action en cas d'aggression (ou vol)";			
			
			if ($this->pnj) {
				$temp[6]="Action Surprise";				
				$temp[7]=$liste_ActionSurprise[$this->actionsurprise];								
				$temp[8]="Phrase pr&eacute;f&eacute;r&eacute;e";				
				$temp[9]=$this->phrasepreferee;				
			}	
			
			$toto= array_values($liste_reactions);			
			$tata = array_keys($liste_reactions);
			
			//$temp[3]=$this->getnomArmePreferee();
			
			$temp[3]="";
			if ($this->SortPrefere) {
				global $db;
				$SQL = "SELECT * FROM ".NOM_TABLE_PERSOMAGIE." WHERE id_clef =".$this->SortPrefere;
				$requete=$db->sql_query($SQL);
				
				$i=0;				
				while ($i<count($this->Sorts) && $temp[3]=="") {
					$row = $db->sql_fetchrow($requete);
					if ($this->Sorts[$i]->ID == $row["id_magie"])
						$temp[3]=$this->Sorts[$i]->nom;
					$i++;	
				}
			}
			
			$temp[5]= $toto[$this->Reaction];
				
			return $temp;
		}

		function DescriptionGrimoire(){
			
			$temp[0]="";$temp[1]="";$temp[2]="";$temp[3]="";$temp[4]="";$temp[5]="";$temp[6]="";$temp[7]="";$temp[8]="";
                        $temp[9]="";$temp[10]="";
                        $temp[11]="";$temp[12]="";
			$temp[13]="<div class ='centerSimple'>Grimoire (".$this->getPlaceGrimoireUtilisee()."/".$this->getPlaceGrimoireMax().")</div>";

			$temp[14]="";
			$temp[15]="";
			$temp[16]="&nbsp;";
			$temp[17]="nom (cliquez pour d&eacute;tails)";
			$temp[18]="";
			$temp[19]="";
			$temp[20]="";
			$temp[21]="description";
			$temp[22]="place";
			if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
				$temp[23]="Prix";
			else 	$temp[23]="";
                        $temp[24]="Cout en PA";
                        $temp[25]="Cout en PI";
                        $temp[26]="Cout en PO";
                        $temp[27]="Cout en PV";
			$compteur=count($temp);
			$nbSorts = count($this->Sorts);
			for($i=0;$i<$nbSorts;$i++){
				$t = $this->Sorts[$i]->description();
				for($j=0;$j<count($t);$j++){
					$temp[$compteur] = $t[$j];
					$compteur++;
				}				
			}
			return $temp;
		}


		// $cache = 1 => on se cache, =0 on sort de sa cachette
		function etrecache($cache) {
			global $db;
			if (($this->dissimule && $cache) ||((!$this->dissimule && (!$cache))))
				return false;
				
			$this->dissimule=$cache;
			
			$SQL="update TLT_Perso set dissimule = ". $cache ." where id_perso = ".$this->ID;
			$requete=$db->sql_query($SQL);
			if (!$cache) {
				global $liste_type_objetSecret;
				$toto = array_keys($liste_type_objetSecret);
				$SQL = "select id from ".NOM_TABLE_ENTITECACHEE ." where    id_entite = ".$this->ID .
				" and id_lieu = ".$this->Lieu->ID." and type = ".$toto[2];
				$requete=$db->sql_query($SQL);
				if ($db->sql_numrows($requete)>0) {
					$row = $db->sql_fetchrow($requete);
					$SQL="delete from ".NOM_TABLE_ENTITECACHEECONNUEDE ." where id_entitecachee =" . $row["id"];
					if ($requete2 = $db->sql_query($SQL)) {
						$SQL="delete from ".NOM_TABLE_ENTITECACHEE ." where id=" . $row["id"];
						$requete2 = $db->sql_query($SQL);
					}
				}
			}	
			return true;
			
		}	
		
		function decouvreEntiteCachee($id_entiteCachee, $type) {
			global $db;
			$SQL = "select * from ".NOM_TABLE_ENTITECACHEECONNUEDE." where id_perso = ".$this->ID ." and id_entitecachee = ".$id_entiteCachee;
			$requete=$db->sql_query($SQL);
			if ($db->sql_numrows($requete)==0) {
				//on ne connait pas encore => insert
				$SQL="insert into ". NOM_TABLE_ENTITECACHEECONNUEDE . " (id_entitecachee,id_perso) values ( ". $id_entiteCachee.",".$this->ID.")";
				$requete=$db->sql_query($SQL);		
				switch($type) {
		/*			0=>"passage",
					1=>"Objet",
					2=>"Perso",*/
					case 0:	 		
						$this->ConnaitLieuxSecrets=true;
						break;
					case 1:	 		
						$this->ConnaitObjetsSecrets=true;
						break;
					case 2:	 		
						$this->ConnaitPersosSecrets=true;
						break;	
				}							
			}	
		}	
	
	
		/** listeQuetes($type=array(), $sous_type = null, $etat=null)
		fonction retournant la requete a lancer pour trouver les Quetes du perso
			filtres:
				$type 
				$sous_type
				$etat
				$whereSuppl

		*/
		function listeQuetes($type=array(), $sous_type = null, $etat=array(), $whereSuppl="") {
			//global $db;
			$SQL= "Select T1.id_persoquete as idselect, ";
			$SQL.=" concat(concat(T1.id_quete,'-'),T2.nom_quete) ";
			$SQL.= " as labselect ".
			" from ".NOM_TABLE_PERSO_QUETE." T1, ".NOM_TABLE_QUETE." T2 ".
			" WHERE T1.id_quete = T2.id_quete AND T1.id_perso = ".$this->ID;
			if ($type <>array())	{
				 $SQL.=" AND ( T2.type_quete = '".$type[0]."'";
				$i=1;
				while ($i<count($type)) {
				 	$SQL.= " OR T2.type_quete = '".$type[$i]."'";
					$i++;
				}	
				 
				 $SQL.=" )";
			}	 
			if ($sous_type <>null)	
				 $SQL.=" AND T2.detail_type_quete = '".$sous_type."'" ;
			if ($etat <>array())	{
				 $SQL.=" AND ( etat = '".$etat[0]."'";
				$i=1;
				while ($i<count($etat)) {
				 	$SQL.= " OR etat = '".$etat[$i]."'";
					$i++;
				}	
				 
				 $SQL.=" )";
			}			
			if ($whereSuppl!="") {
				$SQL.=" AND ".$whereSuppl;
			}		 
			/*if ($equipe ==1)
				 $SQL.=" AND T1.equipe = '1'" ;
			elseif ($equipe ==-1)
				 $SQL.=" AND T1.equipe = '0'" ;				 
			if ($permanent ==1)
				 $SQL.=" AND T2.permanent = '1'" ;
			elseif ($permanent ==-1)
				 $SQL.=" AND T2.permanent = '0'" ;				 
			if ($localAuLieu ==1)
				 $SQL.=" AND T2.temporaire = '1'" ;
			elseif ($localAuLieu ==-1)
				 $SQL.=" AND T1.temporaire = '0'" ;				 
			if ($poing ==-1)
				 $SQL.=" AND T2.id_objet <>1" ;
			*/	 

			return $SQL;
		}			


		/** listeObjets($type=array(), $sous_type = null, $equipe=0,$permanent=0, $localAuLieu=0,$poing =0, $labeletendu = false)
		fonction retournant la requete a lancer pour trouver les Objets du perso
			filtres:
				$type 
				$equipe 0 ramene tous les objets du PJ equipe ou non
					1 ramene tous les objets du PJ equipe
					-1 ramene tous les objets du PJ non equipe
				$permanent 0 ramene tous les objets du PJ permanent ou non
					1 ramene tous les objets du PJ permanent
					-1 ramene tous les objets du PJ non permanent

				$localAuLieu 0 ramene tous les objets du PJ localAuLieu ou non (cles pour passage)
					1 ramene tous les objets du PJ localAuLieu
					-1 ramene tous les objets du PJ non localAuLieu
					
				$poing 0 ramene tous les objets du PJ y compris le poing
					-1 ramene tous les objets du PJ sauf le poing

		*/
		function listeObjets($type=array(), $sous_type = null, $equipe=0,$permanent=0, $localAuLieu=0,$poing =0, $labeletendu = false) {
			//global $db;
			$SQL= "Select T1.id_clef as idselect, ";
			if ($labeletendu) {
				if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
				$SQL.="concat(concat(concat(concat(concat(T1.id_clef,'-'),T2.nom),'  - ('),T2.prix_base),' POs de base)') ";
				else $SQL.="concat(concat(T1.id_clef,'-'),T2.nom) ";	
			}	
			else $SQL.=" concat(concat(T1.id_clef,'-'),T2.nom) ";
			$SQL.= " as labselect ".
			" from ".NOM_TABLE_PERSOOBJET." T1, ".NOM_TABLE_OBJET." T2 ".
			" WHERE T1.id_objet = T2.id_objet AND T1.id_perso = ".$this->ID;
			if ($type <>array())	{
				 $SQL.=" AND ( T2.type = '".$type[0]."'";
				$i=1;
				while ($i<count($type)) {
				 	$SQL.= " OR T2.type = '".$type[$i]."'";
					$i++;
				}	
				 
				 $SQL.=" )";
			}	 
			if ($sous_type <>null)	
				 $SQL.=" AND T2.sous_type = '".$sous_type."'" ;
			if ($equipe ==1)
				 $SQL.=" AND T1.equipe = '1'" ;
			elseif ($equipe ==-1)
				 $SQL.=" AND T1.equipe = '0'" ;				 
			if ($permanent ==1)
				 $SQL.=" AND T2.permanent = '1'" ;
			elseif ($permanent ==-1)
				 $SQL.=" AND T2.permanent = '0'" ;				 
			if ($localAuLieu ==1)
				 $SQL.=" AND T2.temporaire = '1'" ;
			elseif ($localAuLieu ==-1)
				 $SQL.=" AND T1.temporaire = '0'" ;				 
			if ($poing ==-1)
				 $SQL.=" AND T2.id_objet <>1" ;

			return $SQL;
		}	


		/**
		listeSorts($typeSort=array(), $sous_typeSort =array(), $typeMagie=1)
		fonction retournant la requete a lancer pour trouver les Sorts du perso
			filtres:
				$typeSort 
				$sous_typeSort 				
				$typeMagie (1 pour Sort sur 1PJ dans le lieu du lanceur
					    2 pour Sort sur 1 lieu (zone) proche du lanceur	
					    3 pour Sort sur 1PJ eloigne du lanceur
		*/
		function listeSorts($typeSort=array(), $sous_typeSort =array(), $typeMagie=1) {
			global $liste_type_cible;
			global $liste_pis_actions;
			global $liste_pas_actions;
			global $db;
			$SQL= "Select T1.id_clef as idselect, ";
			$SQL.="concat(concat(concat(concat(concat(concat(concat(concat(concat(";
			$SQL.="concat(concat(concat(concat(T1.id_clef,'-'),T2.nom),'-'),T2.sous_type)";
			$SQL.=",'(Cout en PA '), case when T2.coutpa is null then '".$liste_pas_actions["Magie"]."' else T2.coutpa end), 
			', Cout en PI '), case when T2.coutpi is null then '".$liste_pis_actions["Magie"]."' else T2.coutpi end),
			', Cout en PO '), T2.coutpo),', Cout en PV '), T2.coutpv), ')')";
			$SQL.=" as labselect ".
			//T1.id_clef, '-' ) , T2.nom ) , '-' ) , T2.sous_type ) 
			//, '(Cout en PA' ) , T2.coutpa ) , ', Cout en PI' ) , T2.coutpi ) , ')' ) AS labselect
			" from ".NOM_TABLE_PERSOMAGIE." T1, ".NOM_TABLE_MAGIE." T2 ".
			" WHERE T1.id_magie = T2.id_magie AND T1.id_perso = ".$this->ID;
			if ($typeSort <>array())	{
				 $SQL.=" AND ( T2.type = '".$typeSort[0]."'";
				$i=1;
				while ($i<count($typeSort)) {
				 	$SQL.= " OR T2.type = '".$typeSort[$i]."'";
					$i++;
				}	
				 
				 $SQL.=" )";
			}	 

			if ($sous_typeSort <>array())	{
				
				 $SQL.=" AND ( T2.sous_type = '".$sous_typeSort[0]."'";
				$i=1;
				while ($i<count($sous_typeSort)) {
				 	$SQL.= " OR T2.sous_type = '".$sous_typeSort[$i]."'";
					$i++;
				}	
				 
				 $SQL.=" )";
			}

			switch($typeMagie) {
				case 1:	 		
					$SQL.=" and sortdistant =0 and (typecible =1 or typecible =3)" ;
					break;
				case 2:	 		
					$SQL.=" and typecible=2";
					break;	
				case 3:	 		
					$SQL.=" and sortdistant =1 and (typecible =1)";
					break;	
			}							

			return $SQL;
		}	

		/** \brief fonction retournant la requete a lancer pour trouver les PJ/PNJ du lieu ou se trouve le PERSO.
			filtres:
				$vivant 0 ramene tous les PJs morts ou non
					1 ramene tous les PJs vivants	
					-1 ramene tous les PJs morts	
				$archive true	ramene tous les PJs meme archives
					 false ramene les PJs non archives
				$differentsduLanceur : 1: supprime le Lanceur de la liste (sort d'attaque ...)	
						       0: inclus le Lanceur dans la liste (sort de soin...)	
				$connu : true ramene uniquement les PJs du lieu que le PERSO connait (visibles ou caches mais dont PERSO a connaissance)
				       : false ramene tous les PJs du lieu qu'il soit caches ou non				       
		*/
		function listePJsDuLieuDuPerso($vivant, $archive, $connu, $differentsduLanceur) {
			global $db;
			if ($connu) { 
				if ( $this->ConnaitPersosSecrets)
					$SQL = "Select distinct P.id_perso as idselect, P.nom as labselect FROM ".NOM_TABLE_REGISTRE." P LEFT JOIN (".NOM_TABLE_ENTITECACHEE." E,".NOM_TABLE_ENTITECACHEECONNUEDE."  ECCD) 
				        ON  P.id_perso= E.id_entite and E.id= ECCD.id_entitecachee and E.type=2	and (ECCD.id_perso is null or ECCD.id_perso = ".$this->ID.")
					WHERE  P.id_lieu = ".$this->Lieu->ID." and ((P.dissimule = 1 and ECCD.ID is not null) or P.dissimule = 0) ";
				else 
					$SQL = "Select distinct P.id_perso as idselect, P.nom as labselect FROM ".NOM_TABLE_REGISTRE." P 
				        WHERE  P.dissimule = 0 and P.id_lieu = ".$this->Lieu->ID;
			}	        
			else 
				$SQL = "Select distinct P.id_perso as idselect, P.nom as labselect FROM ".NOM_TABLE_REGISTRE."  P 
			        WHERE  P.id_lieu = ".$this->Lieu->ID;
			if ($differentsduLanceur==1)
			          $SQL .= " and P.id_perso <> ".$this->ID;
			if ($vivant==1)
				$SQL .= " AND P.PV >0 ";
			elseif ($vivant==-1)
				$SQL .= " AND P.PV <=0 ";
			if (!$archive)
				$SQL .= " AND P.archive=0 ";
			return $SQL;
		}	


		/** \brief retourne la requete SQL pour determiner les lieux proches du joueur avec les parametres suivants
		    \param $connu inclut les lieux caches que le joueur connait (s'il en connait) si $connu = 1 ou tous les lieux proches (y compris ceux qui sont caches et qu'il ne connait pas ) si $connu = 0
		    \param $distance = nb chemins entre les 2 lieux (pour l'instant, n'est pas code)
		*/
		function listeLieuxProches($connu=1, $distance=1) {
			global $db;
			global $sep;
			global $liste_types_chemins;
			if ($connu) { 
				if ( $this->ConnaitLieuxSecrets)
					$SQL = "Select distinct concat(concat(concat(concat(T3.id_lieu,'$sep'),T3.trigramme),'-'),T3.nom) as idselect, concat(concat(T3.trigramme),'-'),T3.nom) as labselect FROM ".NOM_TABLE_CHEMINS." T1,".NOM_TABLE_ENTITECACHEECONNUEDE." T4,".NOM_TABLE_ENTITECACHEE." T5, ".NOM_TABLE_LIEU." T2, ".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu AND T1.id_lieu_1 = ".$PERSO->Lieu->ID
					." AND (T1.type <> ".$liste_types_chemins["Lieu Secret"]. " OR (T1.type = ".$liste_types_chemins["Lieu Secret"]
					. " AND T4.id_perso =".$this->ID . " AND T4.id_entitecachee = T5.ID AND T1.id_clef=T5.ID_entite  ))";				
				else 
					$SQL = "Select distinct concat(concat(concat(concat(T3.id_lieu,'$sep'),T3.trigramme),'-'),T3.nom) as idselect, concat(concat(T3.trigramme,'-'),T3.nom) as labselect FROM ".NOM_TABLE_CHEMINS." T1,".NOM_TABLE_LIEU." T2, ".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu AND T1.id_lieu_1 = ".$this->Lieu->ID
					." AND T1.type <> ".$liste_types_chemins["Lieu Secret"];
			}	        
			else 
				$SQL = "Select distinct concat(concat(concat(concat(T2.id_lieu,'$sep'),T3.trigramme),'-'),T3.nom) as idselect, concat(concat(T3.trigramme,'-'),T3.nom) as labselect FROM ".NOM_TABLE_CHEMINS." T1, ".NOM_TABLE_LIEU." T2, ".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu AND T1.id_lieu_1 = ".$this->Lieu->ID;
			return $SQL;
		}	


	function listePJsDesLieuvoisins($vivant, $archive, $connu, $differentsduLanceur,$tab) {
	    global $db;	
	    $pj=array();
	    $tab[$this->Lieu->ID] = "on";
	    foreach ($tab as $idlieu => $value) {
		$SQL= $this->listePJsDuLieuDuPerso($vivant, $archive, $connu, $differentsduLanceur);	            
	        $db->sql_query($SQL);
	        
		$requete=$db->sql_query($SQL);
		while($row = $db->sql_fetchrow($requete)){
			$pj[$row["idselect"]] = 1;

		}
	    }
	    return $pj;
	
	}

		function QuitterGroupe($groupe, $NbPJduGroupe) {
			global $db;
			$SQL="update ". NOM_TABLE_REGISTRE . " set id_groupe =null where id_perso =". $this->ID ;
   			if ($db->sql_query($SQL)!==false) {
				if ($NbPJduGroupe==1) {
					$SQL="delete from ". NOM_TABLE_GROUPE . " where id_groupe = ".$this->Groupe;
		   			if ($db->sql_query($SQL)===false) 
						return false;	
				}
				$this->Groupe="";	
				return true;					
			}
			else return false;
		}	

		function CreerGroupe($nom_groupe) {
			global $db;
			$SQL = "INSERT INTO ".NOM_TABLE_GROUPE." (nom) VALUES ('".ConvertAsHTML($nom_groupe)."')";
			if ($db->sql_query($SQL )) {
				$ID_groupe=$db->sql_nextid();
				return $this->EntrerGroupe($ID_groupe);			
			}
			else return false;
		}	

		function EntrerGroupe($ID_groupe) {
			global $db;
			$SQL="update ". NOM_TABLE_REGISTRE . " set id_groupe =". $ID_groupe." where id_perso =". $this->ID ;
			if ($db->sql_query($SQL )) {
				$this->Groupe=$ID_groupe;
				return true;
			}
			else return false;		
		}	


		function peutUtiliserObjet($Objet) {
			if ($Objet->EtatTempSpecifique == null)
				return true;
			else {	
				$trouve=false;
				$i=0;
				$nbEtats=count($this->EtatsTemp);
				while($i<$nbEtats && $trouve==false){
					if ($Objet->EtatTempSpecifique->ID == $this->EtatsTemp[$i]->ID)
						$trouve=true;
					else $i++;
				}
				return $trouve;
			}	
		}


		function peutAccederLieu($Lieu) {
			if ($Lieu->EtatTempSpecifique == null)
				return true;
			else {	
				$trouve=false;
				$i=0;
				$nbEtats=count($this->EtatsTemp);
				while($i<$nbEtats && $trouve==false){
					if ($Lieu->EtatTempSpecifique->ID == $this->EtatsTemp[$i]->ID)
						$trouve=true;
					else $i++;
				}
				return $trouve;
			}	
		}

		function SeDesengager($ADVERSAIRE) {
			global $db;
			$SQL="DELETE FROM ".NOM_TABLE_ENGAGEMENT." WHERE (id_perso = ".$this->ID." AND id_adversaire = ".$ADVERSAIRE->ID.") OR (id_perso = ".$ADVERSAIRE->ID." AND id_adversaire = ".$this->ID.")";
			$requete=$db->sql_query($SQL);
			$SQL = "SELECT count(id_adversaire) as c FROM ".NOM_TABLE_ENGAGEMENT."  WHERE id_perso = '".$this->ID."' AND id_adversaire <> '".$ADVERSAIRE->ID."' ";
			$req = $db->sql_query($SQL);
			$row = $db->sql_fetchrow($req);
			$autreng = $row["c"];

			$SQL = "SELECT count(id_adversaire) as c FROM ".NOM_TABLE_ENGAGEMENT."  WHERE id_perso = '".$ADVERSAIRE->ID."' AND id_adversaire <> '".$this->ID."' ";
			$raq = $db->sql_query($SQL);
			$row = $db->sql_fetchrow($raq);
			$autrenga = $row["c"];

			if ($autreng ==0){
				//$SQL= "UPDATE ".NOM_TABLE_PERSO." SET engagement = '0' WHERE id_perso = ".$this->ID;
				//$requete=$db->sql_query($SQL);
				$this->Engagement=false;
			}/*else{
				$SQL= "UPDATE ".NOM_TABLE_PERSO." SET propdes = '0'  WHERE id_perso = ".$this->ID;
				$requete=$db->sql_query($SQL);
			}*/

			if ($autrenga ==0){
				//$SQL= "UPDATE ".NOM_TABLE_PERSO." SET engagement = '0' WHERE id_perso = ".$ADVERSAIRE->ID;
				$ADVERSAIRE->Engagement=false;
				//$requete=$db->sql_query($SQL);
			}/*else{
				$SQL= "UPDATE ".NOM_TABLE_PERSO." SET propdes = '0'  WHERE id_perso = ".$ADVERSAIRE->ID;
				$requete=$db->sql_query($SQL);
			}*/
			return $requete;				
		}


		/*! 
		    \brief resurrection d'un PJ lors de sa connexion.
		    Comme les monstres et les bestiaires ne se connectent pas, pas besoin de les gerer.
		*/		
		function resurrection($Lieu=0) {
                        global $db;
                        if(defined("RESURRECTION") && RESURRECTION==1) {
                                if(defined("NB_MAX_RESURRECTION") && ($this->nb_deces<NB_MAX_RESURRECTION || NB_MAX_RESURRECTION==-1)) {
							if(defined("PV_RESURRECTION")){
								$pv_regenere = PV_RESURRECTION;
							}
							else{
								$pv_regenere = 10;
							}
                			$this->PV = Min($pv_regenere,$this->GetPVMax()+$this->getRPV());	
                			$this->PA =0;
                			$this->PI =0;
                			$this->nb_deces = $this->nb_deces +1;
                			$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET ";
                			
                			if (defined("LIEU_RESURRECTION") && LIEU_RESURRECTION>0)
                			        $SQL.=" id_lieu = ". LIEU_RESURRECTION.",";
                			$SQL.=" pa=0,pi=0, pv = ".$this->PV.", archive=0, nb_deces=".$this->nb_deces."  WHERE id_perso = ".$this->ID;
                   			if ($db->sql_query($SQL)!==false) {
                				$this->OutPut(GetMessage("resurrectionOK"));	
                				return true;
                			}	
                			else return false;	
                		}
                                else $this->OutPut(GetMessage("resurrectionKO"));		        
                        }
                        else $this->OutPut(GetMessage("resurrectionKO"));		        
		}        
		
		
	function supprimer() {
                global $db;
                global $template_main;
                global $forum;
                
	        $SQL = "DELETE FROM ".NOM_TABLE_PERSOETATTEMP." WHERE id_perso = '".$this->ID."'"; 
	         if($db->sql_query($SQL, "",BEGIN_TRANSACTION_JEU)) {
	         	$SQL = "DELETE FROM ".NOM_TABLE_PERSOMAGIE." WHERE id_perso = '".$this->ID."'"; 
	         	if ($db->sql_query($SQL)) {
	         		$SQL = "DELETE FROM ".NOM_TABLE_PERSOOBJET." WHERE id_perso = '".$this->ID."'"; 
	         		if($db->sql_query($SQL)) {
	         			$SQL = "DELETE FROM ".NOM_TABLE_PERSOSPEC." WHERE id_perso = '".$this->ID."'"; 
	         			if($db->sql_query($SQL)) {
	         				$SQL = "DELETE FROM ".NOM_TABLE_ENTITECACHEECONNUEDE." WHERE id_perso = '".$this->ID."'"; 
					         if($requete2=$db->sql_query($SQL)) {				         
							global $liste_type_objetSecret;
							$toto = array_keys($liste_type_objetSecret);
							$SQL = "select id from ".NOM_TABLE_ENTITECACHEE ." where    id_entite = ".$this->ID ." and type = ".$toto[2];
							$requete=$db->sql_query($SQL);	
							if ($db->sql_numrows($requete)>0) {
								$row = $db->sql_fetchrow($requete);
								$SQL="delete from ".NOM_TABLE_ENTITECACHEECONNUEDE ." where id_entitecachee=" . $row["id"];
								$requete2=$db->sql_query($SQL);	
								if ($requete2) {
									$SQL="delete from ".NOM_TABLE_ENTITECACHEE ." where id=" . $row["id"];
									$requete2=$db->sql_query($SQL);	
								}	
							}
						}	
						if ($requete2) {
						        $SQL="DELETE FROM ".NOM_TABLE_ENGAGEMENT." WHERE id_perso = ".$this->ID." OR id_adversaire = ".$this->ID;
	                                                $requete2=$db->sql_query($SQL);
	                                        }
						if ($requete2)
							if(defined("IN_FORUM")&& IN_FORUM==1) {
								$requete2=$forum->DeleteMembre($this->nom);
							}
						if ($this->pnj==2)
						        if ($requete2)	{
        							$SQL = "DELETE FROM ".NOM_TABLE_APPARITION_MONSTRE." WHERE id_perso = '".$this->ID."'";
        						 	$requete2=$db->sql_query($SQL);
        						} 	
						        
						if ($requete2) {
							$SQL = "DELETE FROM ".NOM_TABLE_REGISTRE." WHERE id_perso = '".$this->ID."'";
						 	if ($db->sql_query($SQL, "",END_TRANSACTION_JEU)) {
								if(file_exists("../pjs/descriptions/desc_".$this->ID.".txt"))
									if ((unlink ("../pjs/descriptions/desc_".$this->ID.".txt"))===false)
										$template_main .= "Impossible d'effacer le fichier '../pjs/descriptions/desc_".$this->ID.".txt'";
								if(file_exists("../fas/pj_".$this->ID.".fa"))
									if ((unlink ("../fas/pj_".$this->ID.".fa"))===false)
										$template_main .= "Impossible d'effacer le fichier '../fas/pj_".$this->ID.".fa'";
								return true;
							}	
						}	
					}
				}
			}			
		}	                        
                return false;        
            }        		  
		
	}	

}

?>