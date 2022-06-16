<?php

require_once 'db.php';

class Kategori
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
    $query = "SELECT * FROM kategori LIMIT {$limit_start}, {$limit}";
    $sql = $this->db->query($query);

    $data = [];
    foreach ($sql as $row) {
      $data[] = $row;
    }

    $query2 = "SELECT * FROM kategori";
    $sql = $this->db->query($query2);
    $total_rows = $sql->num_rows;

    $data = [
      'kategori' => $data,
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

    $query = "INSERT INTO kategori VALUES (NULL, ?)";

    $sql = $this->db->prepare($query);
    $sql->bind_param(
      's',
      $data['nama'],
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
    $query = "SELECT * FROM kategori WHERE id = {$id}";
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

    $query = "UPDATE kategori SET nama = ? WHERE id = ?";

    $sql = $this->db->prepare($query);
    $sql->bind_param(
      'si',
      $data['nama'],
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
    $query = "DELETE FROM kategori WHERE id = ?";

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
    $query = "SELECT * FROM kategori";
    $sql = $this->db->query($query);

    $data = [];
    foreach ($sql as $row) {
      $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
  }
}

$kategori = new Kategori();
$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
  case 'create':
    $kategori->create($_POST);
    break;
  case 'detail':
    $kategori->detail($_GET['id']);
    break;
  case 'update':
    $kategori->update($_POST);
    break;
  case 'delete':
    $kategori->delete($_POST);
    break;
  case 'getAll':
    $kategori->getAll();
    break;
  default:
    $kategori->read();
    break;
}
