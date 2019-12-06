<h1>Liste des médicaments</h1>

<table border=3 cellspacing=1 >
            <tr>
            <th>Nom</th>
            <th>Stock</th>
			<th>État</th>
            </tr>
<?php
foreach($medicaments as $unMedicament) 
{
	$codeMedicament = $unMedicament['codem'];
	$nomMedicament = $unMedicament['nomm'];
	$quantiteRestante = $unMedicament['quantiterestante'];
	?>
	<tr>
		<td> <a href=index.php?uc=medicaments&action=voirMedicament&code=<?php echo $codeMedicament ?> ><?php echo $nomMedicament ?></td>
		<td><?php echo $quantiteRestante ?></td>
	<?php 				 
		if ($quantiteRestante == 0)
		{			
			echo '<td><img src="./img/critique.jpg" width="30"></td>';
	 	} 
	 	else if ($quantiteRestante <= 15)
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

<a href="index?uc=medicaments&action=insertXML"><button>Initialiser les stocks</button></a>
<a href="index?uc=medicaments&action=syncYear"><button>Synchroniser les années</button></a>