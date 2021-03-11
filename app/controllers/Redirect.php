<?php


namespace App\controllers;


class Redirect
{
    public static function to($location = null)
    {
        if ($location) {
            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header('HTTP/1.0 404 Not found');
                        include 'includes/errors/404.php';
                        exit();
                    case 500:
                        header('HTTP/1.0 500 Internal Server Error');
                        exit();
                        break;
                }
            }
            header('Location:' . $location);
        }
    }
}