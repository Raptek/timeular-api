<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\TimeTracking\Model;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\TimeTracking\Model\Mention;
use Timeular\TimeTracking\Model\Note;
use Timeular\TimeTracking\Model\Tag;

class NoteTest extends TestCase
{
    #[Test]
    public function it_creates_note_from_array(): void
    {
        $tag = [
            'id' => 1,
            'key' => '1234',
            'label' => 'some-tag',
            'scope' => 'timeular',
            'spaceId' => '1',
        ];
        $mention = [
            'id' => 1,
            'key' => '1234',
            'label' => 'some mention',
            'scope' => 'timeular',
            'spaceId' => '1',
        ];

        $note = Note::fromArray(
            [
                'text' => null,
                'tags' => [
                    $tag,
                ],
                'mentions' => [
                    $mention,
                ],
            ],
        );

        self::assertNull($note->text);
        self::assertEquals([Tag::fromArray($tag)], $note->tags);
        self::assertEquals([Mention::fromArray($mention)], $note->mentions);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = [
            'text' => null,
            'tags' => [
                [
                    'id' => 1,
                    'key' => '1234',
                    'label' => 'some-tag',
                    'scope' => 'timeular',
                    'spaceId' => '1',
                ],
            ],
            'mentions' => [
                [
                    'id' => 1,
                    'key' => '1234',
                    'label' => 'some mention',
                    'scope' => 'timeular',
                    'spaceId' => '1',
                ],
            ],
        ];

        $note = Note::fromArray($data);

        self::assertSame($note->toArray(), $data);
    }
}
