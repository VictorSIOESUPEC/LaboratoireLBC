<h1>Liste des utilisateurs</h1>

<table>
    <tr>
        <th>Nom :</th>
        <th>Prenom:</th> 
    </tr> 

<?php
foreach($utilisateurs as $utilisateur)
{
    $matricule = $utilisateur['MATRICULE'];
    $nom = $utilisateur['NOM'];
    $prenom = $utilisateur['PRENOM'];
    $num_sec = $utilisateur['SEC_NUM'];         
    ?>
    <tr>
        <td width=30><a href=index.php?uc=utilisateurs&action=voirUtilisateur&mat=<?php echo $matricule ?>><?php echo $nom ?></a></td>
        <td width=150><?php echo $prenom ?></td>
    </tr>
    <?php 
}
?>
</table>