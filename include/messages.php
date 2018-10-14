<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: messages.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.38 $
$Date: 2010/01/24 19:33:13 $

*/


if(!defined("__MESSAGES.PHP") ) {
	Define("__MESSAGES.PHP",	0);

	$sepMessage="||";
	
	function GetMessage($msg,$valeurs=array()){
		global $$msg;
		global $sepMessage;

		$retour = $$msg;

		/*for($i=0;$i<count($valeurs);$i++)
			if (isset($valeurs[$i]))
				$retour = str_replace("{".$i."}",$valeurs[$i],$retour);
			else $retour = str_replace("{".$i."}"," ",$retour);	
*/
		foreach($valeurs as $k => $v) 
			$retour = str_replace("{".$k."}",$v,$retour);

		if (! $valeurs==array())  {
			$tab = array ();
			$tab = explode($sepMessage, $retour);
	
			if (isset($tab[1])) {			 
				$retour = $tab[0] . degats($valeurs[8], $tab[1]).$tab[2];
				$i=3;
				while (isset($tab[$i])) {
					if (($i % 2)==0)
						$retour.= degats($valeurs[8], $tab[$i]);
					else 	$retour.= $tab[$i];
					$i++;
				}	
			}
		}
		return $retour;
	}



	function degats($PVMax, $PVadditionnels) {
		$coeff = abs($PVadditionnels)*100/$PVMax;
		if ($PVadditionnels>0) {
			if($coeff >= POURCENTAGE_PV_PERSO_AUTOP)
				$message = GetMessage("soinultime",null);
			else if($coeff <= POURCENTAGE_PV_PERSO_CRITIQUE)	
				$message = GetMessage("soinimportant",null);
			else if($coeff <= POURCENTAGE_PV_PERSO_ABIME)	
				$message = GetMessage("soinleger",null);
			else if($coeff <= POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE)
				$message = GetMessage("soinminime",null);
		}		
		else  {
			if($coeff >= POURCENTAGE_PV_PERSO_AUTOP)
				$message = GetMessage("degatultime",null);
			else if($coeff <= POURCENTAGE_PV_PERSO_CRITIQUE)	
				$message = GetMessage("degatimportant",null);
			else if($coeff <= POURCENTAGE_PV_PERSO_ABIME)	
				$message = GetMessage("degatleger",null);
			else if($coeff <= POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE)
				$message = GetMessage("degatminime",null);	
		}		
		//logDate("valeur retourne= ".$message);	
		return $message;					
	}	


	function EnvoyerMail($adresseExpediteur="", $adresse,$sujet,$txt, $fichiers_attaches = array()){
	/** 	
	$fichiers_attaches = tableau de noms de fichiers ( chemin + nomfichier)
	
	*/
                $eol="\n";
		if ($adresseExpediteur=="")
			$adresseExpediteur=NOM_JEU."@".$_SERVER['SERVER_NAME'];
                $frontiere = "--==================_". md5(uniqid(mt_rand()))."==_";
                $attachment_files_list="X-attachments:";
		$headers = "MIME-version: 1.0".$eol;
                $headers .= "Message-ID: <".$adresseExpediteur.">".$eol;
                $headers .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters 		
 
                $headers .= 'Content-Transfer-Encoding: 8bit'.$eol;
		//$headers .= "From: \"".$adresseExpediteur."\" <".$adresseExpediteur.">".$eol;
                $headers .= "From: ".$adresseExpediteur.$eol;
                
		$headers .= "Reply-To: ".$adresseExpediteur.$eol;
                $headers .= "X-Sender: ".$adresseExpediteur.$eol;    
	 
		if ($fichiers_attaches <> array()) {
		    $encoded = "";
		        logdate("fichiers_attaches <> array()");
			foreach($fichiers_attaches as $nomFichier) {
				if (substr($nomFichier,0,4)=="http" || file_exists($nomFichier)) {
					$encoded .="--".$frontiere.$eol;
					$encoded .= "Content-type: application/octet-stream; name=\"".basename($filename)."\";\n";
					$encoded .= 'Content-Transfer-Encoding: base64'.$eol;
					$encoded .= 'Content-Disposition:attachment; filename="'.basename($nomFichier).'"'.$eol.$eol;
					$encoded .= chunk_split(base64_encode(file_get_contents($nomFichier))).$eol;	
					$attachment_files_list .= " ".basename($nomFichier).";";    
				}
				else logDate("fichier '". $nomFichier . "' introuvable",E_USER_NOTICE,true);
			}
		}

                if ($attachment_files_list!="X-attachments:") {
                        $headers .= "Content-type: multipart/mixed; boundary=\"$frontiere\"".$eol;
                        $headers .= $attachment_files_list.$eol;
                        $message = "--" . $frontiere .$eol;
                        // $message .="Content-Type: text/plain; charset=\"windows-1252\"".$eol.$eol;
                        $message .="Content-Type: text/html; charset=\"windows-1252\"".$eol.$eol;
                        $message .= $txt.$eol. $encoded ;
                        //fin du mail
                        $message .= "--" . $frontiere . "--" .$eol;
                }                
                else  {
                        //  $headers .="Content-Type: text/plain; charset=\"windows-1252\"".$eol.$eol;
                        $headers .="Content-Type: text/html; charset=\"windows-1252\"".$eol.$eol;
                        $message = $txt.$eol;
                }  
                $headers .= $eol.$eol;
		if (function_exists('mail')) {
			return mail($adresse, $sujet, $message, $headers);
		}	
		else logDate("fonction mail inexistante",E_USER_NOTICE,true);
		return true;
	}


	$mauvais_param_sort = "Le sort préféré de ".span("{2}","pj")." n'est pas correctement paramétré. Or, son actionSurprise est jeter un sort.";
	$mauvais_param_phrase = "La phrase préférée de ".span("{2}","pj")." n'est pas paramétrée. Or, son actionSurprise est parler.";

	$loginrefuse = "Désolé mais vous vous êtes trompés ou votre inscription n'a pas encore été validée";
	$paspossible="Action impossible dans les conditions actuelles de votre ".span("PJ","pj");
	$droitsinsuffisants="Vous n'avez pas les droits suffisants pour cette action";
	$remisepa="Remise de ".span("PAs","pa")." effectuée.";
	$remisepi="Remise de ".span("PIs","pi")." effectuée.";	
	$nocha="Désolé mais vous ne pouvez lancer votre ".span("{0}","sort").", vous n'avez plus de  ".span("charges","mun");
	$nomun="Désolé mais vous ne pouvez attaquer avec votre ".span("{0}","objet").", vous n'avez plus de  ".span("munitions","mun");
	$noright="Désolé mais vous ne pouvez effectuer cette action dans ce ".span("lieu","lieu")." .";
	$nopas="Désolé mais vous ne possédez pas assez de ".span("points d'actions","pa")." ou de ".span("points d'intellect","pi")." pour cette action.";
	$nopvs="Désolé, mais vous êtes un peu mort là. Donc votre action, on l'oublie.";
	$nopas_spect="Ce personnage est actuellement paralysé.";
	$nopvs_spect="Et comme vous êtes très observateur, vous vous apercevez qu'il est un peu mort là.";
	$pjcritique_spect=" Ce personnage semble sur le point de s'écrouler.";
	$pjabime_spect=" Ce personnage semble assez amoché. Du sang s'écoule de plusieurs blessures.";
	$pjlegerementblesse_spect=" Ce personnage a quelques echymoses mais rien de bien grave.";
	$pjautop_spect=" Ce personnage semble au top de sa forme.";
	$pjequip_spect= " Ce personnage est équipé avec ";
	$archive="Votre personnage est archivé. Désarchivez-le pour agir.";
	
	$degatminime="Au pire, ca sera une bosse."; 
	$soinminime="Une simple bosse a disparu."; 
	$degatleger="Du sang s'écoule d'une nouvelle blessure."; 
	$soinleger="Une ancienne blessure s'est maintenant refermée."; 
	$degatimportant="Du sang s'écoule de plusieurs blessures."; 
	$soinimportant="Plusieurs blessures se sont maintenant refermées."; 
	$degatultime="Une blessure mortelle."; 
	$soinultime="Toutes les blessures sont refermées."; 
	
	
	
	
	
	
	$noparam="Problèmes de paramètres";
	$ennemipasmort="Vous ne pouvez pas agir sur ".span("{0}","pj").", celui-ci n'est pas encore mort.";
	$ennemimort="Vous ne pouvez pas agir sur ".span("{0}","pj").", celui-ci est deja mort (mais peut-etre ne le sait-il pas encore).";
	$sacplein="Désolé mais votre sac est trop plein pour pouvoir porter un ".span("{0}","objet").".";
	$grimoireplein="Désolé mais votre grimoire est trop chargé pour pouvoir contenir un ".span("{0}","sort").".";
	
	$manger_question = "Que voulez-vous consommer ?";
	$manger = "Prendre : ";
	$manger_impossible = "Vous n'avez rien &agrave; consommer";
	$manger_01="Vous vous arrêtez quelques instants pour ingurgiter votre ".span("{0}","objet").". ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1)
		$manger_01.= "Vous regagnez ".span("{1} PVs","pv");
	else $manger_01.= $sepMessage."{1}".$sepMessage;		
	$manger_01.= " Vous sentez les effets de ".span("{6}","etattemp")." se dissiper et les effets de ".span("{7}","etattemp")." apparaitre.";


	$manger_02="Vous vous arrêtez quelques instants pour ingurgiter votre ".span("{0}","objet").". ";
	$manger_02.= "Vous regagnez ".span("{1} PAs","pa");
	$manger_02.= " Vous sentez les effets de ".span("{6}","etattemp")." se dissiper et les effets de ".span("{7}","etattemp")." apparaitre.";
	
	$manger_03="Vous vous arrêtez quelques instants pour ingurgiter votre ".span("{0}","objet").". ";
	$manger_03.= "Vous regagnez ".span("{1} PIs","pi");
	$manger_03.= " Vous sentez les effets de ".span("{6}","etattemp")." se dissiper et les effets de ".span("{7}","etattemp")." apparaitre.";

	$manger_04="Vous vous arrêtez quelques instants pour ingurgiter votre ".span("{0}","objet").". ";
	$manger_04.= "Vous regagnez ".span("{1} PVs","pv"). " et ".span("{2} PIs","pi");
	$manger_04.= " Vous sentez les effets de ".span("{6}","etattemp")." se dissiper et les effets de ".span("{7}","etattemp")." apparaitre.";

	$manger_05="Vous vous arrêtez quelques instants pour ingurgiter votre ".span("{0}","objet").". ";
	$manger_05.= "Vous regagnez ".span("{1} PAs","pa"). " et ".span("{2} PIs","pi");
	$manger_05.= " Vous sentez les effets de ".span("{6}","etattemp")." se dissiper et les effets de ".span("{7}","etattemp")." apparaitre.";

	$manger_06="Vous vous arrêtez quelques instants pour ingurgiter votre ".span("{0}","objet").". ";
	$manger_06.= "Vous regagnez ".span("{1} PVs","pv"). " et ".span("{2} PAs","pa");
	$manger_06.= " Vous sentez les effets de ".span("{6}","etattemp")." se dissiper et les effets de ".span("{7}","etattemp")." apparaitre.";

	$manger_07="Vous vous arrêtez quelques instants pour ingurgiter votre ".span("{0}","objet").". ";
	$manger_07.= "Vous regagnez ".span("{1} PIs","pi"). ", ".span("{1} PVs","pv"). " et ".span("{2} PAs","pa");
	$manger_07.= " Vous sentez les effets de ".span("{6}","etattemp")." se dissiper et les effets de ".span("{7}","etattemp")." apparaitre.";


	$libelleConfigArmes="Armes présentes dans les 2 mains";

	// attaques avec armes aux deux mains.
	$attaqueEnchainee_01 = "Attaques enchainées: ";
	$attaquer_01="N'écoutant que votre courage, vous empoignez votre ".span("{0}","objet")." et vous foncez sur ".span("{1}","pj")." pour le saigner a mort. Bien joué, ".span("{1}","pj")." a mal. ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $attaquer_01.="Il perd ".span("{3} PVs","pv");
		else $attaquer_01.= $sepMessage."-{3}".$sepMessage;
	$attaquer_01.=" Il perd ".span("{4} PAs","pas").".";
	$attaquer_01bis=" Il commence a ressentir les effets de ".span("{5}","etattemp").", tandis que les effets de ".span("{6}","etattemp")." disparaissent. ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $attaquer_01bis.="De votre coté, vous gagnez ".span("{7} PVs","pv").".";
	else $attaquer_01bis.= $sepMessage."{7}".$sepMessage;
	$attaquer_02="N'écoutant que votre courage, vous empoignez votre ".span("{0}","objet")." et vous foncez sur ".span("{1}","pj")." pour le saigner a mort.   Petit joueur !, ".span("{1}","pj")." vous évite comme une fleur ";
	$attaquer_01ter =" Devant la puissance du coup, il tombe dans une marre de sang, agonisant.";

	//modif Hixcks pas de riposte possible car plus de PA ou ...
	$attaquer_05=" mais ne riposte pas. Un deuxième essai l'énerverait surement.";

	$riposteGroupe=" La réaction ne se fait pas attendre : ";
	
	$attaquer_adv_01="A l'aide de son ".span("{0}","objet").", ".span("{2}","pj")." vous fonce dessus. Aieuh, ca fait mal .";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $attaquer_adv_01.=" Vous perdez ".span("{3} PVs","pv");
	else $attaquer_adv_01.= $sepMessage."-{3}".$sepMessage;
	$attaquer_adv_01.=" , ".span("{4} PAs","pas")."."; 
	$attaquer_adv_01bis= " Vous commencez a ressentir les effets de ".span("{5}","etattemp").", tandis que les effets de ".span("{6}","etattemp")." disparaissent. ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $attaquer_adv_01bis.= "De son coté, ".span("{2}","pj")." gagne ".span("{7} PVs","pv").". ";
	else $attaquer_adv_01bis.= $sepMessage."{7}".$sepMessage;
	$attaquer_adv_01ter= " C'est plus que vous ne pouvez supporter, vous vous effondrez, baignant dans votre sang.";
	$attaquer_adv_02="A l'aide de son ".span("{0}","objet").", ".span("{2}","pj")." vous fonce dessus. Petit joueur !, vous l'évitez comme une fleur. ";

	$attaquer_adv_05=" Petit joueur !, vous l'évitez comme une fleur mais ne pouvez pas riposter.";
	
	$attaquer_spectat_01="A l'aide de son ".span("{0}","objet").", ".span("{2}","pj")." fonce sur ".span("{1}","pj").". Aieuh, ca fait mal. ";
	$attaquer_spectat_02="A l'aide de son ".span("{0}","objet").", ".span("{2}","pj")." fonce sur ".span("{1}","pj").". Petit joueur !, ".span("{1}","pj")." l'évite comme une fleur. ";
    	$attaquer_spectat_02bis=" et lui botte les fesses.";
	$attaquer_spectat_03="A l'aide de son ".span("{0}","objet").", ".span("{2}","pj")." fonce sur ".span("{1}","pj").". C'est plus que ".span("{1}","pj")." ne peut supporter, il s'effondre, baignant dans son sang. ";

	$attaquer_spectat_05="  mais ne riposte pas.";
	
	//lancement sort attaque reussi
	$sort_attaque_01="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Ca explose de partout. ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_attaque_01.=span("{2}","pj")." se prend ".span("{3} PVs","pv")." en moins. ";
	else $sort_attaque_01.= "Résultats sur ".span("{2}","pj"). $sepMessage."-{3}".$sepMessage;
	$sort_attaque_01.=" Il sent les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	$sort_attaque_01_adv="Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête. Dans le mille: ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_attaque_01_adv.="Vous perdez ".span("{3} PVs","pv");
	else $sort_attaque_01_adv .=  $sepMessage."-{3}".$sepMessage;
	$sort_attaque_01_adv.=" Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	$sort_attaque_01_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj") .". Dans le mille, ".span("{2}","pj")." morfle grave";

	$sort_attaqueDistant_01_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj");

	$sort_attaqueDistant_01_spect_adv ="Un eclair zèbre le ciel et  ".span("{2}","pj") ." est touché par ".span("{0}","sort")." Dans le mille, ".span("{2}","pj")." morfle grave";

	
	//lancement sort attaque reussi => mort
	$sort_attaque_02="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Ca explose de partout, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_attaque_02.="et ".span("{2}","pj")." se prend ".span("{3} PVs","pv")." en moins. ";
	else $sort_attaque_02.= "Résultats sur  ".span("{2}","pj").", ".$sepMessage."-{3}".$sepMessage;
	$sort_attaque_02.="Il sent les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre. C'en est trop pour lui, il explose dans une gerbe de sang et perd conscience";
	$sort_attaque_02_adv="Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête. Dans le mille: ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_attaque_02_adv.="Vous perdez ".span("{3} PVs","pv");
	else $sort_attaque_02_adv.=  $sepMessage."-{3}".$sepMessage;
	$sort_attaque_02_adv.=" Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre. Ce coup vous a ete fatal, vous sombrez peu à peu dans la mort";
	$sort_attaque_02_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj") .". Dans le mille, ".span("{2}","pj")." morfle grave.  Ce coup lui a ete fatal, il sombre peu à peu dans la mort";
	
	
	//lancement sort attaque rate
	$sort_attaque_03="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Pas de bol, ca vous revient en pleine poire, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_attaque_03.=" et vous perdez ".span("{3} PVs","pv").".";
	else $sort_attaque_03.= $sepMessage."-{3}".$sepMessage;
	$sort_attaque_03_adv="Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête. perdu !! le sort lui revient dans la tête";
	$sort_attaque_03_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj") .". perdu !! le sort lui revient dans la tête";
	
	//lancement sort attaque rate => mort 
	$sort_attaque_04="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Pas de bol, ca vous revient en pleine poire, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_attaque_04.=" et vous perdez ".span("{3} PVs","pv").".";
	else $sort_attaque_04.=  $sepMessage."-{3}".$sepMessage;
	$sort_attaque_04.=" C'en est trop, vous vous effondrez comme une loque, baignant dans votre sang.";
	$sort_attaque_04_adv="Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête. perdu !! le sort lui revient dans la tête. Et pour couronner le tout, ce retour de flamme l'a achevé, il git maintenant sur le sol.";
	$sort_attaque_04_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj") .". perdu !! le sort lui revient dans la tête. Et pour couronner le tout, ce retour de flamme l'a achevé, il git maintenant sur le sol.";
	
	
	$sort_transfert_01="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Ca explose de partout, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) {
		$sort_transfert_01.="et ".span("{2}","pj")." se prend ".span("{3} PVs","pv")." en moins";
		$sort_transfert_01.=", ce qui vous fait ".span("{4} PVs","pv")." en plus.";
	}		
	else {
		$sort_transfert_01.="Résultats sur ".span("{2}","pj"). $sepMessage."-{3}".$sepMessage;
		$sort_transfert_01.="et ".$sepMessage."{4}".$sepMessage. " pour vous";
	}		
	$sort_transfert_01.=" Il sent les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	
	$sort_transfert_01_adv="Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête. Dans le mille, vous morflez grave, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) { 
		$sort_transfert_01_adv.="vous perdez ".span("{3} PVs","pv");
		$sort_transfert_01_adv.=". et lui en gagne ".span("{4}","pv");
	}	
	else {
		$sort_transfert_01_adv.="Résultats sur vous ". $sepMessage."-{3}".$sepMessage;
		$sort_transfert_01_adv.="et ".$sepMessage."{4}".$sepMessage. " pour lui";
	}		

	$sort_transfert_01_adv.=". Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	$sort_transfert_01_spect=span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj").". Dans le mille ".span("{2}","pj")." morfle grave et ".span("{1}","pj")." se sent mieux ." ;
	$sort_transfert_02="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Ca explose de partout. ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_transfert_02 .= span("{2}","pj")." se prend ".span("{3} PVs","pv")." en moins, ce qui vous fait ".span("{4} PVs","pv")." en plus. ";
	else {
		$sort_transfert_02 .= "Résultats sur ". span("{2}","pj")." :". $sepMessage."-{3}".$sepMessage; 
		$sort_transfert_02.=" Et ".$sepMessage."{4}".$sepMessage. " pour vous";
	}	
	$sort_transfert_02 .=" Il sent les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre. C'en est trop pour lui, il explose dans une gerbe de sang et perd conscience";
	$sort_transfert_02_adv="Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête. Dans le mille, vous morflez grave, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_transfert_02_adv .="vous perdez ".span("{3} PVs","pv")." et lui en gagne ".span("{4}","pv");
	else {
		$sort_transfert_02_adv.="Résultats sur vous: ". $sepMessage."-{3}".$sepMessage;
		$sort_transfert_02_adv.="et ".$sepMessage."{4}".$sepMessage. " pour lui";
	}		
	$sort_transfert_02_adv .=". Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre. Ce coup vous a ete fatal, vous sombrez peu à peu dans la mort";
	$sort_transfert_02_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj").". Dans le mille ".span("{2}","pj")." morfle grave. Ce coup lui a ete fatal, et sombre peu à peu dans la mort.  ".span("{1}","pj")." se sent mieux ." ;
	$sort_transfert_03="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Pas de bol, ca vous revient en pleine poire.";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_transfert_03 .=" Vous perdez ".span("{3} PVs","pv").".";
	else 	$sort_transfert_03.=" Résultats sur vous: ". $sepMessage."-{3}".$sepMessage;

	$sort_transfert_03_adv="Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête. perdu !! le sort lui revient dans la tête, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_transfert_03_adv .="et il perd ".span("{3} PVs","pv").".";
	else 	$sort_transfert_03_adv.=" Résultats sur lui: ". $sepMessage."-{3}".$sepMessage;
	$sort_transfert_03_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj").". perdu !! le sort lui revient dans la tête." ;
	$sort_transfert_04="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Pas de bol, ca vous revient en pleine poire, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_transfert_04 .="et vous perdez ".span("{3} PVs","pv");
	else 	$sort_transfert_04.=" Résultats sur vous: ". $sepMessage."-{3}".$sepMessage;
	$sort_transfert_04 .=". C'en est trop, vous vous effondrez comme une loque, baignant dans votre sang.";
	$sort_transfert_04_adv="Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête. perdu !! le sort lui revient dans la tête, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_transfert_04_adv .="et il perd ".span("{3} PVs","pv");
	else 	$sort_transfert_04_adv.=" Résultats sur lui: ". $sepMessage."-{3}".$sepMessage;
	$sort_transfert_04_adv .=". Et pour couronner le tout, ce retour de flamme l'a achevé, il git maintenant sur le sol";
	$sort_transfert_04_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj").". perdu !! le sort lui revient dans la tête. Et pour couronner le tout, ce retour de flamme l'a achevé, il git maintenant sur le sol" ;
	
	//lancement soin reussi
	$sort_soin_01= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Ca brille, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_soin_01.=" et ".span("{2}","pj")." gagne ".span("{4} PVs","pv")." en plus. ";
	else $sort_soin_01.= "Résultats: ". $sepMessage."{4}".$sepMessage;
	$sort_soin_01.= " Il sent les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";	
	$sort_soin_01_adv=span("{1}","pj")." se penche sur ".span("{2}","pj")." et invoque ".span("{0}","sort").". ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_soin_01_adv.=span("{2}","pj") . " gagne ".span("{4} PVs","pv");
	else $sort_soin_01_adv.= "Résultats: ". $sepMessage."{4}".$sepMessage;
	$sort_soin_01_adv.=" . Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	$sort_soin_01_spect=span("{1}","pj")." se penche sur ".span("{2}","pj")." et invoque ".span("{0}","sort").". ".span("{2}","pj"). " se sent mieux";
	
	//lancement soin rate
	$sort_soin_02="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Pas de bol, ca vous revient en pleine poire, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_soin_02.=" vous perdez ".span("{4} PVs","pv").".";
	else $sort_soin_02.= "Résultats pour vous: ". $sepMessage."{4}".$sepMessage;
	$sort_soin_02_adv=span("{1}","pj")." se penche sur ".span("{2}","pj")." et invoque ".span("{0}","sort").". " .span("{2}","pj"). " ne sent strictement rien, mais ".span("{1}","pj").", lui, semble aller moins bien.";
	$sort_soin_02_spect=span("{1}","pj")." se penche sur ".span("{2}","pj")." et invoque ".span("{0}","sort").". ".span("{2}","pj"). " ne se sent pas mieux";

        //lancement sort soin rate => mort 
	$sort_soin_04="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Pas de bol, ca vous revient en pleine poire, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_soin_04.=" vous perdez ".span("{4} PVs","pv").".";
	else $sort_soin_04.= "Résultats pour vous: ". $sepMessage."-{4}".$sepMessage;

	$sort_soin_04.=" C'en est trop, vous vous effondrez comme une loque, baignant dans votre sang.";
	$sort_attaque_04_adv="Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête. perdu !! le sort lui revient dans la tête. Et pour couronner le tout, ce retour de flamme l'a achevé, il git maintenant sur le sol.";
	$sort_attaque_04_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj") .". perdu !! le sort lui revient dans la tête. Et pour couronner le tout, ce retour de flamme l'a achevé, il git maintenant sur le sol.";


	$sort_soin_04_adv=span("{1}","pj")." se penche sur ".span("{2}","pj")." et invoque ".span("{0}","sort").". " .span("{2}","pj"). " ne sent strictement rien, mais ".span("{1}","pj").", lui git maintenant sur le sol.";
	$sort_soin_04_spect=span("{1}","pj")." se penche sur ".span("{2}","pj")." et invoque ".span("{0}","sort").". ".span("{2}","pj"). " ne se sent pas mieux. Et pour couronner le tout, ce retour de flamme a achevé ".span("{1}","pj")." qui git maintenant sur le sol.";


	$sort_resurrection_01= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Ca brille et ".span("{2}","pj")." revient à la vie. ";
	$sort_resurrection_01.= " Il sent les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";	
	$sort_resurrection_01_adv=span("{1}","pj")." se penche sur ".span("{2}","pj")." et invoque ".span("{0}","sort").". Ca brille et ".span("{2}","pj")." revient à la vie. ";
	$sort_resurrection_01_adv.=" . Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	$sort_resurrection_01_spect=span("{1}","pj")." se penche sur ".span("{2}","pj")." et invoque ".span("{0}","sort").". Ca brille et ".span("{2}","pj")." revient à la vie. ";
	
	//lancement resurrection rate
	$sort_resurrection_02="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Pas de bol, ca vous revient en pleine poire, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_resurrection_02.=" vous gagnez ".span("{4} PVs","pv").".";
	else $sort_resurrection_02.= "Résultats pour vous: ". $sepMessage."{4}".$sepMessage;
	$sort_resurrection_02_adv=span("{1}","pj")." se penche sur ".span("{2}","pj")." et invoque ".span("{0}","sort").". " .span("{2}","pj"). " ne sent strictement rien, mais ".span("{1}","pj").", lui, semble allez beaucoup mieux.";
	$sort_resurrection_02_spect=span("{1}","pj")." se penche sur ".span("{2}","pj")." et invoque ".span("{0}","sort").". ".span("{2}","pj"). " ne se sent pas mieux";
	
	$sort_resurrection_imp = span("{2}","pj")." n'est pas mort. Lancement du sort ".span("{0}","sort")." impossible.";
	
	$sort_teleport_self_01= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort").". Bien joué, vous voila maintenant à ".span("{8}","lieu").", et en plus vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	$sort_teleport_self_02= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort").". Rien à faire, vous restez sur place,à ".span("{8}","lieu").", et vous vous sentez ridicule.";

	$sort_teleport_01= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Zam ! ".span("{2}","pj")." se retrouve propulsé à ".span("{8}","lieu")." en plus. Il sent les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	$sort_teleport_01_adv= "Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête pour vous ejecter. Dans le mille, vous Vous retrouvez d'un coup à ".span("{8}","lieu")." Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	$sort_teleport_01_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj")." pour l'ejecter. Dans le mille, ".span("{2}","pj")." vient de disparaitre .";
	$sort_teleport_02= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Raté ! ".span("{2}","pj")." ne bouge pas d'un iota.";
	$sort_teleport_02_adv= "Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête pour vous ejecter. Quel nul, il ne se passe rien.";
	$sort_teleport_02_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj")." pour l'ejecter. Quel nul, il ne se passe rien.";

	$sort_paralysie_01= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Ca explose de partout, et ".span("{2}","pj")." se prend ".span("{5} PAs","pa")." en moins. Il sent les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	$sort_paralysie_01_adv="Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête. Dans le mille, vous morfle grave, vous perdez ".span("{5} PAs","pa")." Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	$sort_paralysie_01_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête  de ".span("{2}","pj")." Dans le mille, ".span("{2}","pj")." morflez grave."; 
	$sort_paralysie_02="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","pj").". Pas de bol, ca vous revient en pleine poire, et vous perdez ".span("{5} PAs","pa").".";
	$sort_paralysie_02_adv="Attention, ".span("{1}","pj")." vous envoie un ".span("{0}","sort")." dans la tête. perdu !! le sort lui revient dans la tête, et il perd ".span("{5} PAs","pa").".";
	$sort_paralysie_01_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête  de ".span("{2}","pj")." perdu !! le sort lui revient dans la tête."; 
	

