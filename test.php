<?php

include ("connexion.php");

$nom = $_POST["nom_hotel"];
$adresse = $_POST["adresse_hotel"];
$cp = $_POST["cp_hotel"];
$classement = $_POST["classement"];

$reponse = $bdd->prepare('INSERT INTO hotel(nom, adresse, cp, classement) VALUES(:nom, :adresse, :cp, :classement)');
$reponse->execute(array('nom' => $nom,
		'adresse' => $adresse,
		'cp' => $cp,
		'classement' => $classement
		));

echo"ok";



?>