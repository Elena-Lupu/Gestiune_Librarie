function Add_Autor()
{
    $.ajax({
        url: "Autori_Add.php",
        success: function (result) { $("#Main").html(result); }
    });
}

function Add_Editura()
{
    $.ajax({
        url: "Edituri_Add.php",
        success: function (result) { $("#Main").html(result); }
    });
}

function Add_Utilizator()
{
    $.ajax({
        url: "Utilizatori_Add.php",
        success: function (result) { $("#Main").html(result); }
    });
}

function Add_Criteriu()
{
    let i = parseInt($("#clip").val());
    if (i != 5)
    {
        const newSearch = "<br id='" + i + "'> <select id='criteriu" + i + "_stoc' onchange='Change_Input_Type_Stoc(" + i + ");'> \
                <option value='Titlu'>Titlu</option> \
                <option value='Autor'>Autor</option> \
                <option value='Editura'>Editura</option> \
                <option value='An_Aparitie'>Anul apariției</option> \
                <option value='Nr_Exemplare_An'>Nr exemplare vândute într-un an</option> \
                <option value='Categorie'>Categorie</option> \
                <option value='Pret'>Preț</option> \
            </select> \
            <select id='criteriu_plus" + i + "_stoc' style='display: none;'> \
                <option value='<='>Înainte de anul:</option> \
                <option value='>='>După anul:</option> \
                <option value='='>Exact în anul:</option> \
            </select> \
            <input id='search_bar" + i + "' class='search_bar'></input> \
            <button id='Remove_Button" + i + "' class='buton' onclick='Remove_Criteriu(" + i + ");'><span><img src='Icons/Remove.svg' style='width: 15px;'></span>Elimină Criteriu</button>";
        $("#Add").append(newSearch);
        i++;
        $("#clip").val(i);
    }
    else
    {
        document.getElementById("Add_Button").disabled = true;
        document.getElementById("Add_Button").className += ' inactiv';
    }
}

function Remove_Criteriu(i)
{
    $("#criteriu" + i + "_stoc").remove();
    $("#search_bar" + i).remove();
    $("#criteriu_plus" + i + "_stoc").remove();
    $("#Remove_Button" + i).remove();
    $("#" + i).remove();
    document.getElementById("Add_Button").disabled = false;
    document.getElementById("Add_Button").classList.remove("inactiv");

    let j = parseInt($("#clip").val());
    j--;
    $("#clip").val(j);
}

function Add_Field(str)
{
    let i = parseInt($("#" + str + "-clip").val());
    let newID = str + i;

    $("#span-" + str).append("<br><br>");
    $("#" + str).clone().prop("id", newID).appendTo("#span-" + str);
    $("#span-" + str).append(" \
        <button type='button' class='buton t' \
        onclick=\" \
            $('#" + newID + "').remove(); \
            $(this).prev('br').remove(); \
            $(this).prev('br').remove(); \
            $(this).remove(); \
        \"> \
        - Șterge</button> \
    ");

    i++;
    $("#" + str + "-clip").val(i);
}

function Add_Header_Autori()
{
    $("#Autori_Result").append(
        "<tr> \
            <td colspan='3' class='special_data'> \
                <select id='e_p' onchange='Search_Result_Autori()'> \
                    <option value='10' selected>10</option> \
                    <option value='20'>20</option> \
                    <option value='50'>50</option> \
                    <option value='100'>100</option> \
                </select> \
                <span class='text'>  înregistrări/pg</span> \
            </td> \
            <td colspan='2' class='special_data' id='Special_Data'></td> \
        </tr> \
        <tr> \
            <th class='header' style='pointer-events: none;'></th> \
            <th class='header' style='pointer-events: none;'></th> \
            <th class='data header' style='pointer-events: none;' id='H0'>Nume</th> \
            <th class='data header' style='pointer-events: none;'>Nr titluri</th> \
        </tr>"
    );
}

function Add_Header_Edituri()
{
    $("#Edituri_Result").append(
        "<tr> \
            <td colspan='3' class='special_data'> \
                <select id='e_p' onchange='Search_Result_Edituri()'> \
                    <option value='10' selected>10</option> \
                    <option value='20'>20</option> \
                    <option value='50'>50</option> \
                    <option value='100'>100</option> \
                </select> \
                <span class='text'>  înregistrări/pg</span> \
            </td> \
            <td colspan='2' class='special_data' id='Special_Data'></td> \
        </tr> \
        <tr> \
            <th class='header' style='pointer-events: none;'></th> \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H0');Search_Result_Edituri();\" id='H0'> \
                Nume \
                <span id='H0_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H0_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th> \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H1');Search_Result_Edituri();\" id='H1'> \
                Profit anual \
                <span id='H1_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H1_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th> \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H2');Search_Result_Edituri();\" id='H2'> \
                Nr titluri \
                <span id='H2_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H2_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th> \
        </tr>"
    );
}

