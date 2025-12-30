@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    <h1 class="text-center home-size"><x-suit type="heart" /> Datenschutzerklärung<x-suit type="spade" /></h1>
    <p class="text-center home-size"><x-suit type="club" /> PokerGiants.de<x-suit type="diamond" /></p>
</div>
@endsection

@section('content-body')
<div class="glass-card one-card one-card-75">
    <h2>Datenschutzerklärung</h2>
    <p class="text-sm text-gray-600 mb-6"><strong>Stand: 30. Dezember 2025</strong></p>

    <h3>1. Verantwortlicher</h3>
    <div class="ml-8">
        <p><strong>PokerGiants GmbH</strong><br>
            Musterstraße 123<br>
            12345 Musterstadt<br>
            Deutschland</p>
        <p>E-Mail: datenschutz@pokergiants.de<br>
            Telefon: +49 (0) 123 456789</p>
        <p>Vertretungsberechtigte Geschäftsführer: [Name des Geschäftsführers]</p>
    </div>

    <h3>2. Datenschutzbeauftragter</h3>
    <div class="ml-8">
        <p>[Name des Datenschutzbeauftragten]<br>
            E-Mail: datenschutz@pokergiants.de</p>
    </div>

    <h3>3. Allgemeines zur Datenverarbeitung</h3>
    <div class="ml-8">
        <p>Wir nehmen den Schutz Ihrer persönlichen Daten sehr ernst. Diese Datenschutzerklärung informiert Sie darüber, welche personenbezogenen Daten wir erheben, wie wir diese verarbeiten und welche Rechte Ihnen zustehen.</p>
    </div>

    <h3>4. Personenbezogene Daten, die wir verarbeiten</h3>
    <div class="ml-8">
        <h4>4.1 Bei der Registrierung und Nutzung Ihres Accounts</h4>
        <div class="ml-8">
            <ul>
                <li><strong>Pflichtangaben:</strong> Nickname, E-Mail-Adresse, Passwort</li>
                <li><strong>Freiwillige Angaben:</strong> Vorname, Nachname, Straße und Hausnummer, Postleitzahl, Ort, Land, Länderflagge, Avatar-Bild, Biografie, Geburtsdatum, Giants Card</li>
            </ul>
        </div>

        <h4>4.2 Technische Daten</h4>
        <div class="ml-8">
            <ul>
                <li>IP-Adresse</li>
                <li>Browser-Typ und -Version</li>
                <li>Betriebssystem</li>
                <li>Referrer-URL</li>
                <li>Uhrzeit der Serveranfrage</li>
                <li>Session-IDs</li>
            </ul>
        </div>
    </div>

    <h3>5. Zwecke der Datenverarbeitung</h3>
    <div class="ml-8">
        <h4>5.1 Zur Erfüllung des Vertrags</h4>
        <div class="ml-8">
            <ul>
                <li>Bereitstellung und Verwaltung Ihres Benutzeraccounts</li>
                <li>Personalisierung Ihres Profils</li>
                <li>Bereitstellung der Poker-Plattform-Funktionen</li>
            </ul>
        </div>

        <h4>5.2 Aufgrund berechtigter Interessen</h4>
        <div class="ml-8">
            <ul>
                <li>Sicherstellung der technischen Funktionalität der Website</li>
                <li>Verhinderung von Missbrauch und Betrug</li>
                <li>Verbesserung unserer Services</li>
                <li>Rechtsverfolgung bei Verstößen</li>
            </ul>
        </div>

        <h4>5.3 Aufgrund Ihrer Einwilligung</h4>
        <div class="ml-8">
            <ul>
                <li>Marketing-Kommunikation (falls erteilt)</li>
                <li>Personalisierte Inhalte</li>
            </ul>
        </div>
    </div>

    <h3>6. Rechtsgrundlagen der Verarbeitung</h3>
    <div class="ml-8">
        <p>Wir verarbeiten Ihre Daten ausschließlich auf folgenden Rechtsgrundlagen:</p>
        <ul>
            <li><strong>Art. 6 Abs. 1 lit. b DSGVO:</strong> Zur Erfüllung eines Vertrags</li>
            <li><strong>Art. 6 Abs. 1 lit. f DSGVO:</strong> Zur Wahrung berechtigter Interessen</li>
            <li><strong>Art. 6 Abs. 1 lit. a DSGVO:</strong> Aufgrund Ihrer Einwilligung</li>
        </ul>
    </div>

    <h3>7. Empfänger Ihrer Daten</h3>
    <div class="ml-8">
        <h4>7.1 Interne Empfänger</h4>
        <div class="ml-8">
            <ul>
                <li>Unser Entwicklungsteam</li>
                <li>Unser Support-Team</li>
                <li>Verwaltung</li>
            </ul>
        </div>

        <h4>7.2 Externe Dienstleister</h4>
        <div class="ml-8">
            <ul>
                <li>Hosting-Provider (für Server-Infrastruktur)</li>
                <li>E-Mail-Dienstleister (für Transaktions-E-Mails)</li>
            </ul>
        </div>

        <h4>7.3 Keine Datenweitergabe an Dritte</h4>
        <div class="ml-8">
            <p>Wir geben Ihre Daten nicht an Dritte weiter, es sei denn:</p>
            <ul>
                <li>Sie haben ausdrücklich eingewilligt</li>
                <li>Dies ist zur Erfüllung gesetzlicher Pflichten erforderlich</li>
                <li>Dies ist zur Wahrung unserer rechtlichen Interessen erforderlich</li>
            </ul>
        </div>
    </div>

    <h3>8. Cookies und Tracking</h3>
    <div class="ml-8">
        <h4>8.1 Technisch notwendige Cookies</h4>
        <div class="ml-8">
            <p>Wir verwenden folgende technisch notwendige Cookies:</p>
            <ul>
                <li><strong>Laravel Session Cookie:</strong> Speichert Ihre Session-ID für die Authentifizierung</li>
                <li><strong>CSRF Token Cookie:</strong> Schützt vor Cross-Site-Request-Forgery-Angriffen</li>
            </ul>
        </div>

        <h4>8.2 Cookie-Einstellungen</h4>
        <div class="ml-8">
            <p>Sie können Cookies in Ihrem Browser deaktivieren. Bitte beachten Sie, dass die Website ohne Cookies möglicherweise nicht vollständig funktioniert.</p>
        </div>
    </div>

    <h3>9. Speicherdauer</h3>
    <div class="ml-8">
        <h4>9.1 Account-Daten</h4>
        <div class="ml-8">
            <p>Ihre Account-Daten werden so lange gespeichert, wie Ihr Account aktiv ist. Bei Löschung Ihres Accounts werden Ihre Daten innerhalb von 30 Tagen vollständig gelöscht.</p>
        </div>

        <h4>9.2 Technische Logs</h4>
        <div class="ml-8">
            <p>IP-Adressen und technische Logs werden nach 7 Tagen automatisch gelöscht.</p>
        </div>

        <h4>9.3 Gesetzliche Aufbewahrungspflichten</h4>
        <div class="ml-8">
            <p>Bestimmte Daten müssen wir aufgrund gesetzlicher Vorgaben länger aufbewahren (z.B. für Steuerzwecke).</p>
        </div>
    </div>

    <h3>10. Ihre Rechte</h3>
    <div class="ml-8">
        <h4>10.1 Auskunftsrecht (Art. 15 DSGVO)</h4>
        <div class="ml-8">
            <p>Sie haben das Recht, Auskunft über die Verarbeitung Ihrer Daten zu erhalten.</p>
        </div>

        <h4>10.2 Berichtigungsrecht (Art. 16 DSGVO)</h4>
        <div class="ml-8">
            <p>Sie haben das Recht, unrichtige Daten berichtigen zu lassen.</p>
        </div>

        <h4>10.3 Löschungsrecht (Art. 17 DSGVO)</h4>
        <div class="ml-8">
            <p>Sie haben das Recht auf Löschung Ihrer Daten ("Recht auf Vergessenwerden").</p>
        </div>

        <h4>10.4 Einschränkung der Verarbeitung (Art. 18 DSGVO)</h4>
        <div class="ml-8">
            <p>Sie haben das Recht, die Verarbeitung Ihrer Daten einschränken zu lassen.</p>
        </div>

        <h4>10.5 Datenübertragbarkeit (Art. 20 DSGVO)</h4>
        <div class="ml-8">
            <p>Sie haben das Recht, Ihre Daten in einem strukturierten Format zu erhalten.</p>
        </div>

        <h4>10.6 Widerspruchsrecht (Art. 21 DSGVO)</h4>
        <div class="ml-8">
            <p>Sie haben das Recht, der Verarbeitung Ihrer Daten zu widersprechen.</p>
        </div>

        <h4>10.7 Widerrufsrecht</h4>
        <div class="ml-8">
            <p>Sie können erteilte Einwilligungen jederzeit widerrufen.</p>
        </div>
    </div>

    <h3>11. Sicherheit</h3>
    <div class="ml-8">
        <p>Wir setzen technische und organisatorische Sicherheitsmaßnahmen ein, um Ihre Daten zu schützen:</p>
        <ul>
            <li>SSL/TLS-Verschlüsselung</li>
            <li>Sichere Passwort-Hashing (bcrypt)</li>
            <li>Regelmäßige Sicherheitsupdates</li>
            <li>Zugriffsbeschränkungen</li>
            <li>Regelmäßige Datensicherungen</li>
        </ul>
    </div>

    <h3>12. Datenverarbeitung in Drittländern</h3>
    <div class="ml-8">
        <p>Eine Übermittlung Ihrer Daten in Länder außerhalb der EU/des EWR findet nicht statt.</p>
    </div>

    <h3>13. Automatisierte Entscheidungsfindung</h3>
    <div class="ml-8">
        <p>Wir verwenden keine automatisierte Entscheidungsfindung einschließlich Profiling.</p>
    </div>

    <h3>14. Änderungen dieser Datenschutzerklärung</h3>
    <div class="ml-8">
        <p>Wir behalten uns vor, diese Datenschutzerklärung anzupassen. Die aktuelle Version finden Sie immer auf unserer Website.</p>
    </div>

    <h3>15. Kontakt</h3>
    <div class="ml-8">
        <p>Bei Fragen zu dieser Datenschutzerklärung oder zur Ausübung Ihrer Rechte wenden Sie sich bitte an:</p>
        <p><strong>PokerGiants GmbH</strong><br>
            Datenschutzbeauftragter<br>
            E-Mail: datenschutz@pokergiants.de<br>
            Telefon: +49 (0) 123 456789</p>
        <p>Sie haben auch das Recht, sich bei der zuständigen Aufsichtsbehörde zu beschweren.</p>
    </div>
</div>
@endsection