<?php
include_once 'dbconnect.php';

function get_all_lines($sql){
    $req = mysqli_query(dbconnect(), $sql);
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
    $req = mysqli_query(dbconnect(), $sql);
    if (!$req) {
        die('Erreur SQL : ' . mysqli_error(dbconnect()));
    }
    $result = mysqli_fetch_assoc($req);
    mysqli_free_result($req);
    return $result;
}

function produit_membres($etu){
    $sql = "SELECT 
                produit.nom AS nom_produit,
                produit_membre.prix_vente AS prix,
                membre.nom AS nom_membre,
                produit_membre.quantite_dispo,
                produit_membre.date_dispo,
                categorie.nom_categorie
            FROM produit_membre
            JOIN membre ON produit_membre.id_membre = membre.id_membre
            JOIN produit ON produit_membre.id_produit = produit.id_produit
            JOIN categorie ON produit.id_categorie = categorie.id_categorie
            WHERE membre.numero_etu != '$etu'";
    $result = get_all_lines($sql);
    return $result;
}

function check($etu){
    $sql = "SELECT * FROM membre WHERE numero_etu = '$etu'";
    if (get_one_line($sql)) {
        return true;
    } else {
        return false;
    }
}

function inscription($etu, $name, $image){
    $sql = "INSERT INTO membre (numero_etu, nom, image_profil) VALUES ('$etu', '$name', '$image')";
    return mysqli_query(dbconnect(), $sql);
}

function all_categories(){
    $sql = "SELECT * FROM categorie";
    return get_all_lines($sql);
}

function get_all_produit(){
    $sql = "SELECT * FROM produits";
    return get_all_lines($sql);
}

function vente ($etu, $produit, $price, $quantite, $date_dispo){
    $sql = "INSERT INTO produit_membre (id_membre, id_produit, prix_vente, quantite_dispo, date_dispo) 
            VALUES ((SELECT id_membre FROM membre WHERE numero_etu = '$etu'), (SELECT id_produit FROM produits WHERE id_produit = '$produit'), '$price', '$quantite', '$date_dispo')";
    return mysqli_query(dbconnect(),$sql);
}

function total_ventes($etu){
    $sql = "SELECT SUM(prix_vente * quantite) AS total FROM produit_membre JOIN vente ON produit_membre.id_produit_membre = vente.id_produit_membre WHERE id_membre = (SELECT id_membre FROM membre WHERE numero_etu = '$etu') AND id_produit_membre IN (SELECT id_produit_membre FROM vente)";
    $result = get_one_line($sql);
    return $result['total'] ?? 0;
}

function get_all_ventes($etu){
    $sql = "SELECT produit_membre.nom AS nom, produit_membre.prix_vente AS prix, produit_membre.quantite_dispo AS quantite, produit_membre.date_dispo AS date FROM produit_membre JOIN vente ON produit_membre.id_produit_membre = vente.id_produit_membre WHERE id_membre = (SELECT id_membre FROM membre WHERE numero_etu = '$etu') AND id_produit_membre IN (SELECT id_produit_membre FROM vente)";
    return get_all_lines($sql); 
}