<?php
require_once 'vendor/autoload.php';
include 'admin.header.php';

use Ibd\Ksiazki;
use Ibd\Kategorie;
use Ibd\Autorzy;
use Valitron\Validator;

$ksiazki = new Ksiazki();
$v = new Validator($_POST);
$dane = $_POST;

if (!empty($_POST)) {
    $v->rule('required', ['tytul', 'id_kategorii', 'id_autora', 'cena', 'isbn', 'opis']);

    if ($v->validate()) {
        if ($ksiazki->dodaj($_POST, $_FILES)) {
            header("Location: admin.ksiazki.lista.php");
            exit();
        }
    }
}

$lista = $ksiazki->pobierzWszystkie();

// pobieranie kategorii
$kategorie = new Kategorie();
$listaKategorii = $kategorie->pobierzWszystkie();

$autorzy = new Autorzy();
$listaAutorow = $autorzy->pobierzWszystko("SELECT * FROM autorzy");
?>

<h2>
	Książki
	<small><a href="#" id="aDodajKsiazke">dodaj</a></small>
</h2>

<?php include 'admin.ksiazki.form.php' ?>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 1) : ?>
	<p class="alert alert-success">Książka została dodana.</p>
<?php endif; ?>

<table id="ksiazki" class="table table-striped table-condensed">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Id</th>
			<th>Tytuł</th>
			<th>Autor</th>
			<th>Kategoria</th>
			<th>Cena PLN</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lista as $ks) : ?>
			<tr>
                <td style="width: 100px">
                    <?php if(!empty($ks['zdjecie'])): ?>
                        <img src="zdjecia/<?= $ks['zdjecie'] ?>" alt="<?= $ks['tytul'] ?>" class="img-thumbnail" />
                    <?php else: ?>
                        brak zdjęcia
                    <?php endif; ?>
                </td>
				<td><?= $ks['id'] ?></td>
				<td><?= $ks['tytul'] ?></td>
				<td><?= $ks['autor'] ?></td>
				<td><?= $ks['kategoria'] ?></td>
				<td><?= $ks['cena'] ?></td>
				<td>
					<a href="admin.ksiazki.edycja.php?id=<?= $ks['id'] ?>" title="edycja" class="aEdytujKsiazke"><em class="fas fa-pencil-alt"></em></a>
					<a href="admin.ksiazki.usun.php?id=<?= $ks['id'] ?>" title="usuń" class="aUsunKsiazke"><em class="fas fa-trash"></em></a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php include 'admin.footer.php'; ?>