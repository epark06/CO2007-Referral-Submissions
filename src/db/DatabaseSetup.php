<?php
//filename: DatabaseSetup.php
declare(strict_types= 1);

/**
 * Class for creating the table co2007_referral
 */
class DatabaseSetup {
    /**
     * Shared private PDO connection for member functions of DatabaseSetup.
     */
    private PDO $pdo;

    /**
     * Constructor function to supply a connection to the database for use in setup.
     * @param PDO $pdo Pre-existing PDO connection to a database
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Public function to create the schema for the database.
     * @return string|null Returns `null` on success, returns a comma-separated list of all tables
     * which failed to be created on fail
     */
    public function createDatabaseSchema() : ?string {
        $succesStates = [
            "co2007_referral" => $this->createReferralTable()
        ];

        if(in_array(null, $succesStates)) {
            // Returns a comma-separated string of all keys which map to a null value in formats ["X", "X and Y", "X, Y and Z"]
            $badTables = array_keys(array_filter($succesStates, function($state) {
                return !$state;
            }));
            $plurals = array_slice($badTables, 0, -1);
            return ($plurals ? implode(", ", $plurals) . " and " : "") . end($badTables);
        }

        return null;
    }

    /**
     * Private static function for printing an error message from a failed SQL query.
     * @param PDOException $e The error message to print
     * @return null Fail state flag to be passed through a parent function
     */
    private static function printError(PDOException $e) : null {
        echo "Error: " . $e->getMessage() . "";
        return null;
    }

    /**
     * Private function for a prepared SQL query with a single expected result
     * with error-handling in fail-case.
     * @param string $sql The SQL query to run
     * @return int|null Returns 1 on success, returns `null` on fail
     */
    private function sqlExec(string $sql) : ?int {
        try {
            $this->pdo->exec($sql);
            return 1;
        }
        catch(PDOException $e) {
            return $this->printError($e);
        }
    }

    /**
     * Private function to create the co2007_referral table.
     * @return int|null Returns 1 on success, returns `null` on fail
     */
    private function createReferralTable() : ?int {
        $sql = <<<SQL
        CREATE TABLE IF NOT EXISTS co2007_referral (
            referral_id INT PRIMARY KEY,
            physician_name VARCHAR(30) NOT NULL,
            clinic_name VARCHAR(30) NOT NULL,
            patient_phone_no VARCHAR(15) NOT NULL,
            patient_email VARCHAR(40) NOT NULL,
            fax_no VARCHAR(13),
            patient_name VARCHAR(30) NOT NULL,
            patient_dob DATE NOT NULL,
            urgency_level VARCHAR(30) NOT NULL,
            referral_reason TEXT
        );
        SQL;

        $tableCreated = $this->sqlExec($sql);

        $successMsg = "Successfully created 'co2007_referral' table.</br>";
        $failMsg = "Failed to create 'co2007_referral' table.</br>";
        echo ($tableCreated && TESTING) ? $successMsg : (TESTING ? $failMsg : "");

        return $tableCreated ? 1 : null;
    }

    /**
     * Function to drop the co2007_referral table.
     * @return int|null Returns 1 on success, returns `null` on fail
     */
    public function dropReferralTable() : ?int {
        $sql = <<<SQL
        DROP TABLE IF EXISTS co2007_referral;
        SQL;

        $tableDropped = $this->sqlExec($sql);
        $successMsg = "Sucessfully dropped 'co2007_referral' table.</br>";
        $failMsg = "Failed to drop 'co2007_referral' table.</br>";
        echo ($tableDropped && TESTING) ? $successMsg : (TESTING ? $failMsg : "");

        return $tableDropped ? 1 : null;
    }
}

?>