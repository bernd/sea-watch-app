# Sea Watch App

Auftraggeber & Ansprechpartner:

Harald Höppner

Sea-Watch e.V.

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
Das System wird von Nicolas Zemke und Stefan Pabst im 
 
### 2.2.2 Ressourcen (Budget, Materialien, Zeit, Personal) 

Das Produkt wird für den Auftraggeber kostenlos entwickelt. Für technische Ressourcen werden keinerlei Investitionen seitens des Entwicklerteams getätigt. 

### 2.2.3 Organisatorische Standards 
Für die Entwicklungswerkzeuge gibt es keine Vorgaben vom hochschulexternen Auftraggeber. Für 
die Benutzeroberfläche des Produkts gibt es keine einzuhaltenden Vorgaben des Kunden. 
Für die weitere Verarbeitung der erfassten Daten sollen diese dem Auftraggeber in Form einer 
Excel- oder SPSS-Datei übergeben werden.

### 2.2.4 Vorgehensmodell

Der Fortschritt und die Probleme des Projektes werden täglich/wöchentlich und für alle sichtbar 
festgehalten.
In regelmäßigen Abständen werden Produktfunktionalitäten geliefert und beurteilt. Die 
Anforderungen an das Produkt wurden durch ein Gespräch mit der Auftraggeberin klar definiert. 

### 2.2.5 Juristische und gesetzliche Faktoren

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