<?php
class OptionsCA
{
	private $conn;
	private $table = 'paybox';

	public $site;
	public $rang;
	public $identifiant;
	public $keyPaybox

    public function __construct($db) 
    {
        $this->conn = $db;
    }

    public function getPayboxData() {
       	$query = "SELECT * FROM ".$this->table.' LIMIT 0,1';
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $row = $stmt->fetch();
            return $row;
        }
        return false;
    }
}