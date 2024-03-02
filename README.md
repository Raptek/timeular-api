# Timeular API

Timeular API is a PHP library for consuming https://timeular.com/ API.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install.

```bash
composer install
```

or use provided docker compose file

```bash
docker compose run php composer install
```

## Usage

```bash
php app.php API_KEY API_SECRET
```

or using docker compose

```bash
docker compose run php php app.php API_KEY API_SECRET
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](LICENSE)
