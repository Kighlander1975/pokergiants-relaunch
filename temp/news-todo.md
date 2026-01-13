# News Backend ToDo

1. Datenbank-Tabellen planen
    - `news` inklusive aller erforderlichen Felder (Titel/Slug, Autor, externe Zuordnung, Kategorie, Source, `published`, `auto_publish_at`, Kommentarflag, Timestamps)
    - `news_comments` mit Bezug zur News, optionaler Eltern-Kommentar, Freigabe-Status, Text, Autor-Referenz (nullable)
2. Migrationsstruktur skizzieren (kein Soft Delete, `published`-Flag) und Validierungsregeln definieren
3. BB-Code-Editor-Anforderungen dokumentieren (News + Comments, Einschränkungen bei Comments)
4. Comment-Hierarchie und Zeichenlimits festlegen (Ebene 1: 2000 Zeichen, Ebene 2: 500 Zeichen, Counter)
5. Planung für spätere Freigabe-Workflows und Autorisierungen (nur Admins dürfen News erstellen; Floorman/Admins greifen bei Kommentarfreigaben ein; Kommentare können auch von ihrem Autor auf Ebene 1+2 ergänzt werden, bleiben aber unsichtbar, bis neue Version freigegeben wird)
6. Routen- und Controller-Outline vorbereiten (News CRUD, Kommentarfreigabe)
7. Backend-Oberflächen identifizieren, die später getestet werden sollen (News-Form, Comment-Moderation, Editor-UI)
