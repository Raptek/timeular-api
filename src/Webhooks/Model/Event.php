<?php

declare(strict_types=1);

namespace Timeular\Webhooks\Model;

enum Event: string
{
    case TimeEntryCreated = 'timeEntryCreated';
    case TimeEntryUpdated = 'timeEntryUpdated';
    case TimeEntryDeleted = 'timeEntryDeleted';
    case TrackingStarted = 'trackingStarted';
    case TrackingStopped = 'trackingStopped';
    case TrackingEdited = 'trackingEdited';
    case TrackingCanceled = 'trackingCanceled';
}
