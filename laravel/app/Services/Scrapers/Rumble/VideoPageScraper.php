<?php

namespace App\Services\Scrapers\Rumble;

class VideoPageScraper
{
    private $url;

    public function __construct(string $url)
    {
        if ($this->urlIsValid($url))
        {
            $this->url = $url;
        }
        else
        {
            throw new \ValueError('Expected a rumble video URL. Input was: ' . $url);
        }
    }

    public function url()
    {
        return $this->url;
    }

    private function urlIsValid(string $url)
    {
        /**
         * URL is valid if:
         * 
         * it contains no more than 107 characters
         * 
         * it starts with "https://rumble.com/v
         * 
         * it ends in ".html"
         * 
         * URI (whatever is after "https://rumble.com/") is composed of at least 2 strings separated by "-"
         * 
         * the first string in URI starts with the character "v" followed by 6 characters in the range [a-z][0-9]
         * 
         * every string contains characters in the range [a-z][0-9], as well as the character "."
         */
        return true;
    }



    public function video()
    {
        // title
        // thumbnail
        // src
        // duration
        return;

    }

    private function id(string $url): string
    {
        $rumbleBaseUrl = 'https://rumble.com/';
        $path = str_replace($rumbleBaseUrl, '', $url);

        return str_replace('.html', '', $path);
    }

    private function source(string $url)
    {    
        $data = [];
        $browserFactory = new BrowserFactory();
        $browser = $browserFactory->createBrowser();
        $page = $browser->createPage();
        $page->navigate($url)->waitForNavigation();
        $videoDiv = $page->dom()->querySelector('video');

        $getVideoDurationFunc = <<<SCRIPT
            function ()
            {
                return document.querySelector('video').duration;
            }
        SCRIPT;

        $duration = $page->callFunction($getVideoDurationFunc)->getReturnValue();

        $data = [
            'src' => $videoDiv->getAttribute('src'),
            'thumbnail' => $videoDiv->getAttribute('poster'),
            'duration' => $duration
        ];

        $browser->close();

        return $data;
    }

    private function duration(string $url): ?string
    {
        $browserFactory = new BrowserFactory();
        $browser = $browserFactory->createBrowser();
        $page = $browser->createPage();
        $page->navigate($url)->waitForNavigation();
        $videoDiv = $page->dom()->querySelector('#videoPlayer > div > div > video');
        $browser->close();
        return $videoDiv ? $videoDiv->getAttribute('duration') : null;
    }

    private function thumbnail(string $url): ?string
    {
        $browserFactory = new BrowserFactory();
        $browser = $browserFactory->createBrowser();
        $page = $browser->createPage();
        $page->navigate($url)->waitForNavigation();
        $videoDiv = $page->dom()->querySelector('#videoPlayer > div > div > video');
        $browser->close();
        return $videoDiv ? $videoDiv->getAttribute('poster') : null;
    }

    private function channel()
    {
        // id
        $a = $this->scrape('//a[@class="media-by--a"]')->first();
        if ($a)
        {
            $href = $a->getAttribute('href');
        }
        $id = $a ? str_replace('/c/', '', $href) : null;


        // avatar
        $icon = $this->scrape('//a[@class="media-by--a"]/i')->first();
        if ($a)
        {
            $iconClass = $icon->getAttribute('class');
        }
        if ($iconClass)
        {
            preg_match(
                '/user-image--img--id-\w+/', 
                $iconClass, 
                $matches
            );
            $targetClass = $matches[0];
            $style = $this->scrape('//head/style')->first();
            if ($style)
            {
                $css = $style->textContent;
            }
            if ($css)
            {
                preg_match(
                    '/' . preg_quote($targetClass, '/') . '\s*\{[^\}]*background-image:\s*url\((.*?)\);/', 
                    $css, 
                    $matches
                );
            }
        }
        $avatar = $matches[1] ?? null;

        // name
        $div = $this->scrape('//div[@class="media-heading-name"]')->first();
        $name = $div ? $div->textContent : null;

        //verified
        $verifiedIcons = $this->scrape('//svg[@class="verification-badge-icon media-heading-verified"]')
                              ->all();
        $verified = ($verifiedIcons->length > 0) ? true : false;

        //followersCount
        $div = $this->scrape('//div[@class="media-heading-num-followers"]')->first();
        $followersCount = $div ? $div->textContent : null;

        return [
            'id' => $id,
            'avatar' => $avatar,
            'name' => $name,
            'verified' => $verified,
            'followersCount' => $followersCount
        ];
    }

