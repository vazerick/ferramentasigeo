<?php

//Adds US Form Manager tables and data
//Release Version 5.0.5
//Release Date Unknown


$countE = 0;

logger(1, "System Updates", "Disabled Two Factor Authentication for Upgraders");
$db->update('settings', 1, ['twofa' => 0]);

if ($countE == 0) {
    $db->insert('updates', ['migration' => $update]);
    if (!$db->error()) {
        if ($db->count() > 0) {
            logger(1, "System Updates", "Update $update successfully deployed.");
            $successes[] = "Update $update successfully deployed.";
        } else {
            logger(1, "System Updates", "Update $update unable to be marked complete, query was successful but no database entry was made.");
            $errors[] = "Update " . $update . " unable to be marked complete, query was successful but no database entry was made.";
        }
    } else {
        $error = $db->errorString();
        logger(1, "System Updates", "Update $update unable to be marked complete, Error: " . $error);
        $errors[] = "Update $update unable to be marked complete, Error: " . $error;
    }
} else {
    logger(1, "System Updates", "Update $update unable to be marked complete");
    $errors[] = "Update $update unable to be marked complete";
}
