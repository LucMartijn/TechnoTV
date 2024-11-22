<?php

class DatabaseCommands {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function customQuery($sql) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch(Exception $e) {
            $logFile = 'error_log.txt';
            $errorMessage = date('Y-m-d H:i:s') . " - Error discovered in Syntax, please fix before continueing. ". $e->getMessage();
            error_log($errorMessage, 3, $logFile);
        }

    }

    public function customFetch($sql, $fetchMode) {
        try {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->$fetchMode();
        } catch(Exception $e) {
            $logFile = 'error_log.txt';
            $errorMessage = date('Y-m-d H:i:s') . " - Error discovered in Syntax, please fix before continueing. ". $e->getMessage();
            error_log($errorMessage, 3, $logFile);
        }
    }



    public function insertIntoTable($table, $column, $data) {
        try {
            $sql = "INSERT INTO $table ($column) VALUES (:data)";
            $stmt = $this->conn->prepare($sql);
            
            // Bind the data to the placeholder
            $stmt->bindParam(':data', $data);
        
            // Execute the statement
            $stmt->execute();
            
            echo "Data inserted successfully.";
            
        } catch (Exception $e) {
            // Log errors if any
            $logFile = 'error_log.txt';
            $errorMessage = date('Y-m-d H:i:s') . " - Error inserting data: " . $e->getMessage();
            error_log($errorMessage, 3, $logFile);
        }
    }


    public function insertNieuwsflash($h1,$dc1, $path1, $path2) {
        try {
            $sql = "INSERT INTO `nieuwsflash` (`title`, `flashdesc1`, `flashimage1`, `flashimage2`) 
            VALUES (:h1, :dc1, :path1, :path2)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':h1', $h1);
            $stmt->bindParam(':dc1', $dc1);
            $stmt->bindParam(':path1', $path1);
            $stmt->bindParam(':path2', $path2);
            $stmt->execute();
        } catch(Exception $e) {
            $logFile = 'error_log.txt';
            $errorMessage = date('Y-m-d H:i:s') . " - Error discovered in Syntax, please fix before continueing. ". $e->getMessage();
            error_log($errorMessage, 3, $logFile);
        }
    }

    public function insertGallery($h1, $path1, $path2, $path3, $path4, $path5, $path6, $path7, $path8, $path9, $path10  ) {
        try {
            $sql = "INSERT INTO `Gallery` 
                    (`title`, `image1_path`, `image2_path`, `image3_path`, `image4_path`, `image5_path`, 
                    `image6_path`, `image7_path`, `image8_path`, `image9_path`, `image10_path`) 
                    VALUES (:h1, :path1, :path2, :path3, :path4, :path5, :path6, :path7, :path8, :path9, :path10)";
            
            $stmt = $this->conn->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':h1', $h1);
            $stmt->bindParam(':path1', $path1);
            $stmt->bindParam(':path2', $path2);
            $stmt->bindParam(':path3', $path3);
            $stmt->bindParam(':path4', $path4);
            $stmt->bindParam(':path5', $path5);
            $stmt->bindParam(':path6', $path6);
            $stmt->bindParam(':path7', $path7);
            $stmt->bindParam(':path8', $path8);
            $stmt->bindParam(':path9', $path9);
            $stmt->bindParam(':path10', $path10);
            
            // Execute the statement
            $stmt->execute();
            
        } catch(Exception $e) {
            // Log the error
            $logFile = 'error_log.txt';
            $errorMessage = date('Y-m-d H:i:s') . " - SQL Error: " . $e->getMessage();
            error_log($errorMessage, 3, $logFile);
        }
    }

    public function insertStory($h1,$dc1, $dc2) {
        try {
            $sql = "INSERT INTO `story` (`title`, `storydesc1`, `storydesc2`) 
            VALUES (:h1, :dc1, :dc2)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':h1', $h1);
            $stmt->bindParam(':dc1', $dc1);
            $stmt->bindParam(':dc2', $dc2);
            $stmt->execute();
        } catch(Exception $e) {
            $logFile = 'error_log.txt';
            $errorMessage = date('Y-m-d H:i:s') . " - Error discovered in Syntax, please fix before continueing. ". $e->getMessage();
            error_log($errorMessage, 3, $logFile);
        }
    }

    /*public function insertDaily($h1,$dc1, $dc2,$date
    
     ) {
        try {
            $sql = "INSERT INTO `Daily` (`title`, `storydesc1`, `storydesc2`) 
            VALUES (:h1, :dc1, :dc2)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':h1', $h1);
            $stmt->bindParam(':dc1', $dc1);
            $stmt->bindParam(':dc2', $dc2);
            $stmt->execute();
        } catch(Exception $e) {
            $logFile = 'error_log.txt';
            $errorMessage = date('Y-m-d H:i:s') . " - Error discovered in Syntax, please fix before continueing. ". $e->getMessage();
            error_log($errorMessage, 3, $logFile);
        }
    }*/

    function insertSchedule($day, $slot, $time, $title, $description) {
        $ddate = date('Y-m-d');
        $date = new DateTime($ddate);
        $week = $date->format("W");
        echo "Weeknummer: $week";
        $sql = "INSERT INTO WeeklySchedule (day, slot, time, title, week, description)
         VALUES (:day, :slot, :time, :title, :week, :description)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':day', $day);
        $stmt->bindParam(':slot', $slot);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':week', $week);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
    }
    



    //table = Table name you want to update data from
    //setColumn = the column that will be updated
    //setValue = Data that you want to be put in the column
    //whereColumn = The column to specify what row to update
    //whereValue = The data that identifies the row to be updated.
    public function updateTable($table, $setColumn, $setValue, $whereColumn, $whereValue) {
        try {
            $sql = "UPDATE $table SET $setColumn = :setValue WHERE $whereColumn = :whereValue";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':setValue', $setValue);
            $stmt->bindParam(':whereValue', $whereValue);
            $stmt->execute();
        } catch(Exception $e) {
            $logFile = 'error_log.txt';
            $errorMessage = date('Y-m-d H:i:s') . " - Error discovered in Syntax, please fix before continuing. " . $e->getMessage();
            error_log($errorMessage, 3, $logFile);
        }
    }

    public function deleteFromTable($table, $where, $what) {
        try {
        $sql = "DELETE FROM $table WHERE $where = $what";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        } catch(Exception $e) {
            $logFile = 'error_log.txt';
            $errorMessage = date('Y-m-d H:i:s') . " - Error discovered in Syntax, please fix before continueing. ". $e->getMessage();
            error_log($errorMessage, 3, $logFile);
        }
    }
    /*public function KillConnection() {
        $conn = null;
    }*/
}
