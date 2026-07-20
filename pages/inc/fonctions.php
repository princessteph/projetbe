<?php 
include_once 'connect.php';

function get_all_lines($sql){
    $req = mysqli_query(dbconnect(),$sql );
    if (!$req) {
        die('Erreur SQL : ' . mysqli_error(dbconnect()));
    }
    $result = array();
    while ($line = mysqli_fetch_assoc($req)) {
        $result[] = $line;
    }
    mysqli_free_result($req);
    return $result;
}

function get_one_line($sql){

    $req = mysqli_query(dbconnect(),$sql );
    if (!$req) {
        die('Erreur SQL : ' . mysqli_error(dbconnect()));
    }
    $result = mysqli_fetch_assoc($req);
    mysqli_free_result($req);
    return $result;
}

function produit_membres($etu){
    $sql = "SELECT * FROM produit_membre
                JOIN membre ON produit_membre.id_membre = membre.id_membre
                WHERE membre.numero_etu != '$etu'";
    $result = get_all_lines($sql);
    return $result;
}