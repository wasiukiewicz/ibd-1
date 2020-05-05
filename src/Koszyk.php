<?php

namespace Ibd;

class Koszyk
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
	 * Pobiera dane książek w koszyku.
	 *
	 * @return array
	 */
	public function pobierzWszystkie($idSesji)
	{
		$sql = "
			SELECT ks.*, ko.liczba_sztuk, ko.id AS id_koszyka
			FROM ksiazki ks JOIN koszyk ko ON ks.id = ko.id_ksiazki
			WHERE ko.id_sesji = '$idSesji'
			ORDER BY ko.data_dodania DESC";

		return $this->db->pobierzWszystko($sql);
	}

	/**
	 * Dodaje książkę do koszyka.
	 *
	 * @param int $idKsiazki
	 * @param string $idSesji
	 * @return int
	 */
	public function dodaj($idKsiazki, $idSesji)
	{
		$dane = [
			'id_ksiazki' => $idKsiazki,
			'id_sesji' => $idSesji
		];

		return $this->db->dodaj('koszyk', $dane);
	}

	/**
	 * Sprawdza, czy podana książka znajduje się w koszyku.
	 *
	 * @param int $idKsiazki
	 * @param string $idSesji
	 * @return bool
	 */
	public function czyIstnieje($idKsiazki, $idSesji)
	{
		$sql = "SELECT * FROM koszyk WHERE id_sesji = '$idSesji' AND id_ksiazki = :id_ksiazki";
		$ile = $this->db->policzRekordy($sql, [':id_ksiazki' => $idKsiazki]);
		
		return $ile > 0;
	}

	/**
	 * Zmienia (usuwa) ilości sztuk książek w koszyku.
	 *
	 * @param array $dane Tablica z danymi (klucz to id rekordu w koszyku, wartość to liczba sztuk)
	 */
	public function zmienLiczbeSztuk($dane)
	{
		foreach($dane as $idKoszyka => $ilosc) {
			if ($ilosc <= 0) {
				$this->db->usun('koszyk', $idKoszyka);
            } else {
				$this->db->aktualizuj('koszyk', ['liczba_sztuk' => $ilosc], $idKoszyka);
            }
		}
	}

    /**
     * Czyści koszyk.
     *
     * @param string $idSesji
     * @return bool
     */
    public function wyczysc($idSesji)
    {
        return $this->db->wykonaj("DELETE FROM koszyk WHERE id_sesji = :id_sesji", ['id_sesji' => $idSesji]);
    }
}
