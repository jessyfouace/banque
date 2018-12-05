<?php

include('includes/header.php');

?>

<div class="col-12 m-0 p-0">

	<header class="flex header">
		<a href="index.php" class="margin-right">Bienvenue sur l'application Comptes Bancaires</a>
		<?php if (empty($_SESSION['name'])) {
    ?>
        <a href="login.php">Connection</a>
		<?php
} else {
        ?>
	<a href="disconnect.php">Deconnection</a>
<?php
    } ?>
	</header>

	<h1>Mon application bancaire</h1>

	<h2 class="col-12 text-center">Bonjour, <?php echo $_SESSION['name']; ?> nous sommes ravis de vous revoir</h2>

	<form class="newAccount" action="../controllers/index.php" method="post">
		<label>Sélectionner un type de compte</label>
		<select class="" name="name" required>
			<option value="">Choisissez le type de compte à ouvrir</option>
			<?php foreach ($typeAccount as $account) {
        ?>
				<option value="<?php echo $account ?>"><?php echo $account ?></option>
			<?php
    } ?>
		</select>
		<input type="submit" name="new" value="Ouvrir un nouveau compte">
	</form>

	<hr>
	<h3 class="changecolor"><?php echo $message; ?></h3>
	<div class="main-content flex">

	<!-- Pour chaque compte enregistré en base de données, il faudra générer le code ci-dessous -->

	<?php foreach ($accounts as $account) {
        ?>

		<div class="card-container">

			<div class="card">
				<h3><strong><?php echo $account->getName(); ?></strong></h3>
				<div class="card-content">

					<?php if ($account->getBalance() < 0) {
            ?>
					<h3>/!\ COMPTE EN NEGATIF /!\</h3>
					<?php
        } ?>
					<p>Somme disponible : <?php echo $account->getBalance(); ?> €</p>

					<!-- Formulaire pour dépot/retrait -->
					<h4>Dépot / Retrait</h4>
					<form action="index.php" method="post">
						<input type="hidden" name="id" value=" <?php echo $account->getId(); ?>"  required>
						<label>Entrer une somme à débiter/créditer</label>
						<input type="number" name="balance" placeholder="Ex: 250" required>
						<input type="submit" name="payment" value="Créditer">
						<input type="submit" name="debit" value="Débiter">
					</form>


					<!-- Formulaire pour virement -->
			 		<form action="index.php" method="post">

						<h4>Transfert</h4>
						<label>Entrer une somme à transférer</label>
						<input type="number" name="balance" placeholder="Ex: 300"  required>
						<input type="hidden" name="idDebit" value="<?php echo $account->getId(); ?>" required>
						<label for="">Sélectionner un compte pour le virement</label>
						<select name="idPayment" required>
							<option value="" disabled>Choisir un compte</option>
							<?php foreach ($accounts as $accountInformation) {
            if ($accountInformation->getName() !== $account->getName()) {
                ?>
										<option value="<?php echo $accountInformation->getId(); ?>"><?php echo $accountInformation->getName(); ?></option>
								<?php
            } ?>
							<?php
        } ?>
						</select>
						<input type="submit" name="transfer" value="Transférer l'argent">
					</form>

					<!-- Formulaire pour suppression -->
			 		<form class="delete" action="index.php" method="post">
				 		<input type="hidden" name="id" value="<?php echo $account->getId(); ?>"  required>
				 		<input type="submit" name="delete" value="Supprimer le compte">
			 		</form>

				</div>
			</div>
		</div>

	<?php
    } ?>

	</div>

</div>

<?php

include('includes/footer.php');

 ?>
