<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Scrapers\Rumble\VideoPageScraper;

class VideoPageScraperTest extends TestCase
{
    /** @dataProvider url_is_valid_inputs */
    public function test_url_is_valid($url)
    {
        $vp = new VideoPageScraper($url);
        
        $this->assertTrue($vp->url() === $url);
    }

    static public function url_is_valid_inputs()
    {
        return [
            ['https://rumble.com/v2z1clu-why-females-survive-in-the-desert.html'],
            ['https://rumble.com/v2yb96i-andrew-tate-bugatti-tower-reveal.html'],
            ['https://rumble.com/v2z10t6-ufc-290-crowd-goes-insane-for-donald-trump-skip-bayless-in-trouble-mlb-all-.html'],
            ['https://rumble.com/v2yn47a-live-tucker-carlson-world-first-interview-since-leaving-fox-163-stay-free-w.html'],
            ['https://rumble.com/v2yhxwi-live-45th-president-donald-j.-trump-to-speak-at-nevada-volunteer-recruitmen.html']
        ];
    }

    /** @dataProvide url_is_invalid_inputs */
    public function test_constructor_throws_value_error($url)
    {
        $this->expectException(\ValueError::class);
    
        $vp = new VideoPageScraper($url);
    }

    // TO BE CONTINUED
    static public function url_is_invalid_inputs()
    {
        return [
            ['https://rumble.com/asb2'],
            ['https://rumble. com/asb2'],
            ['https://rumble.com/v/'],
            ['https://rumble.com/v23'],
            ['          https://rumble.com/v2z10t6-ufc-290-crowd-goes-insane-for-donald-trump-skip-bayless-in-trouble-mlb-all'],
            ['          https://rumble.com/v2yn47a-live-tucker-carlson-world-first-interview-since-leaving-fox-163-stay-free-w.html         '],
            ['!@#!@#!@#ASDASDS https://rumble.com/v2yhxwi-live-45th-president-donald-j.-trump-to-speak-at-nevada-volunteer-recruitmen.html'],
            [''],
            ['https://rumble.com/vasdda2yhxwi-live-45th-president-donald-j.-trump-to-speak-at-nevada-volunteer-recruitmen.html   '],
            ['https://rumble.com/v2yb96i-andrew-tate-bugatti-tower-reveal.html23.'],
            ['https://rumble.com/v2yb96i87990-andrew-tate-bugatti-tower-reveal..html'],
            ['https://rumble.com/V2YPRC8-THE-GAME-GETS-HARDER.HTML'],
            ['https://rumble.com/v2z1clu-why-females-survive-in-the-desert.html.'],
        ];
    }
}
