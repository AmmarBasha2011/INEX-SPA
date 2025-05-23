<?php
// web/test_error.ahmed.php
echo $undefinedVariable; // Trigger E_NOTICE
echo "This text should appear, and no overlay should be present.";
?>
