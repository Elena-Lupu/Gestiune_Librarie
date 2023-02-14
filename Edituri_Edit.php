<?php
    //Operatiile pentru editarea datelor unei edituri

    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }

    require 'Scripturi.php';

    if ($_SESSION["Drept"] != 'A') die("Alooooo, n-ai drepturi !!");

    if (isset($_POST["nr"])) { $nr = $_POST["nr"]; Strip($nr); }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["nr"]))
    {
        if (isset($_POST["Nume"])) $Nume = $_POST["Nume"]; else $Nume = "";
        if (isset($_POST["Cod"])) $Cod = $_POST["Cod"]; else $Cod = "";
        if (isset($_POST["Profit"])) $Profit = $_POST["Profit"]; else $Profit = "";
        if (isset($_POST["Tara"])) $Tara = $_POST["Tara"]; else $Tara = "";
        if (isset($_POST["Oras"])) $Oras = $_POST["Oras"]; else $Oras = "";
        if (isset($_POST["Strada"])) $Strada = $_POST["Strada"]; else $Strada = "";
        if (isset($_POST["Nr"])) $Nr = $_POST["Nr"]; else $Nr = "";
        if (isset($_POST["Tel_1"])) $Tel_1 = $_POST["Tel_1"]; else $Tel_1 = "";
        if (isset($_POST["Tel_2"])) $Tel_2 = $_POST["Tel_2"]; else $Tel_2 = "";
        if (isset($_POST["E_mail"])) $E_mail = $_POST["E_mail"]; else $E_mail = "";
        if (isset($_POST["Cod_Vechi_Editura"])) $Cod_Vechi_Editura = $_POST["Cod_Vechi_Editura"]; else die("OOOOooooo");

        $redFlag = 0; //Semnaleaza daca un camp dintre cele pentru contact are prea multe caractere
        $NrContacte = count($Tara);
        for ($i = 0 ; $i < $NrContacte && !$redFlag; $i++)
            if (strlen($Tara[$i]) > 45 || strlen($Oras[$i]) > 45 || strlen($Strada[$i]) > 45 || strlen($Tel_1[$i]) > 12 || strlen($Tel_2[$i]) > 12 ||strlen($E_mail[$i]) > 45)
                $redFlag = 1;

        if (empty($Nume) || empty($Cod) || empty($Profit) || empty($Tara) || empty($Oras) || empty($Strada) || empty($E_mail))
            { $err = "*Nu ați completat câmpurile obligatorii !"; $confirmare = ""; }
        elseif (strlen($Nume) > 45 || strlen($Cod) > 15 || $redFlag)
            {
                $err = "*Unul din câmpuri are prea multe caractere !!";
                $confirmare = "";
            }
        else {
            Strip($Nume);
            Strip($Cod);
            if (!is_numeric($Profit)) die("OOOOOooooo4");

            for ($i = 0 ; $i < $NrContacte ; $i++)
            {
                Strip($Tara[$i]);
                Strip($Oras[$i]);
                Strip($Strada[$i]);
                Strip($Tel_1[$i]);
                Strip($Tel_2[$i]);
                Strip($E_mail[$i]);
                if (!is_numeric($Nr[$i]) && !empty($Nr[$i])) die("OOOOOooooo5");
            }

		    $con = Make_Con();
		    if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
            mysqli_query($con, "USE `librarie`");

            //Daca se modifica codul, trebuie verificat sa nu existe vreo editura cu acelasi cod
            $query = "SELECT ID_Editura FROM edituri WHERE Cod_Editura=? AND Cod_Editura!=?";
            $query = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($query, "ss", $Cod, $Cod_Vechi_Editura);
            mysqli_stmt_execute($query);
            $result = mysqli_stmt_get_result($query);
            
            if (!mysqli_num_rows($result))
            {
                //Daca s-a modificat codul, trebuie intai modificat acesta in baza de date
                //Pe baza acestuia se vor face si urmatoarele modificari
                if ($Cod != $Cod_Vechi_Editura)
                {
                    $query = "UPDATE edituri SET Cod_Editura=? WHERE Cod_Editura=?";
                    $query = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($query, "ss", $Cod, $Cod_Vechi_Editura);
                    mysqli_stmt_execute($query);
                }

                //Se modifica datele editurii
                $query = "UPDATE edituri SET Nume=?, Profit_An=? WHERE Cod_Editura=?";
                $query = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($query, "sss", $Nume, $Profit, $Cod);
                mysqli_stmt_execute($query);

                //se modifica datele punctelor de contact
                for ($i = 0 ; $i < count($Tara) ; $i++)
                {
                    $query = "UPDATE contacte SET Tara=?, Oras=?, Strada=?, Nr=?, Tel_1=?, Tel_2=?, E_mail=?
                                WHERE ID_Editura=(SELECT ID_Editura FROM edituri WHERE Cod_Editura=?)";
                    $query = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($query, "ssssssss", $Tara[$i], $Oras[$i], $Strada[$i], $Nr[$i], $Tel_1[$i], $Tel_2[$i], $E_mail[$i], $Cod);
                    mysqli_stmt_execute($query);
                }

                $confirmare = "Obiectul a fost modificat cu succes în baza de date"; $err = "";
            }
            else { $err = "*Există deja o editură cu acest cod !!"; $confirmare = ""; }
            mysqli_close($con);
        }
    }

    $con = Make_Con();
	if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
    mysqli_query($con, "USE `librarie`");

    //Se fac aceste SELECT-uri ca sa avem o forma deja completata cu datele curente din baza de date
    $query = "SELECT Nume, Profit_An, Cod_Editura FROM edituri WHERE " . ((isset($nr)) ? "ID_Editura=?" : "Cod_Editura=?");
    $query = mysqli_prepare($con, $query);
    $cod = (isset($nr)) ? $nr : $Cod;
    mysqli_stmt_bind_param($query, "s", $cod);
    mysqli_stmt_execute($query);
    $editura = mysqli_fetch_assoc(mysqli_stmt_get_result($query));
    
    //Cu privire la $cod: problema este ca trimiterea la aceasta pagina se face si cu un request de tip POST
    //Request-ul se foloseste pentru a trimite codul dupa care trebuie cautata inregistrarea ce trebuie modificata
    //insa astfel se intra in if-ul de la linia 13 si se executa operatii suplimentare nedorite
    //Asadar, trimit ID-ul prin $_POST[] (ca $nr) cand intru in pagina de adaugare, apoi cand apas pe butonul de modificare, trimit codul

    $query = "SELECT Tara, Oras, Strada, Nr, Tel_1, Tel_2, E_mail FROM contacte
            WHERE ID_Editura=" . ((isset($nr)) ? "?" : "(SELECT ID_Editura FROM edituri WHERE Cod_Editura=?)");
    $query = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($query, "s", $cod);
    mysqli_stmt_execute($query);
    $contacte = mysqli_stmt_get_result($query);
    
    mysqli_close($con);
