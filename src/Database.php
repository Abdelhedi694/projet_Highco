<?php

namespace App;

class Database
{



public function Pdo(){
    try {
       return $pdo = new \PDO("mysql:host=localhost;dbname=highco","root","");

    }catch (\PDOException $exception){
        dd($exception->getMessage());
    }
}

}
