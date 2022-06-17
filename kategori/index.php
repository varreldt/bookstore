<?php
session_start();
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <title>Kategori - BookStore</title>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-light pt-3">
    <div class="container container-fluid">
      <a class="navbar-brand" href="#">Book Store</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item pe-3">
            <a class="nav-link" href="../index.php">Home</a>
          </li>
          <?php if (isset($_SESSION['email'])) : ?>
            <li class="nav-item pe-3">
              <a class="nav-link" href="index.php" id="kategori">Kategori</a>
            </li>
            <li class="nav-item pe-3">
              <a class="nav-link" href="../penerbit">Penerbit</a>
            </li>
            <li class="nav-item pe-3">
              <a class="nav-link" href="#" id="logout">Logout</a>
            </li>
          <?php else : ?>
            <li class="nav-item pe-3">
              <a class="nav-link" href="login.php" id="login">Login</a>
            </li>
            <li class="nav-item pe-3">
              <a class="nav-link" href="register.php" id="register">Register</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <div class="row mb-3">
      <div class="col-lg-3">
        <h3>Kategori Buku</h3>
      </div>
      <div class="col-lg-9">
        <a href="form.php" class="btn btn-primary float-end"><i class="fa-solid fa-plus-circle"></i> Tambah</a>
      </div>
    </div>

    <div class="row g-5">
      <div class="col-lg">
        <div class="row">
          <table class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="table-kategori">
            </tbody>
          </table>
          <div id="pagination"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <!-- jquery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    function hapus(id) {
      $.ajax({
        url: '../model/kategori.php?action=delete',
        type: 'POST',
        data: {
          id: id
        },
        success: function(data) {
          getCategory(1);
        }
      });
    }

    getCategory(1);

    function getCategory(page) {
      $('#table-kategori').html('');

      $.ajax({
        url: '../model/kategori.php?page=' + page,
        type: 'GET',
        success: function(data) {
          var no = page * 4 - 3;

          $.each(data.kategori, function(i, item) {
            $('#table-kategori').append(
              '<tr>' +
              '<td>' + (no + i) + '</td>' +
              '<td>' + item.nama + '</td>' +
              '<td>' +
              '<a href="form.php?action=update&id=' + item.id + '" class="btn btn-primary btn-sm me-3"><i class="fa-solid fa-edit"></i></a>' +
              '<a href="javascript:hapus(' + item.id + ')" class="btn btn-danger btn-sm me-3"><i class="fa-solid fa-trash"></i></a>' +
              '</td>' +
              '</tr>'
            );
          });

          $('#pagination').html('');
          var totalPage = Math.ceil(data.total_rows / 4);
          for (let i = 1; i <= totalPage; i++) {
            $('#pagination').append(
              '<a href="javascript:getCategory(' + i + ')" class="btn btn-outline-primary me-3">' + (i) + '</a>'
            );
          }

          $('#pagination a').removeClass('active');
          $('#pagination a').eq(page - 1).addClass('active');
        }
      });
    }

    $('#logout').on('click', function() {
      $.ajax({
        url: '../model/auth.php?action=logout',
        type: 'POST',
        success: function(data) {
          alert('Logout Berhasil');
          window.location.href = '../login.php';
        }
      });
    });
  </script>
</body>

</html>