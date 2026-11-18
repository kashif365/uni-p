<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingsHelper
{
    /**
     * Get a setting value
     */
    public static function get($key, $default = null)
    {
        return Setting::get($key, $default);
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value)
    {
        return Setting::set($key, $value);
    }

    /**
     * Get all settings as array
     */
    public static function getAll()
    {
        return Setting::getAll();
    }

    /**
     * Get branding settings
     */
    public static function getBranding()
    {
        $logo = self::get('site_logo', '');
        $favicon = self::get('site_favicon', '');
        $siteName = self::get('site_name', 'Campus Deals');
        $siteTagline = self::get('site_tagline', 'Save More, Shop Smart');
        
        return [
            'site_name' => $siteName,
            'site_tagline' => $siteTagline,
            'site_logo' => $logo,
            'site_logo_url' => $logo ? asset($logo) : '',
            'site_favicon' => $favicon,
            'site_favicon_url' => $favicon ? asset($favicon) : '',
            'primary_color' => self::get('primary_color', '#FF0000'),
            'secondary_color' => self::get('secondary_color', '#000000'),
            'background_primary_color' => self::get('background_primary_color', '#FFFFFF'),
            'background_secondary_color' => self::get('background_secondary_color', '#F8F9FA'),
            'text_color' => self::get('text_color', '#333333'),
        ];
    }

    /**
     * Get contact settings
     */
    public static function getContact()
    {
        return [
            'contact_email' => self::get('contact_email', 'support@example.invalid'),
            'contact_phone' => self::get('contact_phone', ''),
            'contact_address' => self::get('contact_address', ''),
            'business_hours' => self::get('business_hours', ''),
            'support_email' => self::get('support_email', 'support@example.invalid'),
            'partnership_email' => self::get('partnership_email', 'campus@example.invalid'),
        ];
    }

    /**
     * Get social media settings
     */
    public static function getSocial()
    {
        return [
            'facebook_url' => self::get('facebook_url', ''),
            'twitter_url' => self::get('twitter_url', ''),
            'instagram_url' => self::get('instagram_url', ''),
            'linkedin_url' => self::get('linkedin_url', ''),
            'youtube_url' => self::get('youtube_url', ''),
            'tiktok_url' => self::get('tiktok_url', ''),
        ];
    }

    /**
     * Get general settings
     */
    public static function getGeneral()
    {
        $siteDescription = self::get('site_description', 'Find the best discount codes and deals for your favorite stores. Save money on every purchase with Campus Deals.');

        return [
            'site_description' => $siteDescription,
            'site_keywords' => self::get('site_keywords', 'discount codes, coupons, deals, savings, shopping'),
            'timezone' => self::get('timezone', 'Europe/London'),
            'currency' => self::get('currency', 'GBP'),
            'items_per_page' => self::get('items_per_page', '20'),
        ];
    }
}
