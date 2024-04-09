<?php

declare(strict_types=1);

namespace Timeular;

use Timeular\Api\TimeTracking;
use Timeular\Api\UserProfile;
use Timeular\Model\TimeTracking\ActiveTimeEntry;
use Timeular\Model\TimeTracking\Activity;
use Timeular\Model\TimeTracking\Device;
use Timeular\Model\TimeTracking\Mention;
use Timeular\Model\TimeTracking\Tag;
use Timeular\Model\TimeTracking\TimeEntry;
use Timeular\Model\UserProfile\Me;

class Timeular
{
    public function __construct(
        private UserProfile\UserApi $user,
        private UserProfile\SpaceApi $space,
        private TimeTracking\DevicesApi $devices,
        private TimeTracking\TagsAndMentionsApi $tagsAndMentions,
        private TimeTracking\ActivitiesApi $activities,
        private TimeTracking\CurrentTrackingApi $currentTracking,
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
}
