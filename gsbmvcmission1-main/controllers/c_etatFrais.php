<?php

$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
switch ($action) {
	case 'selectionnerMois': {
			$lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
			// Afin de sélectionner par défaut le dernier mois dans la zone de liste
			// on demande toutes les clés, et on prend la première,
			// les mois étant triés décroissants
			$lesCles = array_keys($lesMois);
			$moisASelectionner = $lesCles[0];
			include("views/v_listeMois.php");
			break;
		}
	case 'voirEtatFrais': 
		{
			$leMois = $_REQUEST['lstMois'];
			$lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
			$moisASelectionner = $leMois;
			include("views/v_listeMois.php");
			$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
			$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
			$numAnnee = substr($leMois, 0, 4);
			$numMois = substr($leMois, 4, 2);
			$libEtat = $lesInfosFicheFrais['libEtat'];
			$montantValide = $lesInfosFicheFrais['montantValide'];
			$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
			$dateModif =  $lesInfosFicheFrais['dateModif'];
			$dateModif =  dateAnglaisVersFrancais($dateModif);
			include("views/v_etatFrais.php");

			break;
		}

	case 'fraisMois': {
			$lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
			$lesFraisHF = $pdo->getTypeFrais();
			include("views/v_fraisParMois.php");
			break;
		}
	
		case 'saisieFraisMois': 
		{
			$lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
			$lesFraisHF = $pdo->getTypeFrais();
			include("views/v_fraisParMois.php");
			$mois = $_REQUEST['lstMois'];
			$type = $_REQUEST['tpFrais'];

			// une boucle sur $listlignefraisforfait pour récupérer les variable idFraisforfait et quantite
			// avec la variable idFraisForfait tu pourra récupéré dans $listFrais le montant du type (ETP, KM...)
			
			$listLignesFraisForfait = $pdo->getIdVisiteurEtMontant($mois, $type);
				

			include("views/v_etatMoisResult.php");

			break;
		}


}
