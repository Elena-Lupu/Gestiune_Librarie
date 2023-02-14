<?php
    require 'Meniu_Principal.php';

    if ($_SESSION["Drept"] != 'A') die("Aloooo, n-ai drepturi !!");
?>

<div id="Main" class="fundal main">
    <div style="margin: 3%;">
        <label class="text subtitlu">Evidența utilizatorilor ce au acces la aplicație:</label><br><br>
        <label class="text">Căutați un anumit utilizator după nume/drept:</label>
        <br><br>
        <select id="criteriu_utilizator" onchange="Change_Input_Type_Utilizator();">
            <option value="Nume">Nume</option>
            <option value="Drept">Drept</option>
        </select>
        <input class="search_bar" id="Utilizatori_search_bar"></input>
        <br><br>
        <button class="buton" onclick="Search_Result_Utilizatori();">Căutare</button>
        <br><br>
        <button class="buton t <?php echo ($_SESSION['Drept'] != 'A') ? 'inactiv_t' : '' ?>" onclick="Add_Utilizator();" <?php echo ($_SESSION['Drept'] != 'A') ? 'disabled' : '' ?>>
            <span><img src='Icons/Add.svg' style='width: 20px;'></span>
            Adaugă un utilizator
            <?php echo ($_SESSION['Drept'] != 'A') ? "<span class='tooltip'>Trebuie să aveți drepturi de Admin ca să adăugați un utilizator!</span>" : "" ?>
        </button>
    </div>
    <br><br>
    <div class="tabel">
        <table id="Utilizatori_Result" cellspacing=0></table>
    </div>

    <div class="fundal pop_up" id="pop_up" style="display: none;">
        <div class="chenar info_box confirm_box" id="Delete">
		    <p class="text subtitlu">ATENȚIE !</p>
		    <p class="text subtitlu">Această acțiune este ireversibilă! Sunteți sigur că doriți să ștergeți acest utilizator?<p>
		    <button class='buton t' style="border: 2px solid green;" onclick="document.getElementById('pop_up').style.display='none';"><b>Renunță</b></button>
		    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		    <button id='Buton_Stergere' class="buton t" style='border: 2px solid red;'><b>Șterge</b></button>
	    </div>
    </div>

</div>