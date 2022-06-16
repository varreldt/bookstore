<?php

require_once 'db.php';

class Penerbit
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
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 4;
    $limit_start = ($page - 1) * $limit;
    $query = "SELECT * FROM penerbit LIMIT {$limit_start}, {$limit}";
    $sql = $this->db->query($query);

    $data = [];
    foreach ($sql as $row) {
      $data[] = $row;
    }

    $query2 = "SELECT * FROM penerbit";
    $sql = $this->db->query($query2);
    $total_rows = $sql->num_rows;

    $data = [
      'penerbit' => $data,
      'total_rows' => $total_rows
    ];

    header('Content-Type: application/json');
    echo json_encode($data);
  }


  public function create($data)
  {
    foreach ($data as $key => $value) {
      $value = is_array($value) ? trim(implode(',', $value)) : trim($value);
      $data[$key] = strlen($value) > 0 ? $value : NULL;
    }

    $query = "INSERT INTO penerbit VALUES (NULL, ?, ?)";

    $sql = $this->db->prepare($query);
    $sql->bind_param(
      'ss',
      $data['nama'],
      $data['alamat'],
    );

    try {
      $sql->execute();
    } catch (Exception $e) {
      $sql->close();
      http_response_code(500);
      die($e->getMessage());
    }

    $sql->close();
  }

  public function detail($id)
  {
    $query = "SELECT * FROM penerbit WHERE id = {$id}";
    $sql = $this->db->query($query);

    $data = $sql->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($data);
  }

  public function update($data)
  {
    foreach ($data as $key => $value) {
      $value = is_array($value) ? trim(implode(',', $value)) : trim($value);
      $data[$key] = strlen($value) > 0 ? $value : NULL;
    }

    $query = "UPDATE penerbit SET nama = ?, alamat = ? WHERE id = ?";

    $sql = $this->db->prepare($query);
    $sql->bind_param(
      'ssi',
      $data['nama'],
      $data['alamat'],
      $data['id']
    );

    try {
      $sql->execute();
    } catch (Exception $e) {
      $sql->close();
      http_response_code(500);
      die($e->getMessage());
    }

    $sql->close();
  }

  public function delete($data)
  {
    $query = "DELETE FROM penerbit WHERE id = ?";

    $sql = $this->db->prepare($query);

    $sql->bind_param('i', $data['id']);

    try {
      $sql->execute();
    } catch (Exception $e) {
      $sql->close();
      http_response_code(500);
      die($e->getMessage());
    }

    $sql->close();
  }

  public function getAll()
  {
    $query = "SELECT * FROM penerbit";
    $sql = $this->db->query($query);

    $data = [];
    foreach ($sql as $row) {
      $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
  }
}


$penerbit = new Penerbit();
$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
  case 'create':
    $penerbit->create($_POST);
    break;
  case 'detail':
    $penerbit->detail($_GET['id']);
    break;
  case 'update':
    $penerbit->update($_POST);
    break;
  case 'delete':
    $penerbit->delete($_POST);
    break;
  case 'getAll':
    $penerbit->getAll();
    break;
  default:
    $penerbit->read();
    break;
}