function Add_Header_Utilizatori()
{
    $("#Utilizatori_Result").append(
        "<tr> \
            <td colspan='3' class='special_data'> \
                <select id='e_p' onchange='Search_Result_Utilizatori()'> \
                    <option value='10' selected>10</option> \
                    <option value='20'>20</option> \
                    <option value='50'>50</option> \
                    <option value='100'>100</option> \
                </select> \
                <span class='text'>  înregistrări/pg</span> \
            </td> \
            <td colspan='2' class='special_data' id='Special_Data'></td> \
        </tr> \
        <tr> \
            <th class='header' style='pointer-events: none;'></th> \
            <th class='header' style='pointer-events: none;'></th> \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H0');Search_Result_Utilizatori();\" id='H0'> \
                Nume \
                <span id='H0_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H0_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th> \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H1');Search_Result_Utilizatori();\" id='H1'> \
                E-Mail \
                <span id='H1_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H1_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th> \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H2');Search_Result_Utilizatori();\" id='H2'> \
                Drept \
                <span id='H2_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H2_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th> \
        </tr>"
    );
}

function Add_Header_Stoc()
{
    $("#Stoc_Result").append(
        "<tr> \
            <td colspan='3' class='special_data'> \
                <select id='e_p' onchange='Search_Result_Stoc()'> \
                    <option value='10' selected>10</option> \
                    <option value='20'>20</option> \
                    <option value='50'>50</option> \
                    <option value='100'>100</option> \
                </select> \
                <span class='text'>  înregistrări/pg</span> \
            </td> \
            <td colspan='2' class='special_data' id='Special_Data'></td> \
        </tr> \
        <tr> \
            <th class='header' style='pointer-events: none;'></th> \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H0');Search_Result_Stoc();\" id='H0'> \
                Titlu \
                <span id='H0_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H0_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th > \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H1');Search_Result_Stoc();\" id='H1'> \
                Autor(i) \
                <span id='H1_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H1_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th> \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H2');Search_Result_Stoc();\" id='H2'> \
                Editura \
                <span id='H2_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H2_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th> \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H3');Search_Result_Stoc();\" id='H3'> \
                Anul apariției \
                <span id='H3_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H3_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th> \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H4');Search_Result_Stoc();\" id='H4'> \
                Nr. exemplare vândute într-un an \
                <span id='H4_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H4_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th> \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H5');Search_Result_Stoc();\" id='H5'> \
                Categorie \
                <span id='H5_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H5_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th> \
            <th class='data header' data-nr='0' onclick=\"Change_Data_Nr('H6');Search_Result_Stoc();\" id='H6'> \
                Preț \
                <span id='H6_Up' style='display: none;'><img src='Icons/Up.svg' style='width: 30px;'></span> \
                <span id='H6_Down' style='display: none;'><img src='Icons/Down.svg' style='width: 30px;'></span> \
            </th> \
        </tr>"
    );
}

function Add_List(i=0, tabel)
{
    $.ajax({
        type: "POST",
        url: "Lists.php",
        data: {"tabel": tabel},
        success: function(result)
        {
            let options="";
            for (let j = 0; j < result.length; j++) options += "<option value='" + result[j].id + "'>" + result[j].nume + "</option>";
            $("#search_bar".concat("", i)).append(options);
        }
    });
}

function Add_New_Autor()
{
    if ($("#Nume").val() == "") { document.getElementById("Err").style.removeProperty("display"); return; }
    if ($("#Nume").val().length > 45) { document.getElementById("Nume_Err").style.removeProperty("display"); return; }
    if ($("#Prenume").val().length > 45) { document.getElementById("Prenume_Err").style.removeProperty("display"); return; }

    var date = $("#Autor_Add_Form").serialize();
    $.ajax({
        type: "POST",
        url: "Autori_Add.php",
        data: date,
        success: function (data) { $("#Main").html(data); }
    });
}

function Add_New_Editura()
{
    if ($("#Nume").val() == "" || $("#profit").val() == "") { document.getElementById("Err").style.removeProperty("display"); return; }
    if ($("#Nume").val().length > 45) { document.getElementById("Nume_Err").style.removeProperty("display"); return; }
    if ($("#Cod").val().length > 45) { document.getElementById("Cod_Err").style.removeProperty("display"); return; }

    const tari = document.getElementsByName("Tara[]");
    const orase = document.getElementsByName("Oras[]");
    const strazi = document.getElementsByName("Strada[]");
    const nrs = document.getElementsByName("Nr[]");
    const tels_1 = document.getElementsByName("Tel_1[]");
    const tels_2 = document.getElementsByName("Tel_2[]");
    const e_mails = document.getElementsByName("E_mail[]");

    for (let tara of tari)
    {
        if (tara.value == "") { document.getElementById("Err").style.removeProperty("display"); return; }
        if (tara.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }
    }

    for (let oras of orase)
    {
        if (oras.value == "") { document.getElementById("Err").style.removeProperty("display"); return; }
        if (oras.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }
    }

    for (let strada of strazi)
    {
        if (strada.value == "") { document.getElementById("Err").style.removeProperty("display"); return; }
        if (strada.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }
    }

    for (let nr of nrs)
        if (nr.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }

    for (let tel_1 of tels_1)
        if (tel_1.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }

    for (let tel_2 of tels_2)
        if (tel_2.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }

    for (let e_mail of e_mails)
    {
        if (e_mail.value == "") { document.getElementById("Err").style.removeProperty("display"); return; }
        if (e_mail.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }
    }

    const date = $("#Edituri_Add_Form").serialize();
    $.ajax({
        type: "POST",
        url: "Edituri_Add.php",
        data: date,
        success: function (data) { $("#Main").html(data); }
    });
}

