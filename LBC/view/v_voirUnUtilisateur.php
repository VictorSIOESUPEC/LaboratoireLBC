<form action="index.php?uc=utilisateurs&action=modifierStock" method="POST" onsubmit="return confirm('Êtes-vous sûr de valider les changements ?')">
	<h1><?php echo $visiteur['NOM']." ".$visiteur['PRENOM'] ?></h1>
	<table>
		<tr>
			<th>Libellé médicament</th>
			<?php if ($_SESSION['typeProfil'] == 'S'){?><th>Quantité en stock</th><?php } ?>
			<th>Quantité répartie</th>
			<?php if ($_SESSION['typeProfil'] == 'S'){?>
			<th><i>Modification</i></th>
			<th><i>Quantité maximum</i></th>
			<th>État</th>
			<?php } ?>
		</tr>
<?php
		foreach ($medicaments as $medicament) 
	    {
?>
		<tr>
			<td><label><?php echo $medicament['nomm']?></label></td>
			<?php if ($_SESSION['typeProfil'] == 'S'){?><td><label><?php echo $medicament['quantiterestante']?></label></td>
			<td><label><?php echo $medicament['quantiterepartie']?></label></td>
			<td><input type="number" name="<?php echo $medicament['codem']?>" value="<?php echo $medicament['quantiterepartie']?>" 
			max=<?php echo $medicament['quantiterestante']+$medicament['quantiterepartie'] ?> min=0 onKeyUp="updateQuantite(this)"></td>
			<td><input type="number" value="<?php echo $medicament['quantiterepartie'] + $medicament['quantiterestante'] ?>" disabled></td>
			<?php } else { ?>
				<td><input type="number" value="<?php echo $medicament['quantiterepartie']?>" disabled></td>
			<?php 
				} 
				if ($medicament['quantiterepartie'] == 0)
				{			
					echo '<td><img src="./img/critique.jpg" width="30"></td>';
			 	} 
			 	else if ($medicament['quantiterepartie'] < 15)
				{			
					echo '<td><img src="./img/attention.png" width="30"></td>';
			 	} 
			 	else
			 	{
			 		echo '<td></td>';
			 	}
			?>
		</tr>

<?php
		}
?>
	</table>
	<input type="hidden" name="matricule" value="<?php echo $mat ?>">
	<button>VALIDER</button>
</form>
<?php 
	if ($_SESSION['typeProfil'] == 'S')
	{
?>
	<a id="previousA" class="arrow" href="index.php?uc=utilisateurs&action=voirUtilisateur&mat=<?php echo $previousMat ?>">&#10094;</a>
	<a id="nextA" class="arrow" href="index.php?uc=utilisateurs&action=voirUtilisateur&mat=<?php echo $nextMat ?>">&#10095;</a>
<?php
	}
?>

<script type="text/javascript">
		
	window.onload=function()   
	{   
		var previousA = document.getElementById("previousA");
		var nextA = document.getElementById("nextA");
		var mat = <?php echo $mat ?>;
		var maxMat = <?php echo $maxMat ?>;
		var minMat = <?php echo $minMat ?>;

		if (mat == minMat)
		{
			previousA.style.cursor = "not-allowed";
			previousA.style.pointerEvents = "none";
		}
		else if (mat == maxMat)
		{
			nextA.style.cursor = "not-allowed";
			nextA.style.pointerEvents = "none";
		}
	}	

	function updateQuantite(input)
	{
		if (input.value < 0)
		{
			input.value = 0;
		}
		else if (parseFloat(input.value) > input.max)
		{
			input.value = input.max;
		}
	}
</script>