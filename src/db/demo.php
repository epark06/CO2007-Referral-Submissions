<?php
//filename = demo.php

declare(strict_types=1);

require_once("autoload.php");

// Initialises a Database instance and retrieves the PDO connection
$db = new Database();
$pdo = $db->getConnection();

echo "<h1>SCHEMA INIT</h1>";

// Creates the co2007_referral table
$setup = new DatabaseSetup($pdo);
$setup->dropReferralTable();
// Only truthy in event of failure
$schemaFail = $setup->createDatabaseSchema();

// If any of these tables' queries failed, throw a runtime exception
if($schemaFail) {
    // Generates error message, pluralising if multiple tables failed to be created
    $pluralHandler = $schemaFail . (str_contains($schemaFail," and ") ? " have" : " has");
    $e = $pluralHandler . " failed to generate, aborting program.";
    throw new RuntimeException($e);
}

$repo = new ReferralRepository($pdo);
$referralData = [
    "referral_id" => 17564121,
    "physician_name" => "Jane Doe",
    "clinic_name" => "St. Mary's Clinic",
    "patient_phone_no" => "07000 560000",
    "patient_email" => "john-doe@gmail.com",
    "fax_no" => "0161 000 0000",
    "patient_name" => "John Doe",
    "patient_dob" => "1999-01-01",
    "urgency_level" => "Routine",
    "referral_reason" => "Patient complaing of shortness of breath"
];
$repo->insertReferralData($referralData);

?>