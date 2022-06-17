<?php

require_once 'db.php';

class Buku
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
    $query = "SELECT * FROM buku ORDER BY judul ASC  LIMIT {$page}, 4";
    $sql = $this->db->query($query);

    $data = [];
    while ($row = $sql->fetch_assoc()) {
      if (!file_exists('../' . $row['gambar']) || $row['gambar'] == '') {
        $row['gambar'] = "https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png";
      }
      array_push($data, $row);
    }
    header('Content-Type: application/json');
    echo json_encode($data);
  }

  public function create($data)
  {
    foreach ($data as $key => $value) {
      $value = is_array($value) ? trim(implode(',', $value)) : trim($value);
      $data[$key] = strlen($value) > 0 ? $value : NULL;
    }

    if ($_FILES['gambar']['name'] != "") {
      $image = $_FILES['gambar'];
      $image_name = $image['name'];
      $image_tmp_name = $image['tmp_name'];
      $image_size = $image['size'];
      $image_error = $image['error'];

      $image_ext = explode('.', $image_name);
      $image_ext = strtolower(end($image_ext));

      $allowed = ['jpg', 'jpeg', 'png'];

      if (in_array($image_ext, $allowed)) {
        if ($image_error === 0) {
          if ($image_size <= 3000000) {
            $image_name_new = uniqid('', true) . '.' . $image_ext;
            $image_destination = '../img/' . $image_name_new;

            if (move_uploaded_file($image_tmp_name, $image_destination)) {
              $data['gambar'] = 'img/' . $image_name_new;
            } else {
              http_response_code(500);
              die('Error uploading image');
            }
          } else {
            http_response_code(500);
            die('Image size is too big');
          }
        } else {
          http_response_code(500);
          die('Error uploading image');
        }
      } else {
        http_response_code(500);
        die('Image type is not allowed');
      }
    }

    $query = "INSERT INTO buku VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)";

    $sql = $this->db->prepare($query);
    $sql->bind_param(
      'ssiisii',
      $data['judul'],
      $data['penulis'],
      $data['harga'],
      $data['tahun'],
      $data['gambar'],
      $data['id_penerbit'],
      $data['id_kategori']
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

  public function detail($kode_buku)
  {
    $query = "SELECT * FROM buku WHERE kode_buku = {$kode_buku}";
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

    if ($_FILES['gambar']['name'] != "") {
      $image = $_FILES['gambar'];
      $image_name = $image['name'];
      $image_tmp_name = $image['tmp_name'];
      $image_size = $image['size'];
      $image_error = $image['error'];

      $image_ext = explode('.', $image_name);
      $image_ext = strtolower(end($image_ext));

      $allowed = ['jpg', 'jpeg', 'png'];

      if (in_array($image_ext, $allowed)) {
        if ($image_error === 0) {
          if ($image_size <= 3000000) {
            $image_name_new = uniqid('', true) . '.' . $image_ext;
            $image_destination = '../img/' . $image_name_new;

            if (file_exists('../' . $data['old_gambar'])) {
              unlink('../' . $data['old_gambar']);
            }

            if (move_uploaded_file($image_tmp_name, $image_destination)) {
              $data['gambar'] = 'img/' . $image_name_new;
            } else {
              http_response_code(500);
              die('Error uploading image');
            }
          } else {
            http_response_code(500);
            die('Image size is too big');
          }
        } else {
          http_response_code(500);
          die('Error uploading image');
        }
      } else {
        http_response_code(500);
        die('Image type is not allowed');
      }
    } else {
      $data['gambar'] = $data['old_gambar'];
    }

    $query = "UPDATE buku SET judul = ?, penulis = ?, harga = ?, tahun = ?, gambar = ?, id_penerbit = ?, id_kategori = ? WHERE kode_buku = ?";

    $sql = $this->db->prepare($query);
    $sql->bind_param(
      'ssiisiii',
      $data['judul'],
      $data['penulis'],
      $data['harga'],
      $data['tahun'],
      $data['gambar'],
      $data['id_penerbit'],
      $data['id_kategori'],
      $data['kode_buku']
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
    foreach ($data as $key => $value) {
      $value = is_array($value) ? trim(implode(',', $value)) : trim($value);
      $data[$key] = strlen($value) > 0 ? $value : NULL;
    }

    $old_data = $this->db->query("SELECT gambar FROM buku WHERE kode_buku = {$data['kode_buku']}")->fetch_assoc();

    if (file_exists('../' . $old_data['gambar'])) {
      unlink('../' . $old_data['gambar']);
    }

    $query = "DELETE FROM buku WHERE kode_buku=?";
    $sql = $this->db->prepare($query);

    $sql->bind_param("i", $data['kode_buku']);

    try {
      echo $data['kode_buku'];
      $sql->execute();
    } catch (Exception $e) {
      $sql->close();
      http_response_code(500);
      die($e->getMessage());
    }

    $sql->close();
  }

  public function filter($data)
  {
    $query = "SELECT * FROM buku WHERE";

    foreach ($data as $key => $value) {
      $value = is_array($value) ? trim(implode(',', $value)) : trim($value);
      $data[$key] = strlen($value) > 0 ? $value : NULL;
    }

    $query .= " judul LIKE '%{$data['judul']}%'";

    if ($data['id_kategori'] != "") {
      $query .= " AND id_kategori = {$data['id_kategori']}";
    }

    if ($data['tahun'] != "") {
      $query .= " AND tahun = {$data['tahun']}";
    }

    if ($data['id_penerbit'] != "") {
      $query .= " AND id_penerbit = {$data['id_penerbit']}";
    }

    $query .= " ORDER BY {$data['sort']}";

    $sql = $this->db->query($query);

    $newData = [];
    while ($row = $sql->fetch_assoc()) {
      if (!file_exists('../' . $row['gambar']) || $row['gambar'] == '') {
        $row['gambar'] = "https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png";
      }
      array_push($newData, $row);
    }
    header('Content-Type: application/json');
    echo json_encode($newData);
  }
}

$buku = new Buku();
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
  case 'create':
    $buku->create($_POST);
    break;
  case 'detail':
    $buku->detail($_GET['kode_buku']);
    break;
  case 'update':
    $buku->update($_POST);
    break;
  case 'delete':
    $buku->delete($_POST);
    break;
  case 'filter':
    $buku->filter($_POST);
    break;
  default:
    $buku->read();
    break;
}
