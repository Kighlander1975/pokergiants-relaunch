<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>E-Mail-Adresse bestätigen</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #000; text-align: center;">E-Mail-Adresse bestätigen</h1>

        <p>Hallo {{ $user->nickname }},</p>

        <p>Sie haben eine Änderung Ihrer E-Mail-Adresse angefordert. Um die Änderung zu bestätigen, klicken Sie bitte auf den folgenden Link:</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
                E-Mail-Adresse bestätigen
            </a>
        </div>

        <p><strong>Wichtige Hinweise:</strong></p>
        <ul>
            <li>Dieser Link ist nur 10 Minuten gültig.</li>
            <li>Nach der Bestätigung werden Sie automatisch abgemeldet.</li>
            <li>Melden Sie sich mit Ihrer neuen E-Mail-Adresse an.</li>
            <li>Falls Sie keinen Zugriff auf diese E-Mail-Adresse haben, können Sie sich weiterhin mit Ihrer alten E-Mail-Adresse anmelden.</li>
        </ul>

        <p>Wenn Sie diese Änderung nicht angefordert haben, ignorieren Sie diese E-Mail.</p>

        <p>Mit freundlichen Grüßen,<br>
            Ihr Poker Giants Team</p>
    </div>
</body>

</html>