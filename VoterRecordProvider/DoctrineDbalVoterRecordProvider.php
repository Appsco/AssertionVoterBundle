<?php

namespace Appsco\AssertionVoterBundle\VoterRecordProvider;

use Appsco\AssertionVoterBundle\Model\VoterRecord;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\Query;

class DoctrineDbalVoterRecordProvider implements VoterRecordProviderInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string[]
     */
    private $fields;

    /**
     * @var string[]
     */
    private $selectStatement;

    public function __construct(
        Connection $connection,
        $tableName,
        $issuerColumn,
        $attributeColumn,
        $valueColumn,
        $roleColumn
    ) {
        $this->connection = $connection;
        $this->tableName = $tableName;
        $this->fields = [$issuerColumn, $attributeColumn, $valueColumn, $roleColumn];
        $this->selectStatement = '`' . join('`, `', $this->fields) . '`';
    }

    /**
     * {@inheritDoc}
     */
    public function getVoterRecords()
    {
        $sql = "SELECT {$this->selectStatement} FROM `{$this->tableName}`";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $rows = $statement->fetchAll(Query::HYDRATE_ARRAY);

        $records = [];
        foreach ($rows as $row) {
            $records[] = $this->hydrate($row);
        }

        return $records;
    }

    /**
     * @param array $row
     * @return VoterRecord
     */
    private function hydrate($row)
    {
        return new VoterRecord(
            $row[$this->fields[0]],
            $row[$this->fields[1]],
            $row[$this->fields[2]],
            $row[$this->fields[3]]
        );
    }
} 