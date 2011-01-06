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


## 1. Content Fields

### 1.1 Notes
Fields that are arrays (suffixed with []) store values separated by a semicolon.
e.g. Bruce Willis; Brad Pitt; Elvis Presley

Fields referring to duration are stored in seconds.

## 1.2 Content Field Names

### Keys unique (mostly) to this content
### e.g. torrent.info_hash:abcd
###      torrent.filename:Torrent_file.torrent
    mkeys


### Fields for all content types
    content.type
    content.mtag
    content.title
    content.rating (int)
    publisher.name
    publisher.url

### Fields for movies
    movie.title
    movie.year (int)
    movie.genre[]
    movie.actor[]
    movie.director[]
    movie.producer[]
    movie.writer[]

### Fields for music
    album.title
    album.year (int)
    album.genre[]
    album.artist
    track.number (int)
    track.title
    track.year (int)
    track.genre[]
    track.artist
    track.duration (int)

### Fields for series
    series.name
    series.genre[]
    season.number (int)
    episode.number (int)
    episode.title
    episode.production.code
    episode.duration (int)

### Fields for external references or values
    external.imdb.tt
    external imdb.rating (float)
    external.rotten.tomatoes.rating (int)

### Fields for storing content sources
    source.btih
    source.url

## 2. Search Keywords
All searching is done using search keywords. Each keyword has a type which
allows different types of searches to be performed. e.g.: year:2001

Types of keywords include movie, year (int), series, season (int),
episode (int), artist, imdb, rating (int), actor, director, producer, writer,
song/track, genre

Types that are integers (int) allow for searches like year:>2000 or
year:<2000 etc.
`year\>2000`
and
`year\<2000`
are also acceptable.

The genre type will refer to movie_genres if the search includes "movie:" or to
album_genre for "album:" etc.

## 3. Config
