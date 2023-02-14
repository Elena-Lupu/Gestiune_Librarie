<?php
    //Aici se executa operatiile pentru editarea datelor unui autor

    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }

    if ($_SESSION["Drept"] != 'A') die("Alooooo, n-ai drepturi !!");
    
    require 'Scripturi.php';

    if (isset($_POST["nr"])) { $nr = $_POST["nr"]; Strip($nr); }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["nr"]))
    {
        if (isset($_POST["Prenume"])) $Prenume = $_POST["Prenume"]; else $Prenume = "";
        if (isset($_POST["Nume"])) $Nume = $_POST["Nume"]; else $Nume = "";
        if (isset($_POST["ID_Autor"]) && is_numeric($_POST["ID_Autor"])) $ID_Autor = $_POST["ID_Autor"]; else die("OOOOooooo");       

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

            $query = "UPDATE autori SET Nume=?, Prenume=? WHERE ID_Autor=?";
            $query = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($query, "sss", $Nume, $Prenume, $ID_Autor);
            mysqli_stmt_execute($query);
            
            $confirmare = "Obiectul a fost modificat în baza de date"; $err = "";
            mysqli_close($con);
        }
    }

    $con = Make_Con();
	if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
    mysqli_query($con, "USE `librarie`");

    //Aceste SELECT-uri se efectueaza pentru a oferi utilizatorului o forma deja completata,
    //sa nu trebuiasca sa rescrie datele in intregime pentru a face o modificare
	$query = "SELECT ID_Autor, Prenume, Nume FROM autori WHERE ID_Autor=?";
	$query = mysqli_prepare($con, $query);
    $cod = (isset($nr)) ? $nr : $ID_Autor;
	mysqli_stmt_bind_param($query, "s", $cod);
	mysqli_stmt_execute($query);
	$autor = mysqli_fetch_assoc(mysqli_stmt_get_result($query));

	mysqli_close($con);
?>

<div class="add_form">
    <p class="text subtitlu" style="text-align: left;">Modificați datele autorului:</p>
    <form method="post" id="Autor_Edit_Form">
        <p style="color: red"><?php if (isset($err)) echo $err; ?></p>
        <p style="color: red; display: none;" id="Err">*Nu ați completat câmpul obligatorii !</p>
        <p style="color: blue"><?php if (isset($confirmare)) echo $confirmare; ?></p>

        <label class="text">Prenume:</label>
        <span style="color: red; display: none;" id="Prenume_Err">Sunt admise maxim 45 de caractere !</span>
        <br>
        <input name="Prenume" class="search_bar add" id="Prenume" value='<?php if (isset($autor["Prenume"])) echo $autor["Prenume"]; ?>'>
        <br><br>

        <label class="text">Nume:<span style="color: red">*</span></label>
        <span style="color: red; display: none;" id="Nume_Err">Sunt admise maxim 45 de caractere !</span>
        <br>
        <input name="Nume" class="search_bar add" id="Nume" value='<?php echo $autor["Nume"]; ?>'>
        <br><br>

        <input type='hidden' value='<?php echo $autor["ID_Autor"]; ?>' name='ID_Autor'>

        <button type='button' class="buton t" onclick="Edit_New_Autor(); return true;">Modifică</button>
    </form>
</div>