<nav>
<?php
if ($_SESSION['typeProfil']=='S'){
?>
	<li><a href="index.php?uc=medicaments&action=voirMedicaments">Médicaments</a></li>
	<li><a href="index.php?uc=utilisateurs&action=voirUtilisateurs">Utilisateurs</a></li>
	<li><a href="index.php?action=deconnexion">Déconnexion</a></li>
<?php
} else {
?>
	<li><a href="index.php?action=deconnexion">Déconnexion</a></li>
<?php
}
?>
</nav>