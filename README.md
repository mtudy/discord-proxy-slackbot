# Discord Proxy Slackbot

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

## Example
#### 1. Connect Slack to Zeppelin
![slack](https://user-images.githubusercontent.com/32331576/108405916-0836bb00-7265-11eb-8503-74b5c5c055ec.png)


#### 2. Connect Slackbot to Discord with This project
![discord](https://user-images.githubusercontent.com/32331576/108405936-0ff65f80-7265-11eb-8cca-270bf270d6c2.png)
