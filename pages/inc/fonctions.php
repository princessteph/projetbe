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

function get_produit_membre($id_produit_membre) {
    $sql = "SELECT pm.*, p.nom AS nom_produit, m.nom AS nom_membre 
            FROM produit_membre pm
            JOIN produit p ON pm.id_produit = p.id_produit
            JOIN membre m ON pm.id_membre = m.id_membre
            WHERE pm.id_produit_membre = '$id_produit_membre'";
    return get_one_line($sql);
}

function acheter_produit($id_produit_membre, $quantite_achetee) {
    $connect = dbconnect();
    
    $produit = get_produit_membre($id_produit_membre);
    
    if (!$produit) {
        return ['success' => false, 'message' => 'Produit introuvable'];
    }
    
    if ($produit['quantite_dispo'] < $quantite_achetee) {
        return ['success' => false, 'message' => 'Quantite insuffisante. Disponible : ' . $produit['quantite_dispo']];
    }
    
    if ($quantite_achetee <= 0) {
        return ['success' => false, 'message' => 'Quantite invalide'];
    }
    
    $nouvelle_quantite = $produit['quantite_dispo'] - $quantite_achetee;
    $sql_update = "UPDATE produit_membre SET quantite_dispo = '$nouvelle_quantite' WHERE id_produit_membre = '$id_produit_membre'";
    
    if (!mysqli_query($connect, $sql_update)) {
        return ['success' => false, 'message' => 'Erreur lors de la mise a jour'];
    }
    
    $date = date('Y-m-d');
    $heure = date('H:i:s');
    $sql_vente = "INSERT INTO vente (date, heure, id_produit_membre, quantite) 
                  VALUES ('$date', '$heure', '$id_produit_membre', '$quantite_achetee')";
    
    if (!mysqli_query($connect, $sql_vente)) {
        return ['success' => false, 'message' => 'Erreur lors de l\'enregistrement de la vente'];
    }
    
    return ['success' => true, 'message' => 'Achat reussi !'];
}