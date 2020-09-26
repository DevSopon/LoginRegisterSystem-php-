<?php
include_once 'session.php';
include 'database.php';

class User{
     private $db;
     public function __construct(){
            $this->db = new Database ();     
     }
    public function userRegistration ($data) {
            $name = $data ['name'];
            $username = $data ['username'];
            $email = $data ['email'];
            $password = $data ['password'];
            $chk_email = $this->emailcheck ($email);

            if ($name== "" OR $username== "" OR  $email== "" OR  $password== "") {
            $msg= "<div class='alert alert-danger'> <strong> ERROR! </strong> Frild Must Not Be Empty </div>";
            return $msg;
 
        }
         
        if (strlen ($name) < 3 ) {
           $msg= "<div class='alert alert-danger'> <strong> ERROR! </strong> Username is too short </div>";
            return $msg; 
        }
        else if ( preg_match('/[^a-z0-9._-]+/i' ,$username)) {
            $msg= "<div class='alert alert-danger'> <strong> ERROR! </strong> Username must be alphanumerical,dash  and underscore n </div>";
            
        }
        if (filter_var ($email, FILTER_VALIDATE_EMAIL)=== false) {
           $msg= "<div class='alert alert-danger'> <strong> ERROR! </strong> the email is incorrect </div>";
            return $msg; 
        }
        if ($chk_email == true) {
           $msg= "<div class='alert alert-danger'> <strong> ERROR! </strong> the email is exists </div>";
            return $msg; 
        }
         $password = md5($data ['password']);
         $sql = "INSERT INTO tbl_user (name,username,email,password) VALUES (:name, :username, :email, :password)";
         $query = $this->db->pdo->prepare($sql);
         $query-> bindValue (':name', $name);
         $query-> bindValue (':username', $username);
         $query-> bindValue (':email', $email);
         $query-> bindValue (':password', $password);
         $result = $query->execute ();
         if ($result) {
             $msg= "<div class='alert alert-success'> <strong> Success! </strong> You've successgfully registered </div>";
            return $msg;
         }
         else {
            $msg= "<div class='alert alert-danger'> <strong> failed! </strong> Success </div>";
            return $msg;
         }

    }
    public function emailcheck($email) {
         $sql = "SELECT email FROM tbl_user WHERE email = :email";
         $query = $this->db->pdo->prepare ($sql);
         $query-> bindValue (':email', $email);
         $query->execute ();
         if ($query->rowcount () > 0) 
         {
             return true;
         }
         else 
         {
            return false;
         }
    }  
        

    public function getLoginUser ($email,$password) {
            $sql = "SELECT * FROM tbl_user WHERE email = :email AND password = :password LIMIT 1";
            $query = $this->db->pdo->prepare ($sql);
            $query-> bindValue (':email', $email);
            $query-> bindValue (':password', $password);
            $query->execute ();
            $result= $query->fetch (PDO::FETCH_OBJ);
            return $result;
    }

    public function userLogin ($data) {
            $email = $data ['email'];
            $password = md5($data ['password']);
            $chk_email = $this->emailcheck ($email);

            if ($email== "" OR  $password== "") {
            $msg= "<div class='alert alert-danger'> <strong> ERROR! </strong> Frild Must Not Be Empty </div>";
            return $msg;
            }
            
            if (filter_var ($email, FILTER_VALIDATE_EMAIL)=== false) {
            $msg= "<div class='alert alert-danger'> <strong> ERROR! </strong> the email is incorrect </div>";
            return $msg; 
            }
            
            if ($chk_email == false) {
            $msg= "<div class='alert alert-danger'> <strong> ERROR! </strong> the email isn't exists </div>";
            return $msg; 
            }
            
            $result = $this->getLoginUser ($email,$password);
            if ($result) {
                Session::init();
                Session::set("login", true);
                Session::set("id", $result->id);
                Session::set("name", $result->name);
                Session::set("username", $result->username);
                Session::set("loginmsg", "<div class='alert alert-success'> <strong> Success </strong>  You're logged in </div>");
                header ("Location:index.php");
            }
            else {
               $msg= "<div class='alert alert-danger'> <strong> ERROR! </strong>Data not found </div>";
            return $msg; 
            }
    } 
    public function getUserData()
    {
            $sql = "SELECT * FROM tbl_user ORDER BY id ASC";
            $query = $this->db->pdo->prepare ($sql);
            $query->execute();
            $result = $query->fetchAll();
            return $result;
    }

