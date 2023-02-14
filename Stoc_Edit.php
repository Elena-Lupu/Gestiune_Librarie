<?php
    //Operatiile pentru editarea datelor unei carti din stoc

    session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }

    require 'Scripturi.php';

    if ($_SESSION["Drept"] != 'A') die("Alooooo, n-ai drepturi !!");

    if (isset($_POST["nr"])) { $nr = $_POST["nr"]; Strip($nr); }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["nr"]))
    {
        if (isset($_POST["Titlu"])) $Titlu = $_POST["Titlu"]; else $Titlu = "";
        if (isset($_POST["Autor"])) $Autor = $_POST["Autor"]; else die("OOOOOooooo1");
        if (isset($_POST["Editura"])) $Editura = $_POST["Editura"]; else die("OOOOOooooo2");
        if (isset($_POST["Categorie"])) $Categorie = $_POST["Categorie"]; else die("OOOOOooooo3");
        if (isset($_POST["An_Aparitie"])) $An_Aparitie = $_POST["An_Aparitie"]; else $An_Aparitie = "";
        if (isset($_POST["Nr_Exemplare_An"])) $Nr_Exemplare_An = $_POST["Nr_Exemplare_An"]; else $Nr_Exemplare_An = "";
        if (isset($_POST["Pret"])) $Pret = $_POST["Pret"]; else $Pret = "";
        if (isset($_POST["Cod_Carte"])) $Cod_Carte = $_POST["Cod_Carte"]; else $Cod_Carte = "";
        if (isset($_POST["Cod_Vechi_Carte"])) $Cod_Vechi_Carte = $_POST["Cod_Vechi_Carte"]; else die("OOOOooooo");

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
            
            //In cazul modificarii codului, trebuie verificat sa nu existe o carte cu acelasi cod
            $query = "SELECT ID_Carte FROM editura_carte WHERE Cod_Carte=? AND Cod_Carte!=?";
            $query = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($query, "ss", $Cod_Carte, $Cod_Vechi_Carte);
            mysqli_stmt_execute($query);
            $result = mysqli_stmt_get_result($query);
    
            if (!mysqli_num_rows($result))
            {
                //Daca s-a modificat codul, trebuie intai modificat acesta in baza de date
                //Operatiile urmatoare se fac pe baza codului, deoarece acesta individualizeaza cu adevarat o carte
                //(ID-ul titlului nu individualizeaza in functie de editura care a publicat cartea respectiva)
                if ($Cod_Carte != $Cod_Vechi_Carte)
                {
                    $query = "UPDATE editura_carte SET Cod_Carte=? WHERE Cod_Carte=?";
                    $query = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($query, "ss", $Cod_Carte, $Cod_Vechi_Carte);
                    mysqli_stmt_execute($query);
                }

                $query = "UPDATE carti SET Titlu=? WHERE ID_Carte=(SELECT ID_Carte FROM editura_carte WHERE Cod_Carte=?)";
                $query = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($query, "ss", $Titlu, $Cod_Carte);
                mysqli_stmt_execute($query);

                for ($i = 0 ; $i < count($Autor) ; $i++)
                {
                    $query = "UPDATE carte_autor SET ID_Autor=? WHERE ID_Carte=(SELECT ID_Carte FROM editura_carte WHERE Cod_Carte=?)";
                    $query = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($query, "ss", $Autor[$i], $Cod_Carte);
                    mysqli_stmt_execute($query);
                }

                for ($i = 0 ; $i < count($Categorie) ; $i++)
                {
                    $query = "UPDATE carte_categorie SET ID_Categorie=? WHERE ID_Carte=(SELECT ID_Carte FROM editura_carte WHERE Cod_Carte=?)";
                    $query = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($query, "ss", $Categorie[$i], $Cod_Carte);
                    mysqli_stmt_execute($query);
                }

                $query = "UPDATE editura_carte SET ID_Editura=?, ID_UserEdit=" .  $_SESSION["ID_User"] . ", Data_UserEdit='" . date('Y-m-d') . "', An_Aparitie=?, Pret=?, ExemplareVandute_An=?
                            WHERE Cod_Carte=?";
                $query = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($query, "sssss", $Editura, $An_Aparitie, $Pret, $Nr_Exemplare_An, $Cod_Carte);
                mysqli_stmt_execute($query);

                $confirmare = "Obiectul a fost introdus în baza de date"; $err = "";
            }
            else { $err = "*Există deja o carte cu acest cod !!"; $confirmare = ""; }
            
            mysqli_close($con);
        }
    }

    $con = Make_Con();
	if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
    mysqli_query($con, "USE `librarie`");

	$query = "SELECT Titlu, ID_Editura, An_Aparitie, ExemplareVandute_An, Pret, Cod_Carte
            FROM editura_carte INNER JOIN carti ON editura_carte.ID_Carte=carti.ID_Carte
            WHERE Cod_Carte=?";
	$query = mysqli_prepare($con, $query);
    $cod = (isset($nr)) ? $nr : $Cod_Carte;
	mysqli_stmt_bind_param($query, "s", $cod);
	mysqli_stmt_execute($query);
	$carte = mysqli_fetch_assoc(mysqli_stmt_get_result($query));

    $query = "SELECT carte_autor.ID_Autor FROM carte_autor WHERE ID_Carte=(SELECT ID_Carte FROM editura_carte WHERE Cod_Carte=?)";
	$query = mysqli_prepare($con, $query);
	mysqli_stmt_bind_param($query, "s", $cod);
	mysqli_stmt_execute($query);
	$autori = mysqli_stmt_get_result($query);
    
    $query = "SELECT carte_categorie.ID_Categorie FROM carte_categorie WHERE ID_Carte=(SELECT ID_Carte FROM editura_carte WHERE Cod_Carte=?)";
	$query = mysqli_prepare($con, $query);
	mysqli_stmt_bind_param($query, "s", $cod);
	mysqli_stmt_execute($query);
	$categorii = mysqli_stmt_get_result($query);

	mysqli_close($con);
