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

function produit_membres(){
    $sql = "SELECT 
                produit_membre.id_produit_membre,
                produit.nom AS nom_produit,
                produit_membre.prix_vente AS prix,
                membre.nom AS nom_membre,
                produit_membre.quantite_dispo,
                COALESCE(produit_membre.image_produit, 'default-food.svg') AS image_produit,
                COALESCE(membre.image_profil, 'default-profile.svg') AS image_profil,
                categorie.nom_categorie
            FROM produit_membre
            JOIN membre ON produit_membre.id_membre = membre.id_membre
            JOIN produit ON produit_membre.id_produit = produit.id_produit
            JOIN categorie ON produit.id_categorie = categorie.id_categorie";
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

function ensure_image_columns(){
    $connect = dbconnect();
    mysqli_query($connect, "ALTER TABLE membre ADD COLUMN IF NOT EXISTS image_profil VARCHAR(255) DEFAULT 'default.png'");
    mysqli_query($connect, "ALTER TABLE produit_membre ADD COLUMN IF NOT EXISTS image_produit VARCHAR(255) DEFAULT 'default.png'");
}

function inscription($etu, $name, $image){
    ensure_image_columns();
    $connect = dbconnect();
    $etu = mysqli_real_escape_string($connect, $etu);
    $name = mysqli_real_escape_string($connect, $name);
    $image = mysqli_real_escape_string($connect, $image);
    $sql = "INSERT INTO membre (numero_etu, nom, image_profil) VALUES ('$etu', '$name', '$image')";
    return mysqli_query($connect, $sql);
}

function all_categories(){
    $sql = "SELECT * FROM categorie";
    return get_all_lines($sql);
}

function get_all_produit(){
    $sql = "SELECT * FROM produit";
    return get_all_lines($sql);
}

function vente ($etu, $produit, $price, $quantite, $date_dispo, $image = 'default.png'){
    ensure_image_columns();
    $connect = dbconnect();
    $etu = mysqli_real_escape_string($connect, $etu);
    $produit = mysqli_real_escape_string($connect, $produit);
    $price = mysqli_real_escape_string($connect, $price);
    $quantite = mysqli_real_escape_string($connect, $quantite);
    $date_dispo = mysqli_real_escape_string($connect, $date_dispo);
    $image = mysqli_real_escape_string($connect, $image);
    $sql = "INSERT INTO produit_membre (id_membre, id_produit, prix_vente, quantite_dispo, date_dispo, image) 
            VALUES ((SELECT id_membre FROM membre WHERE numero_etu = '$etu'), (SELECT id_produit FROM produit WHERE id_produit = '$produit'), '$price', '$quantite', '$date_dispo', '$image')";
    return mysqli_query($connect, $sql);
}

function total_ventes($etu){
    $sql = "SELECT SUM(pm.prix_vente * v.quantite) AS total
            FROM produit_membre pm
            JOIN vente v ON pm.id_produit_membre = v.id_produit_membre
            WHERE pm.id_membre = (SELECT id_membre FROM membre WHERE numero_etu = '$etu')";
    $result = get_one_line($sql);
    return $result['total'] ?? 0;
}

function get_all_ventes($etu){
    $sql = "SELECT p.nom AS nom, pm.prix_vente AS prix, v.quantite, pm.date_dispo AS date
            FROM produit_membre pm
            JOIN vente v ON pm.id_produit_membre = v.id_produit_membre
            JOIN produit p ON pm.id_produit = p.id_produit
            WHERE pm.id_membre = (SELECT id_membre FROM membre WHERE numero_etu = '$etu')";
    return get_all_lines($sql); 
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
        return array('type' => 'danger', 'texte' => 'Produit introuvable.');
    }

    if ($quantite_achetee <= 0) {
        return array('type' => 'warning', 'texte' => 'Quantite invalide.');
    }

    if ($produit['quantite_dispo'] < $quantite_achetee) {
        return array('type' => 'danger', 'texte' => 'Stock insuffisant. Il reste ' . $produit['quantite_dispo'] . ' unite(s).');
    }

    $nouvelle_quantite = $produit['quantite_dispo'] - $quantite_achetee;
    $sql_update = "UPDATE produit_membre SET quantite_dispo = '$nouvelle_quantite' WHERE id_produit_membre = '$id_produit_membre'";

    if (!mysqli_query($connect, $sql_update)) {
        return array('type' => 'danger', 'texte' => 'Erreur lors de la mise a jour du stock.');
    }

    $date = date('Y-m-d');
    $heure = date('H:i:s');
    $sql_vente = "INSERT INTO vente (date, heure, id_produit_membre, quantite) 
                  VALUES ('$date', '$heure', '$id_produit_membre', '$quantite_achetee')";

    if (!mysqli_query($connect, $sql_vente)) {
        return array('type' => 'danger', 'texte' => 'Erreur lors de l enregistrement de la vente.');
    }

    return array('type' => 'success', 'texte' => 'Achat reussi !');
}

