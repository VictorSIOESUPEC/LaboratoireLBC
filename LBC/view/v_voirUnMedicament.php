<body>
	<form action="index.php?uc=medicaments&action=modifierStock" method="POST" onsubmit="return confirm('Êtes-vous sûr de valider les changements ?')">
		<h1><?php echo $medicament['nomm'] ?></h1>
		<h3>Stock: <?php echo $medicament['quantiterestante'] ?></h3>
		<table>
			<tr>
				<th>Id utilisateur</th>
				<th>Quantité répartie</th>
				<th><i>Modification</i></th>
				<th><i>Quantité maximum</i></th>
				<th>État</th>
			</tr>
	<?php
			foreach ($utilisateurs as $utilisateur) 
			{
	?>
			<tr>
				<td><label><?php echo $utilisateur['matricule']?></label></td>
				<td><label><?php echo $utilisateur['quantiterepartie']?></label></td>
				<td><input class="quantiteI" type="number" name="<?php echo $utilisateur['matricule']?>" value="<?php echo $utilisateur['quantiterepartie']?>" min=0
				onKeyUp="updateMaxQuantite(this)" onclick="console.log(this.max)"></td>
				<td><input class="quantiteI2" type="number" disabled></td>
			<?php 				 
				if ($utilisateur['quantiterepartie'] == 0)
				{			
					echo '<td><img src="./img/critique.jpg" width="30"></td>';
			 	} 
			 	else if ($utilisateur['quantiterepartie'] <= 15)
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
		<input type="hidden" name="code" value="<?php echo $code ?>">
		<button>VALIDER</button>
	</form>

	<a id="previousA" class="arrow" href="index.php?uc=medicaments&action=voirMedicament&code=<?php echo $previousCode ?>">&#10094;</a>
	<a id="nextA" class="arrow" href="index.php?uc=medicaments&action=voirMedicament&code=<?php echo $nextCode ?>">&#10095;</a>
</body>

<script type="text/javascript">
	var previousA = document.getElementById("previousA");
	var nextA = document.getElementById("nextA");
	var code = <?php echo $code ?>;
	var maxCode = <?php echo $maxCode ?>;
	var minCode = <?php echo $minCode ?>;

	if (code == minCode)
	{
		previousA.style.cursor = "not-allowed";
		previousA.style.pointerEvents = "none";
	}
	else if (code == maxCode)
	{
		nextA.style.cursor = "not-allowed";
		nextA.style.pointerEvents = "none";
	}

	var quantiterestante = <?php echo $medicament['quantiterestante'] ?>;
	var quantiteI = document.getElementsByClassName('quantiteI');
	var quantiteI2 = document.getElementsByClassName('quantiteI2');
	var quantiteActuelle = 0;
	var quantiteTotale = 0;

	for (var i = 0; i < quantiteI.length; i++) 
	{
		quantiteActuelle += parseFloat(quantiteI[i].value);
	}

	quantiteTotale = parseFloat(quantiterestante) + quantiteActuelle;

	for (var i = 0; i < quantiteI.length; i++) 
	{
		quantiteI[i].max = parseFloat(quantiteI[i].defaultValue) + parseFloat(quantiterestante);
		quantiteI2[i].value = quantiteI[i].max;
	}

	function updateMaxQuantite(input)
	{
		if (input.value == "")
		{
			input.value = 0;
		}
		quantiteActuelle = 0;
		for (var i = 0; i < quantiteI.length; i++) 
		{
			quantiteActuelle += parseFloat(quantiteI[i].value);			
		}

		for (var i = 0; i < quantiteI.length; i++) 
		{			
			quantiteI[i].max = parseFloat(quantiteTotale) - parseFloat(quantiteActuelle) + parseFloat(quantiteI[i].value);	
			quantiteI2[i].value = quantiteI[i].max;		
		}

		if (parseFloat(input.value) > parseFloat(input.max))
		{
			input.value = input.max;
			updateMaxQuantite(input);
		}
	}
</script>