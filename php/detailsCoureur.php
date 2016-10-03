    <form method="POST" action="details.php" id="formDetails">
		<label>NOM : </label>
      <input type="text" name="nom" id="name" value="<?php echo utf8_encode($coureur[0]['NOM']) ?>" disabled/></br></br>
		<label>PRENOM : </label>
	  <input type="text" name="prenom" id="firstname" value="<?php echo utf8_encode($coureur[0]['PRENOM']) ?>" disabled/></br></br>
      
	  <label>ANNEE_NAISSANCE : </label>
      <select id="anneeNaissance" name='anneeNaissance' disabled>
          <option value=""></option>
          <?php
            for($i=1900;$i<2017;$i++){
            
              echo "<option value='".$i."'";
              if ($i == $coureur[0]['ANNEE_NAISSANCE']) echo " selected ";
              echo  ">".$i."</option>";
            }
          ?>
      </select>
      </br></br>
	  
	  <label>ANNEE_PREM : </label>
      <select id="anneePrem" name='anneePrem' disabled>
          <option value=""></option>
          <?php
            for($i=1900;$i<2017;$i++){
            
              echo "<option value='".$i."'";
              if ($i == $coureur[0]['ANNEE_PREM']) echo " selected ";
              echo  ">".$i."</option>";
            }
          ?>
      </select>
	  </br></br>
      
	  <label>PAYS : </label>
      <select id="codeTdf" name='code_tdf' disabled>
          <option value=""></option>
          <?php
            for($i=0;$i<count($countriesInfo);$i++){
              echo "<option value='".$countriesInfo[$i][0]."'";
              if (strcmp($countriesInfo[$i][0],$coureur[0]['CODE_TDF']) == 0){
				  echo " selected ";
			  }
				  echo ">".$countriesInfo[$i][1]."</option>";
            }
          ?>
        </select></br></br>
		
		<input type="button" id="btnModifier" value="Modifier"/>
		
    </form>