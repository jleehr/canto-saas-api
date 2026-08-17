<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http;

use Fairway\CantoSaasApi\Client;
use Fairway\CantoSaasApi\Http\InvalidRequestException;
use Fairway\CantoSaasApi\Tests\Http\Fixture\ConfigurableRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    #[Test]
    public function emptyApiPathIsAccepted(): void
    {
        $request = new ConfigurableRequest('', ['image', 'content-id-1234']);

        self::assertSame(
            'https://test.canto.com/api/v1/image/content-id-1234',
            (string)$request->toHttpRequest($this->buildClientMock())->getUri()
        );
    }

    #[Test]
    public function singleSegmentApiPathIsKeptUnchanged(): void
    {
        $request = new ConfigurableRequest('image');

        self::assertSame(
            'https://test.canto.com/api/v1/image',
            (string)$request->toHttpRequest($this->buildClientMock())->getUri()
        );
    }

    /**
     * The slash carries meaning in an api path, so multi-segment paths must
     * survive the guard untouched.
     */
    #[Test]
    #[DataProvider('literalApiPathProvider')]
    public function literalApiPathIsKeptUnchanged(string $apiPath): void
    {
        $request = new ConfigurableRequest($apiPath);

        self::assertSame(
            'https://test.canto.com/api/v1/' . $apiPath,
            (string)$request->toHttpRequest($this->buildClientMock())->getUri()
        );
    }

    /**
     * @return array<string, array{string}>
     */
    public static function literalApiPathProvider(): array
    {
        return [
            'batch/album' => ['batch/album'],
            'info/folder' => ['info/folder'],
            'version/comment' => ['version/comment'],
            'upload/setting' => ['upload/setting'],
            'token' => ['token'],
            'mycollections' => ['mycollections'],
            'unreserved characters' => ['a-b_c.d~e'],
            'uppercase segment' => ['ALBUM'],
            'mixed case segments' => ['info/Folder'],
            'dots inside a segment' => ['a..b'],
            'three dots' => ['...'],
        ];
    }

    /**
     * Security: the api path is inserted into the url unencoded, so a value
     * that leaves its segment must not reach the http request.
     */
    #[Test]
    #[DataProvider('invalidApiPathProvider')]
    public function invalidApiPathThrows(string $apiPath): void
    {
        $request = new ConfigurableRequest($apiPath);

        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionCode(1786924800);

        $request->toHttpRequest($this->buildClientMock());
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidApiPathProvider(): array
    {
        return [
            'traversal in the middle' => ['image/../../batch/delete'],
            'leading traversal' => ['../album/ALBUMID'],
            'trailing traversal' => ['album/..'],
            'backslash separator' => ['image\..\..\batch'],
            'full width solidus separator' => ["image\u{FF0F}..\u{FF0F}batch"],
            'parent segment only' => ['..'],
            'current segment only' => ['.'],
            'current segment in the middle' => ['info/./folder'],
            'query delimiter' => ['image?evil=1'],
            'fragment delimiter' => ['image#frag'],
            'encoded traversal' => ['%2e%2e%2fbatch'],
            'leading slash' => ['/tree'],
            'trailing slash' => ['tree/'],
            'empty segment' => ['batch//album'],
            'line break' => ["image\nX-Evil: 1"],
            'null byte' => ["image\0"],
            'space' => ['batch album'],
            'colon and slashes' => ['https://evil.example.com/x'],
        ];
    }

    /**
     * Security: the rejected segment belongs into the message, so the cause is
     * visible without guessing.
     */
    #[Test]
    public function invalidApiPathExceptionNamesTheOffendingSegment(): void
    {
        $request = new ConfigurableRequest('image/../../batch/delete');

        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('".."');

        $request->toHttpRequest($this->buildClientMock());
    }

    /**
     * Security: the message quotes a caller-controlled value, so an oversized
     * value must not be able to flood the log of the consuming application.
     */
    #[Test]
    public function invalidApiPathExceptionTruncatesAnOversizedSegment(): void
    {
        $request = new ConfigurableRequest(str_repeat('a', 5000) . '?evil=1');

        try {
            $request->toHttpRequest($this->buildClientMock());
            self::fail('Expected InvalidRequestException was not thrown.');
        } catch (InvalidRequestException $exception) {
            self::assertStringContainsString('... (truncated)', $exception->getMessage());
            self::assertLessThan(200, strlen($exception->getMessage()));
        }
    }

    /**
     * Security: the rejected value is quoted in the message and the message
     * usually ends up in a log, so line breaks and quotes must not survive it.
     */
    #[Test]
    public function invalidApiPathExceptionEncodesLineBreaksAndQuotes(): void
    {
        $request = new ConfigurableRequest("image\r\nX-Evil: 1 \"quoted\"");

        try {
            $request->toHttpRequest($this->buildClientMock());
            self::fail('Expected InvalidRequestException was not thrown.');
        } catch (InvalidRequestException $exception) {
            $message = $exception->getMessage();
            self::assertStringNotContainsString("\r", $message);
            self::assertStringNotContainsString("\n", $message);
            self::assertStringContainsString('%0D%0A', $message);
            self::assertStringContainsString('%22quoted%22', $message);
            // Only the two quotes of the message template itself are left.
            self::assertSame(2, substr_count($message, '"'));
        }
    }

    /**
     * Security: the value is shortened before it is encoded, so a cut inside a
     * multi-byte character must not leave a dangling percent sign that breaks
     * the encoding of the message.
     */
    #[Test]
    public function invalidPathVariableExceptionTruncatesWithoutBreakingAPercentSequence(): void
    {
        // The offset of the cut is odd, so it falls inside a two-byte "ä".
        $request = new ConfigurableRequest('image', ['a' . str_repeat('ä', 200) . '/../evil']);

        try {
            $request->toHttpRequest($this->buildClientMock());
            self::fail('Expected InvalidRequestException was not thrown.');
        } catch (InvalidRequestException $exception) {
            $message = $exception->getMessage();
            self::assertStringContainsString('... (truncated)', $message);
            self::assertLessThan(400, strlen($message));
            self::assertDoesNotMatchRegularExpression('/%(?![0-9A-Fa-f]{2})/', $message);
        }
    }

    /**
     * Security: a path variable that leaves its segment is rejected, because
     * encoding alone does not stop a server that decodes the url before it
     * resolves the path.
     */
    #[Test]
    #[DataProvider('dotSegmentPathVariableProvider')]
    public function dotSegmentPathVariableThrows(string $pathVariable): void
    {
        $request = new ConfigurableRequest('', ['image', $pathVariable]);

        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionCode(1786924801);

        $request->toHttpRequest($this->buildClientMock());
    }

    /**
     * @return array<string, array{string}>
     */
    public static function dotSegmentPathVariableProvider(): array
    {
        return [
            'parent segment' => ['..'],
            'current segment' => ['.'],
            'parent segment with surrounding whitespace' => [" ..\r\n"],
            'traversal with query and fragment' => ['../../evil?x=1#y'],
            'traversal in the middle' => ['image/../../batch/delete'],
            'trailing traversal' => ['album/..'],
            'current segment in the middle' => ['album/./name'],
            'backslash separator' => ['image\..\..\batch'],
            'encoded traversal' => ['%2e%2e%2fbatch'],
            'encoded backslash separator' => ['%5c..%5cbatch'],
            'double encoded traversal' => ['%252e%252e%252fbatch'],
            'triple encoded traversal' => ['%25252e%25252e%25252f'],
            'encoded traversal with padding' => ['%20%2e%2e%20'],
        ];
    }

    /**
     * Security: a control character or invalid utf-8 survives the encoding as a
     * percent sequence and can turn back into a traversal on the server — a
     * truncating parser cuts "..%00x" at the null byte, and an overlong "%C0%AE"
     * decodes to "." in a lenient utf-8 decoder.
     */
    #[Test]
    #[DataProvider('malformedPathVariableProvider')]
    public function malformedPathVariableThrows(string $pathVariable): void
    {
        $request = new ConfigurableRequest('', ['image', $pathVariable]);

        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionCode(1786924801);

        $request->toHttpRequest($this->buildClientMock());
    }

    /**
     * @return array<string, array{string}>
     */
    public static function malformedPathVariableProvider(): array
    {
        return [
            'null byte' => ["..\0x"],
            'line break' => ["image\r\nX-Evil: 1"],
            'escape character' => ["image\x1bx"],
            'delete character' => ["image\x7fx"],
            'overlong utf-8 traversal' => ["\xC0\xAE\xC0\xAE\xC0\xAF"],
            'truncated utf-8 sequence' => ["image\xC3"],
            'invalid utf-8 byte' => ["image\xFFx"],
        ];
    }

    /**
     * Security: path variables are opaque values, so everything but a dot
     * segment stays encoded instead of being rejected.
     */
    #[Test]
    public function pathVariablesAreStillUrlEncoded(): void
    {
        $request = new ConfigurableRequest('image', ['evil?x=1#y/z']);

        self::assertSame(
            'https://test.canto.com/api/v1/image/evil%3Fx%3D1%23y%2Fz',
            (string)$request->toHttpRequest($this->buildClientMock())->getUri()
        );
    }

    /**
     * Security: only a whole dot segment traverses the path. Dots inside a
     * segment are part of ordinary identifiers and must stay usable.
     */
    #[Test]
    #[DataProvider('dottedPathVariableProvider')]
    public function pathVariableWithDotsInsideASegmentIsAccepted(string $pathVariable, string $expected): void
    {
        $request = new ConfigurableRequest('image', [$pathVariable]);

        self::assertSame(
            'https://test.canto.com/api/v1/image/' . $expected,
            (string)$request->toHttpRequest($this->buildClientMock())->getUri()
        );
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function dottedPathVariableProvider(): array
    {
        return [
            'leading dots' => ['..foo', '..foo'],
            'trailing dots' => ['foo..', 'foo..'],
            'dots inside' => ['file..name', 'file..name'],
            'three dots' => ['...', '...'],
            'file name with extension' => ['image.name.jpg', 'image.name.jpg'],
        ];
    }

    /**
     * A path variable is encoded with rawurlencode() semantics, so a space
     * becomes "%20" instead of "+", and valid utf-8 stays usable: path
     * variables carry free text such as keywords and file names.
     */
    #[Test]
    #[DataProvider('encodedPathVariableProvider')]
    public function pathVariableIsEncodedWithRawUrlEncodeSemantics(string $pathVariable, string $expected): void
    {
        $request = new ConfigurableRequest('image', [$pathVariable]);

        self::assertSame(
            'https://test.canto.com/api/v1/image/' . $expected,
            (string)$request->toHttpRequest($this->buildClientMock())->getUri()
        );
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function encodedPathVariableProvider(): array
    {
        return [
            'value with space' => ['a b', 'a%20b'],
            'value with plus sign' => ['a+b', 'a%2Bb'],
            'file name with umlaut' => ['Straßenfoto.jpg', 'Stra%C3%9Fenfoto.jpg'],
        ];
    }

    #[Test]
    public function emptyPathVariableIsStillAccepted(): void
    {
        $request = new ConfigurableRequest('folder', ['', 'album-name']);

        self::assertSame(
            'https://test.canto.com/api/v1/folder//album-name',
            (string)$request->toHttpRequest($this->buildClientMock())->getUri()
        );
    }

    #[Test]
    public function queryParamsAreStillAppended(): void
    {
        $request = new ConfigurableRequest('batch/album', null, ['description' => 'a b']);

        self::assertSame(
            'https://test.canto.com/api/v1/batch/album?description=a+b',
            (string)$request->toHttpRequest($this->buildClientMock())->getUri()
        );
    }

    private function buildClientMock(): Client
    {
        $clientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getApiUrl'])
            ->getMock();
        $clientMock->method('getApiUrl')->willReturnCallback(
            static fn (?string $path = null): string => 'https://test.canto.com/api/v1/' . ($path ?? '')
        );

        return $clientMock;
    }
}
