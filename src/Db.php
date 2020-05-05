<?php

namespace Ibd;

/**
 * Klasa obsługująca połączenie z bazą danych MySQL.
 * 
 */
class Db
{
	/**
	 * Dane dostępowe do bazy.
	 */
	private $dbLogin = 'root';
	private $dbPassword = '';
	private $dbHost = 'localhost';
	private $dbName = 'ibd';

	/**
	 * @var \PDO
	 */
	private $pdo;
	
	public function __construct()
	{
		$this->pdo = new \PDO('mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName, $this->dbLogin, $this->dbPassword);
		$this->pdo->query("SET NAMES utf8");
	}

	/**
	 * Wykonuje podane zapytanie i zwraca wynik w postaci talicy.
	 * 
	 * @param $sql string Zapytanie SQL
	 * @param array $params Tablica z parametrami zapytania
	 * @return array|bool Tablica z danymi, false jeśl nie udało się wysłać zapytania
	 */
	public function pobierzWszystko($sql, $params = [])
	{
		$stmt = $this->pdo->prepare($sql);

		return $stmt->execute($params) ? $stmt->fetchAll() : false;
	}

	/**
	 * Pobiera rekord o podanym ID z wybranej tabeli.

	 * @param string $table
	 * @param integer $id
	 * @return array|bool
	 */
	public function pobierz($table, $id)
	{
		$sql = "SELECT * FROM $table WHERE id = :id";
		$stmt = $this->pdo->prepare($sql);
		
		return $stmt->execute([':id' => $id]) ? $stmt->fetch() : false;
	}

	/**
	 * Liczy rekordy zwrócone przez zapytanie.
	 * 
	 * @param string $sql
	 * @param array $params
	 * @return int
	 */
	public function policzRekordy($sql, $params)
	{
		$stmt = $this->pdo->prepare($sql);
		
		if (!empty($params) && is_array($params)) {
			foreach($params as $k => $v) {
				$stmt->bindParam($k, $v);
			}
		}
		$stmt->execute();
		
		return $stmt->rowCount();
	}

    /**
     * Dodaje rekord o podanych parametrach do wybranej tabeli.
     *
     * @param string $tabela
     * @param array $params
     * @return int
     */
    public function dodaj($tabela, $params)
    {
        $klucze = array_keys($params);
        $sql = "INSERT INTO $tabela (";
        $sql .= implode(', ', $klucze);
        $sql .= ") VALUES (";

        array_walk($klucze, function(&$elem, $klucz) {
            $elem = ":$elem";
        });
        $sql .= implode(', ', $klucze);
        $sql .= ")";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $this->pdo->lastInsertId();
    }

    /**
     * Usuwa rekord o podanym id z wybranej tabeli.
     *
     * @param string $tabela
     * @param int $id
     * @return bool
     */
    public function usun($tabela, $id)
    {
        $sql = "DELETE FROM $tabela WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Aktualizuje rekord w wybranej tabeli o podanym id.
     *
     * @param string $tabela
     * @param array $params
     * @param int $id
     * @return bool
     */
    public function aktualizuj($tabela, $params, $id)
    {
        $sql = "UPDATE $tabela SET ";
        foreach ($params as $k => $v) {
            $sql .= "$k = :$k, ";
        }

        $sql = substr($sql, 0, -2);
        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $params['id'] = $id;
        return $stmt->execute($params);
    }

    /**
     * Wykonuje podane zapytanie SQL z parametrami.
     *
     * @param       $sql
     * @param array $params
     * @return bool
     */
    public function wykonaj($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }
}