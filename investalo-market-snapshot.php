/*
Plugin Name: Investalo Market Snapshot (Momentum 4h)
Description: Kurzfristiger Marktüberblick unter TradingView – saubere Momentum-Auswertung.
Version: 2.1
*/

if (!defined('ABSPATH')) exit;

/* -----------------------------
   Frontend CSS
----------------------------- */
add_action('wp_enqueue_scripts', function () {
    wp_register_style('investalo-market-snapshot', false);

    $css = "
    .investalo-snapshot-box{
        border:1px solid #e2e8f0;
        padding:18px;
        border-radius:16px;
        background:#ffffff;
        box-shadow:0 4px 14px rgba(0,0,0,0.06);
        margin-top:16px;
        font-family:inherit;
    }
    .snapshot-header{
        font-weight:700;
        margin-bottom:8px;
        font-size:1.05rem;
    }
    .snapshot-mode{
        font-size:1.15rem;
        font-weight:800;
        margin-bottom:6px;
    }
    .mode-riskon{color:#16a34a;}
    .mode-riskoff{color:#dc2626;}
    .mode-neutral{color:#6b7280;}
    .snapshot-assets{
        margin:10px 0;
        line-height:1.6;
    }
    .snapshot-summary{
        margin-top:8px;
        font-style:italic;
        color:#374151;
    }
    .snapshot-footnote{
        margin-top:8px;
        font-size:0.75rem;
        color:#6b7280;
    }
    ";

    wp_enqueue_style('investalo-market-snapshot');
    wp_add_inline_style('investalo-market-snapshot', $css);
});

/* -----------------------------
   Sentiment & Stärke
----------------------------- */
function investalo_strength_label($change) {
    if ($change >= 0.015) return 'impulsiver Aufwärtstrend';
    if ($change >= 0.005) return 'moderates Momentum';
    if ($change <= -0.015) return 'starker Abwärtsdruck';
    if ($change <= -0.005) return 'leichter Verkaufsdruck';
    return 'seitwärts / unentschlossen';
}

function investalo_direction($change) {
    if ($change > 0.005) return 'up';
    if ($change < -0.005) return 'down';
    return 'flat';
}

/* -----------------------------
   Marktmodus Logik (gewichtet)
----------------------------- */
function investalo_market_mode($btc, $dax, $gold) {

    if ($btc === 'up' && $dax !== 'down' && $gold !== 'up') {
        return [
            'label' => 'Risk-On',
            'class' => 'mode-riskon',
            'summary' => 'Risikomärkte zeigen Führung, defensive Nachfrage bleibt aus.'
        ];
    }

    if ($btc === 'down' && $gold === 'up') {
        return [
            'label' => 'Risk-Off',
            'class' => 'mode-riskoff',
            'summary' => 'Kapital sucht Sicherheit, Risikoassets stehen unter Druck.'
        ];
    }

    return [
        'label' => 'Übergangsphase',
        'class' => 'mode-neutral',
        'summary' => 'Kein klarer Kapitalfluss – Markt sammelt Orientierung.'
    ];
}

/* -----------------------------
   Market Snapshot Service
----------------------------- */
function investalo_market_snapshot() {

    $cached = get_transient('investalo_market_snapshot_v21');
    if ($cached !== false) return $cached;

    $api_key = get_option('twelve_data_api_key');
    if (empty($api_key)) {
        return '<p style="color:#dc2626">Twelve Data API-Key fehlt.</p>';
    }

    $assets = [
        'BTC/USD' => 'Bitcoin',
        'DAX'     => 'DAX',
        'XAU/USD' => 'Gold'
    ];

    $directions = [];
    $lines = [];

    foreach ($assets as $symbol => $label) {
        $url = "https://api.twelvedata.com/time_series?symbol={$symbol}&interval=4h&outputsize=2&apikey={$api_key}";
        $response = wp_remote_get($url);
        if (is_wp_error($response)) continue;

        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (!isset($data['values'][0], $data['values'][1])) continue;

        $current = (float) $data['values'][0]['close'];
        $previous = (float) $data['values'][1]['close'];
        if ($previous <= 0) continue;

        $change = ($current - $previous) / $previous;
        $strength = investalo_strength_label($change);
        $direction = investalo_direction($change);

        $directions[$label] = $direction;
        $lines[] = "<strong>{$label}</strong> → {$strength}";
    }

    if (count($directions) < 3) {
        return '<p>Marktdaten derzeit unvollständig.</p>';
    }

    $mode = investalo_market_mode(
        $directions['Bitcoin'],
        $directions['DAX'],
        $directions['Gold']
    );

    $output = '<div class="investalo-snapshot-box">';
    $output .= '<div class="snapshot-header">🧭 Marktstimmung – kurzfristiges Momentum (4h)</div>';
    $output .= '<div class="snapshot-mode ' . esc_attr($mode['class']) . '">' . esc_html($mode['label']) . '</div>';
    $output .= '<div class="snapshot-assets">' . implode('<br>', $lines) . '</div>';
    $output .= '<div class="snapshot-summary">' . esc_html($mode['summary']) . '</div>';
    $output .= '<div class="snapshot-footnote">Basis: letzte abgeschlossene 4h-Periode · BTC · DAX · Gold · automatisierte Auswertung</div>';
    $output .= '</div>';

    set_transient('investalo_market_snapshot_v21', $output, 900);
    return $output;
}

add_shortcode('market_snapshot', 'investalo_market_snapshot');