/*
	$sort_attaqueZone_01="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","lieu");
	
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_attaque_01details=span("{2}","pj")." se prend ".span("{3} PVs","pv")." en moins. ";
	else $sort_attaque_01details= "Résultats sur ".span("{2}","pj"). $sepMessage."-{3}".$sepMessage;
	$sort_attaque_01details.=" Il sent les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	
	$sort_attaque_01_adv="Attention, ".span("{1}","pj")." envoie un ".span("{0}","sort")." sur ".span("{2}","lieu"). ". Dans le mille: ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_attaque_01_adv.="Vous perdez ".span("{3} PVs","pv");
	else $sort_attaque_01_adv .=  $sepMessage."-{3}".$sepMessage;
	$sort_attaque_01_adv.=" Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	$sort_attaque_01_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans la tête de ".span("{2}","pj") .". Dans le mille, ".span("{2}","pj")." morfle grave";
	
	//lancement sort attaque reussi => mort
	$sort_attaqueZone_02=$sort_attaqueZone_01;
	$sort_attaqueZone_02_adv=$sort_attaqueZone_01_adv;
	$sort_attaqueZone_02_spect= $sort_attaqueZone_01_spect;
	
	
	//lancement sort attaque rate
	$sort_attaqueZone_03=$sort_attaqueZone_01;
	$sort_attaqueZone_03.=". Pas de bol, ca vous revient en pleine poire, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_attaqueZone_03.=" et vous perdez ".span("{3} PVs","pv").".";
	else $sort_attaqueZone_03.= $sepMessage."-{3}".$sepMessage;	
	$sort_attaqueZone_03_adv=$sort_attaqueZone_01_adv;
	//PJ presents sur le meme lieu que le lanceur
	$sort_attaqueZone_03_spect= $sort_attaqueZone_01_spect ." Perdu !! le sort lui revient dans la tête";

	
	//lancement sort attaque rate => mort 
	$sort_attaqueZone_04=$sort_attaqueZone_01;
	$sort_attaqueZone_04.=". Pas de bol, ca vous revient en pleine poire, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_attaqueZone_04.=" et vous perdez ".span("{3} PVs","pv").".";
	else $sort_attaqueZone_04.= $sepMessage."-{3}".$sepMessage;	
	$sort_attaqueZone_04.=" C'en est trop, vous vous effondrez comme une loque, baignant dans votre sang.";	
	$sort_attaqueZone_04_adv=$sort_attaqueZone_01_adv;
	//PJ presents sur le meme lieu que le lanceur
	$sort_attaqueZone_04_spect= $sort_attaqueZone_01_spect ." Perdu !! le sort lui revient dans la tête. Et pour couronner le tout, ce retour de flamme l'a achevé, il git maintenant sur le sol.";";


	$sort_transfertZone_01="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","lieu");
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) {
		$sort_transfertZone_01.=", ce qui vous fait ".span("{4} PVs","pv")." en plus.";
	}		
	else {
		$sort_transfertZone_01.="Résultats ".$sepMessage."{4}".$sepMessage. " pour vous";
	}		
	
	$sort_transfertZone_01_adv="Un eclair zèbre le ciel et les personnes presentes en ".span("{2}","lieu"). " sont frappees par ".span("{0}","sort");
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) { 
		$sort_transfertZone_01_adv.="vous perdez ".span("{3} PVs","pv");
	}	
	else {
		$sort_transfertZone_01_adv.="Résultats sur vous ". $sepMessage."-{3}".$sepMessage;
	}		
	$sort_transfertZone_01_adv.=". Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	//PJ presents sur le meme lieu que le lanceur
	$sort_transfertZone_01_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans le lieu ".span("{2}","lieu");	
	
	$sort_transfertZone_02=$sort_transfertZone_01;
	$sort_transfertZone_02_adv=sort_transfertZone_01_adv;
	$sort_transfertZone_02_adv .=". Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre. Ce coup vous a ete fatal, vous sombrez peu à peu dans la mort";
	//PJ presents sur le meme lieu que le lanceur
	$sort_transfertZone_02_spect=$sort_transfertZone_01_spect .span("{1}","pj")." se sent mieux ." ;
	
	$sort_transfertZone_03=$sort_transfertZone_01.". Pas de bol, ca vous revient en pleine poire.";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_transfertZone_03 .=" Vous perdez ".span("{3} PVs","pv").".";
	else 	$sort_transfertZone_03.=" Résultats sur vous: ". $sepMessage."-{3}".$sepMessage;

	$sort_transfertZone_03_adv="Un eclair zèbre le ciel et les personnes presentes en ".span("{2}","lieu"). " sont frappees par ".span("{0}","sort")." .Mais cela ne semble pas les avoir gênés.";
	
	//PJ presents sur le meme lieu que le lanceur
	$sort_transfertZone_03_spect= $sort_transfertZone_01.". perdu !! le sort lui revient dans la tête." ;

	$sort_transfertZone_04=$sort_transfertZone_01.". Pas de bol, ca vous revient en pleine poire.";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_transfertZone_04 .="et vous perdez ".span("{3} PVs","pv");
	else 	$sort_transfertZone_04.=" Résultats sur vous: ". $sepMessage."-{3}".$sepMessage;
	$sort_transfertZone_04 .=". C'en est trop, vous vous effondrez comme une loque, baignant dans votre sang.";
	$sort_transfertZone_04_adv=$sort_transfertZone_03_adv;

	//PJ presents sur le meme lieu que le lanceur
	$sort_transfertZone_04_spect= sort_transfertZone_03_spect. " Et pour couronner le tout, ce retour de flamme l'a achevé, il git maintenant sur le sol" ;
	
	//lancement soin reussi
	$sort_soinZone_01= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","lieu");
	$sort_soinZone_01_adv=$sort_attaqueZone_01_adv;
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_soinZone_01_adv.="Vous gagnez ".span("{4} PVs","pv");
	else $sort_soinZone_01_adv.= "Résultats: ". $sepMessage."{4}".$sepMessage;
	$sort_soinZone_01_adv.=" . Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	//PJ presents sur le meme lieu que le lanceur
	$sort_soinZone_01_spect=$sort_attaqueZone_01_spect;
	
	//lancement soin rate
	$sort_soinZone_02=$sort_soinZone_01;
	$sort_soinZone_02_adv=$sort_soinZone_01_adv;
	//PJ presents sur le meme lieu que le lanceur
	$sort_soinZone_02_spect=$sort_soinZone_01_spect;
	

	$sort_teleportZone_01= $sort_soinZone_01;
	$sort_teleportZone_01_adv= "Un eclair zèbre le ciel et les personnes presentes en ".span("{2}","lieu"). " sont frappees par ".span("{0}","sort"). " Dans le mille, vous vous retrouvez d'un coup à ".span("{8}","lieu")." Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	//PJ presents sur le meme lieu que le lanceur
	$sort_teleportZone_01_spect= $sort_soinZone_01_spect;
	$sort_teleportZone_02= $sort_soinZone_01;
	$sort_teleportZone_02_adv= $sort_transfertZone_03_adv;
	//PJ presents sur le meme lieu que le lanceur
	$sort_teleportZone_02_spect= $sort_soinZone_01_spect;

	$sort_paralysieZone_01= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","lieu").".";
	$sort_paralysieZone_01_adv="Un eclair zèbre le ciel et les personnes presentes en ".span("{2}","lieu"). " sont frappees par ".span("{0}","sort"). " Dans le mille, vous vous retrouvez d'un coup à ".span("{8}","lieu")." Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	//PJ presents sur le meme lieu que le lanceur
	$sort_paralysieZone_01_spect= $sort_soinZone_01_spect;
	$sort_paralysieZone_02= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","lieu").". Pas de bol, ca vous revient en pleine poire, et vous perdez ".span("{5} PAs","pa").".";
	$sort_paralysieZone_02_adv=sort_transfertZone_03_adv;
	//PJ presents sur le meme lieu que le lanceur
	$sort_paralysieZone_01_spect= $sort_soinZone_01_spect;
	
	$sort_attaqueZoneDistant_01="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","lieu");
	$sort_attaqueZoneDistant_01_adv="Un eclair zèbre le ciel et les personnes presentes en ".span("{2}","lieu"). " sont frappées par ".span("{0}","sort");
	//PJ presents sur le meme lieu que le lanceur
	$sort_attaqueZoneDistant_01_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans le lieu ".span("{2}","lieu");
	
	//lancement sort attaque reussi => mort
	$sort_attaqueZoneDistant_02=$sort_attaqueZoneDistant_01;
	$sort_attaqueZoneDistant_02_adv=$sort_attaqueZoneDistant_01_adv;
	$sort_attaqueZoneDistant_02_spect= $sort_attaqueZoneDistant_01_spect;
	
	
	//lancement sort attaque rate
	$sort_attaqueZoneDistant_03=$sort_attaqueZoneDistant_01;
	$sort_attaqueZoneDistant_03.=". Pas de bol, ca vous revient en pleine poire, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_attaqueZoneDistant_03.=" et vous perdez ".span("{3} PVs","pv").".";
	else $sort_attaqueZoneDistant_03.= $sepMessage."-{3}".$sepMessage;	
	$sort_attaqueZoneDistant_03_adv=$sort_attaqueZoneDistant_01_adv;
	//PJ presents sur le meme lieu que le lanceur
	$sort_attaqueZoneDistant_03_spect= $sort_attaqueZoneDistant_01_spect ." Perdu !! le sort lui revient dans la tête";

	
	//lancement sort attaque rate => mort 
	$sort_attaqueZoneDistant_04=$sort_attaqueZoneDistant_01;
	$sort_attaqueZoneDistant_04.=". Pas de bol, ca vous revient en pleine poire, ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_attaqueZoneDistant_04.=" et vous perdez ".span("{3} PVs","pv").".";
	else $sort_attaqueZoneDistant_04.= $sepMessage."-{3}".$sepMessage;	
	$sort_attaqueZoneDistant_04.=" C'en est trop, vous vous effondrez comme une loque, baignant dans votre sang.";	
	$sort_attaqueZoneDistant_04_adv=$sort_attaqueZoneDistant_01_adv;
	//PJ presents sur le meme lieu que le lanceur
	$sort_attaqueZoneDistant_04_spect= $sort_attaqueZoneDistant_01_spect ." Perdu !! le sort lui revient dans la tête. Et pour couronner le tout, ce retour de flamme l'a achevé, il git maintenant sur le sol.";";


	$sort_transfertZoneDistant_01="Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","lieu");
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) {
		$sort_transfertZoneDistant_01.=", ce qui vous fait ".span("{4} PVs","pv")." en plus.";
	}		
	else {
		$sort_transfertZoneDistant_01.="Résultats ".$sepMessage."{4}".$sepMessage. " pour vous";
	}		
	
	$sort_transfertZoneDistant_01_adv="Un eclair zèbre le ciel et les personnes presentes en ".span("{2}","lieu"). " sont frappees par ".span("{0}","sort");
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) { 
		$sort_transfertZoneDistant_01_adv.="vous perdez ".span("{3} PVs","pv");
	}	
	else {
		$sort_transfertZoneDistant_01_adv.="Résultats sur vous ". $sepMessage."-{3}".$sepMessage;
	}		
	$sort_transfertZoneDistant_01_adv.=". Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	//PJ presents sur le meme lieu que le lanceur
	$sort_transfertZoneDistant_01_spect= span("{1}","pj")." envoie un ".span("{0}","sort")." dans le lieu ".span("{2}","lieu");	
	
	$sort_transfertZoneDistant_02=$sort_transfertZoneDistant_01;
	$sort_transfertZoneDistant_02_adv=sort_transfertZoneDistant_01_adv;
	$sort_transfertZoneDistant_02_adv .=". Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre. Ce coup vous a ete fatal, vous sombrez peu à peu dans la mort";
	//PJ presents sur le meme lieu que le lanceur
	$sort_transfertZoneDistant_02_spect=$sort_transfertZoneDistant_01_spect .span("{1}","pj")." se sent mieux ." ;
	
	$sort_transfertZoneDistant_03=$sort_transfertZoneDistant_01.". Pas de bol, ca vous revient en pleine poire.";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_transfertZoneDistant_03 .=" Vous perdez ".span("{3} PVs","pv").".";
	else 	$sort_transfertZoneDistant_03.=" Résultats sur vous: ". $sepMessage."-{3}".$sepMessage;

	$sort_transfertZoneDistant_03_adv="Un eclair zèbre le ciel et les personnes presentes en ".span("{2}","lieu"). " sont frappees par ".span("{0}","sort")." .Mais cela ne semble pas les avoir gênés.";
	
	//PJ presents sur le meme lieu que le lanceur
	$sort_transfertZoneDistant_03_spect= $sort_transfertZoneDistant_01.". perdu !! le sort lui revient dans la tête." ;

	$sort_transfertZoneDistant_04=$sort_transfertZoneDistant_01.". Pas de bol, ca vous revient en pleine poire.";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_transfertZoneDistant_04 .="et vous perdez ".span("{3} PVs","pv");
	else 	$sort_transfertZoneDistant_04.=" Résultats sur vous: ". $sepMessage."-{3}".$sepMessage;
	$sort_transfertZoneDistant_04 .=". C'en est trop, vous vous effondrez comme une loque, baignant dans votre sang.";
	$sort_transfertZoneDistant_04_adv=$sort_transfertZoneDistant_03_adv;

	//PJ presents sur le meme lieu que le lanceur
	$sort_transfertZoneDistant_04_spect= sort_transfertZoneDistant_03_spect. " Et pour couronner le tout, ce retour de flamme l'a achevé, il git maintenant sur le sol" ;
	
	//lancement soin reussi
	$sort_soinZoneDistant_01= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","lieu");
	$sort_soinZoneDistant_01_adv=$sort_attaqueZoneDistant_01_adv;
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $sort_soinZoneDistant_01_adv.="Vous gagnez ".span("{4} PVs","pv");
	else $sort_soinZoneDistant_01_adv.= "Résultats: ". $sepMessage."{4}".$sepMessage;
	$sort_soinZoneDistant_01_adv.=" . Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	//PJ presents sur le meme lieu que le lanceur
	$sort_soinZoneDistant_01_spect=$sort_attaqueZoneDistant_01_spect;
	
	//lancement soin rate
	$sort_soinZoneDistant_02=$sort_soinZoneDistant_01;
	$sort_soinZoneDistant_02_adv=$sort_soinZoneDistant_01_adv;
	//PJ presents sur le meme lieu que le lanceur
	$sort_soinZoneDistant_02_spect=$sort_soinZoneDistant_01_spect;
	

	$sort_teleportZoneDistant_01= $sort_soinZoneDistant_01;
	$sort_teleportZoneDistant_01_adv= "Un eclair zèbre le ciel et les personnes presentes en ".span("{2}","lieu"). " sont frappees par ".span("{0}","sort"). " Dans le mille, vous vous retrouvez d'un coup à ".span("{8}","lieu")." Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	//PJ presents sur le meme lieu que le lanceur
	$sort_teleportZoneDistant_01_spect= $sort_soinZoneDistant_01_spect;
	$sort_teleportZoneDistant_02= $sort_soinZoneDistant_01;
	$sort_teleportZoneDistant_02_adv= $sort_transfertZoneDistant_03_adv;
	//PJ presents sur le meme lieu que le lanceur
	$sort_teleportZoneDistant_02_spect= $sort_soinZoneDistant_01_spect;

	$sort_paralysieZoneDistant_01= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","lieu").".";
	$sort_paralysieZoneDistant_01_adv="Un eclair zèbre le ciel et les personnes presentes en ".span("{2}","lieu"). " sont frappees par ".span("{0}","sort"). " Dans le mille, vous vous retrouvez d'un coup à ".span("{8}","lieu")." Vous sentez egalement les effets de ".span("{6}","etattemp")." apparaitre, et les effets de ".span("{7}","etattemp")." disparaitre.";
	//PJ presents sur le meme lieu que le lanceur
	$sort_paralysieZoneDistant_01_spect= $sort_soinZoneDistant_01_spect;
	$sort_paralysieZoneDistant_02= "Hop, on agite les mains, on prend l'air cool et on invoque ".span("{0}","sort")." sur ".span("{2}","lieu").". Pas de bol, ca vous revient en pleine poire, et vous perdez ".span("{5} PAs","pa").".";
	$sort_paralysieZoneDistant_02_adv=sort_transfertZoneDistant_03_adv;
	//PJ presents sur le meme lieu que le lanceur
	$sort_paralysieZoneDistant_01_spect= $sort_soinZoneDistant_01_spect;	
*/
	
	$deplacer_le= "D'un pas nonchalant, vous vous rendez à ".span("{1}","lieu").".";
	$deplacer_la_01= "Hop hop, vous prenez votre petit sac à dos et vous partez vers ".span("{1}","lieu").". Comme une fleur, le voyage se passe sans encombre";
	$deplacer_la_02= "Hop hop, vous prenez votre petit sac à dos et vous partez vers ".span("{1}","lieu").". Pas de bol, en chemin, des gamins se foutent de vous et vous lance des pierres. ";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $deplacer_la_02.= "Vous perdez ".span("{3} PVs","pv");
	else  $deplacer_la_02.= "Résultats: ". $sepMessage."-{3}".$sepMessage;
	$deplacer_la_02mort = "C'en est trop pour vous. Vous vous écroulez, inconscient.";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $deplacer_la_02groupeblesse= span("{2}","pj") ." perd ".span("{3} PVs","pv");
	else  $deplacer_la_02groupeblesse= "Résultats pour ".span("{2}","pj"). $sepMessage."-{3}".$sepMessage;
	$deplacer_la_02groupemort = "C'en est trop pour lui. Il s'écroule, inconscient.";

	$deplacer_lpeage_01 = " Après une tentative de négociation du prix, vous payez le droit de passage et vous accédez à ".span("{1}","lieu").".";
	$deplacer_lpeage_02 = " Vous n'avez pas de quoi vous payez le droit de passage vers ".span("{1}","lieu").".";
	
	$deplacer_lg_01= "Sésame ouvre toi ! vous vous approchez de la porte menant à ".span("{1}","lieu")." et vous dites la phrase secrete ".span("{3}","comp").". Magie ! La porte s'ouvre et vous entrez";
	$deplacer_lg_02= "Sésame ouvre toi ! vous vous approchez de la porte menant à ".span("{1}","lieu")." et vous dites la phrase secrete ".span("{3}","comp").". Rien ! c'est ce qu'on appele un bide";
	$deplacer_lg_03= span("{0}","pj")." donne le bon mot de passe et vous atteignez ".span("{1}","lieu")." sans problèmes. ";
	$deplacer_lg_01b= "Sésame ouvre toi ! vous vous approchez de la porte menant à ".span("{1}","lieu")." et vous dites la phrase secrete ".span("{3}","comp").". Magie ! La porte s'ouvre et vous entrez. Vous en profitez pour tenir la porte afin que ".span("{2}","pj")." puisse passer.";
	
	$deplacer_lp_portetenue= span("{0}","pj")." ouvre la porte menant à ".span("{1}","lieu")." et, gentilement, il vous tient la porte pour vous permettre de passer.";
	$deplacer_lp_01="Vous essayez votre ".span("{3}","objet")." dans la serrure de la porte menant à ".span("{1}","lieu").". Elle entre parfaitement, et la porte s'ouvre comme par enchantement. Vous en profitez pour tenir la porte afin que ".span("{2}","pj")." puisse passer.";
	$deplacer_lp_02="Vous essayez votre ".span("{3}","objet")." dans la serrure de la porte menant à ".span("{1}","lieu")." et vous forcez un petit peu. Ca s'ouvre !. Vous en profitez pour tenir la porte afin que ".span("{2}","pj")." puisse passer.";
	$deplacer_lp_03="Vous sortez votre attirail et vous tentez de crocheter la serrure de la porte menant à ".span("{1}","lieu").". Ce fut difficile, mais vous y êtes arrivé. Vous en profitez pour tenir la porte afin que ".span("{2}","pj")." puisse passer.";
	$deplacer_les_03="Vous sortez votre attirail et vous tentez d'escalader la paroi menant à ".span("{1}","lieu").". Ce fut difficile, mais vous y êtes arrivé. Vous en profitez pour aider ".span("{2}","pj")." à grimper.";
	$deplacer_ln_03="Un plongeon et vous nagez jusqu'à ".span("{1}","lieu").". Ce fut difficile, mais vous y êtes arrivé. Ce fut difficile, mais vous y êtes arrivé. Vous en profitez pour aider ".span("{2}","pj")." à traverser.";
	$deplacer_lp_01b="Vous essayez votre ".span("{3}","objet")." dans la serrure de la porte menant à ".span("{1}","lieu").". Elle entre parfaitement, et la porte s'ouvre comme par enchantement. ";
	$deplacer_lp_02b="Vous essayez votre ".span("{3}","objet")." dans la serrure de la porte menant à ".span("{1}","lieu")." et vous forcez un petit peu. Ca s'ouvre !. ";
	$deplacer_lp_03b="Vous sortez votre attirail et vous tentez de crocheter la serrure de la porte menant à ".span("{1}","lieu").". Ce fut difficile, mais vous y êtes arrivé. ";
	$deplacer_les_03b="Vous sortez votre attirail et vous tentez d'escalader la paroi menant à ".span("{1}","lieu").". Ce fut difficile, mais vous y êtes arrivé. ";
	$deplacer_ln_03b="Un plongeon et vous nagez jusqu'à ".span("{1}","lieu").". Ce fut difficile, mais vous y êtes arrivé. ";

	$deplacer_lp_04="Vous tentez d'ouvrir la porte menant à ".span("{1}","lieu").", malheureusement vous n'y arrivez pas.";
	$deplacer_les_04="Vous tentez d'escalader la paroi menant à ".span("{1}","lieu").", malheureusement vous n'y arrivez pas.";
	$deplacer_ln_04="Vous tentez de nager jusqu'à ".span("{1}","lieu").", malheureusement vous n'y arrivez pas.";
	
	$etattemplieu1 ="Vous sentez les effets de ".span("{6}","etattemp")." apparaitre";
  $etattemplieu2 ="Vous sentez egalement les effets de ".span("{7}","etattemp")." disparaitre.";
	$nodepgroupe1="Votre groupe ne peut se déplacer (un de ses membres est trop fatigué (ou mort) pour vous suivre, ou vous n'etes pas tous ensemble.";
	$nodepgroupe2="Votre groupe ne peut se déplacer (un de ses membres est engagé dans un combat.)";
	$deplacer_groupe = "Déplacement de groupe (initié par ".span("{0}","pj").") : ";
	$entrer_groupeKO = "Vous faites déja partie d'un groupe.";
	$voirGroupeKO = "Vous ne pouvez pas voir ce groupe. Soit vous n'en faites pas partie, soit vous n'etes pas MJ";
	$groupeInexistant = "Ce Groupe n'existe pas";
	$warningGroupe =" Fonctionnement d'un groupe:  Les groupes ont été ajoutés pour permettre à plusieurs PJ de se déplacer de A vers B ensemble de manière synchronisée. Le joueur connecté dont le PJ est membre d'un groupe fait le choix de déplacer tous les membres du groupe ou non (si tous les PJ sont dans un même lieu bien sûr). Dans l'affirmative, tous les membres du groupes suivent. Vous n'êtes plus maître de votre PJ (Ex : Si le joueur fait se déplacer tous les membres du groupe dans un puits sans fonds, cela entrainera la mort de tous. Aucune réclamation ne sera acceptée. Entrer dans un groupe de déplacement n'est pas donc pas sans danger. Pour aider à la communication entre les différents PJs du groupe, l'action 'parler' a été modifiée pour permettre de parler à tous les membres d'un groupe (si tous les PJ sont dans un même lieu bien sûr). Pour ne pas trop avantager les membres d'un groupe, tous les PJs se voient enlevés des PA à chaque déplacement comme s'ils faisaient tous l'action (<br />Comportement lors d'un déplacement vers un lieu où l'on crochette la porte : Un seul PJ crochette (la réussite ou non ne dépand que de lui), mais on enlève des PA à tous les PJ. <br />Comportement lors d'un déplacement vers un lieu distant: On calcule la moyenne de la réputation des joueurs pour éviter les mauvaises rencontres, mais seul le PJ connecté recoit les dégats éventuels.";

        $voirPJGroupeKO="n'est pas dans le même lieu que vous.";

	$gestionGroupeInterdit=" Cette fonctionnalité n'est pas prévue dans ce jeu";
	$seCacherInterdit=" Cette fonctionnalité n'est pas prévue dans ce jeu";
	$gestionEngagementInterdit=" Cette fonctionnalité n'est pas prévue dans ce jeu";

        $calculFatigue01 = " A bout de souffle, il tient à peine debout" ;
        $calculFatigue02 =" Le pas est lent , il doit sortir d'un effort intensif" ;
        $calculFatigue03 =" Quelques crampes , mais c'est pas ça qui l'arretera" ;
        $calculFatigue04 =" En pleine forme , il parcourerait des Km " ;

	$lire_01="Vous vous asseyez tranquillement et vous sortez votre ".span("{0}","objet")." de votre sac. Vous le lisez attentivement. C'était une lecture intéressante, et vous vous sentez beaucoup plus sage. Vous sentez aussi les effets de ".span("{1}","etattemp")." se dissiper et les effets de ".span("{2}","etattemp")." apparaitre.";

	$lire_02="Vous vous asseyez tranquillement et vous sortez votre ".span("{0}","objet")." de votre sac. Vous le lisez attentivement. Manque de bol, vous ne comprenez pas un traitre mot de ce qui est écrit. ";

	$arrivee_lieu2 = "Dès votre arrivée, ";
	$arrivee_lieu = "Dès son arrivée, ";
	$arrivee_lieuSpect = "Dès l'arrivée de " . span("{0}","pj").", ";
	$arrivee_lieu2bis = span("{0}","pj")." s'adresse à vous : ";
	$arrivee_lieubis = "vous vous adressez à ".span("{1}","pj"). ": ";
	
	$fouiller_cadavre_01= "Vous vous penchez sur la carcasse de ".span("{0}","pj")." et vous lui faites les poches. Merveille ! vous trouvez un magnifique ".span("{2}","objet").", que du bonheur !";
	$fouiller_cadavre_01_adv= "Pendant votre inconscience, quelqu'un a fouillé votre sac et s'est emparé de votre ".span("{2}","objet").".";
	$fouiller_cadavre_02= "Vous vous penchez sur la carcasse de ".span("{0}","pj")." et vous lui faites les poches. Vous ne trouvez rien de valeur, vous vous eloignez en pestant et en mettant un coup de latte au cadavre de ".span("{0}","pj");
	$fouiller_cadavre_02_adv= "Pendant votre inconscience, quelqu'un a fouillé votre sac. Apparament, il n'a rien trouvé d'intéressant.";
	$fouiller_cadavre_02_spec= "Vous apercevez ".span("{1}","pj")." profiter de l'inconscience de ".span("{0}","pj").", pour lui fouiller son sac. Apparament, il n'a rien trouvé d'intéressant.";
	$fouiller_cadavre_01_spec= "Vous apercevez ".span("{1}","pj")." profiter de l'inconscience de ".span("{0}","pj").", pour lui fouiller son sac. Apparament, il a trouvé quelque chose ressemblant à ".span("{2}","objet").".";
	
	$demanderFuite="(Demande Automatique) : Je me fais aggressé, je désire fuir.";
	$demanderFuiteSpec="Devant cette aggression, ".span("{0}","pj")." cherche à fuir (Proposer Action faite au MJ).";

	$SOS = " Au secours, je suis aggressé. Qui viendra à mon aide ??";	

	$enlever_armure_01="Vous enlevez votre ".span("{0}","objet")." et vous sentez les effets de ".span("{1}","etattemp")." disparaitre.";
	$enlever_armure_02="Désolé, Vous ne pouvez enlever votre ".span("{0}","objet").". Cet objet maudit ne peut être déséquipe.";
	$mettre_armure_01="Vous revêtez votre ".span("{0}","objet")." et vous sentez les effets de ".span("{1}","etattemp")." apparaitre.";
	$mettre_armure_02="Désolé mais vous portez déja un équipement de type ".span("{0}","objet").".";
	$mettre_armure_03="Désolé mais vos/votre ".span("{0}","objet")." ne peuvent porter plus.";
	
	$donner_objet_01="Vous sortez votre ".span("{2}","objet")." de votre sac et vous le tendez à ".span("{0}","pj").". Ce dernier l'accepte avec un sourire.";
	$donner_objet_01_adv="Vous voyez ".span("{1}","pj")." sortir son ".span("{2}","objet")." de son sac pour vous le donner. Quelle charmante attention, vous le prenez en le remerciant.";
	$donner_objet_02="Vous sortez votre ".span("{2}","objet")." de votre sac et vous le tendez à ".span("{0}","pj").". Ce dernier se voit obliger de le refuser, il n'a malheureusement pas de place pour le ranger.";
	$donner_objet_02_adv="Vous voyez ".span("{1}","pj")." sortir son ".span("{2}","objet")." de son sac pour vous le donner. Quelle charmante attention, mais malheureusement inutile, votre sac ne pourra jamais contenir un objet aussi gros.";

	$donner_argent_01="Vous sortez ".span("{2} POs","po")." de votre bourse et vous les tendez à ".span("{0}","pj").". Ce dernier les accepte avec un sourire.";
	$donner_argent_01_adv="Vous voyez ".span("{1}","pj")." s'approchez de vous, ".span("{2} POs","po")." à la main, et vous les donner généreusement.";
	$donner_argent_02="Désolé, mais vous ne possédez pas les ".span("{2}","po")." que vous voulez offrir a ".span("{0}","pj").".";

	$voler_argent_01="Vous vous approchez sournoisement de ".span("{0}","pj")." et vous lui glissez une main dans la poche. Magique, vous voila maintenant plus riche de ".span("{2} POs","po");
	$voler_argent_02="Vous vous approchez sournoisement de ".span("{0}","pj")." et vous lui glissez une main dans la poche. pas de bol, vous ne trouvez pas sa bourse.";
	$voler_argent_02_adv="Vous prenez ".span("{1}","pj")." la main dans le sac en train de vous faire les poches.";
	$voler_argent_02_spectat= span("{1}","pj")." vient de se faire prendre la main dans le sac en train de faire les poches de ". span("{0}","pj") ;	
	

	$magasin_prix="Prix proposé pour cette transaction ";
	$magasin_abandonner="Abandonner ";
	$magasin_continuer="Continuer ";
	$magasin_abandonner_nego="Las de cette négociation avec ce (pseudo) marchand, vous abandonnez. Il doit surement y en avoir d'autres qui ne sont pas des arnaqueurs";
	$magasin_objet_acheter_nopos="Vous ne possédez pas assez de ".span("POs", "po")." pour vous permettre les ".span("{1}","po")." que coûte un ".span("{0}","objet");
	$magasin_objet_acheter_01="Après une dure négociation, vous réussissez à acheter ".span("{0}","objet")." pour la modique somme de ".span("{1} POs","po").". Affaire ou arnaque ? vous vous en rendrez bien compte en lisant le prix sur l'étiquette." ;
	$magasin_objet_vendre_01="Après une dure négociation, vous réussissez à vendre ".span("{0}","objet")." pour la modique somme de ".span("{1} POs","po").". Affaire ou arnaque ? vous vous en rendrez bien compte en comparant avec la facture du magasin où vous l'aviez acheté." ;
	$magasin_objet_reparer_01="Après une dure négociation, vous faites reparer votre ".span("{0}","objet")." pour la modique somme de ".span("{1} POs","po").". Affaire ou arnaque ? Faut voir combien ca vous aurez couté de le faire faire par un tit nenfant attaché à une enclume." ;
	$magasin_objet_reparer_nopos="Vous ne possédez pas assez de ".span("POs", "po")." pour vous permettre les ".span("{1}","po")." que coûte la réparation de ".span("{0}","objet");
	$magasin_objet_recharger_01="Après une dure négociation, vous faites recharger votre ".span("{0}","objet")." pour la modique somme de ".span("{1} POs","po").". Affaire ou arnaque ? Faut voir si vous êtes doué en combat à main nues." ;
	$magasin_objet_recharger_nopos="Vous ne possédez pas assez de ".span("POs", "po")." pour vous permettre les ".span("{1}","po")." que coûte les munitions pour votre ".span("{0}","objet");
	$magasin_magie_acheter_nopos="Vous ne possédez pas assez de ".span("POs", "po")." pour vous permettre les ".span("{1}","po")." que coûte le ".span("{0}","sort");
	$magasin_magie_acheter_01="Après une dure négociation, vous réussissez à vous faire enseigner ".span("{0}","sort")." pour la modique somme de ".span("{1} POs","po");
	$magasin_magie_acheter_02="Après une dure négociation, vous réussissez à vous faire enseigner ".span("{0}","sort")." pour la modique somme de ".span("{1} POs","po").". Malheureusement, vous n'arrivez pas à le retenir.";
	$magasin_magie_recharger_01="Après une dure négociation, vous faites recharger votre ".span("{0}","sort")." pour la modique somme de ".span("{1} POs","po")."." ;
	$magasin_magie_recharger_nopos="Vous ne possédez pas assez de ".span("POs", "po")." pour vous permettre les ".span("{1}","po")." que coûte la recharge de votre ".span("{0}","sort");

	$oublier_sort="Le jugeant trop mauvais, vous effacer de votre grimoire le sort ".span("{0}","sort").".";
	$abandonner_objet="Le jugeant trop mauvais, vous balancez votre ".span("{0}","objet")." par terre.";
	$detruire_objet="Le jugeant trop mauvais, vous détruisez votre ".span("{0}","objet").".";
	$cacher_objet=" Vous dissimulez votre ".span("{0}","objet")." dans un lieu connu de vous seul, du moins vous l'espérez.";

	//pour l'or
	$abandonner_objet01="Le jugeant trop mauvais, vous balancez une ".span("{0}","objet")." contenant ".span("{1} POs","po") ." par terre.";
	$detruire_objet01="Le jugeant trop mauvais, vous détruisez une ".span("{0}","objet")." contenant ".span("{1} POs","po");
	$cacher_objet01=" Vous dissimulez une ".span("{0}","objet")." contenant ".span("{1} POs","po")." dans un lieu connu de vous seul, du moins vous l'espérez.";


	$recuperer_objet01=span("{0}","objet")." est désormais votre.";
	$recuperer_objet02="Vous ne pouvez récupérer ". span("{0}","objet").". Votre sac est plein.";

	$recuperer_objet03="Vous ramassez ".span("{0}","objet").". Il contenait ".span("{1} POs","po")." qui s'ajoutent à votre pécule existant.";
	
	$banque_01="Vous videz vos poches sur le comptoir et vous déposez ".span("{0} POs","po")." sur votre compte en banque. C'est toujours ça que ces voleurs n'auront pas ... enfin, vous espérez.";
	$banque_02="Vous faites la queue pendant des heures et finalement, c'est votre tour. Vous retirez ".span("{0} POs","po")." de votre compte en banque. Vous êtes riche !";

	$fouiller_lieu_01="Suite à vos recherches, vous découvrez ".span("{0}","objet");
	$fouiller_lieu_02="Malgré vos recherches, vous ne découvrez rien. Soit il n'y a rien à trouver, soit vous avez besoin de changer vos yeux. Sur qui allez vous les prélever ?";
	$fouiller_lieu_chemin01="Suite à vos recherches, vous découvrez un chemin vers ".span("{0}","lieu");
	$apprentissage_01="Après un long entraînement, vous progressez en ".span("{0}","comp");
	$apprentissage_02="Malgré tous vos efforts, vous ne réussissez pas à progresser en ".span("{0}","comp");

	$recharger_objet_01="Vous ne pouvez pas recharger ".span("{0}","objet")." avec ".span("{1}","objet").". Ils ne sont pas du même type.";
	$recharger_objet_02="Vous rechargez ".span("{0}","objet")." avec les {2} munitions de ".span("{1}","objet").". ".span("{0}","objet") ." a désormais {3} munitions";
	$recharger_objet_03="Vous ne pouvez pas recharger ".span("{0}","objet")." avec ".span("{1}","objet").". Le nombre de munitions cumulé est trop important.";
	$secacher_01="Vous vous dissimuler dans l'ombre. Vous pensez que personne ne vous a vu.";
	$secacher_02="Vous ne trouvez aucun endroit où vous dissimuler.";
	$secacher_spect = "Vous apercevez ".span("{0}","pj")." se fondre dans le décor. ";


	$semontrer_01 = "Vous sortez de l'ombre. ";
	$semontrer_spect = span("{0}","pj")." sort de nullepart. ";
	
	$reveler ="Vous désignez la cachette de ".span("{1}","pj"). " à " . span("{2}","pj"); 
	$reveler_spect =span("{0}","pj")." vous montre la cachette de ".span("{1}","pj"); 
	$reveler_adv =span("{0}","pj")." vient de révéler votre cachette à ".span("{2}","pj"); 

	$soinobjetPV_01="Vous utiliser ".span("{0}","objet"). " sur ". span("{2}","pj"). ". Celui-ci semble aller mieux.";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $soinobjetPV_01.= "Il gagne ".span("{4} PVs","pv");
	else  $soinobjetPV_01.= "Résultats: ". $sepMessage."{4}".$sepMessage;
	$soinobjetPV_01.=  "Il sent les effets de ".span("{7}","etattemp")." se dissiper et les effets de ".span("{6}","etattemp")." apparaitre.";

	$soinobjetPI_01="Vous utiliser ".span("{0}","objet"). " sur ". span("{2}","pj"). ". Celui-ci semble aller mieux.";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $soinobjetPI_01.= "Il gagne ".span("{4} PIs","pi");
	else  $soinobjetPI_01.= "Résultats: ". $sepMessage."{4}".$sepMessage;
	$soinobjetPI_01.=  "Il sent les effets de ".span("{7}","etattemp")." se dissiper et les effets de ".span("{6}","etattemp")." apparaitre.";

	$soinobjet_02="Vous utiliser ".span("{0}","objet"). " sur ". span("{2}","pj"). ". Mais il ne semble pas aller mieux.";

	$soinobjetPV_adv01=span("{1}","pj"). " utilise ".span("{0}","objet"). " sur vous. Vous semblez aller mieux.";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $soinobjetPV_adv01.= "Vous gagnez ".span("{4} PVs","pv");
	else  $soinobjetPV_adv01.= "Résultats: ". $sepMessage."{4}".$sepMessage;
	$soinobjetPV_adv01.=  "Vous sentez les effets de ".span("{7}","etattemp")." se dissiper et les effets de ".span("{6}","etattemp")." apparaitre.";

	$soinobjetPI_adv01=span("{1}","pj"). " utilise ".span("{0}","objet"). " sur vous. Vous semblez aller mieux.";
	if (defined("AFFICHE_PV") && AFFICHE_PV==1) $soinobjetPI_adv01.= "Vous gagnez ".span("{4} PIs","pi");
	else  $soinobjetPI_adv01.= "Résultats: ". $sepMessage."{4}".$sepMessage;
	$soinobjetPI_adv01.=  "Vous sentez les effets de ".span("{7}","etattemp")." se dissiper et les effets de ".span("{6}","etattemp")." apparaitre.";

	$soinobjet_adv02=span("{1}","pj"). " utilise ".span("{0}","objet"). " sur vous. Mais cela ne semble pas vous faire de l'effet.";

	$objet_inutilisable = "Vous ne pouvez pas utiliser ".span("{0}","objet"). ". Cet objet est réservé aux ".span("{1}s","etattemp") .".";
	$sort_inutilisable = "Vous ne pouvez pas utiliser ".span("{0}","sort"). ". Ce sort est réservé aux ".span("{1}s","etattemp") .".";

	$combiner_01="Vous sortez vos affaires et tentez tant bien que mal de les combiner afin d'obtenir quelque chose. Après quelques temps d'effort et de travail, vous parvenez finalement à obtenir ".span("{0}","objet");
	$combiner_02="Vous sortez vos affaires et tentez tant bien que mal de les combiner afin d'obtenir quelque chose. Après quelques temps d'effort et de travail, vous manquez lamentablement votre coup, tout sera à refaire !";
	$combiner_03="Votre sac est trop plein pour stocker le nouvel objet";

	// Debut de l'engagement degagement de Tidou
	$des_rater="Vous essayez de vous enfuir, mais ".span("{1}","pj")." vous barre la route. Le combat continue.";
	$des_rater_spec="".span("{0}","pj")." tente de s'enfuir, mais ".span("{1}","pj")." se met dans son chemin et l'empeche de fuir. Le combat continue";
	$des_rater_adv= "".span("{0}","pj")." tente de s'enfuir, mais vous vous mettez dans son chemin et l'empechez de fuir. Le combat continue";
	$des_reussi = "Vous vous enfuyez du combat avec ".span("{1}","pj").". le combat est fini.";
	$des_reussi_adv ="".span("{0}","pj")." s'enfuit. Le combat est fini.";
	
	$des_mort = "Victorieux de votre combat, celui-ci est fini.";
	$des_mort_adv = "Victorieux, votre ennemi s'en va, vous laissant giser par terre.";
	
	$des_mort_non = "Héhéhé.. petit malin, ton adversaire n'est ni mort, ni assomé.";
	
	$propdes ="Vous proposez à ".span("{1}","pj")." de mettre fin a votre combat.";
	$propdes_adv ="".span("{0}","pj")." vous propose de mettre fin a votre combat.";
	$propdes_spec ="".span("{0}","pj")." propose à ". span("{1}","pj")." de mettre fin a votre combat.";
	$propdes_acc ="Vous acceptez la proposition de ".span("{1}","pj").". Votre combat est fini.";
	$propdes_acc_adv="".span("{1}","pj")." accepte votre proposition. Votre combat est fini.";
	$propdes_acc_spec = "".span("{1}","pj")." accepte la proposition de fin de combat de ". span("{0}","pj").". Leur combat est fini.";
	$engagé = "Vous êtes engagé au corps à corps. Vous ne pouvez effectuer cette action.";
	$pasengagé ="Vous n'êtes pas engagé au corps à corps.";
	$desengagementEnAttente="Vous avez déjà proposé de vous désengager.";
	// Fin de l'engagement degagement de Tidou


	$combiner_01="Vous sortez vos affaires et tentez tant bien que mal de les combiner afin d'obtenir quelque chose. Après quelques temps d'effort et de travail, vous parvenez finalement à obtenir ".span("{0}","objet");
	$combiner_02="Vous sortez vos affaires et tentez tant bien que mal de les combiner afin d'obtenir quelque chose. Après quelques temps d'effort et de travail, vous manquez lamentablement votre coup, tout sera à refaire !";

	$pasDeMagasin="Il n'y a pas de magasin dans ce lieu.";
	$pasDeRecolte="Il n'y a rien à récolter/miner dans ce lieu.";
	$pasDeRecolte01="Il n'y a ce produit à récolter/miner dans ce lieu.";
	$pasDOutil="Vous n'avez aucun outil pour cette action.";
	$recolte_02 = "Malgré le temps passé et tous vos efforts, vous ne récoltez rien.";
	$recolte_01 = "Après un peu de recherche et d'efforts, vous récolter ".span("{0}","objet");
	
	//reparation d'objet par Uriel
	$reparation_armure_01 = "Vous sortez votre ".span("{1}","objet"). " pour réparer " .span("{0}","objet").". Voilà, ".span("{0}","objet")." parait tout neuf.";
	$reparation_armure_02 = "Vous sortez votre ".span("{1}","objet"). " pour réparer " .span("{0}","objet").". Mais vous échouez à la tâche.";
	$reparation_armure_03 = "Cet objet n'a pas besoin d'être réparé.";
	$nomunartisan = "Vous n'avez plus de matériaux pour vous servir de ".span("{1}","objet");

	$creation_objet_01="Vous sortez votre ".span("{1}","objet"). " pour travailler sur " .span("{2}","objet")." afin de créer " .span("{0}","objet") . " . Après quelques temps d'effort et de travail, vous parvenez finalement à faconner l'objet voulu." ;
	$creation_objet_02="Vous sortez votre ".span("{1}","objet"). " pour travailler sur " .span("{2}","objet")." afin de créer " .span("{0}","objet") . " . Après quelques temps d'effort et de travail, vous abandonnez et décidez de remettre ca a plus tard.";
	$creation_objet_03="Vous sortez votre ".span("{1}","objet"). " pour travailler sur " .span("{2}","objet")." afin de créer " .span("{0}","objet") . " . Après quelques temps d'effort et de travail, vous manquez lamentablement votre coup, et comble de malchance, vous détruisez votre " .span("{2}","objet");
	
	$crier_troploin = "Il n'y a aucun lieu à proximité. Ca, à aller n'importe où....";

	$RappelEngagement="(Rappel:Si vous utilisez une arme de contact,<br /> vous serez engagés et ne pourrez pas abandonner le combat)";
	
	$logoutOK = "Session correctement detruite";
	
	$ProblemeSQL="Probleme dans requete SQL<br />";
	$pasComposantesSort = "Vous n'avez pas les composantes nécessaires au lancement de ".span("{1}","magie")."<br />";
	$combinerComposantesSortKO = "Vous sortez les composantes de vos poches mais vous vous y prenez comme un manche et n'arrivez à lancer ".span("{1}","magie")."<br />";
	$utiliseComposanteSort = "Utilisant ".span("{1}","objet").", ";
	$melangeComposantesSort = "Mélangeant plusieurs ingrédients requis, ";
	$composantesAbsentes ="Vous n'avez pas les objets nécessaires";
	$quelqun = "Quelqu'un";
	$composanteDetruiteAuLancement = "(détruite au lancement)";
	$composanteConservee = "(conservée)";
	$archiveOK= "Votre personnage est maintenant archiv&eacute;.";	
	$desarchiveOK="Votre personnage est maintenant op&eacute;rationnel.";
	$SituationBanque = "Vous avez ".span("{0} POs","po"). " sur votre compte.";
	$magieDistanteKOlieu = "Malgré tous vos efforts, vous n'arrivez par à lancer ce sort sur ".span("{0} ","lieu"). " Il doit être protégé de la magie. ";	
	$magieDistanteKOperso = "Malgré tous vos efforts, vous n'arrivez par à lancer ce sort sur ".span("{0} ","pj"). " Il doit se trouver dans un lieu protégé de la magie. ";	

	$lieuInaccessible = "Vous ne pouvez vous rendre en ce lieu. Il est sévèrement gardé et seul un ".span("{0} ","etattemp"). " peut y accéder";
	
	$vendreObj="Vendre un objet";
	$acheterObj="Acheter un objet";
	$allerQuestion="où voulez vous aller ?";
	$allerPorte="Ouvrir la porte et aller vers ";
	$allerClef="Utiliser une clef ";
	$allerVers="partir vers ";
	$allerEntrer="Entrer ";
	$allerPassePartout="Utiliser un passe partout";
	$allerCrocheter="Crocheter la porte";
	$tenirPorte="Tenir la porte  &agrave;";
	$allerEscalader="Escalader";
	$allerNager="Nager";
	$questionMotPasse="Quel est le mot de passe ?  ";
	$allerProtege="Entrer dans un lieu prot&eacute;g&eacute;";
	$allerVIP="Entrer dans un lieu VIP. Avez-vous assez de monnaie sur vous ?";
	$allerImpossible="Aucun lieu où aller.";
	$allerCache="Se déplacer discrètement pour rester dissimulé (coûte plus de PA)";
	$allerGroupe = "D&eacute;placement de groupe";
	$prbPorte="Probleme pour tenir la porte, pas de clef, contactez les MJs.";
	
	$creationqueteImpossible="la Quete n'a pu être cree (Raison:";
	$creationQueteOK= "Quete ".span("{1}","quete")." correctement cree";
	$erreurPasTypeQuete="Aucun type de quête indiqué";
	$erreurPasObjetQuete="Aucun objet indiqué";
	$erreurPasPJQuete="Aucun PJ indiqué";
	$erreurPasLieuQuete="Aucun Lieu indiqué";
	$erreurPasPOQuete="Aucun montant indiqué";


	$queteAnonymeRefusee="Vous refusez la quête ".span("{1}","quete");
	$queteRefusee="Vous refusez la quête ".span("{1}","quete"). " de " .span("{2}","pj").". Celui-ci en est fort dépité";
	$queteRefuseeProposant=span("{3}","pj"). " vient de refuser votre quete ". span("{1}","quete");
	$queteAnonymeAcceptee="Vous acceptez la quête ".span("{1}","quete");
	$queteAcceptee="Vous acceptez la quête ".span("{1}","quete"). " de " .span("{2}","pj").". Celui-ci prend acte de votre décision";
	$queteAccepteeProposant=span("{3}","pj"). " vient d'accepter votre quete ". span("{1}","quete");

	$queteAbandonnee="Vous abandonnez la quête ".span("{1}","quete"). " de " .span("{2}","pj").". Celui-ci prend acte de votre décision";
	$queteAbandonneeProposant=span("{3}","pj"). " vient d'abandonner votre quete " .span("{1}","quete");

        $queteSansInteret="Vous décidez que la quête ".span("{1}","quete")." n'est pas pour vous. Y'en-a-t-il une à votre niveau ?";

	$queteEchoueeTemps="Le temps que vous aviez pour accomplir la quête ".span("{1}","quete"). " de " .span("{2}","pj")." est echu. Que va-t-il penser de vous ?";
	$queteEchoueeTempsProposant=span("{3}","pj"). " vient d'échouer dans votre quete ". span("{1}","quete")." (temps limite atteint)";
        $queteAccomplieAValider=span("{3}","pj"). " estime avoir accompli votre quete ". span("{1}","quete")." A vous de valider";
	$queteReussie=span("{3}","pj"). " vient d'accomplir avec succès votre quete ". span("{1}","quete");
	$queteEnAttente01= " La quete ". span("{1}","quete")." est en attente de validation par ". span("{2}","pj");
	$queteAnonymeEnAttente01= " La quete ". span("{1}","quete")." est en attente de validation par son proposant";
	$queteAutoValidee= " La quete ". span("{1}","quete")." est un succes. Vous avez prouve votre valeur"; 
	$queteAutoValideeKO= " La quete ". span("{1}","quete")." ne semble pas être accomplie. "; 
	$queteValidee=span("{2}","pj") ." a estimé que la quete ". span("{1}","quete")." était un succes. Vous avez prouve votre valeur"; 
	$queteAnonymeValidee="Son proposant a estimé que la quete ". span("{1}","quete")." était un succes. Vous avez prouve votre valeur"; 
	$repondreQuete="Accepter ou Refuser une quête";
	$modifQuetePJ="Les quetes de quel PJ voulez vous modifier ?";
	$modifQuetePJOK="Liste des quetes de  ".span("{1}","pj")." correctement modif&eacute;e";
	$queteArepondre="Quelle quête voulez-vous accepter/refuser ?";
	$queteAconsulter="Quelle annonce voulez-vous consulter ?";
	$PasQuetePublique="Aucune annonce ici pour vous";
	$queteAterminer="Quelle quête voulez-vous déclarer comme accomplie ?";
	$PasQueteRefusee="Vous n'avez aucune nouvelle proposition de quete. ";	
	$PasQueteEnCours="Vous n'avez aucune quete en cours. ";
	$queteRecompenses="Voici ce que vous obtenez en récompense : ".span("{1}","quete");
	$quetePunitions="Voici ce que vous obtenez pour votre incompétence : ".span("{1}","quete");
	$pjsansQuete= span("{1}","pj")." n'a pas de quetes";
	$quetesdePJ=" Liste des quetes de ".span("{1}","pj");
	$queteImposee=span("{1}","pj") . " a décidé de vous mettre à l'épreuve pour que vous montriez votre valeur. Voici ce qu'il vous impose sans que vous puissiez y échapper:\n" . span("{2}","quete");
	$queteAnonymeImposee="Quelqu'un a décidé de vous mettre à l'épreuve pour que vous montriez votre valeur. Voici ce qu'il vous impose sans que vous puissiez y échapper:\n" . span("{2}","quete");
	$queteProposee=span("{1}","pj") . " a décidé de vous mettre à l'épreuve pour que vous montriez votre valeur. Voici ce qu'il vous propose :\n" . span("{2}","quete");
	$queteAnonymeProposee="Quelqu'un a décidé de vous mettre à l'épreuve pour que vous montriez votre valeur. Voici ce qu'il vous propose :\n" . span("{2}","quete");
	$queteLimitee=" Rappel : vous n'avez que ".span("{5}","pj") . " jours pour terminer cette quete";
	$queteQuestion = " Acceptez-vous cette tâche ? ";
	$queteMauvaisEtat  ="Vous ne pouvez faire cela à ce stade de cette quete";
	$queteEchecTemps = "Vous n'aviez que jusqu'à ".span("{3}","quete") . " pour terminer la quete ".span("{2}","quete"). ". Ce délai est expiré, vous avez echoué. ". span("{1}","pj") . " ne va pas être content";
        $queteEchec = "Vous avez echoué dans l'entreprise de la quete ".span("{2}","quete"). ". ". span("{1}","pj") . " ne va pas être content";
	$queteAnonymeEchecTemps = "Vous n'aviez que jusqu'à ".span("{3}","quete") . " pour terminer la quete ".span("{2}","quete"). ". Ce délai est expiré, vous avez echoué. Son proposant ne va pas être content";
        $queteAnonymeEchec = "Vous avez echoué dans l'entreprise de la quete ".span("{2}","quete").". Son proposant ne va pas être content";

	$recolter="Récolter/miner un matériau";
	$recolteAvecOutil="Avec l'outil";
	$queteAnnulee=span("{1}","pj") . " a décidé de mettre un terme à la quête " . span("{2}","quete");
	$queteAnonymeAnnulee="La personne qui proposait la quete " . span("{2}","quete") ." a décidé d'y mettre un terme ";
        $queteAsupprimer ="Quelle quete voulez vous supprimer ?";
        $queteAmodifier ="Quelle quete voulez vous modifier ?";
        $PasDeQuete="Il n'existe aucune quete pour le moment";
	$queteModifiée = "Quete ".span("{1}","quete")." correctement modifi&eacute;e";
	$queteSupprimée ="Quete ".span("{1}","quete")." correctement effac&eacute;e";
	$voirQueteKO="Vous ne pouvez pas voir cette Quete. Soit vous ne la possedez pas, soit vous n'etes pas MJ";
	
	$MagasinRienAVendre="Ce magasin n'a rien à vendre pour le moment";
	$MagasinMagRienARecharger="Ce magasin ne recharge aucun de vos sorts";
	$MagasinRienARecharger="Ce magasin ne recharge aucun de vos armes";
	$MagasinRienAReparer="Ce magasin ne peut réparer aucune de vos armes";
	$MagasinRienAAcheter="Vous n'avez rien qui intéresse ce magasin";
	$AcheterSort="Acheter un Sort";
	$RechargerSort="Recharger un Sort";
	$ReparerObjet="Reparer un objet";
	$RechargerObjet="Recharger un objet";
	

	$mailPJFA = "Bonjour {1}\n Une action impliquant votre personnage a eu lieu alors que vous n'etiez pas connect&eacute;.\n Vous pouvez en lire un compte rendu dans votre Fichier d'Action.";
	$FaModifieSujetMail ="Votre FA a été modifié";
	$historique_Quete = "Détails des quetes";

        $PJaSupprimer = "Quel PJ/PNJ voulez vous supprimer ?";
        $BestiaireaSupprimer = "Quel élément du bestiaire voulez vous supprimer ?";
        
        $AucunPJpourTesQuetes="Aucune de vos quêtes n'ont trouvé preneur";
        $historiqueQuete = "De quelle quete voulez vous voir l'historique ?";
        
        
        $quesionInvPJ="L'inventaire de quel PJ voulez vous vous modifier ?";
        
        
        /**
        *       Messages de confirmation
        */
        $ConfirmerSupprimerObjet="Etes vous sur de vouloir effacer les objets eventuellement selectionnes ?";
        
        $ConfirmerSupprimerQuete= "Etes vous sur de vouloir effacer les quetes eventuellement selectionnes ?";
        
        $ConfirmerSupprimerToutesQuetes= "Etes vous sur de vouloir effacer TOUTES les quetes du jeu ?";
        $ConfirmerSupprimerToutesActions= "Etes vous sur de vouloir effacer TOUTES les actions tracées du jeu ?";
        /*$ConfirmerSupprimerTousMonstresMorts= "Etes vous sur de vouloir effacer TOUS les monstres morts ";
        if (defined("DELAI_MORT_MONSTRE") && DELAI_MORT_MONSTRE>0)
                $ConfirmerSupprimerTousMonstresMorts.= "depuis plus de " ". heures ";
        $ConfirmerSupprimerTousMonstresMorts.= "du jeu ?";
        */
        
        $questionInventairePJAModifier="Modifier l'inventaire de ". span("{1}","pj");
        $InventairePJmodifie= "Inventaire de  ".span("{1}","pj")." correctement modif&eacute;";
        $PJpossedeZeroObjet= span("{1}","pj")." n'a pas d'objet dans son inventaire";
        
        //$supprimerMonstresMorts = "Supprimer tous les monstres morts";
        $aucunMort="Aucun PJ/PNJ mort.";
        $questionPJSupprime="Quel PJ/PNJ voulez vous supprimer ?";
        
