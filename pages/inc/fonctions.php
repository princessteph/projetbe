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

function check ($etu){
    $sql = "SELECT * FROM membre WHERE numero_etu = '$etu'";
    if (get_one_line($sql)) {
        return true;
    } else {
        return false;
    }
}

function inscription ($etu, $name, $image){
    $sql = "INSERT INTO membre (numero_etu, nom, image_profil) VALUES ('$etu', '$name', '$image')";
    return mysqli_query(dbconnect(),$sql);
}

function all_categories(){
    $sql = "SELECT * FROM categorie";
    return get_all_lines($sql);
}

function vendre_produit($nom_produit, $id_category, $price){
    $sql = "INSERT INTO produit (nom, id_categorie, prix_reference) VALUES ('$nom_produit', '$id_category', '$price')";
    return mysqli_query(dbconnect(),$sql);
}

function vente ($etu, $nom_produit, $id_category, $price, $quantite, $date_dispo){
    vendre_produit($nom_produit, $id_category, $price);
    $id_produit = mysqli_insert_id(dbconnect());
    $sql = "INSERT INTO produit_membre (id_membre, id_produit, prix_vente, quantite_dispo, date_dispo) 
            VALUES ((SELECT id_membre FROM membre WHERE numero_etu = '$etu'), '$id_produit', '$price', '$quantite', '$date_dispo')";
    return mysqli_query(dbconnect(),$sql);
}