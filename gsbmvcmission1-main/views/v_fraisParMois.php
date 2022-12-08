<div class="encadre">

	<form action="index.php?uc=etatFrais&action=saisieFraisMois" method="post">

		<h1> Période </h1>

		<label for="lstMois" accesskey="n">Mois/Année : </label>
		<select id="lstMois" name="lstMois">
			<?php
			foreach ($lesMois as $unMois) {
				$mois = $unMois['mois'];
				$numAnnee =  $unMois['numAnnee'];
				$numMois =  $unMois['numMois'];
				if ($mois == $moisASelectionner) {
			?>
					<option selected value="<?php echo $mois ?>"><?php echo  $numMois . "/" . $numAnnee ?> </option>
				<?php
				} else { ?>
					<option value="<?php echo $mois ?>"><?php echo  $numMois . "/" . $numAnnee ?> </option>
			<?php
				}
			}

			?>
		</Select>

		<label for="tpFrais" accesskey="n">Type de frais : </label>
		<select id="tpFrais" name="tpFrais">
			<?php foreach ($lesFraisHF as $frais) { ?>
				<?php $leFrais = $frais['id']; ?>
				<option value="<?php echo $leFrais ?>"><?php echo $leFrais ?></option>
			<?php  }  ?>
		</Select>
		<input type="submit" value="Valider">

		<br><br>

		

</div>