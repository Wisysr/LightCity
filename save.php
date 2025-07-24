<?php
// Telegram данные
$bot_token = '7967646516:AAEi9XwevABI6gcGkykF_CcSABKXSITL4WY';
$chat_id = '1280511210';

// Формируем текст сообщения
$message = "📝 Новый ответ из анкеты:\n";
$message .= "ФИО: " . ($_POST['fio'] ?? '') . "\n";
$message .= "Возраст: " . ($_POST['age'] ?? '') . "\n";
$message .= "Рост: " . ($_POST['height'] ?? '') . " см\n";
$message .= "Вес: " . ($_POST['weight'] ?? '') . " кг\n";
$message .= "Телефон: " . ($_POST['phone'] ?? '') . "\n";

for ($i = 1; $i <= 21; $i++) {
    $q = $_POST['q' . $i] ?? '';
    if (!empty($q)) {
        $message .= "Вопрос {$i}: $q\n";
    }
}

// Отправка в Telegram
file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($message));

// Перенаправление на спасибо
header('Location: thanks.html');
exit();
?>
