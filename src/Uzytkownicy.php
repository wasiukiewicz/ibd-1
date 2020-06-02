<?php

namespace Ibd;

class Uzytkownicy
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
     * Dodaje użytkownika do bazy.
     * 
     * @param array $dane
     * @param string $grupa
     * @return int
     */
    public function dodaj($dane, $grupa = 'użytkownik')
    {
        return $this->db->dodaj('uzytkownicy', [
            'imie' => $dane['imie'],
            'nazwisko' => $dane['nazwisko'],
            'adres' => $dane['adres'],
            'telefon' => $dane['telefon'],
            'email' => $dane['email'],
            'login' => $dane['login'],
            'haslo' => password_hash($dane['haslo'], PASSWORD_DEFAULT),
            'grupa' => $grupa
        ]);
    }

    /**
     * Loguje użytkownika do systemu. Zapisuje dane o autoryzacji do sesji.
     *
     * @param string $login
     * @param string $haslo
     * @param string $grupa
     * @return bool
     */
    public function zaloguj($login, $haslo, $grupa)
    {
        $dane = $this->db->pobierzWszystko(
            "SELECT * FROM uzytkownicy WHERE login = :login AND grupa = '$grupa'", ['login' => $login]
        );

        if ($dane && password_verify($haslo, $dane[0]['haslo'])) {
            $_SESSION['id_uzytkownika'] = $dane[0]['id'];
            $_SESSION['grupa'] = $dane[0]['grupa'];
            $_SESSION['login'] = $dane[0]['login'];

            return true;
        }

        return false;
    }

    /**
     * Sprawdza, czy jest zalogowany użytkownik.
     *
     * @param string $grupa
     * @return bool
     */
    public function sprawdzLogowanie($grupa = 'administrator')
    {
        if (!empty($_SESSION['id_uzytkownika']) && !empty($_SESSION['grupa']) && $_SESSION['grupa'] == $grupa)
            return true;

        return false;
    }

    /**
     * Pobiera zapytanie SELECT z użytkownikami.
     *
     * @param array $params
     * @return string
     */
    public function pobierzSelect($params = [])
    {
        $sql = "SELECT * FROM uzytkownicy WHERE 1=1 ";

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
     * Usuwa użytkownika.
     *
     * @param int $id
     * @return bool
     */
    public function usun($id)
    {
        return $this->db->usun('uzytkownicy', $id);
    }

    /**
     * Pobiera dane użytkownika o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierz($id)
    {
        return $this->db->pobierz('uzytkownicy', $id);
    }

    /**
     * Zmienia dane użytkownika.
     *
     * @param array $dane
     * @param int $id
     * @return bool
     */
    public function edytuj($dane, $id)
    {
        $update = [
            'imie' => $dane['imie'],
            'nazwisko' => $dane['nazwisko'],
            'adres' => $dane['adres'],
            'telefon' => $dane['telefon'],
            'email' => $dane['email'],
            'grupa' => $dane['grupa']
        ];

        if (!empty($dane['haslo'])) {
            $update['haslo'] = password_hash($dane['haslo'], PASSWORD_DEFAULT);
        }

        return $this->db->aktualizuj('uzytkownicy', $update, $id);
    }
}
