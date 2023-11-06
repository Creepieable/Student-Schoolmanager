Please view the issues before doing anything with this.

-----

Dieses Projekt ist Bestandteil einer komplexen Leistung, im Fach Informatik, am [Leipzig-Kolleg](https://cms.sachsen.schule/leipzigkolleg/startseite/), des Schülers Johannes Bela.

# Student-Schoolmanager:
Web Applikation für Schüler, um Aufgaben, Hausarbeiten, Klausuren und Tests zu managen.

## Projektumfang:
### Vollständige features:
Ein Nutzer Managementsystem ermöglicht es Schülern sich einen Account anzulegen und sich über diesen anzumelden. Schüler haben dann die möglichkeit ihren Kalender mit Aufgaben zu füllen. Den einzelnen Einträgen können Notizen angehangen werden, die zudem die nutzung von Markdown-Notation ermöglichen. Die jeweiligen nutzerdaten werden in einer Datenbacnk gespeichert und mit hilfe eines in PHP geschrieben Backends an den jeweiligen nutzer weitergeleitet.

### Unvollständige features:
Des weiteren ist ein Stundeplan-System vorgesehen, mit dem Schüler mehrere Plane Anlegen und Anzeigen können. Hier ist nur die Backend Komponente vollständig, die im Frontend noch nicht eingebunden ist.

-----
Dieses Projekt nutzt unter anderem die Libraries Bootstrap 5 und jQuery für das Frontend und PHP, sowie SQL für das Backend.

## Get startet:
1. Installieren einer [XAMPP](https://www.apachefriends.org/de/index.html) oder einer anderen Webserverumbgebung
2. Einfügen der schoolmanager Server daten in den htdocs Ordenr des XAMPP Webservers
3. Improtieren der [schoolman](https://github.com/Creepieable/Student-Schoolmanager/tree/main/TestDB) Datenbank, über PHPmyAdmin oder Heidi SQL
4. Eingaben der MySQL Server Credentials in die \API\\[credentials.php](https://github.com/Creepieable/Student-Schoolmanager/blob/main/SchoolMan/API/credentials.php) (XAMPP standard: dbUser:"root" und dbPw:"" bereits eingetragen)
