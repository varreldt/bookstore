<?php

require_once 'db.php';

class Toko
{
  private $db;

  public function __construct()
  {
    $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($this->db->connect_error) {
      http_response_code(500);
      die("Database connection error: {$this->db->connect_error}");
    }
  }

  public function __destruct()
  {
    $this->db->close();
  }

  public function read()
  {
    $page = isset($_GET['page']) ? $_GET['page'] : 0;
    $query = "SELECT * FROM toko ORDER BY nama ASC  LIMIT {$page}, 8";
    $sql = $this->db->query($query);

    $data = [];
    foreach ($sql as $row) {
      $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
  }
}

$toko = new Toko();
$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
  default:
    $toko->read();
    break;
}
