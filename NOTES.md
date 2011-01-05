# NOTES

The general idea of MediaHub is to provide publishers with a way to easily
store and manage information about their content. This content can then be
accessed via the MediaHub API and conveniently delivered to consumers. :D

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

### Content keys unique to this content
    key_torrent_btih
    key_torrent_filename

### Fields for all content types
    content_type
    content_mtag
    content_title
    content_rating (int)
    publisher_name
    publisher_url

### Fields for movies
    movie_title
    movie_year (int)
    movie_genre[]
    movie_actor[]
    movie_director[]
    movie_producer[]
    movie_writer[]

### Fields for music
    album_title
    album_year (int)
    album_genre[]
    album_artist
    track_number (int)
    track_title
    track_year (int)
    track_genre[]
    track_artist
    track_duration (int)

### Fields for series
    series_name
    series_genre[]
    season_number (int)
    episode_number (int)
    episode_title
    episode_production_code
    episode_duration (int)

### Fields for external references or values
    external_imdb_tt
    external imdb_rating (float)
    external_rotten_tomatoes_rating (int)

### Fields for storing content sources
    source_btih
    source_url

## 2. Search Keywords
All searching is done using search keywords. Each keyword has a type which
allows different types of searches to be performed. e.g.: year:2001

Types of keywords include movie, year (int), series, season (int),
episode (int), artist, imdb, rating (int), actor, director, producer, writer,
song/track, genre

Types that are integers (int) allow for searches like year:>2000 or
year:<2000 etc. `year\>2000` and `year\<2000` are also acceptable.

The genre type will refer to movie_genres if the search includes "movie:" or to
album_genre for "album:" etc.

## 3. Config
