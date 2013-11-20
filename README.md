Public Serverviewer used by UTzone.de

Using qstat 2.12 with teeworlds and cube2 patch

howto patch qstat:
http://www.utzone.de/forum/showthread.php?t=3219


Serverstats live
http://serverstats.utzone.de/





Hilfe zu quakestat.php
Aufruf:

quakestat.php?game=ut2004s&serverip=95.156.230.70&port=15000&out=svg&template=03&delsc=true

Parameter:

    game = qstat Spielname.
    serverip = IP Adresse des Servers.
    port = Query Port des Servers.
    out = Ausgabe Formatierung (siehe unten).
    template = Nummer der SVG Bild Vorlage die benutzt werden soll.
    delsc = Sonderzeichen entfernen true oder false.

Parameter Ausgabe Formatierung:

    html = Ausgabe Html Code (default).
    json = Ausgabe Javascript Json Format.
    xml = Ausgabe Xml Code.
    cms = Ausgabe Komma getrennte Liste.
    txt = Ausgabe einfache Textzeile.
    irc = Ausgabe Iirc formatierter Text.
    svg = Ausgabe als SVG Bild.
    png = Ausgabe als PNG Bild.

SVG Bilder Vorlagen erstellen:
Zm erstellen empfehle ich das Programm Inkscape.
Beispiel SVG Vorlage.
Unterstütze Variablen für SVG Bilder Vorlagen:

    {SERVERNAME}
    {HOSTNAME} = Hostname des Servers.
    {ADDRESS} = IP Adresse des Servers.
    {STATUS} = Status des Servers UP oder Down.
    {GAME} = Spielname.
    {GAMETYPE} = Spielart die gerade läuft.
    {MAPNAME} = Name der Map die zur Zeit gespielt wird.
    {N} = Anzahl der Spieler auf dem Server
    {M} = Maximale Anzahl der erlaubten Spieler auf dem Server.

Hilfe:

    IP = Gameserver Ip Adresse.
    Port = Query Port des Game Servers.
    Spiel = Spiel das auf dem Server läuft.
    Ausgabe = Formatierung der Ausgabe.
    entf. Sonderz. = Sonderzeichen entfernen ja oder nein.
        html = Ausgabe Html Code.
        json = Ausgabe Javascript Json Format.
        xml = Ausgabe Xml Code.
        cms = Ausgabe Komma getrennte Liste.
        txt = Ausgabe einfache Textzeile.
        irc = Ausgabe Iirc formatierter Text.
        Bild = Ausgabe als SVG oder PNG Bild.

