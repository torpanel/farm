<?php

class Site {
  private $page;

  public function render() {
    try {
      ob_start();
      $this->widget('site');
      ob_end_flush();
    } catch (Exception $e) {
      ob_end_clean();
      trigger_error($e);
      echo "There has been an error";
    }
  }

  public function widget($name, $_args=[]) {
    $here = __DIR__;
    $_script = "$here/../widgets/$name.php";
    if (!file_exists($_script)) { throw new Exception("Unknown widget $name"); }
    unset($here, $name);
    extract($_args);
    unset($_args);
    include $_script;
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

  public function getDb() {
    if (!isset($this->db)) { $this->db = new Db($this->connect()); }
    return $this->db;
  }

  private function connect() {
    $cfg = $this->loadConfig('db');
    if (!isset($cfg->name)) { $cfg->name = "torpanel"; }
    if (!isset($cfg->host)) { $cfg->host = "127.0.0.1"; }
    if (!isset($cfg->dsn)) { $cfg->dsn = "mysql:dbname={$cfg->name};host={$cfg->host}"; }
    return new pdo($cfg->dsn, $cfg->user, $cfg->pass);
  }

  public function query() {
    $args = func_get_args();
    return $this->getDb()->query($args);
  }

  public function loadConfig($name) {
    $here = __DIR__;
    $file = "$here/../config/db.php";
    if (!file_exists($file)) { throw new Exception("Missing config '$name'"); }
    return (object)(include $file);
  }
}

function html($x) {
  if (!is_scalar($x)) { throw new Exception("HTML output must be scalar"); }
  echo htmlspecialchars($x);
}

function htmlattr($key, $value) {
  if (!is_scalar($key)) { throw new Exception("HTML attribute key must be a scalar"); }
  if (!is_scalar($value)) { throw new Exception("HTML attribute value must be a scalar"); }
  $value = trim($value);
  if (strlen($value) <= 0) { return; }
  $value = htmlspecialchars($value);
  echo " $key=\"$value\" ";
}

function href($page, $args=[]) {
  if ($page === 'home') { $page = '/'; }
  if (preg_match('#^[a-z0-9_\\-]$#', $page)) { $page = "/$page"; }
  htmlattr('href', $page);
}