    private function uploadedAt()
    {
        $div = $this->scrape('//div[@class="media-published"]')->first();
        if ($div)
        {
            return $div->getAttribute('title');
        }

        $div = $this->scrape('//div[@class="streamed-on"]/time')->first();
        if ($div)
        {
            return Convert::ISO8601ToString(
                $div->getAttribute('datetime')
            );
        }

        return null;
    }

    private function title()
    {
        $h1 = $this->scrape('//h1[@class="h1"]')->first();
        return $h1 ? $h1->textContent : null;
    }

    private function tags()
    {
        $tags = [];

        $anchorTags = $this->scrape('//a[@class="video-category-tag video-category-tag-tag"]')->all();

        if ($anchorTags->length < 1)
        {
            return $tags;
        }

        foreach($anchorTags as $anchorTag)
        {
            $tags[] = $anchorTag->textContent;
        }

        return $tags;
    }

    private function counters()
    {
        $counters = [];

        // likes
        $span = $this->scrape('//span[@class="rumbles-up-votes"]')->first();
        $counters['likes'] = $span ? $span->textContent : null;

        // dislikes
        $span = $this->scrape('//span[@class="rumbles-down-votes"]')->first();
        $counters['dislikes'] = $span ? $span->textContent : null;

        // views
        $div = $this->scrape('//div[@class="video-counters--item video-item--views"]')->first();
        $counters['views'] = $div ? trim($div->textContent) : null;

        // comments
        $div = $this->scrape('//div[@class="video-counters--item video-item--comments"]')->first();
        $counters['comments'] = $div ? trim($div->textContent) : null;

        return $counters;
    }

    private function comments(string $url)
    {
        $browserFactory = new BrowserFactory();
        $browser = $browserFactory->createBrowser();
        $page = $browser->createPage();
        $navigation = $page->navigate($url)->waitForNavigation();

        $css = $page->dom()->querySelector('style')->getHTML();

        $comments = [];
        $elements = $page->dom()->querySelectorAll('ul.comments-1 > li.comment-item');
        foreach($elements as $element)
        {
            $className = $element->getAttribute('class');
            if ('comment-item comments-create' !== $className)
            {
                $html = $element->getHTML();
                $doc = Scraper::createDomDocumentAndLoad($html);
                $xpath = new \DOMXpath($doc);

                // user
                $user = [];
                // username
                $anchorTags = $xpath->query('//a[contains(@class, "comments-meta-author")]');
                $user['username'] = $anchorTags->item(0)->textContent;
                // avatar
                $icon = $xpath->query('//i[contains(@class, "user-image")]')->item(0);
                if ($icon)
                {
                    $iconClass = $icon->getAttribute('class');
                }
                if ($iconClass)
                {
                    // case 1: letter avatar
                    if (strstr($iconClass, 'user-image--letter'))
                    {
                        $user['avatar'] = null;
                    }
                    else
                    {
                        $iconBgImageFunc = <<<SCRIPT
                            function ()
                            {
                                const element = document.querySelector('[class*="$iconClass"]');
                                const style = getComputedStyle(element);

                                return style.getPropertyValue('background-image');
                            }
                        SCRIPT;
                        $bgImage = $page->callFunction($iconBgImageFunc)->getReturnValue();
                        preg_match('/url\("([^"]*)"\)/', $bgImage, $matches);
                        $user['avatar'] = $matches[1];
                    }
                }

                // postedAt
                $a = $xpath->query('//a[@class="comments-meta-post-time"]')->item(0);
                $postedAt = $a->getAttribute('title');

                // isPinned
                $span = $xpath->query('//span[@class="pinned-text"]');
                $isPinned = ($span->length > 0) ? true : false;

                // text
                $p = $xpath->query('//p[@class="comment-text"]')->item(0);
                $text = $p->textContent;

                // counters
                $counters = [];
                // likes count
                $span = $xpath->query('//span[@class="rumbles-count"]')->item(0);
                $counters['likes'] = $span->textContent;
                // replies count
                $button = $xpath->query('//button[@class="comment-toggle-replies"]/text()[normalize-space()]');
                if ($button->length < 1)
                {
                    $counters['replies'] = 0;
                }
                else
                {
                    $tmp = trim($button->item(0)->textContent);
                    $tmp = explode('repl', $tmp);
                    $counters['replies'] = intval($tmp[0]);
                }

                // replies
                $replies = [];
                $elem = $xpath->query('//div[@class="comment-replies"]/ul[@class="comments-2"]/li[contains(@class, "comment-item")]');
                $replies['count'] = $elem->length;

                $comments[] = [
                    'user' => $user,
                    'postedAt' => $postedAt,
                    'isPinned' => $isPinned,
                    'text' => $text,
                    'counters' => $counters,
                    'replies' => $replies,
                ];
            }
        }

        dd($comments);
        $browser->close();
    }

