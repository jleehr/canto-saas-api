<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http;

/**
 * Guards the two caller-controlled parts of a request url: the api path of a
 * request and its path variables. Both url builders that assemble an api path,
 * Request::buildRequestUrl() and OAuth2::buildRequestUrl(), go through this
 * class, so the rules exist exactly once instead of being copied per builder;
 * UploadRequest::toHttpRequest() is not covered, because it sends its request
 * to the absolute upload url handed out by the api instead of building a path.
 *
 * Known limit: unicode lookalikes of "/" and "." (U+FF0F, U+FF0E, U+2215,
 * U+2044, U+2024) pass a path variable unchanged. They only turn into "../" if
 * the server normalizes them (NFKC) before it resolves the path, and whether
 * the Canto server does so is unknown. Rejecting non-ASCII input as a whole
 * would break legitimate values instead, because path variables carry free text
 * such as keywords and file names.
 *
 * @internal
 */
final class ApiPathValidator
{
    /**
     * Allowlist for a single segment of the api path. It covers the literal
     * paths used by this package (e.g. "batch/album", "info/folder",
     * "upload/setting") and the unreserved characters of RFC 3986. Everything
     * else — most notably "/", "\", "?", "#", "%", non-ASCII characters and
     * control characters — is rejected. "\z" instead of "$" so a trailing line
     * break cannot pass.
     */
    private const API_PATH_SEGMENT_PATTERN = '/^[A-Za-z0-9._~-]+\z/';

    /**
     * Control characters in a path variable. Encoding keeps them in the url as
     * a percent sequence, where a parser that truncates at a null byte or
     * splits at a line break sees a different path than this guard did.
     */
    private const CONTROL_CHARACTER_PATTERN = '/[\x00-\x1F\x7F]/';

    /**
     * A dot segment ("." or "..") anywhere in a path variable. The backslash
     * counts as a separator too, because some server stacks normalize it to
     * "/" before they resolve the path.
     */
    private const DOT_SEGMENT_PATTERN = '~(?:^|[/\\\\])\.\.?(?:[/\\\\]|\z)~';

    /**
     * Upper bound for the repeated decoding of a path variable. Decoding
     * shortens the value, so the loop ends on its own; the bound rules out an
     * endless loop for good and rejects a value that still changes afterwards,
     * because its final form has not been inspected.
     */
    private const MAX_DECODE_PASSES = 10;

    /**
     * A rejected value is quoted in the exception message. It is
     * caller-controlled and therefore unbounded in length, so only a prefix is
     * reported: a multi-megabyte value must not blow up the log of the
     * consuming application. The bound counts bytes, not characters.
     */
    private const MAX_REPORTED_LENGTH = 100;

    private function __construct()
    {
    }

    /**
     * Validates the api path. The slash is meaningful here (e.g.
     * "batch/album"), so the path is split into segments and every segment is
     * checked against the allowlist instead of being encoded as a whole. An
     * empty path is valid: several requests address the api root and add the
     * whole path via path variables.
     *
     * @throws InvalidRequestException
     */
    public static function validateApiPath(string $apiPath): string
    {
        if ($apiPath === '') {
            return $apiPath;
        }

        foreach (explode('/', $apiPath) as $segment) {
            if (
                $segment === '.'
                || $segment === '..'
                || preg_match(self::API_PATH_SEGMENT_PATTERN, $segment) !== 1
            ) {
                throw new InvalidRequestException(
                    sprintf('Invalid api path segment "%s".', self::describeRejectedValue($segment)),
                    1786924800
                );
            }
        }

        return $apiPath;
    }

    /**
     * A path variable is an opaque value, so encoding it as a whole already
     * neutralizes "/", "?" and "#". Three kinds of value are rejected instead
     * of being encoded, because encoding leaves them byte-identical and a
     * server that decodes the url before it resolves the path would still act
     * on them: a value that traverses the path, a value with a control
     * character, and a value that is not valid utf-8 — an overlong sequence
     * such as "\xC0\xAE" decodes to "." in a lenient decoder. Dots inside a
     * segment ("file..name", "..foo") stay valid.
     *
     * @throws InvalidRequestException
     */
    public static function validateAndEncodePathVariable(mixed $pathVariable): string
    {
        $pathVariable = (string)$pathVariable;
        if (
            preg_match(self::CONTROL_CHARACTER_PATTERN, $pathVariable) !== 0
            || preg_match('//u', $pathVariable) !== 1
            || self::decodesToDotSegment($pathVariable)
        ) {
            throw new InvalidRequestException(
                sprintf('Invalid path variable "%s".', self::describeRejectedValue($pathVariable)),
                1786924801
            );
        }

        return rawurlencode($pathVariable);
    }

    /**
     * Percent decodes the value until it no longer changes and looks for a dot
     * segment in every intermediate form, so neither a single ("%2e%2e%2f")
     * nor a repeated encoding ("%252e%252e%252f") slips through.
     */
    private static function decodesToDotSegment(string $value): bool
    {
        $candidate = trim($value);
        for ($pass = 0; $pass < self::MAX_DECODE_PASSES; $pass++) {
            if (self::containsDotSegment($candidate)) {
                return true;
            }

            $decoded = trim(rawurldecode($candidate));
            if ($decoded === $candidate) {
                return false;
            }
            $candidate = $decoded;
        }

        return self::containsDotSegment($candidate)
            || trim(rawurldecode($candidate)) !== $candidate;
    }

    /**
     * Fail closed: a preg_match() error returns false and must not count as a
     * clean value.
     */
    private static function containsDotSegment(string $candidate): bool
    {
        return preg_match(self::DOT_SEGMENT_PATTERN, $candidate) !== 0;
    }

    /**
     * The value is encoded, so a rejected value cannot inject line breaks or
     * quotes into the log of the consuming application. It is shortened before
     * the encoding, which bounds the reported input rather than its inflation:
     * a non-ASCII byte still grows to three characters. Cutting bytes cannot
     * break the encoding, because every byte is encoded on its own.
     */
    private static function describeRejectedValue(string $value): string
    {
        if (strlen($value) <= self::MAX_REPORTED_LENGTH) {
            return rawurlencode($value);
        }

        return rawurlencode(substr($value, 0, self::MAX_REPORTED_LENGTH)) . '... (truncated)';
    }
}
