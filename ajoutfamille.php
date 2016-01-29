<?php
include ("header.html");
include("fonctions.php");


if (isset($_POST["validation"]) && $_POST["validation"]=="enregistrer") {
	if (!isset($_POST["francais"]) && (isset($_POST["id_langue"]) && $_POST["id_langue"]!=1 )) $_POST["francais"]=0; else $_POST["francais"]=1;
	$requete = ajoutFamille($_POST["nom_famille"],$_POST["nombre_enfants"],$_POST["idlangue1"],$_POST["pays"], $_POST["francais"], $bdd);
	if (isset($_POST["retour"])) {
		modifBbsitting($_POST["idbbsitting"], "id_famille", $requete["id_insert"], $bdd);
		header('Location:'.$_POST["retour"].'.php?source=ajoutfamille&id='.$requete["idbbsitting"]);
	} else header('Location:fichebbsitting.php?id='.$requete["id_insert"]);
	// echo "<br />reponse: ";print_r($requete);
} if (isset($_POST["validation"]) && $_POST["validation"]=="modification") {
	// print_r($_POST["francais"]); echo "  ";print_r($_POST["id_langue"]); die;
	if (!isset($_POST["francais"]) && (isset($_POST["id_langue"]) && $_POST["id_langue"]!=1 )) $_POST["francais"]=0; else $_POST["francais"]=1;
	$requete = modifFamille($_POST["idbbsitting"],$_POST["idfamille"],$_POST["nom_famille"],$_POST["nombre_enfants"],$_POST["idlangue1"],$_POST["pays"], $_POST["francais"], $bdd);
	header('Location:fichebbsitting.php?id='.$requete["id_modif"]);
} else {
$pays = pays(); 
// print_r($pays);die;
$reponse = $bdd->query('select * from langue');
?>
	<?php if (isset($_POST["validation"]) && $_POST["validation"]=="modifier") {
		?>
		<h1>Modifier la famille</h1>
		<?php
	} else {
		?>
		<h1>Ajouter une famille</h1>
		<?php
	}
	?>
	

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
<?php
if (isset($_POST["validation"]) && $_POST["validation"]=="modifier") {
?>
	<input type="hidden" name="idbbsitting" value=" <?= $_POST["idbbsitting"] ?>">
	<input type="hidden" name="idfamille" value=" <?= $_POST["idfamille"] ?>">
<?php	
}
?>
<fieldset>
	<div class="control-group">
		<label class="control-label" for="nom_famille">Nom *: </label>
		<div class="controls">
			<input type = "text" name = "nom_famille" required <?php if (isset($_POST["nom_famille"])) echo "value=".$_POST["nom_famille"]; ?>>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="nombre_enfants">Nombre d'enfants *: </label> 
		<div class="controls">
			<input type="number" class="form-control-lg" name="nombre_enfants" min="0" max="3" required <?php if (isset($_POST["nb_enfants"])) echo "value=".$_POST["nb_enfants"]; ?>>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="idlangue1">Langue *: </label>
		<div class="controls">
			<select name="idlangue1" class="form-control input-lg" class="input-medium" required>
				<?php
				while ($donnees = $reponse->fetch()) {	
				?>
					<option value=<?php echo $donnees['id_langue']; ?> <?php if (isset($_POST["id_langue"]) && $_POST["id_langue"]==$donnees['id_langue']) echo "selected" ?>><?php echo $donnees['nom_langue'];?></option>
				<?php
				}
				?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<div class="col-xs-8">
			<div class="controls">
				<input type="checkbox" name="francais" <?php if (isset($_POST["francais"]) && $_POST["francais"]==1) echo "checked" ?>/><label for="francais">&nbsp;Les enfants parlent français</label><br />
			</div>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="pays">Pays d'origine *: </label>
		<div class="controls">
			<select name="pays" class="form-control input-lg" class="input-medium">
				<?php
				for($i=0;$i<sizeof($pays);$i++) {	
				?>
					<option value=<?php echo $pays[$i][3]; ?> <?php if (isset($_POST["pays"]) && $_POST["pays"]==$pays[$i][3]) echo " selected" ?>><?php echo $pays[$i][3];?></option>
				<?php
				}
				?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
		<?php if (isset($_POST["validation"]) && $_POST["validation"]=="modifier") {
			?>
			<button type="submit" class ="btn btn-primary" name ="validation" value="modification">Modifier la famille</button>
			<?php
		} else {
			?>
			<button type="submit" class ="btn btn-primary" name ="validation" value="enregistrer">Enregistrer la famille</button>
			<?php
		}
		?>
			
		</div>
	</div>
</fieldset>
</form>


<?php
}
include ("footer.html");