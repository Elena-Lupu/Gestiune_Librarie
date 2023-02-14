<?php require 'Meniu_Principal.php'; ?>

<div id="Main" class="fundal main">
    <div style="margin: 3%;">
        <label class="text subtitlu">Evidența autorilor ce se regăsesc la editurile din sistem:</label><br><br>
        <label class="text">Căutați un anumit autor după nume:</label>
        <input class="search_bar" id="Autori_search_bar"></input>
        <br><br>
        <button class="buton" onclick="Search_Result_Autori();">Căutare</button>
        <br><br>
        <button class="buton t <?php echo ($_SESSION['Drept'] != 'A') ? 'inactiv_t' : '' ?>" onclick="Add_Autor();" <?php echo ($_SESSION['Drept'] != 'A') ? 'disabled' : '' ?>>
            <span><img src='Icons/Add.svg' style='width: 20px;'></span>
            Adaugă un autor
            <?php echo ($_SESSION['Drept'] != 'A') ? "<span class='tooltip'>Trebuie să aveți drepturi de Admin ca să adăugați un autor!</span>" : "" ?>
        </button>
    </div>
    <br><br>
    <div class="tabel">
        <table id="Autori_Result" cellspacing=0></table>
    </div>

    <div class="fundal pop_up" id="pop_up" style="display: none;">
        <div class="chenar info_box confirm_box" id="Delete">
		    <p class="text subtitlu">ATENȚIE !</p>
		    <p class="text subtitlu">Această acțiune este ireversibilă! Odată cu ștergerea unui autor se vor șterge și toate titlurile acestuia din baza de date. Sunteți sigur că doriți să ștergeți acest autor?<p>
		    <button class='buton t' style="border: 2px solid green;" onclick="document.getElementById('pop_up').style.display='none';"><b>Renunță</b></button>
		    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		    <button id='Buton_Stergere' class="buton t <?php echo $_SESSION['Drept'] != 'A' ? 'inactiv_t' : '' ?>" style='border: 2px solid red;' <?php echo $_SESSION['Drept'] != 'A' ? 'disabled' : '' ?>>
			    <b>Șterge</b>
			    <?php echo $_SESSION['Drept'] != 'A' ? "<span class='tooltip'>N-ai drepturi !!!</span>" : "" ?>
		    </button>
	    </div>
    </div>

</div>