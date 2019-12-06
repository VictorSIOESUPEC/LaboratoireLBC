<?php

include_once 'pdo.connexionBDD.inc.php';

function updateQuota($codeM, $matricule, $quantiteRepartie) {

	try {
		$cnx = connexionPDO();
        $req = $cnx->prepare("update quota set quantiterepartie=:quantiterepartie where matricule=:matricule and codem=:codem");
        $req->bindValue(':quantiterepartie', $quantiteRepartie, PDO::PARAM_INT);
        $req->bindValue(':matricule', $matricule, PDO::PARAM_INT);
        $req->bindValue(':codem', $codeM, PDO::PARAM_INT);
        $req->execute();

    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $req;
}

function updateStock($codeM, $sec_num, $quantiteRestante) {

	try {
		$cnx = connexionPDO();
        $req = $cnx->prepare("update stock set quantiterestante=:quantiterestante where sec_num=:sec_num and codem=:codem");
        $req->bindValue(':quantiterestante', $quantiteRestante, PDO::PARAM_INT);
        $req->bindValue(':sec_num', $sec_num, PDO::PARAM_INT);
        $req->bindValue(':codem', $codeM, PDO::PARAM_INT);
        $req->execute();

    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $req;
}

function insertQuota($matriucle, $id, $annee, $quantite) {
    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("INSERT INTO `quota` (`MATRICULE`, `CODEM`, `ANNEE`, `QUANTITEREPARTIE`) VALUES (:matriucle, :id, :annee, :quantite)");
        $req->bindValue(':matriucle', $matriucle, PDO::PARAM_INT);
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':annee', $annee, PDO::PARAM_INT);
        $req->bindValue(':quantite', $quantite, PDO::PARAM_INT);
        $req->execute();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $req;
}

function insertStock($sec_num, $id, $annee, $quantite) {
    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("INSERT INTO `stock` (`SEC_NUM`, `CODEM`, `ANNEE`, `QUANTITERESTANTE`) VALUES (:sec_num, :id, :annee, :quantite)");
        $req->bindValue(':sec_num', $sec_num, PDO::PARAM_INT);
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':annee', $annee, PDO::PARAM_INT);
        $req->bindValue(':quantite', $quantite, PDO::PARAM_INT);
        $req->execute();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $req;
}

function getQuantiteRestante($codeM, $sec_num) {

    $resultat = array();

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select stock.sec_num, medicament.nomm, stock.quantiterestante from stock inner join medicament on medicament.codem=stock.codem where medicament.codem=:codeM and stock.sec_num=:sec_num");
        $req->bindValue(':codeM', $codeM, PDO::PARAM_INT);
        $req->bindValue(':sec_num', $sec_num, PDO::PARAM_INT);
        $req->execute();

        $ligne = $req->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $ligne;   
}


function getQuantiteRepartie($codeM, $matricule) {

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select medicament.nomm, quota.quantiterepartie from quota inner join medicament on medicament.codem=quota.codem where quota.codem=:codeM and quota.matricule=:matricule");
        $req->bindValue(':codeM', $codeM, PDO::PARAM_INT);
        $req->bindValue(':matricule', $matricule, PDO::PARAM_INT);
        $req->execute();

        $ligne = $req->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $ligne;   
}

function getSumByYearFromQuota() {
    $resultat = array();

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("SELECT quota.*, SUM(quota.QUANTITEREPARTIE) as 'somme' from quota, (SELECT * FROM quota) t WHERE quota.MATRICULE = t.MATRICULE AND  quota.CODEM = t.CODEM AND quota.ANNEE <> t.ANNEE GROUP BY quota.MATRICULE, quota.CODEM");
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

function getSumByYearFromStock() {
    $resultat = array();

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("SELECT stock.*, SUM(stock.QUANTITERESTANTE) as 'somme' from stock, (SELECT * FROM stock) t WHERE stock.SEC_NUM = t.SEC_NUM AND  stock.CODEM = t.CODEM AND stock.ANNEE <> t.ANNEE GROUP BY stock.SEC_NUM, stock.CODEM");
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

function deleteQuota($codeM, $matricule) {
    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("DELETE FROM quota WHERE CODEM=:codeM AND MATRICULE=:matricule AND ANNEE <> YEAR(CURDATE())");
        $req->bindValue(':codeM', $codeM, PDO::PARAM_INT);
        $req->bindValue(':matricule', $matricule, PDO::PARAM_INT);
        $req->execute();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $req; 
}

function deleteStock($codeM, $sec_num) {
    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("DELETE FROM stock WHERE CODEM=:codeM AND SEC_NUM=:sec_num AND ANNEE <> YEAR(CURDATE())");
        $req->bindValue(':codeM', $codeM, PDO::PARAM_INT);
        $req->bindValue(':sec_num', $sec_num, PDO::PARAM_INT);
        $req->execute();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $req; 
}

function isEmptyStockAndQuota($codeM, $sec_num) {
    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("SELECT * FROM stock, quota where stock.CODEM = quota.CODEM and (quota.QUANTITEREPARTIE != 0 
        OR stock.QUANTITERESTANTE != 0) AND stock.CODEM = :codeM AND stock.SEC_NUM = :sec_num");
        $req->bindValue(':codeM', $codeM, PDO::PARAM_INT);
        $req->bindValue(':sec_num', $sec_num, PDO::PARAM_INT);
        $req->execute();
        $res = $req -> fetchAll();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $res;     
}

function isInStock($nomM) {
    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("SELECT * FROM stock where stock.CODEM = (SELECT CODEM FROM MEDICAMENT WHERE NOMM = :nomM)");
        $req->bindValue(':nomM', $nomM, PDO::PARAM_STR);
        $req->execute();
        $res = $req -> fetchAll();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $res;     
}
?>