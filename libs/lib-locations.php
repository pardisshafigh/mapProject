<?php
function insertLocation($data)
{
    $pdo = new PDO("mysql:host=localhost;dbname=7map","root","");
    // validation here
    $sql = "INSERT INTO `locations` (`title`, `lat`, `lng`, `type`) VALUES (:title, :lat, :lng, :typ);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':title'=>$data['title'],':lat'=>$data['lat'],':lng'=>$data['lng'],':typ'=>$data['type']]);
    return $stmt->rowCount();
}


function getLocations($params = [])
{
    $pdo = new PDO("mysql:host=localhost;dbname=7map","root","");
    $condition = '';
    if (isset($params['verified']) && in_array($params['verified'],['0', '1'])) {
       $condition = "WHERE verified = {$params['verified']}";
    }else if (isset($params['keyword'])) {
        $condition = "WHERE verified = 1 and title LIKE '%{$params['keyword']}%'";
    }
    $sql = "SELECT * FROM ‍‍‍`locations‍‍` $condition";
    // dd($sql);
    $stmt= $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);

}



function getLocation($id)
{
    global $pdo;
    $sql = "SELECT * FROM ‍‍‍`locations‍‍` where id=:id";
    // dd($sql);
    $stmt= $pdo->prepare($sql);
    $stmt->execute(['id'=>$id]);
    return $stmt->fetch(PDO::FETCH_OBJ);

}


function toggleStatus($id)
{
    global $pdo;
    $sql = "UPDATE locations SET verified = 1 - verified WHERE id = :id";
    // dd($sql);
    $stmt= $pdo->prepare($sql);
    $stmt->execute(['id'=>$id]);
    return $stmt->rowCount();

}



