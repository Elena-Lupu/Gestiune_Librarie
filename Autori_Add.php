<?php
    //De aici se executa operatiile necesare pentru adaugarea unui nou autor

    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }

    if ($_SESSION["Drept"] != 'A') die("Alooooo, n-ai drepturi !!");
    
    require 'Scripturi.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST["Prenume"])) $Prenume = $_POST["Prenume"]; else $Prenume = "";
        if (isset($_POST["Nume"])) $Nume = $_POST["Nume"]; else $Nume = "";

        if (empty($Nume))
            { $err = "*Nu ați completat câmpul obligatoriu !"; $confirmare = ""; }
        elseif (strlen($Nume) > 45 || strlen($Prenume) > 45)
            { $err = "*Unul din câmpuri are prea multe caractere !!"; $confirmare = ""; }
        else {
            Strip($Nume);
            Strip($Prenume);

            $con = Make_Con();
		    if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
            mysqli_query($con, "USE `librarie`");

            $query = "INSERT INTO autori(Nume, Prenume) VALUES (?, ?)";
            $query = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($query, "ss", $Nume, $Prenume);
            mysqli_stmt_execute($query);
            
            $confirmare = "Obiectul a fost introdus în baza de date"; $err = "";
            mysqli_close($con);
        }
    }
?>

<div class="add_form">
    <p class="text subtitlu" style="text-align: left;">Introduceți numele (și prenumele) noului autor:</p>
    <form method="post" id="Autor_Add_Form">
        <p style="color: red"><?php if (isset($err)) echo $err; ?></p>
        <p style="color: red; display: none;" id="Err">*Nu ați completat câmpul obligatoriu !</p>
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

        <button type='button' class="buton t" onclick="Add_New_Autor(); return true;">Adaugă</button>
    </form>
</div>