function Add_New_Carte()
{
    if ($("#Titlu").val() == "" || $("#Cod_Carte").val() == "" || $("#An_Aparitie").val() == "" || $("#Pret").val() == "" || $("#Nr_Exemplare_An").val() == "")
    { document.getElementById("Err").style.removeProperty("display"); return; }
    if ($("#Titlu").val().length > 45) { document.getElementById("Titlu_Err").style.removeProperty("display"); return; }
    if ($("#Cod_Carte").val().length > 30) { document.getElementById("Cod_Carte_Err").style.removeProperty("display"); return; }

    var date = $("#Stoc_Add_Form").serialize();
    $.ajax({
        type: "POST",
        url: "Stoc_Add.php",
        data: date,
        success: function (data) { $("#Main").html(data); }
    });
}

function Add_New_Utilizator()
{
    if ($("#E_Mail").val() == "" || $("#Parola").val() == "" || $("#Nume").val() == "")
    { document.getElementById("Err").style.removeProperty("display"); return; }
    if ($("#Nume").val().length > 45) { document.getElementById("Nume_Err").style.removeProperty("display"); return; }
    if ($("#Prenume").val().length > 45) { document.getElementById("Prenume_Err").style.removeProperty("display"); return; }    
    if ($("#Parola").val().length < 8 || !$("#Parola").val().match(/[a-z]/) || !$("#Parola").val().match(/[A-Z]/) || !$("#Parola").val().match(/\d/) || !$("#Parola").val().match(/[^a-zA-Z\d]/))
    { document.getElementById("Parola_Err").style.removeProperty("display"); return; }

    var date = $("#Utilizator_Add_Form").serialize();
    $.ajax({
        type: "POST",
        url: "Utilizatori_Add.php",
        data: date,
        success: function (data) { $("#Main").html(data); }
    });
}

function Change_Data_Nr(id)
{
    let element = document.getElementById(id);
    switch (element.getAttribute('data-nr'))
    {
        case '0':
            element.setAttribute('data-nr', '1');
            document.getElementById(id + "_Up").style.removeProperty('display');
            break;
        case '1':
            element.setAttribute('data-nr', '2');
            document.getElementById(id + "_Up").style.setProperty('display', 'none');
            document.getElementById(id + "_Down").style.removeProperty('display');
            break;
        case '2':
            element.setAttribute('data-nr', '0');
            document.getElementById(id + "_Down").style.setProperty('display', 'none');
            break;
    }
}

function Change_Input_Type_Editura()
{
    switch ($("#criteriu_editura").val())
    {
        case "Nume" :
        case "Cod":
            $("#Edituri_search_bar").replaceWith("<input class='search_bar' id='edituri_search_bar'></input>");
            document.getElementById("criteriu_plus_editura").style.display = "none";
            break;
        case "Profit" :
            $("#Edituri_search_bar").replaceWith("<input type='number' min=0 id='edituri_search_bar'></input>");
            document.getElementById("criteriu_plus_editura").style.removeProperty("display");
            break;
    }
}

function Change_Input_Type_Stoc(i)
{
    switch ($("#criteriu" + i + "_stoc").val())
    {
        case "Categorie" :
            $("#search_bar" + i).replaceWith("<select id='search_bar" + i + "'></select>");
            Add_List(i, "Categorie");
            document.getElementById("criteriu_plus" + i + "_stoc").style.display = "none";
            break;
        case "Autor" :
            $("#search_bar" + i).replaceWith("<select id='search_bar" + i + "'></select>");
            Add_List(i, "Autor");            
            document.getElementById("criteriu_plus" + i + "_stoc").style.display = "none";
            break;
        case "Editura" :
            $("#search_bar" + i).replaceWith("<select id='search_bar" + i + "'></select>");
            Add_List(i, "Editura");            
            document.getElementById("criteriu_plus" + i + "_stoc").style.display = "none";
            break;
        case "Titlu" :
        case "Cod_Carte" :
            $("#search_bar" + i).replaceWith("<input class='search_bar' id='search_bar" + i + "'></input>");
            document.getElementById("criteriu_plus" + i + "_stoc").style.display = "none";
            break;
        case "An_Aparitie" :
            $("#search_bar" + i).replaceWith("<input type='number' min=0 id='search_bar" + i + "'></input>");
            document.getElementById("criteriu_plus" + i + "_stoc").style.removeProperty("display");
            $("#criteriu_plus" + i + "_stoc").replaceWith(" \
                <select id=criteriu_plus" + i + "_stoc> \
                    <option value='<='>Înainte de anul:</option> \
                    <option value='>='>După anul:</option> \
                    <option value='='>Exact în anul:</option> \
                </select> \
            ");
            break;
        case "Pret" :
        case "Nr_Exemplare_An":
            $("#search_bar" + i).replaceWith("<input type='number' min=0 id='search_bar" + i + "'></input>");
            document.getElementById("criteriu_plus" + i + "_stoc").style.removeProperty("display");
            $("#criteriu_plus" + i + "_stoc").replaceWith(" \
                <select id=criteriu_plus" + i + "_stoc> \
                    <option value='<='>Mai puțin de:</option> \
                    <option value='>='>Mai mult de:</option> \
                </select> \
            ");            
            break;
    }
}

