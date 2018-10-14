<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: objetpj.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.20 $
$Date: 2010/01/24 16:36:42 $

*/

require_once("../include/extension.inc");
if(!defined("__OBJETPJ.PHP") ) {
	Define("__OBJETPJ.PHP",	0);

	class ObjetPJ extends Objet{
		
		var $id_clef;
		var $Dur_actu;		///< durabilite de l'objet (diminue avec l'utilisation)
		var $Mun_actu;		///< munitions de l'objet (diminue avec l'utilisation)
		var $temporaire;
		var $equipe;		///< indique si l'objet est dans le sac ou s'il est equipe (porte par le PJ)
		var $Numero;		///< numero de l'objet dans l'inventaire du pj (ne sert a rien apparemment)
		/**
			Note: Bizarrement, temporaire n'est pas le contraire de permanent.
			Les seuls objetPJ avec temporaire =1 sont les cles pour passer d'un lieu a un autre.
			Ces objetPJs avec temporaire =1 sont effaces lorsque l'on change de lieu .
			Un objet declare permanent ou non dans l'admin, sera de toute facon non temporaire, une fois 
			dans l'inventaire d'un PJ. Un objet permanent, ne peut etre abandonne, vendu, donne ou pris.
			En contre-partie, il est plus difficile a utiliser.
			temporaire doit plutot etre compris comme local au lieu actuel du PJ.
			
		**/
		function ObjetPJ($id_objet,$id_clef,$numero, $equipe, $temporaire, $Mun_actu, $Dur_actu){
			$retour=$this->Objet($id_objet);
			if (! $retour) {
				$this->id_clef = $id_clef;
				$this->Numero = $numero;
				//$SQL = "SELECT * FROM ".NOM_TABLE_PERSOOBJET." WHERE id_clef =".$this->id_clef;
				//$requete=$db->sql_query($SQL);
				$this->Dur_actu = $Dur_actu;
				$this->Mun_actu = $Mun_actu;
				$this->temporaire = $temporaire;
				$this->equipe = $equipe;
			}
			else {
				$this->Detruire2($id_objet);
				return null;
			}	
		}

		function estequipe(){
			return $this->equipe == 1;
		}

		function GetPrixModifie(){
			$prix = $this->prix_base;
			if($this->amunitionsInfinie()){
				$facmun = 1;
			} else {
				$facmun = $this->Mun_actu/$this->munitions;
			}
			if($this->adurabiliteInfinie()){
				$facdur = 1;
			} else {
				$facdur = $this->Dur_actu/$this->durabilite;
			}
			return round($prix * $facmun * $facdur);
		}

		function Abime($nb_degats=1){
			global $db;
			if(!$this->adurabiliteInfinie()){
				if($this->Dur_actu > $nb_degats){
					$this->Dur_actu -= abs($nb_degats);
					$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET durabilite = ".$this->Dur_actu." WHERE id_clef = ".$this->id_clef;
					if ($db->sql_query($SQL))
						return true;
					else return false;
				} else {
					return $this->Detruire();
				}
			}
			return true;
		}

		function Reparer($nb_degats=1){
			global $db;
			if(!$this->adurabiliteInfinie()){
				if($this->Dur_actu < $this->durabilite){
					$this->Dur_actu += abs($nb_degats);
					$this->Dur_actu = min ($this->Dur_actu,$this->durabilite);
					$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET durabilite = ".$this->Dur_actu." WHERE id_clef = ".$this->id_clef;
					if ($db->sql_query($SQL))
						return true;
					else return false;
				}
			}
			return true;
		}

		function Decharge($nb_munitions=1){
			global $db;
			if(!$this->amunitionsInfinie()){
				if($this->Mun_actu >= $nb_munitions){
					$this->Mun_actu -= abs($nb_munitions);
					$this->Mun_actu = Max(0,$this->Mun_actu);
					$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET munitions = ".$this->Mun_actu." WHERE id_clef = ".$this->id_clef;
					if ($db->sql_query($SQL))
						return true;
					else return false;
				} else {
					return false;
				}
			}
			return true;
		}


		function Recharge($nb_munitions=1){
			global $db;
			if(!$this->amunitionsInfinie()){
				if($nb_munitions>0){
					$this->Mun_actu += abs($nb_munitions);
					$this->Mun_actu = Max(0,$this->Mun_actu);
					$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET munitions = ".$this->Mun_actu." WHERE id_clef = ".$this->id_clef;
					if ($db->sql_query($SQL))
						return true;
					else return false;
				} else {
					return false;
				}
			}
			return true;
		}

		function changeProprio($ID_newprop=null){
			global $db;
			if ($ID_newprop==null)
				$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET id_perso = null, equipe = 0 WHERE id_clef = ".$this->id_clef;
			else	$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET id_perso = ".$ID_newprop.", equipe = 0 WHERE id_clef = ".$this->id_clef;
			if ($db->sql_query($SQL))
				return true;
			else return false;
		}

		function Detruire(){
			global $db;
			$SQL = "DELETE FROM ".NOM_TABLE_PERSOOBJET." WHERE id_clef = ".$this->id_clef;
			if ($db->sql_query($SQL)) {
				return true;					
			}	
			else return false;	
		}

		// au cas, ou on aurait supprime un objet detenu par le perso
		// supprime tous les objets identiques du PERSO
		function Detruire2($id_objet){
			global $db;
			$SQL = "DELETE FROM ".NOM_TABLE_PERSOOBJET." WHERE id_objet = ".$id_objet;
			if ($db->sql_query($SQL))
				return true;
			else return false;
		}
		
		function description(){
			global $phpExtJeu;
			$temp = "";
			$temp[0]=$this->id_clef;
			if($this->estpermanent()){$temp[1]="#";}else{$temp[1]="-";}
			if( ($this->image != "") &&(file_exists("../templates/$template_name/images/".$this->image)) ){
					$temp[2]="<img src='../templates/$template_name/images/".$this->image."' border='0' alt='image de l''objet' />";
				}else{
					$temp[2]= GetImage($this->Soustype);
				}
			if($this->temporaire == 0){ $span = "objet";}else{$span = "temporaire";}
			if($this->equipe == 1){$span = "equipe";}
			if($this->competencespe != ""){
				if($this->anonyme == 0){
					$temp[3] = span($this->nom." (".$this->Soustype.") - ".$this->competencespe,$span);
				} else {
					$temp[3] = span($this->nom." (".$this->Soustype.") - ".$this->competencespe." - anonyme",$span);
				}
			} else {
				if($this->anonyme == 0){
					$temp[3] = span($this->nom." (".$this->Soustype.")",$span);
				} else {
					$temp[3] = span($this->nom." (".$this->Soustype.") - anonyme",$span);
				}
			}
			if($this->equipe == 1){$temp[3] = span("* ".$temp[3]." *","equipe"); }
			$temp_obj = "<a href=\"javascript:a('../bdc/objet.$phpExtJeu?";
			if (defined("PAGE_ADMIN"))
				$temp_obj .= "for_mj=1&amp;";				
			$temp_obj = $temp_obj ."num_obj=".$this->ID."')\">".$temp[3]."</a>";
			$temp[3] = $temp_obj;
			if($this->type == "ArmeMelee" || $this->type == "ArmeJet"){
				$temp[4]=span("D&eacute;gats : ".$this->Degats[0]." &agrave; ".$this->Degats[1],"degats");
				if($this->adurabiliteInfinie()){
					$temp[5]=span("Indestructible","dur");
				} else {
					$temp[5]=span("durabilite : ".$this->Dur_actu." sur ".$this->durabilite,"dur");
				}
				if($this->amunitionsInfinie()){
					$temp[6]=span("Munitons Infinies","mun");
				} else {
					$temp[6]=span("munitions : ".$this->Mun_actu." sur ".$this->munitions,"mun");
				}
				$temp[7]=span($this->caracteristique."/".$this->competence." - Diff : ".$this->GetdifficulteUtilisation(),"comp");
			} 
			else if($this->type == "Outil" ){
				$temp[4]="";
				if($this->adurabiliteInfinie()){
					$temp[5]=span("Indestructible","dur");
				} else {
					$temp[5]=span("durabilite : ".$this->Dur_actu." sur ".$this->durabilite,"dur");
				}
				if($this->amunitionsInfinie()){
					$temp[6]=span("Munitons Infinies","mun");
				} else {
					$temp[6]=span("munitions : ".$this->Mun_actu." sur ".$this->munitions,"mun");
				}
				$temp[7]=span($this->caracteristique."/".$this->competence." - Diff : ".$this->GetdifficulteUtilisation(),"comp");
			} 
			else if($this->type == "Soins"){
				$temp[4]=span("Gain : ".$this->Degats[0]." &agrave; ".$this->Degats[1]." pvs","degats");
				$temp[7]=span($this->caracteristique."/".$this->competence." - Diff : ".$this->GetdifficulteUtilisation(),"comp");
				$temp[6]="";
				$temp[5]="";
			} 
			else if($this->type == "SoinsPI"){
				$temp[4]=span("Gain : ".$this->Degats[0]." &agrave; ".$this->Degats[1]." pis","degats");
				$temp[7]=span($this->caracteristique."/".$this->competence." - Diff : ".$this->GetdifficulteUtilisation(),"comp");
				$temp[6]="";
				$temp[5]="";
			} 
			else {
				if($this->type == "Nourriture"){
					$temp[6]=span($this->Mun_actu." doses sur ".$this->munitions,"mun");
					$temp[4]=span("Gain  : ".$this->Degats[0]." &agrave; ".$this->Degats[1],"degats");
					$temp[5]=" ";
					$temp[7]=" ";
				}	
				else {
					$temp[6]="";
					$temp[7]="";
					$temp[4]="";
					$temp[5]="";
						
					if($this->type == "Armure"){
						$temp[4]=span("Absorbe : ".$this->Degats[0]." &agrave; ".$this->Degats[1],"degats");
						if($this->adurabiliteInfinie()){
							$temp[5]=span("Indestructible","dur");
						} else {
							$temp[5]=span("durabilite : ".$this->Dur_actu." sur ".$this->durabilite,"dur");
						}
						$temp[7]="Protege contre ".span($this->competence,"comp");
					}
					if($this->Soustype == "passe Partout"){
						$temp[4]=span("bonus : ".$this->Degats[0]." &agrave; ".$this->Degats[1],"degats");
						if($this->adurabiliteInfinie()){
							$temp[5]=span("Indestructible","dur");
						} else {
							$temp[5]=span("durabilite : ".$this->Dur_actu." sur ".$this->durabilite,"dur");
						}
					}

					if($this->Soustype == "Livre"){
						$temp[5] = "Fait monter la ";
						if($this->caracteristique != ""){
							$temp[5].= span($this->caracteristique,"comp");
						} else {
							if($this->competence != ""){
								$temp[5].= span($this->competence,"comp");
							} else {
								$temp[5].= span("Sagesse","comp");
							}
						}
					}
				}
			}

			$temp[8]=$this->description;
			if($temp[8]==""){$temp[8]="-";}
			$temp[9]=span($this->poids." kg","poids");
			if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
				$temp[10]=span($this->prix_base." po","po");
			else 	$temp[10]="";
			return $temp;
		}		
	}

}

?>