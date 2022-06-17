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

  <title>BookStore</title>
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
            <a class="nav-link" href="#">Home</a>
          </li>
          <?php if (isset($_SESSION['email'])) : ?>
            <li class="nav-item pe-3">
              <a class="nav-link" href="kategori/index.php">Kategori</a>
            </li>
            <li class="nav-item pe-3">
              <a class="nav-link" href="penerbit/index.php">Penerbit</a>
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
    <div class="row mb-5">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4><i class="fas fa-filter"></i> Filters</h4>
          </div>
          <div class="card-body">
            <form>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="id_kategori">Kategori</label>
                    <select class="form-control" id="id_kategori" name="id_kategori">
                      <option value="" selected>-</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="id_penerbit">Penerbit</label>
                    <select class="form-control" id="id_penerbit" name="id_penerbit">
                      <option value="" selected>-</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="tahun">Tahun</label>
                    <select class="form-control" id="tahun" name="tahun">
                      <option value="">All</option>
                      <option value="2022">2022</option>
                      <option value="2021">2021</option>
                      <option value="2020">2020</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group mt-2">
                <label for="judul"><i class="fas fa-search"></i> Judul</label>
                <input type="text" class="form-control" name="judul" id="judul" placeholder="judul">
              </div>
              <div class="form-group mt-3">
                <label for="sort"><i class="fas fa-clock"></i> Urut Berdasarkan</label>
                <select class="form-control" name="sort" id="sort">
                  <option value="judul ASC" selected>Judul Ascending</option>
                  <option value="judul DESC">Judul Descending</option>
                  <option value="tahun ASC">Tahun Ascending</option>
                  <option value="tahun DESC">Tahun Descending</option>
                </select>
              </div>
              <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary" id="btn-filter">Filter</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-lg-3">
        <h3>Popular Book</h3>
      </div>
      <div class="col-lg-9">
        <?php if (isset($_SESSION['email'])) : ?>
          <a href="form.php" class="btn btn-primary float-end"><i class="fa-solid fa-plus-circle"></i> Create</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="row g-5">
      <div class="col-lg">
        <div class="row" id="buku">
        </div>

        <div class="row" id="pagination">
          <button type="button" class="btn btn-primary" id="btnLoad"><i class="fa-solid fa-check-circle"></i></button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <!-- jquery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    let page = 0;
    let keranjang = [];

    function setKeranjang(kode_buku) {
      if (keranjang.includes(kode_buku)) {
        keranjang.splice(keranjang.indexOf(kode_buku), 1);
      } else {
        keranjang.push(kode_buku);
      }
      $('#keranjang').text('(' + keranjang.length + ')');
    }

    function hapus(id) {
      $.ajax({
        url: 'model/buku.php?action=delete',
        type: 'POST',
        data: {
          kode_buku: id
        },
        success: function(data) {
          $('#buku').html('');
          page = 0;
          get();
        }
      });
    }

    function get() {
      $.get('model/buku.php?page=' + page, function(data) {
        $.each(data, function(i, item) {
          $('#buku').append('<div class="col-lg-3 mb-3">' +
            '<div class="card">' +
            '<a href="#">' +
            '<img src="' + item.gambar + '" class="card-img-top" alt="' + item.judul + '">' +
            '</a>' +
            '<div class="card-body">' +
            '<a href="#" class="card-title text-decoration-none text-center">' + item.judul + '</a>' +
            '<div class="card-text">' +
            '<p>Penulis: ' + item.penulis + '</p>' +
            <?php if (isset($_SESSION['email'])) : ?> '<a href="form.php?action=update&kode_buku=' + item.kode_buku + '" class="btn btn-primary btn-sm me-2 float-right"><i class="fa-solid fa-edit"></i></a>' +
              '<a href="#" onclick="hapus(' + item.kode_buku + ')" class="btn btn-danger btn-sm float-right"><i class="fa-solid fa-trash"></i></a>' +
            <?php endif; ?> '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>');
        });
        page += 4;

        $('#btnLoad').html('<i class="fa-solid fa-check-circle"></i> Load More...');
      });
    }

    function filter(data) {
      $.each(data, function(i, item) {
        $('#buku').append('<div class="col-lg-3 mb-3">' +
          '<div class="card">' +
          '<a href="#">' +
          '<img src="' + item.gambar + '" class="card-img-top" alt="' + item.judul + '">' +
          '</a>' +
          '<div class="card-body">' +
          '<a href="#" class="card-title text-decoration-none text-center">' + item.judul + '</a>' +
          '<div class="card-text">' +
          '<p>Penulis: ' + item.penulis + '</p>' +
          <?php if (isset($_SESSION['email'])) : ?> '<a href="form.php?kode_buku=' + item.kode_buku + '" class="btn btn-primary btn-sm me-2 float-right"><i class="fa-solid fa-edit"></i></a>' +
            '<a href="#" onclick="hapus(' + item.kode_buku + ')" class="btn btn-danger btn-sm float-right"><i class="fa-solid fa-trash"></i></a>' +
          <?php endif; ?> '</div>' +
          '</div>' +
          '</div>' +
          '</div>' +
          '</div>' +
          '</div>');
      });
    }

    $.get('model/penerbit.php?action=getAll', function(data) {
      $.each(data, function(key, value) {
        $('#id_penerbit').append('<option value="' + value.id + '">' + value.nama + '</option>');
      });
    });

    $.get('model/kategori.php?action=getAll', function(data) {
      $.each(data, function(key, value) {
        $('#id_kategori').append('<option value="' + value.id + '">' + value.nama + '</option>');
      });
    });

    $('#btnLoad').on('click', function() {
      get();
    }).trigger('click');

    $('#logout').on('click', function() {
      $.ajax({
        url: 'model/auth.php?action=logout',
        type: 'POST',
        success: function(data) {
          alert('Logout Berhasil');
          window.location.href = 'login.php';
        }
      });
    });

    $('form').on('submit', function(e) {
      e.preventDefault();
      var data = $(this).serialize();

      $.ajax({
        url: 'model/buku.php?action=filter',
        type: 'POST',
        data: data,
        success: function(data) {
          $('#buku').html('');
          filter(data);
        }
      });
    });

    $('#transaksi').on('click', function() {
      window.location.href = 'transaksi.php';
    });
  </script>
</body>

</html>