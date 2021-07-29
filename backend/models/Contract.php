<?php
class Contract 
{
    private $conn;
    private $table = "contracts";

    public $idContract;
    public $idBooking;
    public $idCar;
    public $idCustomer;
    public $idPartner;
    public $idAgency;
    public $urlCustomerForthInventory;
    public $urlPartnerForthInventory;
    public $urlPartnerBackInventory;
    public $urlCustomerBackInventory;
    public $dateContract;
    public $idForthAddress;
    public $idBackAddress;
    public $idTeammateForth;
    public $idTeammateBack;
    public $additionnalFees;
    public $urlInventory;

    public $way;

    public function __construct($db) 
    {
        $this->conn = $db;
    }

    public function createContract() 
    {
        $query = "
            INSERT INTO "
            . $this->table .
            " SET
            idBooking = :idBooking,
            idCar = :idCar,
            idCustomer = :idCustomer,
            idPartner = :idPartner,
            idAgency = :idAgency,
            idForthAddress = :idForthAddress,
            idBackAddress = :idBackAddress,
            idTeammateForth = :idTeammateForth,
            idTeammateBack = :idTeammateBack
        ";
        $stmt = $this->conn->prepare($query);

        $params = [
            "idBooking" => htmlspecialchars(strip_tags($this->idBooking)),
            "idCar" => htmlspecialchars(strip_tags($this->idCar)),
            "idCustomer" => htmlspecialchars(strip_tags($this->idCustomer)),
            "idPartner" => htmlspecialchars(strip_tags($this->idPartner)),
            "idAgency" => htmlspecialchars(strip_tags($this->idAgency)),
            "idForthAddress" => htmlspecialchars(strip_tags($this->idForthAddress)),
            "idBackAddress" => htmlspecialchars(strip_tags($this->idBackAddress)),
            "idTeammateForth" => htmlspecialchars(strip_tags($this->idTeammateForth)),
            "idTeammateBack" => htmlspecialchars(strip_tags($this->idTeammateBack))
        ];

        if ($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    public function listContracts() 
    {
        $query = "
            SELECT *
            FROM "
            . $this->table . " 
            ORDER BY
            dateContract DESC";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return $stmt;
        }
        return false;
    }

    public function searchContractsByPartner() 
    {
        $query = "
        SELECT *
        FROM "
        . $this->table . " 
        WHERE idPartner = :idPartner";
        $stmt = $this->conn->prepare($query);
        
        $params = ["idPartner" => htmlspecialchars(strip_tags($this->idPartner))];

        if ($stmt->execute($params)) {
            return $stmt;
        }
        return false;
    }

    public function searchContractsByCustomer() 
    {
        $query = "
        SELECT *
        FROM "
        . $this->table . " 
        WHERE idCustomer = :idCustomer";
        $stmt = $this->conn->prepare($query);

        $params = ["idCustomer" => htmlspecialchars(strip_tags($this->idCustomer))];
        
        if ($stmt->execute($params)) {
            return $stmt;
        }
        return false;
    }

    public function searchContractsByCar() 
    {
        $query = "
        SELECT *
        FROM "
        . $this->table . " 
        WHERE idCar = :idCar";
        $stmt = $this->conn->prepare($query);

        $params = ["idCar" => htmlspecialchars(strip_tags($this->idCar))];

        if ($stmt->execute($params)) {
            return $stmt;
        }
        return false;    
    }

    public function searchContractByAgency() 
    {
        $query = "
        SELECT *
        FROM "
        . $this->table . " 
        WHERE idAgency = :idAgency";
        $stmt = $this->conn->prepare($query);

        $params = ["idAgency" => htmlspecialchars(strip_tags($this->idAgency))];

        if ($stmt->execute($params)) {
            return $stmt;
        }
        return false;           
    }

    public function searchContractByBooking() 
    {
        $query = "
        SELECT *
        FROM "
        . $this->table . " 
        WHERE idBooking = :idBooking
        LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        
        $params = ["idBooking" => htmlspecialchars(strip_tags($this->idBooking))];
        
        if ($stmt->execute($params)) {
            $row = $stmt->fetch();    
            return $row;
        }
        return false;
    }

    public function searchContract() 
    {
        $query = "
        SELECT *
        FROM "
        . $this->table . " 
        WHERE idContract = :idContract
        ";
        $stmt = $this->conn->prepare($query);
        
        $params = ["idContract" => htmlspecialchars(strip_tags($this->idContract))];
        
        if ($stmt->execute($params)) {
            $row = $stmt->fetch();    
            return $row;
        }
        return false;
    }

    public function addInventory() 
    {
        $way = htmlspecialchars(strip_tags($this->way));
        $query = "
            UPDATE "
            . $this->table .
            " SET
            url".$way."Inventory = :urlInventory
            WHERE idContract = :idContract";
        $stmt = $this->conn->prepare($query);

        $params = [
            "urlInventory" => htmlspecialchars(strip_tags($this->urlInventory)),
            "idContract" => htmlspecialchars(strip_tags($this->idContract))
        ];
        if ($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    public function addTeammateBack() 
    {
        $query = "
            UPDATE "
            . $this->table .
            " SET
            idTeammateBack = :idTeammateBack
            WHERE idContract = :idContract";
        $stmt = $this->conn->prepare($query);   

        $params = [
            "idTeammateBack" => htmlspecialchars(strip_tags($this->idTeammateBack)),
            "idContract" => htmlspecialchars(strip_tags($this->idContract))
        ];

        if ($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    public function addFee() 
    {
        $query = "
            UPDATE "
            . $this->table .
            " SET
            additionnalFees = :additionnalFees
            WHERE idContract = :idContract";
        $stmt = $this->conn->prepare($query);   

        $params = [
            "additionnalFees" => htmlspecialchars(strip_tags($this->additionnalFees)),
            "idContract" => htmlspecialchars(strip_tags($this->idContract))
        ];

        if ($stmt->execute($params)) {
            return true;
        }
        return false;
    }


}