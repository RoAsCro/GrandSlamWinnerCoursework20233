<?php

header("Content-Type: application/json; charset=utf-8");
header('Access-Control-Allow-Origin: https://titan.dcs.bbk.ac.uk');

$file = (isset($_GET['file'])) ? $_GET['file'] : "";
$tournament = (isset($_GET["tournament"])) ? $_GET["tournament"] : "";
$year = $year = (isset($_GET["year"])) ? $_GET["year"] : "";
$yearOp = (isset($_GET["yearOp"])) ? $_GET["yearOp"] : "";
$winner = (isset($_GET["winner"])) ? $_GET["winner"] : "";
$runnerUp = (isset($_GET["runnerUp"])) ? $_GET["runnerUp"] : "";
$error = "";

// Check for errors in the query string parameters
// As year, winner, and runnerUp can be blank, they are not required parameters
if (!is_numeric($year) && $year != "") {
    $error = "Error: the Year field must be a number.";
} else if (!($file === "mens-grand-slam-winners.json" or $file === "womens-grand-slam-winners.json")) {
    $error = "Error: file not found.";
} else if (!($yearOp === "=" or $yearOp ===">" or $yearOp === "<")) {
    $error = "Error: year operator must be set to '=', '>' or '<'.";
} else if (!($tournament === "Any" or
$tournament === "Australian Open" or
$tournament === "French Open" or
$tournament === "U.S. Open" or
$tournament === "Wimbledon")) {
    $error = "Error: Please select a valid tournament.";
}

$return = array();

// As long as their were no errors in the query string, procede with getting the results
if ($error === "") {
    $result = json_decode(file_get_contents($file), true);

    foreach ($result as $currenNode) {
        //Test if the current node should be added to the returned JSON
        $test = true;
        if ($year != "") {
            $currentYear = $currenNode["year"];
            if ($yearOp === "=") {
                $test = "" . $currentYear === $year;
            } elseif ($yearOp === ">") {
                $test = "" . $currentYear > $year;
            } else {
                $test = "" . $currentYear < $year;
            }
        }

        if ($tournament != "Any" && !str_contains($currenNode["tournament"], $tournament)) {
            $test = false;
        }

        if ($winner != "" && !str_contains($currenNode["winner"], $winner)) {
            $test = false;
        }

        if ($runnerUp != "" && !str_contains($currenNode["runner-up"], $runnerUp)) {
            $test = false;
        }

        if ($test === true) {
            array_push($return, $currenNode);
        }
    }
} else {
    // If there were errors with the query string, make the error message the returned JSON
    $return = array("error" => $error);
}

$json = json_encode($return);
echo ($json);

?>