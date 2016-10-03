<html>
  <head>
    <meta charset="utf-8"/>
<?php
  include('lib/oci_utils.php');
  include('lib/libparse.php');
  
  /*
  ######################################################################################################
  Fonction qui parse le nom d'un coureur envoyé par l'utilisateur
  Règles : 
      - Ecrits en majuscule sans accent. ✓
      - Tirets (dont 1 double tiret), espaces isolés autorisés mais pas au début ni à la fin. ✓
      - Apostrophes autorisées. ✓
      - Les caractères autorisés sont ceux de l'alphabet français (sans ligature),✓
      - Le nom ne peut pas contenir une seule appostrophe ✓
  ######################################################################################################
  */
  function parseName($nom){
    $nom = htmlentities($nom, ENT_NOQUOTES, 'utf-8');

    $nom = removeAccent($nom);
    $nom = removeLigature($nom);
    $nom = removeSpecialChar($nom);
     
    if($nom){
      $nom = removeMultipleSpaces($nom);
      $nom = trim($nom," -");
      $nom = strtoupper($nom);
      
      if(strpos($nom,' ')){
        $nom = removeSpacesBetweenHyphen($nom);
      }
      if(checkNameSyntaxe($nom)){
        return $nom;
      }
    }
    return false;
  }
  
  /*
  ######################################################################################################
  Fonction qui parse le prenom d'un coureur envoyé par l'utilisateur
  Règles : 
    - Ecrits en minuscule 
    - Premières lettres de chaque mot en majuscule sans accent. 
    - Les tirets et espaces isolés sont autorisés mais ni au début, ni à la fin
  ######################################################################################################
  */
  function parseFirstName($fnom){
    $fnom = htmlentities($fnom, ENT_NOQUOTES, 'utf-8');
    
    $fnom = trim($fnom," -");
    $fnom = strtolower($fnom);
    $fnom = majFirstLetter($fnom);
    $fnom = trim($fnom," -");
    $fnom = removeMultipleSpaces($fnom);

    if(strpos($fnom,' ')){
      $fnom = removeSpacesBetweenHyphen($fnom);
    }

    return $fnom;
  }
  /*
  ######################################################################################################
  Fonction générale qui parse les arguments d'ajout d'un coureur envoyé par l'utilisateur
  ######################################################################################################
  */
  function parseCoureur(&$nom, &$prenom, &$annee_naissance, &$annee_prem){
    $nom = parseName($nom);
    $prenom = parseFirstName($prenom);
    if(parseDate($annee_naissance) && parseDate($annee_prem)){
      return true;
    }
    return false;
  }
  /*
  #######################################################################################################
  Fonction responsable de l'envoi des requetes d'ajout d'un coureur dans la base
  les arguments doivent être préalablement vérifier.
  #######################################################################################################
  */  
  function addCoureur($conn,$ncoureur,$code_tdf,$nom,$prenom,$annee_naissance = NULL,$annee_prem = NULL){    
    $arrayReq = array(
      "insert into tdf_coureur(n_coureur,code_tdf,nom,prenom) values(:p0,:p1,:p2,:p3)",
      "insert into tdf_coureur(n_coureur,code_tdf,nom,prenom,annee_prem) values(:p0,:p1,:p2,:p3,:p4)",
      "insert into tdf_coureur(n_coureur,code_tdf,nom,prenom,annee_naissance) values(:p0,:p1,:p2,:p3,:p4)",
      "insert into tdf_coureur (n_coureur,code_tdf,nom,prenom,annee_naissance,annee_prem) values (:p0,:p1,:p2,:p3,:p4,:p5)",
      );
    
    
    if(isset($conn) && isset($ncoureur) && isset($code_tdf) && isset($nom) && isset($prenom)){
      
      //Si année naissance et année prem n'ont pas été renseigné
      if (empty($annee_naissance) && empty($annee_prem)){ 
        $req = $arrayReq[0];
        $param = array($ncoureur,$code_tdf,$nom,$prenom);
        
      //Si juste année naissance n'a pas été renseigné  
      }elseif(empty($annee_naissance)){
        $req = $arrayReq[1];
        $param = array($ncoureur,$code_tdf,$nom,$prenom,$annee_prem);
        
      //Si juste année prem n'a pas été renseigné
      }elseif(empty($annee_prem)){
        $req = $arrayReq[2];
        $param = array($ncoureur,$code_tdf,$nom,$prenom,$annee_naissance);
        
      //Si les deux on été renseigné
      }else{
        $req = $arrayReq[3];
        $param = array($ncoureur,$code_tdf,$nom,$prenom,$annee_naissance,$annee_prem);
      }
    
      $req = prepareQuery($conn,$req);  
      for($i=0;$i<count($param);$i++){
        oci_bind_by_name($req,":p".$i, $param[$i],10);
      }
      $err = executeQuery($req);
      echo "</br></br></br></br></br></br>";
      $err = 0;
      return $err;
    }
  }
  /*
  #######################################################################################################
  Fonction retournant le numéro du prochain coureur qui sera ajouter dans la base.
  #######################################################################################################
  */
  function getMaxNumCoureur($conn){
    $req = "select max(n_coureur)+1 from tdf_coureur";
    $req = prepareQuery($conn,$req);
    
    executeQuery($req);
    $tab = array(); 
    $ncoureur = readRequestData($req,$tab,1);  
    
    return $tab[0][0]; 
  }
  /*
  #######################################################################################################
  Fonction main.
  #######################################################################################################
  */
  
  
  $conn = ociConnexion('ETU2_2','ETU2_2','info');
  $categorie = 'coureur';
  
  $code_tdf = "FRA";
  $nom = "éé''éé--uù  gg";
  $prenom = "- - - - -- --- ---- - -- - jœuœœLE^^$$@(@s beR)NA@rD -- -  0123456789 _+-.,!@#$%^&*();|<>\"'- - - - -";
  $annee_naissance = '1998';
  $annee_prem = '2015';
    
  //selectCoureur($conn);

  /*
  switch($categorie){
      
      case 'coureur':
         $ncoureur = getMaxNumCoureur($conn);
         
         addCoureur($conn,$ncoureur,$code_tdf,$nom,$prenom,NULL,NULL);
         
         commitOci($conn);
         echo "max num : ".getMaxNumCoureur($conn);
         $req = "select * from tdf_coureur where n_coureur = :p1";
         $req = prepareQuery($conn,$req);
         oci_bind_by_name($req,":p1",$ncoureur, 10);
      
    
         executeQuery($req);
         $tab = array(); 
         $ncoureur = readRequestData($req,$tab,6);  
    
         echo "ligne ajoutée :";
         print_r($tab);
     
         break;
  }*/
  
  closeConnexion($conn);
  
?>
  </head>
</html>
