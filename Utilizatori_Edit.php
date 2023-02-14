<?php
    //Operatiile de editare a datelor unui utilizator

    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }

    if ($_SESSION["Drept"] != 'A') die("Alooooo, n-ai drepturi !!");
    
    require 'Scripturi.php';

    if (isset($_POST["nr"])) { $nr = $_POST["nr"]; Strip($nr); }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["nr"]))
    {
        if (isset($_POST["Prenume"])) $Prenume = $_POST["Prenume"]; else $Prenume = "";
        if (isset($_POST["Nume"])) $Nume = $_POST["Nume"]; else $Nume = "";
        if (isset($_POST["E_Mail"])) $E_Mail = $_POST["E_Mail"]; else $E_Mail = "";
        if (isset($_POST["Drept"])) $Drept = $_POST["Drept"]; else die("OOOOOOooooooo");
        if (isset($_POST["ID_Utilizator"])) $ID_Utilizator = $_POST["ID_Utilizator"]; else die("OOOOooooo");

        if (empty($Nume) || empty($E_Mail))
            { $err = "*Nu ați completat câmpurile obligatorii !"; $confirmare = ""; }
        elseif (strlen($Nume) > 45 || strlen($Prenume) > 45)
            { $err = "*Unul din câmpuri are prea multe caractere !!"; $confirmare = ""; }
        elseif ($Drept != 'A' && $Drept != 'U') die("OOOOOOooooo");
        else {
            Strip($Nume);
            Strip($Prenume);

            $con = Make_Con();
		    if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
            mysqli_query($con, "USE `librarie`");

            $query = "UPDATE utilizatori SET Nume=?, Prenume=?, E_mail=?, Drept=? WHERE ID_Utilizator=?";
            $query = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($query, "sssss", $Nume, $Prenume, $E_Mail, $Drept, $ID_Utilizator);
            mysqli_stmt_execute($query);
            
            $confirmare = "Obiectul a fost modificat în baza de date"; $err = "";
            mysqli_close($con);
        }
    }

    $con = Make_Con();
	if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
    mysqli_query($con, "USE `librarie`");

    //SELECT efectuat pentru oferirea unei forme precompletate
	$query = "SELECT ID_Utilizator, Prenume, Nume, Drept, E_mail FROM utilizatori WHERE ID_Utilizator=?";
	$query = mysqli_prepare($con, $query);
    $cod = (isset($nr)) ? $nr : $ID_Utilizator;
	mysqli_stmt_bind_param($query, "s", $cod);
	mysqli_stmt_execute($query);
	$user = mysqli_fetch_assoc(mysqli_stmt_get_result($query));

	mysqli_close($con);
?>

<div class="add_form">
    <p class="text subtitlu" style="text-align: left;">Modificați datele utilizatorului:</p>
    <form method="post" id="Utilizator_Edit_Form">
        <p style="color: red"><?php if (isset($err)) echo $err; ?></p>
        <p style="color: red; display: none;" id="Err">*Nu ați completat câmpurile obligatorii !</p>
        <p style="color: blue"><?php if (isset($confirmare)) echo $confirmare; ?></p>

        <label class="text">Prenume:</label>
        <span style="color: red; display: none;" id="Prenume_Err">Sunt admise maxim 45 de caractere !</span>
        <br>
        <input name="Prenume" class="search_bar add" id="Prenume" value='<?php if (isset($user["Prenume"])) echo $user["Prenume"]; ?>'>
        <br><br>

        <label class="text">Nume:<span style="color: red">*</span></label>
        <span style="color: red; display: none;" id="Nume_Err">Sunt admise maxim 45 de caractere !</span>
        <br>
        <input name="Nume" class="search_bar add" id="Nume" value='<?php echo $user["Nume"]; ?>'>
        <br><br>

        <label class="text">E-Mail:<span style="color: red">*</span></label>
        <br>
        <input type='email' name="E_Mail" class="search_bar add" value='<?php echo $user["E_mail"]; ?>'>
        <br><br>

        <label class="text">Drept:</label>
        <select name='Drept' id='Drept' class='add'>
            <option value='A' <?php echo ($user["Drept"] == 'A') ? 'selected' : '' ?>>Admin</option>
            <option value='U' <?php echo ($user["Drept"] == 'U') ? 'selected' : '' ?>>User</option>
        </select>
        <br><br>

        <input type='hidden' value='<?php echo $user["ID_Utilizator"]; ?>' name='ID_Utilizator'>

        <button type='button' class="buton t" onclick="Edit_New_Utilizator(); return true;">Modifică</button>
    </form>
</div>