<?php

namespace App\Libraries;

use App\Models\SettingsModel;

class FacebookImporter
{
    /**
     * Parse and fetch details from a Facebook post URL
     *
     * @param string $url
     * @return array
     */
    public function fetchPost(string $url): array
    {
        $url = trim($url);
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return [
                'success' => false,
                'message' => 'กรุณาระบุ URL ที่ถูกต้อง'
            ];
        }

        // 1. Resolve short/share redirects (e.g. share/p/..., fb.me/..., fb.watch/...)
        $resolvedUrl = $this->resolveRedirectUrl($url);
        $cleanUrl = $this->normalizeUrl($resolvedUrl);

        // Check if Facebook Access Token is configured in Settings
        $settingsModel = new SettingsModel();
        $accessToken = $settingsModel->getVal('facebook_access_token') ?? $settingsModel->getVal('facebook_app_token');

        if (!empty($accessToken)) {
            $apiResult = $this->fetchFromGraphApi($cleanUrl, $accessToken);
            if ($apiResult['success'] && !empty($apiResult['data']['content'])) {
                return $apiResult;
            }
        }

        // 2. Fetch HTML from multiple public endpoints to capture all attachments & full text
        $collectedHtml = [];

        // Approach A: Facebook Public Embed Plugin for the resolved URL (exact post only)
        $embedUrl = 'https://www.facebook.com/plugins/post.php?href=' . urlencode($cleanUrl) . '&show_text=true&width=750';
        $embedHtml = $this->requestHtml($embedUrl, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36');
        if (!empty($embedHtml)) {
            $collectedHtml[] = $embedHtml;
        }

        // Approach B: Mobile Touch Site (exact post page)
        $mobileUrl = preg_replace('#https?://(?:www\.|mbasic\.|web\.)?facebook\.com/#i', 'https://m.facebook.com/', $cleanUrl);
        $mobileHtml = $this->requestHtml($mobileUrl, 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile/15E148 Safari/604.1');
        if (!empty($mobileHtml)) {
            $collectedHtml[] = $mobileHtml;
        }

        // Approach C: Facebook External Hit Crawler (exact post page)
        $externalHitHtml = $this->requestHtml($cleanUrl, 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)');
        if (!empty($externalHitHtml)) {
            $collectedHtml[] = $externalHitHtml;
        }



        $allHtml = implode("\n", $collectedHtml);

        if (!empty($allHtml)) {
            $result = $this->extractDeepPostData($allHtml, $cleanUrl);
            if ($result['success']) {
                return $result;
            }
        }

        return [
            'success' => false,
            'message' => 'ไม่สามารถดึงข้อมูลจากลิงก์ Facebook นี้ได้โดยอัตโนมัติ (อาจเป็นโพสต์ส่วนตัว หรือโพสต์ถูกจำกัดสิทธิ์)',
            'data' => [
                'title' => '',
                'content' => '',
                'cover_url' => null,
                'cover_local' => null,
                'gallery_urls' => [],
                'gallery_items' => [],
                'created_at' => date('Y-m-d\TH:i'),
                'original_url' => $url
            ]
        ];
    }

    /**
     * Resolve short/share redirects to reach canonical post URL
     */
    public function resolveRedirectUrl(string $url): string
    {
        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_USERAGENT => 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
                CURLOPT_HEADER => true
            ]);

            $response = curl_exec($ch);
            $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            curl_close($ch);

            if (!empty($effectiveUrl) && $effectiveUrl !== $url && strpos($effectiveUrl, 'facebook.com/login') === false) {
                return $effectiveUrl;
            }

            // Also check for meta refresh or og:url in body
            if ($response) {
                if (preg_match('/<meta\s+property=["\']og:url["\']\s+content=["\']([^"\']+)["\']/i', $response, $ogMatch)) {
                    return $ogMatch[1];
                }
                if (preg_match('/<link\s+rel=["\']canonical["\']\s+href=["\']([^"\']+)["\']/i', $response, $canMatch)) {
                    return $canMatch[1];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Resolve redirect error: ' . $e->getMessage());
        }

        return $url;
    }

