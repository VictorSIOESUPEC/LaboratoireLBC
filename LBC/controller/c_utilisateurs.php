<?php
	include('./model/pdo.fonctions.inc.php');
	include('./model/pdo.stock.inc.php');

	//SI C'EST UN UTILISATEUR BASIQUE
	if ($_SESSION['typeProfil'] == 'V')
	{
		$action = 'voirUtilisateur';
	}
	else
	{
		$action = null;
		if (isset($_GET['action']))
		{
			$action = $_GET['action'];
		}
	}

	switch ($action) 
	{
		case 'voirUtilisateurs' :
			$utilisateurs = getUtilisateursParSecteur($_SESSION['secteur']);
			include('./view/v_voirTousUtilisateurs.php');
			break;
		case 'voirUtilisateur' :
			//SI C'EST UN UTILISATEUR BASIQUE
			if ($_SESSION['typeProfil'] == 'V')
			{
				$mat = $_SESSION['valeur'];
			}
			else
			{
				if (isset($_GET['mat']))
				{
					$mat = $_GET['mat'];
				}
			}
			$visiteur = getUnUtilisateur($mat);
			if ($visiteur['SEC_NUM'] != $_SESSION['secteur'])
			{
				header('Location: index.php?uc=utilisateurs&action=voirUtilisateurs');
			}
			$visiteurs = getUtilisateursParSecteur($_SESSION['secteur']);

			$maxMat = $visiteurs[sizeof($visiteurs) - 1]['MATRICULE'];
			$minMat = $visiteurs[0]['MATRICULE'];

			for ($i=0; $i < sizeof($visiteurs); $i++) { 
				if ($visiteurs[$i]['MATRICULE'] == $mat)
				{
					$index = $i;
				}
			}
			
			if ($index > 0)
			{
				$previousMat = $visiteurs[$index-1]['MATRICULE'];
			}
			else
			{
				$previousMat = $visiteurs[0]['MATRICULE'];
			}

			if ($index < sizeof($visiteurs) - 1)
			{
				$nextMat = $visiteurs[$index+1]['MATRICULE'];
			}
			else
			{
				$nextMat = $visiteurs[sizeof($visiteurs) - 1]['MATRICULE'];
			}

			$medicaments = getLesMedicamentsPourUnUtilisateur($mat, $_SESSION['secteur']);
			include('./view/v_voirUnUtilisateur.php');
        	break;
        case 'modifierStock' :
        	$matricule = $_POST['matricule'];
        	$sec_num = $_SESSION['secteur'];

        	$medicaments = getLesMedicamentsPourUnUtilisateur($matricule, $_SESSION['secteur']);
        	foreach ($medicaments as $medicament) 
        	{
        		$codeM = $medicament['codem'];
        		$quantite = $_POST[$codeM];
        		$quantiteRepartie = getQuantiteRepartie($codeM, $matricule);
        		$diffQuantite = $quantite - $quantiteRepartie['quantiterepartie'];
        		$quantiteRestante = getQuantiteRestante($codeM, $sec_num);
        		$quantiteRestante = $quantiteRestante['quantiterestante'] - $diffQuantite;
        		updateStock($codeM, $sec_num, $quantiteRestante);
				updateQuota($codeM, $matricule, $quantite);
        	}
        	header('Location:index.php?uc=utilisateurs&action=voirUtilisateur&mat='.$matricule);
        	break;	
		default:
			header('Location: index.php?uc=utilisateurs&action=voirUtilisateurs');
			break;
	}
?>