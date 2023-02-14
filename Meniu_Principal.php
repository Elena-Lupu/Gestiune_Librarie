<!DOCTYPE html>
<?php
    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="Stiluri.css" />
        <script src="jquery-3.6.0.js"></script>
        <script src="Scripturi.js"></script>
    </head>
    <body style="background-color: #FFF7E9;">
        <div class="bar top">
            <div class="buton_menu" onclick="Open_Close_Nav();"><img src="Icons/Menu.svg" style="width: 50px"></div>
            <div class="text logo">Evidența librăriei</div>
            <div class="text menu_info">Sunteți logat ca:<br> <?php echo $_SESSION["Nume_User"] . "    (" . (($_SESSION["Drept"] == 'A') ? 'Admin' : 'User') . ")"; ?></div>
        </div>
        <div class="bar side">
            <ul style="padding: 15px">
                <li class="item">
                    <span><img src="Icons/Stoc.svg" style="width: 40px"></span>
                    <a href="Stoc.php"><span class="text item_text">Stoc<br>de cărți</span></a>
                </li>
                <li class="item">
                    <span><img src="Icons/Add.svg" style="width: 40px"></span>
                    <a href="Stoc_Add.php"><span class="text item_text">Adauga<br>o carte</span></a>
                </li>
                <li class="item">
                    <span><img src="Icons/Autor_Edit.svg" style="width: 40px;"></span>
                    <a href="Autori.php"><span class="text item_text">Gestionează<br>autorii</span></a>
                </li>
                <li class="item">
                    <span><img src="Icons/Editura.svg" style="width: 40px;"></span>
                    <a href="Edituri.php"><span class="text item_text">Gestionează<br>edituri</span></a>
                </li>
                <li class="item">
                    <span><img src="Icons/User.svg" style="width: 40px"></span>
                    <a href= <?php echo ($_SESSION["Drept"] != 'A') ? "'javascript:void(0)' title='Trebuie să aveți drepturi de Admin ca gestionați utilizatorii!'" : "'Utilizatori.php'" ?>><span class="text item_text">Gestionează<br>utilizatorii</span></a>
                </li>
                <br><br><br><br><br><br>
                <li class="item">
                    <a href="Scripturi.php?f=LogOut">
                        <span><img src="Icons/LogOut.svg" style="width: 40px"></span>
                        <span class="text item_text">LogOut</span>
                    </a>
                </li>
            </ul>
        </div>
    </body>
</html>