<?php

namespace Ibd;

class Ksiazki
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
     * Pobiera wszystkie książki.
     *
     * @return array
     */
    public function pobierzWszystkie()
    {
        $sql = "SELECT k.* FROM ksiazki k";

        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera zapytanie SELECT oraz jego parametry;
     *
     * @param $params
     * @return array
     */
    public function pobierzZapytanie($params)
    {
        $parametry = [];
        $sql = " SELECT k.*, author.imie, author.nazwisko, category.nazwa
				 FROM ksiazki k 
				 JOIN autorzy author ON author.id = k.id_autora
				 JOIN kategorie category ON category.id = k.id_kategorii
                 WHERE 1=1
		 ";

        // dodawanie warunków do zapytanie
        if (!empty($params['fraza'])) {
            $sql .= "AND ( k.tytul LIKE :fraza 
                     OR k.opis LIKE :fraza
                     OR author.imie LIKE :fraza
                     OR author.nazwisko LIKE :fraza )
            ";
            $parametry['fraza'] = "%$params[fraza]%";
        }
        if (!empty($params['id_kategorii'])) {
            $sql .= "AND k.id_kategorii = :id_kategorii ";
            $parametry['id_kategorii'] = $params['id_kategorii'];
        }

        // dodawanie sortowania
        if (!empty($params['sortowanie'])) {
            $kolumny = ['k.tytul', 'k.cena', 'author.nazwisko'];
            $kierunki = ['ASC', 'DESC'];
            [$kolumna, $kierunek] = explode(' ', $params['sortowanie']);

            if (in_array($kolumna, $kolumny) && in_array($kierunek, $kierunki)) {
                $sql .= " ORDER BY " . $params['sortowanie'];
            }
        }

        return ['sql' => $sql, 'parametry' => $parametry];
    }

    /**
     * Pobiera stronę z danymi książek.
     *
     * @param string $select
     * @param array $params
     * @return array
     */
    public function pobierzStrone($select, $params)
    {
        return $this->db->pobierzWszystko($select, $params);
    }

    /**
     * Pobiera dane książki o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierz($id)
    {
        return $this->db->pobierz('ksiazki', $id);
    }

    public function pobierzBestsellery()
	{
		$sql = "SELECT book.id, book.tytul, book.zdjecie, author.imie, author.nazwisko FROM ksiazki book
				JOIN autorzy author ON author.id = book.id_autora
				ORDER BY RAND() LIMIT 5";

		return $this->db->pobierzWszystko($sql);
	}

	/**
	 * Pobiera wszystkie książki, z imieniem i nazwiskiem autora oraz kategorią.
	 *
	 * @return array
	 */
	public function pobierzWszystieZKategoriaIAutorem(){
		$sql = " SELECT book.*, author.imie, author.nazwisko, category.nazwa
				 FROM ksiazki book 
				 JOIN autorzy author ON author.id = book.id_autora
				 JOIN kategorie category ON category.id = book.id_kategorii
		 ";

		return $this->db->pobierzWszystko($sql);
	}


}