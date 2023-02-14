<?php
    //Operatiile pentru aducerea datelor pentru tabelul din pagina "Gestioneaza edituri"

    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }

    header("Content-Type: application/json");

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        require 'Scripturi.php';

        $pg = $_POST["pg"]; //pagina pe care doresc s-o afisez
        $search_bar = $_POST["search_bar"]; //ce este scris de utilizator in search-bar
        $criteriu = $_POST["criteriu"]; //criteriul ales de utilizator pentru cautare
        $operator = $_POST["operator"]; //in criteriului "Profit" nu se cauta potriviri exacte, ci valori mai mici sau mai mari
        $sort=""; //aici se va construi clauza ORDER BY in cazul in care se doreste o sortare a unui tabel deja existent

        //$e_p reprezinta nr de elemente prezente pe o pagina
        //Acesta poate veni prin $_POST[] daca exista deja tabelul in pagina in care se afla utilizatorul in acel moment
        //Daca nu avem tabelul creat, trebuie dat implicit o valoare lui $e_p
        if (!isset($_POST["e_p"]) || $_POST["e_p"] == '') $e_p = 10;
        else
        {
            $e_p = $_POST["e_p"];
            if ($e_p != '10' && $e_p != '20' && $e_p != '50' && $e_p != '100') die("OOOOOooooo");
        }

        Strip($search_bar);

        if (!is_numeric($pg)) die("OOOOOooooooo");

        //Functia AJAX care face trimitere la acest fisier trimite un vector cu cifre in ordinea corespunzatoare capetelor de tabel
        //Pe baza acestor cifre se construieste o clauza ORDER BY:
        //0 - nu se sorteaza, 1 - se sorteaza crescator, 2 - se sorteaza descrescator
        if (isset($_POST["sort"])) 
        {
            $sorties = $_POST["sort"];

            for ($i = 0 ; $i < count($sorties) ; $i++)
                if ($sorties[$i] != "0" && $sorties[$i] != "1" && $sorties[$i] != "2" && $sorties[$i] != "") die("OOOOOooooo");

            $sorts = array("Nume"=>$sorties[0], "Profit_An"=>$sorties[1], "NrTitluri"=>$sorties[2]);

            foreach ($sorts as $camp => $val)
                if ($val != "0")
                    if ($sort == "")
                    {
                        $sort .= " ORDER BY ";
                        if ($val == "1") $sort .= $camp . " ASC";
                        else $sort .= $camp . " DESC";
                    }
                    else
                        if ($val == "1") $sort .= ", " . $camp . " ASC";
                        else $sort .= ", " . $camp . " DESC";
        }

		$con = Make_Con();
	    if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
        mysqli_query($con, "USE `librarie`");
        $query_0 = " WHERE";

        switch ($criteriu)
        {
            case "Nume" :
                if (strlen($search_bar) > 45) die("OOOOOooooooo");
                $query_0 .= " Nume LIKE ? ";
                $search_bar = "%" . $search_bar . "%";
                break;
            case "Profit" :
                if (!is_numeric($search_bar)) die("OOOOOooooooo");
                if ($operator != '<=' && $operator != '>=') die("OOOOOooooo");
                $query_0 .= " Profit_An" . $operator . "? ";
                break;
            case "Cod" :
                $query = $query . " Cod_Editura LIKE ? ";
                $search_bar = "%" . $search_bar . "%";
                break;

            default: die("OOOOOooooo");
        }
        
        $offset = $e_p * ($pg-1); //se calculeaza parametrul pentru operatorul OFFSET
        $query = " SELECT Nume, Profit_An, edituri.ID_Editura, COUNT(Cod_Carte) AS NrTitluri FROM edituri
                LEFT JOIN editura_carte ON edituri.ID_Editura=editura_carte.ID_Editura" . $query_0 . "
                GROUP BY edituri.ID_Editura " . $sort . " LIMIT " . $e_p . " OFFSET " . $offset;

        $query = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($query, 's', $search_bar);
        mysqli_stmt_execute($query);

        $result = mysqli_stmt_get_result($query);

        //Nr total de rezultate care indeplinesc criteriile de cautare date de utilizator:
        $Nr_R = "SELECT COUNT(ID_Editura) AS Nr_R FROM edituri" . $query_0;
        $Nr_R = mysqli_prepare($con, $Nr_R);
        mysqli_stmt_bind_param($Nr_R, 's', $search_bar);
        mysqli_stmt_execute($Nr_R);
        $Nr_R = mysqli_fetch_assoc(mysqli_stmt_get_result($Nr_R))["Nr_R"];

        $result_arr = array();
        if (mysqli_num_rows($result))
        {
            while ($editura = mysqli_fetch_assoc($result))
                $result_arr[] = array("ID"=>$editura["ID_Editura"], "Nume"=>$editura["Nume"], "Profit"=>$editura["Profit_An"], "NrTitluri"=>$editura["NrTitluri"]);
            
            if (!isset($sorties)) $sorties = "";
            $result_arr[] = array("ID"=>$Nr_R, "Nume"=>$e_p, "Profit"=>"", "NrTitluri"=>"");
            //Ultimul array este creat deoarece este nevoie sa aducem nr de inregistrari si de elemente pe pagina intr-un obiect de acelasi format cu restul obiectelor
            //Rezultatul este un set de date JSON
        }
        
        echo json_encode($result_arr);
        mysqli_close($con);
    }
?>