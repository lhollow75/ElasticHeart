<?php
include ("connexion.php");

function afficheRetour($reponse, $typeDonnees="") {
	// echo $reponse["commentaire"];
	print_r($reponse);
	// if ($reponse["erreur"] == 0) {
		
	// }
}

// renvoi vrai si l'élément demandé en paramètre est différent de 0 dans la table babysitting
function isOK($idbbsitting, $verif, $bdd) {
	echo "idbbsitting: ";print_r($idbbsitting);echo" ,verif: ";print_r($verif);echo" ,bdd: ";print_r($bdd);echo"<br /><br />";
	
	if ($verif == 'id_hotel') {
		$prepare = 'SELECT id_hotel FROM babysitting WHERE id_babysitting= :idbbsitting';
	} else if ($verif == 'id_famille'){
		$prepare = 'SELECT id_famille FROM babysitting WHERE id_babysitting= :idbbsitting';
	}
	$requete = $bdd->prepare($prepare);
	
	$requete->execute(array(
		'idbbsitting' => $idbbsitting
		));
	while ($donnees = $requete->fetch()){
		// echo"<br />données: ";print_r($donnees);
		// echo"<br />données: ";print_r($donnees[$verif]);
		$reponse = $donnees[$verif];		
		// echo"<br />données: ";print_r($reponse);
	}
	// echo "<br /><br />";die;
	if ($reponse==0) $reponse=false;
		else $reponse=true;
	// echo "<br />reponse: ";print_r($reponse);die;
	return $reponse;
}

function ajoutHotel($nom,$adresse,$cp,$classement, $bdd) {
	// echo "nom: ";print_r($nom);echo ", adresse: ";print_r($adresse);echo ", cp: ";print_r($cp);echo"<br />bdd: ";var_dump($bdd);
	$verif = verifHotel($nom,$adresse,$bdd);
	if ($verif['bool']==true) {
		$requete = $bdd->prepare('INSERT INTO hotel(nom, adresse, cp, classement) VALUES(:nom, :adresse, :cp, :classement)');
		$requete->execute(array('nom' => $nom,
			'adresse' => $adresse,
			'cp' => $cp,
			'classement' => $classement
			));
		$reponse["erreur"] = 0;
		$reponse["commentaire"] = "Ajout effectué";
		$reponse["id_insert"] = $bdd->lastInsertId();
		
		
	} else {
		$reponse["erreur"] = 1;
		$reponse["commentaire"] = "Cet hotel existe déjà, ou un autre hotel est déjà enregistré à cette adresse !";
		$reponse["hotels"] = $verif['hotels'];
		
	}
	
	return $reponse;
}

function verifHotel($nom,$adresse, $bdd) {
	$requete = $bdd->prepare('SELECT * FROM hotel WHERE nom= :nom OR adresse= :adresse ');
	$requete->execute(array(
		'nom' => $nom,
		'adresse' => $adresse,
		));

	while ($donnees = $requete->fetch()){
		$hotels = $donnees['nom'];		
	}
	if (empty($hotels))
		$reponse['bool']=true;
	else {
		$reponse['bool'] = false;
		$reponse['hotels']=$hotels;
	}

	return $reponse;
	
}

