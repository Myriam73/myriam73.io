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

switch ($action) {
    case 'selectionnerMois':
        $mois = getMois(date('d/m/Y'));
        $lesMois = getLesDouzeDerniersMois($mois);
        // Afin de sélectionner par défaut le dernier mois dans la zone de liste
        // on demande toutes les clés, et on prend la première,
        // les mois étant triés décroissants
        $lesCles[] = array_keys($lesMois);
        $moisASelectionner = $lesCles[0];
        $visiteur = $pdo->getNomsVisiteurs();
        $lesCles[] = array_keys($visiteur);
        $visiteurASelectionner = $lesCles[0];
        include 'vues/v_listeMoisVisiteurs.php';
        break;
    case 'validerFrais';
        $mois = getMois(date('d/m/Y'));
        $lesMois = getLesDouzeDerniersMois($mois);
        $visiteur = $pdo->getNomsVisiteurs();
        $leMois = filter_input(INPUT_GET, 'lstMois', FILTER_SANITIZE_STRING);
        $idVisiteur = filter_input(INPUT_GET, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        if (empty($lesFraisForfait)) {
            ajouterErreur('Pas de fiche de frais pour ce visiteur ce mois');
            header("Refresh: 3;URL=index.php?uc=validerFrais&action=selectionnerMois");
            include 'vues/v_erreurs.php';
        }
        include 'vues/v_validerFrais.php';
        break;
}
