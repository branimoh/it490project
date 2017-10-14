#!/usr/bin/php
<?php
  require_once ('/home/shannon/Documents/IT490/it490project/vendor/autoload.php');
  require_once ('/home/shannon/Documents/IT490/it490project/movies.php.inc');
  $db = new moviedb();  
  $data = array();
  
  $token = new \Tmdb\ApiToken('32878828bcad6134a8ced94250b6fc53');
  $client = new \Tmdb\Client($token);
  $dRepo = new \Tmdb\Repository\DiscoverRepository($client);
  $dQuery = new \Tmdb\Model\Query\Discover\DiscoverMoviesQuery($data);
  $repository = new \Tmdb\Repository\MovieRepository($client);
  $movies = $dRepo->discoverMovies($dQuery->page(2));
  foreach ($movies as $movie)
  {
    $m =  $repository->load($movie->getId());
    $movieTitle = $m->getTitle();
    $movieGenre = $m->getGenres()[0]->getName();
    $releaseDate = $m->getReleaseDate()->format('Y-m-d H-i-s');
    $rating = $m->getPopularity();
    $imagePaths = array();
    $count = 0;
    foreach ($m->getImages() as $images)
    {
      $imagePaths[$count] = $images->getFilePath();
    }
    $image = $imagePaths[0];
    $query = "insert into movies(title, genre, releaseDate, rating, imagePath)
 values('$movieTitle', '$movieGenre', '$releaseDate', '$rating', '$image');";
    $db->addMovie($query);
  }
?>
