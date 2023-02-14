<?php
	//Extragerea detaliilor pentru o anumita editura

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
		$query = "SELECT Nume, Profit_An, edituri.ID_Editura, COUNT(Cod_Carte) AS NrTitluri, Cod_Editura FROM edituri
                    LEFT JOIN editura_carte ON edituri.ID_Editura=editura_carte.ID_Editura
                    WHERE edituri.ID_Editura=? GROUP BY edituri.ID_Editura";
		$query = mysqli_prepare($con, $query);
		mysqli_stmt_bind_param($query, "s", $nr);
		mysqli_stmt_execute($query);
		$editura = mysqli_fetch_assoc(mysqli_stmt_get_result($query));
?>

		<p class='text'><b>Nume:</b></p><p class='text'><?php echo $editura["Nume"]; ?></p><br>
		<p class='text'><b>Cod:</b></p><p class='text'><?php echo $editura["Cod_Editura"]; ?></p><br>
		<p class='text'><b>Profit anual:</b></p><p class='text'><?php echo $editura["Profit_An"]; ?></p><br>
		<p class='text'><b>Nr de cărți publicate:</b></p><p class='text'><?php echo $editura["NrTitluri"]; ?></p><br>
        -----------------------
        <p class='text'><b>Contact:</b><br><br>
<?php
        $query = "SELECT Tara, Oras, Strada, Nr, Tel_1, Tel_2, E_mail FROM contacte WHERE ID_Editura=?";
        $query = mysqli_prepare($con, $query);
		mysqli_stmt_bind_param($query, "s", $nr);
		mysqli_stmt_execute($query);
        $result = mysqli_stmt_get_result($query);
        if (mysqli_num_rows($result))
            while ($contact = mysqli_fetch_assoc($result))
            {
?>
		        <p class='text'><?php echo $contact["Oras"] . ", " . $contact["Tara"]; ?></p>
		        <p class='text'><?php echo "Str. " . $contact["Strada"] . ", nr. " . $contact["Nr"]; ?></p>
		        <p class='text'><?php echo "Tel.:  " . $contact["Tel_1"] . ", " . $contact["Tel_2"]; ?></p>
	            <p class='text'><?php echo "E-Mail: " . $contact["E_mail"]; ?></p><br>
                -----
                <br>
<?php
            }
?>
		
		<button class="buton t" onclick="document.getElementById('pop_up').style.display='none';">Închide</button>
		&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		<button class="buton t <?php echo $_SESSION['Drept'] != 'A' ? 'inactiv_t' : '' ?>" style='border: 2px solid blue;' onclick="Edit_Editura('<?php echo $editura['ID_Editura'];?>');" <?php echo $_SESSION['Drept'] != 'A' ? 'disabled' : '' ?>>
			<b><span><img src='Icons/Edit.svg' style='width: 20px;'>Edit</span></b>
            <?php echo $_SESSION['Drept'] != 'A' ? "<span class='tooltip'>Trebuie să aveți drepturi de Admin ca să editați atributele unei edituri!</span>" : "" ?>
		</button>
		&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		<button class="buton t <?php echo $_SESSION['Drept'] != 'A' ? 'inactiv_t' : '' ?>" style='border: 2px solid red;' onclick="document.getElementById('pop_up_2').style.removeProperty('display');" <?php echo $_SESSION['Drept'] != 'A' ? 'disabled' : '' ?>>
			<b><span><img src='Icons/Delete.svg' style='width: 20px;'>Șterge</span></b>
            <?php echo $_SESSION['Drept'] != 'A' ? "<span class='tooltip'>Trebuie să aveți drepturi de Admin ca să ștergeți o editura</span>" : "" ?>
		</button>
	
<?php mysqli_close($con); } ?>

<div class="fundal pop_up" id="pop_up_2" style="display: none;">
    <div class="chenar info_box confirm_box" id="Delete">
		<p class="text subtitlu">ATENȚIE !</p>
		<p class="text subtitlu">Această acțiune este ireversibilă! Sunteți sigur că doriți să ștergeți această carte?<p>
		<button class='buton t' style="border: 2px solid green;" onclick="document.getElementById('pop_up_2').style.display='none';"><b>Renunță</b></button>
		&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		<button class="buton t <?php echo $_SESSION['Drept'] != 'A' ? 'inactiv_t' : '' ?>" style='border: 2px solid red;' onclick="Sterge_Editura('<?php echo $editura['ID_Editura']; ?>');" <?php echo $_SESSION['Drept'] != 'A' ? 'disabled' : '' ?>>
			<b>Șterge</b>
			<?php echo $_SESSION['Drept'] != 'A' ? "<span class='tooltip'>N-ai drepturi !!!</span>" : "" ?>
		</button>
	</div>
</div>