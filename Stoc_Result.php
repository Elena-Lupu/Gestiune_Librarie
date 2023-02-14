<?php
    //Operatiile pentru obtinerea datelor pentru tabelul din pagina "Stoc de carti"

    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }

    header("Content-Type: application/json");

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        require 'Scripturi.php';

        $pg = $_POST["pg"]; //pagina pe care doresc s-o afisez
        $search_bar = $_POST["search_bar"]; //ce este scris de utilizator in search-bar
        $nr_criterii = $_POST["nr_criterii"]; //aici cautarea se poate face dupa mai multe criterii
        $criteriu = $_POST["criteriu"]; //criteriul ales de utilizator pentru cautare
        $operator = $_POST["operator"]; //in cazul anumitor criterii nu se cauta potriviri exacte, ci valori mai mici sau mai mari
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

        if ($nr_criterii != "1" && $nr_criterii != "2" && $nr_criterii != "3" && $nr_criterii != "4" && $nr_criterii != "5") die("OOOOOooooo");

        for ($i = 0 ; $i < $nr_criterii ; $i++) Strip($search_bar[$i]);

        if (!is_numeric($pg)) die("OOOOOooooooo");

        //Functia AJAX care face trimitere la acest fisier trimite un vector cu cifre in ordinea corespunzatoare capetelor de tabel
        //Pe baza acestor cifre se construieste o clauza ORDER BY:
        //0 - nu se sorteaza, 1 - se sorteaza crescator, 2 - se sorteaza descrescator
        if (isset($_POST["sort"])) 
        {
            $sorties = $_POST["sort"];

            for ($i = 0 ; $i < count($sorties) ; $i++)
                if ($sorties[$i] != "0" && $sorties[$i] != "1" && $sorties[$i] != "2" && $sorties[$i] != "") die("OOOOOooooo");

            $sorts = array("Titlu"=>$sorties[0],
                        "Nume_Autor"=>$sorties[1],
                        "Editura"=>$sorties[2],
                        "An_Aparitie"=>$sorties[3],
                        "ExemplareVandute_An"=>$sorties[4],
                        "Nume_Categorie"=>$sorties[5],
                        "Pret"=>$sorties[6]);

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
        $query_0 = " FROM editura_carte
                    INNER JOIN carti ON editura_carte.ID_Carte=carti.ID_Carte
                    INNER JOIN edituri ON editura_carte.ID_Editura=edituri.ID_Editura
                    INNER JOIN carte_categorie ON editura_carte.ID_Carte=carte_categorie.ID_Carte
                    INNER JOIN categorii ON carte_categorie.ID_Categorie=categorii.ID_Categorie
                    INNER JOIN carte_autor ON carte_autor.ID_Carte=editura_carte.ID_Carte
                    INNER JOIN autori ON autori.ID_Autor=carte_autor.ID_Autor
                    WHERE";

        $bind_param = "";

        for ($i = 0 ; $i < $nr_criterii ; $i++)
            switch ($criteriu[$i])
            {
                case "Titlu" :
                    if (strlen($search_bar[$i]) > 45) die("OOOOOooooooo");
                    $query_0 = $query_0 . " Titlu LIKE ? AND ";
                    $search_bar[$i] = "%" . $search_bar[$i] . "%";
                    $bind_param = $bind_param . "s";
                    break;
                case "Autor" :
                    if (!is_numeric($search_bar[$i])) die("OOOOOooooooo");
                    $query_0 = $query_0 . " autori.ID_Autor=? AND ";
                    $bind_param = $bind_param . "s";
                    break;
                case "Editura" :
                    if (!is_numeric($search_bar[$i])) die("OOOOOooooooo");
                    $query_0 = $query_0 . " edituri.ID_Editura=? AND ";
                    $bind_param = $bind_param . "s";
                    break;
                case "An_Aparitie" :
                    if (!is_numeric($search_bar[$i])) die("OOOOOooooooo");
                    if ($operator[$i] != '=' && $operator[$i] != '<=' && $operator[$i] != '>=') die("OOOOOooooo");
                    $query_0 = $query_0 . " An_Aparitie" . $operator[$i] . "? AND ";
                    $bind_param = $bind_param . "s";
                    break;
                case "Nr_Exemplare_An" :
                    if (!is_numeric($search_bar[$i])) die("OOOOOooooooo");
                    if ($operator[$i] != '<=' && $operator[$i] != '>=') die("OOOOOooooo");
                    $query_0 = $query_0 . " ExemplareVandute_An" . $operator[$i] . "? AND ";
                    $bind_param = $bind_param . "s";
                    break;
                case "Categorie" :
                    if (!is_numeric($search_bar[$i])) die("OOOOOooooooo");
                    $query_0 = $query_0 . " categorii.ID_Categorie=? AND ";
                    $bind_param = $bind_param . "s";
                    break;
                case "Pret" :
                    if (!is_numeric($search_bar[$i])) die("OOOOOooooooo");
                    if ($operator[$i] != '<=' && $operator[$i] != '>=') die("OOOOOooooo");
                    $query_0 = $query_0 . " Pret" . $operator[$i] . "? AND ";
                    $bind_param = $bind_param . "s";
                    break;
                case "Cod_Carte" :
                    $query_0 = $query_0 . " Cod_Carte LIKE ? AND ";
                    $search_bar[$i] = "%" . $search_bar[$i] . "%";
                    $bind_param = $bind_param . "s";
                    break;

                default: die("OOOOOooooo");
            }
        
        //Se taie "AND " ramas la finalul lui $query_0 si se pune clauza GROUP BY
        $query_0 = substr($query_0, 0, strlen($query_0)-5) . " GROUP BY Cod_Carte ";
        
        //Nr total de rezultate care indeplinesc criteriile de cautare date de utilizator:
        $Nr_R = "SELECT SUM(Nr_Rez) AS Nr_R FROM (SELECT COUNT(DISTINCT Cod_Carte) AS Nr_Rez" . $query_0 . ") AS stoc";
        $Nr_R = mysqli_prepare($con, $Nr_R);
        mysqli_stmt_bind_param($Nr_R, $bind_param, ...$search_bar);
        mysqli_stmt_execute($Nr_R);
        $Nr_R = mysqli_fetch_assoc(mysqli_stmt_get_result($Nr_R))["Nr_R"];

        $offset = $e_p * ($pg-1); //se calculeaza parametrul pentru operatorul OFFSET
        $query = "SELECT Titlu, edituri.Nume AS Nume_Editura, An_Aparitie, GROUP_CONCAT(DISTINCT categorii.Nume) AS Nume_Categorie,
                    Pret, ExemplareVandute_An, GROUP_CONCAT(DISTINCT CONCAT(autori.Prenume, ' ', autori.Nume)) AS Nume_Autor, Cod_Carte" . 
                    $query_0 . $sort . " LIMIT " . $e_p . " OFFSET " . $offset;

        $query = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($query, $bind_param, ...$search_bar);
        mysqli_stmt_execute($query);

        $result = mysqli_stmt_get_result($query);
        $result_arr = array();
        if (mysqli_num_rows($result))
        {
            while ($carte = mysqli_fetch_assoc($result))
            {
                $result_arr[] = array("Titlu"=>$carte["Titlu"], 
                                    "Autor"=>$carte["Nume_Autor"],
                                    "Editura"=>$carte["Nume_Editura"], 
                                    "An_Aparitie"=>$carte["An_Aparitie"],
                                    "Nr_Exemplare_An"=>$carte["ExemplareVandute_An"], 
                                    "Categorie"=>$carte["Nume_Categorie"], 
                                    "Pret"=>$carte["Pret"],
                                    "Cod_Carte"=>$carte["Cod_Carte"]);

            }
            
            if (!isset($sorties)) $sorties = "";
            $result_arr[] = array("Titlu"=>"",
                                "Autor"=>$e_p,
                                "Editura"=>$Nr_R, 
                                "An_Aparitie"=>"",
                                "Nr_Exemplare_An"=>"", 
                                "Categorie"=>"", 
                                "Pret"=>"",
                                "Cod_Carte"=>"");
            //Ultimul array este creat deoarece este nevoie sa aducem nr de inregistrari si de elemente pe pagina intr-un obiect de acelasi format cu restul obiectelor
            //Rezultatul este un set de date JSON
        }
        
        echo json_encode($result_arr);
        mysqli_close($con);
    }
?>