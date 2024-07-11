<?php

namespace Lookup;

class Sitemap
{
    public static function Sitemap($host)
    {
        // Log: Starting to fetch sitemap for the provided host
        \Document\Log::Log("Sitemap: Starting to fetch sitemap for host: $host");

        // Fetch the sitemap content
        $sitemapContent = file_get_contents($host."/sitemap.xml");

        // Check if fetching the sitemap content failed
        if ($sitemapContent === false) {
            // Log: Failed to fetch sitemap for the provided host
            \Document\Log::Log("Sitemap: Failed to fetch sitemap for host: $host");
            return false;
        }

        // Log: Sitemap fetched successfully
        \Document\Log::Log("Sitemap: Sitemap fetched successfully for host: $host");

        return $sitemapContent;
    }
}
