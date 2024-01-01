<?php
/**
 * Gestion de l'affichage des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

$mois = getMois(date('d/m/Y'));
$lesMois = getLesDouzeDerniersMois($mois);
$visiteur = $pdo->getNomsVisiteurs();
$leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
$idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
$visiteurASelectionner = $idVisiteur;
$moisASelectionner = $leMois;
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
switch ($action) {
    case 'selectionnerMois':
        // Afin de sélectionner par défaut le dernier mois dans la zone de liste
        // on demande toutes les clés, et on prend la première,
        // les mois étant triés décroissants
        $lesCles[] = array_keys($lesMois);
        $moisASelectionner = $lesCles[0];
        $lesCles[] = array_keys($visiteur);
        $visiteurASelectionner = $lesCles[0];
        include 'vues/v_listeMoisVisiteurs.php';
        break;
    case 'validerFrais';
        if (empty($lesFraisForfait)) {
            ajouterErreur('Pas de fiche de frais pour ce visiteur ce mois');
            include 'vues/v_erreurs.php';
            header("Refresh: 1;URL=index.php?uc=validerFrais&action=selectionnerMois");
        }else{
            $nbJustificatifs=$pdo->getNbjustificatifs($idVisiteur, $leMois);
           include 'vues/v_validerFrais.php'; 
        }
        
        break;
    case 'corrigerFrais';
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        $nbJustificatifs=$pdo->getNbjustificatifs($idVisiteur, $leMois);
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $leMois, $lesFrais);
            $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
            include 'vues/v_validerFrais.php';
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }

        break;
    case 'corrigerFraisHorsForfait';
    if(isset($_POST['corrigerFHF'])){
        $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
        $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
        $idFrais = filter_input(INPUT_POST, 'idFrais', FILTER_VALIDATE_INT);
        $nbJustificatifs=$pdo->getNbjustificatifs($idVisiteur, $leMois);
        $pdo->majFraisHorsForfait($idFrais,$idVisiteur,$leMois,$libelle,$dateFrais,$montant);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        include 'vues/v_validerFrais.php';
    }
   elseif(isset($_POST['supprimerFHF'])){
        $idFrais = filter_input(INPUT_POST, 'idFrais', FILTER_SANITIZE_STRING);
        $nbJustificatifs=$pdo->getNbjustificatifs($idVisiteur, $leMois);
        $pdo->supprimerFraisHorsForfait($idFrais);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        include 'vues/v_validerFrais.php';
   } elseif (isset($_POST['reporterFHF'])) {
            $leMois2 = getMoisSuivant($leMois);
            $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
            $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
            $libelle2 = 'Refusé '.$libelle;
            $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
            $idFrais = filter_input(INPUT_POST, 'idFrais', FILTER_VALIDATE_INT);
            $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $leMois);
            $pdo->majFraisHorsForfait($idFrais, $idVisiteur, $leMois, $libelle2, $dateFrais, $montant);
            $pdo->creeNouveauFraisHorsForfait($idVisiteur,$leMois2,$libelle,$dateFrais,$montant);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
            include 'vues/v_validerFrais.php';
        }

        break;
}
