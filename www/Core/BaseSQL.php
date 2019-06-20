<?php
declare(strict_types=1);

namespace Legacy\Core;

use mysql_xdevapi\Exception;
use PDO;

class BaseSQL
{
    private $pdo;
    private $table;

    public function __construct(PDO $pdo, string $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        /*
        try {
            $this->pdo = new PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            die("Erreur SQL : " . $e->getMessage());
        }

        $this->table = get_called_class();
        */
    }


    public function setId($id)
    {
        $this->id = $id;
        $this->getOneByObject(["id" => $id]);
    }

    /**
     * @param array $where the where clause
     * @return array
     */
    public function getOneByArray(array $where): array
    {
        $query = $this->prepareQuery($where);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute($where);

        return $query->fetch();
    }

    public function getOneByObject(array $where): self
    {
        $query = $this->prepareQuery($where);
        $query->setFetchMode(PDO::FETCH_INTO, $this);
        $query->execute($where);

        return $query->fetch();
    }

    private function prepareQuery(array $conditionList): \PDOStatement
    {
        $sqlWhere = [];
        foreach ($conditionList as $key => $value) {
            $sqlWhere[] = $key . "=:" . $value;
        }
        $sql = " SELECT * FROM " . $this->table . " WHERE  " . implode(" AND ", $sqlWhere) . ";";
        $query = $this->pdo->prepare($sql);

        if ($query === false) {
            // TODO custom Exception
            throw new \Exception();
        }

        return $query;
    }

    public function save(): bool
    {
        $dataObject = get_object_vars($this);
        $dataChild = array_diff_key($dataObject, get_class_vars(get_class()));

        $sql = $this->getQueryToSave($dataChild);
        $query = $this->pdo->prepare($sql);

        if ($query === false) {
            // TODO custom Exception
            throw new \Exception("Error during the prepare of Insert");
        }

        return $query->execute($dataChild);
    }

    private function prepareInsert(array $parameters): string
    {
        $sql = "INSERT INTO " . $this->table . " ( " .
            implode(",", array_keys($parameters)) . ") VALUES ( :" .
            implode(",:", array_keys($parameters)) . ")";

        return $sql;
    }

    private function prepareUpdate(array $dataChild): string
    {
        $sqlUpdate = [];
        foreach ($dataChild as $key => $value) {
            if ($key != "id") {
                $sqlUpdate[] = $key . "=:" . $key;
            }
        }

        return "UPDATE " . $this->table . " SET " . implode(",", $sqlUpdate) . " WHERE id=:id";
    }

    private function getQueryToSave($dataChild)
    {
        if (is_null($dataChild['id'])) {
            return $this->prepareInsert($dataChild);
        }

        return $this->prepareUpdate($dataChild);
    }
}
