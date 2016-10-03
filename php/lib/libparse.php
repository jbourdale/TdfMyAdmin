<?php  

  /*
  ######################################################################################################
  Fonction vérifiant que le nom passé en paramètre vérifie les règles suivantes :
     - Ne contient pas qu'une seule appostrophe
     - a completer /!\
  ######################################################################################################
  */  
  function checkNameSyntaxe($name){
    if ($name[0] == "'" && strlen($name) == 1){
      echo "/!\Appostrophe seule /!\ ";
      return false;
    }
    return true;
  }
  
  /*
  ######################################################################################################
  Fonction responsable de mettre en majuscule les premières lettres de chaque mot dans un prenom
  ######################################################################################################
  */  
  function majFirstLetter($str){
    
    //On vire les accents sur les premières lettres des mots
    //On mets tout en minuscule avec mc_strtolower($str, 'utf-8');
    //On fait des mots avec uc_words

    $str = preg_replace('#^&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#-&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '-\1', $str);
    $str = preg_replace('#\ &([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', ' \1', $str);
    $str = preg_replace('#\'&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\'\1', $str);

    $str = preg_replace('#Ŭ|ŭ#','U',$str);
    echo "str apres ŭ : $str</br>";

    $str = ucwords(mb_strtolower($str,"utf-8"),"[ -']");

    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);

    $str = html_entity_decode($str);
    if(preg_match('#0|1|2|3|4|5|6|7|8|9|_|\+|\.|,|!|@|\#|\$|%|€|\^|\*|\(|\)|\\|\/|\||<|>|\"|\'\'#',$str)){
      return false;
    }


    echo "str fin majFirstLetter : $str</br>";
    return $str;
  }
  
  /*
  ######################################################################################################
  Fonction supprimant les accents de la chaine de caractère passé en argument
  ######################################################################################################
  */
  function removeAccent($str, $encoding='utf-8'){
    $str = preg_replace('#Ŭ#','U',$str);
    return preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);
  }
  /*
  ######################################################################################################
  Fonction supprimant les ligatures et les autres caractères spéciaux commençant par & 
  de la chaine de caractère passé en argument
  ######################################################################################################
  */
  function removeLigature($str){
    //echo "str input : $str";
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
    //echo "str output : $str";
    if (preg_match('#&[^;]+;#',$str)){
        return false;
    }
    return $str;
    //return preg_replace('#&[^;]+;#', '\1', $str);
  }
  /*
  ######################################################################################################
  Fonction supprimant les redondances d'espace dans la chaine de caractère passé en argument
  ######################################################################################################
  */
  function removeMultipleSpaces($str,$encoding='utf-8'){
    return preg_replace('/\s\s+/', ' ', $str);
  }    
  /*
  ######################################################################################################
  Fonction supprimant les caractères spéciaux suivant : 0123456789 _+-.,!@#$%^&*();\/|<>"' dans
  la chaine de caractère passée en argument
  ######################################################################################################
  */
  function removeSpecialChar($str, $encoding='utf-8'){
    if(preg_match('#0|1|2|3|4|5|6|7|8|9|_|\+|\.|,|!|@|\#|\$|%|€|\^|&|\*|\(|\)|;|\\|\/|\||<|>|\"|\'\'#',$str)){
      return false;
    }
    return $str;
  }  
  /*
  ######################################################################################################
  Fonction qui verifie si l'annee passer en parametre est valide
  ######################################################################################################
  */
  function parseDate($d){
    return checkdate(1,1,$d);
  }
  /*
  ######################################################################################################
  Fonction qui enleve les espaces entre deux mots si ils sont séparés par un tiret
  ######################################################################################################
  */    
  function removeSpacesBetweenHyphen($str){
  $str = explode(" ",$str);
  $tab = array();
  foreach($str as $mot) array_push($tab, trim($mot," "));
  
  $name = "";
  for($i=0;$i<count($tab)-1;$i++){          
    if($tab[$i+1] == "-"){
    $name .= $tab[$i];
    }else{
    if($tab[$i] == "-") $name .= $tab[$i];  
    else $name.= $tab[$i]." ";
    }
  }
  $name.=$tab[count($tab)-1];
  return $name;  
  }
?>
