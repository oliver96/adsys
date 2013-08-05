<?php

import("com.zcx.db.Driver");
import("com.zcx.db.DriverManager");

abstract class Model {

    /**
     * 返回操作的数据表（抽象方法）
     *
     * @return string
     */
    abstract protected function getTableName();

    /**
     * 获取主键字段名称
     *
     * @return string
     */
    abstract protected function getPrimaryKey();

    /**
     * 获取数据库连接
     * 
     * @return object
     */
    protected function connect() {
        return connect();
    }

    /**
     * 获取字段名称
     *
     * @param array $options 字段
     *
     * @return string
     */
    protected function getFields(& $options = array()) {
        $fieldStr = '*';
        $tmpFields = '';

        if (isset($options['field'])) {
            $tmpFields = $options['field'];
        } else {
            if (property_exists($this, 'fields')) {
                $tmpFields = $this->fields;
            }
        }
        if (is_array($tmpFields)) {
            $fieldStr = '`' . implode("`, `", $tmpFields) . '`';
        } else {
            $fieldStr = ($tmpFields == '' ? '*' : $tmpFields);
        }

        return $fieldStr;
    }

    /**
     * 解析where语句部分
     */
    protected function parseWhere($key, $str) {
        $ops = array('eq' => '=', 'gt' => '>', 'lt' => '<', 'in' => 'IN', 'like' => 'LIKE', 'not' => '!=');
        $pos = strpos($str, ' ');

        if ($pos !== false) {
            $opKey = strtolower(substr($str, 0, $pos));
            $val = substr($str, $pos + 1);
        } else {
            $opKey = 'eq';
            $val = "'" . addslashes(trim($str, "'")) . "'";
        }
        $op = isset($ops[$opKey]) ? $ops[$opKey] : '=';
        $where = sprintf("`%s` %s %s", $key, $op, $val);

        return $where;
    }

    /**
     * 解析group语句部分
     */
    protected function parseGroup($str) {
        return $str;
    }

    protected function parseOrder($str) {
        return $str;
    }

    /**
     * 解析SQL的条件语句：where ,group, order
     */
    protected function parseCondition($options) {
        if (empty($options)) {
            return '';
        }
        if (is_array($options)) {
            $sqlOps = array('where', 'group', 'order');
            $conditions = array();

            if (isset($options['field'])) {
                unset($options['field']);
            }

            foreach ($sqlOps as $key) {
                $conditions[$key] = array();
            }
            if (!empty($options)) {
                foreach ($options as $key => $values) {
                    if (in_array($key, $sqlOps)) {
                        if (is_array($values)) {
                            foreach ($values as $idx => $value) {
                                switch (strtolower($key)) {
                                    case 'where' :
                                        $conditions['where'][] = $this->parseWhere($idx, $value);
                                        break;
                                    case 'group' :
                                        $conditions['group'][] = $this->parseGroup($value);
                                        break;
                                    case 'order' :
                                        $conditions['order'][] = $this->parseOrder($value);
                                        break;
                                }
                            }
                        } else {
                            switch (strtolower($key)) {
                                case 'where' :
                                    $conditions['where'][] = $values;
                                    break;
                                case 'group' :
                                    $conditions['group'][] = $this->parseGroup($values);
                                    break;
                                case 'order' :
                                    $conditions['order'][] = $this->parseOrder($values);
                                    break;
                            }
                        }
                    } else {
                        $conditions['where'][] = $this->parseWhere($key, $values);
                    }
                }
            }
            $sql = '';
            foreach ($sqlOps as $key) {
                if (!empty($conditions[$key])) {
                    if ($key == 'where') {
                        $sql .= ' ' . strtoupper($key) . ' ';
                        $sql .= implode(' AND ', $conditions[$key]);
                    } else {
                        $sql .= ' ' . strtoupper($key) . ' BY ';
                        $sql .= implode(', ', $conditions[$key]);
                    }
                }
            }
        } else {
            $sql = $options;
        }
        return $sql;
    }

    /**
     * 解析SET语句部分
     */
    protected function parseSet($updateAry) {
        if (property_exists($this, 'fields')) {
            $tmp = array();

            if (!is_array($this->fields)) {
                $fields = explode(',', $this->fields);
            } else {
                $fields = $this->fields;
            }
            if (!empty($fields)) {
                $priKey = $this->getPrimaryKey();
                foreach ($fields as $field) {
                    $field = trim($field);
                    if ($field == $priKey)
                        continue; // 主键不能修改
                    if (isset($updateAry[$field])) {
                        $tmp[] = sprintf("`%s` = '%s'", $field, addslashes($updateAry[$field]));
                    }
                }
                return implode(', ', $tmp);
            }
        }
        return '';
    }

    /**
     * 根据条件获取一条记录
     * 
     * @param array $options 查询条件
     *
     * @return Dataset 返回一个数据集对象
     */
    public function & getOne($options) {
        $tableName = $this->getTableName();
        $conn = $this->connect();

        if ($tableName == '' || $conn == null) {
            return ref(null);
        }

        $fieldStr = $this->getFields();
        $extSql = $this->parseCondition($options);

        $sql = array();
        $sql[] = sprintf("SELECT %s FROM `%s`", $fieldStr, $tableName);
        $sql[] = $extSql;
        $sql[] = " LIMIT 1";
        $dataset = $conn->query(implode('', $sql));
        $dataset->setModel($this);
        $dataRow = $dataset->firstRow();
        return $dataRow;
    }

