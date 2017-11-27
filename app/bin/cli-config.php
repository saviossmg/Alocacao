<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

// Aqui é carrego o arquivo que criamos antes
require_once 'bootstrap.php';

return ConsoleRunner::createHelperSet($entityManager);