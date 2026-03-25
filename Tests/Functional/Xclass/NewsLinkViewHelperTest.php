<?php

declare(strict_types=1);

namespace HDNET\CalendarizeNews\Tests\Functional\Xclass;

use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class NewsLinkViewHelperTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = [
        'extbase',
        'fluid',
    ];

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/news',
        'typo3conf/ext/calendarize',
        'typo3conf/ext/calendarize_news',
    ];

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
