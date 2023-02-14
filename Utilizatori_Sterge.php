<?php
    //Operatiile de stergere a unui utilizator

    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }

    if ($_SESSION["Drept"] != 'A') die("Alooooo, n-ai drepturi !!");

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $nr = $_POST["nr"];

        require 'Scripturi.php';
        Strip($nr);

        $con = Make_Con();
	    if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
        mysqli_query($con, "USE `librarie`");

        //Verific ca este fix o singura inregistrare care se sterge
        $query = mysqli_prepare($con, "SELECT COUNT(ID_Utilizator) FROM utilizatori WHERE ID_Utilizator=?");
        mysqli_stmt_bind_param($query, "s", $nr);
        mysqli_stmt_execute($query);
        if (mysqli_num_rows(mysqli_stmt_get_result($query)) > 1) die("OOOOOOOooooooo");

        $query = mysqli_prepare($con, "DELETE FROM utilizatori WHERE ID_Utilizator=?");
		mysqli_stmt_bind_param($query, "s", $nr);
        mysqli_stmt_execute($query);
        mysqli_close($con);

        echo "<p class='text subtitlu'>Utilizatorul a fost șters din baza de date<p><br><br>";
		echo "<button class='buton t' onclick=\"Reactualizare('Utilizatori')\">Închide</button>";
    }
?>