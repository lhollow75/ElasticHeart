<?php
include ("header.html");
include("fonctions.php");


if (isset($_POST["validation"]) && $_POST["validation"]=="enregistrer"){
	if (!isset($_POST["repas"])) $_POST["repas"]=0; else $_POST["repas"]=1;
	if (!isset($_POST["taxis"])) $_POST["taxis"]=0; else $_POST["taxis"]=1;
	if (!isset($_POST["taxis_paye"])) $_POST["taxis_paye"]=0; else $_POST["taxis_paye"]=1;
	$requete = ajoutBbsitting($_POST["date"],$_POST["id_hotel"],$_POST["id_famille"],$_POST["nb_heure"],$_POST["repas"],$_POST["taxis"],$_POST["pourboire"],$_POST["commentaire"],$_POST["id_taux"], $_POST["taxis_paye"],$bdd);
	if ($requete["erreur"] == 0) {
		if (isOK($requete["id_insert"], 'id_hotel', $bdd)==false) {
			header('Location:ajouthotel.php?retour=ajoutbbsitting&id='.$requete["id_insert"]);
			// afficheRetour($requete);
		} else if (isOK($requete['id_insert'], 'id_famille', $bdd)==false) {
			header('Location:ajoutfamille.php?retour=ajoutbbsitting&id='.$requete["id_insert"]);	
			// afficheRetour($requete);
		}
	} else {
		afficheRetour($requete);
	}
} else if (isset($_GET["source"]) && $_GET["source"]=="ajouthotel" && isset($_GET["id"])){
	if (isOK($_GET["id"], 'id_famille', $bdd)==false) {
		header('Location:ajoutfamille.php?retour=ajoutbbsitting&id='.$_GET["id"]);	
		// afficheRetour($requete);
	}
} else if (isset($_GET["source"]) && $_GET["source"]=="ajoutfamille" && isset($_GET["id"])){
	header('Location:fichebbsitting.php?id='.$_GET["id"]);
} if (isset($_POST["validation"]) && $_POST["validation"]=="modification") {
	// print_r($_POST["francais"]); echo "  ";print_r($_POST["id_langue"]); die;
	$requete = modifBabysitting($_POST["idbbsitting"],$_POST["idfamille"],$_POST["nom_famille"],$_POST["nombre_enfants"],$_POST["idlangue1"],$_POST["pays"], $_POST["francais"], $bdd);
	header('Location:fichebbsitting.php?id='.$requete["id_modif"]);
} else {

$hotel_bdd = $bdd->query('select * from hotel');
$famille_bdd = $bdd->query('select id_famille, nom_famille from famille');
$taux_bdd = $bdd->query('select * from taux_horaire');

?>
	<h1>Ajouter un babysitting</h1>

      </header>

<div class="bs-example" >	
<form method="post" action="#" class="form-horizontal">
	<div class="row">	
		<div class="col-xs-4">
			<div class="control-group">
				<label class="control-label" for="date">Date: </label>
				<div class="controls">
					<input type = "date" name = "date"/>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="control-group">
				<label class="control-label" for="id_hotel">Hotel: </label>
				<div class="controls">
					<select name="id_hotel" class="form-control input-lg" class="input-medium">
						<?php
						while ($donnees = $hotel_bdd->fetch()) {	
						?>
							<option value=<?php echo $donnees['id_hotel']; ?>><?php echo $donnees['nom'];?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="control-group">
				<label class="control-label" for="id_famille">Famille: </label>
				<div class="controls">
					<select name="id_famille" class="form-control input-lg" class="input-medium">
						<?php
						while ($donnees = $famille_bdd->fetch()) {	
						?>
							<option value=<?php echo $donnees['id_famille']; ?>><?php echo $donnees['nom_famille'];?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-8">
			<div class="control-group">
				<label class="control-label" for="nb_heure">Nombre d'heures travaillées: </label> 
				<div class="controls">
					<input type="number" class="form-control-lg" name="nb_heure" min="0" max="24"/>h
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-8">
			<div class="control-group">
				<label class="control-label" for="id_taux">Taux horaire facturé à la famille: </label>
				<div class="controls">
					<select name="id_taux" class="form-control input-lg" class="input-medium">
						<?php
						while ($donnees = $taux_bdd->fetch()) {	
						?>
							<option value=<?php echo $donnees['id_taux']; ?>><?php echo $donnees['description'];?> - <?php echo $donnees['taux'];?>€/h</option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-8">
			<div class="control-group">
				<div class="controls">
					<input type="checkbox" name="repas" /><label for="repas">&nbsp;Repas à l'hôtel</label><br />
				</div>
				<div class="controls">
					<input type="checkbox" name="taxis" /><label for="taxis">&nbsp;Taxis payé</label><br />
				</div>
				<div class="controls">
					<input type="checkbox" name="taxis_paye" /><label for="taxis_paye">&nbsp;Taxis facturé non payé</label><br />
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-8">
			<div class="control-group">
				<label class="control-label" for="pourboire">Pourboire: </label>
				<div class="controls">
					<input type="number" class="form-control-lg" name="pourboire" min="0" step="5"/>€
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="control-group">
				<label class="control-label" for="commentaire">Commentaire: </label>
				<div class="controls">
					<textarea name="commentaire" rows="2" cols="20"></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="control-group">
				<div class="controls">
					<button type="submit" class ="btn btn-primary" name ="validation" value="enregistrer">Enregistrer le babysitting</button>
				</div>
			</div>
		</div>
	</div>

</form>
</div>


<?php
}
include ("footer.html");