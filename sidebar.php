<?php 
	use Ibd\Ksiazki;

	$ksiazki = new Ksiazki();
	$lista = $ksiazki->pobierzBestsellery();
?>

<div class="col-md-2">
	<h1>Bestsellery</h1>
	<ul>
		<?php foreach ($lista as $bestseller) : ?>
			<li onclick="location.href='ksiazki.szczegoly.php?id=<?= $bestseller['id'] ?>'">
				<img src="zdjecia/<?= $bestseller['zdjecie'] ?>" alt="<?= $bestseller['tytul'] ?>" class="img-thumbnail" />
				<p><?= $bestseller['tytul'] ?></p>
				<p> <?= $bestseller['imie'] ?> <?= $bestseller['nazwisko'] ?> </p>
			</li>
		<?php endforeach; ?>
	</ul>
</div>