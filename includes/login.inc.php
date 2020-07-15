<?php

    if(isset($_POST['login-submit'])){
        require 'dbh.inc.php';

        $mailuid = $_POST['mailuid'];
        $password = $_POST['pwd'];

        if(empty($mailuid) || empty($password)){
            header("Location: ../login.html?error=emptyFields&mailuid=".$mailuid);
            exit();
        }
        else{
            $sql = "SELECT * FROM users WHERE usernameUsers = ? OR emailUsers = ?;";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: ../login.html?error=sqlError&mailuid=".$mailuid);
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    $pwdCheck = password_verify($password, $row['pwdUSers']);
                    if($pwdCheck == false){
                        header("Location: ../login.html?error=wrongPwd&mailuid=".$mailuid);
                        exit();
                    }
                    else if($pwdCheck == true){
                        session_start();
                        $_SESSION['userId'] = $row['idUsers'];
                        $_SESSION['userUsername'] = $row['usernameUsers'];
                        
                        header("Location: ../index.php?login=success");
                        exit();
                    }
                    else{
                        header("Location: ../login.html?error=wrongPwd&mailuid=".$mailuid);
                        exit();
                    }
                }
                else{
                    header("Location: ../login.html?error=noUser&mailuid=".$mailuid);
                    exit();
                }
            }
        }
    }
    else{
        header("Location: ../login.html");
        exit();
    }