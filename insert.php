<?php
include "php/dbConnect.php";
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <title>TechnoTV</title>
    <?php include 'php/Meta.php'; ?>
    <link href="css/index.css" rel="stylesheet">
    <script src="scripts/insert.js" type="text/javascript" defer></script>

    <?php

        $commandRunner = new DatabaseCommands($conn);

        function insertFlash(){
            if (!isset($_SESSION["flash-image2-log"]))  {
                $_SESSION["flash-image2-log"] = null;
            } 
            if (isset($_SESSION["flash-image1-log"])){
                global $commandRunner;
                $commandRunner->insertNieuwsflash($_POST["flash-header1"], $_POST["flash-desc1"], $_SESSION["flash-image1-log"], $_SESSION["flash-image2-log"]);
            }
            unset($_SESSION["flash-image1-log"]);
            unset($_SESSION["flash-image2-log"]);
        }
        function insertGallery(){
            global $commandRunner;

        
            // Call the insertGallery function with the gallery title and image paths
            $commandRunner->insertGallery($_POST["gallery-header1"],
            $_SESSION["gallery-image1-log"],
            $_SESSION["gallery-image2-log"],
            $_SESSION["gallery-image3-log"],
            $_SESSION["gallery-image4-log"],
            $_SESSION["gallery-image5-log"],
            $_SESSION["gallery-image6-log"],
            $_SESSION["gallery-image7-log"],
            $_SESSION["gallery-image8-log"],
            $_SESSION["gallery-image9-log"],
            $_SESSION["gallery-image10-log"]);
        }


        function insertStory(){
            global $commandRunner;

        
            // Call the insertGallery function with the gallery title and image paths
            $commandRunner->insertStory($_POST["Story-header1"], $_POST["Story-desc1"], $_POST["Story-desc2"]);
            }
        
        function insertWeekly(){
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] === 'WeeklySchedule') {
                global $commandRunner;
                $days = ['Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag'];
                $slots = ['Eerste', 'Tweede', 'Derde'];
            
                // Loop through each day and slot to retrieve the submitted form data
                foreach ($days as $day) {
                    foreach ($slots as $slot) {
                        // Retrieve the submitted time, title, and description for each day and slot
                        $time = isset($_POST["{$day}-{$slot}-time"]) ? $_POST["{$day}-{$slot}-time"] : null;
                        $title = isset($_POST["{$day}-{$slot}-title"]) ? $_POST["{$day}-{$slot}-title"] : null;
                        $description = isset($_POST["{$day}-{$slot}-description"]) ? $_POST["{$day}-{$slot}-description"] : null;
                        $time = substr($time, 0, 5);
                        // Only insert if all required fields are set
                        if ($time && $title && $description) {
                            $commandRunner->insertSchedule($day, $slot, $time, $title, $description);
                        }
                    }
                }
            }
            header("Location: insert.php");
        }
    ?>
    <?php
        $uploadCounter = 0;
        function appendImageFile($formName, $formType){
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Random string for file name generation
                $encodedString = random_bytes(30);
                $DecodedString = bin2hex($encodedString);

                global $uploadCounter;
                $securityCheckResults = 0;//Check for uploading errors

                $fileName = basename($_FILES["$formName"]["name"]);
                $imgDir = "newsImages/";
                $formNameConst = "$formName-log";
                $imageFileType = strtolower(pathinfo($_FILES["$formName"]["name"], PATHINFO_EXTENSION));
                

                $FileInfo = $imgDir . $DecodedString . "." . $imageFileType;
                if (file_exists($FileInfo)) {
                    echo "Bestand bestaat al! ";
                    $securityCheckResults = 1;
                }

                $check = getimagesize($_FILES["$formName"]["tmp_name"]);

                if ($check == false) {
                    echo "Gekozen file is geen foto.";
                    $securityCheckResults = 1;
                }

                if ($_FILES["$formName"]["size"] > 5000000) {
                    echo "Het gekozen bestand is te groot.";
                    $securityCheckResults = 1;
                }

                if (isset($_SESSION[$formName]) && $_SESSION[$formName] == $fileName) {
                    echo "Het bestand `'$fileName'` is al geupload.<br>";
                    $securityCheckResults = 1;
                } else {
                    $_SESSION[$formName] = $fileName;
                }

                if ($securityCheckResults == 0) {
                    if (move_uploaded_file($_FILES["$formName"]["tmp_name"], $FileInfo)) {

                        $_SESSION[$formNameConst] = $FileInfo;
                        $uploadCounter++;
                    } else {
                        echo "Er is iets fout gegaan, Probeer astublieft opnieuw!";
                    }
                }
            }
        }
        ?>   
</head>

