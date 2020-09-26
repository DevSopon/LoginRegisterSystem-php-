<?php
include 'libary/user.php';
include 'iic/header.php';
Session::CheckSession();
?>

<?php
$loginmsg = Session::get ("loginmsg");
if (isset ($loginmsg)) {
      echo $loginmsg;
}

   Session::set ("loginmsg", NULL);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2> User List
            <span class="pull-right"> <strong> Welcome! </strong>
                <?php 
                    $name = Session:: get ("name");
                        if (isset ($name)) {
                            echo $name;
                        }
                ?>
            </span>
        </h2>
    </div>
    <div class="panel-body">
        <table class="table table-striped">
            <th width="10%"> Serial No.</th>
            <th width="20%"> Name.</th>
            <th width="20%"> Username. </th>
            <th width="20%">Email Adress </th>
            <th width="15%"> Action </th>
            
      <?php
            $user = new User();
            $userData = $user->getuserData();
            if ($userData) {
                  $i= 0;
                  foreach ($userData as $usrdata) {
                        $i++;
      ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $usrdata['name']; ?></td>
                <td><?php echo $usrdata['username']; ?></td>
                <td><?php echo $usrdata['email']; ?></td>
                <td><a class="btn btn-primary" href="profile.php?id=<?php echo $usrdata['id'] ?>">View</a></td>
                
            </tr>

      <?php }}  else { ?>
            <tr>
                  <td colspan="5"><h2>No User data Found</h2></td>
            </tr>
      <?php } ?>
        </table>
    </div>
</div>
<?php include 'iic/footer.php'; ?>









