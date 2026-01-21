# Investalo Market Snapshot (Momentum 4h) 🧭

Ein intelligentes WordPress-Plugin für Trader, das mehr als nur Kurse anzeigt. Es analysiert das kurzfristige Momentum (4h-Intervall) und bestimmt daraus den aktuellen **Marktmodus**.

## 🧠 Intelligente Logik
Das Tool vergleicht die Bewegungen von Risiko-Assets (Bitcoin, DAX) mit sicheren Häfen (Gold), um den Kapitalfluss zu bestimmen:
- **Risk-On:** Bitcoin führt, defensive Nachfrage (Gold) bleibt aus.
- **Risk-Off:** Kapital flieht aus Krypto in Richtung Sicherheit (Gold).
- **Übergangsphase:** Neutrale Marktstimmung ohne klaren Trend.

## ✨ Features
- **Automatisierte Sentiment-Phrasen:** Übersetzt nackte Zahlen in Trading-Begriffe wie "impulsiver Aufwärtstrend".
- **Eingebautes Styling:** Modernes Card-Design mit Shadow-Effekten und Risk-Farben (Grün/Rot/Grau).
- **Performance-Optimiert:** Nutzt WordPress Transients (15 Min. Cache), um API-Limits zu schonen und Ladezeiten minimal zu halten.

## 🚀 Installation
1. Lade die `.php` Datei in deinen Plugin-Ordner hoch.
2. Hinterlege deinen `twelve_data_api_key` in der WordPress Datenbank.
3. Nutze den Shortcode `[market_snapshot]` an beliebiger Stelle.

---
*Entwickelt für die Investalo Homepage.*
