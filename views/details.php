<?php
  
  include('lib/oci_utils.php');
  /*
  #######################################################################################################
  Fonction retournant le numéro du prochain coureur qui sera ajouter dans la base.
  #######################################################################################################
  */
  function getCountriesCode($conn){
    $req = "select code_tdf, nom from tdf_pays order by nom";
    $req = prepareQuery($conn,$req);
    executeQuery($req);
    $array = array(); 
    readRequestData($req,$array,2);  
    return $array;
  }
  /*
  ######################################################################################################
    Fonction retournant le html a afficher
  ######################################################################################################
  */
  function getDetails($categorie, $coureur, $countriesInfo){
    switch ($categorie){
		case 1:
			include("../views/templates/detailsCoureur.php");
			break;
	}
  }
  /*
  ######################################################################################################
    Fonction retournant un tableau contenant les infos du coureur
  ######################################################################################################
  */  
  function getCoureur($conn, $id){
  
    $req = "select * from tdf_coureur where n_coureur = '".$id."'";
    $req = prepareQuery($conn,$req);
    
    executeQuery($req);
    $array = array(); 
    
    readRequestDataToName($req,$array);
    return $array;
  }
  /*
  ######################################################################################################
    Fonction main
  ######################################################################################################
  */  
  $id = NULL;
  $categorie = NULL;
  if(isset($_GET["id"]) && !empty($_GET["id"])) $id = $_GET["id"];
  else exit();
  if(isset($_GET["categorie"]) && !empty($_GET["categorie"])) $categorie = $_GET["categorie"];
  else exit();
  $conn = ociConnexion('ETU2_2','ETU2_2','info');
  echo "<script> var categorie = $categorie </script>";
  switch($categorie){
    case 1:
      $coureur = getCoureur($conn, $id);
	  $countriesInfo = getCountriesCode($conn);
      //$html = getHtml($coureur);
	  break;
  }
?>