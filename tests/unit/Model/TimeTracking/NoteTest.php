<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Model\TimeTracking;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Model\TimeTracking\Note;

class NoteTest extends TestCase
{
    #[Test]
    public function it_creates_note_from_array():void
    {
        $note = Note::fromArray(
            [
                'text' => null,
                'tags' => [],
                'mentions' => [],
            ]
        );

        self::assertNull($note->text);
        self::assertEquals([], $note->tags);
        self::assertEquals([], $note->mentions);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = [
            'text' => null,
            'tags' => [],
            'mentions' => [],
        ];

        $note = Note::fromArray($data);

        self::assertSame($note->toArray(), $data);
    }
}
