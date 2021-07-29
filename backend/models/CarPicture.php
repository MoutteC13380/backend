<?php
class Contract 
{
    private $conn;
    private $table = "carPictures";

    public $idPicture;
    public $idContract;
    public $urlPicture;
    public $location;
    public $datePicture;

    public function __construct($db) 
    {
        $this->conn = $db;
    }

    public function createPicture() 
    {
        $query = "
            INSERT INTO "
            . $this->table .
            " SET
            idContract = :idContract,
            location = :location,
            urlPicture = :urlPicture,
        ";
        $stmt = $this->conn->prepare($query);

        $params = [
            "idContract" => htmlspecialchars(strip_tags($this->idContract)),
            "location" => htmlspecialchars(strip_tags($this->location)),
            "urlPicture" => htmlspecialchars(strip_tags($this->urlPicture))
        ];

        if ($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    public function listPictures() 
    {
        $query = "
            SELECT *
            FROM "
            . $this->table;
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return $stmt;
        }
        return false;
    }

    public function listPicturesByContract() 
    {
        $query = "
        SELECT *
        FROM "
        . $this->table . " 
        WHERE idContract = :idContract";
        $stmt = $this->conn->prepare($query);
        
        $params = ["idContract" => htmlspecialchars(strip_tags($this->idContract))];

        if ($stmt->execute($params)) {
            return $stmt;
        }
        return false;
    }
}