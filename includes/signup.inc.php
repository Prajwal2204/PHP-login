<?php
    
    if(isset($_POST['signup-submit']))
    {
        /*to check if the user entered this page thru signup.html only or not*/
        require 'dbh.inc.php'; /*access to DB*/
        
        $name = $_POST['nam'];
        $username = $_POST['uname'];
        $phoneNumber = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['pwd'];
        $passwordRepeat = $_POST['pwd-repeat'];
        
        if(empty($username) || empty($name) || empty($phoneNumber) || empty($email) || empty($password) || empty($passwordRepeat)){
            /*empty fields, show error*/
            header("Location: ../signup.html?error=emptyfields&name".$name."&uname=".$username."&phone=".$phoneNumber."&email".$email);
            exit();/*stops script from running cuz of the mistake*/
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)){
            /*both the below errors*/
            header("Location: ../signup.html?error=invalidMailUserNamel&name".$name);
            exit();
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            /*checks if its a legit email*/
            header("Location: ../signup.html?error=invalidMail&name".$name."&uname=".$username."&phone=".$phoneNumber);
            exit();
        }
        else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
            /*password can consist of the above characters*/
            header("Location: ../signup.html?error=invalidUserName&name".$name."&email".$email);
            exit();
        }
        else if($password != $passwordRepeat){
            header("Location: ../signup.html?error=passwordCheck&name".$name."&uname=".$username."&phone=".$phoneNumber."&email".$email);
            exit();
        }
        else{
            /*username already exists, NO*/
            $sql = "SELECT usernameUsers FROM users WHERE usernameUsers=?";
            $stmt = mysqli_stmt_init($conn); /*connects to db*/
            if (!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: ../signup.html?error=sqlError&name".$name."&email".$email);
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "s", $username); /*sends info to db*/
                mysqli_stmt_execute($stmt);/*runs info in the db and checks for match*/
                mysqli_stmt_store_result($stmt);
                $resultCheck = mysqli_stmt_num_rows($stmt); /*returns no. of matches*/
                if($resultCheck > 0){
                    header("Location: ../signup.html?error=UserNameExists&name".$name."&email".$email);
                    exit();
                }
                else{
                    // insert into db, succesfull login
                    $sql = "INSERT into users(nameUsers, usernameUsers, phoneUsers, emailUsers, pwdUSers) VALUES(?, ?, ?, ?, ?)"; 
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)){
                        header("Location: ../signup.html?error=sqlError&name".$name."&email".$email);
                        exit();
                    }
                    else{
                        /*password hashing*/
                        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt, "ssiss", $name, $username, $phoneNumber, $email, $hashedPwd); /*sends info to db*/
                        mysqli_stmt_execute($stmt);/*runs info in the db and checks for match*/
                        header("Location: ../signup.html?signup=success");
                        exit();
                    }
                }
            }        
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
    else{
        // accesing this page directly,
        header("Location: ../signup.html");
        exit();
    }
    
