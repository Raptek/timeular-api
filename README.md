# Timeular API

Timeular API is a PHP library for consuming https://timeular.com/ API.

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
