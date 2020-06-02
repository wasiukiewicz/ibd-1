<?php
require_once 'vendor/autoload.php';
use Ibd\Uzytkownicy;
use Valitron\Validator;

$uzytk = new Uzytkownicy();
$v = new Validator($_POST);

if (!empty($_POST)) {
    $v->rule('required', ['imie', 'nazwisko', 'adres', 'email', 'login', 'haslo', 'grupa']);

    if ($v->validate()) {
		if ($uzytk->dodaj($_POST, $_POST['grupa'])) {
			header("Location: admin.uzytkownicy.lista.php?msg=1");
			exit();
		}
	}
}

$select = $uzytk->pobierzSelect();
$lista = $uzytk->pobierzWszystko($select);
$dane = $_POST;

include 'admin.header.php';
?>

<h2>
	Użytkownicy
	<small><a href="#" id="aDodajUzytkownika">dodaj</a></small>
</h2>

<?php include 'admin.uzytkownicy.form.php' ?>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 1): ?>
	<p class="alert alert-success">Użytkownik został dodany.</p>
<?php endif; ?>

<table id="autorzy" class="table table-striped">
	<thead>
		<tr>
			<th>Id</th>
			<th>Imię</th>
			<th>Nazwisko</th>
			<th>Login</th>
			<th>Grupa</th>
			<th>Email</th>
			<th>Telefon</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lista as $a): ?>
			<tr>
				<td><?= $a['id'] ?></td>
				<td><?= $a['imie'] ?></td>
				<td><?= $a['nazwisko'] ?></td>
				<td><?= $a['login'] ?></td>
				<td><?= $a['grupa'] ?></td>
				<td><?= (empty($a['email'])) ? '-' : $a['email'] ?></td>
				<td><?= (empty($a['telefon'])) ? '-' : $a['telefon'] ?></td>
				<td>
					<a href="admin.uzytkownicy.edycja.php?id=<?= $a['id'] ?>" title="edycja" class="aEdytujUzytkownika"><em class="fas fa-pencil-alt"></em></a>
					<a href="admin.uzytkownicy.usun.php?id=<?= $a['id'] ?>" title="usuń" class="aUsunUzytkownika"><em class="fas fa-trash"></em></a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php include 'admin.footer.php'; ?>