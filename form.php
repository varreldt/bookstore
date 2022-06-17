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

  <div class="container py-5">
    <h1 id="header">Tambah Buku</h1>
    <form enctype="multipart/form-data">
      <input type="hidden" id="kode_buku" name="kode_buku" value="0">
      <input type="hidden" id="old_gambar" name="old_gambar" value="0">

      <div class="row mb-3">
        <label for="judul" class="col-sm-2 col-form-label">Judul *</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="judul" name="judul" required>
        </div>
      </div>
      <div class="row mb-3">
        <label for="gambar" class="col-sm-2 col-form-label">Gambar</label>
        <div class="col-sm-10">
          <input type="file" class="form-control-file" id="gambar" name="gambar" />

          <div class="row mt-3">
            <div class="col-sm-12">
              <img class="img-fluid" id="image_preview">
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <label for="penulis" class="col-sm-2 col-form-label">Penulis *</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="penulis" name="penulis" required>
        </div>
      </div>
      <div class="row mb-3">
        <label for="harga" class="col-sm-2 col-form-label">Harga *</label>
        <div class="col-sm-10">
          <input type="number" class="form-control" id="harga" name="harga" required>
        </div>
      </div>
      <div class="row mb-3">
        <label for="tahun" class="col-sm-2 col-form-label">Tahun *</label>
        <div class="col-sm-10">
          <input type="number" class="form-control" id="tahun" name="tahun" required>
        </div>
      </div>
      <div class="row mb-3">
        <label for="id_penerbit" class="col-sm-2 col-form-label">Penerbit *</label>
        <div class="col-sm-10">
          <select class="form-control" id="id_penerbit" name="id_penerbit" required>
            <option value="" selected>-</option>
          </select>
        </div>
      </div>
      <div class="row mb-3">
        <label for="id_kategori" class="col-sm-2 col-form-label">Kategori *</label>
        <div class="col-sm-10">
          <select class="form-control" id="id_kategori" name="id_kategori" required>
            <option value="" selected>-</option>
          </select>
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
      $.get('model/kategori.php?action=getAll', function(data) {
        $.each(data, function(key, value) {
          $('#id_kategori').append('<option value="' + value.id + '">' + value.nama + '</option>');
        });
      });

      $.get('model/penerbit.php?action=getAll', function(data) {
        $.each(data, function(key, value) {
          $('#id_penerbit').append('<option value="' + value.id + '">' + value.nama + '</option>');
        });

        var params = window.location.search.substring(1).split('&');
        for (var i = 0; i < params.length; i++) {
          params[i] = params[i].split('=');
        }

        if (params[0][0] == "action" && params[0][1] == "update") {
          $("#header").html("Update Buku");
          $.get("model/buku.php?action=detail&kode_buku=" + params[1][1], function(data) {
            $('#kode_buku').val(data.kode_buku);
            $('#judul').val(data.judul);
            $('#old_gambar').val(data.gambar);
            if (data.gambar != "") {
              $('#image_preview').attr('src', data.gambar);
            }
            $('#penulis').val(data.penulis);
            $('#harga').val(data.harga);
            $('#tahun').val(data.tahun);
            $('#id_penerbit').val(data.id_penerbit);
            $('#id_kategori').val(data.id_kategori);
          });
        }

        $('#gambar').change(function() {
          if (this.files[0].type == 'image/jpeg' || this.files[0].type == 'image/png' || this.files[0].type == 'image/jpg') {
            var reader = new FileReader();
            reader.onload = function(e) {
              $('#image_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
          } else {
            alert('Please upload a jpg, jpeg or png file');
            $('#gambar').val('');
          }
        });

        $("form").submit(function(e) {
          e.preventDefault();
          var formData = new FormData(this);

          if (params[0][0] == "action" && params[0][1] == "update") {

            $.ajax({
              url: 'model/buku.php?action=update',
              type: 'POST',
              data: formData,
              success: function(data) {
                alert("Data buku berhasil diubah");
                window.location.href = "index.php";
              },
              error: function(data) {
                alert("Data buku gagal diubah");
              },
              cache: false,
              contentType: false,
              processData: false
            });
          } else {
            $.ajax({
              url: "model/buku.php?action=create",
              type: "POST",
              data: formData,
              success: function(data) {
                alert("Data buku berhasil ditambahkan");
                window.location.href = "index.php";
              },
              error: function(data) {
                alert("Data buku gagal ditambahkan");
              },
              cache: false,
              contentType: false,
              processData: false
            });
          }
        });
      });
    });
  </script>
</body>

</html>