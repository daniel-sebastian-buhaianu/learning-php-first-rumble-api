# Rumble Channel API

Retrieves useful information about any existent rumble channel.

## Table of Contents

- [Project Title](#project-title)
- [Description](#description)
- [Features](#features)
- [Usage](#usage)

## Description

Since I couldn't find a Rumble API to retrieve information 
about a particular rumble channel, I decided to build one.

This Rumble Channel API allows you to get useful data about
any existent rumble channel, such as:

- url 
- id
- banner image source
- avatar image source
- title
- number of followers
- about description
- rumble joining date
- number of videos uploaded
- videos data

... and more!

All you need to do is provide a valid URL of an existent rumble
channel, such as: https://rumble.com/c/TateSpeech

## Features

- In Progress...

## Usage

- https://dsb99.app/rc-api/channel?key=YOUR_API_KEY&url=VALID_RUMBLE_CHANNEL_URL
	- gets rumble channel general data

- https://dsb99.app/rc-api/channel/about?key=YOUR_API_KEY&url=VALID_RUMBLE_CHANNEL_URL
	- gets rumble channel about page data 

- https://dsb99.app/rc-api/channel/videos?key=YOUR_API_KEY&url=VALID_RUMBLE_CHANNEL_URL
	- gets rumble channel videos page data

You can also pass rumble specific query parameters to the rumble channel url to sort/filter the videos result from a channel videos page, like: https://dsb99.app/rc-api/channel/videos?key=YOUR_API_KEY&url=VALID_RUMBLE_CHANNEL_URL?sort=views&date=this-year&duration=long

## Contact

- dsb99.dev@gmail.com

