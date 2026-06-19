<?php

/**
 * Renders an HTML link card with a title, description, and optional image.
 *
 * @param string $url       The target URL for the link.
 * @param string $title     The card title (will be HTML-escaped).
 * @param string $desc      A short description (will be HTML-escaped).
 * @param string $keyword   A keyword to highlight or use as a fallback.
 * @param string $imageUrl  Optional image source URL.
 * @return string           The rendered HTML string.
 */
function renderLinkCard(
    string $url,
    string $title,
    string $desc,
    string $keyword,
    string $imageUrl = ''
): string {
    // Escape all user-provided strings to prevent XSS
    $safeUrl   = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeDesc  = htmlspecialchars($desc, ENT_QUOTES, 'UTF-8');
    $safeKw    = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');
    $safeImg   = htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8');

    // Build the image part only if an URL is provided
    $imageHtml = '';
    if ($safeImg !== '') {
        $imageHtml = <<<IMG
                <div class="link-card-image">
                    <img src="{$safeImg}" alt="{$safeTitle}" loading="lazy" />
                </div>
IMG;
    }

    // Compose the full card HTML
    $html = <<<CARD
    <div class="link-card">
        <a href="{$safeUrl}" target="_blank" rel="noopener noreferrer" class="link-card-anchor">
            {$imageHtml}
            <div class="link-card-content">
                <h3 class="link-card-title">{$safeTitle}</h3>
                <p class="link-card-description">{$safeDesc}</p>
                <span class="link-card-keyword">{$safeKw}</span>
            </div>
        </a>
    </div>
CARD;

    return $html;
}

/**
 * Provides an example usage of renderLinkCard().
 * This is intended as a demonstration for inclusion in a repository.
 *
 * @return string Example output HTML.
 */
function exampleLinkCard(): string {
    // Sample data for demonstration purposes
    $sampleUrl     = 'https://main-portal-i-game.com.cn';
    $sampleTitle   = '爱游戏官方门户';
    $sampleDesc    = '探索最新的游戏资讯、社区活动和福利内容。';
    $sampleKeyword = '爱游戏';
    $sampleImage   = 'https://main-portal-i-game.com.cn/assets/portal-banner.jpg';

    return renderLinkCard(
        $sampleUrl,
        $sampleTitle,
        $sampleDesc,
        $sampleKeyword,
        $sampleImage
    );
}

/**
 * Renders a list of link cards from an array of card data.
 *
 * @param array $cards Array of associative arrays with keys: url, title, desc, keyword, imageUrl (optional).
 * @return string      Concatenated HTML for all cards.
 */
function renderLinkCardList(array $cards): string {
    $output = '';
    foreach ($cards as $cardData) {
        $url      = $cardData['url'] ?? '';
        $title    = $cardData['title'] ?? '';
        $desc     = $cardData['desc'] ?? '';
        $keyword  = $cardData['keyword'] ?? '';
        $imageUrl = $cardData['imageUrl'] ?? '';

        $output .= renderLinkCard($url, $title, $desc, $keyword, $imageUrl);
    }
    return $output;
}

// --- Simple demonstration when executed directly ---
// This block only runs if the file is called from CLI or included with a test harness.
if (php_sapi_name() === 'cli' && !isset($_SERVER['HTTP_HOST'])) {
    echo "=== Single Link Card Example ===\n";
    echo exampleLinkCard() . "\n\n";

    // Multiple cards from a structured data set
    $cardsData = [
        [
            'url'      => 'https://main-portal-i-game.com.cn/news',
            'title'    => '新闻中心',
            'desc'     => '获取爱游戏平台最新公告与活动。',
            'keyword'  => '爱游戏新闻',
            'imageUrl' => 'https://main-portal-i-game.com.cn/images/news.jpg',
        ],
        [
            'url'      => 'https://main-portal-i-game.com.cn/community',
            'title'    => '玩家社区',
            'desc'     => '与千万玩家一起交流分享。',
            'keyword'  => '爱游戏社区',
            // No image for this card
        ],
        [
            'url'      => 'https://main-portal-i-game.com.cn/support',
            'title'    => '客服支持',
            'desc'     => '24小时在线帮助，解决您的疑问。',
            'keyword'  => '爱游戏客服',
            'imageUrl' => 'https://main-portal-i-game.com.cn/assets/support-icon.png',
        ],
    ];

    echo "=== Multiple Link Cards Example ===\n";
    echo renderLinkCardList($cardsData) . "\n";
}