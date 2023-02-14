<?php
    //Operatiile pentru adaugarea unei carti in stoc

    require 'Meniu_Principal.php';
    require 'Scripturi.php';
    
    if ($_SESSION["Drept"] != 'A') die("Alooooo, n-ai drepturi !!");
    
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST["Titlu"])) $Titlu = $_POST["Titlu"]; else $Titlu = "";
        if (isset($_POST["Autor"])) $Autor = $_POST["Autor"]; else die("OOOOOooooo1");
        if (isset($_POST["Editura"])) $Editura = $_POST["Editura"]; else die("OOOOOooooo2");
        if (isset($_POST["Categorie"])) $Categorie = $_POST["Categorie"]; else die("OOOOOooooo3");
        if (isset($_POST["An_Aparitie"])) $An_Aparitie = $_POST["An_Aparitie"]; else $An_Aparitie = "";
        if (isset($_POST["Nr_Exemplare_An"])) $Nr_Exemplare_An = $_POST["Nr_Exemplare_An"]; else $Nr_Exemplare_An = "";
        if (isset($_POST["Pret"])) $Pret = $_POST["Pret"]; else $Pret = "";
        if (isset($_POST["Cod_Carte"])) $Cod_Carte = $_POST["Cod_Carte"]; else $Cod_Carte = "";

        if (empty($Titlu) || empty($Cod_Carte) || empty($An_Aparitie) || empty($Nr_Exemplare_An) || empty($Pret))
            { $err = "*Nu ați completat câmpurile obligatorii !"; $confirmare = ""; }
        elseif (strlen($Titlu) > 45 || strlen($Cod_Carte) > 30)
            { $err = "*Unul din câmpuri are prea multe caractere !!"; $confirmare = ""; }
        else {
            Strip($Titlu);
            Strip($Cod_Carte);
            if (!is_numeric($Editura) || !is_numeric($An_Aparitie) || !is_numeric($Nr_Exemplare_An) || !is_numeric($Pret)) die("OOOOOooooo4");
            for ($i = 0 ; $i < count($Autor) ; $i++)
                if (!is_numeric($Autor[$i])) die("OOOOOoooo5"); 
            for ($i = 0 ; $i < count($Categorie) ; $i++)
                if (!is_numeric($Categorie[$i])) die("OOOOOoooo6"); 
                
		    $con = Make_Con();
		    if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
            mysqli_query($con, "USE `librarie`");
            
            //Se verifica sa nu mai existe vreo carte cu acelasi cod
            $query = "SELECT ID_Carte FROM editura_carte WHERE Cod_Carte=?";
            $query = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($query, "s", $Cod_Carte);
            mysqli_stmt_execute($query);
            $result = mysqli_stmt_get_result($query);
            
            if (!mysqli_num_rows($result))
            {
                //Trebuie verificat ca titlu sa nu existe deja (daca nu exista, trebuie adaugat, altfel trebuie adugat doar in tabelul de legatura cu editura)
                //De asemenea, trebuie tinut cont de faptul ca pot exista mai multe carti cu acelasi titlu, dar de la autori diferiti (Exista mai multi poeti care publica volume de "Poezii")
                $query = "SELECT Titlu, ID_Autor FROM carti
                        INNER JOIN carte_autor ON carti.ID_Carte=carte_autor.ID_Carte
                        WHERE Titlu=? AND ID_Autor IN (" . implode(', ', $Autor) . ")";
                $query = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($query, "s", $Titlu);
                mysqli_stmt_execute($query);
                $result = mysqli_stmt_get_result($query);
                if (!mysqli_num_rows($result))
                {
                    $query = "INSERT INTO carti (Titlu) VALUES(?)";
                    $query = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($query, "s", $Titlu);
                    mysqli_stmt_execute($query);
    
                    for ($i = 0 ; $i < count($Autor) ; $i++)
                    {
                        $query = "INSERT INTO carte_autor VALUES((SELECT MAX(ID_Carte) FROM carti), ?)";
                        $query = mysqli_prepare($con, $query);
                        mysqli_stmt_bind_param($query, "s", $Autor[$i]);
                        mysqli_stmt_execute($query);
                    }

                    for ($i = 0 ; $i < count($Categorie) ; $i++)
                    {
                        $query = "INSERT INTO carte_categorie VALUES((SELECT MAX(ID_Carte) FROM carti), ?)";
                        $query = mysqli_prepare($con, $query);
                        mysqli_stmt_bind_param($query, "s", $Categorie[$i]);
                        mysqli_stmt_execute($query);
                    }

                    $query = "INSERT INTO editura_carte VALUES(?, (SELECT MAX(ID_Carte) FROM carti), " . $_SESSION["ID_User"] . ", '" . date('Y-m-d') . "', ?, ?, ?, ?)";
                    $query = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($query, "sssss", $Editura, $An_Aparitie, $Pret, $Nr_Exemplare_An, $Cod_Carte);
                    mysqli_stmt_execute($query);    
                }
                else
                {
                    $query = "INSERT INTO editura_carte VALUES(?, (SELECT ID_Carte FROM carti WHERE Titlu=?), " . $_SESSION["ID_User"] . ", '" . date('Y-m-d') . "', ?, ?, ?, ?)";
                    $query = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($query, "ssssss", $Editura, $Titlu, $An_Aparitie, $Pret, $Nr_Exemplare_An, $Cod_Carte);                    
                    mysqli_stmt_execute($query);    
                }
                $confirmare = "Obiectul a fost introdus în baza de date"; $err = "";
            }
            else { $err = "*Există deja o carte cu acest cod !!"; $confirmare = ""; }
            mysqli_close($con);
        }
    }
