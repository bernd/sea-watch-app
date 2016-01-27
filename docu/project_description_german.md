# Sea Watch App



#Wo wir Hilfe brauchen:

Falsche Anfragen verhindern (Spam & targeted Attacks)

GPS Hardware Zugriff (Wir entwickeln die App gerade mit Cordova).

Neben High-Accuracy trotzdem schnell trackbar sein.

Signal zum Satelliten nicht unterbrechen um immer aktuelle Positionen zu haben.

Akku-Verbrauch aufs Minimum reduzieren.

Möglichkeiten wenn das Internet unterbricht weiter ein Signal zu senden oder auf SMS umzuschalten um die Position sowie die Infos zu senden und zu kommunizieren.

Weiteres:
- Marketing in den Zielländern - Wie bekommen wir die App auf die Smartphones der Betroffenen? - bspw. Google AdWords Kampagne in der Türkei, Syrien, Libyen, Tunesien etc.
- SMS-API (wie bspw. messagebird oder twilio - am besten wäre aber eine kostenlose / kostengünstige Variante damit der Chat auch via SMS funktioniert.) Hat Google so etwas? 
- Mittelmeer: Möglichkeit ohne Mobilfunknetz trotzdem mit dem Smartphone Signale zu senden oder eigenes GSM-Netz im Mittelmeer rund um das Schiff. – Hier wären kreative und unkonventionelle Ideen gefragt.







Auftraggeber & Ansprechpartner:

Sea-Watch:
Harald Höppner

Entwicklung:
Nic Zemke
nic@transaperency-everywhere.com

Design und UI
Joshua Krüger
joshua@sea-watch.org


## 1. Kurzbeschreibung

Koordination und Verwaltung von Notrufen

## 2.1 Technische Randbedingungen 

### 2.1.1 Schnittstellen 
Es sind keine Randbedingungen in Bezug auf die Schnittstellen definiert. 

### 2.1.2 Hardware 
Die Applikation muss auf einem Android- und/oder iOS-Gerät lauffähig sein. Zusätzlich müssen diese ein 
GMS/GPS-Modul besitzen, damit die benötigten Geokoordinaten erfasst werden können. 
Für den Zugriff auf das Backend muss der verwendete Computer einen Webbrowser installiert 
haben. (Chrome Version: XXX, Firefox Version: XXX, o.ä.) 

### 2.1.3 Software/Programmiersprachen 
Serverseitig werden PHP und MySQL verwendet. Clientseitig werden HTML, CSS und JavaScript 
verwendet. Für das Frontend kommt das hybride Mobile App Framework IONIC1 (ggf) im Verbund mit AngularJS2 zum Einsatz. Es ermöglicht eine auf HTML basierte Applikation zu konzipieren, welche 
mit Hilfe von Cordova3 als App für Android und iOS kompiliert wird. Für die Umsetzung des 
Backend wird das PHP Framework Laravel 5 genutzt. 

## 2.2 Organisatorische Randbedingungen 
### 2.2.1 Organisation und Struktur 

### 2.2.2 Ressourcen (Budget, Materialien, Zeit, Personal) 

Das Produkt wird für den Auftraggeber kostenlos entwickelt. Für technische Ressourcen werden keinerlei Investitionen seitens des Entwicklerteams getätigt. 

### 2.2.3 Vorgehensmodell

Der Fortschritt und die Probleme des Projektes werden täglich/wöchentlich und für alle sichtbar 
festgehalten.
In regelmäßigen Abständen werden Produktfunktionalitäten geliefert und beurteilt. Die 
Anforderungen an das Produkt wurden durch ein Gespräch mit der Auftraggeberin klar definiert. 

### 2.2.4 Juristische und gesetzliche Faktoren

Für die juristischen und gesetzlichen Aspekte des Produkts liegt die Verantwortung beim 
Auftraggeber. 


## 3 Funktionsumfang 

### 3.1 Musskriterien
Der Kunde soll durch das Produkt die Möglichkeit haben, die zuvor manuell erfassten Daten nun mit Hilfe einer Android-App speichern zu lassen und anschließend im Backend auszuwerten.

**Aufbau**

## App auf einem Smartphone

### • Initialisierung
- Überprüfung ob sich Nutzer in Operationsgebiet befindet.
- Falls nicht -> Fehlermeldung

### • Notruf Senden
- Auswahl: Selbst an Board / Notfall gesichtet
- Notruf Formular

### • Notruf Session
- Übermittlung der Position alle n Sekunden



## Laravel Backend

### • Nutzerverwaltung
- Admin kann Nutzer anlegen und diesem Organisation und Einsatzgebiet zuweisen

### • Operationsgebiet Verwaltung
- Admin kann Operationsgebiete anlegen

### • Karte
- Auswahl von Operationsgebieten
	- Anzeige von Fällen auf Karte
