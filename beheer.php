<?php
include "php/dbConnect.php";
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>TechnoTV - Beheer</title>
    <?php include 'php/Meta.php';?>
    <link href="css/manage.css" rel="stylesheet">
    <script src="scripts/manage.js" type="text/javascript" defer></script>
    <?php
        session_start();
        if (isset($_GET['name'])) {
            $_SESSION['name'] = $_GET['name'];
        }

        $commandRunner = new DatabaseCommands($conn);
    ?>
</head>
<body id="manage-body">
    <?php include 'php/Header.php';?>
    <main>
    <form class="primary-form" action="beheer.php" method="GET">
        <h1>Selecteer NieuwsElement</h1>
    <label for="name">Titel:</label>
    <input type="text" id="name" name="name" value="<?php echo $_SESSION['name']?>" required>

    <label for="category">Category:</label>
    <select id="category" name="type" required>
        <option value="nieuwsflash">Nieuwsflash</option>
        <option value="weeklySchedule">Weekrooster</option>
        <option value="gallery">Gallery</option>
        <option value="story">Verhaal</option>
    </select>

    <button type="submit" name="submit">Submit</button>
</form>
        <?php
            if (!isset($_GET['submit'])) {//delete form
                        if (isset($_SESSION["error"])) {
                            echo '<p class="error">'. $_SESSION["error"]. '</p>';
                            unset($_SESSION["error"]);
                        }else if (isset($_GET["delete"])) {
                            $formType = $_GET['table'];
                            $stmt = $conn->prepare('UPDATE ' . $formType . ' SET deleted = 1 WHERE id = :id');
                            $stmt->bindParam(':id', $_GET["id"], PDO::PARAM_INT);
                            $stmt->execute();
                            
                            if ($stmt->rowCount() > 0) {
                                echo '<p class="success">Element met ID '.$_GET["id"].' is verwijderd!</p>';
                                $_GET = null;
                            } else {
                                echo '<p class="error">Er is geen element gevonden met de ID "'.$_GET["id"].'" of de item is al verwijderd. </p>';
                            }
                        }
                        else if (isset($_GET['edit'])) {
                            if (isset($_GET['Deleted']) && $_GET['Deleted'] == 1) { $isDeleted = 1;}
                            else if (isset($_GET['Deleted']) && $_GET['Deleted'] == 0) { $isDeleted = 0;}
                            $editId = $_GET['id'];
                            $newTitle = $_GET['title'];

                            //update switch statement
                            switch ($_GET["table"]) {
                                case 'gallery':
                                        $stmt = $conn->prepare('UPDATE gallery SET title = :title,deleted = :isDeleted WHERE id = :id');
                                        $stmt->bindParam(':title', $newTitle, PDO::PARAM_STR);
                                        $stmt->bindParam(':id', $editId, PDO::PARAM_INT);
                                        $stmt->bindParam(':isDeleted', $isDeleted, PDO::PARAM_INT);
                                    break;
                                
                                case 'story':
                                    $newDescription1 = $_GET['description1'];
                                    $newDescription2 = $_GET['description2'];
                                    $stmt = $conn->prepare('UPDATE story SET title = :title, storydesc1 = :description1,deleted = :isDeleted, storydesc2 = :description2 WHERE id = :id');
                                    $stmt->bindParam(':title', $newTitle, PDO::PARAM_STR);
                                    $stmt->bindParam(':description1', $newDescription1, PDO::PARAM_STR);
                                    $stmt->bindParam(':description2', $newDescription2, PDO::PARAM_STR);
                                    $stmt->bindParam(':isDeleted', $isDeleted, PDO::PARAM_INT);
                                    $stmt->bindParam(':id', $editId, PDO::PARAM_INT);
                                    break;
                                
                                case 'nieuwsflash':
                                    $newDescription = $_GET['description'];
                                    $stmt = $conn->prepare('UPDATE nieuwsflash SET title = :title,deleted = :isDeleted ,flashdesc1 = :description  WHERE id = :id');
                                    $stmt->bindParam(':title', $newTitle, PDO::PARAM_STR);
                                    $stmt->bindParam(':description', $newDescription, PDO::PARAM_STR);
                                    $stmt->bindParam(':id', $editId, PDO::PARAM_INT);
                                    $stmt->bindParam(':isDeleted', $isDeleted, PDO::PARAM_INT);
                                    break;
                                
                                case 'weeklySchedule':
                                try {
                                    $newDescription = $_GET['description'];
                                    $stmt = $conn->prepare('UPDATE weeklyschedule SET title = :title,deleted = :isDeleted, description = :description WHERE id = :id');
                                    $stmt->bindParam(':title', $newTitle, PDO::PARAM_STR);
                                    $stmt->bindParam(':description', $newDescription, PDO::PARAM_STR);
                                    $stmt->bindParam(':isDeleted', $isDeleted, PDO::PARAM_INT);
                                    $stmt->bindParam(':id', $editId, PDO::PARAM_INT);
                                } catch (PDOException $e) {
                                // Handle any errors
                                echo "Error: " . $e->getMessage();
                                }
                                    break;
                                
                                default:
                                    // Code to execute if $value does not match any case
                                    echo "Geen tabel met die naam gevonden.";
                                    break;
                            }

                            $stmt->execute();
                            if ($stmt->rowCount() > 0) {
                                echo "<p class='success'>Element met ID '$editId' is bewerkt!</p>";
                            } else {
                                echo "<p class='error'>Geen element gevonden met ID '$editId' of er zijn geen veranderingen gemaakt.</p>    ";
                            }
                        }
                    }
                        else {//selection for elements
                            $name = $_GET['name'];
                            $formType = $_GET['type'];
                            $searchTerm = '%'.$name.'%';

                            $query = 'SELECT * FROM ' . $formType . ' WHERE title LIKE :title';
                            $stmt = $conn->prepare($query);
                            $stmt->bindParam(':title', $searchTerm, PDO::PARAM_STR);
                            $stmt->execute();
                            
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if (!empty($result)) {
                                switch ($_GET["type"]) {
                                    case 'gallery':
                                        foreach ($result as $row) {
                                        echo '<form action="beheer.php" method="get">
                                                <h1>Beheer Gallerij</h1>
                                                <input type="hidden" name="id" value="'. $row['id']. '">
                                                <input type="hidden" name="table" value="gallery">
                                                <input type="hidden" name="title" value="'. $row['title']. '">
                                                <label for="title">Titel:</label>
                                                <input type="text" id="title" name="title" value="'. $row['title']. '" required>
                                                ';
                                                    if ($row['deleted'] == 1) {
                                                    echo '
                                                    <label for="Deleted">Item is verwijderd, ongedaan maken?:</label>
                                                    <select id="deleted" name="Deleted" required>
                                                        <option value="1">Nee</option>
                                                        <option value="0">Ja</option>
                                                    </select>';
                                                    }
                                                    echo '
                                                <button type="submit" name="delete">Delete</button>
                                                <button type="submit" name="edit">Edit</button>
                                            </form>';}
                                       break;
                                    
                                    case 'story':
                                        foreach ($result as $row) {
                                            echo '<form action="beheer.php" method="get">
                                                    <h1>Beheer verhaal</h1>
                                                    <input type="hidden" name="id" value="'. $row['id']. '">
                                                    <input type="hidden" name="table" value="story">
                                                    <input type="hidden" name="title" value="'. $row['title']. '">
                                                    <label for="title">Titel:</label>
                                                    <input type="text" id="title" name="title" value="'. $row['title']. '" required>
                                                    <label for="description1">Deel 1:</label>
                                                    <textarea id="description" name="description1" required>'. $row['storydesc1']. '</textarea>
                                                    <label for="description2">Deel 2:</label>
                                                    <textarea id="description" name="description2" required>'. $row['storydesc2']. '</textarea>
                                                    ';
                                                    if ($row['deleted'] == 1) {
                                                    echo '
                                                    <label for="Deleted">Item is verwijderd, ongedaan maken?:</label>
                                                    <select id="deleted" name="Deleted" required>
                                                        <option value="1">Nee</option>
                                                        <option value="0">Ja</option>
                                                    </select>';
                                                    }
                                                    echo '
                                                    <button type="submit" name="delete">Delete</button>
                                                    <button type="submit" name="edit">Edit</button>
                                                </form>';
                                        }
                                        break;
                                    
                                    case 'nieuwsflash':
                                        foreach ($result as $row) {
                                            echo '<form action="beheer.php" method="get">
                                                    <h1>Beheer Nieuwsflash</h1>
                                                    <input type="hidden" name="id" value="'. $row['id']. '">
                                                    <input type="hidden" name="table" value="nieuwsflash">
                                                    <input type="hidden" name="title" value="'. $row['title']. '">
                                                    <label for="title">Titel:</label>
                                                    <input type="text" id="title" name="title" value="'. $row['title']. '" required>
                                                    <label for="description">Beschrijving:</label>
                                                    <textarea id="description" name="description" required>'. $row['flashdesc1']. '</textarea>';
                                                    if ($row['deleted'] == 1) {
                                                    echo '
                                                    <label for="Deleted">Item is verwijderd, ongedaan maken?:</label>
                                                    <select id="deleted" name="Deleted" required>
                                                        <option value="1">Nee</option>
                                                        <option value="0">Ja</option>
                                                    </select>';
                                                    }
                                                    echo '
                                                    <button type="submit" name="delete">Delete</button>
                                                    <button type="submit" name="edit">Edit</button>
                                                </form>';
                                        }
                                        break;
                                    
                                    case 'weeklySchedule':
                                        foreach ($result as $row) {
                                            echo '<form action="beheer.php" method="get">
                                                    <h1>Beheer WeekElement</h1>
                                                    <input type="hidden" name="id" value="'. $row['id']. '">
                                                    <input type="hidden" name="table" value="weeklySchedule">
                                                    <input type="hidden" name="id" value="'. $row['id']. '">
                                                    <input type="hidden" name="day" value="'. $row['day']. '">
                                                    <input type="hidden" name="slot" value="'. $row['slot']. '">
                                                    <input type="hidden" name="time" value="'. $row['time']. '">
                                                    <input type="hidden" name="week" value="'. $row['week']. '">
                                                    <input type="hidden" name="created_at" value="'. $row['created_at']. '">
                                                    
                                                    <label for="title">Title:</label>
                                                    <input type="text" id="title" name="title" value="'. $row['title']. '" required>
                                                    
                                                    <label for="description">Wie?:</label>
                                                    <input type="text" id="description" name="description" value="'. $row['description']. '" required>

                                                    <label for="Week">Week?:</label>
                                                    <input type="text" id="Week" name="Week" value="'. $row['week']. '" required>
                                                    ';
                                                    if ($row['deleted'] == 1) {
                                                    echo '
                                                    <label for="Deleted">Item is verwijderd, ongedaan maken?:</label>
                                                    <select id="deleted" name="Deleted" required>
                                                        <option value="1">Nee</option>
                                                        <option value="0">Ja</option>
                                                    </select>';
                                                    }
                                                    echo '
                                                    
                                                    <button type="submit" name="delete">Delete</button>
                                                    <button type="submit" name="edit">Edit</button>
                                                </form>';}
                                        break;
                                    
                                    default:
                                        // Code to execute if $value does not match any case
                                        echo "Er is geen form met deze naam gevonden!";
                                        break;
                                }

                            }else {
                                $_SESSION["error"] = 'Item "'.$name. '" niet gevonden in tabel: '. $_GET["type"];
                                header('Location: beheer.php?name='. $_GET["name"]);
                            }
                            
                        }
//if a name is submitted, echo all the elements with the submitted name. Give them a form with 2 buttons: delete and edit, and have a hidden form part that contains the item id in the database and the corresponding table name



        
        
        
        ?>

    </main>
    <footer>
        <p>&copy; 2024 TechnoTV</p>
    </footer>
</body>
</html>

