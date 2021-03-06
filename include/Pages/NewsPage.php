<?php
namespace ScummVM\Pages;

use ScummVM\Controller;
use ScummVM\Models\NewsModel;
use ScummVM\Models\ScreenshotsModel;

class NewsPage extends Controller
{


    /* Constructor. */
    public function __construct()
    {
        parent::__construct();
        $this->template = 'pages/news.tpl';
    }

    /* Display the index page. */
    public function index($args)
    {
        $filename = $args['date'];

        if ($filename != null) {
            if (strtolower($filename) == 'archive' || $filename == '') {
                $filename = null;
            }
            return $this->getNews($filename);
        }
        return $this->getNewsIntro();
    }

    /* Display a specific news item, or all news items. */
    public function getNews($filename = null)
    {
        if ($filename == null) {
            $news_items = NewsModel::getAllNews();
            $filename = 'archive';
        } else {
            $news_items = array(NewsModel::getOneByFilename($filename));
        }

        return $this->renderPage(
            array(
                'title' => $this->getConfigVars('newsTitle'),
                'content_title' => $this->getConfigVars('newsContentTitle'),
                'show_intro' => false,
                'news_items' => $news_items,
                'news_archive_link' => false,
            )
        );
    }

    /* Display the main page with limited news items and intro text. */
    public function getNewsIntro()
    {
        $news_items = NewsModel::getLatestNews(NEWS_ITEMS);
        $random_shot = ScreenshotsModel::getRandomScreenshot();

        $this->addJSFiles(
            array(
            'baguetteBox.min.js'
            )
        );

        return $this->renderPage(
            array(
                'title' => $this->getConfigVars('newsTitle'),
                'content_title' => $this->getConfigVars('newsContentTitle'),
                'show_intro' => true,
                'news_items' => $news_items,
                'news_archive_link' => true,
                'random_shot' => $random_shot,
            )
        );
    }
}
