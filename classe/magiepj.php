<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: magiepj.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.14 $
$Date: 2010/01/24 16:36:42 $

*/

require_once("../include/extension.inc");
if(!defined("__MAGIEPJ.PHP") ) {
	Define("__MAGIEPJ.PHP",	0);

	class MagiePJ extends Magie{
		
		var $id_clef;
		var $charges_actu;
		var $Numero;

		function MagiePJ($id_magie,$id_clef,$numero, $charges_actu){
			$retour=$this->Magie($id_magie);
			if (! $retour) {
				$this->id_clef = $id_clef;
				$this->Numero = $numero;
				//$SQL = "SELECT * FROM ".NOM_TABLE_PERSOMAGIE." WHERE id_clef =".$this->id_clef;
				//$requete=$db->sql_query($SQL);
				$this->charges_actu = $charges_actu;
			}
			else {
				$this->Oublier2($id_magie);
				return null;
			}	
		}
		



		function Decharge($nb_munitions=1){
			global $db;
			if(!$this->achargesInfinie()){
				if($this->charges_actu > $nb_munitions){
					$this->charges_actu -= abs($nb_munitions);
					$SQL = "UPDATE ".NOM_TABLE_PERSOMAGIE." SET charges = ".$this->charges_actu." WHERE id_clef = ".$this->id_clef;
					if ($db->sql_query($SQL))
						return true;
					else return false;	
				} else {
					return false;
				}
			}
			return true;
		}

		function Oublier(){
			global $db;
			$SQL = "DELETE FROM ".NOM_TABLE_PERSOMAGIE." WHERE id_clef = ".$this->id_clef;
			if ($db->sql_query($SQL))
				return true;
			else return false;	
		}

		function Oublier2($id_magie){
			global $db;
			$SQL = "DELETE FROM ".NOM_TABLE_PERSOMAGIE." WHERE id_magie = ".$id_magie;
			if ($db->sql_query($SQL))
				return true;
			else return false;	
		}


		function TestOublieMort($nivInt=1){
			if(!$this->estpermanent()){
				if( lanceDe(10) > $nivInt){ return $this->Oublier();}				
			}
			else return true;
		}

		function description(){
			global $phpExtJeu;
			$temp = "";
			$temp[0]=$this->id_clef;
			if($this->estpermanent()){$temp[1]="#";}else{$temp[1]="-";}
			if( ($this->image != "") &&(file_exists("../templates/$template_name/images/".$this->image)) ){
					$temp[2]="<img src='../templates/$template_name/images/".$this->image."' border='0' alt='image du sort' />";
				}else{
					$temp[2]= GetImage($this->type);
				}

			$temp[3] = "<a href=\"javascript:a('../bdc/sort.".$phpExtJeu."?";
			if (defined("PAGE_ADMIN"))
				$temp[3] .= "for_mj=1&amp;";				
			$temp[3] = $temp[3] ."num_sort=".$this->ID."')\">";

			if($this->anonyme == 0){
				$temp[3] = $temp[3] . span($this->nom." (".$this->Soustype.")","sort")."</a>";
			} else {
				$temp[3] = $temp[3] . span($this->nom." (".$this->Soustype.") - anonyme","sort")."</a>";
			}

			$temp[4]=span("D&eacute;gats : ".$this->Degats[0]." &agrave; ".$this->Degats[1],"degats");			
			if($this->achargesInfinie()){
				$temp[5]=span("charges Infinies","mun");
			} else {
				$temp[5]=span("charges : ".$this->charges_actu." sur ".$this->charges,"mun");
			}
			$temp[6]=span($this->caracteristique."/".$this->competence." - Diff : ".$this->GetdifficulteUtilisation(),"comp");
			$temp[7]=$this->description;
			$temp[8]=span($this->place." pl","poids");
			if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
				$temp[9]=span($this->prix_base." po","po");
			else 	$temp[9]="";
			$temp[10]=$this->coutPA;
			$temp[11]=$this->coutPI;			
                        $temp[12]=$this->coutPO;
                        $temp[13]=$this->coutPV;
			return $temp;
		}		

		
	}
}

?>