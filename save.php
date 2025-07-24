<?php
require_once 'vendor/autoload.php';
use TCPDF;

// Получаем данные
$fio = $_POST['fio'] ?? 'аноним';
$filename = 'Анкета_' . preg_replace('/[^a-zA-Zа-яА-Я0-9_]/u', '_', $fio) . '.pdf';

// Список вопросов
$questions = [
    "ФИО" => $_POST['fio'] ?? '-',
    "Возраст" => $_POST['age'] ?? '-',
    "Рост (см)" => $_POST['height'] ?? '-',
    "Вес (кг)" => $_POST['weight'] ?? '-',
    "Препараты снижения веса" => $_POST['q1'] ?? '-',
    "Препараты гормональные" => $_POST['q2'] ?? '-',
    "Почки" => $_POST['q3'] ?? '-',
    "Печень" => $_POST['q4'] ?? '-',
    "Злокач. и доброкач. новообразования" => $_POST['q5'] ?? '-',
    "Лактация" => $_POST['q6'] ?? '-',
    "Системные заболевания крови" => $_POST['q7'] ?? '-',
    "Позвоночник (грыжи, протрузии)" => $_POST['q8'] ?? '-',
    "Кровотечения и склонность к ним (в т.ч. антикоагулянты)" => $_POST['q9'] ?? '-',
    "Гипертоническая болезнь" => $_POST['q10'] ?? '-',
    "ОРВИ / ОРЗ, инфекционные заболевания" => $_POST['q11'] ?? '-',
    "Лимфатическая система" => $_POST['q12'] ?? '-',
    "Варикоз" => $_POST['q13'] ?? '-',
    "Сахарный диабет" => $_POST['q14'] ?? '-',
    "Индивидуальная непереносимость ультразвука" => $_POST['q15'] ?? '-',
    "Лёгочная, сердечная, почечная, печёночная недостаточность" => $_POST['q16'] ?? '-',
    "Роды (не ранее 6 месяцев назад)" => $_POST['q17'] ?? '-',
    "Плохое заживление ран или рубцов" => $_POST['q18'] ?? '-',
    "Тромбофлебит" => $_POST['q19'] ?? '-',
    "Металлические импланты" => $_POST['q20'] ?? '-',
    "Индивидуальные противопоказания" => $_POST['q21'] ?? '-'
];

// Создаём PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);
$pdf->Write(0, "Анкета: $fio

");

// Добавляем каждый вопрос и ответ
foreach ($questions as $question => $answer) {
    $pdf->MultiCell(0, 10, "• $question
Ответ: $answer", 0, 'L', 0, 1, '', '', true);
}

// Сохраняем PDF
$path = __DIR__ . '/' . $filename;
$pdf->Output($path, 'F');

// Отправка PDF в Telegram
$bot_token = '...'; // вставь токен
$chat_id = '...';   // вставь chat_id

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