function Change_Input_Type_Utilizator()
{
    switch ($("#criteriu_utilizator").val())
    {
        case "Nume" :
            $("#Utilizatori_search_bar").replaceWith("<input class='search_bar' id='Utilizatori_search_bar'></input>");
            break;
        case "Drept" :
            $("#Utilizatori_search_bar").replaceWith(" \
                <select id='Utilizatori_search_bar'> \
                    <option value='A'>Admin</option> \
                    <option value='U'>User</option> \
                </select>");
            break;
    }
}

function Detalii_Carte(nr)
{
    document.getElementById("pop_up").style.removeProperty("display");
    $.ajax({
        type: "POST",
        url: "Stoc_Detalii.php",
        data: { "nr": nr },
        success: function (result) { $("#info_box").html(result); }
    });
}

function Detalii_Editura(nr)
{
    document.getElementById("pop_up").style.removeProperty("display");
    $.ajax({
        type: "POST",
        url: "Edituri_Detalii.php",
        data: { "nr": nr },
        success: function (result) { $("#info_box").html(result); }
    });
}

function Edit_Autor(nr)
{
    $.ajax({
        url: "Autori_Edit.php",
        type: "POST",
        data: {"nr": nr },
        success: function (result) { $("#Main").html(result); }
    });
}

function Edit_Carte(nr)
{
    $.ajax({
        url: "Stoc_Edit.php",
        type: "POST",
        data: {"nr": nr },
        success: function (result) { $("#Main").html(result); }
    });
}

function Edit_Editura(nr) {
    $.ajax({
        url: "Edituri_Edit.php",
        type: "POST",
        data: {"nr": nr },
        success: function (result) { $("#Main").html(result); }
    });
}

function Edit_Utilizator(nr)
{
    $.ajax({
        url: "Utilizatori_Edit.php",
        type: "POST",
        data: {"nr": nr },
        success: function (result) { $("#Main").html(result); }
    });
}

function Edit_New_Autor()
{
    if ($("#Nume").val() == "") { document.getElementById("Err").style.removeProperty("display"); return; }
    if ($("#Nume").val().length > 45) { document.getElementById("Nume_Err").style.removeProperty("display"); return; }
    if ($("#Prenume").val().length > 45) { document.getElementById("Prenume_Err").style.removeProperty("display"); return; }

    var date = $("#Autor_Edit_Form").serialize();
    $.ajax({
        type: "POST",
        url: "Autori_Edit.php",
        data: date,
        success: function (data) { $("#Main").html(data); }
    });
}

function Edit_New_Carte() {
    if ($("#Titlu").val() == "" || $("#Cod_Carte").val() == "" || $("#An_Aparitie").val() == "" || $("#Pret").val() == "" || $("#Nr_Exemplare_An").val() == "")
    { document.getElementById("Err").style.removeProperty("display"); return; }
    if ($("#Titlu").val().length > 45) { document.getElementById("Titlu_Err").style.removeProperty("display"); return; }
    if ($("#Cod_Carte").val().length > 30) { document.getElementById("Cod_Carte_Err").style.removeProperty("display"); return; }

    var date = $("#Stoc_Edit_Form").serialize();
    $.ajax({
        type: "POST",
        url: "Stoc_Edit.php",
        data: date,
        success: function (data) { $("#Main").html(data); }
    });
}

function Edit_New_Editura() {
    if ($("#Nume").val() == "" || $("#profit").val() == "") { document.getElementById("Err").style.removeProperty("display"); return; }
    if ($("#Nume").val().length > 45) { document.getElementById("Nume_Err").style.removeProperty("display"); return; }
    if ($("#Cod").val().length > 45) { document.getElementById("Cod_Err").style.removeProperty("display"); return; }

    const tari = document.getElementsByName("Tara[]");
    const orase = document.getElementsByName("Oras[]");
    const strazi = document.getElementsByName("Strada[]");
    const nrs = document.getElementsByName("Nr[]");
    const tels_1 = document.getElementsByName("Tel_1[]");
    const tels_2 = document.getElementsByName("Tel_2[]");
    const e_mails = document.getElementsByName("E_mail[]");

    for (let tara of tari)
    {
        if (tara.value == "") { document.getElementById("Err").style.removeProperty("display"); return; }
        if (tara.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }
    }

    for (let oras of orase)
    {
        if (oras.value == "") { document.getElementById("Err").style.removeProperty("display"); return; }
        if (oras.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }
    }

    for (let strada of strazi)
    {
        if (strada.value == "") { document.getElementById("Err").style.removeProperty("display"); return; }
        if (strada.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }
    }

    for (let nr of nrs)
        if (nr.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }

    for (let tel_1 of tels_1)
        if (tel_1.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }

    for (let tel_2 of tels_2)
        if (tel_2.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }

    for (let e_mail of e_mails)
    {
        if (e_mail.value == "") { document.getElementById("Err").style.removeProperty("display"); return; }
        if (e_mail.value.length > 45) { document.getElementById("Contact_Err").style.removeProperty("display"); return; }
    }

    var date = $("#Edituri_Edit_Form").serialize();
    $.ajax({
        type: "POST",
        url: "Edituri_Edit.php",
        data: date,
        success: function (data) { $("#Main").html(data); }
    });
}

