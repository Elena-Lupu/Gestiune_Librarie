<?php
    //Operatiile de stergere al unei carti

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

        //Verific daca exista fix o singura inregistrare de sters
        $query = mysqli_prepare($con, "SELECT COUNT(Cod_Carte) FROM editura_carte WHERE Cod_Carte=?");
        mysqli_stmt_bind_param($query, "s", $nr);
        mysqli_stmt_execute($query);
        if (mysqli_num_rows(mysqli_stmt_get_result($query)) > 1) die("OOOOOOOooooooo");
        
        $query = mysqli_prepare($con, "DELETE FROM carti WHERE ID_Carte=(SELECT ID_Carte FROM editura_carte WHERE Cod_Carte=?)");
		mysqli_stmt_bind_param($query, "s", $nr);
        mysqli_stmt_execute($query);
        mysqli_close($con);

        echo "<p class='text subtitlu'>Cartea a fost ștearsă din baza de date<p><br><br>";
		echo "<button class='buton t' onclick='Reactualizare()'>Închide</button>";
    }
?>