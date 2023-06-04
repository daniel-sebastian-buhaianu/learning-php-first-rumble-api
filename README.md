# Rumble API

Retrieves useful information about a Rumble channel.

## Table of Contents

- [Project Title](#project-title)
- [Description](#description)
- [Features](#features)
- [Usage](#usage)

## Description

Since I couldn't find a Rumble API to retrieve information about a particular Rumble Channel, I decided to build one.

This simple Rumble API allows you to get useful data of a particular Rumble Channel, such a channel id, number of pages, useful information about all videos uploaded by that channel, and more!

All you need to do is provide the Rumble Channel URL, such as: https://rumble.com/c/TateSpeech

## Features

- Allows you to get useful information about a particular Rumble Channel, such as:
  - url
  - channel_id
  - pages_count
  - pages_data
    - page_url
    - current_page_index
    - last_page_index
    - videos_data ( url, title, thumbnail, uploaded_at, likes, dislikes, views, comments )

## Usage

https://dsb99.app/rumble-api?api_key=YOUR_API_KEY&url=RUMBLE_CHANNEL_URL

## Acknowledgments

- Thanks to the PHP team for helping me code this using PHP.

## Contact

- dsb99.dev@gmail.com

