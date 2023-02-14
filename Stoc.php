<?php require 'Meniu_Principal.php'; ?>

<div id="Main" class="fundal main">
    <div style="margin: 3%;">
        <label class="text subtitlu">Stocul de cărți disponibile în librărie:</label><br><br>
        <label class="text">Selectați criteriul după care doriți să căutați și introduceți cuvântul cheie:</label>
        <br><br>
        <select id="criteriu0_stoc" onchange="Change_Input_Type_Stoc(0);">
            <option value="Titlu">Titlu</option>
            <option value="Autor">Autor</option>
            <option value="Editura">Editura</option>
            <option value="An_Aparitie">Anul apariției</option>
            <option value="Nr_Exemplare_An">Nr exemplare vândute într-un an</option>
            <option value="Categorie">Categorie</option>
            <option value="Pret">Preț</option>
            <option value="Cod_Carte">Codul cărții</option>
        </select>
        <select id="criteriu_plus0_stoc" style="display: none;">
            <option value="<=">Înainte de anul:</option>
            <option value=">=">După anul:</option>
            <option value="=">Exact în anul:</option>
        </select>
        <input class="search_bar" id="search_bar0"></input>
        <button id="Add_Button" class="buton" onclick="Add_Criteriu();"><span><img src="Icons/Add_mic.svg" style="width: 15px"></span>Adaugă Criteriu (max 5)</button>
        <input type="hidden" id="clip" value="1"></input>
        <div id="Add"></div>
        <br><br>
        <button class="buton" onclick="Search_Result_Stoc();">Căutare</button>
    </div>
    <br><br>
    <div class="tabel">
        <table id="Stoc_Result" cellspacing=0></table>
    </div>
    <div class="fundal pop_up" id="pop_up" style="display: none;">
        <div class="chenar info_box" id="info_box"></div>
    </div>
</div>