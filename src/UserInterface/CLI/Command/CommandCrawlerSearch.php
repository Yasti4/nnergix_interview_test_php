<?php

namespace App\UserInterface\CLI\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class CommandCrawlerSearch extends Command
{
    const DEFAULT_ITERATIONS = 2;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->setName('crawler:search')
            ->setDescription('Execute scan web')
            ->addArgument('url', InputArgument::REQUIRED, 'The url to search.')
            ->addArgument('deep', InputArgument::OPTIONAL, 'Iterations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // $deep = $input->getArgument('deep');

            // if (!$deep) {
            //     $deep = $this::DEFAULT_ITERATIONS;
            // }
            // $output->writeln($input->getArgument('url'));
            // $output->writeln($deep);


            $url = $input->getArgument('url');
            try {
                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    throw new \InvalidArgumentException('Invalid Url');
                }
                $httpClient = HttpClient::create();
                $response = $httpClient->request('GET', $url);
                if ($response->getStatusCode() !== 200) {
                    throw new \Exception('Page Error');
                }

                $html = $response->getContent();
                $crawler = new Crawler($html, $url);
                // die(var_dump($response->getHeaders()));
                // $metas = $crawler->filter('head meta');
                // $scripts = $crawler->filter('head script');

                $links = $crawler->filter('a')->each(function (Crawler $node, $i) {
                    $url =  trim($node->link()->getUri());
                    if (
                        ((strpos($url, "http://") === 0 || strpos($url, "https://") === 0)) &&
                        filter_var($url, FILTER_VALIDATE_URL)) {
                        // echo $url .PHP_EOL;
                        return $url;
                    }
                });
                $links = array_filter($links, function ($link) {
                    return $link;
                });


                $internalLinks = array_filter($links, function ($link) use ($url) {
                    if (preg_match("*($url)*", $link)) {
                        return $link;
                    }
                });
                $externalLinks = array_filter($links, function ($link) use ($url) {
                    if (!preg_match("*($url)*", $link)) {
                        return $link;
                    }
                });
                // die(var_dump($externalLinks));

                // $output->writeln('Externar Links:'.PHP_EOL.implode(PHP_EOL, $externalLinks));
                // $output->writeln(PHP_EOL);
                // $output->writeln('Internal Links:'.PHP_EOL.implode(PHP_EOL, $internalLinks));
                foreach ($links as $link) {
                    if ($url === $link) {
                        continue;
                    }
                    $httpClient->request('GET', $link);
                    echo $link . '=>'. $response->getStatusCode().PHP_EOL;
                }
            } catch (\Exception $e) {
                die($e->getMessage());
            }

            return 0;
        } catch (\Throwable $e) {
            $this->logger->error('Unknown error while consuming and/or dispatching a command.', [$e]);

            return 1;
        }
    }
}
