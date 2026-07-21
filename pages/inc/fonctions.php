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

function produit_membres($id_categorie = null, $id_produit = null){
    $sql = "SELECT 
                produit_membre.id_produit_membre,
                produit.id_produit,
                produit.nom AS nom_produit,
                produit_membre.prix_vente AS prix,
                membre.nom AS nom_membre,
                produit_membre.quantite_dispo,
                produit_membre.date_dispo,
                produit_membre.image,
                categorie.id_categorie as id_categorie,
                categorie.nom_categorie,
                produit.perime 
            FROM produit_membre
            JOIN membre ON produit_membre.id_membre = membre.id_membre
            JOIN produit ON produit_membre.id_produit = produit.id_produit
            JOIN categorie ON produit.id_categorie = categorie.id_categorie";

    $conditions = array();
    if (!empty($id_categorie)) {
        $conditions[] = "categorie.id_categorie = '" . (int) $id_categorie . "'";
    }
    if (!empty($id_produit)) {
        $conditions[] = "produit.id_produit = '" . (int) $id_produit . "'";
    }
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(' AND ', $conditions);
    }

    $result = get_all_lines($sql);
    return $result;
}

function get_produit_by_categorie($id_categorie){
    $id_categorie = (int) $id_categorie;
    $sql = "SELECT * FROM produit WHERE id_categorie = '$id_categorie' ORDER BY nom";
    return get_all_lines($sql);
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

function vente ($etu, $produit, $price, $quantite, $date_dispo, $image = ''){
    ensure_image_columns();
    $connect = dbconnect();
    $etu = mysqli_real_escape_string($connect, $etu);
    $id_produit = (int)$produit;
    $price = mysqli_real_escape_string($connect, $price);
    $quantite = mysqli_real_escape_string($connect, $quantite);
    $date_dispo = mysqli_real_escape_string($connect, $date_dispo);

    if (empty($image)) {
        $info_produit = get_produit($id_produit);
        if ($info_produit) {
            $id_cat = (int)$info_produit['id_categorie'];
            if ($id_cat == 1) {
                $image = 'plat_default.jpg'; 
            } elseif ($id_cat == 2) {
                $image = 'boisson_default.jpg';
            } elseif ($id_cat == 3) {
                $image = 'snak_default.jpg';
            } elseif ($id_cat == 4) {
                $image = 'dessert_default.jpg';
            } else {
                $image = 'default.png';
            }
        } else {
            $image = 'default.png';
        }
    }

    $image = mysqli_real_escape_string($connect, $image);
    $sql = "INSERT INTO produit_membre (id_membre, id_produit, prix_vente, quantite_dispo, date_dispo, image) 
            VALUES (
                (SELECT id_membre FROM membre WHERE numero_etu = '$etu'), 
                '$id_produit', 
                '$price', 
                '$quantite', 
                '$date_dispo', 
                '$image'
            )";
            
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

// --- Statistiques : ventes par categorie -> par produit -> par membre ---

function stats_par_categorie(){
    $sql = "SELECT c.id_categorie, c.nom_categorie,
                COALESCE(SUM(pm.prix_vente * v.quantite), 0) AS total_ventes,
                COALESCE(SUM(v.quantite), 0) AS quantite_vendue
            FROM categorie c
            LEFT JOIN produit p ON p.id_categorie = c.id_categorie
            LEFT JOIN produit_membre pm ON pm.id_produit = p.id_produit
            LEFT JOIN vente v ON v.id_produit_membre = pm.id_produit_membre
            GROUP BY c.id_categorie, c.nom_categorie
            ORDER BY total_ventes DESC";
    return get_all_lines($sql);
}

function stats_par_produit($id_categorie){
    $id_categorie = (int) $id_categorie;
    $sql = "SELECT p.id_produit, p.nom,
                COALESCE(SUM(pm.prix_vente * v.quantite), 0) AS total_ventes,
                COALESCE(SUM(v.quantite), 0) AS quantite_vendue
            FROM produit p
            LEFT JOIN produit_membre pm ON pm.id_produit = p.id_produit
            LEFT JOIN vente v ON v.id_produit_membre = pm.id_produit_membre
            WHERE p.id_categorie = '$id_categorie'
            GROUP BY p.id_produit, p.nom
            ORDER BY total_ventes DESC";
    return get_all_lines($sql);
}

function stats_par_membre($id_produit){
    $id_produit = (int) $id_produit;
    $sql = "SELECT m.id_membre, m.nom,
                COALESCE(SUM(pm.prix_vente * v.quantite), 0) AS total_ventes,
                COALESCE(SUM(v.quantite), 0) AS quantite_vendue
            FROM membre m
            JOIN produit_membre pm ON pm.id_membre = m.id_membre
            LEFT JOIN vente v ON v.id_produit_membre = pm.id_produit_membre
            WHERE pm.id_produit = '$id_produit'
            GROUP BY m.id_membre, m.nom
            ORDER BY total_ventes DESC";
    return get_all_lines($sql);
}

function get_categorie($id_categorie){
    $id_categorie = (int) $id_categorie;
    $sql = "SELECT * FROM categorie WHERE id_categorie = '$id_categorie'";
    return get_one_line($sql);
}

function get_produit($id_produit){
    $id_produit = (int) $id_produit;
    $sql = "SELECT * FROM produit WHERE id_produit = '$id_produit'";
    return get_one_line($sql);
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
    $image_par_defaut = '';

    if (!isset($_FILES['image'])) {
        return $image_par_defaut;
    }

    $file = $_FILES['image'];

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return $image_par_defaut;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Erreur lors de l'upload : " . $file['error'];
        exit();
    }

    $maxSize = 2 * 1024 * 1024; 
    if ($file['size'] > $maxSize) {
        echo "Le fichier est trop volumineux (2 Mo maximum).";
        exit();
    }

    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowedMimeTypes)) {
            echo "Type de fichier non autorisé : " . htmlspecialchars($mime);
            exit();
        }
    }

    $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    
    $originalName = preg_replace('/[^a-zA-Z0-9_-]/', '', $originalName);
    $newName = $prefix . '_' . $originalName . '_' . uniqid() . '.' . $extension;

    $uploadDir = __DIR__ . '/../assets/img/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
        return $newName;
    } else {
        echo "Échec du déplacement du fichier vers : " . htmlspecialchars($uploadDir);
        exit();
    }
}

function ajout_produit($nom, $id_categorie, $prix_reference, $perime = 0) {
    $connect = dbconnect();
    $nom = mysqli_real_escape_string($connect, $nom);
    $id_categorie = (int)$id_categorie;
    $prix_reference = (float)$prix_reference;
    $perime = $perime ? 1 : 0;
    
    $sql = "INSERT INTO produit (nom, id_categorie, prix_reference, perime) VALUES ('$nom', '$id_categorie', '$prix_reference', '$perime')";
    return mysqli_query($connect, $sql);
}

function modifier_produit($id_produit, $nom, $id_categorie, $prix_reference, $perime = 0) {
    $connect = dbconnect();
    $id_produit = (int)$id_produit;
    $nom = mysqli_real_escape_string($connect, $nom);
    $id_categorie = (int)$id_categorie;
    $prix_reference = (float)$prix_reference;
    $perime = $perime ? 1 : 0;
    
    $sql = "UPDATE produit SET nom = '$nom', id_categorie = '$id_categorie', prix_reference = '$prix_reference', perime = '$perime' WHERE id_produit = '$id_produit'";
    return mysqli_query($connect, $sql);
}