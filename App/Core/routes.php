<?php

// Define resource paths
$resources = [
    "lehrbetriebe" => "App/Controllers/lehrbetriebe",
    "lernende" => "App/Controllers/lernende",
    "lehrbetrieb_lernende" => "App/Controllers/lehrbetrieb_lernende",
    "laender" => "App/Controllers/laender",
    "dozenten" => "App/Controllers/dozenten",
    "kurse" => "App/Controllers/kurse",
    "kurse_lernende" => "App/Controllers/kurse_lernende",
    "benutzer" => "App/Controllers/benutzer"
];

// Define routes for each resource
foreach ($resources as $resource => $resource_path) {
    $router->get("/{$resource}", "{$resource_path}/index.php");
    $router->post("/{$resource}", "{$resource_path}/create.php");
    $router->get("/{$resource}/{id}", "{$resource_path}/read.php");
    $router->put("/{$resource}/{id}", "{$resource_path}/update.php");
    $router->delete("/{$resource}/{id}", "{$resource_path}/delete.php");
}