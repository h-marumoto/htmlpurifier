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

}