    private function description()
    {
        // short description
        $p = $this->scrape('//p[@class="media-description"]')->first();
        if ($p)
        {
            return $p->textContent;
        }

        // long description
        $p1 = $this->scrape('//p[@class="media-description media-description--first"]/text()[1]')->first();
        $p2 = $this->scrape('//p[@class="media-description media-description--more"]')->first();
        if ($p1 && $p2)
        {
            return $p1->textContent . '\n' . $p2->textContent;
        }

        // no description
        return null;
    }

    private function relatedVideos()
    {
        $relatedVideos = [];

        $anchorTags = $this->scrape('//a[contains(@class, "mediaList-link")]')->all();

        if ($anchorTags->length < 1)
        {
            return $relatedVideos;
        }

        foreach ($anchorTags as $anchorTag)
        {
            $video = [];
            
            // url
            $video['url'] = 'https://rumble.com' . $anchorTag->getAttribute('href');

            // thumbnail
            $img = $this->scrape('.//img[@class="mediaList-image"]', $anchorTag)->first();
            $video['thumbnail'] = $img->getAttribute('src');

            // duration
            $small = $this->scrape('.//small[@class="mediaList-duration"]', $anchorTag)->first();
            $video['duration'] = $small ? $small->textContent : null;

            // channel
            $channel = [];
            // channel avatar
            $icon = $this->scrape('.//i[contains(@class, "user-image user-image--img")]', $anchorTag)->first();
            $iconClass = $icon->getAttribute('class');
            preg_match(
                '/user-image--img--id-\w+/', 
                $iconClass, 
                $matches
            );
            $targetClass = $matches[0];
            $style = $this->scrape('//head/style')->first();
            $css = $style->textContent;
            preg_match(
                '/' . preg_quote($targetClass, '/') . '\s*\{[^\}]*background-image:\s*url\((.*?)\);/', 
                $css, 
                $matches
            );
            $channel['avatar'] = $matches[1];
            // channel name
            $h4 = $this->scrape('.//h4[@class="mediaList-by-heading"]', $anchorTag)->first();
            $channel['name'] = $h4->textContent;
            $video['channel'] = $channel;
            
            // uploaded_at
            $small = $this->scrape('.//small[@class="mediaList-timestamp"]', $anchorTag)->first();
            $video['uploaded_at'] = $small->textContent;

            // title
            $h3 = $this->scrape('.//h3[contains(@class, "mediaList-heading")]', $anchorTag)->first();
            $video['title'] = $h3->getAttribute('title');

            // counters
            $counters = [];
            // views
            $div = $this->scrape('.//div[@class="video-counters--item video-item--views"]', $anchorTag)->first();
            $counters['views'] = $div ? trim($div->textContent) : null;
            // comments
            $div = $this->scrape('.//div[@class="video-counters--item video-item--comments"]', $anchorTag)->first();
            $counters['comments'] = $div ? trim($div->textContent) : null;
            // live
            $small = $this->scrape('.//small[@class="mediaList-liveCount"]', $anchorTag)->first();
            $counters['live'] = $small ? $small->textContent : null; 

            $video['counters'] = $counters;

            $relatedVideos[] = $video;
        }

        return $relatedVideos;
    }
}
