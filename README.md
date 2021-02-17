# Slackbot proxy for Discord
Slackbot proxy for Discord.

!! Supported only Zeppelin message format.

## Getting Started

Create your [Slackbot](https://api.slack.com/apps)

```bash
cp .env.example .env
cp docker/.env.example docker/.env

make start
make composer
make bash 

php artisan workman start --d
```
