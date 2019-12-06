<script>
	function redirect(message)
	{
		alert(message); 
		window.location.href = 'index.php?uc=medicaments&action=voirMedicaments';
	}
</script>

<?php
	include('./model/pdo.fonctions.inc.php');
	include('./model/pdo.stock.inc.php');

	$action = null;
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
	}

	switch ($action) 
	{
		case 'voirMedicaments' :
			$medicaments = getMedicamentsParSecteur($_SESSION['secteur']);
			include('./view/v_voirTousMedicaments.php');
			break;
		case 'voirMedicament' :
			if (isset($_GET['code']))
			{
				$code = $_GET['code'];
			}
			else
			{
				header('Location: index.php?uc=medicaments&action=voirMedicaments');
			}	

			$medicament = getQuantiteRestante($code, $_SESSION['secteur']);
			if ($medicament['sec_num'] != $_SESSION['secteur'])
			{
				header('Location: index.php?uc=medicaments&action=voirMedicaments');
			}

			$medicaments = getMedicamentsParSecteur($_SESSION['secteur']);
			$maxCode = sizeof($medicaments) - 1;

			$maxCode = $medicaments[sizeof($medicaments) - 1]['codem'];
			$minCode = $medicaments[0]['codem'];

			for ($i=0; $i < sizeof($medicaments); $i++) { 
				if ($medicaments[$i]['codem'] == $code)
				{
					$index = $i;
				}
			}
			
			if ($index > 0)
			{
				$previousCode = $medicaments[$index-1]['codem'];
			}
			else
			{
				$previousCode = $medicaments[0]['codem'];
			}

			if ($index < sizeof($medicaments) - 1)
			{
				$nextCode = $medicaments[$index+1]['codem'];
			}
			else
			{
				$nextCode = $medicaments[sizeof($medicaments) - 1]['codem'];
			}

			$utilisateurs = getLesUtilisateursPourUnMedicament($code, $_SESSION['secteur']);
			include('./view/v_voirUnMedicament.php');
        	break;
        case 'modifierStock' :
        	$codeM = $_POST['code'];
			$sec_num = $_SESSION['secteur'];

        	$visiteurs = getLesUtilisateursPourUnMedicament($codeM, $_SESSION['secteur']);

        	foreach ($visiteurs as $visiteur) 
        	{
        		$matricule = $visiteur['matricule'];
        		$quantite = $_POST[$matricule];
        		$quantiteRepartie = getQuantiteRepartie($codeM, $matricule);
        		$diffQuantite = $quantite - $quantiteRepartie['quantiterepartie'];
        		$quantiteRestante = getQuantiteRestante($codeM, $sec_num);
				$quantiteRestante = $quantiteRestante['quantiterestante'] - $diffQuantite;
        		updateStock($codeM, $sec_num, $quantiteRestante);
				updateQuota($codeM, $matricule, $quantite);
        	}
			header('Location: index.php?uc=medicaments&action=voirMedicament&code='.$codeM);
			break;		
		case 'insertXML' :
			$xml = simplexml_load_file("ppe(exemple).xml") or die("Error: Cannot create object");
			$annee = $xml->annee;
			$medicaments = $xml->code;
			$modif = false;

			for ($i=0; $i < sizeOf($medicaments); $i++) { 
				$nom = $medicaments[$i]->nom;
				$quantite = $medicaments[$i]->quantite;

				if (isInStock($nom))
				{
					$medicament = getMedicamentByName($nom);
					$id = $medicament['CODEM'];

					if(sizeof(isEmptyStockAndQuota($id, $_SESSION['secteur'])) == 0)
					{						
						updateStock($id, $_SESSION['secteur'], $quantite);
						$modif = true;
					}
				}
				else
				{			
					if (!getMedicamentByName($nom))
					{
						insertMedicament($nom);
					}
					$medicament = getMedicamentByName($nom);
					$id = $medicament['CODEM'];
					insertStock($_SESSION['secteur'], $id, $annee, $quantite);	

					$utilisateurs = getUtilisateursParSecteur($_SESSION['secteur']);
					foreach ($utilisateurs as $utilisateur) 
					{
						insertQuota($utilisateur['MATRICULE'], $id, $annee, 0);	
					} 	
					$modif = true;			
				}
			}
			if ($modif)
			{
				echo "<script> redirect('Les stocks ont été mis à jour.')</script>";
			}	
			else
			{
				echo "<script> redirect('Les données ont déjà été implanté dans la base de données.')</script>";	
			}			
			break;
		case 'syncYear' :
			$quotas = getSumByYearFromQuota();
			$modif = false;
			foreach ($quotas as $quota) 
			{
				updateQuota($quota['CODEM'], $quota['MATRICULE'], $quota['somme']);
				deleteQuota($quota['CODEM'], $quota['MATRICULE']);
				$modif = true;
			}
			$stocks = getSumByYearFromStock();
			foreach ($stocks as $stock) 
			{
				updateStock($stock['CODEM'], $stock['SEC_NUM'], $stock['somme']);
				deleteStock($stock['CODEM'], $stock['SEC_NUM']);
				$modif = true;
			}

			if ($modif)
			{
				echo "<script> redirect('La base de données a été mise à jour.')</script>";
			}	
			else
			{
				echo "<script> redirect('Les années sont déjà synchronisées.')</script>";	
			}	
			break;
		default:
			header('Location: index.php?uc=medicaments&action=voirMedicaments');
			break;
	}
?>