<?PHP
if (($entrypoint ?? 0) != 1) {
    http_response_code(403);
    echo "Access denied";
    exit();
}
?>
