<?php

declare(strict_types=1);

namespace Timeular\Webhooks\Api;

use Timeular\Http\Exception\BadRequestException;
use Timeular\Http\Exception\NotFoundException;
use Timeular\Http\HttpClientInterface;
use Timeular\Webhooks\Exception\InvalidEventException;
use Timeular\Webhooks\Exception\InvalidUrlException;
use Timeular\Webhooks\Exception\MaximumSubscriptionsReachedException;
use Timeular\Webhooks\Exception\SubscriptionNotFoundException;
use Timeular\Webhooks\Model\Event;
use Timeular\Webhooks\Model\Subscription;

readonly class WebhooksApi
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    /**
     * @see https://developers.timeular.com/#8a39dd40-8282-4d1e-9315-1945c3117321
     */
    public function listAvailableEvents(): array
    {
        $response = $this->httpClient->request(
            'GET',
            'webhooks/event',
        );

        return array_map(static fn(string $event): Event => Event::from($event), $response['events']);
    }

    /**
     * @see https://developers.timeular.com/#f3ed186d-288f-4a7e-9a35-31c849f936c2
     *
     * @throws InvalidEventException
     * @throws InvalidUrlException
     * @throws MaximumSubscriptionsReachedException
     * @throws BadRequestException
     */
    public function subscribe(string $event, string $targetUrl): Subscription
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                'webhooks/subscription',
                [
                    'event' => $event,
                    'target_url' => $targetUrl,
                ],
            );
        } catch (BadRequestException $exception) {
            throw match ($exception->getMessage()) {
                'invalid event provided' => InvalidEventException::fromEvent($event),
                'invalid URL provided' => InvalidUrlException::fromUrl($targetUrl),
                'maximum subscriptions per event exceeded' => MaximumSubscriptionsReachedException::fromEvent($event),
                default => $exception,
            };
        }

        return Subscription::fromArray(
            [
                'id' => $response['id'],
                'event' => $event,
                'target_url' => $targetUrl,
            ],
        );
    }

    /**
     * @see https://developers.timeular.com/#49f4cefd-7e39-437d-b411-469335b6cb15
     *
     * @throws SubscriptionNotFoundException
     */
    public function unsubscribe(string $id): void
    {
        try {
            $this->httpClient->request(
                'DELETE',
                sprintf('webhooks/subscription/%s', $id),
            );
        } catch (NotFoundException) {
            throw SubscriptionNotFoundException::fromId($id);
        }
    }

    /**
     * @see https://developers.timeular.com/#295fadf6-7f50-48b2-8c1b-daa426046e68
     */
    public function listSubscriptions(): array
    {
        $response = $this->httpClient->request(
            'GET',
            'webhooks/subscription',
        );

        return array_map(static fn(array $subscription): Subscription => Subscription::fromArray($subscription), $response['subscriptions']);
    }

    /**
     * @seehttps://developers.timeular.com/#3e7db6eb-4bbe-400e-b155-ba7ffde690d4
     */
    public function unsubscribeAllForUser(): void
    {
        $this->httpClient->request(
            'DELETE',
            'webhooks/subscription',
        );
    }
}