?>

<div id="Main" class="fundal main">
    <div class="add_form">
        <p class="text subtitlu" style="text-align: left;">Introduceți datele noii edituri:</p>
        <form method="post" id="Edituri_Edit_Form">
            <p style="color: red"><?php if (isset($err)) echo $err; ?></p>
            <p style="color: red; display: none;" id="Err">*Nu ați completat toate câmpurile obligatorii !</p>
            <p style="color: blue"><?php if (isset($confirmare)) echo $confirmare; ?></p>

            <label class="text">Nume:<span style="color: red">*</span></label>
            <span style="color: red; display: none;" id="Nume_Err">Sunt admise maxim 45 de caractere !</span>
            <br>
            <input class="search_bar add" id="Nume" name="Nume" value='<?php echo $editura["Nume"]; ?>'>
            <br><br>

            <label class="text">Cod:<span style="color: red">*</span></label>
            <span style="color: red; display: none;" id="Cod_Err">Sunt admise maxim 45 de caractere !</span>
            <br>
            <input class="search_bar add" id="Cod" name="Cod" value='<?php echo $editura["Cod_Editura"]; ?>'>
            <br><br>

            <label class='text'>Profit în ultimul an:<span style="color: red">*</span></label>
            &nbsp&nbsp
            <input name="Profit" id="Profit" type='number' class='add' min=0 value='<?php echo $editura["Profit_An"]; ?>'>
            <br><br><br>

            <label class="text">Contact:</label>
            <span style="color: red; display: none;" id="Contact_Err">Un câmp are prea multe caractere !</span>
            &nbsp&nbsp&nbsp
            <input type='hidden' id='contact-clip' value=1>
            <button type='button' class="buton t" onclick="Add_Field('contact');">Adaugă încă un punct de contact</button>
            <br><br>
            -----------------------
            <br><br>

            <span id='span-contact'>
                <?php while ($contact = mysqli_fetch_assoc($contacte))
                {?>
                    <span id='contact'>
                        <label class="text">Țară:<span style="color: red">*</span></label>
                        &nbsp&nbsp
                        <input class="search_bar add" name="Tara[]" value='<?php echo $contact["Tara"]; ?>'>
                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                        
                        <label class="text">Oraș:<span style="color: red">*</span></label>
                        &nbsp&nbsp
                        <input class="search_bar add" name="Oras[]" value='<?php echo $contact["Oras"]; ?>'>
                        <br><br>

                        <label class="text">Strada:<span style="color: red">*</span></label>
                        &nbsp&nbsp
                        <input class="search_bar add" name="Strada[]" value='<?php echo $contact["Strada"]; ?>'>
                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                        <label class="text">Nr. :</label>
                        &nbsp&nbsp
                        <input name="Nr[]" type='number' class='add' min=0 value='<?php echo $contact["Nr"]; ?>'>
                        <br><br>

                        <label class="text">Tel. 1:</label>
                        &nbsp&nbsp
                        <input class="search_bar add" name="Tel_1[]" value='<?php echo $contact["Tel_1"]; ?>'>
                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                        <label class="text">Tel. 2:</label>
                        &nbsp&nbsp
                        <input class="search_bar add" name="Tel_2[]" value='<?php echo $contact["Tel_2"]; ?>'>
                        <br><br>

                        <label class="text">E-Mail:<span style="color: red">*</span></label>
                        &nbsp&nbsp
                        <input type='email' class="search_bar add" name="E_mail[]" value='<?php echo $contact["E_mail"]; ?>'>
                        <br><br>
                        -----------------------
                    </span>
                <?php } ?>
            </span>

            <br><br>

            <input type='hidden' name="Cod_Vechi_Editura" value=<?php echo $editura["Cod_Editura"]; ?>>
            <button type='button' class="buton t" onclick="Edit_New_Editura(); return true;">Adaugă</button>
        </form>
    </div>
</div>