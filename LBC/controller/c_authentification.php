<?php
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
	}
	else
	{
		$action = 'connexion';
	}

	switch ($action) 
	{
		case 'connexion' :
			include('view/v_connexion.php');
			break;
		case 'confirmConnexion' :			
			$login = $_POST['login'];
        	$mdp = $_POST['mdp'];
        	$connexion = getProfilByLogin($login, $mdp);

        	if ($connexion != false)
        	{
                if (sizeof($connexion) > 0)
                {
                    $typeProfil = $connexion['TYPEPROFIL'];         
                    $valeur = $connexion['VALEUR']; 
                    if ($typeProfil == 'V')
                    {
                        $secteur = getVisiteurByMatricule($valeur);
                        $secteur = $secteur['sec_num'];
                    }
                    else
                    {
                        $secteur = $valeur;
                    }
                    seConnecter($login, $mdp, $typeProfil, $valeur, $secteur);
                    header('Location: index.php');  
                }          	
        	}
			include('view/v_connexion.php');
			echo "Aucun utilisateur n'a été trouvé.";        	
        	break;
	}
?>