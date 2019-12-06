<link rel="stylesheet" href="style.css">
<title>Gestion des stocks</title>
<link rel="icon" href="img/favicon.png">

<?php
	session_start();

	include('model/pdo.connexionBDD.inc.php');
	include('model/pdo.authentification.inc.php');
	include('view/v_footer.php');

	connexionPDO();

	if (!isLoggedOn())
	{
		include('controller/c_authentification.php');
		exit();
	}
	
	if (isset($_GET['action']))
	{
		if ($_GET['action'] == 'deconnexion')
		{
			seDeconnecter();
			header('Location: index.php');
		}
	}

	$uc = null;
	//SI C'EST UN UTILISATEUR BASIQUE
	if ($_SESSION['typeProfil'] == 'V')
	{
		$uc = 'utilisateurs';
	}
	else
	{
		if (isset($_GET['uc']))
		{
			$uc = $_GET['uc'];
		}
	}

	switch ($uc) 
	{
		case 'medicaments' :
			include('controller/c_medicaments.php');
			include('view/v_menu.php');
			break;
		case 'utilisateurs' :
			include('controller/c_utilisateurs.php');
			include('view/v_menu.php');
			break;
		case 'accueil' :
			$profil = getProfilByLogin($_SESSION['loginU'], $_SESSION['mdpU']);
			$prenom = $profil['PRENOM'];
			$nom = $profil['NOM'];
			include('view/v_accueil.php');
			break;
		default:
			header('Location: index.php?uc=accueil');
			break;
	}
?>