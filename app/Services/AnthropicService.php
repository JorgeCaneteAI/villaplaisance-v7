<?php
declare(strict_types=1);

class AnthropicService
{
    private const API_URL = 'https://api.anthropic.com/v1/messages';
    private const MODEL = 'claude-sonnet-4-20250514';

    public static function generateArticle(string $type, string $title, string $subtitle = '', string $category = ''): ?array
    {
        $apiKey = $_ENV['ANTHROPIC_API_KEY'] ?? '';
        if ($apiKey === '') {
            return null;
        }

        $typeLabel = $type === 'sur-place' ? 'guide pratique / recommandation locale' : 'article de journal / blog';

        $prompt = <<<PROMPT
Tu es le rédacteur de Villa Plaisance, une maison d'hôtes de charme à Bédarrides (Vaucluse, Provence).
Double offre saisonnière : chambres d'hôtes (sept–juin) et villa entière avec piscine privée (juil–août). 10 personnes max.
Tu rédiges un {$typeLabel}.

CONTEXTE VILLA PLAISANCE :
- Située à Bédarrides, entre Avignon, Orange et Châteauneuf-du-Pape
- 3 chambres d'hôtes avec petit-déjeuner inclus, piscine partagée
- En été : villa entière (5 chambres, piscine privée, jardin méditerranéen)
- Note Booking 9.4/10, Superhost Airbnb
- Environnement : vignobles, villages provençaux, marchés, cigales, oliviers

TON ET STYLE — TRÈS IMPORTANT :
- Ton SIMPLE, amical, comme un ami qui donne des conseils. Pas expert, pas professoral.
- Phrases courtes. Pas de formules creuses. Aller droit au but.
- Parler comme si on discutait autour d'une table. Tutoyer le lecteur ou utiliser "vous" naturellement.
- Inclure des anecdotes, des détails vécus, du concret (noms de lieux, distances, prix quand pertinent)
- Humour léger bienvenu. Jamais pompeux.
- Le mot "luxe" est INTERDIT. Dire : charme, caractère, authenticité, simplicité
- Pas de superlatifs vides ("exceptionnel", "incontournable", "magnifique"). Montrer plutôt que dire.

OPTIMISATION SEO :
- Intégrer naturellement le mot-clé principal dans le titre H1, le premier paragraphe, et 2-3 H2
- Utiliser des variantes longue traîne dans les sous-titres
- Structurer avec 3-5 H2 qui répondent à des questions que les gens cherchent
- Le premier paragraphe doit accrocher ET contenir le mot-clé (featured snippet)
- Maillage interne : mentionner Villa Plaisance et Bédarrides naturellement

OPTIMISATION GSO (IA : ChatGPT, Perplexity, Google AI) :
- Le résumé GSO doit répondre directement à la question implicite du titre
- Phrases courtes, factuelles, avec des données concrètes
- Format "réponse directe" : l'IA doit pouvoir citer ce texte tel quel
- Inclure : qui, quoi, où, quand, combien

LONGUEUR : 800-1200 mots pour le contenu FR. Pas de remplissage.
STRUCTURE : 3-5 sections avec titres H2 (questions ou angles concrets)

TITRE : {$title}
SOUS-TITRE / ANGLE : {$subtitle}
CATÉGORIE : {$category}

Génère le résultat au format JSON STRICT suivant (pas de markdown autour, UNIQUEMENT le JSON) :

{
  "fr": {
    "title": "titre FR accrocheur et naturel",
    "excerpt": "extrait FR, 2-3 phrases qui donnent envie de lire (100-150 car.)",
    "content": "contenu complet FR avec ## pour H2, > pour citations, paragraphes séparés par double saut de ligne. 800-1200 mots.",
    "meta_title": "meta title FR optimisé SEO (50-60 car.)",
    "meta_desc": "meta description FR avec appel à l'action (130-155 car.)",
    "meta_keywords": "mot-clé principal, variante 1, variante 2, lieu, thème",
    "gso_desc": "Résumé factuel GSO : réponse directe en 2-3 phrases. Données concrètes. Citable par une IA."
  },
  "en": {
    "title": "English title — natural, not literal translation",
    "excerpt": "English excerpt (100-150 chars)",
    "content": "Full English content, same structure, adapted for English-speaking travelers. NOT a word-for-word translation.",
    "meta_title": "SEO meta title EN (50-60 chars)",
    "meta_desc": "Meta description EN with CTA (130-155 chars)",
    "meta_keywords": "main keyword, variant 1, variant 2, location",
    "gso_desc": "Factual GSO summary in English. Direct answer. Concrete data."
  },
  "es": {
    "title": "Título en español — natural, adaptado",
    "excerpt": "Extracto ES (100-150 car.)",
    "content": "Contenido completo en español, misma estructura, adaptado para viajeros hispanohablantes. NO traducción literal.",
    "meta_title": "Meta title ES optimizado SEO (50-60 car.)",
    "meta_desc": "Meta description ES con llamada a la acción (130-155 car.)",
    "meta_keywords": "palabra clave, variante 1, variante 2, lugar",
    "gso_desc": "Resumen factual GSO en español. Respuesta directa. Datos concretos."
  }
}
PROMPT;

        $payload = [
            'model' => self::MODEL,
            'max_tokens' => 8000,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ];

        $ch = curl_init(self::API_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-api-key: ' . $apiKey,
                'anthropic-version: 2023-06-01',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 120,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        // curl_close deprecated in PHP 8.5, handle is auto-freed

        if ($error || $httpCode !== 200) {
            $errData = json_decode($response, true);
            $errMsg = $errData['error']['message'] ?? "HTTP {$httpCode}";
            error_log("Anthropic API error: {$errMsg}");
            // Return error detail for the frontend
            return ['_error' => $errMsg];
        }

        $data = json_decode($response, true);
        $text = $data['content'][0]['text'] ?? '';

        // Extract JSON from response (handle potential markdown wrapping)
        if (preg_match('/\{[\s\S]*\}/m', $text, $match)) {
            $result = json_decode($match[0], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $result;
            }
        }

        error_log("Anthropic: failed to parse JSON from response");
        return null;
    }
}
