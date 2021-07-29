<?php
class IdCard 
{
    private $conn;
    private $table = "cardIdCustomer";

    public $idCard;
    public $urlCard;
    public $idContract;
    public $dateCard;

    public function __construct($db) 
    {
        $this->conn = $db;
    }

    public function createIdCard() 
    {
        $query = "
        INSERT INTO "
            . $this->table .
            " SET
            idCustomer = :idCustomer,
            idContract = :idContract
            ";
        $stmt = $this->conn->prepare($query);

        $params = [
            "idCustomer" => htmlspecialchars(strip_tags($this->idCustomer)),
            "idContract" => htmlspecialchars(strip_tags($this->idContract))
        ];

        if($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    public function listIdCards() 
    {
        $query = "
            SELECT *
            FROM "
            . $this->table . " 
            ORDER BY
            idCard ASC";
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

    public function searchIdCardByContract() 
    {
        $query = "
        SELECT *
        FROM " 
        . $this->table . " 
        WHERE idContract = :idContract";
        $stmt = $this->conn->prepare($query);

        $params = ["idContract" => htmlspecialchars(strip_tags($this->idContract))];

        if($stmt->execute($params)) {
            return $stmt;
        }
        return false;
    }
}