/** fichiers bdc 
*
*/

        $QueteInexistante="Cette quête n'existe pas";
        $EtatInexistant="Cet état n'existe pas";
        $EtatInvisible="Vous ne pouvez pas voir cet &eacute;tat temporaire. Soit vous ne le possedez pas, soit vous n'etes pas MJ";
        $EtatPNJ="Réservé aux PNJs";
        $EtatPJ="Utilisable par un PJ";

        $modifLieuApparitionOK="Les apparitions du monstre ont bien été modifiées";


        $ObjetsAterre="Il y a des objets par terre";
        $QuetesDanslieu="Il y a des annonces placardées à un écriteau";
        $ChoixQueteNonFait="Vous devez choisir d'accepter ou de refuser cette annnonce";

        $SEquiperKO="Vous n'avez rien &agrave; mettre";
        $QuestionEquiper="Que voulez vous mettre ?";
        
        $RecolteManuelle="Sans outil (ie. à la main)";

        $EtatTempKO = "Etat temp d'ID = ".span("{0}","etattemp")." introuvable";
        
        $Identification = "Vous pouvez vous identifier ici";
        $IdentificationMJKO="Vous devez etre identifi&eacute; en tant que MJ pour afficher cette page.";
        $IdentificationPJKO="Vous devez etre identifi&eacute; en tant que joueur pour afficher cette page.";

        $resurrectionOK="Vous étiez  (encore) mort. Vous voila ressucité, ca va mieux non ?";
        $resurrectionKO="Résurrection non autorisée ou vous avez atteint le quota. PJ définitivement mort.";
        
        $prochaineRemisePA = "Prochaine Remise de PA dans ";
        $prochaineRemisePI = "Prochaine Remise de PI dans ";
        $heureServeur="Heure du serveur principal: ";
        $hopIci = "Hop par ici";
        $bienvenue="Bienvenue";
        $TablesDejaExistantesContinue="Attention!!!! Des tables avec ce préfixe existent déjà. Toutes les données présentes seront perdues. Voulez vous continuer ?";
        $InstallAnnulee="Installation annulée par l'utilisateur";
        $BaseInexistante="La base de donn&eacute;es que vous avez choisie n'existe pas, vous devez la cr&eacute;er avant d'installer Talesta4+ !";
        $PassMJdifferents="Les deux mots de passe du MJ principal ne sont pas identiques";


        //script voler.php
        $questionVoler = "Qui voulez vous voler ?";
        $personneAVoler="Il n'y a personne que vous pourrez voler.";

        //scrit include/online.php
        $LesConnectes = "Les connectés";
        $Joueurs = "Joueurs";
        $MJs="MJs";
        
        
        //menu_admin
        $gestionMJS="Gestion des MJs";
        $gestionPJS="Gestion des PJs";
        $configGene="Configuration Générale";
        $gestionMonstres="Gestion des Monstres";
        $gestionPPAS="Gestion des PPAs";
        $gestionNews="Gestion des News et du Forum";
        $gestionQCM="Gestion des Questions";
        $gestionMagasins="Gestion des Boutiques";
        $gestionChemins="Gestion des chemins";
        $gestionLieux="Gestion des Lieux";
        $gestionQuetes="Gestion des Quetes";
        $gestionMagie="Gestion de la Magie";
        $gestionEtats="Gestion des états temporaires";
        $gestionOBJ="Gestion des objets";
        $modifMJ="Modifier un MJ";

        $gestionLogs="Gestion des logs";     
        $purgerLogs="Purger les logs";
        $voirLogs="Voir les logs";



}

?>
