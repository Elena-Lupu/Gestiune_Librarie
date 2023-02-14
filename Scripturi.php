<?php
    function Make_Con() { return mysqli_connect("localhost", "root", "PufuleatzaZzDuP27!*"); }

    function LogOut()
    {
        session_start();
        header("Location: LogIn.php");
        session_destroy();
    }

    function Strip($item) //Functie pentru "Curatarea" diverselor elemente venite prin forme de la client. Previne atacuri tip XSS
    {
        $item = trim($item);
		$item = stripslashes($item);
	    $item = htmlspecialchars($item);
    }

    function Get_List($tabel)   //Obtine o lista de elemente dintr-un tabel specificat. Folosit pentru crearea de liste dinamice
    {
        $con = Make_Con();
	    if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
        mysqli_query($con, "USE `librarie`");

        switch ($tabel)
        {
            case 'Editura':
                $result = mysqli_query($con, "SELECT ID_Editura, Nume FROM edituri");
                while ($row = mysqli_fetch_assoc($result)) $result_arr[] = array("id"=>$row["ID_Editura"], "nume"=>$row["Nume"]);
                break;
            case 'Categorie':                
                $result = mysqli_query($con, "SELECT * FROM categorii");
                while ($row = mysqli_fetch_assoc($result)) $result_arr[] = array("id"=>$row["ID_Categorie"], "nume"=>$row["Nume"]);
                break;
            case 'Autor':
                $result = mysqli_query($con, "SELECT ID_Autor, CONCAT(Prenume, ' ', Nume) AS Nume FROM autori");
                while ($row = mysqli_fetch_assoc($result)) $result_arr[] = array("id"=>$row["ID_Autor"], "nume"=>$row["Nume"]);
                break;
            default: die("OOOOoooo");
        }

        mysqli_close($con);

        return json_encode($result_arr);
    }

    if (isset($_GET["f"]))
        switch($_GET['f'])
        {
            case 'LogOut' : LogOut(); break;
        }
?>