function ajoutBbsitting($date,$idhotel,$idfamille,$nbheure,$repas=0,$taxis=0,$pourboire,$commentaire,$idtaux,$taxispaye=0,$bdd) {
	// echo "date: ";print_r($date);echo ", hotel: ";print_r($idhotel);echo ", heures: ";print_r($nbheure);echo ", repas: ";print_r($repas);echo ", taxis: ";print_r($taxis);echo ", pourboire: ";print_r($pourboire);echo ", commentaire: ";print_r($commentaire);echo ", taux: ";print_r($idtaux);echo ", taxis payé: ";print_r($taxispaye);echo"<br />bdd: ";var_dump($bdd); die;
	if ($idfamille!=0 && $idhotel!=0) {
		$verif = verifBbsitting($idfamille,$idhotel,$date, $bdd);
	} else $verif['bool']=true;
	
	// $verif['bool']=true;
	if ($verif['bool']==true) {
		$requete = $bdd->prepare('INSERT INTO babysitting(date, id_hotel, id_famille, nb_heure, repas, taxis, pourboire, commentaire, id_taux, taxis_paye) 
			VALUES(:date, :id_hotel, :id_famille, :nb_heure, :repas, :taxis, :pourboire, :commentaire, :id_taux, :taxis_paye)');
		$requete->execute(array(':date' => $date,
			'id_hotel' => $idhotel,
			'id_famille' => $idfamille,
			'nb_heure' => $nbheure,
			'repas' => $repas,
			'taxis' => $taxis,
			'pourboire' => $pourboire,
			'commentaire' => $commentaire,
			'id_taux' => $idtaux,
			'taxis_paye' => $taxispaye
			));
		$reponse["commentaire"] = "Ajout effectué";
		$reponse["id_insert"] = $bdd->lastInsertId();
		$reponse["erreur"] = 0;
		
	} else {
		$reponse["commentaire"] = "Ce babysitting existe déjà !";
		$reponse["babysitting"] = $verif['bbsitting'];
		$reponse["erreur"] = 1;
	}
	
	return $reponse;
}

function modifBbsitting($idbbsitting, $colonne, $modif, $bdd) {
	$verif = verifIdBbsitting($idbabysitting, $bdd);
	// $verif['bool']=true;
	if ($verif==true) { 
		if ($colonne == 'id_hotel') {
			$prepare = 'UPDATE babysitting SET id_hotel = :modif WHERE id_babysitting= :idbbsitting';
		} else if ($colonne == 'id_famille'){
			$prepare = 'UPDATE babysitting SET id_famille = :modif WHERE id_babysitting= :idbbsitting';
		}
		// echo "idbbsitting: ";print_r($idbbsitting);echo", colonne: ";print_r($colonne);echo", modif: ";print_r($modif);die;
		// $requete = $bdd->prepare('UPDATE babysitting SET :colonne = :modif WHERE id_babysitting= :idbbsitting');
		$requete = $bdd->prepare($prepare);
		$requete->execute(array(
			'idbbsitting' => $idbbsitting,
			'modif' => $modif
			));
		$retour =$requete->fetchAll();
	} else {
		$retour["commentaire"] = "Ce babysitting n'existe pas !";
		$retour["erreur"] = 1;
	}
	return $retour;
}

function modifBabysitting($date, $idhotel, $nbheure, $repas, $taxis, $pourboire, $commentaire, $idtaux, $taxis_paye, $bdd){
	$verif = verifIdBbsitting($idbabysitting, $bdd);
	// $verif['bool']=true;
	if ($verif==true) { 
		$requete = $bdd->prepare('UPDATE babysitting SET date= :date, id_hotel= :idhotel, nb_heure= :nbheure, repas= :repas, taxis = :taxis, pourboire= :pourboire, commentaire= :commentaire; id_taux= :idtaux, taxis_paye= :taxis_paye WHERE id_babysitting= :id_bbsitting');
		$requete->execute(array(':date' => $date,
			'id_hotel' => $idhotel,
			'nb_heure' => $nbheure,
			'repas' => $repas,
			'taxis' => $taxis,
			'pourboire' => $pourboire,
			'commentaire' => $commentaire,
			'id_taux' => $idtaux,
			'taxis_paye' => $taxis_paye
			));
		$reponse["commentaire"] = "Modification effectuée";
		$reponse["id_modif"] = $idbbsitting;
		$reponse["erreur"] = 0;
	} else {
		$reponse["commentaire"] = "Ce babysitting n'existe pas !";
		$reponse["erreur"] = 1;
	}
	
	return $reponse;
}

function verifIdBbsitting($idbbsitting, $bdd) {
	$requete = $bdd->prepare('SELECT count(*) FROM babysitting WHERE id_bbsitting= :id_bbsitting');
	$requete->execute(array(
		'id_bbsitting' => $idbbsitting,
		));

	while ($donnees = $requete->fetch()){
		$babysitting = $donnees;		
	}
	if (empty($babysitting) || $babysitting==0)
		$reponse=false;
	else 
		$reponse = true;
	return $reponse;
}

function verifBbsitting($idfamille,$idhotel, $date, $bdd) {
	$requete = $bdd->prepare('SELECT * FROM babysitting WHERE id_hotel= :id_hotel AND id_famille= :id_famille AND date= :date');
	$requete->execute(array(
		'id_hotel' => $idhotel,
		'id_famille' => $idfamille,
		'date' => $date
		));

	while ($donnees = $requete->fetch()){
		$babysittings = $donnees['id_babysitting'];		
	}
	if (empty($babysittings))
		$reponse['bool']=true;
	else {
		$reponse['bool'] = false;
		$reponse['bbsitting']=$babysittings;
	}

	return $reponse;
}
function infobbsitting($idbbsitting,$bdd) {
	// echo "id bbsitting: ";print_r($idbbsitting);echo", bdd: ";print_r($bdd);
	$requete = $bdd->prepare('SELECT francais, famille.id_langue_1, famille.id_famille, date, nb_heure, repas, taxis, pourboire, commentaire, taxis_paye, classement.description as classement, taux_horaire.description as taux_horaire, nom, adresse, cp, nom_langue, nom_famille, nombre_enfants, pays
		FROM babysitting, hotel, famille, langue, classement, taux_horaire 
		WHERE famille.id_famille = babysitting.id_famille
		AND hotel.id_hotel = babysitting.id_hotel
		AND taux_horaire.id_taux = babysitting.id_taux
		AND famille.id_langue_1 = langue.id_langue
		AND hotel.classement = classement.id_classement
		AND id_babysitting= :idbbsitting');
	$requete->execute(array(':idbbsitting' => $idbbsitting));
	
	while ($donnees = $requete->fetch()){
		$bbsitting = $donnees;		
	}
	// print_r($bbsitting); die;
	if (empty($bbsitting))
		$reponse['bool']=false;
	else {
		$reponse=$bbsitting;
	}

	return $reponse;
}

function ajoutFamille($nom, $nbenfants, $idlangue1, $pays, $francais, $bdd) {
	// echo "nom: ";print_r($nom);echo ", nb enfants: ";print_r($nbenfants);echo ", id langue 1: ";print_r($idlangue1);echo ", pays: ";print_r($pays);echo"<br />bdd: ";var_dump($bdd);die;
	$verif = verifFamille($nom, $pays, $bdd);
	// $verif['bool']=true;
	if ($verif['bool']==true) {
		$requete = $bdd->prepare('INSERT INTO famille(nom_famille, nombre_enfants, id_langue_1, francais, pays) 
			VALUES(:nom, :nbenfants, :idlangue1, :francais, :pays)');
		$requete->execute(array(':nom' => $nom,
			'nbenfants' => $nbenfants,
			'idlangue1' => $idlangue1,
			'francais' => $francais,
			'pays' => $pays
			));
		$reponse["commentaire"] = "Ajout effectué";
		$reponse["id_insert"] = $bdd->lastInsertId();
		$reponse["erreur"] = 0;
	} else {
		$reponse["commentaire"] = "Cette famille existe déjà !";
		$reponse["babysitting"] = $verif['famille'];
		$reponse["erreur"] = 1;
	}
	
	afficheRetour($reponse, "ajoutFamille");
}

function modifFamille($idbbsitting,$idfamille, $nom, $nbenfants, $idlangue1, $pays, $francais, $bdd) {
	// echo "nom: ";print_r($nom);echo ", nb enfants: ";print_r($nbenfants);echo ", id langue 1: ";print_r($idlangue1);echo ", pays: ";print_r($pays);echo"<br />bdd: ";var_dump($bdd);die;
	$verif = verifIdFamille($idfamille, $bdd);
	// $verif['bool']=true;
	if ($verif==true) { 
		$requete = $bdd->prepare('UPDATE famille SET nom_famille= :nom, nombre_enfants= :nbenfants, id_langue_1= :id_langue, francais= :francais, pays = :pays WHERE id_famille= :id_famille');
		$requete->execute(array(':nom' => $nom,
			'nbenfants' => $nbenfants,
			'id_langue' => $idlangue1,
			'francais' => $francais,
			'pays' => $pays,
			'id_famille' => $idfamille
			));
		$reponse["commentaire"] = "Modification effectuée";
		$reponse["id_modif"] = $idbbsitting;
		$reponse["erreur"] = 0;
	} else {
		$reponse["commentaire"] = "Cette famille n'existe pas !";
		$reponse["erreur"] = 1;
	}
	
	return $reponse;
}

function verifIdFamille($idfamille, $bdd) {
	$requete = $bdd->prepare('SELECT count(*) FROM famille WHERE id_famille= :id_famille');
	$requete->execute(array(
		'id_famille' => $idfamille,
		));

	while ($donnees = $requete->fetch()){
		$famille = $donnees;		
	}
	if (empty($famille) || $famille==0)
		$reponse=false;
	else 
		$reponse = true;
	return $reponse;
}

function verifFamille($nom, $pays, $bdd) {
	$requete = $bdd->prepare('SELECT * FROM famille WHERE nom_famille= :nom_famille AND pays =:pays');
	$requete->execute(array(
		'nom_famille' => $nom,
		'pays' => $pays
		));

	while ($donnees = $requete->fetch()){
		$famille = $donnees['id_famille'];		
	}
	if (empty($famille))
		$reponse['bool']=true;
	else {
		$reponse['bool'] = false;
		$reponse['famille']=$famille;
	}

	return $reponse;
	
}

function pays() {
	// liste des pays
	// code numérique, code alpha 2, code Alpha 3, nom du pays
	$countryCode = array(
	array('4', 'AFG', 'AF', 'Afghanistan'), 
	array('710', 'ZAF', 'ZA', 'Afrique du Sud'), 
	array('248', 'ALA', 'AX', 'Aland'), 
	array('8', 'ALB', 'AL', 'Albanie'), 
	array('12', 'DZA', 'DZ', 'Algérie'), 
	array('276', 'DEU', 'DE', 'Allemagne'), 
	array('20', 'AND', 'AD', 'Andorre'), 
	array('24', 'AGO', 'AO', 'Angola'), 
	array('660', 'AIA', 'AI', 'Anguilla'), 
	array('10', 'ATA', 'AQ', 'Antarctique'), 
	array('28', 'ATG', 'AG', 'Antigua-et-Barbuda'), 
	array('682', 'SAU', 'SA', 'Arabie saoudite'), 
	array('32', 'ARG', 'AR', 'Argentine'), 
	array('51', 'ARM', 'AM', 'Arménie'), 
	array('533', 'ABW', 'AW', 'Aruba'), 
	array('36', 'AUS', 'AU', 'Australie'), 
	array('40', 'AUT', 'AT', 'Autriche'), 
	array('31', 'AZE', 'AZ', 'Azerbaïdjan'), 
	array('44', 'BHS', 'BS', 'Bahamas'), 
	array('48', 'BHR', 'BH', 'Bahreïn'), 
	array('50', 'BGD', 'BD', 'Bangladesh'), 
	array('52', 'BRB', 'BB', 'Barbade'), 
	array('112', 'BLR', 'BY', 'Biélorussie'), 
	array('56', 'BEL', 'BE', 'Belgique'), 
	array('84', 'BLZ', 'BZ', 'Belize'), 
	array('204', 'BEN', 'BJ', 'Bénin'), 
	array('60', 'BMU', 'BM', 'Bermudes'), 
	array('64', 'BTN', 'BT', 'Bhoutan'), 
	array('68', 'BOL', 'BO', 'Bolivie'), 
	array('535', 'BES', 'BQ', 'Bonaire', ' Saint-Eustache et Saba'), 
	array('70', 'BIH', 'BA', 'Bosnie-Herzégovine'), 
	array('72', 'BWA', 'BW', 'Botswana'), 
	array('74', 'BVT', 'BV', 'Île Bouvet'), 
	array('76', 'BRA', 'BR', 'Brésil'), 
	array('96', 'BRN', 'BN', 'Brunei'), 
	array('100', 'BGR', 'BG', 'Bulgarie'), 
	array('854', 'BFA', 'BF', 'Burkina Faso'), 
	array('108', 'BDI', 'BI', 'Burundi'), 
	array('136', 'CYM', 'KY', 'Îles Caïmans'), 
	array('116', 'KHM', 'KH', 'Cambodge'), 
	array('120', 'CMR', 'CM', 'Cameroun'), 
	array('124', 'CAN', 'CA', 'Canada'), 
	array('132', 'CPV', 'CV', 'Cap-Vert'), 
	array('140', 'CAF', 'CF', 'République centrafricaine'), 
	array('152', 'CHL', 'CL', 'Chili'), 
	array('156', 'CHN', 'CN', 'Chine'), 
	array('162', 'CXR', 'CX', 'Île Christmas'), 
	array('196', 'CYP', 'CY', 'Chypre'), 
	array('166', 'CCK', 'CC', 'Îles Cocos'), 
	array('170', 'COL', 'CO', 'Colombie'), 
	array('174', 'COM', 'KM', 'Comores'), 
	array('178', 'COG', 'CG', 'République du Congo'), 
	array('180', 'COD', 'CD', 'République démocratique du Congo'), 
	array('184', 'COK', 'CK', 'Îles Cook'), 
	array('410', 'KOR', 'KR', 'Corée du Sud'), 
	array('408', 'PRK', 'KP', 'Corée du Nord'), 
	array('188', 'CRI', 'CR', 'Costa Rica'), 
	array('384', 'CIV', 'CI', 'Côte d\'Ivoire'), 
	array('191', 'HRV', 'HR', 'Croatie'), 
	array('192', 'CUB', 'CU', 'Cuba'), 
	array('531', 'CUW', 'CW', 'Curaçao'), 
	array('208', 'DNK', 'DK', 'Danemark'), 
	array('262', 'DJI', 'DJ', 'Djibouti'), 
	array('214', 'DOM', 'DO', 'République dominicaine'), 
	array('212', 'DMA', 'DM', 'Dominique'), 
	array('818', 'EGY', 'EG', 'Égypte'), 
	array('222', 'SLV', 'SV', 'Salvador'), 
	array('784', 'ARE', 'AE', 'Émirats arabes unis'), 
	array('218', 'ECU', 'EC', 'Équateur'), 
	array('232', 'ERI', 'ER', 'Érythrée'), 
	array('724', 'ESP', 'ES', 'Espagne'), 
	array('233', 'EST', 'EE', 'Estonie'), 
	array('840', 'USA', 'US', 'États-Unis'), 
	array('231', 'ETH', 'ET', 'Éthiopie'), 
	array('238', 'FLK', 'FK', 'Îles Malouines'), 
	array('234', 'FRO', 'FO', 'Îles Féroé'), 
	array('242', 'FJI', 'FJ', 'Fidji'), 
	array('246', 'FIN', 'FI', 'Finlande'), 
	array('250', 'FRA', 'FR', 'France'), 
	array('266', 'GAB', 'GA', 'Gabon'), 
	array('270', 'GMB', 'GM', 'Gambie'), 
	array('268', 'GEO', 'GE', 'Géorgie'), 
	array('239', 'SGS', 'GS', 'Géorgie du Sud-et-les Îles Sandwich du Sud'), 
	array('288', 'GHA', 'GH', 'Ghana'), 
	array('292', 'GIB', 'GI', 'Gibraltar'), 
	array('300', 'GRC', 'GR', 'Grèce'), 
	array('308', 'GRD', 'GD', 'Grenade'), 
	array('304', 'GRL', 'GL', 'Groenland'), 
	array('312', 'GLP', 'GP', 'Guadeloupe'), 
	array('316', 'GUM', 'GU', 'Guam'), 
	array('320', 'GTM', 'GT', 'Guatemala'), 
	array('831', 'GGY', 'GG', 'Guernesey'), 
	array('324', 'GIN', 'GN', 'Guinée'), 
	array('624', 'GNB', 'GW', 'Guinée-Bissau'), 
	array('226', 'GNQ', 'GQ', 'Guinée équatoriale'), 
	array('328', 'GUY', 'GY', 'Guyana'), 
	array('254', 'GUF', 'GF', 'Guyane'), 
	array('332', 'HTI', 'HT', 'Haïti'), 
	array('334', 'HMD', 'HM', 'Îles Heard-et-MacDonald'), 
	array('340', 'HND', 'HN', 'Honduras'), 
	array('344', 'HKG', 'HK', 'Hong Kong'), 
	array('348', 'HUN', 'HU', 'Hongrie'), 
	array('833', 'IMN', 'IM', 'Île de Man'), 
	array('581', 'UMI', 'UM', 'Îles mineures éloignées des États-Unis'), 
	array('92', 'VGB', 'VG', 'Îles Vierges britanniques'), 
	array('850', 'VIR', 'VI', 'Îles Vierges des États-Unis'), 
	array('356', 'IND', 'IN', 'Inde'), 
	array('360', 'IDN', 'ID', 'Indonésie'), 
	array('364', 'IRN', 'IR', 'Iran'), 
	array('368', 'IRQ', 'IQ', 'Irak'), 
	array('372', 'IRL', 'IE', 'Irlande'), 
	array('352', 'ISL', 'IS', 'Islande'), 
	array('376', 'ISR', 'IL', 'Israël'), 
	array('380', 'ITA', 'IT', 'Italie'), 
	array('388', 'JAM', 'JM', 'Jamaïque'), 
	array('392', 'JPN', 'JP', 'Japon'), 
	array('832', 'JEY', 'JE', 'Jersey'), 
	array('400', 'JOR', 'JO', 'Jordanie'), 
	array('398', 'KAZ', 'KZ', 'Kazakhstan'), 
	array('404', 'KEN', 'KE', 'Kenya'), 
	array('417', 'KGZ', 'KG', 'Kirghizistan'), 
	array('296', 'KIR', 'KI', 'Kiribati'), 
	array('414', 'KWT', 'KW', 'Koweït'), 
	array('418', 'LAO', 'LA', 'Laos'), 
	array('426', 'LSO', 'LS', 'Lesotho'), 
	array('428', 'LVA', 'LV', 'Lettonie'), 
	array('422', 'LBN', 'LB', 'Liban'), 
	array('430', 'LBR', 'LR', 'Liberia'), 
	array('434', 'LBY', 'LY', 'Libye'), 
	array('438', 'LIE', 'LI', 'Liechtenstein'), 
	array('440', 'LTU', 'LT', 'Lituanie'), 
	array('442', 'LUX', 'LU', 'Luxembourg'), 
	array('446', 'MAC', 'MO', 'Macao'), 
	array('807', 'MKD', 'MK', 'Macédoine'), 
	array('450', 'MDG', 'MG', 'Madagascar'), 
	array('458', 'MYS', 'MY', 'Malaisie'), 
	array('454', 'MWI', 'MW', 'Malawi'), 
	array('462', 'MDV', 'MV', 'Maldives'), 
	array('466', 'MLI', 'ML', 'Mali'), 
	array('470', 'MLT', 'MT', 'Malte'), 
	array('580', 'MNP', 'MP', 'Îles Mariannes du Nord'), 
	array('504', 'MAR', 'MA', 'Maroc'), 
	array('584', 'MHL', 'MH', 'Marshall'), 
	array('474', 'MTQ', 'MQ', 'Martinique'), 
	array('480', 'MUS', 'MU', 'Maurice'), 
	array('478', 'MRT', 'MR', 'Mauritanie'), 
	array('175', 'MYT', 'YT', 'Mayotte'), 
	array('484', 'MEX', 'MX', 'Mexique'), 
	array('583', 'FSM', 'FM', 'Micronésie'), 
	array('498', 'MDA', 'MD', 'Moldavie'), 
	array('492', 'MCO', 'MC', 'Monaco'), 
	array('496', 'MNG', 'MN', 'Mongolie'), 
	array('499', 'MNE', 'ME', 'Monténégro'), 
	array('500', 'MSR', 'MS', 'Montserrat'), 
	array('508', 'MOZ', 'MZ', 'Mozambique'), 
	array('104', 'MMR', 'MM', 'Birmanie'), 
	array('516', 'NAM', 'NA', 'Namibie'), 
	array('520', 'NRU', 'NR', 'Nauru'), 
	array('524', 'NPL', 'NP', 'Népal'), 
	array('558', 'NIC', 'NI', 'Nicaragua'), 
	array('562', 'NER', 'NE', 'Niger'), 
	array('566', 'NGA', 'NG', 'Nigeria'), 
	array('570', 'NIU', 'NU', 'Niue'), 
	array('574', 'NFK', 'NF', 'Île Norfolk'), 
	array('578', 'NOR', 'NO', 'Norvège'), 
	array('540', 'NCL', 'NC', 'Nouvelle-Calédonie'), 
	array('554', 'NZL', 'NZ', 'Nouvelle-Zélande'), 
	array('86', 'IOT', 'IO', 'Territoire britannique de l\'océan Indien'), 
	array('512', 'OMN', 'OM', 'Oman'), 
	array('800', 'UGA', 'UG', 'Ouganda'), 
	array('860', 'UZB', 'UZ', 'Ouzbékistan'), 
	array('586', 'PAK', 'PK', 'Pakistan'), 
	array('585', 'PLW', 'PW', 'Palaos'), 
	array('275', 'PSE', 'PS', 'Autorité Palestinienne'), 
	array('591', 'PAN', 'PA', 'Panama'), 
	array('598', 'PNG', 'PG', 'Papouasie-Nouvelle-Guinée'), 
	array('600', 'PRY', 'PY', 'Paraguay'), 
	array('528', 'NLD', 'NL', 'Pays-Bas'), 
	array('604', 'PER', 'PE', 'Pérou'), 
	array('608', 'PHL', 'PH', 'Philippines'), 
	array('612', 'PCN', 'PN', 'Îles Pitcairn'), 
	array('616', 'POL', 'PL', 'Pologne'), 
	array('258', 'PYF', 'PF', 'Polynésie française'), 
	array('630', 'PRI', 'PR', 'Porto Rico'), 
	array('620', 'PRT', 'PT', 'Portugal'), 
	array('634', 'QAT', 'QA', 'Qatar'), 
	array('638', 'REU', 'RE', 'La Réunion'), 
	array('642', 'ROU', 'RO', 'Roumanie'), 
	array('826', 'GBR', 'GB', 'Royaume-Uni'), 
	array('643', 'RUS', 'RU', 'Russie'), 
	array('646', 'RWA', 'RW', 'Rwanda'), 
	array('732', 'ESH', 'EH', 'Sahara occidental'), 
	array('652', 'BLM', 'BL', 'Saint-Barthélemy'), 
	array('659', 'KNA', 'KN', 'Saint-Christophe-et-Niévès'), 
	array('674', 'SMR', 'SM', 'Saint-Marin'), 
	array('663', 'MAF', 'MF', 'Saint-Martin (Antilles françaises)'), 
	array('534', 'SXM', 'SX', 'Saint-Martin'), 
	array('666', 'SPM', 'PM', 'Saint-Pierre-et-Miquelon'), 
	array('336', 'VAT', 'VA', 'Saint-Siège (État de la Cité du Vatican)'), 
	array('670', 'VCT', 'VC', 'Saint-Vincent-et-les-Grenadines'), 
	array('654', 'SHN', 'SH', 'Sainte-Hélène', ' Ascension et Tristan da Cunha'), 
	array('662', 'LCA', 'LC', 'Sainte-Lucie'), 
	array('90', 'SLB', 'SB', 'Salomon'), 
	array('882', 'WSM', 'WS', 'Samoa'), 
	array('16', 'ASM', 'AS', 'Samoa américaines'), 
	array('678', 'STP', 'ST', 'Sao Tomé-et-Principe'), 
	array('686', 'SEN', 'SN', 'Sénégal'), 
	array('688', 'SRB', 'RS', 'Serbie'), 
	array('690', 'SYC', 'SC', 'Seychelles'), 
	array('694', 'SLE', 'SL', 'Sierra Leone'), 
	array('702', 'SGP', 'SG', 'Singapour'), 
	array('703', 'SVK', 'SK', 'Slovaquie'), 
	array('705', 'SVN', 'SI', 'Slovénie'), 
	array('706', 'SOM', 'SO', 'Somalie'), 
	array('729', 'SDN', 'SD', 'Soudan'), 
	array('728', 'SSD', 'SS', 'Soudan du Sud'), 
	array('144', 'LKA', 'LK', 'Sri Lanka'), 
	array('752', 'SWE', 'SE', 'Suède'), 
	array('756', 'CHE', 'CH', 'Suisse'), 
	array('740', 'SUR', 'SR', 'Suriname'), 
	array('744', 'SJM', 'SJ', 'Svalbard et Île Jan Mayen'), 
	array('748', 'SWZ', 'SZ', 'Swaziland'), 
	array('760', 'SYR', 'SY', 'Syrie'), 
	array('762', 'TJK', 'TJ', 'Tadjikistan'), 
	array('158', 'TWN', 'TW', 'Taïwan / (République de Chine (Taïwan))'), 
	array('834', 'TZA', 'TZ', 'Tanzanie'), 
	array('148', 'TCD', 'TD', 'Tchad'), 
	array('203', 'CZE', 'CZ', 'République tchèque'), 
	array('260', 'ATF', 'TF', 'Terres australes et antarctiques françaises'), 
	array('764', 'THA', 'TH', 'Thaïlande'), 
	array('626', 'TLS', 'TL', 'Timor oriental'), 
	array('768', 'TGO', 'TG', 'Togo'), 
	array('772', 'TKL', 'TK', 'Tokelau'), 
	array('776', 'TON', 'TO', 'Tonga'), 
	array('780', 'TTO', 'TT', 'Trinité-et-Tobago'), 
	array('788', 'TUN', 'TN', 'Tunisie'), 
	array('795', 'TKM', 'TM', 'Turkménistan'), 
	array('796', 'TCA', 'TC', 'Îles Turques-et-Caïques'), 
	array('792', 'TUR', 'TR', 'Turquie'), 
	array('798', 'TUV', 'TV', 'Tuvalu'), 
	array('804', 'UKR', 'UA', 'Ukraine'), 
	array('858', 'URY', 'UY', 'Uruguay'), 
	array('548', 'VUT', 'VU', 'Vanuatu'), 
	array('862', 'VEN', 'VE', 'Venezuela'), 
	array('704', 'VNM', 'VN', 'Viêt Nam'), 
	array('876', 'WLF', 'WF', 'Wallis-et-Futuna'), 
	array('887', 'YEM', 'YE', 'Yémen'), 
	array('894', 'ZMB', 'ZM', 'Zambie'), 
	array('716', 'ZWE', 'ZW', 'Zimbabwe')
	);
	return $countryCode;
}




