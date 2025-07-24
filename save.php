<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = fopen('responses.csv', 'a');
    $data = [
        date('Y-m-d H:i:s'),
        $_POST['fio'] ?? '', $_POST['age'] ?? '', $_POST['height'] ?? '',
        $_POST['weight'] ?? '', $_POST['phone'] ?? ''
    ];
    for ($i = 1; $i <= 21; $i++) {
        $data[] = $_POST['q' . $i] ?? '';
    }
    fputcsv($file, $data);
    fclose($file);
    header('Location: thanks.html');
    exit();
}
?>