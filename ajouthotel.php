<?php
include ("header.html");
include("fonctions.php");


if (isset($_POST["validation"]) && $_POST["validation"]=="enregistrer") {
	$requete = ajoutHotel($_POST["nom_hotel"],$_POST["adresse_hotel"],$_POST["cp_hotel"],$_POST["classement"], $bdd);
	if (isset($_POST["retour"])) {
		$retour = modifBbsitting($_POST["idbbsitting"], "id_hotel", $requete["id_insert"], $bdd);
		// print_r($retour);die;
		header('Location:'.$_POST["retour"].'.php?source=ajouthotel&id='.$requete["idbbsitting"]);
	} else {
		echo "ok";
	}
	// echo "<br />reponse: ";print_r($requete);
} else {
$reponse = $bdd->query('select * from classement');
?>
	<h1>Ajouter un hôtel</h1>

      </header>

	
<form method="post" action="#" class="form-horizontal">
<?php
if (isset($_GET["retour"]) && isset($_GET["id"])) {
?>
	<input type="hidden" name="retour" value=" <?= $_GET["retour"] ?>">
	<input type="hidden" name="idbbsitting" value=" <?= $_GET["id"] ?>">
<?php	
}
?>
<fieldset>
	<div class="control-group">
		<label class="control-label" for="nom_hotel">Nom: </label>
		<div class="controls">
			<input type = "text" name = "nom_hotel" required />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="adresse_hotel">Adresse: </label>
		<div class="controls">
			<input type="text" name="adresse_hotel" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="cp_hotel">Code Postal: </label>
		<div class="controls">
			<input type="text" name="cp_hotel" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="classement">Classement: </label>
		<div class="controls">
			<select name="classement" class="form-control input-lg" class="input-medium">
				<?php
				while ($donnees = $reponse->fetch()) {	
				?>
					<option value=<?php echo $donnees['id_classement']; ?>><?php echo $donnees['description'];?></option>
				<?php
				}
				?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<button type="submit" class ="btn btn-primary" name ="validation" value="enregistrer">Enregistrer l'hôtel</button>
		</div>
	</div>
</fieldset>
</form>


<?php
}
include ("footer.html");