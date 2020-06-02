<?php
require_once 'vendor/autoload.php';
include 'header.php';

use Ibd\Zamowienia;

$zamowienia = new Zamowienia();

$lista = $zamowienia->pobierzWszystkieDanegoUzytkownika($_SESSION['login']);
?>

<h2>Historia zamówień</h2>

<table id="historiaZamowien" class="table table-striped">
	<thead>
		<tr>
		<th>Id</th>
		<th>Status</th>
		<th>Suma</th>
		<th>Liczba produktów</th>
		<th>Liczba sztuk</th>
		<th>Data dodania</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($lista as $z): ?>
			<tr>
				<td><?=$z['id']?></td>
				<td><?=$z['status']?></td>
				<td><?=$z['suma']?></td>
				<td><?=$z['liczba_produktow']?></td>
				<td><?=$z['liczba_sztuk']?></td>
				<td><?=$z['data_dodania']?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
