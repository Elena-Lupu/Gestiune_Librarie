<?php require 'Meniu_Principal.php'; ?>

<div id="Main" class="fundal main">
    <div style="margin: 3%;">
        <label class="text subtitlu">Evidența editurilor ce se regăsesc în sistem:</label><br><br>
        <label class="text">Căutați o anumită editură după nume/cod/profit:</label>
        <br><br>
        <select id="criteriu_editura" onchange="Change_Input_Type_Editura();">
            <option value="Nume">Nume</option>
            <option value="Cod">Cod</option>
            <option value="Profit">Profit anual</option>
        </select>
        <select id="criteriu_plus_editura" style="display: none;">
            <option value="<=">Mai mult de:</option>
            <option value=">=">Mai puțin de:</option>
        </select>
        <input class="search_bar" id="Edituri_search_bar"></input>
        <br><br>
        <button class="buton" onclick="Search_Result_Edituri();">Căutare</button>
        <br><br>
        <button class="buton t <?php echo ($_SESSION['Drept'] != 'A') ? 'inactiv_t' : '' ?>" onclick="Add_Editura();" <?php echo ($_SESSION['Drept'] != 'A') ? 'disabled' : '' ?>>
            <span><img src='Icons/Add.svg' style='width: 20px;'></span>
            Adaugă o editură
            <?php echo ($_SESSION['Drept'] != 'A') ? "<span class='tooltip'>Trebuie să aveți drepturi de Admin ca să adăugați o editură!</span>" : "" ?>
        </button>
    </div>
    <br><br>
    <div class="tabel">
        <table id="Edituri_Result" cellspacing=0></table>
    </div>
    <div class="fundal pop_up" id="pop_up" style="display: none;">
        <div class="chenar info_box" id="info_box"></div>
    </div>
</div>