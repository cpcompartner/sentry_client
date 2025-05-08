<?php

namespace Networkteam\SentryClient\Content;

use Networkteam\SentryClient\Client;
use Networkteam\SentryClient\Event\BeforeSentryCaptureEvent;
use Networkteam\SentryClient\Service\ConfigurationService;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;

class ProductionExceptionHandler extends \TYPO3\CMS\Frontend\ContentObject\Exception\ProductionExceptionHandler
{

    /**
     * Handles exceptions thrown during rendering of content objects
     * The handler can decide whether to re-throw the exception or
     * return a nice error message for production context.
     */
    public function handle(
        \Exception $exception,
        AbstractContentObject $contentObject = null,
        $contentObjectConfiguration = []
    ): string {
        if (Environment::getContext()->isDevelopment()) {
            throw $exception;
        }

        $eventDispatcher = GeneralUtility::makeInstance(EventDispatcherInterface::class);

        $event = $eventDispatcher->dispatch(
            new BeforeSentryCaptureEvent($exception)
        );

        if (!$event->isPropagationStopped()) {
            $exceptionToSend = $event->getException();
            $eventId = GeneralUtility::makeInstance(Client::class)->captureException($exceptionToSend);
        }

        $errorMessage = parent::handle($exception, $contentObject, $contentObjectConfiguration);

        if (ConfigurationService::showEventId()) {
            return sprintf('%s Event: %s', $errorMessage, $eventId);
        }
        return $errorMessage;
    }
}
