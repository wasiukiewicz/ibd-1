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


	/**
     * Pobiera stronę z danymi autorów.
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
     * Pobiera zapytanie SELECT oraz jego parametry;
     *
     * @param $params
     * @return array
     */
    public function pobierzZapytanie($params)
    {
        $parametry = [];
        $sql = " SELECT author.*, 
				 CASE WHEN ks.id IS NOT NULL
				 THEN count(author.id)
				 ELSE '0'
				 END AS ilosc
				 FROM autorzy author
				 LEFT JOIN ksiazki ks ON ks.id_autora = author.id
                 WHERE 1=1
		 ";

        // dodawanie warunków do zapytanie
        if (!empty($params['fraza'])) {
            $sql .= "AND ( author.imie LIKE :fraza
                     OR author.nazwisko LIKE :fraza )
            ";
            $parametry['fraza'] = "%$params[fraza]%";
        }

		$sql .= " group by author.id ";

        // dodawanie sortowania
        if (!empty($params['sortowanie'])) {
            $kolumny = ['author.nazwisko'];
            $kierunki = ['ASC', 'DESC'];
            [$kolumna, $kierunek] = explode(' ', $params['sortowanie']);

            if (in_array($kolumna, $kolumny) && in_array($kierunek, $kierunki)) {
                $sql .= " ORDER BY " . $params['sortowanie'];
            }
        }
		
        return ['sql' => $sql, 'parametry' => $parametry];
	}
	
	public function czyMaKsiazki($authorId){
		$sql = " SELECT author.*
				 FROM autorzy author
				 JOIN ksiazki ks ON ks.id_autora = author.id
				 WHERE author.id = '$authorId'
		 ";
		 $wynikZapytania = $this->db->pobierzWszystko($sql);
		 if($wynikZapytania!=null){
			 return true;
		 }
		 return false;
	}

}
