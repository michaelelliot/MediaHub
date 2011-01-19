# NOTES

The general idea behind MediaHub is to provide publishers with an easy way to
store and manage information about their content. This content can then be
accessed via the MediaHub API by and delivered to consumers. :D

While the MediaHub server stores information about the content it holds, this
information can be periodically updated to reflect the information stored at
MediaTag. This provides a central place for accurate meta data to be stored and
managed.

An example of how this can be helpful is the change of the capitalization of
the band silverchair. They originally used the typical uppercase first letter
convention, then later announced that their band name would be entirely lower
case. The MediaTag entries for silverchair content could be ammended and would
eventually be updated by all the MediaHub servers.

## Glossary
*Meob or Media Object*

A meob is a media object. Media objects have a media class which defines
what the media object is. Examples of media classes include: a movie, a series,
a series season, a series episode, an album.

Every media object or meob has an mtag.

*Media Class*

The type of media object. A media class can be one of the following:
* movie
* album
* track
* series
* season
* episode
* game
* ebook
* application

_Media Tag or mtag_

TODO:

_Media Class or mclass_

TODO:

_Content Sources_

A list of sources for the content, the type of source (e.g. url, btih) and its
relation to the content (e.g. feature, trailer, sample, screenshot).

The content sources are specified using a CSS-like syntax. For example:
* relation: feature; source: btih(c389547e7551e9785c4fa87935824a5403d178e8);
* relation: trailer; source: url(http://www.youtube.com/xxx);

Types of relation:
* feature
* trailer
* sample
* screenshot
* music-video

## 1. Content Fields

### 1.1 Notes
Fields that are arrays (suffixed with []) store values separated by a semicolon.
e.g. Bruce Willis; Brad Pitt; Elvis Presley

Fields referring to duration are stored in seconds.

## 1.2 Content Field Names

### Keys unique (mostly) to this content
### e.g. torrent_info_hash:abcd
###      torrent_filename:Torrent_file.torrent
* mkeys[]

### Global fields
* mtag
* mclass

### Publisher fields
* publisher_name
* publisher_url

### Movie fields
* title
* year (int)
* runtime
* plot
* tagline
* release_date
* classification
* genres[]
* actors[]
* directors[]
* writers[]

### Album fields
* title
* year (int)
* total_tracks
* total_duration
* genres[]
* artists[]

### Extraneous fields
* imdb_tt
* imdb_rating (float)
* rotten_tomatoes_rating (int)

<!--
* track_number (int)
* track_title
* track_year (int)
* track_genres[]
* track_artist
* track_duration (int)

### Fields for series
* series_name
* series_genres[]
* season_number (int)
* episode_number (int)
* episode_title
* episode_production_code
* episode_duration (int)

### Fields for external references or values
* external_imdb_tt
* external imdb_rating (float)
* external_rotten_tomatoes_rating (int)

-->

## 2. Search Keywords
All searching is done using search keywords. Each keyword refers to a specific
field to search. e.g.: "year:2001" would search for content with a year of 2001.
If no search keyword is specified it will default to a "all:" search.

Types of search keywords include:
* all
* movie
* year (int)
* series
* season (int)
* episode (int)
* artist
* imdb
* rating (int)
* actor
* director
* producer
* writer
* song
* track
* genre

* bitrate:

The content fields each search keyword relates to is as follows:
###all
The all keyword searches the following content fields:
* content.title
* movie.title
* movie.actor[]
* movie.director[]
* movie.producer[]
* movie.writer[]
* album.title
* album.genre[]
* album.artist
* track.title
* track.genre[]
* track.artist
* series.name
* episode.title

###movie
The movie keyword searches the following content fields:
* movie.title

###year (int)
Depending on the type of search, the year keyword searches the following
content fields:
* movie.year
* album.year
* track.year
* series

###season (int)

###episode (int)

###artist

###imdb

###rating (int)

###actor

###director

###producer

###writer

###song

###track

###genre

Types that are integers (int) allow for searches like "year:>2000" or
"year:<2000". Searching for "year>2000" or "year<2000" is also acceptable.

The genre type will refer to movie_genres if the search includes "movie:" or to
album_genre for "album:" etc.

## 3. Config
