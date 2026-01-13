# News & Comments Dokumentation

## Tabellenübersicht

-   `news`

    -   `id`
    -   `title` (aus dem Titel generieren wir einen SEO-konformen Slug mit `-` als Trennzeichen)
    -   `author` (Name der internen Person, nur Floorman/Admin darf News verfassen)
    -   `author_external` (nullable, Pflichtfeld, sobald externe Quelle oder Links angegeben werden)
    -   `tags` (optional, freie Verschlagwortung für späteres Filtern)
    -   `category` (`Interne Pokernews` | `Externe Pokernews`)
    -   `source` (nullable JSON, kann Link + Text enthalten oder nur reinen Text; bei externer Kategorie verpflichtend)
    -   `comments_allowed` (Boolean)
    -   `published` (Boolean-Flag, steuert Sichtbarkeit)
    -   `auto_publish_at` (nullable, später durch Scheduler/Job aktivierbar)
    -   `created_at`, `updated_at`

-   `news_comments`
    -   `id`
    -   `news_id` (FK zur News)
    -   `parent_id` (nullable, max. zwei Ebenen tief)
    -   `user_id` (nullable, falls Autor gelöscht wird, bleibt Kommentar erhalten und zeigt "unbekannter/gelöschter Nutzer")
    -   `is_approved` (Boolean, Kommentare werden erst nach Freigabe sichtbar)
    -   `content` (Text mit BB-Code, eingeschränkter Editor-Einsatz: keine Bilder, Links oder Überschriften `h2/h1`)
    -   `level` (1 oder 2, für Zeichenlimit und Anzeige)
    -   `created_at`, `updated_at`

## BB-Code-Editor-Funktionalitäten

-   News können über einen Richtext-Editor mit folgenden Befehlen ausgestattet werden:
    -   Formatierungen: fett, kursiv, unterstrichen, Schriftgröße
    -   Überschriften: `h2` bis `h5` (kein `h1`)
    -   Ausrichtung: linksbündig, zentriert, rechtsbündig, Blocksatz
    -   Absätze mit `<p>`
    -   Horizontale Linie (`[hr]`)
    -   Link-Modal (optionaler Linktext + URL)
    -   Font-Awesome-Iconpicker mit Vorschau
    -   Bild-Icon-Platzhalter (nur visuell, keine aktive Einbindung)
-   Kommentare nutzen denselben Editor, allerdings:
    -   Keine Bilder
    -   Keine Links
    -   Keine Überschriften `h2` oder `h1`
    -   Zeichenlimit: Ebene 1 → 2000 Zeichen, Ebene 2 → 500 Zeichen
    -   Ein eingebauter Counter zeigt verbleibende Zeichen

## Sonstige Anforderungen

-   Nur Admins dürfen neue News erstellen; Floorman und Admins dürfen Kommentare freigeben.
-   Kommentare dürfen verschachtelt sein (max. zwei Ebenen), werden erst nach Freigabe sichtbar und behalten bei Löschung des Nutzers die Historie (Autortext falls der User nicht mehr existiert).
-   News und Comments wird zukünftig ein eigener Moderationsbereich spendiert, um Freigaben, Status und Authoring zu kontrollieren.
-   Migrationen `news` und `news_comments` sind jetzt erstellt; Modelle, Routen und Controllerstubs folgen als nächster Schritt.
-   Alle Schritte sollen zuerst in kleinen Prüfungen getestet werden, bevor wir die komplette Implementierung finalisieren.
-   Derzeitiger Stand: Nur Dokumentation und ToDo in `temp/` erstellen; keine Codeänderungen oder Migrationen angehen, bis ein weiteres Go kommt.