    public function getUserbyID($userid)
         {
            $sql = "SELECT * FROM tbl_user WHERE id= :id LIMIT 1";
            $query = $this->db->pdo->prepare ($sql);
            $query-> bindValue (':id', $userid);
            $query->execute ();
            $result= $query->fetch (PDO::FETCH_OBJ);
            return $result;
    }
    public function updateUserData($id, $data) {
            $name = $data ['name'];
            $username = $data ['username'];
            $email = $data ['email'];

            if ($name== "" OR $username== "" OR  $email== "") {
            $msg= "<div class='alert alert-danger'> <strong> ERROR! </strong> Frild Must Not Be Empty </div>";
            return $msg;
 
        }
         $sql = "UPDATE tbl_user set
            name=:name,
            username= :username,
            email= :email
            WHERE id=:id";
         $query = $this->db->pdo->prepare($sql);
         $query-> bindValue (':name', $name);
         $query-> bindValue (':username', $username);
         $query-> bindValue (':email', $email);
         $query-> bindValue (':id', $id);
         $result = $query->execute ();
         if ($result) {
             $msg= "<div class='alert alert-success'> <strong> Success! </strong> User Data Updated Successfully </div>";
            return $msg;
         }
         else {
            $msg= "<div class='alert alert-danger'> <strong> failed! </strong> User data Not updated </div>";
            return $msg;
         }
    }
    private function checkPassword($old_pass, $id)
    {
        $password = md5($old_pass);
        $sql = "SELECT password FROM tbl_user WHERE id = :id AND  password =:password";
        $query = $this->db->pdo->prepare ($sql);
        $query-> bindValue (':id', $id);
        $query-> bindValue (':password', $password);
        $query->execute ();
                if ($query->rowcount () > 0) 
            {
                return true;
            }
         else 
         {
            return false;
         }
    }
    private function checkPass ($id, $old_pass)
    {
        $password = md5($old_pass);
        $sql = "SELECT password FROM tbl_user WHERE id = :id AND password= :password";
         $query = $this->db->pdo->prepare ($sql);
         $query-> bindValue (':id', $id);
         $query-> bindValue (':password', $password);
         $query->execute ();
         if ($query->rowcount () > 0) 
         {
             return true;
         }
         else 
         {
            return false;
         }
    }
    public function updateUserPasss($id, $data) {
            $old_pass = $data ['old_pass'];
            $new_pass = $data ['password'];
            $chk_pass = $this->checkPassword($id, $old_pass);
            if ($old_pass == "" OR $new_pass == "")
            {
                $msg= "<div class='alert alert-danger'> <strong> Error! </strong>Field Must not be empty</div>";
                return $msg;
            }
            if ($chk_pass==false) {
                        $msg= "<div class='alert alert-danger'> <strong> Error! </strong> Old password not exists </div>";
                return $msg;
            }
            if (strlen ($new_pass) < 6 ) {
             $msg= "<div class='alert alert-danger'> <strong> ERROR! </strong> Password is too short </div>";
            return $msg; 
            }
            $password = md5($new_pass);
            $sql = "UPDATE tbl_user set
            password=:password
            WHERE id=:id";
            $query = $this->db->pdo->prepare($sql);
            $query-> bindValue (':password', $password);
            $query-> bindValue (':id', $id);
            $result = $query->execute ();
            if ($result) {
             $msg= "<div class='alert alert-success'> <strong> Success! </strong> Password Updated Successfully </div>";
                     return $msg;
             }
            else {
            $msg= "<div class='alert alert-danger'> <strong> failed! </strong>Password Not updated </div>";
                    return $msg;
         }     
         
    }
    
}  
?>
