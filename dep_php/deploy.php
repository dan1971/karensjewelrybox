
<?php
define('GITHUB_SECRET', 'copper&TinGiveItAllToForWiddershins');

// Cloudflare-friendly header check
$headers = array_change_key_case(getallheaders(), CASE_LOWER);
$hubSignature = isset($headers['x-hub-signature-256']) ? $headers['x-hub-signature-256'] : '';

// If the standard check fails, look for Apache's fallback environment variable
if (empty($hubSignature) && isset($_SERVER['HTTP_X_HUB_SIGNATURE_256'])) {
    $hubSignature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'];
}

$rawPostData = file_get_contents('php://input');
$expectedSignature = 'sha256=' . hash_hmac('sha256', $rawPostData, GITHUB_SECRET);

if (!hash_equals($expectedSignature, $hubSignature)) {
    header('HTTP/1.1 403 Forbidden');
    echo "Error: Invalid secret signature.";
    exit();
}

ob_end_clean();

// Run cPanel automation
$pull = shell_exec("uapi VersionControl update repository_root='/home2/sazxjwte/repositories/karensjewelrybox'");
$deploy = shell_exec("uapi VersionControlDeployment create repository_root='/home2/sazxjwte/repositories/karensjewelrybox'");

echo "GitHub Verification Successful via Cloudflare Proxy!\n\n";
echo "Pull Log:\n" . $pull . "\n\nDeploy Log:\n" . $deploy;
?>
