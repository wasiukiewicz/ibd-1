<?php

require_once 'vendor/autoload.php';

use Ibd\Kategorie;

$kategorie = new Kategorie();

if (!empty($_POST)) {
    $kategorie = new Kategorie();
    if ($kategorie->dodaj($_POST)) {
        header("Location: admin.kategorie.lista.php?msg=1");
    }
}

$lista = $kategorie->pobierzWszystkie();

include 'admin.header.php';
?>

<h2>
    Kategorie
    <small><a href="#" id="aDodajKategorie">dodaj</a></small>
</h2>

<form method="post" action="" id="fDodajKategorie" class="form-inline mb-3">
    <input type="text" placeholder="Nazwa" name="nazwa" class="form-control mr-1" />
    <button type="submit" class="btn btn-primary">Dodaj</button>
</form>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
    <p class="alert alert-success">Kategoria została dodana.</p>
<?php endif; ?>

<table id="kategorie" class="table table-striped">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nazwa</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= $a['nazwa'] ?></td>
                <td>
                    <a href="admin.kategorie.edycja.php?id=<?= $a['id'] ?>" title="edycja" class="aEdytujKategorie"><em class="fas fa-pencil-alt"></em></a>
                    <a href="admin.kategorie.usun.php?id=<?= $a['id'] ?>" title="usuń" class="aUsunKategorie"><em class="fas fa-trash"></em></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'admin.footer.php'; ?>
