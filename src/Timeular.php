<?php

declare(strict_types=1);

namespace Timeular;

use Timeular\TimeTracking\Model\ActiveTimeEntry;
use Timeular\TimeTracking\Model\Activity;
use Timeular\TimeTracking\Model\Device;
use Timeular\TimeTracking\Model\Mention;
use Timeular\TimeTracking\Model\Tag;
use Timeular\TimeTracking\Model\TimeEntry;
use Timeular\UserProfile\Model\Me;
use Timeular\Webhooks\Model\Subscription;

class Timeular
{
    public function __construct(
        private UserProfile\Api\UserApi $user,
        private UserProfile\Api\SpaceApi $space,
        private TimeTracking\Api\DevicesApi $devices,
        private TimeTracking\Api\TagsAndMentionsApi $tagsAndMentions,
        private TimeTracking\Api\ActivitiesApi $activities,
        private TimeTracking\Api\CurrentTrackingApi $currentTracking,
        private TimeTracking\Api\TimeEntriesApi $timeEntries,
        private TimeTracking\Api\ReportsApi $reports,
        private Webhooks\Api\WebhooksApi $webhooks,
        private Integrations\Api\IntegrationsApi $integrations,
    ) {
    }

    public function me(): Me
    {
        return $this->user->me();
    }

    public function devices(): array
    {
        return $this->devices->list();
    }

    public function activateDevice(string $serial): Device
    {
        return $this->devices->activate($serial);
    }

    public function deactivateDevice(string $serial): Device
    {
        return $this->devices->deactivate($serial);
    }

    public function forgetDevice(string $serial): void
    {
        $this->devices->forget($serial);
    }

    public function disableDevice(string $serial): Device
    {
        return $this->devices->disable($serial);
    }

    public function enableDevice(string $serial): Device
    {
        return $this->devices->enable($serial);
    }

    public function editDevice(string $serial, string $name): Device
    {
        return $this->devices->edit($serial, $name);
    }

    public function spacesWithMembers(): array
    {
        return $this->space->spacesWithMembers();
    }

    public function tagsAndMentions(): array
    {
        return $this->tagsAndMentions->tagsAndMentions();
    }

    public function createTag(
        string $key,
        string $label,
        string $scope,
        string $spaceId,
    ): Tag {
        return $this->tagsAndMentions->createTag($key, $label, $scope, $spaceId);
    }

    public function updateTag(
        string $id,
        string $label,
    ): Tag {
        return $this->tagsAndMentions->updateTag($id, $label);
    }

    public function deleteTag(
        string $id,
    ): array {
        return $this->tagsAndMentions->deleteTag($id);
    }

    public function createMention(
        string $key,
        string $label,
        string $scope,
        string $spaceId,
    ): Mention {
        return $this->tagsAndMentions->createMention($key, $label, $scope, $spaceId);
    }

    public function updateMention(
        string $id,
        string $label,
    ): Mention {
        return $this->tagsAndMentions->updateMention($id, $label);
    }

    public function deleteMention(
        string $id,
    ): array {
        return $this->tagsAndMentions->deleteMention($id);
    }

    public function activities(): array
    {
        return $this->activities->list();
    }

    public function createActivity(string $name, string $color, string $integration, string $spaceId): Activity
    {
        return $this->activities->create($name, $color, $integration, $spaceId);
    }

    public function editActivity(string $id, string $name, string $color): Activity
    {
        return $this->activities->edit($id, $name, $color);
    }

    public function archiveActivity(string $id): array
    {
        return $this->activities->archive($id);
    }

    public function assignActivityToDeviceSide(string $id, int $deviceSide): Activity
    {
        return $this->activities->assign($id, $deviceSide);
    }

    public function unassignActivityFromDeviceSide(string $id, int $deviceSide): Activity
    {
        return $this->activities->unassign($id, $deviceSide);
    }

    public function showCurrentTracking(): ActiveTimeEntry|null
    {
        return $this->currentTracking->show();
    }

    public function startTracking(string $activityId, \DateTimeInterface $startedAt): ActiveTimeEntry
    {
        return $this->currentTracking->start($activityId, $startedAt);
    }

    public function editTracking(string $activityId, \DateTimeInterface|null $startedAt = null, string|null $note = null): ActiveTimeEntry
    {
        return $this->currentTracking->edit($activityId, $startedAt, $note);
    }

    public function stopTracking(\DateTimeInterface $stoppedAt): TimeEntry
    {
        return $this->currentTracking->stop($stoppedAt);
    }

    public function findTimeEntries(\DateTimeInterface $startedAt, \DateTimeInterface $stoppedAt): array
    {
        return $this->timeEntries->find($startedAt, $stoppedAt);
    }

    public function createTimeEntry(string $activityId, \DateTimeInterface $startedAt, \DateTimeInterface $stoppedAt, string|null $note): TimeEntry
    {
        return $this->timeEntries->create($activityId, $startedAt, $stoppedAt, $note);
    }

    public function findTimeEntry(string $id): TimeEntry
    {
        return $this->timeEntries->findById($id);
    }

    public function editTimeEntry(string $id, string $activityId, \DateTimeInterface $startedAt, \DateTimeInterface $stoppedAt, string|null $note): TimeEntry
    {
        return $this->timeEntries->edit($id, $activityId, $startedAt, $stoppedAt, $note);
    }

    public function deleteTimeEntry(string $id): array
    {
        return $this->timeEntries->delete($id);
    }

    public function getEntriesInDateRange(\DateTimeInterface $startedAt, \DateTimeInterface $stoppedAt): array
    {
        return $this->reports->getAllData($startedAt, $stoppedAt);
    }

    public function generateReport(\DateTimeInterface $startedAt, \DateTimeInterface $stoppedAt, string $timezone, string|null $activityId = null, string|null $noteQuery = null, string|null $fileType = 'csv'): mixed
    {
        return $this->reports->generateReport($startedAt, $stoppedAt, $timezone, $activityId, $noteQuery, $fileType);
    }

    public function listAvailableEvents(): array
    {
        return $this->webhooks->listAvailableEvents();
    }

    public function subscribeToEvent(string $event, string $targetUrl): Subscription
    {
        return $this->webhooks->subscribe($event, $targetUrl);
    }

    public function unsubscribeFromEvent(string $id): void
    {
        $this->webhooks->unsubscribe($id);
    }

    public function listSubscriptions(): array
    {
        return $this->webhooks->listSubscriptions();
    }

    public function unsubscribeAllEventsForUser(): void
    {
        $this->webhooks->unsubscribeAllForUser();
    }

    public function listEnabledIntegrations(): array
    {
        return $this->integrations->listEnabledIntegrations();
    }
}