    /**
     * Normalize URL (strip tracking params and normalize hostname)
     */
    public function normalizeUrl(string $url): string
    {
        $parsed = parse_url($url);
        if (!$parsed || !isset($parsed['host'])) {
            return $url;
        }

        $scheme = $parsed['scheme'] ?? 'https';
        $host = 'www.facebook.com';
        $path = $parsed['path'] ?? '';

        $query = '';
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $queryParams);
            $cleanParams = [];
            $keepKeys = ['story_fbid', 'id', 'fbid', 'v', 'post_id', 'bid', 'set'];
            foreach ($keepKeys as $k) {
                if (isset($queryParams[$k])) {
                    $cleanParams[$k] = $queryParams[$k];
                }
            }
            if (!empty($cleanParams)) {
                $query = '?' . http_build_query($cleanParams);
            }
        }

        return "{$scheme}://{$host}{$path}{$query}";
    }

    /**
     * cURL HTML Fetcher
     */
    protected function requestHtml(string $url, string $userAgent): ?string
    {
        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 6,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_CONNECTTIMEOUT => 8,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_ENCODING => 'gzip,deflate',
                CURLOPT_HTTPHEADER => [
                    'Referer: https://developers.facebook.com/docs/plugins/embedded-posts/',
                    'Accept-Language: th-TH,th;q=0.9,en-US;q=0.8,en;q=0.7',
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
                    'Sec-Fetch-Dest: document',
                    'Sec-Fetch-Mode: navigate',
                    'Sec-Fetch-Site: cross-site',
                    'Cache-Control: no-cache',
                    'Pragma: no-cache'
                ]
            ]);

            $html = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode >= 200 && $httpCode < 400 && !empty($html)) {
                return $html;
            }
        } catch (\Exception $e) {
            log_message('error', 'Facebook requestHtml error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Parallel cURL HTML Fetcher for multiple URLs
     */
    protected function requestMultipleHtml(array $urls): array
    {
        if (empty($urls)) return [];

        $mh = curl_multi_init();
        $handles = [];
        $results = [];

        foreach ($urls as $idx => $url) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_CONNECTTIMEOUT => 6,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
                CURLOPT_ENCODING => 'gzip,deflate',
                CURLOPT_HTTPHEADER => [
                    'Referer: https://developers.facebook.com/docs/plugins/embedded-posts/',
                    'Accept-Language: th-TH,th;q=0.9,en-US;q=0.8,en;q=0.7',
                    'Sec-Fetch-Mode: navigate',
                    'Sec-Fetch-Site: cross-site'
                ]
            ]);
            curl_multi_add_handle($mh, $ch);
            $handles[$idx] = $ch;
        }

        $running = null;
        do {
            $status = curl_multi_exec($mh, $running);
            if ($running > 0) {
                curl_multi_select($mh, 0.05);
            }
        } while ($running > 0 && $status === CURLM_OK);

        foreach ($handles as $idx => $ch) {
            $html = curl_multi_getcontent($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);

            if ($code >= 200 && $code < 400 && !empty($html)) {
                $results[] = $html;
            }
        }

        curl_multi_close($mh);
        return $results;
    }

    /**
     * Deep extraction of Title, Full Content with Auto-Links, High-Res Images and Exact Date
     */
    protected function extractDeepPostData(string $html, string $url): array
    {
        $ogTags = $this->getMetaTags($html);

        // 1. EXTRACT POST TIMESTAMP / DATE
        $postTimestamp = null;

        if (preg_match('/"datePublished":\s*"([^"]+)"/i', $html, $dateMatch)) {
            $ts = strtotime($dateMatch[1]);
            if ($ts && $ts > 0) $postTimestamp = $ts;
        }

        if (!$postTimestamp && preg_match_all('/"(?:creation_time|publish_time|publish_timestamp|post_date|created_time)":\s*(\d{10})/i', $html, $tsMatches)) {
            foreach ($tsMatches[1] as $matchTs) {
                $val = intval($matchTs);
                if ($val > 1514764800 && $val <= (time() + 86400)) {
                    $postTimestamp = $val;
                    break;
                }
            }
        }

        if (!$postTimestamp && preg_match('/data-utime=["\'](\d{10})["\']/i', $html, $uMatch)) {
            $postTimestamp = intval($uMatch[1]);
        }

        if (!$postTimestamp && preg_match('/abbr[^>]*data-store=["\'][^"\']*?time&quot;:\s*(\d{10})/i', $html, $uMatch2)) {
            $postTimestamp = intval($uMatch2[1]);
        }

        $formattedDate = $postTimestamp ? date('Y-m-d\TH:i', $postTimestamp) : date('Y-m-d\TH:i');


        // 2. EXTRACT FULL COMPLETE CONTENT
        $candidates = [];

        // A. Extract from GraphQL / ServerJS payload ("message":{"text":"..."})
        if (preg_match_all('/"(?:message|story_text)":\s*\{\s*"text":\s*"((?:[^"\\\\]|\\\\.)*)"\s*\}/u', $html, $msgMatches)) {
            foreach ($msgMatches[1] as $rawMsg) {
                $decoded = $this->cleanJsonString($rawMsg);
                if (!empty($decoded) && mb_strlen($decoded) > 5) {
                    $candidates[] = $decoded;
                }
            }
        }

        // B. Extract from Embed Plugin / mbasic userContent
        if (preg_match_all('/<div[^>]*class=["\'][^"\']*(?:userContent|_5pbx|_5rgt|_5nk5)[^"\']*["\'][^>]*>(.*?)<\/div>/is', $html, $contentMatches)) {
            foreach ($contentMatches[1] as $rawBlock) {
                $plain = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], "\n", $rawBlock));
                $plain = html_entity_decode($plain, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $plain = trim($plain);
                if (!empty($plain) && mb_strlen($plain) > 5) {
                    $candidates[] = $plain;
                }
            }
        }

        // C. Extract from JSON-LD articleBody
        if (preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $ldMatches)) {
            foreach ($ldMatches[1] as $ldJson) {
                $ldData = json_decode($ldJson, true);
                if (isset($ldData['articleBody']) && !empty($ldData['articleBody'])) {
                    $candidates[] = trim($ldData['articleBody']);
                }
                if (isset($ldData['description']) && !empty($ldData['description'])) {
                    $candidates[] = trim($ldData['description']);
                }
            }
        }

        // D. Extract from OpenGraph description
        if (!empty($ogTags['og:description'])) {
            $candidates[] = html_entity_decode($ogTags['og:description'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        if (!empty($ogTags['description'])) {
            $candidates[] = html_entity_decode($ogTags['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        // Sort by length to pick the most complete text
        usort($candidates, function($a, $b) {
            return mb_strlen($b) - mb_strlen($a);
        });

        $rawFullContent = !empty($candidates) ? $candidates[0] : '';
        $rawFullContent = trim($rawFullContent);

        // Auto-linkify URLs in content
        $contentWithLinks = $this->autoLinkUrls($rawFullContent);

        // Parse Title & Cleaned Content
        $pageTitle = $ogTags['og:title'] ?? $ogTags['twitter:title'] ?? '';
        $pageTitle = preg_replace('/(\s*\|\s*Facebook|\s*-\s*Facebook|\s*on\s*Facebook)$/i', '', $pageTitle);
        $pageTitle = trim($pageTitle);

        $parsed = $this->parseTextAndTitle($rawFullContent, $pageTitle);


        // 3. EXTRACT ALL HIGH-RES IMAGES & RAPID PARALLEL DOWNLOAD
        $allImages = $this->extractAllHighResImages($html, $ogTags);

        // Download all images in parallel to temp folder
        $downloadedItems = $this->downloadImagesParallel($allImages, 'temp');

        $coverItem = !empty($downloadedItems) ? $downloadedItems[0] : null;
        $coverRemote = $coverItem ? $coverItem['remote'] : (!empty($allImages) ? $allImages[0] : null);
        $coverLocal = $coverItem ? $coverItem['local_file'] : null;
        $coverLocalUrl = $coverItem ? $coverItem['local_url'] : null;

        $galleryItems = array_slice($downloadedItems, 1);
        $galleryRemotes = array_slice($allImages, 1);

        if (!empty($parsed['title']) || !empty($rawFullContent) || !empty($coverRemote) || !empty($coverLocal)) {
            return [
                'success' => true,
                'data' => [
                    'title' => $parsed['title'],
                    'content' => $contentWithLinks,
                    'raw_content' => $rawFullContent,
                    'cover_url' => $coverRemote,
                    'cover_local' => $coverLocal,
                    'cover_local_url' => $coverLocalUrl,
                    'gallery_urls' => $galleryRemotes,
                    'gallery_items' => $galleryItems,
                    'created_at' => $formattedDate,
                    'original_url' => $url,
                    'source' => 'deep_scraped'
                ]
            ];
        }

        return ['success' => false];
    }

    /**
     * Convert URLs in text into clickable HTML <a> links
     */
    public function autoLinkUrls(string $text): string
    {
        if (strpos($text, '<a ') !== false) {
            return $text;
        }

        $pattern = '/(?<!href="|src=")(https?:\/\/[^\s<]+|www\.[^\s<]+)/i';
        
        return preg_replace_callback($pattern, function($matches) {
            $url = $matches[1];
            $href = (strpos($url, 'http') === 0) ? $url : 'https://' . $url;
            
            $trailing = '';
            if (preg_match('/[.,)>]+$/', $url, $tMatch)) {
                $trailing = $tMatch[0];
                $url = substr($url, 0, -strlen($trailing));
                $href = substr($href, 0, -strlen($trailing));
            }
            
            return '<a href="' . htmlspecialchars($href, ENT_QUOTES) . '" target="_blank" rel="noopener noreferrer" class="text-blue-600 font-bold underline hover:text-blue-800">' . htmlspecialchars($url) . '</a>' . $trailing;
        }, $text);
    }

    /**
     * Clean and unescape JSON string with full Thai unicode support
     */
    protected function cleanJsonString(string $raw): string
    {
        $decoded = json_decode('"' . $raw . '"');
        if ($decoded === null) {
            $decoded = stripcslashes($raw);
        }
        $decoded = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return trim($decoded);
    }

    /**
     * Extract ALL high-resolution images from HTML, OpenGraph, Embed plugin and GraphQL payloads
     */
    protected function extractAllHighResImages(string $html, array $ogTags): array
    {
        $rawImagePool = [];

        // 1. OpenGraph & Twitter Images
        $priorityKeys = ['og:image', 'og:image:url', 'og:image:secure_url', 'twitter:image'];
        foreach ($priorityKeys as $k) {
            if (!empty($ogTags[$k])) {
                $clean = $this->cleanImageUrl($ogTags[$k]);
                if ($clean) $rawImagePool[] = $clean;
            }
        }

        // 2. All <img> tags in HTML (scaledImageFitWidth, img, etc.)
        if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/is', $html, $imgTagMatches)) {
            foreach ($imgTagMatches[1] as $rawSrc) {
                if (strpos($rawSrc, 'fbcdn.net') !== false || strpos($rawSrc, 'facebook.com') !== false) {
                    $clean = $this->cleanImageUrl($rawSrc);
                    if ($clean) $rawImagePool[] = $clean;
                }
            }
        }

        // 3. Extract from all GraphQL JSON payloads in scripts (subattachments, nodes, photo_image, full_size_image, uri, src)
        if (preg_match_all('/"(?:image|photo_image|full_size_image|preview_image|viewer_image|display_image|url|src|thumbnail)":\s*\{\s*"uri":\s*"((?:[^"\\\\]|\\\\.)*)"\s*\}/u', $html, $uriMatches)) {
            foreach ($uriMatches[1] as $rawUri) {
                $clean = $this->cleanImageUrl($rawUri);
                if ($clean) $rawImagePool[] = $clean;
            }
        }

        // 4. Regex match any direct scontent / fbcdn image URLs in scripts
        if (preg_match_all('/https:\/\/[^"\'\s<>]*(?:fbcdn\.net|facebook\.com)\/[^"\'\s<>]+\.(?:jpg|jpeg|png|webp)[^"\'\s<>]*/i', $html, $rawMatches)) {
            foreach ($rawMatches[0] as $match) {
                $clean = $this->cleanImageUrl($match);
                if ($clean) $rawImagePool[] = $clean;
            }
        }

        // 5. Deduplicate and filter by unique Photo IDs / High Resolution
        $uniquePhotos = [];
        $seenPhotoKeys = [];

        foreach ($rawImagePool as $img) {
            // Filter junk, page logos, avatars, and static UI icons
            if (
                strpos($img, 'emoji') !== false ||
                strpos($img, 'static.xx') !== false ||
                strpos($img, 'rsrc.php') !== false ||
                strpos($img, '16x16') !== false ||
                strpos($img, '32x32') !== false ||
                strpos($img, '48x48') !== false ||
                strpos($img, '50x50') !== false ||
                strpos($img, '60x60') !== false ||
                strpos($img, '100x100') !== false ||
                strpos($img, 'badge') !== false ||
                strpos($img, 'icon') !== false ||
                strpos($img, 'avatar') !== false ||
                strpos($img, 'profile') !== false ||
                preg_match('/\bt\d+\.\d+-1\b/i', $img) ||
                preg_match('/_a\.(?:jpg|png|webp)/i', $img)
            ) {
                continue;
            }

            // Extract unique photo identifier (e.g. fbid or main photo ID number)
            $photoKey = null;
            if (preg_match('/[?&]fbid=(\d+)/i', $img, $fbidMatch)) {
                $photoKey = 'fbid_' . $fbidMatch[1];
            } elseif (preg_match('/\/([^\/?#]+\.(?:jpg|jpeg|png|webp))/i', $img, $fileMatch)) {
                $filename = $fileMatch[1];
                // Remove resolution prefixes e.g. s565x565_, p261x260_, c0.0.100.100_
                $cleanFilename = preg_replace('/^(?:[spc]\d+(?:x\d+|\.\d+)*_)+/i', '', $filename);
                // Extract the core numeric photo ID (e.g. 1700080938786722)
                if (preg_match('/(?:^|_)?(\d{10,22})(?:_|$)/', $cleanFilename, $numMatch)) {
                    $photoKey = 'id_' . $numMatch[1];
                } else {
                    $photoKey = 'fn_' . $cleanFilename;
                }
            }

            if (!empty($photoKey) && isset($seenPhotoKeys[$photoKey])) {
                continue;
            }
            if (!empty($photoKey)) {
                $seenPhotoKeys[$photoKey] = true;
            }

            if (!in_array($img, $uniquePhotos)) {
                $uniquePhotos[] = $img;
            }

            if (count($uniquePhotos) >= 120) break;
        }

        return $uniquePhotos;
    }

    /**
     * Clean and normalize image URL
     */
    protected function cleanImageUrl(string $rawUrl): ?string
    {
        $url = str_replace('\\/', '/', $rawUrl);
        $url = html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $url = trim($url, '"\'\\ ');

        $url = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $url);

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        return null;
    }

    /**
     * Parse meta tags from HTML
     */
    protected function getMetaTags(string $html): array
    {
        $tags = [];
        if (preg_match_all('/<meta\s+[^>]*?(?:property|name)=["\']([^"\']+)["\']\s+[^>]*?content=["\']([^"\']*)["\']/is', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $tags[strtolower($m[1])] = $m[2];
            }
        }
        if (preg_match_all('/<meta\s+[^>]*?content=["\']([^"\']*)["\']\s+[^>]*?(?:property|name)=["\']([^"\']+)["\']/is', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $k = strtolower($m[2]);
                if (!isset($tags[$k])) {
                    $tags[$k] = $m[1];
                }
            }
        }
        return $tags;
    }

    /**
     * Split and structure Title and Content intelligently
     */
    protected function parseTextAndTitle(string $fullText, string $fallbackTitle = ''): array
    {
        $text = trim($fullText);
        
        if (empty($text)) {
            return [
                'title' => !empty($fallbackTitle) ? $fallbackTitle : 'ข่าวสารประชาสัมพันธ์จาก Facebook',
                'content' => ''
            ];
        }

        $lines = preg_split('/\r\n|\r|\n/', $text);
        $cleanLines = [];
        foreach ($lines as $l) {
            $trimmed = trim($l);
            if (!empty($trimmed)) {
                $cleanLines[] = $trimmed;
            }
        }

        $title = '';
        if (!empty($cleanLines)) {
            $firstLine = $cleanLines[0];
            if (mb_strlen($firstLine) <= 120) {
                $title = $firstLine;
            } else {
                $truncated = mb_substr($firstLine, 0, 95);
                $title = $truncated . '...';
            }
        }

        if (empty($title)) {
            $title = !empty($fallbackTitle) ? $fallbackTitle : 'ข่าวสารประชาสัมพันธ์จาก Facebook';
        }

        return [
            'title' => $title,
            'content' => $text
        ];
    }

    /**
     * Fetch from Facebook Graph API (oEmbed endpoint)
     */
    protected function fetchFromGraphApi(string $url, string $accessToken): array
    {
        try {
            $apiUrl = 'https://graph.facebook.com/v19.0/oembed_post?' . http_build_query([
                'url' => $url,
                'access_token' => $accessToken,
                'omitscript' => 'true'
            ]);

            $ch = curl_init($apiUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                if ($data) {
                    $rawText = strip_tags($data['html'] ?? '');
                    $parsed = $this->parseTextAndTitle($rawText);
                    $contentWithLinks = $this->autoLinkUrls($rawText);

                    return [
                        'success' => true,
                        'data' => [
                            'title' => $parsed['title'],
                            'content' => $contentWithLinks,
                            'raw_content' => $rawText,
                            'cover_url' => null,
                            'cover_local' => null,
                            'cover_local_url' => null,
                            'gallery_urls' => [],
                            'gallery_items' => [],
                            'created_at' => date('Y-m-d\TH:i'),
                            'original_url' => $url,
                            'source' => 'graph_api'
                        ]
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Facebook Graph API fetch error: ' . $e->getMessage());
        }

        return ['success' => false];
    }

    /**
     * Fast Concurrent/Parallel Image Downloader with cURL Multi
     *
     * @param array $imageUrls
     * @param string $targetSubdir 'covers', 'gallery', or 'temp'
     * @return array Array of ['remote' => ..., 'local_file' => ..., 'local_url' => ...]
     */
    public function downloadImagesParallel(array $imageUrls, string $targetSubdir = 'temp'): array
    {
        if (empty($imageUrls)) {
            return [];
        }

        $targetDir = FCPATH . 'uploads/news/' . trim($targetSubdir, '/') . '/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $mh = curl_multi_init();
        $curlHandles = [];
        $fileMaps = [];

        foreach ($imageUrls as $i => $rawUrl) {
            $cleanUrl = $this->cleanImageUrl($rawUrl);
            if (!$cleanUrl) continue;

            $tempFilename = time() . '_' . bin2hex(random_bytes(6)) . "_{$i}.jpg";
            $savePath = $targetDir . $tempFilename;

            $ch = curl_init($cleanUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 4,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_CONNECTTIMEOUT => 6,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
                CURLOPT_HTTPHEADER => [
                    'Accept: image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
                    'Sec-Fetch-Dest: image',
                    'Sec-Fetch-Mode: no-cors',
                    'Sec-Fetch-Site: cross-site'
                ]
            ]);

            curl_multi_add_handle($mh, $ch);
            $curlHandles[$i] = $ch;
            $fileMaps[$i] = [
                'remote' => $cleanUrl,
                'save_path' => $savePath,
                'filename' => $tempFilename
            ];
        }

        // Execute parallel requests
        $running = null;
        do {
            $status = curl_multi_exec($mh, $running);
            if ($running > 0) {
                curl_multi_select($mh, 0.05);
            }
        } while ($running > 0 && $status === CURLM_OK);

        $results = [];

        // Collect downloaded files
        foreach ($curlHandles as $i => $ch) {
            $imageData = curl_multi_getcontent($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);

            if ($httpCode === 200 && !empty($imageData) && strlen($imageData) > 600) {
                // Check image dimensions to exclude tiny profile icons / emojis (< 100px)
                $sizeInfo = @getimagesizefromstring($imageData);
                if ($sizeInfo) {
                    $width = $sizeInfo[0];
                    $height = $sizeInfo[1];
                    // Skip tiny UI icons (< 100px)
                    if ($width < 100 || $height < 100) {
                        continue;
                    }
                }

                $savePath = $fileMaps[$i]['save_path'];
                $filename = $fileMaps[$i]['filename'];

                // Fix extension based on content if needed
                if (strpos($contentType, 'png') !== false || substr($imageData, 0, 8) === "\x89PNG\r\n\x1a\n") {
                    $newFilename = str_replace('.jpg', '.png', $filename);
                    $savePath = $targetDir . $newFilename;
                    $filename = $newFilename;
                } elseif (strpos($contentType, 'webp') !== false || substr($imageData, 8, 4) === 'WEBP') {
                    $newFilename = str_replace('.jpg', '.webp', $filename);
                    $savePath = $targetDir . $newFilename;
                    $filename = $newFilename;
                }

                if (file_put_contents($savePath, $imageData) !== false) {
                    $localUrl = function_exists('base_url') 
                        ? base_url('uploads/news/' . trim($targetSubdir, '/') . '/' . $filename)
                        : '/uploads/news/' . trim($targetSubdir, '/') . '/' . $filename;

                    $results[] = [
                        'remote' => $fileMaps[$i]['remote'],
                        'local_file' => $filename,
                        'local_url' => $localUrl
                    ];
                }
            }
        }

        curl_multi_close($mh);
        return $results;
    }

    /**
     * Download single image
     */
    public function downloadAndSaveImage(string $imageUrl, string $targetSubdir = 'covers'): ?string
    {
        $res = $this->downloadImagesParallel([$imageUrl], $targetSubdir);
        return !empty($res) ? $res[0]['local_file'] : null;
    }

    /**
     * Clean up orphaned / old temporary files from uploads/news/temp/ and writable/uploads/temp/
     *
     * @param int $maxAgeSeconds Maximum age in seconds before deleting (default 1800 = 30 mins)
     */
    public function cleanOldTempFiles(int $maxAgeSeconds = 1800): void
    {
        $tempDirs = [
            FCPATH . 'uploads/news/temp/',
            WRITEPATH . 'uploads/temp/'
        ];

        $now = time();
        foreach ($tempDirs as $dir) {
            if (is_dir($dir)) {
                $files = glob($dir . '*');
                if ($files) {
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            $mtime = @filemtime($file);
                            if ($mtime && ($now - $mtime) > $maxAgeSeconds) {
                                @unlink($file);
                            }
                        }
                    }
                }
            }
        }
    }
}
