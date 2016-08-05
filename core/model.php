<?php

class Model
{
    protected $mysqli;
    protected $fields;
    protected $conditions;
    protected $order;
    protected $groupBy;
    protected $limit;
    protected $set;
    protected $insertFields;
    protected $insertValues;

    public function __construct()
    {
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($this->mysqli->connect_errno) {
            printf("Connect failed: %s\n", $this->mysqli->connect_error);
            exit();
        }
    }

    public function select($data = array())
    {
        if (!empty($data)) {
            $fields = array_map(function ($val) {
                return '`' . $val . '`';
            }, $data);
            $this->fields = implode(', ', $fields);
        }

        return $this;
    }

    public function where($column = 1, $operator = "=", $value = 1)
    {
        if (!empty($this->conditions)) {
            $this->conditions .= ' AND `' . $column . '` ' . $operator . "'" . $value . "'";
        } else {
            $this->conditions = ' `' . $column . '` ' . $operator . "'" . $value . "'";
        }

        return $this;
    }

    public function orWhere($column = 1, $operator = "=", $value = 1)
    {
        $this->conditions .= ' OR `' . $column . '` ' . $operator . "'" . $value . "'";
        return $this;
    }

    public function order($column, $ascDesc)
    {
        if (!empty($this->order)) {
            $this->order .= ' , `' . $column . '` ' . $ascDesc;
        } else {
            $this->order = ' `' . $column . '` ' . $ascDesc;
        }

        return $this;
    }


    public function groupBy($data = array())
    {
        if (!empty($data)) {
            $this->groupBy = implode(', ', $data);
        }

        return $this;
    }

    public function limit($limit = 1)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * select query builder
     */
    public function selectQueryBuild()
    {

        if (empty($this->fields)) {
            $this->fields = '*';
        }

        if (empty($this->conditions)) {
            $this->conditions = '1=1';
        }

        $query = 'SELECT ' . $this->fields
            . ' FROM ' . '`' . $this->table . '`'
            . ' WHERE ' . $this->conditions;

        if (!empty($this->groupBy)) {
            $query .= ' GROUP BY ' . $this->groupBy;
        }

        if (!empty($this->order)) {
            $query .= ' ORDER BY ' . $this->order;
        }

        if (!empty($this->limit)) {
            $query .= ' LIMIT ' . $this->limit;
        }
        return $query;
    }

    public function first()
    {
        $this->limit = 1;
        $query = $this->selectQueryBuild();

        $result = $this->mysqli->query($query);
        $this->reset();
        return $result->fetch_assoc();
    }

    public function all()
    {

        $query = $this->selectQueryBuild();


        $result = $this->mysqli->query($query);
        $this->reset();
        return $result->fetch_assoc();
    }


    public function getById($id)
    {
        $this->where('id', '=', $id);
        return $this->first();
    }


    /**
     * insert query builder
     */
    public function insertQueryBuilder()
    {
        if (empty($this->fields)) {
            $this->fields = '*';
        }

        if (empty($this->conditions)) {
            $this->conditions = '1=1';
        }

        $query = 'INSERT INTO ' . '`' . $this->table . '`'
            . $this->insertFields
            . ' VALUES ' . $this->insertValues;


        return $query;
    }

    /**
     * insert data
     */

    public function insert($data)
    {

        $array_keys = array_keys($data);
        $array_keys_array = array_map(function ($val) {
            return '`' . $val . '`';
        }, $array_keys);
        $this->insertFields = '(' . implode(',', $array_keys_array) . ')';

        $array_values_array = array_map(function ($val) {
            return "'" . $val . "'";
        }, $data);
        $this->insertValues = '(' . implode(',', $array_values_array) . ')';


        $query = $this->insertQueryBuilder();

        $result = $this->mysqli->query($query);
        $this->reset();
        return $this->mysqli->insert_id;
    }

    /**
     * update query builder
     */
    public function updateQueryBuilder()
    {
        if (empty($this->fields)) {
            $this->fields = '*';
        }

        if (empty($this->conditions)) {
            $this->conditions = '1=1';
        }

        $query = 'UPDATE ' . '`' . $this->table . '`'
            . ' SET ' . $this->set
            . ' WHERE ' . $this->conditions;


        if (!empty($this->order)) {
            $query .= ' ORDER BY ' . $this->order;
        }

        if (!empty($this->limit)) {
            $query .= ' LIMIT ' . $this->limit;
        }
        return $query;
    }

    /*
     * update data
     */
    public function update($data = array())
    {
        if (!empty($data)) {

            $fields = array();
            array_walk($data, function ($value, $key) use (&$fields) {
                array_push($fields, "`" . $key . "` = '" . $value . "'");
            });

            $this->set = implode(', ', $fields);
            $query = $this->updateQueryBuilder();

            $result = $this->mysqli->query($query);
            $this->reset();

            if ($this->mysqli->affected_rows) {
                return true;
            } else {
                return false;
            }

        }


    }


    /**
     * delete query builder
     */
    public function deleteQueryBuilder()
    {


        if (empty($this->conditions)) {
            $this->conditions = '1=1';
        }

        $query = 'DELETE FROM ' . '`' . $this->table . '`'
            . ' WHERE ' . $this->conditions;


        if (!empty($this->order)) {
            $query .= ' ORDER BY ' . $this->order;
        }

        if (!empty($this->limit)) {
            $query .= ' LIMIT ' . $this->limit;
        }


        return $query;
    }

    /*
     * delete data
     */
    public function delete()
    {


        $query = $this->deleteQueryBuilder();

        $result = $this->mysqli->query($query);
        $this->reset();

        if ($this->mysqli->affected_rows) {
            return true;
        } else {
            return false;
        }


    }

    /**
     * reset variable values
     */
    public function reset()
    {
        foreach ($this as $key => $value) {
            if ($key !== 'table' && $key !== 'mysqli')
                unset($this->$key);
        }
    }


}