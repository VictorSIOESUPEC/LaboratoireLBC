<?php

include_once 'pdo.connexionBDD.inc.php';

function getMedicamentById($code) {

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select * from medicament where codem=:code");
        $req->bindValue(':code', $code, PDO::PARAM_INT);
        $req->execute();
        
        $resultat = $req->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $resultat;
}

function getMedicamentsParSecteur($sec_num) {

    $resultat = array();

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select medicament.nomm, stock.quantiterestante, medicament.codem from medicament inner join stock on stock.codem=medicament.codem where sec_num=:sec_num");
        $req->bindValue(':sec_num', $sec_num, PDO::PARAM_INT);
        $req->execute();

        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        while ($ligne) {
            $resultat[] = $ligne;
            $ligne = $req->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $resultat;
}

function getTousLesMedicaments() {
	$resultat = array();

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select medicament.nomm, stock.quantiterestante, medicament.codem from medicament inner join stock on stock.codem=medicament.codem");
        $req->execute();

        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        while ($ligne) {
            $resultat[] = $ligne;
            $ligne = $req->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $resultat;
}

function getLesMedicamentsPourUnUtilisateur($matricule, $sec_num) {
    $resultat = array();

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select distinct medicament.nomm, medicament.codem, quota.quantiterepartie, stock.quantiterestante from medicament, quota, visiteur, profil, stock where profil.valeur=visiteur.matricule and visiteur.matricule=quota.matricule and 
        quota.codem=medicament.codem and visiteur.matricule=:matricule and profil.typeprofil='V' and quota.CODEM = stock.CODEM and stock.SEC_NUM =:sec_num ");
        $req->bindValue(':matricule', $matricule, PDO::PARAM_INT);
        $req->bindValue(':sec_num', $sec_num, PDO::PARAM_INT);
        $req->execute();

        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        while ($ligne) {
            $resultat[] = $ligne;
            $ligne = $req->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $resultat;
}

function getMedicamentByName($nomM) {
    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select * from medicament where nomm=:nomM");
        $req->bindValue(':nomM', $nomM, PDO::PARAM_STR);
        $req->execute();
        
        $resultat = $req->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $resultat;
}

function insertMedicament($nomM) {
    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("INSERT INTO medicament(NOMM) VALUES(:nomM)");
        $req->bindValue(':nomM', $nomM, PDO::PARAM_STR);
        $req->execute();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $req;
}

function getUnUtilisateur($mat) {

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select * from profil, visiteur where profil.valeur = visiteur.matricule and valeur=:mat and typeprofil='V'");
        $req->bindValue(':mat', $mat, PDO::PARAM_INT);
        $req->execute();
        
        $resultat = $req->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $resultat;
}

function getUtilisateursParSecteur($sec_num) {

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select * from profil, visiteur where profil.valeur = visiteur.matricule and sec_num=:sec_num and typeprofil='V'");
        $req->bindValue(':sec_num', $sec_num, PDO::PARAM_INT);
        $req->execute();

        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        while ($ligne) {
            $resultat[] = $ligne;
            $ligne = $req->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $resultat;
}

function getTousLesUtilisateurs() {

    $resultat = array();

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select * from profil");
        $req->execute();

        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        while ($ligne) {
            $resultat[] = $ligne;
            $ligne = $req->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $resultat;
}

function getLesUtilisateursPourUnMedicament($codeM, $sec_num) {

    $resultat = array();

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select distinct medicament.nomm, visiteur.matricule, quota.quantiterepartie from visiteur inner join quota on quota.matricule=visiteur.matricule 
        inner join medicament on medicament.codem=quota.codem inner join stock on medicament.codem=stock.codem where medicament.codeM=:codeM and visiteur.sec_num = :sec_num");
        $req->bindValue(':codeM', $codeM, PDO::PARAM_INT);
        $req->bindValue(':sec_num', $sec_num, PDO::PARAM_INT);
        $req->execute();

        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        while ($ligne) {
            $resultat[] = $ligne;
            $ligne = $req->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }    
    return $resultat;
}

?>