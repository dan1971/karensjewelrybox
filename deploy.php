

<?php
// Clear output caches so we can see the text response clearly
ob_end_clean();

// 1. "Update from Remote" (Pull latest changes from GitHub)
// The '/usr/local/cpanel/bin/' prefix ensures HostGator can find the cPanel tool.
// The '2>&1' forces hidden errors to show up in your GitHub logs.

/home2/sazxjwte/public_html/website_14f8c164
// 2. "Deploy HEAD Commit" (Runs your .cpanel.yml instructions to copy files live)
$deploy = shell_exec("/usr/local/cpanel/bin/uapi VersionControlDeployment create repository_root='/home2/sazxjwte/public_html/website_14f8c164' 2>&1");

// Print the logs so GitHub can capture the results
echo "GitHub Webhook Triggered Successfully (No Secret Required)!\n\n";
echo "Pull Log:\n" . $pull . "\n\nDeploy Log:\n" . $deploy;
?>