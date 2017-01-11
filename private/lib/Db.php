<?php

class Db {
  private $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  public function getPDO() {
    return $this->pdo;
  }

  public function query() {
    $this->connect();
    $args = func_get_args();
    if (count($args) === 1 && is_array($args[0])) { $args = $args[0]; }
    $sql = [];
    $params = [];

    foreach ($args as $value) {
      if (is_array($value)) {
        $params = array_merge($params, $value);
      } else if (is_string($value)) {
        $sql[] = $value;
      } else {
        throw new Exception("Arguments must be a string or array");
      }
    }

    $sql = implode(' ', $sql);
    $statement = $this->pdo->prepare($sql);
    if (!$statement->execute($params)) { throw new Exception("Unable to execute statement"); }
    return $statement;
  }
}
