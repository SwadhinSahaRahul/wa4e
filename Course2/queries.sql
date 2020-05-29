DROP table if exists Album;
DROP table if exists Artist;
DROP table if exists Genre;
DROP table if exists Track;

CREATE TABLE Artist
(
    id   INTEGER AUTO_INCREMENT PRIMARY KEY,
    name varchar(255)
);

CREATE TABLE Genre
(
    id   INTEGER PRIMARY KEY AUTO_INCREMENT,
    name varchar(255)
);

CREATE TABLE Album
(
    id        INTEGER AUTO_INCREMENT PRIMARY KEY,
    artist_id INTEGER,
    title     varchar(255),
    CONSTRAINT FOREIGN KEY (artist_id) references Artist (id) on delete cascade on update cascade
);

CREATE TABLE Track
(
    id       INTEGER PRIMARY KEY AUTO_INCREMENT,
    title    varchar(255),
    album_id INTEGER,
    genre_id INTEGER,
    len      INTEGER,
    rating   INTEGER,
    count    INTEGER,
    CONSTRAINT foreign key (album_id) references Album (id) on delete cascade on update cascade,
    CONSTRAINT foreign key (genre_id) references Genre (id) on delete cascade on update cascade
);

insert into Artist (name)
values ('Greatest Hits'),
       ('Grease'),
       ('Herzeleid');

insert into Genre (name)
values ('Rock'),
       ('Jazz'),
       ('Hiphop'),
       ('Pop');

insert into Album (artist_id, title)
VALUES (1, 'Love Dose'),
       (1, 'Chain Smokers'),
       (2, 'Celebrity'),
       (2, 'Now or Never'),
       (3, 'Nobody');

insert into Track (title, album_id, genre_id, len, rating, count)
VALUES ('Title1', 1, 2, 123, 5, 12),
       ('Title2', 1, 2, 123, 5, 12),
       ('Title3', 1, 2, 123, 5, 12),
       ('Title4', 1, 2, 123, 5, 12),
       ('Title5', 2, 2, 123, 5, 12),
       ('Title6', 2, 1, 123, 5, 12),
       ('Title7', 2, 1, 123, 5, 12),
       ('Title8', 2, 1, 123, 5, 12),
       ('Title9', 3, 1, 123, 5, 12),
       ('Title10', 3, 1, 123, 5, 12),
       ('Title11', 3, 3, 123, 5, 12),
       ('Title12', 3, 3, 123, 5, 12),
       ('Title13', 4, 3, 123, 5, 12),
       ('Title14', 4, 3, 123, 5, 12),
       ('Title15', 4, 3, 123, 5, 12),
       ('Title16', 4, 4, 123, 5, 12),
       ('Title17', 5, 4, 123, 5, 12),
       ('Title18', 5, 4, 123, 5, 12),
       ('Title19', 5, 4, 123, 5, 12),
       ('Title20', 5, 4, 123, 5, 12);

select Track.title title, Album.title album, Artist.name artist, Genre.name genre
from Track
         JOIN Album on Track.album_id = Album.id
         JOIN Artist ON Album.artist_id = Artist.id
         JOIN Genre ON Track.genre_id = Genre.id;

select DISTINCT Artist.name artist, Genre.name genre
from Track
         JOIN Album on Track.album_id = Album.id
         JOIN Artist ON Album.artist_id = Artist.id
         JOIN Genre ON Track.genre_id = Genre.id
WHERE Artist.name = 'Grease'