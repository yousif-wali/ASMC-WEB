<?php

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true; // Directory doesn't exist, nothing to delete
    }

    if (!is_dir($dir)) {
        return unlink($dir); // Delete the file
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false; // Recursive deletion failed
        }
    }

    return rmdir($dir); // Delete the directory after its contents have been deleted
}

$directory = __DIR__ . "/projects/Yousif";

try {
    if (deleteDirectory($directory)) {
        echo "Folder and its contents deleted";
    } else {
        echo "Error: Unable to delete folder";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

?>