function Edit_New_Utilizator()
{
    if ($("#E_Mail").val() == "" || $("#Parola").val() == "" || $("#Nume").val() == "")
    { document.getElementById("Err").style.removeProperty("display"); return; }
    if ($("#Nume").val().length > 45) { document.getElementById("Nume_Err").style.removeProperty("display"); return; }
    if ($("#Prenume").val().length > 45) { document.getElementById("Prenume_Err").style.removeProperty("display"); return; } 

    var date = $("#Utilizator_Edit_Form").serialize();
    $.ajax({
        type: "POST",
        url: "Utilizatori_Edit.php",
        data: date,
        success: function (data) { $("#Main").html(data); }
    });
}

function Search_Result_Autori(pg=1)
{
    const search_bar = $("#Autori_search_bar").val();
    const e_p = $("#E_P").val();

    $.ajax({
        type: "POST",
        url: "Autori_Result.php",
        data: { "search_bar": search_bar, "pg": pg, "e_p": e_p },
        success: function(result)
        {
            if (result.length === 0)
            {
                if (pg == 1)
                {
                    $("#Autori_Result").empty();
                    $("#Autori_Result").append("<p class='text' style='color: red;' id='search_err'>*Nu exista inregistrari!</p>");
                }
                else
                {
                    document.getElementById("Next_Pg").disabled = true;
                    document.getElementById("Next_Pg").className = 'inactiv';
                }
            }
            else
            {
                $(".row").remove();
                $("#Paginator").remove();
                $("#search_err").remove();
                if (!$('#H0').length) Add_Header_Autori();
                $("#special_data").html("<span class='text' id='nr_r'>" + result[result.length-1].NrTitluri + "  înregistrări</span>");

                for (let i = 0; i < result.length-1; i++)
                $("#Autori_Result").append(
                    "<tr class='row'> \
                        <td class='data' style='padding: 0;'> \
                            &nbsp&nbsp&nbsp \
                            <button class='buton t' onclick=\"document.getElementById('pop_up').style.removeProperty('display'); document.getElementById('Buton_Stergere').setAttribute('onclick', \'Sterge_Autor(\\'" + result[i].id + "\\')\');\"><span><img src='Icons/Delete.svg' style='width: 20px'></span></button> \
                            &nbsp&nbsp&nbsp \
                        </td> \
                        <td class='data' style='padding: 0;'> \
                            &nbsp&nbsp&nbsp \
                            <button class='buton t' onclick=\"Edit_Autor('" + result[i].id + "');\"><span><img src='Icons/Edit.svg' style='width: 20px'></span></button> \
                            &nbsp&nbsp&nbsp \
                        </td> \
                        <td class='data'>" + ((result[i].Nume_Autor).length > 20 ? (result[i].Nume_Autor).substring(0,20).concat("","...") : result[i].Nume_Autor) + "</td> \
                        <td class='data'>" + result[i].NrTitluri + "</td> \
                    </tr>"
                );

                let pagini = "";
                let nr_pg = Math.ceil(result[result.length-1].NrTitluri/result[result.length-1].Nume_Autor);
                //Aici campul "NrTitluri" pastreaza nr inregistrarilor, iar "Nume_Autor" nr paginii pe care o incarca
                //(toate elementele trimise prin JSON trebuie sa aiba acelasi format)
                for (let i = 1 ; i < pg ; i++) pagini += "<option value='" + i + "'>" + i + "</option>";
                pagini += "<option value='" + pg + "' selected>" + pg + "</option>";                
                for (let i = pg+1 ; i <= nr_pg ; i++) pagini += "<option value='" + i + "'>" + i + "</option>";

                $("#Autori_Result").append(
                    "<tr id='Paginator'> \
                        <td colspan='3' class='special_data'> \
                            <button class='buton t' onclick='Back_Pg()' id='Back_Pg'><span><img src='Icons/Back.svg'></span></button> \
                            <span class='text' data-nr='" + pg + "' id='pg'>Pg. " + pg + "</span> \
                            <button class='buton t' onclick='Next_Pg(\'Autori\')' id='Next_Pg'><span><img src='Icons/Forward.svg'></span></button> \
                            &nbsp &nbsp &nbsp \
                            <select id='Jump_Paginator' onchange='Jump_Pg(\'Autori\')';>" + pagini + "</select> \
                        </td>\
                    </tr>"
                );

                if (pg == 1)
                {
                    document.getElementById("Back_Pg").disabled = true;
                    document.getElementById("Back_Pg").className = 'inactiv';
                }

                if (pg == nr_pg)
                {
                    document.getElementById("Next_Pg").disabled = true;
                    document.getElementById("Next_Pg").className = 'inactiv';
                }
            }
        }
    });
}

