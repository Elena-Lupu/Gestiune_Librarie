<?php
    //Aici se executa operatiile pentru a obtine setul de date pentru tabelul din pagina "Gestioneaza autorii"

    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }

    header("Content-Type: application/json");

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        require 'Scripturi.php';

        $pg = $_POST["pg"]; //pagina pe care doresc s-o afisez
        $search_bar = $_POST["search_bar"]; //ce este scris de utilizator in search-bar

        Strip($search_bar);
        if (strlen($search_bar) > 100) die("OOOOOooooooo");
        $search_bar = "%" . $search_bar . "%";

        //$e_p reprezinta nr de elemente prezente pe o pagina
        //Acesta poate veni prin $_POST[] daca exista deja tabelul in pagina in care se afla utilizatorul in acel moment
        //Daca nu avem tabelul creat, trebuie dat implicit o valoare lui $e_p
        if (!isset($_POST["e_p"]) || $_POST["e_p"] == '') $e_p = 10; 
        else
        {
            $e_p = $_POST["e_p"];
            if ($e_p != '10' && $e_p != '20' && $e_p != '50' && $e_p != '100') die("OOOOOooooo");
        }

        if (!is_numeric($pg)) die("OOOOOooooooo");

        $offset = $e_p * ($pg-1); //se calculeaza parametrul pentru operatorul OFFSET

		$con = Make_Con();
	    if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
        mysqli_query($con, "USE `librarie`");
        $query = "SELECT COUNT(ID_Carte) AS NrTitluri, CONCAT(autori.Prenume, ' ', autori.Nume) AS Nume_Autor, autori.ID_Autor FROM carte_autor 
                RIGHT JOIN autori ON carte_autor.ID_Autor=autori.ID_Autor
                GROUP BY Nume_Autor
                HAVING Nume_Autor LIKE ?
                LIMIT " . $e_p . " OFFSET " . $offset;

        $query = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($query, 's', $search_bar);
        mysqli_stmt_execute($query);

        $result = mysqli_stmt_get_result($query);

        //Nr total de rezultate care indeplinesc criteriile de cautare date de utilizator:
        $Nr_R = "SELECT SUM(Nr_Rez) AS Nr_R FROM (
                    SELECT COUNT(autori.ID_Autor) AS Nr_Rez, CONCAT(Prenume, ' ', Nume) AS Nume_Autor FROM carte_autor
                        RIGHT JOIN autori ON carte_autor.ID_Autor=autori.ID_Autor
                        GROUP BY Nume_Autor
                        HAVING Nume_Autor LIKE ?
                ) AS autori_total";

        $Nr_R = mysqli_prepare($con, $Nr_R);
        mysqli_stmt_bind_param($Nr_R, 's', $search_bar);
        mysqli_stmt_execute($Nr_R);
        $Nr_R = mysqli_fetch_assoc(mysqli_stmt_get_result($Nr_R))["Nr_R"];

        $result_arr = array();
        if (mysqli_num_rows($result))
        {
            while ($autor = mysqli_fetch_assoc($result))
                $result_arr[] = array("id"=>$autor["ID_Autor"], "NrTitluri"=>$autor["NrTitluri"], "Nume_Autor"=>$autor["Nume_Autor"]);
            
            $result_arr[] = array("id"=>"", "NrTitluri"=>$Nr_R, "Nume_Autor"=>$e_p);
            //Ultimul array este creat deoarece este nevoie sa aducem nr de inregistrari si de elemente pe pagina intr-un obiect de acelasi format cu restul obiectelor
            //Rezultatul este un set de date JSON
        }
        
        echo json_encode($result_arr);
        mysqli_close($con);
    }
?>