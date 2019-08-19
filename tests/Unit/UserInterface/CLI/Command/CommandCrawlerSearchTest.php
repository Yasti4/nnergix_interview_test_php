<?php

declare(strict_types=1);

namespace AML\Tests\Unit\UserInterface\CLI\Command;

use AML\Application\Service\CrawlerSearchService;
use AML\Domain\Repository\InfoUrlRepository;
use AML\Domain\Repository\SearchUrlRepository;
use AML\Domain\Service\ProcessPage;
use AML\Domain\ValueObject\Page;
use AML\Domain\ValueObject\PageProcessed;
use AML\Domain\ValueObject\SearchDeep;
use AML\Domain\ValueObject\SearchUrl;
use AML\Domain\ValueObject\SearchUrlCollection;
use AML\Domain\ValueObject\SearchUrlHeaderCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandCrawlerSearchTest extends TestCase
{

    /** @var MockObject|CrawlerSearchService */
    private $crawlerSearchService;
    /** @var MockObject|InputInterface */
    private $input;
    /** @var MockObject|OutputInterface */
    private $ouput;
    /** @var SearchUrl */
    private $searchUrl;

    protected function setUp()
    {
        $this->crawlerSearchService = $this->createMock(CrawlerSearchService::class);
        $this->input = $this->createMock(InputInterface::class);
        $this->ouput = $this->createMock(OutputInterface::class);

        $this->searchUrl = new SearchUrl('https://simplehtmldom.sourceforge.io/');
        parent::setUp();
    }

    public function test_should_command_named_correctly()
    {
        $headers = new SearchUrlHeaderCollection();
//        $searchDeep = new SearchDeep(2);

        $page = new Page($this->searchUrl, $headers, null);
        $pageProcessed = new PageProcessed($page, new SearchUrlCollection(), new SearchUrlCollection());
        $this->crawlerSearchService->method('__invoke')->willReturnCallback($pageProcessed);

        $this->assertTrue($pageProcessed->page()->url()->equals($this->searchUrl));
        $this->assertCount(0, $pageProcessed->page()->headers());
        $this->assertNotNull($pageProcessed->page()->reference());
    }

}
