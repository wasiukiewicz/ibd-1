<?php

require_once 'vendor/autoload.php';

use Ibd\Autorzy;

$autorzy = new Autorzy();

if (!empty($_POST)) {
    $autorzy = new Autorzy();
    if ($autorzy->dodaj($_POST)) {
        header("Location: admin.autorzy.lista.php?msg=1");
    }
}

$select = $autorzy->pobierzSelect();
$lista = $autorzy->pobierzWszystko($select);

include 'admin.header.php';
?>

<h2>
    Autorzy
    <small><a href="#" id="aDodajAutora">dodaj</a></small>
</h2>

<form method="post" action="" id="fDodajAutora" class="form-inline mb-3">
    <input type="text" placeholder="Imię" name="imie" class="form-control mr-1" />
    <input type="text" placeholder="Nazwisko" name="nazwisko" class="form-control mr-1" />
    <button type="submit" class="btn btn-primary">Dodaj</button>
</form>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
    <p class="alert alert-success">Autor został dodany.</p>
<?php endif; ?>

<table id="autorzy" class="table table-striped">
    <thead>
        <tr>
            <th>Id</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= $a['imie'] ?></td>
                <td><?= $a['nazwisko'] ?></td>
                <td>
                    <a href="admin.autorzy.edycja.php?id=<?= $a['id'] ?>" title="edycja" class="aEdytujAutora"><em class="fas fa-pencil-alt"></em></a>
                    <a href="admin.autorzy.usun.php?id=<?= $a['id'] ?>" title="usuń" class="aUsunAutora"><em class="fas fa-trash"></em></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'admin.footer.php'; ?>