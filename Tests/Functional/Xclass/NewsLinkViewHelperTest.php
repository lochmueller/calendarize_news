<?php

declare(strict_types=1);

namespace HDNET\CalendarizeNews\Tests\Functional\Xclass;

use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Core\View\ViewInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Minimum version TYPO3 v13 because of ViewFactoryInterface
 * @see https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.3/Feature-104773-GenericViewFactory.html
 */
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


    protected function generateRequest(): ServerRequest
    {
        // >= v13 requires initializing request for Standalone view
        $request = new ServerRequest('https://typo3-testing.local/typo3/');
        $request = $request->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);

        // need to initialize ConfigurationManager with requests because news LinkViewHelper.php uses Extbase functionality
        //$configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $configurationManager = $this->get(ConfigurationManager::class);
        $configurationManager->setRequest($request);
        return $request;
    }

    protected function setupView(): ViewInterface
    {
        $viewFactory = $this->get(ViewFactoryInterface::class);
        $viewData = new ViewFactoryData(
            templateRootPaths: ['EXT:calendarize_news/Tests/Functional/Xclass/Fixtures/Extensions/my_extension/Resources/Private/Templates/'],
            request: $this->generateRequest()
        );

        return $viewFactory->create($viewData);
    }

    public function testViewHelperRender(): void
    {
        $view = $this->setupView();
        $view->assign('news', null);

        self::assertEquals('MyLink', rtrim($view->render('TestViewhelper'), "\n"));
    }

    public function testXclassLoadedAndIndexArgumentIsAccepted(): void
    {
        $view = $this->setupView();
        $view->assign('news', null);
        $view->assign('index', null);

        self::assertEquals('MyLink', rtrim($view->render('TestViewhelper'), "\n"));
    }

    public function testIndexId(): void
    {
        $view = $this->setupView();
        $view->assign('news', null);
        $view->assign('index', 22);

        self::assertEquals('MyLink', rtrim($view->render('TestViewhelper'), "\n"));
    }
}
