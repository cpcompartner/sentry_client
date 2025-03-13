<?php
declare(strict_types = 1);
namespace Networkteam\SentryClient\Service;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\ServerRequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ConfigurationService
{
    const DSN = 'dsn';

    const REPORT_USER_INFORMATION = 'reportUserInformation';

    const USER_INFORMATION_NONE = 'none';

    const IGNORE_MESSAGE_REGEX = 'ignoreMessageRegex';

    const REPORT_DATABASE_CONNECTION_ERRORS = 'reportDatabaseConnectionErrors';

    const SHOW_EVENT_ID = 'showEventId';

    const LOGWRITER_COMPONENT_IGNORELIST = 'logWriterComponentIgnorelist';

    const DISABLE_DATABASE_LOG = 'disableDatabaseLogging';

    protected static function getExtensionConfiguration(string $path): mixed
    {
        return $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['sentry_client'][$path] ?? null;
    }

    public static function getExtConf(): ?array
    {
        $extConf = self::getSentrySiteConfiguration('extConf') ?:
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sentry_client'] ?? null;

        return !empty($extConf) ? $extConf : null;
    }

    public static function getDsn(): ?string
    {
        $dsn = self::getSentrySiteConfiguration(self::DSN) ?:
            getenv('SENTRY_DSN') ?:
                self::getExtensionConfiguration(self::DSN);

        return !empty($dsn) ? $dsn : null;
    }

    public static function getEnvironment(): ?string
    {
        $environment = self::getSentrySiteConfiguration('environment') ?:
            getenv('SENTRY_ENVIRONMENT') ?:
                self::getNormalizedApplicationContext();

        return !empty($environment) ? $environment : null;
    }

    public static function getRelease(): ?string
    {
        $release = self::getSentrySiteConfiguration('release') ?:
            getenv('SENTRY_RELEASE') ?:
                self::getExtensionConfiguration('release');

        return !empty($release) ? $release : null;
    }

    protected static function getNormalizedApplicationContext(): string
    {
        return preg_replace("/[^a-zA-Z0-9]/", "-", Environment::getContext()->__toString());
    }

    public static function getReportUserInformation(): ?string
    {
        $reportUserInformation = self::getSentrySiteConfiguration(self::REPORT_USER_INFORMATION) ?:
            self::getExtensionConfiguration(self::REPORT_USER_INFORMATION);

        return !empty($reportUserInformation) ? $reportUserInformation : null;
    }

    public static function getIgnoreMessageRegex(): ?string
    {
        $ignoreMessageRegex = self::getSentrySiteConfiguration(self::IGNORE_MESSAGE_REGEX) ?:
            self::getExtensionConfiguration('messageBlacklistRegex') ??
            self::getExtensionConfiguration(self::IGNORE_MESSAGE_REGEX);

        return !empty($ignoreMessageRegex) ? $ignoreMessageRegex : null;
    }

    public static function reportDatabaseConnectionErrors(): bool
    {
        return self::getSentrySiteConfiguration(self::REPORT_DATABASE_CONNECTION_ERRORS) ?:
            (bool)self::getExtensionConfiguration(self::REPORT_DATABASE_CONNECTION_ERRORS);
    }

    public static function showEventId(): bool
    {
        return self::getSentrySiteConfiguration(self::SHOW_EVENT_ID) ?:
            (bool)self::getExtensionConfiguration(self::SHOW_EVENT_ID);
    }

    /**
     * @return string[]
     */
    public static function getLogWriterComponentIgnorelist(): array
    {
        $ignoreList = self::getSentrySiteConfiguration(self::LOGWRITER_COMPONENT_IGNORELIST) ?:
            self::getExtensionConfiguration('logWriterComponentBlacklist') ??
            self::getExtensionConfiguration(self::LOGWRITER_COMPONENT_IGNORELIST);

        return !empty($ignoreList) ? GeneralUtility::trimExplode(',', $ignoreList, true) : [];
    }

    public static function shouldDisableDatabaseLogging(): bool
    {
        return self::getSentrySiteConfiguration(self::DISABLE_DATABASE_LOG) ?:
            (bool)self::getExtensionConfiguration(self::DISABLE_DATABASE_LOG);
    }

    private static function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'] ?? $GLOBALS['_REQUEST'];
    }

    protected static function getSentrySiteConfiguration(string $key = ''): mixed
    {
        if ($key === '') {
            return null;
        }

        $request = self::getRequest();
        return $request->getAttribute(self::appendSentryAndUpperFirstCharOfOriginal($key));
    }

    // This function matches the siteConfiguration naming of variables
    protected static function appendSentryAndUpperFirstCharOfOriginal($string): string
    {
        if (!empty($string)) {
            $firstChar = mb_strtoupper(mb_substr($string, 0, 1));
            $restOfString = mb_substr($string, 1);
            $string = $firstChar . $restOfString;
        }

        return "sentry" . $string;
    }
}
