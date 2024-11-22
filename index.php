<?php
include "php/dbConnect.php";
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>TechnoTV</title>
    <?php include 'php/Meta.php';?>
    <link href="css/index.css" rel="stylesheet">
    <script src="scripts/Script.js" type="text/javascript" defer></script>
    <?php
        session_start();
        //fetch data functions
        $commandRunner = new DatabaseCommands($conn);
        $sql = "SELECT * FROM nieuwsflash WHERE deleted = 0";
        $nieuwsdata = $commandRunner->customFetch($sql, 'fetchAll');
        $_SESSION['nieuwsdata'] = $nieuwsdata;

        $sql = "SELECT * FROM gallery WHERE deleted = 0";
        $GalleryData = $commandRunner->customFetch($sql, 'fetchAll');
        $_SESSION['GalleryData'] = $GalleryData;
        
        $sql = "SELECT * FROM Story WHERE deleted = 0";
        $StoryData = $commandRunner->customFetch($sql, 'fetchAll');
        $_SESSION['StoryData'] = $StoryData;

        $ddate = date('Y-m-d');
        $date = new DateTime($ddate);
        $week = $date->format("W");

        $sql = "SELECT * FROM weeklyschedule WHERE week = $week AND deleted = 0;";
        $WeeklyData = $commandRunner->customFetch($sql, 'fetchAll');
        $_SESSION['WeeklyData'] = $WeeklyData;
        
        $conn = null;
    ?>
    
</head>
<body id="index-body">
    <?php include 'php/Header.php';?>
    <main>
        <template id="index-slide-1">
            <section>
                <h1 id="template-h1-1"></h1><!--Template for Flash (Title, Article and 2 images)-->
                <article id="template-article-1"></article>
                <img id="template-image-1" src="">
                <img id="template-image-2" src="">
            </section>
        </template>
        <!--Template for Gallery (Title and 10 images)-->
        <template id="index-slide-2">
            <section>
                <h1 id="gallery-h1-1"></h1>
                <section class="gallery-row1">
                    <img class="gallery-img" id="gallery-image-1" src="">
                    <img class="gallery-img" id="gallery-image-3" src="">
                    <img class="gallery-img" id="gallery-image-5" src="">
                    <img class="gallery-img" id="gallery-image-7" src="">
                    <img class="gallery-img" id="gallery-image-9" src="">
                </section>
                <section class="gallery-row2">
                    <img class="gallery-img" id="gallery-image-2" src="">
                    <img class="gallery-img" id="gallery-image-4" src="">
                    <img class="gallery-img" id="gallery-image-6" src="">
                    <img class="gallery-img" id="gallery-image-8" src="">
                    <img class="gallery-img" id="gallery-image-10" src="">
                </section>
            </section>
        </template>


        <!--Template for Story (Title and 2 articles)-->
        <template id="index-slide-3">
            <section class="Story-section">
                <h1 id="Story-h1-1" ></h1>
                <article id="Story-article-1"></article>
                <article id="Story-article-2"></article>
            </section>
        </template>
        <!--Template for Weekly Roster-->
        <template id="index-slide-4">
            <section class="Slide WeeklySchedule">
                <h2 id="schedule-h2-header">Weekrooster</h2>
                <section id="weekly-schedule-container">
                    <ul id="monday-schedule">
                        <h3 class="schedule-ul-h3">Maandag</h3>
                    </ul>
                    <ul id="tuesday-schedule">
                        <h3 class="schedule-ul-h3">Dinsdag</h3>
                    </ul>
                    <ul id="wednesday-schedule">
                        <h3 class="schedule-ul-h3">Woensdag</h3>
                    </ul>
                    <ul id="thursday-schedule">
                        <h3 class="schedule-ul-h3">Donderdag</h3>
                    </ul>
                    <ul id="friday-schedule">
                        <h3 class="schedule-ul-h3">Vrijdag</h3>  
                    </ul>
                </section>
            </section>
        </template>

<!-- TODO Remove unused files, such as json files, images, Etc -->

        <section id="index-section">
            <button id="FullScrnButton" onclick="var el = document.getElementById('index-slideshow-frame'); el.requestFullscreen();">
                Go Full Screen!
            </button>

            <section id="index-slideshow-frame">
            </section>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Mijn Webpagina</p>
    </footer>

</body>
</html>
