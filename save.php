<?php
require_once 'vendor/autoload.php';

use TCPDF;

// Получаем данные
$fio = $_POST['fio'] ?? 'аноним';
$filename = 'Анкета_' . preg_replace('/[^a-zA-Zа-яА-Я0-9_]/u', '_', $fio) . '.pdf';

$questions = [
    "Препараты снижения веса", "Препараты гормональные", "Почки", "Печень",
    "Злокач. и доброкач. новообразования", "Лактация", "Системные заболевания крови",
    "Позвоночник (грыжи, протрузии)", "Кровотечения и склонность к ним (в т.ч. антикоагулянты)",
    "Гипертоническая болезнь", "ОРВИ / ОРЗ, инфекционные заболевания", "Лимфатическая система",
    "Варикоз", "Сахарный диабет", "Индивидуальная непереносимость ультразвука",
    "Лёгочная, сердечная, почечная, печёночная недостаточность", "Роды (не ранее 6 месяцев назад)",
    "Плохое заживление ран или рубцов", "Тромбофлебит", "Металлические импланты",
    "Индивидуальные противопоказания"
];

// Создаём PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);

$pdf->Write(0, "Анкета: $fio\n\n");

// Выводим каждый вопрос и ответ
foreach ($questions as $i => $q) {
    $answer = $_POST['q' . ($i + 1)] ?? '-';
    $pdf->MultiCell(0, 10, "• $q\nОтвет: $answer", 0, 'L', 0, 1, '', '', true);
}

// Сохраняем PDF
$path = __DIR__ . '/' . $filename;
$pdf->Output($path, 'F');

// Отправляем PDF в Telegram
$bot_token = '...'; // твой токен
$chat_id = '...';   // твой chat_id

$send_url = "https://api.telegram.org/bot$bot_token/sendDocument";
$post_fields = [
    'chat_id' => $chat_id,
    'document' => new CURLFile(realpath($path)),
    'caption' => "PDF-отчёт по анкете: $fio"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
curl_setopt($ch, CURLOPT_URL, $send_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_exec($ch);
curl_close($ch);

// Перенаправляем
header('Location: thanks.html');
exit();
?>
