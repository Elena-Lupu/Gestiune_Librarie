<?php
    //Operatiile pentru stergerea unei edituri

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

        //Verific daca este exact o singura inregistrare care se sterge
        $query = mysqli_prepare($con, "SELECT COUNT(ID_Editura) FROM edituri WHERE ID_Editura=?");
        mysqli_stmt_bind_param($query, "s", $nr);
        mysqli_stmt_execute($query);
        if (mysqli_num_rows(mysqli_stmt_get_result($query)) > 1) die("OOOOOOOooooooo");
        
        //Odata cu stergerea unei edituri vreau sa dispara si cartile publicate de aceasta care se aflau in baza de date
        //insa trebuie sa dispara doar titlurile care apartineau strict de editura respectiva (ele trebuie sa ramana daca se regasesc la alte edituri)
        $query = mysqli_prepare($con, "DELETE FROM carti WHERE ID_Carte NOT IN (SELECT ID_Carte FROM editura_carte WHERE ID_Editura !=?)");
		mysqli_stmt_bind_param($query, "s", $nr);
        mysqli_stmt_execute($query);

        $query = mysqli_prepare($con, "DELETE FROM edituri WHERE ID_Editura=?");
		mysqli_stmt_bind_param($query, "s", $nr);
        mysqli_stmt_execute($query);
        mysqli_close($con);

        echo "<p class='text subtitlu'>Editura a fost ștearsă din baza de date<p><br><br>";
		echo "<button class='buton t' onclick=\"Reactualizare('Edituri')\">Închide</button>";
    }
?>