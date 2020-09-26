<?php
include 'libary/user.php';
include 'iic/header.php';
Session::CheckSession();
?>
<?php
  if (isset($_GET['id'])) {
      $userid = (int)$_GET['id'];
  }
  $user = new User();
  if ($_SERVER ['REQUEST_METHOD']=='POST' && isset ($_POST ['update'])) {
    $updateusr= $user->updateUserData($userid,$_POST);
  }
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2> User Profile
            <span class="pull-right"><a class="btn btn-primary" href="index.php"> Back </a></span>
        </h2>
    </div>
    <div class="panel-body">
        <div style="max-width:450px;margin: 0 auto">
            <?php
                if (isset($updateusr)) {
                    echo $updateusr;
                }

            ?>
            <?php
                $userdata = $user->getUserbyID($userid);
                    if ($userdata) {
                              
                ?>
            <form method="POST">
                <div class="form-group">
                    <label for="name"> Your Name </label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $userdata->name; ?>" />
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo $userdata->username; ?>" />
                </div>
                <div class="form-group">
                    <label for="email">Email Adress </label>
                    <input type="text" id="email" name="email" class="form-control" value="<?php echo $userdata->email; ?>" />
                </div>
                <?php
                    $sesId = Session::get("id");
                    if ($userid == $sesId) {
                ?>
                <button type="submit" name="update" class="btn btn-success">Update</button>
                <a class="btn btn-primary" href="changepass.php?id=<?php  echo $userid; ?>">Password Change </a>
                <?php } ?>
            </form>
            <?php } ?>
        </div>
    </div>
</div>
<?php include 'iic/footer.php'; ?>