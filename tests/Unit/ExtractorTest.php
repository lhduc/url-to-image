<?php

namespace Unit;

use App\Extractor;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

class ExtractorTest extends TestCase
{
    private Extractor $extractor;
    private HttpBrowser $mockClient;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->mockClient = $this->getMockBuilder(HttpBrowser::class)->getMock();
        $this->extractor = new Extractor();
        $this->extractor->setClient($this->mockClient);
    }

    /**
     * This tests if given url is not accessible.
     *
     * @return void
     * @throws Exception
     */
    public function testFetchThrowInvalidUrlError()
    {
        $url = 'https://invalid-url';
        $this->mockClient->expects($this->once())
            ->method('request')
            ->with('GET', $url)
            ->willThrowException(new Exception('Invalid URL.'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid URL.');

        $this->extractor->fetch($url);
    }

    /**
     * This tests if there is no table.
     *
     * @throws Exception
     */
    public function testFetchThrowNoTableError()
    {
        $url = 'https://valid-url';
        $this->mockClient->expects($this->once())
            ->method('request')
            ->with('GET', $url)
            ->willReturn(new Crawler(''));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Matching table is unavailable.');

        $this->extractor->fetch($url);
    }

    /**
     * This tests if having a table but not matching numeric condition.
     *
     * @throws Exception
     */
    public function testFetchThrowNoMatchingTableError()
    {
        $url = 'https://valid-url';
        $html = '<table>
                <tr><th>Player</th><th>Achievement</th></tr>
                <tr><td>A</td><td>X</td></tr>
                <tr><td>B</td><td>Y</td></tr>
                <tr><td>C</td><td>Z</td></tr>
            </table>';
        $this->mockClient->expects($this->once())
            ->method('request')
            ->with('GET', $url)
            ->willReturn(new Crawler($html));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Matching table is unavailable.');

        $this->extractor->fetch($url);
    }

    /**
     * @throws Exception
     */
    public function testFetchSuccessWithMultipleTable()
    {
        $url = 'https://valid-url';
        $html = '<table>
                <tr><th>Player</th><th>Achievement</th></tr>
                <tr><td>A</td><td>X</td></tr>
                <tr><td>B</td><td>Y</td></tr>
                <tr><td>C</td><td>Z</td></tr>
            </table>
            <table>
                <tr><th>Player</th><th>Achievement</th></tr>
                <tr><td>A</td><td>7.1 m</td></tr>
                <tr><td>B</td><td>5.2 m</td></tr>
                <tr><td>C</td><td>6.4 m</td></tr>
            </table>';

        $this->mockClient->expects($this->once())
            ->method('request')
            ->with('GET', $url)
            ->willReturn(new Crawler($html));

        $result = $this->extractor->fetch($url);
        $this->assertEquals([7.1, 5.2, 6.4], $result);
    }
}