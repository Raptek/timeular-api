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

# WIP

## Installation

1. Create `.env` file and fill it with credentials from https://profile.timeular.com/

```bash
cp .env.dist .env
```

2. Use the package manager [composer](https://getcomposer.org/) to install dependencies

```bash
composer install
```

or use provided docker compose file

```bash
docker compose run php composer install
```

## Usage

Run script with 
```bash
php app.php
```

or using docker compose

```bash
docker compose run php php app.php
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](LICENSE)