function Search_Result_Edituri(pg=1)
{
    const search_bar = $("#Edituri_search_bar").val();
    const e_p = $("#E_P").val();
    const criteriu = $("#criteriu_editura").val();
    const criteriu_plus = $("#criteriu_plus_editura").val();
    let sort = [];

    const sorts = document.getElementsByClassName("header");
    for (let i = 1; i < sorts.length; i++)
    {
        sort[i-1] = sorts[i];
        sort[i-1] = sort[i-1].getAttribute('data-nr');
    }

    $.ajax({
        type: "POST",
        url: "Edituri_Result.php",
        data: { "search_bar": search_bar, "pg": pg, "e_p": e_p, "criteriu": criteriu, "operator": criteriu_plus, "sort": sort },
        success: function(result)
        {
            if (result.length === 0)
            {
                if (pg == 1)
                {
                    $("#Edituri_Result").empty();
                    $("#Edituri_Result").append("<p class='text' style='color: red;' id='Search_Err'>*Nu exista inregistrari!</p>");
                }
                else
                {
                    document.getElementById("Next_Pg").disabled = true;
                    document.getElementById("Next_Pg").className = 'inactiv';
                }
            }
            else
            {
                $(".row").remove();
                $("#Paginator").remove();
                $("#Search_Err").remove();
                if (!$('#H0').length) Add_Header_Edituri();
                $("#Special_Data").html("<span class='text' id='Nr_R'>" + result[result.length-1].ID + "  înregistrări</span>");

                for (let i = 0; i < result.length-1; i++)
                $("#Edituri_Result").append(
                    "<tr class='row'> \
                    <td class='data' style='padding: 0;'><button class='buton t' onclick=\"Detalii_Editura('" + result[i].ID + "');\"><span><img src='Icons/Edit_Setting.svg' style='width: 20px'></span></button></td> \
                    <td class='data'>" + ((result[i].Nume).length > 20 ? (result[i].Nume).substring(0,20).concat("","...") : result[i].Nume) + "</td> \
                        <td class='data'>" + result[i].Profit + "</td> \
                        <td class='data'>" + result[i].NrTitluri + "</td> \
                    </tr>"
                );

                let pagini = "";
                let nr_pg = Math.ceil(result[result.length-1].ID/result[result.length-1].Nume);
                //Aici campul "NrTitluri" pastreaza nr inregistrarilor, iar "Nume_Autor" nr paginii pe care o incarca
                //(toate elementele trimise prin JSON trebuie sa aiba acelasi format)
                for (let i = 1 ; i < pg ; i++) pagini += "<option value='" + i + "'>" + i + "</option>";
                pagini += "<option value='" + pg + "' selected>" + pg + "</option>";                
                for (let i = pg+1 ; i <= nr_pg ; i++) pagini += "<option value='" + i + "'>" + i + "</option>";

                $("#Edituri_Result").append(
                    "<tr id='Paginator'> \
                        <td colspan='3' class='special_data'> \
                            <button class='buton t' onclick='Back_Pg()' id='Back_Pg'><span><img src='Icons/Back.svg'></span></button> \
                            <span class='text' data-nr='" + pg + "' id='Pg'>Pg. " + pg + "</span> \
                            <button class='buton t' onclick='Next_Pg(\'Edituri\')' id='Next_Pg'><span><img src='Icons/Forward.svg'></span></button> \
                            &nbsp &nbsp &nbsp \
                            <select id='Jump_Paginator' onchange='Jump_Pg(\'Edituri\')';>" + pagini + "</select> \
                        </td>\
                    </tr>"
                );

                if (pg == 1)
                {
                    document.getElementById("Back_Pg").disabled = true;
                    document.getElementById("Back_Pg").className = 'inactiv';
                }

                if (pg == nr_pg)
                {
                    document.getElementById("Next_Pg").disabled = true;
                    document.getElementById("Next_Pg").className = 'inactiv';
                }
            }
        }
    });
}

