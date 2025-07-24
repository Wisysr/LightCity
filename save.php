<?php
require_once 'vendor/autoload.php'; // подключаем PHPWord

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Формируем Word-документ
$word = new PhpWord();
$section = $word->addSection();

$fio = $_POST['fio'] ?? 'аноним';
$section->addTitle("Опрос - $fio", 1);

// Вопросы
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

foreach ($questions as $i => $q) {
    $answer = $_POST['q' . ($i + 1)] ?? '-';
    $section->addText("• $q");
    $section->addText("Ответ: $answer\n", ['italic' => true]);
}

// Сохраняем файл
$filename = 'Отчёт_' . preg_replace('/[^a-zA-Zа-яА-Я0-9_]/u', '_', $fio) . '.docx';
$path = __DIR__ . "/$filename";
$writer = IOFactory::createWriter($word, 'Word2007');
$writer->save($path);

// Отправляем файл в Telegram
$bot_token = '7967646516:AAEi9XwevABI6gcGkykF_CcSABKXSITL4WY';
$chat_id = '1280511210';

$send_url = "https://api.telegram.org/bot$bot_token/sendDocument";
$post_fields = [
    'chat_id' => $chat_id,
    'document' => new CURLFile(realpath($path)),
    'caption' => "Опрос от $fio"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
curl_setopt($ch, CURLOPT_URL, $send_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
$result = curl_exec($ch);
curl_close($ch);

// Перенаправляем пользователя
header('Location: thanks.html');
exit();
?>