?>

<div id="Main" class="fundal main">
    <div class="add_form">
        <p class="text subtitlu" style="text-align: left;">Introduceți datele noii cărți:</p>
        <form method="post" id="Stoc_Add_Form">
            <p style="color: red"><?php if (isset($err)) echo $err; ?></p>
            <p style="color: red; display: none;" id="Err">*Nu ați completat toate câmpurile obligatorii !</p>
            <p style="color: blue"><?php if (isset($confirmare)) echo $confirmare; ?></p>

            <label class="text">Titlu:<span style="color: red">*</span></label>
            <span style="color: red; display: none;" id="Titlu_Err">Sunt admise maxim 45 de caractere !</span>
            <br>
            <input class="search_bar add" id="Titlu" name="Titlu">
            <br><br><br><br>

            <label class="text">Autor:<span style="color: red">*</span></label>
            <input type='hidden' id='autor-clip' value=1>
            &nbsp&nbsp&nbsp
            <button type='button' class="buton t" onclick="Add_Field('autor');">Adaugă încă un autor</button>
            <br><br>
            <span id='span-autor'>
                <select name="Autor[]" id='autor' class='add'>
                    <?php
                        $opt = json_decode(Get_List('Autor'), true);
                        for ($i = 0 ; $i < count($opt) ; $i++)
                        {
                            $imploded_opt = implode(' ', $opt[$i]);
                            echo "<option value=" . substr($imploded_opt, 0, stripos($imploded_opt, ' ')) . ">" . substr($imploded_opt, stripos($imploded_opt, ' ')) . "</option>";
                        }
                    ?>
                </select>
            </span>
            
            <br><br><br><br>

            <label class="text">Editura:<span style="color: red">*</span></label>
            <select name="Editura" class='add'>
                <?php
                    $opt = json_decode(Get_List('Editura'), true);
                    for ($i = 0 ; $i < count($opt) ; $i++)
                    {
                        $imploded_opt = implode(' ', $opt[$i]);
                        echo "<option value=" . substr($imploded_opt, 0, stripos($imploded_opt, ' ')) . ">" . substr($imploded_opt, stripos($imploded_opt, ' ')) . "</option>";
                    }
                ?>
            </select>
            <br><br><br><br>

            <label class="text">Categorie:<span style="color: red">*</span></label>
            &nbsp&nbsp&nbsp
            <input type='hidden' id='categorie-clip' value=1>
            <button type='button' class="buton t" onclick="Add_Field('categorie');">Adaugă încă o catgorie</button>
            <br><br>
            <span id='span-categorie'>
                <select name="Categorie[]" id='categorie' class='add'>
                    <?php
                        $opt = json_decode(Get_List('Categorie'), true);
                        for ($i = 0 ; $i < count($opt) ; $i++)
                        {
                            $imploded_opt = implode(' ', $opt[$i]);
                            echo "<option value=" . substr($imploded_opt, 0, stripos($imploded_opt, ' ')) . ">" . substr($imploded_opt, stripos($imploded_opt, ' ')) . "</option>";
                        }
                    ?>
                </select>
            </span>

            <br><br><br><br>

            <label class="text">Anul apariției: &nbsp&nbsp<span style="color: red">*</span></label>
            <input name="An_Aparitie" class='add' id="An_Aparitie" type='number' min=0>
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

            <label class="text">Nr. exemplare vândute în ultimul an: &nbsp&nbsp<span style="color: red">*</span></label>
            <input name="Nr_Exemplare_An" class='add' id="Nr_Exemplare_An" type='number' min=0><br><br>

            <label class='text'>Preț: &nbsp&nbsp<span style="color: red">*</span></label>
            <input name="Pret" id="Pret" type='number' class='add' min=0><br><br>

            <br><br><br><br>

            <label class="text">Cod:<span style="color: red">*</span></label>
            <span style="color: red; display: none;" id="Cod_Carte_Err">Sunt admise maxim 15 caractere !</span>
            <br>
            <input class="search_bar add" id="Cod_Carte" name="Cod_Carte">

            <br><br><br><br>

            <button type='button' class="buton t" onclick="Add_New_Carte(); return true;">Adaugă</button>
        </form>
    </div>
</div>