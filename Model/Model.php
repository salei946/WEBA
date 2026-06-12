<?php

class Model {

    private $db;

    public function __construct(bool $withErrors = false) {
        $this->db = new PDO("mysql:host=localhost;dbname=weba-te03-2006", "root", "");

        if ($withErrors) {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    // TODO
}
?>