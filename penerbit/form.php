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

  <title>Form Penerbit BookStore</title>
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
              <a class="nav-link" href="../kategori" id="kategori">Kategori</a>
            </li>
            <li class="nav-item pe-3">
              <a class="nav-link" href="index.php" id="penerbit">Penerbit</a>
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
    <h1 id="header">Tambah Penerbit</h1>
    <form>
      <input type="hidden" id="id" name="id" value="0">

      <div class="row mb-3">
        <label for="nama" class="col-sm-2 col-form-label">Nama *</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
      </div>
      <div class="row mb-3">
        <label for="alamat" class="col-sm-2 col-form-label">Alamat *</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="alamat" name="alamat" required>
        </div>
      </div>
      <div class="row mb-3">
        <div class="offset-sm-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="index.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
          </a>
        </div>
      </div>
    </form>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <!-- jquery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    $(document).ready(function() {
      var params = window.location.search.substring(1).split('&');
      for (var i = 0; i < params.length; i++) {
        params[i] = params[i].split('=');
      }

      if (params[0][0] == "action" && params[0][1] == "update") {
        $("#header").html("Update Penerbit");
        $.get("../model/penerbit.php?action=detail&id=" + params[1][1], function(data) {
          $('#id').val(data.id);
          $('#nama').val(data.nama);
          $('#alamat').val(data.alamat);
        });
      }

      $("form").submit(function(e) {
        e.preventDefault();
        var data = $(this).serialize();

        if (params[0][0] == "action" && params[0][1] == "update") {
          $.ajax({
            url: '../model/penerbit.php?action=update',
            type: 'POST',
            data: data,
            success: function(data) {
              alert("Data penerbit berhasil diubah");
              window.location.href = "index.php";
            },
            error: function(data) {
              alert("Data penerbit gagal diubah");
            },
          });
        } else {
          $.ajax({
            url: "../model/penerbit.php?action=create",
            type: "POST",
            data: data,
            success: function(data) {
              alert("Data penerbit berhasil ditambahkan");
              window.location.href = "index.php";
            },
            error: function(data) {
              alert("Data penerbit gagal ditambahkan");
            },
          });
        }
      });

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
    });
  </script>
</body>

</html>