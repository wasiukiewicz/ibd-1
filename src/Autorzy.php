<?php

namespace Ibd;

class Autorzy
{
	/**
	 * Instancja klasy obsługującej połączenie do bazy.
	 *
	 * @var Db
	 */
	private $db;

	public function __construct()
	{
		$this->db = new Db();
	}

	/**
	 * Pobiera zapytanie SELECT z autorami.
	 *
	 * @return array
	 */
	public function pobierzSelect($params = [])
	{
		$sql = "SELECT * FROM autorzy WHERE 1=1 ";

		return $sql;
	}

	/**
	 * Wykonuje podane w parametrze zapytanie SELECT.
	 * 
	 * @param string $select
	 * @return array
	 */
	public function pobierzWszystko($select)
	{
		return $this->db->pobierzWszystko($select);
	}

	/**
	 * Pobiera dane autora o podanym id.
	 * 
	 * @param int $id
	 * @return array
	 */
	public function pobierz($id)
	{
		return $this->db->pobierz('autorzy', $id);
	}

	/**
	 * Dodaje autora.
	 *
	 * @param array $dane
	 * @return int
	 */
	public function dodaj($dane)
	{
		return $this->db->dodaj('autorzy', [
			'imie' => $dane['imie'],
			'nazwisko' => $dane['nazwisko']
		]);
	}

	/**
	 * Usuwa autora.
	 * 
	 * @param int $id
	 * @return bool
	 */
	public function usun($id)
	{
		return $this->db->usun('autorzy', $id);
	}

	/**
	 * Zmienia dane autora.
	 * 
	 * @param array $dane
	 * @param int $id
	 * @return bool
	 */
	public function edytuj($dane, $id)
	{
		$update = [
			'imie' => $dane['imie'],
			'nazwisko' => $dane['nazwisko']
		];
		
		return $this->db->aktualizuj('autorzy', $update, $id);
	}

}
