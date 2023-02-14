<?php
    //Operatiile pentru adaugarea unei noi edituri

    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }
    
    if ($_SESSION["Drept"] != 'A') die("Alooooo, n-ai drepturi !!");
 
    require 'Scripturi.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST")
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

        $redFlag = 0; //Semnaleaza daca un camp dintre cele pentru contact are prea multe caractere
        $NrContacte = count($Tara);
        for ($i = 0 ; $i < $NrContacte && !$redFlag; $i++)
            if (strlen($Tara[$i]) > 45 || strlen($Oras[$i]) > 45 || strlen($Strada[$i]) > 45 || strlen($Tel_1[$i]) > 12 || strlen($Tel_2[$i]) > 12 ||strlen($E_mail[$i]) > 45)
                $redFlag = 1;

        if (empty($Nume) || empty($Cod) || empty($Profit) || empty($Tara) || empty($Oras) || empty($Strada) || empty($E_mail))
            { $err = "*Nu ați completat câmpurile obligatorii !"; $confirmare = ""; }
        elseif (strlen($Nume) > 45 || strlen($Cod) > 15 || $redFlag)
            { $err = "*Unul din câmpuri are prea multe caractere !!"; $confirmare = ""; }
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
            
            //Trebuie verificat sa nu existe vreo alta inregistrare cu acelasi cod
            $query = "SELECT ID_Editura FROM edituri WHERE Cod_Editura=?";
            $query = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($query, "s", $Cod);
            mysqli_stmt_execute($query);
            $result = mysqli_stmt_get_result($query);
            
            if (!mysqli_num_rows($result))
            {
                //Se adauga datele editurii
                $query = "INSERT INTO edituri (Nume, Profit_An, Cod_Editura) VALUES(?, ?, ?)";
                $query = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($query, "sss", $Nume, $Profit, $Cod);
                mysqli_stmt_execute($query);

                //Se adauga datele punctelor de contact
                for ($i = 0 ; $i < count($Tara) ; $i++)
                {
                    $query = "INSERT INTO contacte (ID_Editura, Tara, Oras, Strada, Nr, Tel_1, Tel_2, E_mail)
                                VALUES((SELECT MAX(ID_Editura) FROM edituri), ?, ?, ?, ?, ?, ?, ?)";
                    $query = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($query, "sssssss", $Tara[$i], $Oras[$i], $Strada[$i], $Nr[$i], $Tel_1[$i], $Tel_2[$i], $E_mail[$i]);
                    mysqli_stmt_execute($query);
                }

                $confirmare = "Obiectul a fost introdus în baza de date"; $err = "";
            }
            else
                { $err = "*Există deja o editura cu acest cod !!"; $confirmare = ""; }
            mysqli_close($con);
        }
    }
?>

<div id="Main" class="fundal main">
    <div class="add_form">
        <p class="text subtitlu" style="text-align: left;">Introduceți datele noii edituri:</p>
        <form method="post" id="Edituri_Add_Form">
            <p style="color: red"><?php if (isset($err)) echo $err; ?></p>
            <p style="color: red; display: none;" id="Err">*Nu ați completat toate câmpurile obligatorii !</p>
            <p style="color: blue"><?php if (isset($confirmare)) echo $confirmare; ?></p>

            <label class="text">Nume:<span style="color: red">*</span></label>
            <span style="color: red; display: none;" id="Nume_Err">Sunt admise maxim 45 de caractere !</span>
            <br>
            <input class="search_bar add" id="Nume" name="Nume">
            <br><br>

            <label class="text">Cod:<span style="color: red">*</span></label>
            <span style="color: red; display: none;" id="Cod_Err">Sunt admise maxim 45 de caractere !</span>
            <br>
            <input class="search_bar add" id="Cod" name="Cod">
            <br><br>

            <label class='text'>Profit în ultimul an:<span style="color: red">*</span></label>
            &nbsp&nbsp
            <input name="Profit" id="Profit" type='number' class='add' min=0>
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
                <span id='contact'>
                    <label class="text">Țară:<span style="color: red">*</span></label>
                    &nbsp&nbsp
                    <input class="search_bar add" name="Tara[]">
                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                    <label class="text">Oraș:<span style="color: red">*</span></label>
                    &nbsp&nbsp
                    <input class="search_bar add" name="Oras[]">
                    <br><br>

                    <label class="text">Strada:<span style="color: red">*</span></label>
                    &nbsp&nbsp
                    <input class="search_bar add" name="Strada[]">
                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                    <label class="text">Nr. :</label>
                    &nbsp&nbsp
                    <input name="Nr[]" type='number' class='add' min=0>
                    <br><br>

                    <label class="text">Tel. 1:</label>
                    &nbsp&nbsp
                    <input class="search_bar add" name="Tel_1[]">
                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                    <label class="text">Tel. 2:</label>
                    &nbsp&nbsp
                    <input class="search_bar add" name="Tel_2[]">
                    <br><br>
                
                    <label class="text">E-Mail:<span style="color: red">*</span></label>
                    &nbsp&nbsp
                    <input type='email' class="search_bar add" name="E_mail[]">
                    <br><br>
                    -----------------------
                </span>
            </span>

            <br><br>

            <button type='button' class="buton t" onclick="Add_New_Editura(); return true;">Adaugă</button>
        </form>
    </div>
</div>