function Search_Result_Utilizatori(pg=1)
{
    const search_bar = $("#Utilizatori_search_bar").val();
    const e_p = $("#E_P").val();
    const criteriu = $("#criteriu_utilizator").val();
    let sort = [];

    const sorts = document.getElementsByClassName("header");
    for (let i = 2; i < sorts.length; i++)
    {
        sort[i-2] = sorts[i];
        sort[i-2] = sort[i-2].getAttribute('data-nr');
    }

    $.ajax({
        type: "POST",
        url: "Utilizatori_Result.php",
        data: { "search_bar": search_bar, "pg": pg, "e_p": e_p, "criteriu": criteriu, "sort": sort },
        success: function(result)
        {
            if (result.length === 0)
            {
                if (pg == 1)
                {
                    $("#Utilizatori_Result").empty();
                    $("#Utilizatori_Result").append("<p class='text' style='color: red;' id='Search_Err'>*Nu există înregistrări!</p>");
                }
                else
                {
                    document.getElementById("Next_Pg").disabled = true;
                    document.getElementById("Next_Pg").className = 'inactiv';
                }
            }
            else
            {
                $(".row").remove();
                $("#Paginator").remove();
                $("#Search_Err").remove();
                if (!$('#H0').length) Add_Header_Utilizatori();
                $("#Special_Data").html("<span class='text' id='Nr_R'>" + result[result.length-1].Drept + "  înregistrări</span>");

                for (let i = 0; i < result.length-1; i++)
                $("#Utilizatori_Result").append(
                    "<tr class='row'> \
                        <td class='data' style='padding: 0;'><button class='buton t' onclick=\"Edit_Utilizator('" + result[i].ID + "');\"><span><img src='Icons/Edit.svg' style='width: 20px'></span></button></td> \
                        <td class='data' style='padding: 0;'> \
                            &nbsp&nbsp&nbsp \
                            <button class='buton t' onclick=\"document.getElementById('pop_up').style.removeProperty('display'); document.getElementById('Buton_Stergere').setAttribute('onclick', \'Sterge_Utilizator(\\'" + result[i].ID + "\\')\');\"><span><img src='Icons/Delete.svg' style='width: 20px'></span></button> \
                            &nbsp&nbsp&nbsp \
                        </td> \
                        <td class='data'>" + ((result[i].Nume).length > 20 ? (result[i].Nume).substring(0,20).concat("","...") : result[i].Nume) + "</td> \
                        <td class='data'>" + result[i].E_Mail + "</td> \
                        <td class='data'>" + ((result[i].Drept) == 'A' ? 'Admin' : 'User') + "</td> \
                    </tr>"
                );

                let pagini = "";
                let nr_pg = Math.ceil(result[result.length-1].Drept/result[result.length-1].E_Mail);
                for (let i = 1 ; i < pg ; i++) pagini += "<option value='" + i + "'>" + i + "</option>";
                pagini += "<option value='" + pg + "' selected>" + pg + "</option>";                
                for (let i = pg+1 ; i <= nr_pg ; i++) pagini += "<option value='" + i + "'>" + i + "</option>";

                $("#Utilizatori_Result").append(
                    "<tr id='Paginator'> \
                        <td colspan='3' class='special_data'> \
                            <button class='buton t' onclick='Back_Pg()' id='Back_Pg'><span><img src='Icons/Back.svg'></span></button> \
                            <span class='text' data-nr='" + pg + "' id='Pg'>Pg. " + pg + "</span> \
                            <button class='buton t' onclick='Next_Pg(\'Utilizatori\')' id='Next_Pg'><span><img src='Icons/Forward.svg'></span></button> \
                            &nbsp &nbsp &nbsp \
                            <select id='Jump_Paginator' onchange='Jump_Pg(\'Utilizatori\')';>" + pagini + "</select> \
                        </td>\
                    </tr>"
                );

                if (pg == 1)
                {
                    document.getElementById("Back_Pg").disabled = true;
                    document.getElementById("Back_Pg").className = 'inactiv';
                }

                if (pg == nr_pg)
                {
                    document.getElementById("Next_Pg").disabled = true;
                    document.getElementById("Next_Pg").className = 'inactiv';
                }
            }
        }
    });
}

function Search_Result_Stoc(pg=1) {
    let search_bar = [];
    let criteriu = [];
    let criteriu_plus = [];
    const nr_criterii = parseInt($("#clip").val());
    let sort = [];
    const e_p = $("#E_P").val();

    for (let i = 0 ; i < nr_criterii ; i++)
    {
        search_bar[i] = $("#search_bar".concat("",i)).val();
        criteriu[i] = $("#criteriu".concat("",i, "", "_stoc")).val();
        criteriu_plus[i] = $("#criteriu_plus".concat("",i, "", "_stoc")).val();
    }

    for (let i = 0 ; i < nr_criterii ; i++)
        if (criteriu[i] == "Titlu")
            if (search_bar[i].length > 45) return;

    const sorts = document.getElementsByClassName("header");
    for (let i = 1; i < sorts.length; i++)
    {
        sort[i-1] = sorts[i];
        sort[i-1] = sort[i-1].getAttribute('data-nr');
    }

    $.ajax({
        type: "POST",
        url: "Stoc_Result.php",
        data: { "search_bar": search_bar, "criteriu": criteriu, "operator": criteriu_plus, "nr_criterii": nr_criterii, "sort": sort, "pg": pg, "e_p": e_p },
        success: function(result)
        {
            if (result.length === 0)
            {
                if (pg == 1)
                {
                    $("#Stoc_Result").empty();
                    $("#Stoc_Result").append("<p class='text' style='color: red;' id='Search_Err'>*Nu exista inregistrari!</p>");
                }
                else
                {
                    document.getElementById("Next_Pg").disabled = true;
                    document.getElementById("Next_Pg").className = 'inactiv';
                }
            }
            else
            {
                $(".row").remove();
                $("#Paginator").remove();
                $("#Search_Err").remove();
                if (!$('#H0').length) Add_Header_Stoc();
                $("#Special_Data").html("<span class='text' id='Nr_R'>" + result[result.length-1].Editura + "  înregistrări</span>");
                
                for (let i = 0; i < result.length-1; i++)
                    $("#Stoc_Result").append(
                        "<tr class='row'> \
                            <td class='data' style='padding: 0;'><button class='buton t' onclick=\"Detalii_Carte('" + result[i].Cod_Carte + "');\"><span><img src='Icons/Edit_Setting.svg' style='width: 20px'></span></button></td> \
                            <td class='data'>" + ((result[i].Titlu).length > 20 ? (result[i].Titlu).substring(0,20).concat("","...") : result[i].Titlu) + "</td> \
                            <td class='data'>" + ((result[i].Autor).length > 20 ? (result[i].Autor).substring(0,20).concat("","...") : result[i].Autor) + "</td> \
                            <td class='data'>" + ((result[i].Editura).length > 20 ? (result[i].Editura).substring(0,20).concat("","...") : result[i].Editura) + "</td> \
                            <td class='data'>" + result[i].An_Aparitie + "</td> \
                            <td class='data'>" + result[i].Nr_Exemplare_An + "</td> \
                            <td class='data'>" + ((result[i].Categorie).length > 20 ? (result[i].Categorie).substring(0,20).concat("","...") : result[i].Categorie) + "</td> \
                            <td class='data'>" + result[i].Pret + " RON" + "</td> \
                        </tr>"
                );

                let pagini = "";
                let nr_pg = Math.ceil(result[result.length-1].Editura/result[result.length-1].Autor);
                //Aici campul "Editura" pastreaza nr inregistrarilor, iar "Autor" nr paginii pe care o incarca
                //(toate elementele trimise prin JSON trebuie sa aiba acelasi format)
                for (let i = 1 ; i < pg ; i++) pagini += "<option value='" + i + "'>" + i + "</option>";
                pagini += "<option value='" + pg + "' selected>" + pg + "</option>";                
                for (let i = pg+1 ; i <= nr_pg ; i++) pagini += "<option value='" + i + "'>" + i + "</option>";

                $("#Stoc_Result").append(
                    "<tr id='Paginator'> \
                        <td colspan='3' class='special_data'> \
                            <button class='buton t' onclick='Back_Pg(\"Stoc\")' id='Back_Pg'><span><img src='Icons/Back.svg'></span></button> \
                            <span class='text' data-nr='" + pg + "' id='Pg'>Pg. " + pg + "</span> \
                            <button class='buton t' onclick='Next_Pg(\"Stoc\")' id='Next_Pg'><span><img src='Icons/Forward.svg'></span></button> \
                            &nbsp &nbsp &nbsp \
                            <select id='Jump_Paginator' onchange='Jump_Pg(\"Stoc\")';>" + pagini + "</select> \
                        </td>\
                    </tr>"
                );

                if (pg == 1)
                {
                    document.getElementById("Back_Pg").disabled = true;
                    document.getElementById("Back_Pg").className = 'inactiv';
                }

                if (pg == nr_pg)
                {
                    document.getElementById("Next_Pg").disabled = true;
                    document.getElementById("Next_Pg").className = 'inactiv';
                }
            }
        }
    });
}

