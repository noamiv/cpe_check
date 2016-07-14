<?php
/*
  UserPie Version: 1.0
  http://userpie.com


 */
require_once("models/config.php");

/*
 * Check for login
 
if (!isset($loggedInUser)) {
    header('Location: login.php');
    exit();
}
*/
?>


<?php
/*
  Below is a very simple example of how to process a new user.
  Some simple validation (ideally more is needed).

  The first goal is to check for empty / null data, to reduce workload here we let the user class perform it's own internal checks, just in case they are missed.
 */

//Forms posted
if (!empty($_POST)) {
    $errors = array();
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_pass = trim($_POST["passwordc"]);

    //Perform some validation
    //Feel free to edit / change as required

    if (minMaxRange(5, 25, $username)) {
        $errors[] = lang("ACCOUNT_USER_CHAR_LIMIT", array(5, 25));
    }
    if (minMaxRange(8, 50, $password) && minMaxRange(8, 50, $confirm_pass)) {
        $errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT", array(8, 50));
    } else if ($password != $confirm_pass) {
        $errors[] = lang("ACCOUNT_PASS_MISMATCH");
    }
    /* if(!isValidemail($email))
      {
      $errors[] = lang("ACCOUNT_INVALID_EMAIL");
      }

     */
    //End data validation
    if (count($errors) == 0) {
        //Construct a user object
        $user = new User($username, $password, $email);

        //Checking this flag tells us whether there were any errors such as possible data duplication occured
        if (!$user->status) {
            if ($user->username_taken)
                $errors[] = lang("ACCOUNT_USERNAME_IN_USE", array($username));
            if ($user->email_taken)
                $errors[] = lang("ACCOUNT_EMAIL_IN_USE", array($email));
        }
        else {
            //Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
            if (!$user->userPieAddUser()) {
                if ($user->mail_failure)
                    $errors[] = lang("MAIL_ERROR");
                if ($user->sql_failure)
                    $errors[] = lang("SQL_ERROR");
            }
        }
    }
    if (count($errors) == 0) {
        if ($emailActivation) {
            $message = lang("ACCOUNT_REGISTRATION_COMPLETE_TYPE2");
        } else {
            $message = lang("ACCOUNT_REGISTRATION_COMPLETE_TYPE1",array($username));
        }
    } else {
        $message = '<span style="color: red;">' . implode(", ", $errors) . '</span>';
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Register New User </title>
        <script src="js/jquery-2.1.4.min.js"></script>
        <script src="assets/js/jquery.validate.min.js"></script>
        <link rel="stylesheet" href="assets/css/forms.css"/>
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/font-awesome.min.css"/>        
    </head>
    <body>
        <div class="modal-ish">
            <div class="modal-header">
                <h2>Register New User</h2>
            </div>

            <div id="success">
                <p><?php if (isset($message))
    echo $message; ?>
                </p>
            </div>

            <div id="regbox">
                <form name="newUser"  class="form-horizontal" role="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="username">User name:</label>
                        <div class="col-sm-2 controls">
                            <input type="text" class="form-control input-xlarge" name="username" id="username"/>
                        </div>
                    </div>                          

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="password">Password:</label>
                        <div class="col-sm-2 controls">
                            <input type="password" class="form-control input-xlarge" name="password" id="password"/>
                        </div>
                    </div>      

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="passwordc">Re-type Password:</label>
                        <div class="col-sm-2 controls">
                            <input type="password" class="form-control input-xlarge" name="passwordc" id="passwordc"/>
                        </div>
                    </div>  

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Email:</label>
                        <div class="col-sm-2 controls">
                            <input type="test" class="form-control input-xlarge" name="email" id="email"/>
                        </div>
                    </div>  


                    <div class="form-actions"> 
                        <div class="col-sm-offset-2 col-sm-2">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </div> 

                </form>
            </div>

        </div>
    </body>
</html>
