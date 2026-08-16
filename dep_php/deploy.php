<?php
// 1. Define your secret key (Must match the Secret field in GitHub)
define('GITHUB_SECRET', 'copper&TinGiveItAllToForWiddershins');

// 2. Grab the signature header sent by GitHub
$headers = getallheaders();
$hubSignature = isset($headers['X-Hub-Signature-256']) ? $headers['X-Hub-Signature-256'] : '';

// 3. Grab the raw body data sent by GitHub
$rawPostData = file_get_contents('php://input');

// 4. Calculate what the signature *should* be using your secret key
$expectedSignature = 'sha256=' . hash_hmac('sha256', $rawPostData, GITHUB_SECRET);

// 5. Verify that the signatures match perfectly
if (!hash_equals($expectedSignature, $hubSignature)) {
    // If they don't match, block the request immediately
    header('HTTP/1.1 403 Forbidden');
    echo "Error: Invalid secret signature.";
    exit();
}

// 6. If the signature is correct, clear output caches and run the update
ob_end_clean();

// "Update from Remote" (Pull changes)
$pull = shell_exec("uapi VersionControl update repository_root='/home2/sazxjwte/repositories/karensjewelrybox'");

// "Deploy HEAD Commit" (Run .cpanel.yml tasks)
$deploy = shell_exec("uapi VersionControlDeployment create repository_root='/home2/sazxjwte/repositories/karensjewelrybox'");

// Log results for troubleshooting
echo "GitHub Verification Successful!\n\n";
echo "Pull Log:\n" . $pull . "\n\nDeploy Log:\n" . $deploy;
?>
