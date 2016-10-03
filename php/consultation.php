<?php

  include('lib/oci_utils.php');
  /*
  #######################################################################################################
  Fonction retournant le code html pour le tableau passé en args
  #######################################################################################################
  */
  function buildTable($array){
      $html = '<table>';
      $html .= '<tr>';
      foreach($array[0] as $key=>$value){
          if (!(strcmp($key, 'COMPTE_ORACLE') == 0 || strcmp($key, 'DATE_INSERT') == 0))
                $html .= '<th>' . $key . '</th>';
          }
      $html .= '</tr>';
      foreach( $array as $key=>$value){
          $html .= '<tr>';
          foreach($value as $key2=>$value2){
            if (!(strcmp($key2, 'COMPTE_ORACLE') == 0 || strcmp($key2, 'DATE_INSERT')== 0))
              if(empty($value2)) $html .= '<td> N/A </td>';
                else $html .= '<td>' . utf8_encode($value2) . '</td>';
            if (strcmp($key2,'N_COUREUR') == 0) $id = $value2;
          }
          $html.='<td><a href="details.php?categorie=1&id='.$id.'">Details</a></td>';
          $html .= '</tr>';
      }

      $html .= '</table>';
      return $html;
  }
  /*
  #######################################################################################################
  Fonction transmettant les variables post dans un tableau en fonction de la catégorie filtrée
  #######################################################################################################
  */
  function getFilters($categorie){
    $filters = array();
    switch($categorie){
      case 'coureur':
        $filters[0] = $_POST['coureurName'];
        $filters[1] = $_POST['coureurFirstName'];
        $filters[2] = $_POST['anneeNaissance'];
        $filters[3] = $_POST['anneePrem'];
        $filters[4] = $_POST['code_tdf'];
        break;

    }
    return $filters;
  }  
  /*
  #######################################################################################################
  Fonction permettant de rajouter un filtre a une requete sql
  Param : 
    - firstFilter : Boolean pour savoir si c'est le premier filtre
    - req : référence sur la requete a modifier
    - name : nom de la colonne
    - filter : valeur du filtre
    - string : Boolean si filter est un string
  #######################################################################################################
  */
  function addFilters($firstFilter, &$req, $name, $filter, $string){
    if($firstFilter) $req.="where ";
    else $req.= "and ";
    if($string) $req .= "$name = '$filter' ";
    else $req .= "$name = $filter ";

    return false;
  }
  /*
  #######################################################################################################
  Fonction reponsable de la requete pour afficher les coureurs en fonction des filtres passés en args
  #######################################################################################################
  */
  function requestCoureur($conn,$filters){
    $nom = $filters[0];
    $prenom = $filters[1];
    $annee_naissance = $filters[2];
    $annee_prem = $filters[3];
    $code_tdf = $filters[4];

    $req = "select * from tdf_coureur ";
    $firstFilter = true;

    if(isset($nom) && !empty($nom))
      $firstFilter = addFilters($firstFilter, $req, 'nom', $nom, true);
    
    if(isset($prenom) && !empty($prenom))
      $firstFilter = addFilters($firstFilter, $req, 'prenom', $prenom, true);
    
    if(isset($annee_naissance) && !empty($annee_naissance))
      $firstFilter = addFilters($firstFilter, $req, 'annee_naissance', $annee_naissance, false);
    
    if(isset($annee_prem) && !empty($annee_prem))
      $firstFilter = addFilters($firstFilter, $req, 'annee_prem', $annee_prem, false);
    
    if(isset($code_tdf) && !empty($code_tdf)) 
      $firstFilter = addFilters($firstFilter, $req, 'code_tdf', $code_tdf, true);

    $req .= " order by nom, n_coureur";

    $req = prepareQuery($conn,$req);
    executeQuery($req);
    $array = array(); 
    readRequestDataToName($req,$array);
    //readRequestData($req,$array,6); 
    return $array;
  }
  /*
  #######################################################################################################
  Fonction faisant le lien entre la catégorie et la requete a effectuer
  #######################################################################################################
  */
  function parseFilters($conn, $categorie, $filters){

    switch($categorie){
      //Si la catégorie choisie est coureur
      case 0:
        $array = requestCoureur($conn, $filters);
    }
    return $array;
  }
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
    Fonction main
    ######################################################################################################
    */  
  $conn = ociConnexion('ETU2_2','ETU2_2','info');
  $countriesInfo = getCountriesCode($conn);
  $isFilter = NULL;
  if(isset($_POST["filters"]) && !empty($_POST["filters"]))
    $isFilter = $_POST["filters"];

  if($isFilter){
    $categorie = $_POST['categorie'];
    $filters = getFilters($categorie);
    $arrayRequest = parseFilters($conn,$categorie,$filters);
  }else{
    //Si on ne filtre rien, on affiche tous les courreurs sans filtres
    $arrayRequest = requestCoureur($conn,NULL);
  }

  closeConnexion($conn); 
?>