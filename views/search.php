<!DOCTYPE html>

<?php
	include("../php/consultation.php");	
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>TdfMyAdmin - Search</title>
	</head>
	<body>
		<header>
			HEADER
		</header>

		<section id="filters">

			<form id="formFilters" method="post" action="search.php"/>
				<input type="hidden" name="filters" value="true"/>
				
				<label for="categorie">Type : </label>
				<select id="categorie" name="categorie">
					<option value="coureur" selected>Coureur</option>
				</select>
				
				<label for="coureurName">Nom : </label>
				<input type="text" id="coureurName" name="coureurName" placeholder="Nom"/>

				<label for="coureurFirstName">Prenom : </label>
				<input type="text" id="coureurFirstName" name="coureurFirstName" placeholder="Prenom"/>

				<label for="anneeNaissance">Annee de naissance : </label>
				<select id="anneeNaissance" name="anneeNaissance">
					<option value=""></option>
					<?php
						for($i=1900;$i<2017;$i++){
							echo "<option value='".$i."'>".$i."</option>";
						}
					?>
				</select>
				
				<label for="anneePrem">Annee premiere participation : </label>
				<select id="anneePrem" name='anneePrem'>
					<option value=""></option>
					<?php
						for($i=1900;$i<2017;$i++){
							echo "<option value='".$i."'>".$i."</option>";
						}
					?>
				</select>

				<label for="codeTdf">Pays : </label>
				<select id="codeTdf" name='code_tdf'>
					<option value=""></option>
					<?php
						for($i=0;$i<count($countriesInfo);$i++){
							echo "<option value='".$countriesInfo[$i][0]."'>".$countriesInfo[$i][1]."</option>";
						}
					?>
				</select>
				<input type="submit" value="Valider"/>
			</form>
		</section>

		<section id="resultArray">

			<table>
				<?php
					echo buildTable($arrayRequest);
				?>
			</table>
		</section>
	</body>
</html>















