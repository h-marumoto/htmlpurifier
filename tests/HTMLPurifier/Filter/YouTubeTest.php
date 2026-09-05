<?php

class HTMLPurifier_Filter_YouTubeTest extends HTMLPurifier_Harness
{

    public function test_preFilter()
    {
        $filter = new HTMLPurifier_Filter_YouTube();
        $input = '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/abc123"></param></object>';
        $this->assertIdentical(
            '<span class="youtube-embed">v/abc123</span>',
            $filter->preFilter($input, $this->config, $this->context)
        );
    }

    public function test_postFilter()
    {
        $filter = new HTMLPurifier_Filter_YouTube();
        $result = $filter->postFilter(
            '<span class="youtube-embed">v/abc123</span>',
            $this->config,
            $this->context
        );
        $this->assertTrue(strpos($result, '//www.youtube.com/v/abc123') !== false);
    }

    public function test_preFilter_pregFailurePassesInputThrough()
    {
        // preg_replace() returns null when the PCRE engine fails (e.g.
        // backtrack limit exhaustion); the filter must pass input through
        // rather than propagate null.
        $filter = new HTMLPurifier_Filter_YouTube();
        $input = '<object data="x">' . str_repeat('a ', 5000) . '!' . '</object>';
        $limit = ini_get('pcre.backtrack_limit');
        ini_set('pcre.backtrack_limit', 10);
        $result = $filter->preFilter($input, $this->config, $this->context);
        ini_set('pcre.backtrack_limit', $limit);
        $this->assertIdentical($input, $result);
    }

}
