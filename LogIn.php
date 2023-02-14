<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="Stiluri.css" />
        <?php
            require 'Scripturi.php';

            if ($_SERVER["REQUEST_METHOD"] == "POST")
		    {
                $e_mail = $_POST["e-mail"];
                $parola = $_POST["parola"];

                if (empty($e_mail) || empty($parola)) $err = "*Combinatia e-mail - parola nu este corecta!";
                else {
                    Strip($e_mail);
                    Strip($parola);

		            $con = Make_Con();
		            if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
                    mysqli_query($con, "USE `librarie`");
                    $query = mysqli_prepare($con,"SELECT `E_mail`, Parola, Drept, CodUtilizator, ID_Utilizator, CONCAT(Prenume, ' ', Nume) AS Nume FROM utilizatori WHERE `E_mail`=?");
                    mysqli_stmt_bind_param($query, "s", $e_mail);
                    mysqli_stmt_execute($query);
                    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($query));
                    mysqli_close($con);

                    if (isset($user["E_mail"]) && password_verify($parola, $user["Parola"]))
                    {
                        $_SESSION["guid"] = $user["CodUtilizator"];
                        $_SESSION["Drept"] = $user["Drept"];
                        $_SESSION["ID_User"] = $user["ID_Utilizator"];
                        $_SESSION["Nume_User"] = $user["Nume"];
                        header("Location: Meniu_Principal.php"); exit();
                    }
                    else $err = "*Combinatia e-mail - parola nu este corecta!";
                }
		    }
	    ?>
    </head>
    <body style="background-color: #FFF7E9;">
        <div style="padding: 60px;">
            <p class="text titlu">Evidența librăriei</p>
            <p class="text subtitlu">Log In</p>
            <form class="chenar" style="height: 240px;" action="" method="post">
                <p style="color: red"><?php if (isset($err)) echo $err;?></p>
                E-mail:<br>
                <input type="email" name="e-mail" style="width: 100%;"><br><br>
                Parola:<br>
                <input type="password" name="parola" style="width: 100%;"><br><br>
                <input class="buton" type="submit" value="Intrați"><br>
            </form> 
        </div>
    </body>
</html>