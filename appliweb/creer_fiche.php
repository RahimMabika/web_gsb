<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=frais_app;charset=utf8', 'root', '');
$user_id = $_SESSION['user']['id']; // 🔒 L'ID du visiteur connecté

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1️⃣ Créer la fiche de frais
    $stmt = $pdo->prepare("INSERT INTO fiches_frais (user_id, type, montant, date, statut) VALUES (:user_id, :type, :montant, :date, :statut)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':type' => 'forfaitaire', // ou autre si tu veux le rendre modifiable
        ':montant' => $_POST['total_general'],
        ':date' => $_POST['date_fiche'],
        ':statut' => 'en attente',
    ]);
    $fiche_id = $pdo->lastInsertId();

    // 2️⃣ Enregistrer les lignes de frais
    foreach ($_POST['date'] as $index => $date_frais) {
        $desc = $_POST['description'][$index];
        $deplacement = $_POST['deplacement'][$index];
        $repas = $_POST['repas'][$index];
        $hebergement = $_POST['hebergement'][$index];
        $autres = $_POST['autres'][$index];
        
        // Insertion de chaque frais par catégorie
        $frais = [
            ['categorie' => 'Déplacement', 'montant' => $deplacement],
            ['categorie' => 'Repas', 'montant' => $repas],
            ['categorie' => 'Hébergement', 'montant' => $hebergement],
            ['categorie' => 'Autres', 'montant' => $autres],
        ];

        foreach ($frais as $f) {
            if ($f['montant'] > 0) {
                $stmt = $pdo->prepare("INSERT INTO details_frais (fiche_id, date, description, categorie, montant) VALUES (:fiche_id, :date, :description, :categorie, :montant)");
                $stmt->execute([
                    ':fiche_id' => $fiche_id,
                    ':date' => $date_frais,
                    ':description' => $desc,
                    ':categorie' => $f['categorie'],
                    ':montant' => $f['montant'],
                ]);
            }
        }
    }

    echo "✅ Fiche créée avec succès !";
}
?>

<h2>Créer une fiche de frais</h2>
<form method="POST">
    Date de la fiche : <input type="date" name="date_fiche" required><br><br>

    <table border="1" id="tableFrais">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Déplacement (€)</th>
                <th>Repas (€)</th>
                <th>Hébergement (€)</th>
                <th>Autres (€)</th>
                <th>Sous-total (€)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="tbodyFrais">
            <tr>
                <td><input type="date" name="date[]"></td>
                <td><input type="text" name="description[]"></td>
                <td><input type="number" name="deplacement[]" step="0.01" value="0" oninput="calculer(this)"></td>
                <td><input type="number" name="repas[]" step="0.01" value="0" oninput="calculer(this)"></td>
                <td><input type="number" name="hebergement[]" step="0.01" value="0" oninput="calculer(this)"></td>
                <td><input type="number" name="autres[]" step="0.01" value="0" oninput="calculer(this)"></td>
                <td><input type="text" name="subtotal[]" readonly value="0"></td>
                <td><button type="button" onclick="supprimerLigne(this)">❌</button></td>
            </tr>
        </tbody>
    </table>
    <button type="button" onclick="ajouterLigne()">➕ Ajouter une ligne</button><br><br>

    Total général (€) : <input type="text" name="total_general" id="total_general" readonly value="0"><br><br>
    <input type="submit" value="Enregistrer la fiche">
</form>

<script>
function calculer(elem) {
    const row = elem.closest('tr');
    const deplacement = parseFloat(row.querySelector('input[name="deplacement[]"]').value) || 0;
    const repas = parseFloat(row.querySelector('input[name="repas[]"]').value) || 0;
    const hebergement = parseFloat(row.querySelector('input[name="hebergement[]"]').value) || 0;
    const autres = parseFloat(row.querySelector('input[name="autres[]"]').value) || 0;
    const subtotal = deplacement + repas + hebergement + autres;
    row.querySelector('input[name="subtotal[]"]').value = subtotal.toFixed(2);

    // Calcul total général
    let total = 0;
    document.querySelectorAll('input[name="subtotal[]"]').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    document.getElementById('total_general').value = total.toFixed(2);
}

function ajouterLigne() {
    const tbody = document.getElementById('tbodyFrais');
    const tr = tbody.rows[0].cloneNode(true);
    tr.querySelectorAll('input').forEach(input => {
        if (input.type !== 'text') input.value = (input.name.includes('subtotal')) ? '0' : '';
    });
    tbody.appendChild(tr);
}

function supprimerLigne(btn) {
    const tbody = document.getElementById('tbodyFrais');
    if (tbody.rows.length > 1) {
        btn.closest('tr').remove();
        calculer(document.querySelector('input[name="deplacement[]"]')); // recalcul total
    }
}
</script>
