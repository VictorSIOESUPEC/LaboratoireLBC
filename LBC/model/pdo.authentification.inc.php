<?php

include_once "pdo.connexionBDD.inc.php";



function getProfilByLogin($loginU, $mdpU) {

	 try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select * from profil where login=:loginU and mdp=:mdpU");
        $req->bindValue(':loginU', $loginU, PDO::PARAM_STR);
        $req->bindValue(':mdpU', $mdpU, PDO::PARAM_STR);
        $req->execute();

        $resultat = $req->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $resultat;
}







function seConnecter($loginU, $mdpU, $typeProfil, $valeur, $secteur) {
    if (!isset($_SESSION)) {
        session_start();
    }
   	
    $_SESSION["loginU"] = $loginU;
    $_SESSION["mdpU"] = $mdpU;
    $_SESSION["valeur"] = $valeur;
    $_SESSION["typeProfil"] = $typeProfil;     
    $_SESSION["secteur"] = $secteur;     
}


function seDeconnecter() {
    if (!isset($_SESSION)) {
        session_start();
    }
    unset($_SESSION["loginU"]);
    unset($_SESSION["mdpU"]);
    unset($_SESSION["valeur"]);
    unset($_SESSION["typeProfil"]);
    unset($_SESSION["secteur"]);
  

}







function isLoggedOn() {
    if (!isset($_SESSION)) {
        session_start();
    }
    $ret = false;

    if (isset($_SESSION["loginU"])) {
        $util = getProfilByLogin($_SESSION["loginU"], $_SESSION["mdpU"]);
        if ($util["LOGIN"] == $_SESSION["loginU"] && $util["MDP"] == $_SESSION["mdpU"]
        ) {
            $ret = true;
        }
    }
    return $ret;
}








function getVisiteurByMatricule($matricule) {

    try {
        $cnx = connexionPDO();
        $req = $cnx->prepare("select visiteur.matricule, profil.nom, profil.prenom, visiteur.sec_num from visiteur, profil where profil.valeur=visiteur.matricule and visiteur.matricule=:matricule and profil.typeprofil='V'");
        $req->bindValue(':matricule', $matricule, PDO::PARAM_INT);
        $req->execute();

        $ligne = $req->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
    return $ligne; 
}














?>