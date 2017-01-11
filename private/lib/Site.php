<?php

class Site {
  private $pdo;
  private $page;

  public function render() {
    try {
      ob_start();
      include $this->getPageScript($this->getPage());
      ob_end_flush();
    } catch (Exception $e) {
      ob_end_clean();
      trigger_error($e);
      echo "There has been an error";
    }
  }

  public function getPage() {
    if (!isset($this->page)) {
      $page = trim($_SERVER['REQUEST_URI'], '/');
      if (strlen($page) <= 0) { $page = 'home'; }
      if (!$this->checkPageExists($page)) { $page = 'home'; }
      $this->page = $page;
    }

    return $this->page;
  }

  public function getPageScript($page) {
    $here = __DIR__;
    return "$here/../pages/$page.php";
  }

  public function checkPageExists($page) {
    if (!preg_match('#^[a-z0-9_\\-]+$#', $page)) { return false; }
    return file_exists($this->getPageScript($page));
  }

  public function getRequestString($key) {
    if (!isset($_GET[$key])) { return ""; }
    if (!is_scalar($_GET[$key])) { return ""; }
    return (string)$_GET[$key];
  }

  public function getRequestInt($key) {
    return (int)$this->getRequestString($key);
  }

  public function getPostString($key) {
    if (!isset($_POST[$key])) { return ""; }
    if (!is_scalar($_POST[$key])) { return ""; }
    return (string)$_POST[$key];
  }

  public function getPostInt($key) {
    return (int)$this->getPostInt($key);
  }

  public function query() {
    $this->connect();
    $args = func_get_args();
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

  public function loadConfig($name) {
    $here = __DIR__;
    $file = "$here/../config/db.php";
    if (!file_exists($file)) { throw new Exception("Missing config '$name'"); }
    return (object)(include $file);
  }

  public function connect() {
    if (!isset($this->pdo)) {
      $cfg = $this->loadConfig('db');
      if (!isset($cfg->name)) { $cfg->name = "torpanel"; }
      if (!isset($cfg->host)) { $cfg->host = "127.0.0.1"; }
      if (!isset($cfg->dsn)) { $cfg->dsn = "mysql:dbname={$cfg->name};host={$cfg->host}"; }
      $this->pdo = new pdo($cfg->dsn, $cfg->user, $cfg->pass);
    }

    return $this->pdo;
  }
}
