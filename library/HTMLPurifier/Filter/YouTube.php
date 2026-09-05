<?php

class HTMLPurifier_Filter_YouTube extends HTMLPurifier_Filter
{

    /**
     * @type string
     */
    public $name = 'YouTube';

    /**
     * @param string $html
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return string
     */
    public function preFilter($html, $config, $context)
    {
        $pre_regex = '#<object[^>]+>.+?' .
            '(?:http:)?//www.youtube.com/((?:v|cp)/[A-Za-z0-9\-_=]+).+?</object>#s';
        $pre_replace = '<span class="youtube-embed">\1</span>';
        $html = (string) $html;
        $result = preg_replace($pre_regex, $pre_replace, $html);
        // preg_replace() returns null on error; pass input through
        return $result === null ? $html : $result;
    }

    /**
     * @param string $html
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return string
     */
    public function postFilter($html, $config, $context)
    {
        $post_regex = '#<span class="youtube-embed">((?:v|cp)/[A-Za-z0-9\-_=]+)</span>#';
        $html = (string) $html;
        $result = preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
        // preg_replace_callback() returns null on error; pass input through
        return $result === null ? $html : $result;
    }

    /**
     * @param $url
     * @return string
     */
    protected function armorUrl($url)
    {
        return str_replace('--', '-&#45;', $url);
    }

    /**
     * @param array $matches
     * @return string
     */
    protected function postFilterCallback($matches)
    {
        $url = $this->armorUrl($matches[1]);
        return '<object width="425" height="350" type="application/x-shockwave-flash" ' .
        'data="//www.youtube.com/' . $url . '">' .
        '<param name="movie" value="//www.youtube.com/' . $url . '"></param>' .
        '<!--[if IE]>' .
        '<embed src="//www.youtube.com/' . $url . '"' .
        'type="application/x-shockwave-flash"' .
        'wmode="transparent" width="425" height="350" />' .
        '<![endif]-->' .
        '</object>';
    }
}

// vim: et sw=4 sts=4
