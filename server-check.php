<?php

echo '<h1>Uchunguzi wa Server (Winga)</h1>';
echo '<p><strong>Toleo la PHP linalotumika sasa:</strong> '.phpversion().'</p>';

if (version_compare(phpversion(), '8.3.0', '<')) {
    echo "<p style='color: red; font-weight: bold;'>⚠️ TATIZO: Toleo lako la PHP ni dogo sana (".phpversion().'). Mfumo huu unahitaji angalau PHP asilimia 8.3 (PHP >= 8.3.0).</p>';
    echo "<p><strong>Jinsi ya kutatua:</strong> Ingia kwenye cPanel yako, tafuta <strong>'Select PHP Version'</strong> au <strong>'MultiPHP Manager'</strong> kisha ubadilishe kutoka ".phpversion().' kwenda <strong>8.3</strong> au <strong>8.4</strong>.</p>';
} else {
    echo "<p style='color: green; font-weight: bold;'>✅ SAFI: Toleo la PHP ni zuri na linakidhi mahitaji (".phpversion().')</p>';
}

echo '<hr>';
echo '<h3>Maelezo ya Ziada (PHP Info)</h3>';
phpinfo();
