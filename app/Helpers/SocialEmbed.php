<?php

namespace App\Helpers;

/**
 * Turn pasted social URLs into embed-friendly values for contestant gallery items.
 */
class SocialEmbed
{
    public static function linkTypes(): array
    {
        return ['link', 'short', 'youtube', 'tiktok', 'instagram', 'facebook'];
    }

    /** Normalize a pasted URL for storage in galleries.file */
    public static function normalizeForStorage(string $url, string $type): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        $type = self::normalizeType($type);

        switch ($type) {
            case 'youtube':
            case 'short':
                return self::youtubeEmbedUrl($url);
            case 'tiktok':
                return self::tiktokEmbedUrl($url) ?: $url;
            case 'instagram':
                return self::instagramEmbedUrl($url) ?: $url;
            case 'facebook':
                return self::facebookEmbedUrl($url) ?: $url;
            default:
                return null;
        }
    }

    public static function normalizeType(string $type): string
    {
        if ($type === 'link') {
            return 'youtube';
        }

        return $type;
    }

    public static function platformLabel(string $type): string
    {
        $map = [
            'youtube' => 'YouTube',
            'short' => 'YouTube Short',
            'tiktok' => 'TikTok',
            'instagram' => 'Instagram',
            'facebook' => 'Facebook',
            'link' => 'YouTube',
        ];

        return $map[$type] ?? ucfirst($type);
    }

    /** iframe src for display (handles legacy stored values). */
    public static function embedSrc(string $stored, string $type): ?string
    {
        $stored = trim($stored);
        if ($stored === '') {
            return null;
        }

        $type = self::normalizeType($type);

        if (strpos($stored, 'instagram.com') !== false && strpos($stored, '/embed') === false) {
            return self::instagramEmbedUrl($stored);
        }
        if (strpos($stored, 'tiktok.com') !== false && strpos($stored, '/embed/') === false) {
            return self::tiktokEmbedUrl($stored);
        }
        if (strpos($stored, 'facebook.com') !== false && strpos($stored, 'plugins/video.php') === false) {
            return self::facebookEmbedUrl($stored);
        }
        if (strpos($stored, 'youtube.com') !== false || strpos($stored, 'youtu.be') !== false) {
            return self::youtubeEmbedUrl($stored);
        }

        if (strpos($stored, 'http') === 0) {
            return $stored;
        }

        return null;
    }

    public static function youtubeEmbedUrl(string $url): ?string
    {
        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/|live/)|youtu\.be/)([a-zA-Z0-9_-]{11})~', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('~youtube\.com/embed/([a-zA-Z0-9_-]{11})~', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        return null;
    }

    public static function tiktokEmbedUrl(string $url): ?string
    {
        if (preg_match('~tiktok\.com/.*/video/(\d+)~', $url, $m)) {
            return 'https://www.tiktok.com/embed/v2/' . $m[1];
        }
        if (preg_match('~tiktok\.com/embed/v2/(\d+)~', $url, $m)) {
            return 'https://www.tiktok.com/embed/v2/' . $m[1];
        }

        return null;
    }

    public static function instagramEmbedUrl(string $url): ?string
    {
        if (preg_match('~instagram\.com/(?:p|reel|tv)/([^/?#]+)~', $url, $m)) {
            return 'https://www.instagram.com/p/' . $m[1] . '/embed';
        }
        if (preg_match('~instagram\.com/p/([^/?#]+)/embed~', $url, $m)) {
            return 'https://www.instagram.com/p/' . $m[1] . '/embed';
        }

        return null;
    }

    public static function facebookEmbedUrl(string $url): ?string
    {
        if (strpos($url, 'facebook.com') === false && strpos($url, 'fb.watch') === false) {
            return null;
        }

        return 'https://www.facebook.com/plugins/video.php?href=' . urlencode($url) . '&show_text=false&width=560';
    }
}
