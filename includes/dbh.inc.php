<?php

    $servername = "localhost";
    $dBUsername = "root";
    $dbpassword = "";
    $dBName = "loginsystem";
    /*connect to the database*/

    $conn = mysqli_connect($servername, $dBUsername, $dbpassword, $dBName);
    if(!$conn){
        /*failed connection*/
        die("Connection Failed!: ".mysqli_connect_error());
        /*mysqli_connect_error gets the error message received*/
    }