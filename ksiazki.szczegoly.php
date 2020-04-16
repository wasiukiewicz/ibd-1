<?php

// jesli nie podano parametru id, przekieruj do listy książek
if(empty($_GET['id'])) {
    header("Location: ksiazki.lista.php");
    exit();
}

$id = (int)$_GET['id'];

include 'header.php';

use Ibd\Ksiazki;

$ksiazki = new Ksiazki();
$dane = $ksiazki->pobierz($id);
?>

<p>
	<a href="ksiazki.lista.php"><i class="fas fa-chevron-left"></i> Powrót</a>
</p>

<div id="midSection">
    <span style="float:left">
        <h1><?= $dane['tytul'] ?></h1><br>
        <p style="font-size: 24px">Cena: <?= $dane['cena'] ?></p>
    </span>
    <span style="float:right" >
        <?php if (!empty($dane['zdjecie'])) : ?>
			<img width="200px" src="zdjecia/<?= $dane['zdjecie'] ?>" alt="<?= $dane['tytul'] ?>" class="img-rounded" />
		<?php else : ?>
			brak zdjęcia
		<?php endif; ?>
    </span>
    <div style="clear: both"></div>
    <div>
        <p>
            <span style="float:left" > Liczba Stron: <?= $dane['liczba_stron'] ?>   </span>
            <span style="float:right" > Isbn: <?= $dane['isbn'] ?>  </span> <br>
        </p>
        <p>
            <span style="float:left" > Id autora: <?= $dane['id_autora'] ?>  </span>
            <span style="float:right" > Id Kategorii: <?= $dane['id_kategorii'] ?>  </span> <br>
        </p>
    </div>
    <p style="font-size: 14px">
        <?= $dane['opis'] ?><br>
    </p>
    
</div>
</p>

<?php include 'footer.php'; ?>