<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: magie.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.12 $
$Date: 2010/01/24 16:36:41 $

*/

require_once("../include/extension.inc");
if(!defined("__MAGIE.PHP") ) {
	Define("__MAGIE.PHP",	0);

	class Magie{
		
		var $ID;
		var $type;
		var $Soustype;
		var $nom;
		var $Degats;
		var $anonyme;
		var $prix_base;
		var $place;
		var $description;
		var $image;
		var $permanent;
		var $charges;
		var $caracteristique;
		var $competence;
		var $provoqueetat;
		var $EtatTempSpecifique;
		var $SortDistant;
		var $TypeCible;
		var $composantes;
		var $ObjetsComposantesSort;
		var $majeur;
		var $coutPA;                    ///< le nombre de PA que perd le lanceur 
		var $coutPI;                    ///< le nombre de PI que perd le lanceur
		var $coutPO;                    ///< le nombre de PO que perd le lanceur
		var $coutPV;		        ///< le nombre de PV que perd le lanceur 
		
		function Magie($id_magie){
			global $db;
		        global $liste_pas_actions;
		        global $liste_pis_actions;
		        			
			$this->ID = $id_magie;
			$SQL = "SELECT * FROM ".NOM_TABLE_MAGIE." WHERE id_magie =".$this->ID;
			$requete=$db->sql_query($SQL);
			if ($db->sql_numrows($requete)>0) {
				$row = $db->sql_fetchrow($requete);
				$this->nom = $row["nom"];
				$this->type = $row["type"];
				$this->Soustype = $row["sous_type"];
				$this->Degats[0] = $row["degats_min"];
				$this->Degats[1] = $row["degats_max"];
				$this->anonyme = $row["anonyme"];
				$this->prix_base = $row["prix_base"];
				$this->place = $row["place"];
				$this->description = $row["description"];
				$this->image = $row["image"];
				$this->permanent = $row["permanent"];
				$this->charges = $row["charges"];	
				$this->caracteristique = $row["caracteristique"];
				$this->competence= $row["competence"];
				$this->provoqueetat= $row["provoqueetat"];
				$this->SortDistant = $row["sortdistant"];
				$this->TypeCible = $row["typecible"];
				$this->composantes=$row["composantes"];
				$this->majeur = (($this->TypeCible==2)||$this->SortDistant);
				if ($row["id_etattempspecifique"]<>"" && $row["id_etattempspecifique"]<>"0") 
					$this->EtatTempSpecifique= new EtatTemp($row["id_etattempspecifique"]);
				else 	$this->EtatTempSpecifique=null;
				$this->ObjetsComposantesSort=array();
				$this->coutPA = $row["coutpa"];
				$this->coutPI = $row["coutpi"];
				if ($this->coutPA=="") 
				        $this->coutPA=$liste_pas_actions["Magie"];
				if ($this->coutPI=="") 
				        $this->coutPI=$liste_pis_actions["Magie"];
				$this->coutPO = $row["coutpo"];
				$this->coutPV = $row["coutpv"];
			}
			else return false;
		}

		function achargesInfinie(){
			return ($this->charges == -1);
		}

		function estpermanent(){
			return $this->permanent == 1;
		}

		function GetdifficulteUtilisation(){
			if($this->Soustype == "Teleport"){ return 15;}
			if($this->Soustype == "Teleport Self"){ return 10;}
			$total = $this->Degats[0] + ($this->Degats[1]*2);
			if($this->Soustype == "Transfert"){ $total+=5;}
			if($this->provoqueetat != ""){$total += 5;}
			if(!$this->achargesInfinie()){$total -= 2;}
			if($this->anonyme == 1){$total += 5;}		
			if($this->permanent == 0){$total -= 5;}	
			return Max($total,0);
		}

		function setObjetsComposantesSort() {
			//pour ne le faire qu'une fois
			if ($this->ObjetsComposantesSort == array()) {
				if ($this->composantes!="") {
					$temp = explode(";",$this->composantes);
					// count -1 pour supprimer le dernier | 
					for($i=0;$i<count($temp)-1;$i++){
						$temp_composante = explode("|",$temp[$i]);
						$this->ObjetsComposantesSort[$i][0]= new Objet ($temp_composante[0]);
						$this->ObjetsComposantesSort[$i][1]= $temp_composante[1];
					}
				}	
				else return array();
			}
			return $this->ObjetsComposantesSort;
		}
		
	}

}
?>