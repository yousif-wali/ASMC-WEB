<?php
session_start();

$zipFileName = $_REQUEST['folder'] . ".zip";
$directory = __DIR__ . "/projects/" . $_SESSION['username']; // Target directory

// Set the appropriate headers for a ZIP file download
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename=' . basename($zipFileName));
header('Content-Length: ' . filesize($zipFileName));

// Flush the headers and output the file
flush();
readfile($zipFileName);

// Delete the ZIP file after sending it to the browser
if (strpos(realpath($zipFileName), realpath(__DIR__ . "/projects/")) === 0) {
    // Ensure file is within the /projects/ directory
    unlink($zipFileName);
}

// Function to delete directories and their contents
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true; // Directory doesn't exist, nothing to delete
    }

    if (!is_dir($dir)) {
        return false; // Not a directory, don't proceed
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        $path = $dir . DIRECTORY_SEPARATOR . $item;

        if (is_dir($path)) {
            if (!deleteDirectory($path)) {
                return false; // Recursive deletion failed
            }
        } else {
            if (strpos(realpath($path), realpath(__DIR__ . "/projects/")) === 0) {
                unlink($path); // Delete file if within /projects/
            }
        }
    }

    return rmdir($dir); // Delete the directory after its contents have been deleted
}

// Attempt to delete the user's specific directory within /projects/
try {
    if (strpos(realpath($directory), realpath(__DIR__ . "/projects/")) === 0) {
        // Ensure the target directory is within /projects/
        if (deleteDirectory($directory)) {
            error_log("User's folder and its contents deleted.");
        } else {
            error_log("Error: Unable to delete user's folder.");
        }
    } else {
        error_log("Error: Target directory is outside the /projects/ scope.");
    }
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
}

// Redirect after deletion
header("Location: ./default.php");
exit();
?>
