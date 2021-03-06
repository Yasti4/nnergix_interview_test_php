<?php

declare(strict_types=1);

namespace AML\Tests\Unit\Application\Command;

use AML\Application\Command\ProcessPageCommand;
use AML\Domain\Exception\InvalidSearchDeepException;
use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\ValueObject\PageReference;
use AML\Domain\ValueObject\SearchDeep;
use AML\Domain\ValueObject\SearchUrl;
use PHPUnit\Framework\TestCase;

class ProcessPageCommandTest extends TestCase
{
    /** @var SearchUrl $url */
    private $url;
    /** @var PageReference $reference */
    private $reference;


    protected function setUp()
    {
        parent::setUp();
        $this->url = new SearchUrl('https://simplehtmldom.sourceforge.io/');
        $this->reference = PageReference::fromString('8697746F-F303-44DB-9140-A207778E0818');

    }

    public function test_values_a_command()
    {
        $processPageCommand = new ProcessPageCommand(
            $this->url->value(),
            2,
            $this->reference->toString()
        );

        $this->assertEquals($processPageCommand->url()->value(), $this->url);
        $this->assertEquals($processPageCommand->deep()->value(), 2);
        $this->assertEquals($processPageCommand->pageReference()->toString(), $this->reference);
    }

    public function test_throw_when_incorrect_url()
    {
        $this->expectException(InvalidSearchUrlException::class);
        new ProcessPageCommand(
            'simplehtmldom.sourceforge.io',
            2,
            $this->reference->toString()
        );
    }

    public function test_throw_when_incorrect__negative_deep()
    {
        $this->expectException(InvalidSearchDeepException::class);
        new ProcessPageCommand(
            $this->url->value(),
            -1,
            $this->reference->toString()
        );
    }

    public function test_throw_when_incorrect_greater_than_limit_deep()
    {
        $this->expectException(InvalidSearchDeepException::class);
        new ProcessPageCommand(
            $this->url->value(),
            SearchDeep::LIMIT + 1,
            $this->reference->toString()
        );
    }

}
