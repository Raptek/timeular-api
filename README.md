# Timeular API

> Track time and attendance seamlessly in a single time tracking app. With a physical time tracker, automatic time tracking, and other smart methods, you will do it fast and without effort. Rest assured, it’s GDPR and privacy-compliant.
>
> -- <cite>[Timeular](https://timeular.com/)</cite>

## Motivation

As a Developer\
To get paid for my work\
I have to report my time spent on work

When working on multiple projects, depending on habits, sometimes it's hard to track time spent on given task when context switching occurs frequently. Filling work time in Jira after each task doesn't work for me. Doing this every day isn't for me either. Trying to remember what I had worked on two weeks earlier was tedious. I like gadgets. My colleague showed my his dice. Each side assigned to different project. I immediately felt in love with it. Now, my own dice sits in front of me, and when I see that it's in standby when I'm working, I know that it needs to be flipped on correct side.

Timeular application is great, but it lacks few features which would improve my workflow greatly. I wanted to create small app fulfilling all my requirements, but it was impossible to find time for it. And here comes [100 commitów](https://100commitow.pl/) - commit to making small changes for 100 days in a row. I didn't find any PHP library to use Timeular API, so I created one.

## Installation

Package can be installed using [Composer](https://getcomposer.org/) by running command

```bash
composer require raptek/timeular-api
```

## Usage

There is a `Timeular` class which acts as a facade for all API classes that requires `HttpClient` instance. But nothing stops You from using specific API class.

This package relies on [virtual packages](https://getcomposer.org/doc/04-schema.md#provide) and not specific package(s) providing PSR implementations. If You want to use this library in project which already uses at least one implementation (PSR-7, PSR-17, PSR-18) you can manually configure all required dependencies (as shown in example 01) or You can leverage `php-http/discovery`, which will try to automatically find best installed implementation OR will install it for You, if plugin is enabled (example 02).

## License

[MIT](LICENSE)