function image_upload($prefix = 'membre') {
    ensure_image_columns();
    $image = 'default.png';
    $GLOBALS['upload_debug'] = 'ok';

    if (!isset($_FILES['image'])) {
        $GLOBALS['upload_debug'] = "aucun champ 'image' reçu dans \$_FILES (le formulaire a-t-il enctype=multipart/form-data ?)";
        return $image;
    }
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $GLOBALS['upload_debug'] = "erreur PHP upload, code = " . $_FILES['image']['error'];
        return $image;
    }
    if ($_FILES['image']['size'] <= 0) {
        $GLOBALS['upload_debug'] = "taille du fichier reçu = 0 (aucun fichier sélectionné ?)";
        return $image;
    }

    $extensions_ok = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    $nom_fichier = $_FILES['image']['name'];
    $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));

    if (!in_array($extension, $extensions_ok)) {
        $GLOBALS['upload_debug'] = "extension refusée = '$extension' (fichier: $nom_fichier)";
        return $image;
    }

    $prefix_name = ($prefix === 'produit') ? 'produit_' : 'membre_';
    $nouveau_nom = $prefix_name . uniqid('', true) . '.' . $extension;
    $dossier_destination = dirname(__DIR__) . '/assets/img/' . $nouveau_nom;

    if (!is_dir(dirname($dossier_destination))) {
        mkdir(dirname($dossier_destination), 0777, true);
    }
    if (!is_writable(dirname($dossier_destination))) {
        $GLOBALS['upload_debug'] = "dossier non writable = " . dirname($dossier_destination);
        return $image;
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $dossier_destination)) {
        $image = $nouveau_nom;
    } else {
        $GLOBALS['upload_debug'] = "move_uploaded_file a échoué vers $dossier_destination (tmp_name: " . $_FILES['image']['tmp_name'] . ")";
    }

    return $image;
}

function ajout_produit($nom, $categorie, $price) {
    $connect = dbconnect();
    $nom = mysqli_real_escape_string($connect, $nom);
    $categorie = mysqli_real_escape_string($connect, $categorie);
    $price = mysqli_real_escape_string($connect, $price);
    $sql = "INSERT INTO produit (nom, id_categorie, prix_reference) VALUES ('$nom', '$categorie', '$price')";
    return mysqli_query($connect, $sql);
}

function get_produit($id_produit) {
    $sql = "SELECT * FROM produit WHERE id_produit = '$id_produit'";
    return get_one_line($sql);
}

function modifier_produit($id_produit, $nom, $categorie, $price, $perime) {
    $sql = "UPDATE produit SET nom = '$nom', id_categorie = '$categorie', prix_reference = '$price', perime = '$perime' WHERE id_produit = '$id_produit'";
    return mysqli_query($connect, $sql);
}