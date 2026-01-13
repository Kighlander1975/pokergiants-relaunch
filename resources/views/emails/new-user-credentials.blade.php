<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Neuer PokerGiants Zugang</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #000; text-align: center;">Willkommen bei PokerGiants</h1>

        <p>Hallo {{ $user->nickname }},</p>

        <p>Ein Administrator hat für dich einen Account angelegt. Du kannst dich ab sofort mit folgenden Zugangsdaten anmelden:</p>

        <ul style="list-style: none; padding: 0;">
            <li><strong>E-Mail:</strong> {{ $user->email }}</li>
            <li><strong>Passwort:</strong> {{ $password }}</li>
        </ul>

        <p>Der Zugang ist bereits verifiziert. Bitte melde dich unter folgendem Link an und ändere das Passwort nach dem ersten Login:</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $loginUrl }}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Zum Login
            </a>
        </div>

        <p>Falls du Unterstützung brauchst, antworte einfach auf diese E-Mail.</p>

        <p>Mit freundlichen Grüßen,<br>
            Dein PokerGiants Team</p>
    </div>
</body>

</html>