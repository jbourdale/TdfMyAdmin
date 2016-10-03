<?php
// E.Porcq  fonc_oracle.php  12/10/2009 

//---------------------------------------------------------------------------------------------
function ociConnexion($session,$mdp,$instance)
{
  $conn = oci_connect($session, $mdp,$instance,"WE8ISO8859P15");
  //utf "AL32UTF8";
  if (!$conn){ //si pas de connexion retourne une erreur  
    $e = oci_error();
    exit;
  }
  return $conn;
}
//---------------------------------------------------------------------------------------------
function prepareQuery($conn,$req)
{
  $cur = oci_parse($conn, $req);
  
  if (!$cur){  
    $e = oci_error($conn);  
    print htmlentities($e['message']);  
    exit;
  }
  return $cur;
}
//---------------------------------------------------------------------------------------------
function executeQuery($cur)
{
  $r = oci_execute($cur, OCI_DEFAULT);
  if (!$r) {  
    $e = oci_error($cur);  
    echo htmlentities($e['message']);  
    exit;
  }
  return $r;
}
//---------------------------------------------------------------------------------------------
function readRequestDataToName($cur,&$tab)
  {
    $nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW); //OCI_FETCHSTATEMENT_BY_ROW, OCI_ASSOC, OCI_NUM
    return $nbLignes;
  }
//---------------------------------------------------------------------------------------------
function readRequestData($cur,&$tab,$nbcolumns){
  $nbLignes = 0;
  $i=0;
  while ($row = oci_fetch_array ($cur, OCI_BOTH  )) 
  { 
    for($i=0;$i<$nbcolumns;$i++){
      if(isset($row[$i])){
        $tab[$nbLignes][$i] = $row[$i];
        }
    }
  $nbLignes++;
  }
  return $nbLignes;
}
  
//---------------------------------------------------------------------------------------------
function closeConnexion($conn)
{
  oci_close($conn);
}
//---------------------------------------------------------------------------------------------
function commitOci($conn){
  $r = oci_commit($conn);
  if (!$r) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message']), E_USER_ERROR);
  }
}
//---------------------------------------------------------------------------------------------
function selectCoureur($conn){

  $req = "select * from tdf_coureur order by n_coureur desc";
  $req2 = prepareQuery($conn,$req);

  $donnees = executeQuery($req2);
  $tab = array(); 
  $donnees = readRequestData($req2,$tab,3);
 
  echo "<PRE>";
  print_r($tab);
  echo "</PRE>";
    
}

/**$login = 'ETU2_2';
$mdp = 'ETU2_2';
$instance = 'info';
$conn = ociConnexion($login,$mdp,$instance);

$req = "select * from tdf_coureur where n_coureur = 1";
$req2 = prepareQuery($conn,$req);

$donnees = executeQuery($req2);
$tab = array(); 
$donnees = LireDonnees1($req2,$tab);
 
echo "<PRE>";
print_r($tab);
echo "</PRE>";
closeConnexion($conn);
 */
 ?>