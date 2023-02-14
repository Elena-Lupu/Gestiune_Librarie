<?php
    //Operatiile de adaugare a unui utilizator

    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }

    if ($_SESSION["Drept"] != 'A') die("Alooooo, n-ai drepturi !!");
    
    require 'Scripturi.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST["Prenume"])) $Prenume = $_POST["Prenume"]; else $Prenume = "";
        if (isset($_POST["Nume"])) $Nume = $_POST["Nume"]; else $Nume = "";
        if (isset($_POST["E_Mail"])) $E_Mail = $_POST["E_Mail"]; else $E_Mail = "";
        if (isset($_POST["Parola"])) $Parola = $_POST["Parola"]; else $Parola = "";
        if (isset($_POST["Drept"])) $Drept = $_POST["Drept"]; else die("OOOOOOooooooo");

        if (empty($Nume) || empty($E_Mail) || empty($Parola))
            { $err = "*Nu ați completat câmpurile obligatoriu !"; $confirmare = ""; }
        elseif (strlen($Nume) > 45 || strlen($Prenume) > 45)
            { $err = "*Unul din câmpuri are prea multe caractere !!"; $confirmare = ""; }
        elseif ($Drept != 'A' && $Drept != 'U') die("OOOOOOooooo");
        else {
            Strip($Nume);
            Strip($Prenume);
            Strip($Parola);

            $con = Make_Con();
		    if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
            mysqli_query($con, "USE `librarie`");

            $query = "INSERT INTO utilizatori(Nume, Prenume, E_mail, Drept, Parola, CodUtilizator) VALUES (?, ?, ?, ?, ?, uuid())";
            $query = mysqli_prepare($con, $query);
            $Parola = password_hash($Parola,PASSWORD_BCRYPT);
            mysqli_stmt_bind_param($query, "sssss", $Nume, $Prenume, $E_Mail, $Drept, $Parola);
            mysqli_stmt_execute($query);
            
            $confirmare = "Obiectul a fost introdus în baza de date"; $err = "";
            mysqli_close($con);
        }
    }
?>

<div class="add_form">
    <p class="text subtitlu" style="text-align: left;">Introduceți numele, adresa de e-mail, dreptul și parola (litere mici, mari, cifre și semne, 8 caractere) noului utilizator:</p>
    <form method="post" id="Utilizator_Add_Form">
        <p style="color: red"><?php if (isset($err)) echo $err; ?></p>
        <p style="color: red; display: none;" id="Err">*Nu ați completat câmpurile obligatoriu !</p>
        <p style="color: blue"><?php if (isset($confirmare)) echo $confirmare; ?></p>

        <label class="text">Prenume:</label>
        <span style="color: red; display: none;" id="Prenume_Err">Sunt admise maxim 45 de caractere !</span>
        <br>
        <input name="Prenume" class="search_bar add" id="Prenume">
        <br><br>

        <label class="text">Nume:<span style="color: red">*</span></label>
        <span style="color: red; display: none;" id="Nume_Err">Sunt admise maxim 45 de caractere !</span>
        <br>
        <input name="Nume" class="search_bar add" id="Nume">
        <br><br>

        <label class="text">E-Mail:<span style="color: red">*</span></label>
        <br>
        <input type='email' name="E_Mail" class="search_bar add">
        <br><br>

        <label class="text">Parola:<span style="color: red">*</span></label>
        <span style="color: red; display: none;" id="Parola_Err">Parola nu este suficient de puternică !</span>
        <br>
        <input type='password' name="Parola" class="search_bar add" id="Parola">
        <br><br>

        <label class="text">Drept:</label>
        <select name='Drept' id='Drept' class='add'>
            <option value='A'>Admin</option>
            <option value='U'>User</option>
        </select>
        <br><br>

        <button type='button' class="buton t" onclick="Add_New_Utilizator(); return true;">Adaugă</button>
    </form>
</div>