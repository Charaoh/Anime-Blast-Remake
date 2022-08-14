<?php
class db {
    var $host = NULL;
    var $username = NULL;
    var $password = NULL;
    var $dbmaster = NULL;
    var $dbslave = NULL;
    var $db = NULL;
    
    public function connect($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
		try {

			$this->link_id = new PDO("mysql:host=$this->host;dbname=$this->database;", $this->username, $this->password);
			$this->link_id->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		}
		catch (PDOException $error) {
			echo $error;
			die();
		}
    }
    public function query($query) {

        return $this->link_id->query($query);
    }
    
    public function fetch($query) {
        return $this->query($query)->fetch(PDO::FETCH_ASSOC);
		$return = null;
    }
    public function fieldFetch($table, $id, $field) {
        $query = $this->query("SELECT * FROM " . $table . " WHERE id = '" . $id . "'");
		$query = $query->fetch();
        if (!isset($query[$field])) {
            $query[$field] = 'undefined';
        }
        return $query[$field];

        unset($query);
    }
    public function close()
    {
        $this->db = null;
    }
}
