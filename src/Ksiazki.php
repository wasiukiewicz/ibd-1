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
        $sql = "
			SELECT k.*, CONCAT(a.imie, ' ', a.nazwisko) AS autor, kat.nazwa AS kategoria
			FROM ksiazki k 
			JOIN autorzy a ON k.id_autora = a.id
			JOIN kategorie kat ON k.id_kategorii = kat.id
			";

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

		return $this->db->wywolajZapytanieSql($sql);
	}


    /**
     * Dodaje książkę do bazy.
     *
     * @param array $dane
     * @param array $pliki Dane wgrywanego pliku z okładką
     * @return int
     */
    public function dodaj($dane, $pliki)
    {
        $id = $this->db->dodaj('ksiazki', [
            'id_autora' => $dane['id_autora'],
            'id_kategorii' => $dane['id_kategorii'],
            'tytul' => $dane['tytul'],
            'opis' => $dane['opis'],
            'cena' => $dane['cena'],
            'liczba_stron' => $dane['liczba_stron'],
            'isbn' => $dane['isbn']
        ]);

        $rozszerzenie = strtolower(pathinfo($pliki['zdjecie']['name'], PATHINFO_EXTENSION));

        if (!empty($pliki['zdjecie']['name']) && $rozszerzenie == 'jpg') {
            // zostal wybrany plik ze zdjeciem do uploadu
            if($this->wgrajPlik($pliki, $id)) {
                $this->db->aktualizuj('ksiazki', ['zdjecie' => "$id.jpg"], $id);
            }
        }

        return $id;
    }

    /**
     * Wgrywa plik ze zdjęciem na serwer.
     *
     * @param array $pliki
     * @param int $idKsiazki
     * @return bool
     */
    public function wgrajPlik($pliki, $idKsiazki)
    {
        $nazwa = $idKsiazki . "_org.jpg";

        if ($a=move_uploaded_file($pliki['zdjecie']['tmp_name'], "zdjecia/$nazwa")) {
            $this->stworzMiniature($nazwa, $idKsiazki);
            return true;
        }

        return false;
    }

    /**
     * Tworzy miniaturę wgrywanego zdjęcia.
     *
     * @param string $nazwa
     * @param int $idKsiazki
     * @param int $szerokosc
     */
    public function stworzMiniature($nazwa, $idKsiazki, $szerokosc = 100)
    {
        $img = imagecreatefromjpeg("zdjecia/$nazwa");
        $width = imagesx($img);
        $height = imagesy($img);
        $newWidth = $szerokosc;
        $newHeight = floor($height * ( $szerokosc / $width ));

        $tmpImg = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresized($tmpImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagejpeg($tmpImg, "zdjecia/$idKsiazki.jpg");
    }

    /**
     * Zmienia dane książki.
     *
     * @param array $dane
     * @param int   $id
     * @param       $pliki
     * @return bool
     */
    public function edytuj($dane, $id, $pliki)
    {
        $update = [
            'id_autora' => $dane['id_autora'],
            'id_kategorii' => $dane['id_kategorii'],
            'tytul' => $dane['tytul'],
            'opis' => $dane['opis'],
            'cena' => $dane['cena'],
            'liczba_stron' => $dane['liczba_stron'],
            'isbn' => $dane['isbn']
        ];

        $rozszerzenie = strtolower(pathinfo($pliki['zdjecie']['name'], PATHINFO_EXTENSION));

        if (!empty($pliki['zdjecie']['name']) && $rozszerzenie == 'jpg') {
            // zostal wybrany plik ze zdjeciem do uploadu
            if ($this->wgrajPlik($pliki, $id)) {
                $update['zdjecie'] = "$id.jpg";
            }
        }

        return $this->db->aktualizuj('ksiazki', $update, $id);
    }

    /**
     * Usuwa książkę.
     *
     * @param int $id
     * @return bool
     */
    public function usun($id)
    {
        if(file_exists("zdjecia/$id.jpg")) {
            unlink("zdjecia/$id.jpg");
        }
        if (file_exists("zdjecia/" . $id . "_org.jpg")) {
            unlink("zdjecia/" . $id . "_org.jpg");
        }

        return $this->db->usun('ksiazki', $id);
    }
}