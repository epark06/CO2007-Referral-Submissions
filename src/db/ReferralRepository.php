<?php
//filename: ReferralRepository.php
declare(strict_types=1);

define("REFERRAL_DEFAULTS", [
    "referral_id" => null,
    "physician_name" => null,
    "clinic_name" => null,
    "patient_phone_no" => null,
    "patient_email" => null,
    "fax_no" => null,
    "patient_name" => null,
    "patient_dob" => null,
    "urgency_level" => null,
    "referral_reason" => null
]);

/**
 * Class for managing data in the co2007_referral table
 */
class ReferralRepository {
    private PDO $pdo;

    /**
     * Constructor for ReferralRepository, supplies a pre-existing PDO connection
     * For connection to the database
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
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
     * Private function for a prepared SQL query expected to return multiple rows
     * with error-handling in fail-case. 
     * @param PDOStatement $stmt Prepared SQL statement to execute
     * @return int|null Returns 1 on success, returns `null` on fail
     */
    private function sqlExecute(PDOStatement &$stmt) : ?int {
        try {
            $stmt->execute();
            return 1;
        }
        catch(PDOException $e) {
            return $this->printError($e);
        }
    }

    /**
     * Private function for an SQL query with no bound parameters
     * with error-handling in fail-case.
     * @param string $sql The SQL query to run
     * @return PDOStatement|null Returns the query result on success, returns `null` on fail
     */
    private function sqlQuery(string $sql) : ?PDOStatement {
        try {
            return $this->pdo->query($sql);
        }
        catch(PDOException $e) {
            return $this->printError($e);
        }
    }

    /**
     * Private static function for optional output for the results of an
     * INSERT statement.
     * @param string $tblName The name of the table being inserted into
     * @param int $recordId The ID of the inserted record
     * @return void
     */
    private static function insertOutput(string $tblName, int $recordId) : void {
        echo ($recordId ? "Successfully inserted record into '$tblName' with ID $recordId." : "Failed to insert into '$tblName'.") . "</br>";
    }

    public function insertReferralData(array $opts) : ?int {

        $args = [...REFERRAL_DEFAULTS, ...$opts];

        $sql = <<<SQL
        INSERT INTO co2007_referral(referral_id, physician_name, clinic_name, patient_phone_no, 
        patient_email, fax_no, patient_name, patient_dob, urgency_level, referral_reason)
        SELECT * FROM (SELECT :rreferral_id0, :rphysician_name, :rclinic_name, :rpatient_phone_no,
        :rpatient_email, :rfax_no, :rpatient_name, :rpatient_dob, :rurgency_level, :rreferral_reason) AS tmp
        WHERE NOT EXISTS (SELECT referral_id FROM co2007_referral WHERE referral_id = :rreferral_id1);
        SQL;

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(":rreferral_id0", $args["referral_id"], PDO::PARAM_INT);
        $stmt->bindParam(":rphysician_name", $args["physician_name"], PDO::PARAM_STR);
        $stmt->bindParam(":rclinic_name", $args["clinic_name"], PDO::PARAM_STR);
        $stmt->bindParam(":rpatient_phone_no", $args["patient_phone_no"], PDO::PARAM_STR);
        $stmt->bindParam(":rpatient_email", $args["patient_email"], PDO::PARAM_STR);
        $stmt->bindParam(":rfax_no", $args["fax_no"], PDO::PARAM_STR);
        $stmt->bindParam(":rpatient_name", $args["patient_name"], PDO::PARAM_STR);
        $stmt->bindParam(":rpatient_dob", $args["patient_dob"], PDO::PARAM_STR);
        $stmt->bindParam(":rurgency_level", $args["urgency_level"], PDO::PARAM_STR);
        $stmt->bindParam(":rreferral_reason", $args["referral_reason"], PDO::PARAM_STR);
        $stmt->bindParam(":rreferral_id1", $args["referral_id"], PDO::PARAM_INT);

        if($this->sqlExecute($stmt)) {
            echo $this->insertOutput("co2007_referral", $args["referral_id"]);
            return $args["referral_id"];
        }

        return null;
    }
}

?>