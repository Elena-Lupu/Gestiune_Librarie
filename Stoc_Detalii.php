<?php
	//Se obtin detaliile pentru o anumita carte

	session_start();
    if (empty($_SESSION["guid"])) { header("Location: LogIn.php"); exit(); }

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$nr = $_POST["nr"];

		require 'Scripturi.php';
		Strip($nr);

		$con = Make_Con();
	    if (!$con) die("Conexiune esuata :(" . mysqli_connect_error());
        mysqli_query($con, "USE `librarie`");
		$query = "SELECT Titlu, edituri.Nume AS Nume_Editura, An_Aparitie, GROUP_CONCAT(DISTINCT categorii.Nume) AS Nume_Categorie,
                Pret, ExemplareVandute_An, GROUP_CONCAT(DISTINCT CONCAT(autori.Prenume, ' ', autori.Nume)) AS Nume_Autor, Cod_Carte,
				CONCAT(utilizatori.Prenume, ' ', utilizatori.Nume) AS Nume_User, Data_UserEdit
                    FROM editura_carte
                    INNER JOIN carti ON editura_carte.ID_Carte=carti.ID_Carte
                    INNER JOIN edituri ON editura_carte.ID_Editura=edituri.ID_Editura
                    INNER JOIN carte_categorie ON editura_carte.ID_Carte=carte_categorie.ID_Carte
                    INNER JOIN categorii ON carte_categorie.ID_Categorie=categorii.ID_Categorie
                    INNER JOIN carte_autor ON carte_autor.ID_Carte=editura_carte.ID_Carte
                    INNER JOIN autori ON autori.ID_Autor=carte_autor.ID_Autor
					LEFT JOIN utilizatori ON editura_carte.ID_UserEdit=utilizatori.ID_Utilizator
                    WHERE Cod_Carte=? GROUP BY Titlu";
		$query = mysqli_prepare($con, $query);
		mysqli_stmt_bind_param($query, "s", $nr);
		mysqli_stmt_execute($query);
		$carte = mysqli_fetch_assoc(mysqli_stmt_get_result($query));
		mysqli_close($con);
?>

		<p class='text'><b>Titlu:</b></p><p class='text'><?php echo $carte["Titlu"]; ?></p><br>
		<p class='text'><b>Autor(i):</b></p><p class='text'><?php echo str_replace(',', '<br>', $carte["Nume_Autor"]); ?></p><br>
		<p class='text'><b>Editura:</b></p><p class='text'><?php echo $carte["Nume_Editura"]; ?></p><br>
		<p class='text'><b>Anul apariției:</b></p><p class='text'><?php echo $carte["An_Aparitie"]; ?></p><br>
		<p class='text'><b>Categorie:</b></p><p class='text'><?php echo str_replace(',', '<br>', $carte["Nume_Categorie"]); ?></p><br>
		<p class='text'><b>Nr. de exemplare vândute într-un an:</b></p><p class='text'><?php echo $carte["ExemplareVandute_An"]; ?></p><br>
		<p class='text'><b>Preț:</b></p><p class='text'><?php echo $carte["Pret"]; ?></p><br>
		<p class='text'><b>Cod:</b></p><p class='text'><?php echo $carte["Cod_Carte"]; ?></p><br>
		------------------
		<p class='text'><b>Carte introdusă de:</b></p><p class='text'><?php echo $carte["Nume_User"] . ',  ' . date_format(date_create($carte["Data_UserEdit"]), 'd/m/Y'); ?></p><br>		
		
		<button class="buton t" onclick="document.getElementById('pop_up').style.display='none';">Închide</button>
		&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		<button class="buton t <?php echo $_SESSION['Drept'] != 'A' ? 'inactiv_t' : '' ?>" style='border: 2px solid blue;' onclick="Edit_Carte('<?php echo $carte['Cod_Carte'];?>');" <?php echo $_SESSION['Drept'] != 'A' ? 'disabled' : '' ?>>
			<b><span><img src='Icons/Edit.svg' style='width: 20px;'>Edit</span></b>
            <?php echo $_SESSION['Drept'] != 'A' ? "<span class='tooltip'>Trebuie să aveți drepturi de Admin ca să editați atributele unei cărți!</span>" : "" ?>
		</button>
		&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		<button class="buton t <?php echo $_SESSION['Drept'] != 'A' ? 'inactiv_t' : '' ?>" style='border: 2px solid red;' onclick="document.getElementById('pop_up_2').style.removeProperty('display');" <?php echo $_SESSION['Drept'] != 'A' ? 'disabled' : '' ?>>
			<b><span><img src='Icons/Delete.svg' style='width: 20px;'>Șterge</span></b>
            <?php echo $_SESSION['Drept'] != 'A' ? "<span class='tooltip'>Trebuie să aveți drepturi de Admin ca să ștergeți o carte!</span>" : "" ?>
		</button>
	
<?php } ?>

<div class="fundal pop_up" id="pop_up_2" style="display: none;">
    <div class="chenar info_box confirm_box" id="Delete">
		<p class="text subtitlu">ATENȚIE !</p>
		<p class="text subtitlu">Această acțiune este ireversibilă! Sunteți sigur că doriți să ștergeți această carte?<p>
		<button class='buton t' style="border: 2px solid green;" onclick="document.getElementById('pop_up_2').style.display='none';"><b>Renunță</b></button>
		&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		<button class="buton t <?php echo $_SESSION['Drept'] != 'A' ? 'inactiv_t' : '' ?>" style='border: 2px solid red;' onclick="Sterge_Carte('<?php echo $carte['Cod_Carte']; ?>');" <?php echo $_SESSION['Drept'] != 'A' ? 'disabled' : '' ?>>
			<b>Șterge</b>
			<?php echo $_SESSION['Drept'] != 'A' ? "<span class='tooltip'>N-ai drepturi !!!</span>" : "" ?>
		</button>
	</div>
</div>