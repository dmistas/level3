<?php

namespace App\Models;


use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder
{
    private $pdo, $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    /**
     * @param int $id
     * @param string $table
     * @return array|false
     */
    public function find($id, $table)
    {
        $select = $this->queryFactory->newSelect();

        $select->cols(['*']);
        $select->from($table)
        ->where('id = :id')
        ->bindValue('id', $id);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $table
     * @return array
     */
    public function getAll($table)
    {
        $select = $this->queryFactory->newSelect();

        $select->cols(['*']);
        $select->from($table);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $data ассоциативный массив записей [поле => значение]
     * @param string $table название таблицы для записи данных
     *
     * @return string
     */
    public function insert(array $data, string $table)
    {
        $insert = $this->queryFactory->newInsert();

        $insert->into($table)             // insert into this table
        ->cols($data);

        $sth = $this->pdo->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());

        $name = $insert->getLastInsertIdName('id');
        return $this->pdo->lastInsertId($name);

    }

    /**
     * @param array $data
     * @param int $id
     * @param string $table
     *
     * @return boolean
     */
    public function update(array $data, int $id, string $table)
    {
        $update = $this->queryFactory->newUpdate();

        $update
            ->table($table)                  // update this table
            ->cols($data)
            ->where('id = :id')           // AND WHERE these conditions
            ->bindValue('id', $id);   // bind one value to a placeholder

        $sth = $this->pdo->prepare($update->getStatement());

        $sth->execute($update->getBindValues());
        return boolval($sth->rowCount());
    }

    /**
     * @param int $id
     * @param string $table
     * @return bool
     */
    public function delete(int $id, string $table)
    {
        $delete = $this->queryFactory->newDelete();

        $delete
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());

        return boolval($sth->rowCount());
    }
}