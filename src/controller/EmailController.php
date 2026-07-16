<?php
require_once __DIR__ . '/../vendor/phpmailer/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/SMTP.php';
require_once __DIR__ . '/../vendor/phpmailer/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class EmailController
{
    public static function send(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /contato');
            exit;
        }

        $visitorName = trim($_POST['name'] ?? '');
        $visitorEmail = trim($_POST['email'] ?? '');
        $messageBody = trim($_POST['message'] ?? '');

        if (empty($messageBody)) {
            header('Location: /contato?error=missing_email');
            exit;
        }

        if ($visitorEmail === '' || !filter_var($visitorEmail, FILTER_VALIDATE_EMAIL)) {
            header('Location: /contato?error=missing_email');
            exit;
        }

        $to = self::envValue('CONTACT_EMAIL_TO', self::envValue('MAIL_TO', 'gremioestudantil@rolante.ifrs.edu.br'));
        if (empty($to)) {
            header('Location: /contato?error=email_no_dest');
            exit;
        }

        $headerFrom = self::envValue('CONTACT_EMAIL_FROM', self::envValue('MAIL_FROM', 'no-reply@jornalgremio.local'));
        $envelopeFrom = self::envValue('CONTACT_EMAIL_FROM', self::envValue('MAIL_FROM', $headerFrom));
        $replyTo = $visitorEmail;
        $subject = 'Mensagem recebida pelo formulário de contato do Jornal do Grêmio Estudantil';
        $message = "<h2>📨 Nova mensagem recebida</h2>

<p>Olá,</p>

<p>
Você recebeu uma nova mensagem enviada por meio do formulário de contato do
<strong>Jornal do Grêmio Estudantil</strong>.
</p>

<p><strong>Mensagem:</strong></p>

<div style='padding:15px;background:#f5f5f5;border-left:4px solid #0d6efd;'>
" . nl2br(htmlspecialchars($messageBody)) . "
</div>

<p><strong>Nome:</strong> {$visitorName}</p>

<p><strong>E-mail:</strong> {$visitorEmail}</p>

<hr>

<p style='color:#666'>
Esta mensagem foi enviada automaticamente pelo sistema de contato do site.
</p>

<p>
Atenciosamente,<br>
Equipe do Jornal do Grêmio Estudantil
</p>
";

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/plain; charset=UTF-8',
            'From: ' . $headerFrom,
            'Reply-To: ' . $replyTo,
            'X-Mailer: PHP/' . phpversion(),
        ];

        $sent = false;
        $smtpHost = self::envValue('CONTACT_SMTP_HOST', self::envValue('SMTP_HOST', ''));
        $smtpError = '';
        if ($smtpHost !== '') {
            $smtpPort = (int) self::envValue('CONTACT_SMTP_PORT', self::envValue('SMTP_PORT', '587'));
            $smtpUsername = self::envValue('CONTACT_SMTP_USERNAME', self::envValue('SMTP_USERNAME', ''));
            $smtpPassword = self::envValue('CONTACT_SMTP_PASSWORD', self::envValue('SMTP_PASSWORD', ''));
            $smtpPassword = str_replace([' ', "\t", "\n", "\r"], '', $smtpPassword);
            $smtpSecure = strtolower(self::envValue('CONTACT_SMTP_SECURE', self::envValue('SMTP_SECURE', 'tls')));
            $result = self::sendViaSmtp($to, $subject, $message, $headerFrom, $envelopeFrom, $replyTo, $smtpHost, $smtpPort, $smtpUsername, $smtpPassword, $smtpSecure);
            $sent = $result['sent'];
            $smtpError = $result['error'];
        } else {
            $sent = @mail($to, $subject, $message, implode("\r\n", $headers));
            if (!$sent) {
                $smtpError = 'Falha ao usar mail() no servidor local.';
            }
        }

        if (!$sent) {
            $logPath = __DIR__ . '/../data/contact_messages.log';
            $logDir = dirname($logPath);
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0777, true);
            }

            $logEntry = '[' . date('Y-m-d H:i:s') . "]\n";
            $logEntry .= "To: {$to}\n";
            $logEntry .= "From: {$headerFrom}\n";
            $logEntry .= "Subject: {$subject}\n";
            if ($smtpError !== '') {
                $logEntry .= "SMTP Error: {$smtpError}\n";
            }
            $logEntry .= "\n";
            $logEntry .= $message . "\n\n---\n";
            @file_put_contents($logPath, $logEntry, FILE_APPEND);

            header('Location: /contato?error=email_failed');
            exit;
        }

        header('Location: /contato?success=email_sent');
        exit;
    }

    private static function envValue(string $key, string $default = ''): string
    {
        $value = getenv($key);
        if ($value !== false && trim($value) !== '') {
            return trim($value);
        }

        $envFile = dirname(__DIR__) . '/.env';

        if (is_file($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lines) {
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line === '' || str_starts_with($line, '#')) {
                        continue;
                    }

                    $parts = explode('=', $line, 2);
                    if (count($parts) === 2 && trim($parts[0]) === $key) {
                        return trim($parts[1]);
                    }
                }
            }
        }

        return $default;
    }

    private static function sendViaSmtp(string $to, string $subject, string $message, string $headerFrom, string $envelopeFrom, string $replyTo, string $host, int $port, string $username, string $password, string $secure): array
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->Port = $port;
            $mail->SMTPAuth = ($username !== '');
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->CharSet = 'UTF-8';
            $mail->SMTPAutoTLS = true;

            if ($secure === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($secure === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->SMTPOptions = [
            'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
            ],
        ];

            $mail->setFrom($envelopeFrom, 'Jornal do Grêmio Estudantil');
            $mail->addAddress($to);
            $mail->addReplyTo($replyTo);
            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = $message;
            $mail->AltBody = strip_tags(str_replace(["\r\n", "\n"], "\n", $message));
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';
            $mail->send();
            return ['sent' => true, 'error' => ''];
        } catch (PHPMailerException $e) {
            return ['sent' => false, 'error' => $e->getMessage()];
        }
    }
}
