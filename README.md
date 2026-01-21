# Investalo Market Snapshot (Momentum 4h) 🧭

An intelligent WordPress plugin for traders that displays more than just prices. It analyzes short-term momentum (4h interval) to determine the current **Market Regime**.

## 🧠 Intelligent Logic
The tool compares the movement of risk assets (Bitcoin, DAX) with safe havens (Gold) to determine capital flow:
- **Risk-On:** Bitcoin leads, defensive demand (Gold) is absent.
- **Risk-Off:** Capital flees from Crypto towards safety (Gold).
- **Transition Phase:** Neutral market sentiment without a clear trend.

## ✨ Features
- **Automated Sentiment Phrases:** Translates raw numbers into trading terms like "impulsive uptrend."
- **Built-in Styling:** Modern card design with shadow effects and risk-based colors (Green/Red/Gray).
- **Performance Optimized:** Uses WordPress Transients (15 min. cache) to stay within API limits and keep loading times minimal.

## 🚀 Installation
1. Upload the `.php` file to your plugins folder.
2. Save your `twelve_data_api_key` in the WordPress database.
3. Use the shortcode `[market_snapshot]` anywhere on your site.

---
*Developed for the Investalo homepage.*