    /**
     * 根据条件获取所有记录数
     *
     * @param array $options 查询条件（可选）
     *
     * @return integer
     */
    public function getCount($options = array()) {
        $tableName = $this->getTableName();
        $conn = $this->connect();

        if ($tableName == '' || $conn == null) {
            return;
        }

        $extSql = $this->parseCondition($options);

        $sql = array();
        $sql[] = sprintf("SELECT COUNT(*) AS Records FROM `%s`", $tableName);
        $sql[] = $extSql;
        $sql[] = " LIMIT 1";
        $dataSet = $conn->query(implode('', $sql));
        $row = $dataSet->firstRow();
        $count = $row->get('Records') ? $row->get('Records') : 0;
        return $count;
    }

    /**
     * 获取列表总记录数
     * 
     * @param array $options 查询条件（可选）
     * 
     * @return integer
     */
    public function getCountByList($options = array()) {
        $tableName = $this->getTableName();
        $conn = $this->connect();

        if ($tableName == '' || $conn == null) {
            return;
        }

        $fieldStr = $this->getFields($options);
        $extSql = $this->parseCondition($options);

        $sql = array();
        $sql[] = sprintf("SELECT COUNT(*) AS Records FROM (SELECT %s FROM `%s`", $fieldStr, $tableName);
        $sql[] = $extSql;
        $sql[] = ") AS tmp";

        $dataset = $conn->query(implode('', $sql));
        $row = $dataset->firstRow();
        $count = $row->get('Records') ? $row->get('Records') : 0;

        return $count;
    }

    /**
     * 　根据条件获取记录列表
     *
     * @param array $options 查询条件（可选）
     * @param integer $page　页码数
     * @param integer $psize　页码大小
     *
     * @return Dataset 返回一个数据集对象
     */
    public function & getList($options = array(), $page = 0, $psize = 0) {

        $tableName = $this->getTableName();
        $conn = $this->connect();

        if ($tableName == '' || $conn == null) {
            $null = null;
            return $null;
        }
        $fieldStr = $this->getFields($options);
        $extSql = $this->parseCondition($options);

        $sql = array();
        $sql[] = sprintf("SELECT %s FROM `%s`", $fieldStr, $tableName);
        $sql[] = $extSql;

        if ($page > 0 && $psize > 0) {
            $start = ($page - 1) * $psize;
            $sql[] = sprintf(" LIMIT %d, %d", $start, $psize);
        }
        $dataset = $conn->query(implode('', $sql));
        $dataset->setModel($this);

        return $dataset;
    }

    /**
     * 插入一条记录
     *
     * @param array $updateAry 记录的内容
     *
     * @return integer 返回受影响的记录数
     */
    public function insert($insertAry) {
        $insertid = -1;

        $tableName = $this->getTableName();
        $conn = $this->connect();

        if ($tableName == '' || $conn == null) {
            return $insertid;
        }

        if ($this->validate($insertAry)) {
            $insertStr = $this->parseSet($insertAry);
            $affecteds = 0;
            if (!empty($insertStr)) {

                $sql = sprintf("INSERT INTO `%s` SET %s", $tableName, $insertStr);
                $affecteds = $conn->execute($sql);
                if ($affecteds) {
                    $insertid = $conn->insertid();
                }
            }
        }

        return $insertid;
    }

    /**
     * 根据指定的条件进行更新记录内容
     *
     * @param array $updateAry 更新的内容
     * @param array $options 更新条件
     *
     * @return integer 返回受影响的记录数
     */
    public function update($updateAry, $options) {
        $affecteds = -1;

        $tableName = $this->getTableName();
        $conn = $this->connect();

        if ($tableName == '' || $conn == null) {
            return;
        }

        if ($this->validate($updateAry)) {
            $extSql = $this->parseCondition($options);
            $updateStr = $this->parseSet($updateAry);
            $affecteds = 0;
            if (!empty($updateStr)) {
                $sql = array();

                $sql[] = sprintf("UPDATE `%s` SET %s", $tableName, $updateStr);
                $sql[] = $extSql;
                $affecteds = $conn->execute(implode('', $sql));
            }
        }
        return $affecteds;
    }

    /**
     * 根据指定的条件进行删除记录
     *
     * @param array $options 删除条件
     *
     * @return integer 返回受影响的记录数
     */
    public function delete($options) {
        $tableName = $this->getTableName();
        $conn = $this->connect();
        if ($tableName == '' || $conn == null) {
            return;
        }

        $extSql = $this->parseCondition($options);

        $affecteds = 0;
        if (!empty($extSql)) {
            $sql = array();
            $sql[] = sprintf("DELETE FROM `%s`", $tableName);
            $sql[] = $extSql;
            $affecteds = $conn->execute(implode('', $sql));
        }
        return $affecteds;
    }

    /**
     * 表数据验证
     * 
     * @param array $data 数据数组
     * 
     * @return boolean 数据是否验证通过
     */
    public function validate($data) {
        return true;
    }

    /**
     * 添加错误信息到缓存中
     * 
     * @param type $error 错误信息
     */
    public function addError
    ($error) {
        if (!property_exists($this, 'errors')) {
            $this->errors = array();
        }
        $this->errors[] = $error;
    }

    /**
     * 获取最后一条错误信息
     * 
     * @return string 错误信息
     */
    public function getError() {
        if (property_exists($this, 'errors') && isset($this->errors [0])) {
            $last = count($this->errors);
            if ($last > 0)
                $last--;

            return $this->errors[$last];
        }
        else {
            return '';
        }
    }

    /**
     * 获取缓存内所有的错误信息
     * 
     * @return array 返回错误信息数组 
     */
    public function getErrors() {
        if (!property_exists($this, 'errors')) {
            return $this->errors;
        } else {
            return array();
        }
    }

}

?>
