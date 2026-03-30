<?php
$heading = $heading ?? t('home.territoire.title');

$proximites = [];
try {
    $currentLang = LangService::get();
    $proximites = Database::fetchAll("SELECT * FROM vp_proximites WHERE lang = ? ORDER BY distance_min ASC", [$currentLang]);
} catch (\Throwable) {}
if (empty($proximites)) return;
?>
<section class="section section-alt" id="territoire">
    <div class="container">
        <h2><?= htmlspecialchars($heading) ?></h2>
        <div class="territoire-grid">
            <?php
            $catIconMap = [
                'ville' => 'icon-village', 'village' => 'icon-village',
                'monument' => 'icon-monument', 'patrimoine' => 'icon-monument',
                'vignoble' => 'icon-vignoble', 'vin' => 'icon-vin',
                'theatre' => 'icon-theatre', 'spectacle' => 'icon-theatre',
                'antiquites' => 'icon-antiquites', 'antiquité' => 'icon-antiquites',
                'aqueduc' => 'icon-aqueduc',
                'archeologie' => 'icon-archeologie', 'archéologie' => 'icon-archeologie',
                'montagne' => 'icon-montagne', 'nature' => 'icon-montagne',
                'activité' => 'icon-velo', 'activite' => 'icon-velo', 'sport' => 'icon-velo',
                'transport' => 'icon-transport', 'gare' => 'icon-transport',
            ];
            foreach ($proximites as $lieu):
                $cat = mb_strtolower($lieu['category'] ?? 'ville');
                $catIcon = $catIconMap[$cat] ?? 'icon-localisation';
            ?>
            <div class="territoire-item">
                <span class="territoire-icon"><?= ImageService::icon($catIcon, 20) ?></span>
                <span class="territoire-distance"><?= htmlspecialchars($lieu['distance']) ?></span>
                <span class="territoire-name"><?= htmlspecialchars($lieu['name']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
