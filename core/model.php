<?php

class Model
{
    protected $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($this->mysqli->connect_errno) {
            printf("Connect failed: %s\n", $this->mysqli->connect_error);
            exit();
        }
    }

    public function getAll()
    {
        $query = 'select * from `' . $this->table . '`';
        $result = $this->mysqli->query($query);
        return $result->fetch_assoc();
    }

    public function getById($id)
    {
        $query = 'select * from `' . $this->table . '` where `id` = \'' . $this->mysqli->real_escape_string($id) . '\'';
        $result = $this->mysqli->query($query);
        return $result->fetch_assoc();
    }

    public function save($data)
    {

        $array_keys = array_keys($data);
        $array_keys_array = array_map(function ($val) {
            return '`' . $val . '`';
        }, $array_keys);


        $array_values_array = array_map(function ($val) {
            return "'" . $val . "'";
        }, $data);


        $query = 'INSERT INTO `' . $this->table . '` (' . implode(',', $array_keys_array) . ')
VALUES (' . implode(',', ($array_values_array)) . ');';

        $result = $this->mysqli->query($query);
        return $this->mysqli->insert_id;
    }

    public function update($data = array())
    {
        if (isset($data['data'])) {

            $fields = array();
            array_walk($data['data'], function ($value, $key) use (&$fields) {
                array_push($fields, "`" . $key . "` = '" . $value . "'");
            });

        }

        if (isset($data['where'])) {

            $where = array();
            array_walk($data['where'], function ($value, $key) use (&$where) {
                array_push($where, $key . " '" . $value . "'");
            });
        } else {
            $where = '1=1';
        }

        $query = "UPDATE " . $this->table . " SET " . implode(',', $fields) . " WHERE   " . implode(' AND ', $where);


        $result = $this->mysqli->query($query);
        if ($this->mysqli->affected_rows) {
            return true;
        } else {
            return false;
        }


    }


    /**
     * find
     */
    public function find($type, $data = array())
    {

        if ($type = 'first') {
            $limit = 'limit 1';
        } else {
            $limit = '';
        }

        if (isset($data['select'])) {
            $fields = array_map(function ($val) {
                return '`' . $val . '`';
            }, $data['select']);
        } else {
            $fields = '*';
        }

        if (isset($data['where'])) {

            $where = array();
            array_walk($data['where'], function ($value, $key) use (&$where) {
                array_push($where, $key . " '" . $value . "'");
            });


        } else {
            $where = '1=1';
        }

        $query = "SELECT " . implode(',', $fields) . "  FROM  " . $this->table . " WHERE  " . implode(' AND ', $where) . ' ' . $limit;


        $result = $this->mysqli->query($query);
        return $result->fetch_assoc();
    }

    public function query($query, $isInsert = 0)
    {
        $result = $this->mysqli->query($query);

        if (!$isInsert) {
            return $result->fetch_assoc();
        } else {
            return $this->mysqli->insert_id;
        }

    }
}