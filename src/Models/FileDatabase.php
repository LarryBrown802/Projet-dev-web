<?php

namespace App\Models;

/**
 * Class FileDatabase
 * Implements the Database interface and provides functionality to interact with a CSV file-based database.
 */
class FileDatabase implements Database {

    /**
     * @var string The path to the database file.
     */
    private $path;

    /**
     * @var int The next available ID for a new record.
     */
    private $nextId = 0;

    /**
     * FileDatabase constructor.
     * @param string|null $dbname The name of the database file.
     * @param array $cols The columns of the database table.
     */
    public function __construct($dbname, $cols) {
        $this->path = __DIR__.DIRECTORY_SEPARATOR.$dbname.'.csv';
        
        if (!file_exists($this->path)) {
            $this->initializeDatabase($cols);
        } else {
            $this->updateNextId();   
        }
    }

    private function initializeDatabase($cols) {
        $file = fopen($this->path, 'w');
        array_unshift($cols, 'id');
        fputcsv($file, $cols);
        fclose($file);
    }

    /**
     * Updates the next available ID based on the existing records in the database file.
     */
    private function updateNextId() {
        $this->checkFileAccessibility();
     
        $file = fopen($this->path, 'r');
        $header = fgetcsv($file);
        $max_id = 0;
        while($row = fgetcsv($file)) {
            $max_id = max($max_id, (int)$row[0]);
        }
        fclose($file);
        $this->nextId = $max_id + 1;
    
    }

    private function checkFileAccessibility() {
        if (!file_exists($this->path) || !is_readable($this->path)) {
            throw new FileDatabaseException("Database file is not accessible.");
        }
    }

    /**
     * Retrieves all records from the database.
     * @return array The array of records.
     */
    public function getAllRecords() {
        $data = [];
        
        $this->checkFileAccessibility();
        
        $file = fopen($this->path, 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            $record = array_combine($header, $row);
            $data[] = $record;
        }

        fclose($file);
        return $data;
    }

    /**
     * Retrieves a record from the database based on its ID.
     * @param int $id The ID of the record.
     * @return array|null The record if found, null otherwise.
     */
    public function getRecord($id) {
        $this->checkFileAccessibility();

        $file = fopen($this->path, 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            $record = array_combine($header, $row);
            if ((int)$record['id'] === (int)$id) {
                fclose($file);
                return $record;
            }
        }

        fclose($file);
        return null;
    }

    /**
     * Inserts a new record into the database.
     * @param array $record The record to be inserted.
     * @return int The ID of the inserted record.
     */
    public function insertRecord($record) {
        $this->checkFileAccessibility();

        $file = fopen($this->path, 'a');
        $record = array('id' => $this->nextId) + $record;
        $this->nextId++;
        fputcsv($file, $record);
        fclose($file);
        return $record['id'];
    }

    /**
     * Updates a record in the database based on its ID.
     * @param int $id The ID of the record to be updated.
     * @param array $record The updated record.
     * @return bool True if the record was updated successfully, false otherwise.
     */
    public function updateRecord($id, $record) {
        $this->checkFileAccessibility();

        array_unshift($record, $id);
        $updated = false; // flag to check if the record was updated

        $file = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

     
        foreach ($file as $index => $line) {

            $data = str_getcsv($line);
        
            if (isset($data[0]) && $data[0] === $id) {
                var_dump($record);
                $file[$index] = implode(',', $record); 
                $updated = true; // Set the flag to true
                break; // Stop the loop after updating the line
            }
        }

        // Write the updated lines to the file
        if ($updated) {
            file_put_contents($this->path, implode("\n", $file)."\n");
        }

        return $updated;
    }
}


class FileDatabaseException extends \Exception {}
