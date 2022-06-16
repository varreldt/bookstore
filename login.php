<?php

session_start();

if (isset($_SESSION['email'])) {
  return header('Location: index.php');
}
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
  <section class="vh-100" style="background-color: #508bfc;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card shadow-2-strong" style="border-radius: 1rem;">
            <div class="card-body p-5">
              <form>
                <h3 class="mb-5 text-center">Sign in</h3>

                <div class="form-outline mb-4">
                  <label class="form-label" for="typeEmailX-2">Email</label>
                  <input type="email" name="email" id="typeEmailX-2" class="form-control form-control-lg" required />
                </div>

                <div class="form-outline mb-4">
                  <label class="form-label" for="typePasswordX-2">Password</label>
                  <input type="password" name="password" id="typePasswordX-2" class="form-control form-control-lg" required />
                </div>
                <div class="d-grid gap-2">
                  <button class="btn btn-primary" type="submit">Login</button>
                  <a href="register.php" class="btn btn-link">Register</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <!-- jquery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    $(document).ready(function() {
      $('form').submit(function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
          type: 'POST',
          url: 'model/auth.php',
          data: data,
          success: function(data) {
            alert('Login berhasil');
            window.location.href = 'index.php';
          },
          error: function(data) {
            alert('Login gagal');
          }
        });
      });
    });
  </script>
</body>

</html>