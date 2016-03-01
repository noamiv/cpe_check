<?php
/*
  UserPie
  http://userpie.com


 */
require_once("models/config.php");

//Prevent the user visiting the logged in page if he/she is already logged in
if (isUserLoggedIn()) {
    header("Location: index.php");
    die();
}
?>
<?php
/*
  Below is a very simple example of how to process a login request.
  Some simple validation (ideally more is needed).
 */

//Forms posted
if (!empty($_POST)) {
    $errors = array();
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $remember_choice = trim($_POST["remember_me"]);

    //Perform some validation
    //Feel free to edit / change as required
    if ($username == "") {
        $errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
    }
    if ($password == "") {
        $errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
    }

    //End data validation
    if (count($errors) == 0) {
        //A security note here, never tell the user which credential was incorrect
        if (!usernameExists($username)) {
            $errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
        } else {
            $userdetails = fetchUserDetails($username);

            //See if the user's account is activation
            if ($userdetails["active"] == 0) {
                $errors[] = lang("ACCOUNT_INACTIVE");
            } else {
                //Hash the password and use the salt from the database to compare the password.
                $entered_pass = generateHash($password, $userdetails["password"]);

                if ($entered_pass != $userdetails["password"]) {
                    //Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
                    $errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
                } else {
                    //passwords match! we're good to go'
                    //Construct a new logged in user object
                    //Transfer some db data to the session object
                    $loggedInUser = new loggedInUser();
                    $loggedInUser->email = $userdetails["email"];
                    $loggedInUser->user_id = $userdetails["user_id"];
                    $loggedInUser->hash_pw = $userdetails["password"];
                    $loggedInUser->display_username = $userdetails["username"];
                    $loggedInUser->clean_username = $userdetails["username_clean"];
                    $loggedInUser->remember_me = $remember_choice;
                    $loggedInUser->remember_me_sessid = generateHash(uniqid(rand(), true));

                    //Update last sign in
                    $loggedInUser->updatelast_sign_in();

                    if ($loggedInUser->remember_me == 0)
                        $_SESSION["userPieUser"] = $loggedInUser;
                    else if ($loggedInUser->remember_me == 1) {
                        $db->sql_query("INSERT INTO " . $db_table_prefix . "sessions VALUES('" . time() . "', '" . serialize($loggedInUser) . "', '" . $loggedInUser->remember_me_sessid . "')");
                        setcookie("userPieUser", $loggedInUser->remember_me_sessid, time() + parseLength($remember_me_length));
                    }

                    //Redirect to user account page
                    header("Location: index.php");
                    die();
                }
            }
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Login | <?php echo $websiteName; ?> </title>
        <link href="assets/css/login_style.css" rel="stylesheet" type="text/css"/>
        <?php require_once("head_inc.php"); ?>
        
    </head>
    <body  class="login" style="display: block;">
        <script>
            if (self == top) {
                var theBody = document.getElementsByTagName('body')[0]
                theBody.style.display = "block"
            } else {
                top.location = self.location
            }
        </script>       

        <?php
        if (($_GET['status']) == "success") {

            echo "<p>Your account was created successfully. Please login.</p>";
        }
        ?>


        <form name="newUser" class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="top-image"></div>
            <fieldset class="login">
                <legend><span></span></legend>
                <div id="banner" class="banner"><p>
                        <?php
                        if (!empty($_POST)) {
                            if (count($errors) > 0) {
                                ?>
                                <div class="errors">
                                    <?php errorBlock($errors); ?>
                                </div>     
                                <?php
                            }
                        }
                        ?>            

                    </p></div>
                <div id="banner" class="banner"><p><i></i></p></div>
                <ol>
                    <li>
                        <label for="username" class="fieldLabel">Username</label>
                        <input name="username" size="22" type="text" autocomplete="off" tabindex="1"/>
                    </li>
                    <li>
                        <label for="password" class="fieldLabel">Password</label>
                        <input name="password" autocomplete="off"  size="10" type="password" tabindex="2"/>
                    </li>
                    <li>	    
                        <input type="submit" class="login-button" name="new" id="newfeedform" tabindex="3" value="Login" />                         
                    </li>
                    <li>                        
                        <input type="checkbox" name="remember_me" value="1" />	
                        <label><small>Remember Me?</small></label>
                    </li>  
                </ol>
            </fieldset>
            <div class="bottom-image"></div>
        </form>
        <div class="clear"></div>
        <p style="margin-top:30px; text-align:center;">
            <a href="register.php">Create New User</a> 
        </p>


    </body>
</html>