<body id="insert-body">
    <?php include 'php/Header.php'; ?>

    <main>
        <form id="buttons" action="insert.php" method="get">
            <section id="insert-form-select-knop">
                <input type="submit" name="Nieuwsflash" class="insert-button" value="Nieuwsflash">
                <input type="submit" name="WeeklySchedule" class="insert-button" value="Weekrooster">
                <input type="submit" name="Gallery" class="insert-button" value="Gallerij">
                <input type="submit" name="Story" class="insert-button" value="Verhaal">
            </section>
        </form>
        <section id="form-input-section"><!--This is the section where the form gets echoed into-->
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formType = $_POST['form_type'] ?? '';

            switch ($formType) {
                case 'WeeklySchedule':
                    insertWeekly();
                    echo '<p>Weekrooster is toegevoegd!</p>';
                    break;

                case 'Story':
                    
                    insertStory();
                    echo '<p>Verhaal is toegevoegd!</p>';
                    break;

                case 'Gallery':
                    

                    $galleryImages = [
                        "gallery-image1", "gallery-image2", "gallery-image3", "gallery-image4", "gallery-image5",
                        "gallery-image6", "gallery-image7", "gallery-image8", "gallery-image9", "gallery-image10"
                    ];
                    
                    $uploadCounter = 0;
                    
                    foreach ($galleryImages as $image) {
                        if (isset($_FILES[$image]) && $_FILES[$image]['error'] == UPLOAD_ERR_OK) {
                            appendImageFile($image, "Gallery");
                        } else {
                            $_SESSION[$image.'-log'] = null;
                        }
                    }
                    
                    if ($uploadCounter >= 3) {
                        insertGallery();
                        $uploadCounter = 0;
                        echo '<p>Gallerij is toegevoegd!</p>';
                    }
                    break;

                case 'NieuwsFlash':
                    if (!isset($_POST['flash-image1'])) {

                        appendImageFile("flash-image1", "Nieuwsflash");

                        if ($_FILES["flash-image2"]["error"] != 4) {

                            appendImageFile("flash-image2", "Nieuwsflash");
                        }
                        insertFlash();
                        echo "<p>Nieuwsflash is toegevoegd!</p>";
                    }
                    break;

                default:
                    // Handle unknown form type
                    break;
            }
        }
        //Form echo section
            if (isset($_GET['Gallery'])) {
                echo "
                    <form action='insert.php' method='post' enctype='multipart/form-data'>
                        <fieldset class='uploadForm'>
                            <legend id='form-legend'>Gallerij</legend>
                            <input type='hidden' name='form_type' value='Gallery'>
                            <input type='text' name='gallery-header1' class='gallery-form-elements' id='gallery-header1' placeholder='Voer hier de Header in.' required><br><br>
                            
                            <label for='gallery-image1' class='custom-file-upload'>Upload Foto 1</label>
                            <input type='file' name='gallery-image1' class='gallery-form-elements' id='gallery-image1' accept='image/*' required>
                            <span class='file-name' id='file-name1'>Geen file Geselecteerd</span>
                            
                            <label for='gallery-image2' class='custom-file-upload'>Upload Foto 2</label>
                            <input type='file' name='gallery-image2' class='gallery-form-elements' id='gallery-image2' accept='image/*' required>
                            <span class='file-name' id='file-name2'>Geen file Geselecteerd</span>
                            
                            <label for='gallery-image3' class='custom-file-upload'>Upload Foto 3</label>
                            <input type='file' name='gallery-image3' class='gallery-form-elements' id='gallery-image3' accept='image/*' required>
                            <span class='file-name' id='file-name3'>Geen file Geselecteerd</span>
                            
                            <label for='gallery-image4' class='custom-file-upload'>Upload Foto 4</label>
                            <input type='file' name='gallery-image4' class='gallery-form-elements' id='gallery-image4' accept='image/*'>
                            <span class='file-name' id='file-name4'>Geen file Geselecteerd</span>
                            
                            <label for='gallery-image5' class='custom-file-upload'>Upload Foto 5</label>
                            <input type='file' name='gallery-image5' class='gallery-form-elements' id='gallery-image5' accept='image/*'>
                            <span class='file-name' id='file-name5'>Geen file Geselecteerd</span>
                            
                            <label for='gallery-image6' class='custom-file-upload'>Upload Foto 6</label>
                            <input type='file' name='gallery-image6' class='gallery-form-elements' id='gallery-image6' accept='image/*'>
                            <span class='file-name' id='file-name6'>Geen file Geselecteerd</span>
                            
                            <label for='gallery-image7' class='custom-file-upload'>Upload Foto 7</label>
                            <input type='file' name='gallery-image7' class='gallery-form-elements' id='gallery-image7' accept='image/*'>
                            <span class='file-name' id='file-name7'>Geen file Geselecteerd</span>
                            
                            <label for='gallery-image8' class='custom-file-upload'>Upload Foto 8</label>
                            <input type='file' name='gallery-image8' class='gallery-form-elements' id='gallery-image8' accept='image/*'>
                            <span class='file-name' id='file-name8'>Geen file Geselecteerd</span>
                            
                            <label for='gallery-image9' class='custom-file-upload'>Upload Foto 9</label>
                            <input type='file' name='gallery-image9' class='gallery-form-elements' id='gallery-image9' accept='image/*'>
                            <span class='file-name' id='file-name9'>Geen file Geselecteerd</span>

                            <label for='gallery-image10' class='custom-file-upload'>Upload Foto 10</label>
                            <input type='file' name='gallery-image10' class='gallery-form-elements' id='gallery-image10' accept='image/*'>
                            <span class='file-name' id='file-name10'>Geen file Geselecteerd</span>
                            
                            <button type='submit' name='submit' class='btn'>Upload</button>
                        </fieldset>
                    </form>";
            } else if (isset($_GET['WeeklySchedule'])) {
                echo "<form id='schedule-form' action='insert.php' method='post'>";
                echo "<h2 id='WeekSchedule-Title'>Weekrooster</h2>";
                echo "<input type='hidden' name='form_type' value='WeeklySchedule'>";
                $days = ['Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag'];
                $slots = ['Eerste', 'Tweede', 'Derde'];
    
                foreach ($days as $day) {//Foreach to loop through the days
                    echo "<fieldset class='uploadForm'>";
                    echo "<legend class='header-elements' id='form-legend'>$day</legend>";
                    foreach ($slots as $slot) {
                        $isRequired = ($slot === 'Morning') ? 'required' : '';
                        echo "<section class='slot-input'>";
                        echo "<h4 class='header-elements'>$slot Slot</h4>";
                        echo "<label class='header-elements' for='{$day}-{$slot}-time'>Tijd:</label>";
                        echo "<input type='time' id='{$day}-{$slot}-time' name='{$day}-{$slot}-time' step='60' $isRequired>";
                        echo "<label class='header-elements' for='{$day}-{$slot}-title'>Titel:</label>";
                        echo "<input type='text' id='{$day}-{$slot}-title' name='{$day}-{$slot}-title' $isRequired>";
                        echo "<label class='header-elements' for='{$day}-{$slot}-description'>Wie?:</label>";
                        echo "<textarea id='{$day}-{$slot}-description' name='{$day}-{$slot}-description' $isRequired></textarea>";
                        echo "</section>";
                    }
                    echo "</fieldset>";
                }
                echo "<button type='submit' name='submit' class='btn'>Update Schedule</button>";
                echo "</form>";
            } else if (isset($_GET['Story'])) {
                echo "
                <form action='insert.php' method='post' enctype='multipart/form-data'>
                    <fieldset class='uploadForm'>
                    <legend id='form-legend'>Verhaal</legend>
                        <input type='hidden' name='form_type' value='Story'>
                        <input type='text' class='Story-elements' name='Story-header1' id='Story-header1' placeholder='Voer hier een header in.' required>
                        <input type='text' class='Story-elements' name='Story-desc1' id='Story-desc1' placeholder='Voer hier het artikel in.' required>
                        <input type='text' class='Story-elements' name='Story-desc2' id='Story-desc2' placeholder='Voer hier het artikel in.' required>
                        <br>
                        <button type='submit' name='submit' class='btn'>Upload</button>
                    </fieldset>
                </form>";
            } else {//NieuwsFlash form. Else to default to it.
                echo "
                <form action='insert.php' method='post' enctype='multipart/form-data'>
                    <fieldset class='uploadForm' action='insert.php' method='post' enctype='multipart/form-data'>
                        <legend id='form-legend'>NieuwsFlash</legend>
                            <input type='hidden' class='flash-form-elements' name='form_type' value='NieuwsFlash'>
                            <input type='text' class='flash-form-elements' name='flash-header1' id='flash-header1' placeholder='Voer hier een header in.' required>
                            <input type='text' class='flash-form-elements' name='flash-desc1' id='flash-desc1' placeholder='Voer hier het artikel in.' required>
                            <form>
                                <label for='flash-image1' class='custom-file-upload'>
                                    Upload Foto 1
                                </label>
                                <input type='file' class='flash-form-elements' name='flash-image1' id='flash-image1' accept='image/*' required>
                                <span id='file-name1' class='file-name'>Geen file Geselecteerd</span>

                                <label for='flash-image2' class='custom-file-upload'>
                                    Upload Foto 2
                                </label>
                                <input type='file' class='flash-form-elements' name='flash-image2' id='flash-image2' accept='image/*'>
                                <span id='file-name2' class='file-name'>Geen file Geselecteerd</span>
                            </form>

                            <br>
                            <button type='submit' name='submit' class='btn'>Upload</button>
                    </fieldset>
                </form>";
            }
            
        ?>
        </section>
    </main>

    <footer>
        <p>&copy;||TechnoTV</p>
    </footer>
</body>

</html>
