<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: quete.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.2 $
$Date: 2010/01/24 16:36:43 $

*/

require_once("../include/extension.inc");
if(!defined("__QUETE.PHP") ) {
	Define("__QUETE.PHP",	0);

	class Quete{
		
		var $id_quete;
		var $type_quete;		///< type de la quete (cf. $liste_type_quete)
		var $detail_type_queteID;	///< l'ID du detail dy type
		var $detail_type_quete;		///< l'instance du detail (le PJ ou l'objet ou le lieu)
		var $nom_quete;
		var $duree_quete;               ///< durée de la quête en jours réels (ou -1 pour illimitée)
		var $public;                    ///< proposée dans un lieu, sinon il faut discuter avec le PJ/MJ qui propose...
		var $cyclique;			///< 
		var $proposepartype;		///< Qui propose la quete (un MJ ou PJ)
		var $proposepar;		///< l'Identifiant du MJ ou PJ qui propose la quete
		var $acteurProposant;		///< l'instance de celui qui a propose (le MJ ou le PJ)
		var $texteProposition;
		var $texteReussite;
		var $texteEchec;
		var $refusPossible;
		var $abandonPossible;
		var $recompenses;		///< la liste des recompenses en cas de reussite
		var $punitions;                 ///< la liste des punitions en cas d'echec
		var $validationquete;	        ///< determine s'il faut une validation du proposant quand un pj affirme avoir termine la quete (1 pour validation par proposant, 0 pour validation automatique). Ex: si la quete est trouver lieu L et que le PJ s'y trouve et que validationquete = 1 => la quete est reussie si le PJ clique sur terminer quete, sinon, il faut que le proposant de la quete valide en plus)
		var $lieuAffiche;               ///< indique dans quel lieu cette quete est affichée (quete publique uniquement)
		var $proposantAnonyme;          ///< indique si on sait qui propose la quete (quete publique uniquement)
		var $EtatTempSpecifique;	///< l'etat temp a avoir pour pouvoir acceder à la quete
		
		function Quete($id_quete){
			global $db;
			$this->id_quete = $id_quete;
			$SQLquete = "SELECT T1.* FROM ".NOM_TABLE_QUETE." T1 WHERE T1.id_quete =".$this->id_quete;
			$requete=$db->sql_query($SQLquete);
			if ($db->sql_numrows($requete)>0) {
				$rowquete = $db->sql_fetchrow($requete);
				$this->type_quete = $rowquete["type_quete"];
				$this->detail_type_queteID = $rowquete["detail_type_quete"];
				switch ($this->type_quete) {
					case 1:  //lieu
						$this->detail_type_quete= new Lieu($this->detail_type_queteID,false);
						break;	
					case 2:  //pj
					case 4:
					case 5:
						$this->detail_type_quete= new Joueur($this->detail_type_queteID,false,false,false,false,false,false);
						break;
					case 3:  //objet
						$this->detail_type_quete= new Objet($this->detail_type_queteID);
						break;
					default:	
						$erreur=GetMessage("erreurPasTypeQuete");
		
				}					
				$this->nom_quete = $rowquete["nom_quete"];
				$this->duree_quete = $rowquete["duree_quete"];
				$this->public = $rowquete["public"];
				$this->cyclique = $rowquete["cyclique"];
				$this->proposepartype = $rowquete["proposepartype"];
				$this->proposepar = $rowquete["proposepar"];
				$this->texteProposition = $rowquete["texteproposition"];
				$this->texteReussite= $rowquete["textereussite"];
				$this->texteEchec= $rowquete["texteechec"];
				$this->refusPossible=$rowquete["refuspossible"];
				$this->abandonPossible=$rowquete["abandonpossible"];
				$this->validationquete = $rowquete["validationquete"];
        			if ($rowquete["id_etattempspecifique"]<>"" && $rowquete["id_etattempspecifique"]<>"0") 
        				$this->EtatTempSpecifique= new EtatTemp($rowquete["id_etattempspecifique"]);
        			else 	$this->EtatTempSpecifique=null;				
				if ($this->proposepartype==1)
					$this->acteurProposant= new MJ($this->proposepar);
				else	
					$this->acteurProposant= new Joueur($this->proposepar,false, false,false,false,false,false, false);
                                $this->proposantAnonyme = $rowquete["proposant_anonyme"];
                                if ($this->public && $rowquete["id_lieu"]>=1)
                                        $this->lieuAffiche= new Lieu($rowquete["id_lieu"], false);
				$SQLquete = "SELECT * FROM ".NOM_TABLE_RECOMPENSE_QUETE." WHERE id_quete =".$this->id_quete;
				$requete=$db->sql_query($SQLquete);
				$nb_recompenses=0;
				$nb_punitions=0;
				while ($rowquete = $db->sql_fetchrow($requete)) {	
				        if ($rowquete['recompense']>0) {
					        $this->recompenses[$nb_recompenses]= new RecompenseQuete($rowquete['id_quete'], $rowquete['id_recompensequete'],$rowquete['type_recompense'], $rowquete['recompense']);
					        $nb_recompenses++;
					}        
				        else {
					        $this->punitions[$nb_punitions]= new RecompenseQuete($rowquete['id_quete'], $rowquete['id_recompensequete'],$rowquete['type_recompense'], -$rowquete['recompense']);
					        $nb_punitions++;
					}					
				}	

				
			}
			else return false;
		}
		
    
		/**
		*    retourne la description utilisée dans la fiche de PJ
		*    
		*/
		function description(){
			global $phpExtJeu;
			global $liste_type_quete;
			global $liste_etat_quete;
			global $PERSO;
			
			$temp=array();
			$temp[0] = "<a href=\"javascript:a('../bdc/quete.$phpExtJeu?";
			if (defined("PAGE_ADMIN"))
				$temp[0] .= "for_mj=1&amp;";				
			$temp[0] .= "num_quete=".$this->id_quete."')\">".$this->nom_quete."</a>";			
			//$temp[2]= $this->acteurProposant->nom;
                        
                        if ((!$this->proposantAnonyme) || (isset($PËRSO) && $PERSO->ID == $this->acteurProposant->ID)) {
                                if ($this->proposepartype==1)
                                        $temp[1] = span(" MJ ".$this->acteurProposant->nom,"mj"); 
                                else $temp[1] = span(" PJ ".$this->acteurProposant->nom,"pj");
                                if ($this->proposantAnonyme)
                                        $temp[1] .= "(anonymement)";
                        }
                        else $temp[1]= " Anonyme";
                       
			$temp[2]= $liste_type_quete[$this->type_quete] . " " .$this->detail_type_quete->nom;
			$temp[3]= $this->texteProposition;
			$temp[6]= "";
			if ($this->public)
				$temp[7]= "Oui dans ".$this->lieuAffiche->nom;
			else $temp[7]= "Non";	

			if ($this->abandonPossible)
				$temp[8]= "Oui";
			else $temp[8]= "Non";	
			return $temp;
		}	


	}

}
?>