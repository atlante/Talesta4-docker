<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: objet.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.16 $
$Date: 2010/01/24 16:36:42 $

*/

require_once("../include/extension.inc");
if(!defined("__OBJET.PHP") ) {
	Define("__OBJET.PHP",	0);

	class Objet{
		
		var $ID;
		var $type;
		var $Soustype;
		var $nom;
		var $Degats;
		var $anonyme;
		var $durabilite;		///< durabiliteMax de l'objet (constante). Chaque instance de cet objet a sa propre durabilite dans objetpj
		var $prix_base;			///< prix de base de l'objet neuf (utilisé pour calculer le prix de vente ou d'ahat suivant son usure et l'habilete de negociation du joueur)
		var $image;			///< image de surclasse de l'icone asocié à l'objet.
		var $munitions;			///< munitionsMax de l'objet (constante). Chaque instance de cet objet a ses propres munitions dans objetpj
		var $poids;			///< poids de l'objet (peut être 1 chiffre à virgule depuis la version 3.4.080)																																												 XiÕ \ñ PqQ Á          Á  ( œ¼ñ ×`Q Á         @ò ´bQ     ñº
		var $permanent;			///< indique si l'objet est une partie physique du PJ et ne peut etre enlevé à son propriétaire (Ex: poing)
		var $caracteristique;           
		var $competence;
		var $provoqueetat;		///< indique les états que provoque cet objet (sur son possesseur pour une armure, une nourriture, un livre), sur son adversaire pour une arme
		var $competencespe;
		var $PartieCorps;		///< indique la partie du corps qu'occupera l'objet une fois équipé (sert pour ne pas mettre 2 casques sur 1 tete). Voir aussi $QteTotale et $QteOccupee pour les éléments multiples (bras ...)
		var $QteTotale;			///< indique la quantite maximale d'objets de ce type que peut mettre un pj (Ex 2 mains => 2 épées courtes )
		var $QteOccupee;		///< indique la quantite qu'occupe cet objet (Ex 2 mains =>  2 épées courtes mais 1 seule épée à 2 mains )
		var $EtatTempSpecifique;
		
		function Objet($id_objet){
			global $liste_type_objs;
			global $db;
			$this->ID = $id_objet;
			$SQL = "SELECT * FROM ".NOM_TABLE_OBJET." WHERE id_objet =".$this->ID;
			$requete=$db->sql_query($SQL);
			if ($db->sql_numrows($requete)>0) {
				$row = $db->sql_fetchrow($requete);
				$this->nom = $row["nom"];
				$this->type = $row["type"];
				$this->Soustype = $row["sous_type"];
				$this->Degats[0] = $row["degats_min"];
				$this->Degats[1] = $row["degats_max"];
				$this->anonyme = $row["anonyme"];
				$this->durabilite = $row["durabilite"];
				$this->prix_base = $row["prix_base"];
				$this->poids = $row["poids"];
				$this->description = $row["description"];
				$this->image = $row["image"];
				$this->permanent = $row["permanent"];
				$this->munitions = $row["munitions"];
				$this->caracteristique = htmlentities($row["caracteristique"]);
				$this->competence= htmlentities($row["competence"]);
				$this->provoqueetat= htmlentities($row["provoqueetat"]);
				$this->competencespe= htmlentities($row["competencespe"]);
				$this->PartieCorps = $liste_type_objs[$this->type.";".$this->Soustype][1];
				$this->QteTotale = $liste_type_objs[$this->type.";".$this->Soustype][2];
				$this->QteOccupee = $liste_type_objs[$this->type.";".$this->Soustype][3];
				//$this->id_etattempspecifique = $row["id_etattempspecifique"];
				if ($row["id_etattempspecifique"]<>"" && $row["id_etattempspecifique"]<>"0") {
					$this->EtatTempSpecifique= new EtatTemp($row["id_etattempspecifique"]);
				}	
				else 	{ $this->EtatTempSpecifique=null;}
			}
			else return false;
		}

		function GetdifficulteUtilisation(){
			if($this->Soustype == "Livre"){ return $this->Degats[0] + ($this->Degats[1]*2);}
			 if($this->type == "Soins"){ return $this->Degats[0] + ($this->Degats[1]*2);} 
 		        else
 		        if($this->type == "SoinsPI"){ return $this->Degats[0] + ($this->Degats[1]*2);} 
			if(($this->type != 'ArmeMelee') && ($this->type != 'ArmeJet')){ return 0;}
			$total = $this->Degats[0] + ($this->Degats[1]*2);
			if($this->competencespe != ""){$total += 5;}
			if($this->anonyme == 1){$total += 5;}
			if($this->provoqueetat != ""){$total += 5;}
			if($this->permanent == 0){$total -= 5;}	
			return Max($total,0);
		}

		function adurabiliteInfinie(){
			return ($this->durabilite == -1);
		}

		
		function estpermanent(){
			return $this->permanent == 1;
		}

		function amunitionsInfinie(){
			return ($this->munitions == -1);
		}
		
		function ajouteMagasin($Lieu,$typeMagasin){
			global $db;

			$Objet = null;
			$nb_mags = count($Lieu->Magasins);
			for($i=0;$i<$nb_mags;$i++){
				if($Lieu->Magasins[$i]->type == $typeMagasin){
					$j=0;
					$nbItems = count($Lieu->Magasins[$i]->Items);
					while ( ($j< $nbItems) && ($Objet == null)){						
						if($Lieu->Magasins[$i]->Items[$j]->ID == $this->ID){$Objet = $Lieu->Magasins[$i]->Items[$j];}
						else $j++;
					}					
				}
			}
                        //objet trouve
			if ($Objet <> null) {
                                return $Objet->AugmenteQuantite(1);
                        }
			else {  //nouvel objet => on le cree dans le magasin, de telle facon qu'il n'y ait pas de gestion de stock et que la qte de cet objet n'augmente pas
			        //sinon il va y avoir des tricheurs qui vont vendre un objet, atteindre que la remise se fasse afin d'en racheter plusieurs.
			        $SQL = "insert into ".NOM_TABLE_MAGASIN." ( id_lieu , type , pointeur,stockmax,remisestock,quantite, derniereremise) values ($Lieu->ID, $typeMagasin, $this->ID,-1,-1,1,now())";
			if ($db->sql_query($SQL)) {
				return true;					
			}	
			else return false;		
			}
		}	

	}

}
?>