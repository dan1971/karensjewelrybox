<?php
// Clear output caches
ob_end_clean();

// 1. "Update from Remote" (Pull changes)
$pull = shell_exec("uapi VersionControl update repository_root='/home2/sazxjwte/repositories/karensjewelrybox'");

// 2. "Deploy HEAD Commit" (Run .cpanel.yml tasks)
$deploy = shell_exec("uapi VersionControlDeployment create repository_root='/home2/sazxjwte/repositories/karensjewelrybox'");

echo "Pull Log:\n" . $pull . "\n\nDeploy Log:\n" . $deploy;
?>