?>

<div id="Main" class="fundal main">
    <div class="add_form">
        <p class="text subtitlu" style="text-align: left;">Modificați datele cărții:</p>
        <form method="post" id="Stoc_Edit_Form">
            <p style="color: red"><?php if (isset($err)) echo $err; ?></p>
            <p style="color: red; display: none;" id="Err">*Nu ați completat toate câmpurile obligatorii !</p>
            <p style="color: blue"><?php if (isset($confirmare)) echo $confirmare; ?></p>

            <label class="text">Titlu:<span style="color: red">*</span></label>
            <span style="color: red; display: none;" id="Titlu_Err">Sunt admise maxim 45 de caractere !</span>
            <br>
            <input class="search_bar add" id="Titlu" name="Titlu" value='<?php echo $carte["Titlu"]; ?>'>
            <br><br><br><br>

            <label class="text">Autor:<span style="color: red">*</span></label>
            <input type='hidden' id='autor-clip' value=1>
            &nbsp&nbsp&nbsp
            <button type='button' class="buton t" onclick="Add_Field('autor');">Adaugă încă un autor</button>
            <br><br>
            <span id='span-autor'>
                <?php
                    $opt = json_decode(Get_List('Autor'), true);
                    while ($autor = mysqli_fetch_assoc($autori))
                    {
                        echo "<select name='Autor[]' id='autor' class='add'>";
                        for ($i = 0 ; $i < count($opt) ; $i++)
                        {
                            $imploded_opt = implode(' ', $opt[$i]);
                            $a_id = substr($imploded_opt, 0, stripos($imploded_opt, ' '));
                            echo "<option value=" . $a_id . " " . (($autor['ID_Autor'] == $a_id) ? 'selected' : '') . ">" . substr($imploded_opt, stripos($imploded_opt, ' ')) . "</option>";
                        }
                        echo '</select><br>';
                    } 
                ?>
            </span>
            
            <br><br><br><br>

            <label class="text">Editura:<span style="color: red">*</span></label>
            <select name="Editura" class='add'>
                <?php
                    $opt = json_decode(Get_List('Editura'), true);
                    for ($i = 0 ; $i < count($opt) ; $i++)
                    {
                        $imploded_opt = implode(' ', $opt[$i]);
                        $ed_id = substr($imploded_opt, 0, stripos($imploded_opt, ' '));
                        echo "<option value=" . $ed_id . " " . (($carte['ID_Editura'] == $ed_id) ? 'selected' : '') .  ">" . substr($imploded_opt, stripos($imploded_opt, ' ')) . "</option>";
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
                <?php
                    $opt = json_decode(Get_List('Categorie'), true);
                    while ($categorie = mysqli_fetch_assoc($categorii))
                    {
                        echo "<select name='Categorie[]' id='categorie' class='add'>";
                        for ($i = 0 ; $i < count($opt) ; $i++)
                        {
                            $imploded_opt = implode(' ', $opt[$i]);
                            $cat_id = substr($imploded_opt, 0, stripos($imploded_opt, ' '));
                            echo "<option value=" . $cat_id . " " . (($categorie['ID_Categorie'] == $cat_id) ? 'selected' : '') . ">" . substr($imploded_opt, stripos($imploded_opt, ' ')) . "</option>";
                        }
                        echo "</select><br>";
                    }    
                ?>
            </span>

            <br><br><br><br>

            <label class="text">Anul apariției: &nbsp&nbsp<span style="color: red">*</span></label>
            <input name="An_Aparitie" class='add' id="An_Aparitie" type='number' min=0 value=<?php echo $carte["An_Aparitie"]; ?>>
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

            <label class="text">Nr. exemplare vândute în ultimul an: &nbsp&nbsp<span style="color: red">*</span></label>
            <input name="Nr_Exemplare_An" class='add' id="Nr_Exemplare_An" type='number' min=0 value=<?php echo $carte["ExemplareVandute_An"]; ?>>
            <br><br>

            <label class='text'>Preț: &nbsp&nbsp<span style="color: red">*</span></label>
            <input name="Pret" id="Pret" type='number' class='add' min=0 value=<?php echo $carte["Pret"]; ?>>
            <br><br>

            <br><br><br><br>

            <label class="text">Cod:<span style="color: red">*</span></label>
            <span style="color: red; display: none;" id="Cod_Carte_Err">Sunt admise maxim 15 caractere !</span>
            <br>
            <input class="search_bar add" id="Cod_Carte" name="Cod_Carte" value=<?php echo $carte["Cod_Carte"]; ?>>
            <input type='hidden' name="Cod_Vechi_Carte" value=<?php echo $carte["Cod_Carte"]; ?>>

            <br><br><br><br>

            <button type='button' class="buton t" onclick="Edit_New_Carte(); return true;">Modifică</button>
        </form>
    </div>
</div>