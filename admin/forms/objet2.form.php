<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: objet2.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.17 $
$Date: 2006/09/05 05:50:49 $

*/

	$template_main .= "<script type='text/javascript'>
		var chainetemp = '$provoqueetat';
	
			
		function getCssValue(tagRef,element) {
		  //var tag = document.all(tagRef);
		  var tag = document.getElementById(tagRef);
		  var value= tag.style[element];
		  if(value==null || value=='' || value=='undefined'){
		    var aClass = tag.className;
		    var cssRules = 'rules';
		    for (var sSheet=0; sSheet < document.styleSheets.length; sSheet++){
		    	  if (document.styleSheets[0].cssRules) {
				for (var rule=0; rule < document.styleSheets[sSheet].cssRules.length; rule++) {
			    		var currentClass = document.styleSheets[sSheet].cssRules[rule].selectorText.substring(1);
		        		if (currentClass == aClass) {
		          			return document.styleSheets[sSheet].cssRules[rule].style[element];
		        		}
		      		}	    	  	
	
		      	} else {	
			      for (var rule=0; rule < document.styleSheets[sSheet][cssRules].length; rule++) {
				    var currentClass = document.styleSheets[sSheet][cssRules][rule].selectorText.substring(1);
			        if (currentClass == aClass) {
			          return document.styleSheets[sSheet][cssRules][rule].style[element];
			        }
			      }
		      }
		    }
		  }
		  return  value;
		}	
	
	
		function RAZ(){
			document.forms[1].ListAdd.options.length = 0;
			document.forms[0].chaine.value = '';
			document.forms[0].provoqueetatValue.value = '';
			chainetemp= '';
		}
		
		function ajouteprovoqueetat(operateur){
	
			if (operateur=='-') { 
				classoption=getCssValue('etatsupprime','color');
				style = 'etatsupprime';
			}
			else {
				 classoption=getCssValue('etatajout','color');			
				 style = 'etatajout';
			}	 
			chainetemp = chainetemp + operateur + document.forms[1].provoqueetat.options[document.forms[1].provoqueetat.selectedIndex].value 
				+ ';' + document.forms[1].pourcent.value + ';' + document.forms[1].duree.value+ '|' ;
			document.forms[1].ListAdd.options.length = document.forms[1].ListAdd.options.length +1;
			document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].text =
				document.forms[1].provoqueetat.options[document.forms[1].provoqueetat.selectedIndex].text 
				+ ';' + document.forms[1].pourcent.value + '%;' + document.forms[1].duree.value+ ' h' ;
			document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].style.color=classoption;
			document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].style.fontWeight='bold';
			document.forms[0].chaine.value = chainetemp;
			document.forms[0].provoqueetatValue.value = document.forms[0].provoqueetatValue.value + 
			'<option class=\''+style+'\' value=\''+operateur+document.forms[1].provoqueetat.options[document.forms[1].provoqueetat.selectedIndex].value+'\'>' 
			+ document.forms[1].provoqueetat.options[document.forms[1].provoqueetat.selectedIndex].text 
			+ ';' + document.forms[1].pourcent.value + '%;' + document.forms[1].duree.value+ ' h' 
			+ '<\/option>';	
		}
	
					
	</script>";
	$template_main .= "<table class='detailscenter'  width='60%'>";
	$template_main .= "<tr><td>";
	
	$SQL = "Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1 WHERE T1.id_etattemp > 1 ORDER BY T1.nom ASC";
	
	$template_main .= "Influence sur les états<br />(<span id='etatajout' class='etatajout'>Etats Ajoutes</span><br /><span id='etatsupprime' class='etatsupprime'>Etats Supprimes</span>)</td><td>";
	$template_main .= "\t<form action='".NOM_SCRIPT."' method='post'>";
	if (NOM_SCRIPT<>"supprimer_lieu.".$phpExtJeu && NOM_SCRIPT<>"supprimer_objet.".$phpExtJeu && NOM_SCRIPT<>"supprimer_magie.".$phpExtJeu) {
		$var=faitSelect("provoqueetat",$SQL,"",-1);	
		$template_main .= $var[1];
		/*
		//un lieu ou une armure provoque toujours les etats		
		if (NOM_SCRIPT=="creer_lieu.".$phpExtJeu ||NOM_SCRIPT=="modifier_lieu.".$phpExtJeu) {
			 $readonly2= " readonly='readonly'  ";
			 $disabled2= " disabled='disabled'  "; 
		}
		else {*/ $readonly2= "";$disabled2= ""; //}			
		$template_main .= "<br />Pourcentage de déclenchement: <input value='100' $readonly2 name='pourcent' type='texte' /><br />";
		$template_main .= "Durée en heures: <input value='-1' name='duree' $readonly2 type='texte' />";
		$template_main .= "<br /><br />";
		$template_main .= "<input value=\"Provoque l'etat\" type='button' onclick=\"ajouteprovoqueetat('')\" />";
		$template_main .= "<input value=\"Annule l'etat\" type='button' onclick=\"ajouteprovoqueetat('-')\" />";
		$template_main .= "<input value=\"Voir l'etat\" type='button' onclick=\"a('../bdc/etat.$phpExtJeu?for_mj=1&amp;num_etat='+document.forms[1].provoqueetat.options[document.forms[1].provoqueetat.selectedIndex].value)\" />";	
		$template_main .= "<br />&nbsp;<br />";
	}
$template_main .= "<select name=\"ListAdd\" size='5'>".stripslashes($provoqueetatValue)."</select><br />&nbsp;<br />";
if (NOM_SCRIPT<>"supprimer_lieu.".$phpExtJeu && NOM_SCRIPT<>"supprimer_objet.".$phpExtJeu && NOM_SCRIPT<>"supprimer_magie.".$phpExtJeu) 
	$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ(1)\" />";
$template_main .= "</form></td></tr>";
$template_main .= "</table>";

?>