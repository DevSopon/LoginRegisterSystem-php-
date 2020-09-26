<?php
include 'iic/header.php'; 
include 'libary/user.php';
Session::CheckLogin();
?>
<?php 
  $user = new User ();
  if ($_SERVER ['REQUEST_METHOD']=='POST' && isset ($_POST ['login'])) {
    $usrlogin= $user-> userLogin ($_POST);
  }
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2> User List </h2>
    </div>
    <div class="panel-body">
        <div style="max-width:450px;margin: 0 auto">
            <?php 
                if (isset ($usrlogin)) {
                echo $usrlogin;
                }
            ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email"> Email Adress </label>
                    <input type="text" id="email" name="email" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="password"> Password </label>
                    <input type="password" id="password" name="password" class="form-control" />
                </div>
                <button type="submit" name="login" class="btn btn-success">Log In</button>
            </form>
        </div>
    </div>
</div>
<?php include 'iic/footer.php'; ?>