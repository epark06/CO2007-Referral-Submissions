<?php
//filename = demo.php

declare(strict_types=1);

require_once("Database.php");
require_once("DatabaseSetup.php");

// Initialises a Database instance and retrieves the PDO connection
$db = new Database();
$pdo = $db->getConnection();

echo "<h1>SCHEMA INIT</h1>";

// Creates the co2717_clothing, co2717_clothing_sizes and co2717_warehouses tables
$setup = new DatabaseSetup($pdo);
// Only truthy in event of failure
$schemaFail = $setup->createDatabaseSchema();

// If any of these tables' queries failed, throw a runtime exception
if($schemaFail) {
    // Generates error message, pluralising if multiple tables failed to be created
    $pluralHandler = $schemaFail . (str_contains($schemaFail,", ") ? " have" : " has");
    $e = $pluralHandler . " failed to generate, aborting program.";
    throw new RuntimeException($e);
}

?>