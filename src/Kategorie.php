<?php

namespace Ibd;

class Kategorie
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
     * Pobiera wszystkie kategorie.
     *
     * @return array
     */
    public function pobierzWszystkie(): array
    {
        $sql = "SELECT * FROM kategorie";

        return $this->db->pobierzWszystko($sql);
    }

    /**
	 * Dodaje kategorie.
	 *
	 * @param array $dane
	 * @return int
	 */
	public function dodaj($dane)
	{
		return $this->db->dodaj('kategorie', [
			'nazwa' => $dane['nazwa']
		]);
    }
    
    /**
	 * Usuwa kategorie.
	 * 
	 * @param int $id
	 * @return bool
	 */
	public function usun($id)
	{
		return $this->db->usun('kategorie', $id);
    }
    
    /**
	 * Zmienia dane kategorii.
	 * 
	 * @param array $dane
	 * @param int $id
	 * @return bool
	 */
	public function edytuj($dane, $id)
	{
		$update = [
			'nazwa' => $dane['nazwa']
		];
		
		return $this->db->aktualizuj('kategorie', $update, $id);
    }
    
    	/**
	 * Pobiera dane kategorii o podanym id.
	 * 
	 * @param int $id
	 * @return array
	 */
	public function pobierz($id)
	{
		return $this->db->pobierz('kategorie', $id);
	}

}
