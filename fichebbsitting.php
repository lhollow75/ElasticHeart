<?php
include ("header.html");
include("fonctions.php");

if (isset($_GET["id"]) && verifIdFamille($_GET["id"], $bdd)==true) $idbbsitting=$_GET["id"];
	else header('Location:listbbsitting.php');

$bbsitting=infobbsitting($idbbsitting,$bdd);
// print_r($_GET["id"]); echo "    "; print_r($idbbsitting);
// var_dump($bbsitting);
?>

<h1>Votre babysitting</h1>
</header>

<div class="container">

  <div class="row">
    <div class="col-md-4">
      <h3>La famille</h3>
		<p>Nom: <?=$bbsitting["nom_famille"]?><br />
			Nombre d'enfants: <?=$bbsitting["nombre_enfants"]?><br />
			Langue parlée: <?=$bbsitting["nom_langue"]?><br />
			Français parlé: <?php if ($bbsitting["francais"]==1) echo "Oui"; else echo "Non";?><br />
			Pays d'origine: <?=$bbsitting["pays"]?><br />
			<?php if ($bbsitting["commentaire"]=="") $bbsitting["commentaire"]="Aucun"?>
			Votre commentaire: <?=$bbsitting["commentaire"]?><br />
		</p>
		<form method="post" action="ajoutfamille.php" class="form-horizontal">
			<input type="hidden" name="modif" value=1>
			<input type="hidden" name="idbbsitting" value="<?=$_GET["id"] ?>">
			<input type="hidden" name="idfamille" value="<?=$bbsitting["id_famille"] ?>">
			<input type="hidden" name="nom_famille" value="<?=$bbsitting["nom_famille"] ?>">
			<input type="hidden" name="nb_enfants" value="<?=$bbsitting["nombre_enfants"] ?>">
			<input type="hidden" name="id_langue" value="<?=$bbsitting["id_langue_1"] ?>">
			<input type="hidden" name="francais" value="<?=$bbsitting["francais"] ?>">
			<input type="hidden" name="pays" value="<?=$bbsitting["pays"] ?>">
		<button type="submit" class ="btn btn-primary" name ="validation" value="modifier">Modifier la famille</button>
    </div>
    <div class="col-md-4">
      <h3>La garde</h3>
	  <p>Nombre d'heures: <?=$bbsitting["nb_heure"]?></p>
		<form method="post" action="ajoutbbsitting.php" class="form-horizontal">
			<input type="hidden" name="modif" value=1>
			<input type="hidden" name="idbbsitting" value="<?=$_GET["id"] ?>">
			<input type="hidden" name="idfamille" value="<?=$bbsitting["id_famille"] ?>">
			<input type="hidden" name="nom_famille" value="<?=$bbsitting["nom_famille"] ?>">
			<input type="hidden" name="nb_enfants" value="<?=$bbsitting["nombre_enfants"] ?>">
			<input type="hidden" name="id_langue" value="<?=$bbsitting["id_langue_1"] ?>">
			<input type="hidden" name="francais" value="<?=$bbsitting["francais"] ?>">
			<input type="hidden" name="pays" value="<?=$bbsitting["pays"] ?>">
		<button type="submit" class ="btn btn-primary" name ="validation" value="modifier">Modifier la garde</button>
	</div>
    <div class="col-md-4">
      <h3>L'hôtel</h3>
	  <p>Nom: <?=$bbsitting["nom"]?></p>
    </div>
  </div>
</div>

</body>
</html>
