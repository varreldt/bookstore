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
  <nav class="navbar navbar-expand-lg bg-light mt-3">
    <div class="container container-fluid">
      <a class="navbar-brand" href="#">Book Store</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item pe-5">
            <a class="nav-link" href="#">Home</a>
          </li>
          <li class="nav-item pe-5">
            <a class="nav-link" href="#">Book</a>
          </li>
          <li class="nav-item pe-5">
            <a class="nav-link" href="#">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <div class="row mb-3">
      <div class="col-lg-3">
        <h3>Popular Book</h3>
      </div>
      <div class="col-lg-9">
        <a href="form.php" class="btn btn-primary float-end"><i class="fa-solid fa-plus-circle"></i> Create</a>
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
            '<a href="#" class="card-title text-decoration-none">' + item.judul + '</a>' +
            '<div class="card-text">' +
            '<div class="row">' +
            '<div class="col-lg-5">' +
            '<span>Penerbit: ' + item.penerbit + '</span>' +
            '</div>' +
            '<div class="col-lg-7">' +
            '<a href="form.php?action=update&kode_buku=' + item.kode_buku + '" class="btn btn-sm btn-primary float-end ms-1"><i class="fa-solid fa-pen-to-square"></i></a>' +
            '<a href="#" class="btn btn-sm btn-danger float-end ms-1" onclick="hapus(' + item.kode_buku + ')"><i class="fa-solid fa-trash-alt"></i></a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>');
        });
        page += 8;

        $('#btnLoad').html('<i class="fa-solid fa-check-circle"></i> Load More...');
      });
    }

    $('#btnLoad').on('click', function() {
      get();
    }).trigger('click');
  </script>
</body>

</html>