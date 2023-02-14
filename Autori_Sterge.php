<?php
    //Procedurile pentru stregere
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
        $query = mysqli_prepare($con, "SELECT COUNT(ID_Autor) FROM autori WHERE ID_Autor=?");
        mysqli_stmt_bind_param($query, "s", $nr);
        mysqli_stmt_execute($query);
        if (mysqli_num_rows(mysqli_stmt_get_result($query)) > 1) die("OOOOOOOooooooo");

        //Daca sterg autorul, dispare si inregistrarea din tabelul de legatura carte_autor, conform constrangerii de integritate cascade
        //insa titlurile din tabelul carti care apartineau de autorul respectiv nu dispar, deci trebuie intai sterse titlurile corespunzatoare
        $query = mysqli_prepare($con, "DELETE FROM carti WHERE ID_Carte IN (SELECT ID_Carte FROM carte_autor WHERE ID_Autor=?)");
		mysqli_stmt_bind_param($query, "s", $nr);
        mysqli_stmt_execute($query);
        
        $query = mysqli_prepare($con, "DELETE FROM autori WHERE ID_Autor=?");
		mysqli_stmt_bind_param($query, "s", $nr);
        mysqli_stmt_execute($query);
        mysqli_close($con);

        echo "<p class='text subtitlu'>Autorul a fost șters din baza de date<p><br><br>";
		echo "<button class='buton t' onclick=\"Reactualizare('Autori')\">Închide</button>";
    }
?>