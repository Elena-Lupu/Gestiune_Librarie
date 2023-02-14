<?php
    //Acest cod obtine inregistrarile din $tabel si le returneaza in forma JSON
    //Este folosit pentru crearea listelor de optiuni (categorii, autori, edituri)
    //Face acelasi lucru ca functia Get_List() din Scripturi.php, dar aici poate fi accesat din JS folosind o functie AJAX

    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }

    header("Content-Type: application/json");

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        require 'Scripturi.php';
        $tabel = $_POST["tabel"];

        Strip($tabel);

        $result_arr = Get_List($tabel);
        
        echo $result_arr;
    }
?>