function Sterge_Autor(nr)
{
    $.ajax({
        type: "POST",
        url: "Autori_Sterge.php",
        data: { "nr": nr },
        success: function (data) { $("#Delete").html(data); }
    });
}

function Sterge_Carte(nr)
{
    $.ajax({
        type: "POST",
        url: "Stoc_Sterge.php",
        data: { "nr": nr },
        success: function (data) { $("#Delete").html(data); }
    });
}

function Sterge_Editura(nr) {
    $.ajax({
        type: "POST",
        url: "Edituri_Sterge.php",
        data: { "nr": nr },
        success: function (data) { $("#Delete").html(data); }
    });
}

function Sterge_Utilizator(nr)
{
    $.ajax({
        type: "POST",
        url: "Utilizatori_Sterge.php",
        data: { "nr": nr },
        success: function (data) { $("#Delete").html(data); }
    });
}

function Reactualizare(str)
{
    if ($('#pop_up_2').length) document.getElementById('pop_up_2').style.display='none';
    document.getElementById('pop_up').style.display='none';
    
    switch (str)
    {
        case 'Autori' : Search_Result_Autori(); break;
        case 'Stoc' : Search_Result_Stoc(); break;
        case 'Edituri' : Search_Result_Edituri(); break;
        case 'Utilizatori' : Search_Result_Utilizatori(); break;
    }
}

function Open_Close_Nav()
{
    let item_text = document.getElementsByClassName("item_text");
    if (window.getComputedStyle(document.querySelector(".side")).width == '225px')
    {
        document.getElementsByClassName("side")[0].style.width = '70px';
        if (document.getElementsByClassName("main")[0] != null) document.getElementsByClassName("main")[0].style.left = '70px';
        for (let i = 0 ; i < item_text.length ; i++) item_text[i].style.display = 'none';
    }
    else
    {
        document.getElementsByClassName("side")[0].style.width = '225px';
        if (document.getElementsByClassName("main")[0] != null) document.getElementsByClassName("main")[0].style.left = '225px';
        for (let i = 0 ; i < item_text.length ; i++) item_text[i].style.removeProperty('display');
    }
}

function Next_Pg(str)
{
    let pg = document.getElementById('Pg').getAttribute('data-nr');
    pg = parseInt(pg);
    pg++;
    switch (str)
    {
        case 'Autori' : Search_Result_Autori(pg); break;
        case 'Stoc' : Search_Result_Stoc(pg); break;
        case 'Edituri' : Search_Result_Edituri(pg); break;
        case 'Utilizatori' : Search_Result_Utilizatori(pg); break;
    }
}

function Back_Pg(str)
{
    let pg = document.getElementById('Pg').getAttribute('data-nr');
    pg = parseInt(pg);
    pg--;
    switch (str)
    {
        case 'Autori' : Search_Result_Autori(pg); break;
        case 'Stoc' : Search_Result_Stoc(pg); break;
        case 'Edituri' : Search_Result_Edituri(pg); break;
        case 'Utilizatori' : Search_Result_Utilizatori(pg); break;
    }
}

function Jump_Pg(str)
{
    let pg = $("#Jump_Paginator").val();
    switch (str)
    {
        case 'Autori' : Search_Result_Autori(pg); break;
        case 'Stoc' : Search_Result_Stoc(pg); break;
        case 'Edituri' : Search_Result_Edituri(pg); break;
        case 'Utilizatori' : Search_Result_Utilizatori(pg); break;
    }
}