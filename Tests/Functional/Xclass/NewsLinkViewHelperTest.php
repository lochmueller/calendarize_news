<?php

declare(strict_types=1);

namespace HDNET\CalendarizeNews\Tests\Functional\Xclass;

use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class NewsLinkViewHelperTest extends FunctionalTestCase
{
    public function __construct()
    {
        // Initialize inside constructor to support TYPO3 v10
        $this->coreExtensionsToLoad = ['extbase', 'fluid'];
        $this->testExtensionsToLoad = ['typo3conf/ext/news', 'typo3conf/ext/calendarize_news'];
        parent::__construct();
    }

    public function testViewHelperRender(): void
    {
        $view = new StandaloneView();
        $template = '{namespace n=GeorgRinger\News\ViewHelpers}' .
            '<n:link newsItem="{news}">MyLink</n:link>';

        $view->setTemplateSource($template);
        $view->assign('news', null);

        self::assertEquals('MyLink', $view->render());
    }

    public function testXclassLoadedAndIndexArgumentIsAccepted(): void
    {
        $view = new StandaloneView();
        $template = '{namespace n=GeorgRinger\News\ViewHelpers}' .
            '<n:link newsItem="{news}" index="{index}">MyLink</n:link>';

        $view->setTemplateSource($template);
        $view->assign('news', null);
        $view->assign('index', null);

        self::assertEquals('MyLink', $view->render());
    }

    public function testIndexId(): void
    {
        $view = new StandaloneView();
        $template = '{namespace n=GeorgRinger\News\ViewHelpers}' .
            '<n:link newsItem="{news}" index="{index}">MyLink</n:link>';

        $view->setTemplateSource($template);
        $view->assign('news', null);
        $view->assign('index', 22);

        self::assertEquals('MyLink', $view->render());
    }
}
