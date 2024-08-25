<?php

namespace DatabaseNamespace;

use PDO;

class Insert
{
    private string $table;
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function setTable(string $table)
    {
        $this->table = $table;
    }

    public function create(array $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO " . $this->table . " ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        try {
            $stmt->execute();
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
