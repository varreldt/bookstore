<?php

require_once 'db.php';

class Auth
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

  public function checkLogin($email, $password)
  {
    $password = md5($password);
    $query = "SELECT * FROM user WHERE email = '{$email}' AND password = '{$password}'";
    $sql = $this->db->query($query);

    if ($sql->num_rows > 0) {
      $data = $sql->fetch_assoc();

      session_start();

      $_SESSION['nama'] = $data['nama'];
      $_SESSION['email'] = $data['email'];

      header('Content-Type: application/json');
      echo json_encode($data);
    } else {
      http_response_code(401);
    }
  }

  public function logout()
  {
    session_start();
    session_destroy();
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Logout success']);
  }

  public function register($data)
  {
    foreach ($data as $key => $value) {
      $value = is_array($value) ? trim(implode(',', $value)) : trim($value);
      $data[$key] = strlen($value) > 0 ? $value : NULL;
    }

    $data['password'] = md5($data['password']);

    $query = "INSERT INTO user VALUES (NULL, ?, ?, ?, ?)";

    $sql = $this->db->prepare($query);
    $sql->bind_param(
      'ssss',
      $data['email'],
      $data['password'],
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
}

$auth = new Auth();
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
  case 'logout':
    $auth->logout($_POST);
    break;
  case 'register':
    $auth->register($_POST);
    break;
  default:
    $auth->checkLogin($_POST['email'], $_POST['password']);
    break;
}
