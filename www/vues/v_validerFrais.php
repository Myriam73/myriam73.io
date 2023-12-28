<?php
/**
 * Vue État de Frais
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
?>

    <form method="post" 
          action="index.php?uc=validerFrais&action=corrigerFrais" 
          role="form">
    <div class="row">
        <div class="col-md-4">
            <h4>Choisir le visiteur : </h4> 
            <div class="form-group">
                <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
                    <?php
                    foreach ($visiteur as $unVisiteur) {
                        $id = $unVisiteur['id'];
                        $nom = $unVisiteur['nom'];
                        $prenom = $unVisiteur['prenom'];
                        if ($id == $visiteurASelectionner) {
                            ?>
                            <option selected value="<?php echo $id ?>">
                                <?php echo $nom . ' ' . $prenom ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $id ?>">
                                <?php echo $nom . ' ' . $prenom ?> </option>
                            <?php
                        }
                    }
                    ?>    

                </select>
            </div>
        </div>
        <div class="col-md-4">
            <h4>Mois : </h4>
            <div class="form-group">
                <select id="lstMois" name="lstMois" class="form-control">
                    <?php
                    foreach ($lesMois as $unMois) {
                        $mois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        if ($mois == $moisASelectionner) {
                            ?>
                            <option selected value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        }
                    }
                    ?>    

                </select>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <FONT color="orange">   
        <h2>Valider la fiche de frais</h2>
        </FONT>
        <h3>Eléments forfaitisés</h3>
        <div class="col-md-4">
            <fieldset>       
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite'];
                    ?>
                    <div class="form-group">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">
                    </div>
                    <?php
                }
                ?>
                <button class="btn btn-success" type="submit">Corriger</button>
                <button class="btn btn-danger" type="reset">Réinitialiser</button>
            </fieldset>

        </div>
        <hr>
    </div>
    </form>
    <br>
    <br>
    <div class="row">
        <form action="index.php?uc=validerFrais&action=corrigerFraisHorsForfait"
               method="post" role="form">
            <div style="border-color: orange" class="panel panel-info">
                <div style="background-color: orange" class="panel-heading">Descriptif des éléments hors forfait</div>
                <table class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th class="date">Date</th>
                            <th class="libelle">Libellé</th>
                            <th class="montant">Montant</th>  
                            <th class="action">&nbsp;</th> 
                        </tr>
                    </thead>  
                    <tbody>
                        <?php
                        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                            $date = $unFraisHorsForfait['date'];
                            $montant = $unFraisHorsForfait['montant'];
                            $id = $unFraisHorsForfait['id'];
                            ?>   
                            <tr>
                                <td> 
                                    <div class="input-group">
                                        <input class="form-control" name='dateFrais' value="<?php echo $date ?>" type="text" maxlength="45">
                                    </div>  
                                </td>
                                <td> 
                                    <div class="input-group">
                                        <input class="form-control" style="width: 300px;" name="libelle" value="<?php echo $libelle ?>" type="text" maxlength="60">
                                    </div>  
                                </td>
                                <td> 
                                    <div class="input-group">
                                        <input class="form-control" name='montant' value="<?php echo $montant ?>" type="text" maxlength="45">
                                    </div>  
                                </td>
                                 <td> 
                                    <div class="input-group">
                                        <input class="form-control" name='idFrais' value="<?php echo $id ?>" type="hidden" maxlength="45">
                                    </div>  
                                    
                                        <input id="corrigerFHF" name="corrigerFHF" type="submit" value="Corriger" class="btn btn-success"/>
                                        <input id="supprimerFHF" name="supprimerFHF" type="submit" value="Supprimer" class="btn btn-danger"/>
                                        <input id="reporterFHF" style="background-color: orange" name="reporterFHF" type="submit" value="Reporter" class="btn btn-success"/>
                                        
                                    </td>
                            </tr>
                            <?php
                        }
                            ?>
                    </tbody>  
                </table>
                <input name="lstMois" type="hidden" id="lstMois" class="form-control" value="<?php echo $moisASelectionner ?>">
                <input name="lstVisiteurs" type="hidden" id="lstVisiteurs" class="form-control" value="<?php echo $visiteurASelectionner ?>">
            </div>
        </form>
    </div>
    <div class="row">
    <div class="col-md-4">
        <form  action="index.php?uc=validerFrais&action=validerFrais" 
              method="post" role="form">
            <div class="form-group">
                <label for="txtMontantHF">Nombre de justificatifs : </label>
               <div style="width: 100px;" class="input-group">
                    <input class="form-control" name='idFrais' value="<?php echo $nbJustificatifs ?>" type="text" maxlength="45">
                </div>
            </div>
            <button class="btn btn-success" type="submit">Valider</button>
            <button class="btn btn-danger" type="reset">Réinitialiser</button>
        </form>
    </div>
</div>
    <br>
    <br>
    <br>
    <br>

