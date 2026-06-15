<?php
/**
 * Black Manor — Formulaire Projet
 * Envoi vers : blackmanorttt@gmail.com
 *
 * Prérequis serveur :
 *   - PHP 7.4+
 *   - Extension mbstring activée
 *   - Fonction mail() ou PHPMailer (recommandé pour SMTP)
 *
 * Pour utiliser PHPMailer (fortement recommandé avec Gmail) :
 *   composer require phpmailer/phpmailer
 *   puis décommenter la section PHPMailer ci-dessous
 */

header('Content-Type: application/json; charset=utf-8');

// ── Sécurité : méthode POST uniquement ──
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// ── Fonction de nettoyage ──
function clean(string $value): string {
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

// ── Récupération et validation des champs ──
$errors = [];

$prenom      = clean($_POST['prenom']      ?? '');
$nom         = clean($_POST['nom']         ?? '');
$email       = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$telephone   = clean($_POST['telephone']   ?? '');
$artiste     = clean($_POST['artiste']     ?? 'Pas de préférence');
$style       = clean($_POST['style']       ?? '');
$zone        = clean($_POST['zone']        ?? '');
$taille      = clean($_POST['taille']      ?? '');
$couleur     = clean($_POST['couleur']     ?? '');
$description = clean($_POST['description'] ?? '');
$premiere    = clean($_POST['premiere-fois'] ?? '');
$disponible  = clean($_POST['disponibilite'] ?? '');
$budget      = clean($_POST['budget']      ?? 'Non renseigné');
$message     = clean($_POST['message']     ?? '');

if (empty($prenom))      $errors[] = 'Le prénom est requis';
if (empty($nom))         $errors[] = 'Le nom est requis';
if (!$email)             $errors[] = 'Email invalide';
if (empty($style))       $errors[] = 'Le style est requis';
if (empty($zone))        $errors[] = 'La zone du corps est requise';
if (empty($taille))      $errors[] = 'La taille approximative est requise';
if (empty($description)) $errors[] = 'La description du projet est requise';

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// ── Gestion des fichiers joints ──
$uploadDir     = __DIR__ . '/../uploads/';
$allowedTypes  = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
$maxFileSize   = 10 * 1024 * 1024; // 10 Mo
$uploadedFiles = [];

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!empty($_FILES['references']['name'][0])) {
    foreach ($_FILES['references']['tmp_name'] as $i => $tmpName) {
        if ($_FILES['references']['error'][$i] !== UPLOAD_ERR_OK) continue;
        if ($_FILES['references']['size'][$i] > $maxFileSize) continue;

        $mimeType = mime_content_type($tmpName);
        if (!in_array($mimeType, $allowedTypes, true)) continue;

        $ext        = pathinfo($_FILES['references']['name'][$i], PATHINFO_EXTENSION);
        $safeFile   = 'ref_' . time() . '_' . $i . '.' . strtolower($ext);
        $destPath   = $uploadDir . $safeFile;

        if (move_uploaded_file($tmpName, $destPath)) {
            $uploadedFiles[] = $destPath;
        }
    }
}

// ── Construction de l'email HTML ──
$to      = 'blackmanorttt@gmail.com';
$subject = '=?UTF-8?B?' . base64_encode('Nouveau projet — ' . $prenom . ' ' . $nom) . '?=';

$body = "
<!DOCTYPE html>
<html lang='fr'>
<head><meta charset='UTF-8'><style>
  body{font-family:Georgia,serif;background:#f7f0f0;color:#211e1e;margin:0;padding:0}
  .wrap{max-width:620px;margin:0 auto;background:#fff;border:1px solid #e0d8d5}
  .header{background:#211e1e;padding:2rem;text-align:center}
  .header h1{color:#f7f0f0;font-size:1.5rem;letter-spacing:0.2em;margin:0}
  .header p{color:#8a756a;font-size:0.8rem;margin:0.25rem 0 0;letter-spacing:0.1em}
  .body{padding:2rem}
  .section{margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid #e0d8d5}
  .section:last-child{border:none}
  h2{font-size:0.75rem;letter-spacing:0.2em;text-transform:uppercase;color:#8a756a;margin:0 0 0.75rem}
  table{width:100%;border-collapse:collapse}
  td{padding:0.4rem 0;font-size:0.9rem;vertical-align:top}
  td:first-child{width:40%;font-weight:600;color:#382f2e}
  .description{background:#faf7f5;border-left:3px solid #8a756a;padding:1rem;font-size:0.9rem;line-height:1.7}
  .footer{background:#211e1e;padding:1rem 2rem;text-align:center;color:#6b5e58;font-size:0.75rem}
</style></head>
<body>
<div class='wrap'>
  <div class='header'>
    <h1>BLACK MANOR</h1>
    <p>Nouveau projet de tatouage</p>
  </div>
  <div class='body'>

    <div class='section'>
      <h2>Coordonnées</h2>
      <table>
        <tr><td>Prénom / Nom</td><td>" . $prenom . " " . $nom . "</td></tr>
        <tr><td>Email</td><td><a href='mailto:" . $email . "'>" . $email . "</a></td></tr>
        <tr><td>Téléphone</td><td>" . ($telephone ?: 'Non renseigné') . "</td></tr>
      </table>
    </div>

    <div class='section'>
      <h2>Projet</h2>
      <table>
        <tr><td>Artiste souhaité</td><td>" . $artiste . "</td></tr>
        <tr><td>Style</td><td>" . $style . "</td></tr>
        <tr><td>Zone du corps</td><td>" . $zone . "</td></tr>
        <tr><td>Taille</td><td>" . $taille . "</td></tr>
        <tr><td>Couleur</td><td>" . $couleur . "</td></tr>
        <tr><td>Première fois</td><td>" . ($premiere === 'oui' ? 'Oui — premier tatouage' : 'Non') . "</td></tr>
      </table>
    </div>

    <div class='section'>
      <h2>Description du projet</h2>
      <div class='description'>" . nl2br($description) . "</div>
    </div>

    <div class='section'>
      <h2>Disponibilités &amp; Budget</h2>
      <table>
        <tr><td>Disponibilités</td><td>" . ($disponible ?: 'Non précisé') . "</td></tr>
        <tr><td>Budget</td><td>" . $budget . "</td></tr>
      </table>
    </div>

    " . (!empty($message) ? "
    <div class='section'>
      <h2>Informations complémentaires</h2>
      <div class='description'>" . nl2br($message) . "</div>
    </div>" : '') . "

    " . (!empty($uploadedFiles) ? "
    <div class='section'>
      <h2>Références jointes</h2>
      <p>" . count($uploadedFiles) . " fichier(s) reçu(s) et sauvegardé(s) sur le serveur.</p>
    </div>" : '') . "

  </div>
  <div class='footer'>Black Manor — blackmanorttt@gmail.com</div>
</div>
</body></html>
";

// ── Envoi (mail() natif) ──
// Pour production avec Gmail, utiliser PHPMailer/SMTP (voir ci-dessous)

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: Black Manor Site <noreply@blackmanor.fr>\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

$sent = mail($to, $subject, $body, $headers);

// ── Envoi accusé de réception au client ──
if ($sent) {
    $ackBody = "
    <html><body style='font-family:Georgia,serif;color:#211e1e;background:#f7f0f0;padding:2rem'>
    <div style='max-width:540px;margin:0 auto;background:#fff;padding:2rem;border:1px solid #e0d8d5'>
      <h1 style='font-size:1.3rem;letter-spacing:0.15em;color:#211e1e'>Merci " . $prenom . " !</h1>
      <p>Votre projet a bien été reçu par l'équipe <strong>Black Manor</strong>.</p>
      <p>Nous étudions votre demande et vous répondons sous <strong>48h ouvrées</strong>.</p>
      <p style='color:#8a756a;font-size:0.85rem;margin-top:2rem'>À bientôt,<br>L'équipe Black Manor</p>
    </div>
    </body></html>
    ";
    $ackHeaders  = "MIME-Version: 1.0\r\n";
    $ackHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";
    $ackHeaders .= "From: Black Manor <blackmanorttt@gmail.com>\r\n";
    mail($email, '=?UTF-8?B?' . base64_encode('Votre projet — Black Manor') . '?=', $ackBody, $ackHeaders);

    echo json_encode(['success' => true, 'message' => 'Email envoyé avec succès']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Échec de l\'envoi. Contactez-nous directement par email.']);
}

/*
 * ─────────────────────────────────────────────────────────────────────────────
 * ALTERNATIVE : PHPMailer + SMTP Gmail (recommandé en production)
 * ─────────────────────────────────────────────────────────────────────────────
 * 1. Installer : composer require phpmailer/phpmailer
 * 2. Créer un "App Password" Gmail (Compte > Sécurité > Mots de passe d'application)
 * 3. Remplacer le bloc mail() par :
 *
 * require __DIR__ . '/../vendor/autoload.php';
 * use PHPMailer\PHPMailer\PHPMailer;
 *
 * $mail = new PHPMailer(true);
 * $mail->isSMTP();
 * $mail->Host       = 'smtp.gmail.com';
 * $mail->SMTPAuth   = true;
 * $mail->Username   = 'blackmanorttt@gmail.com';
 * $mail->Password   = 'VOTRE_APP_PASSWORD';
 * $mail->SMTPSecure = 'tls';
 * $mail->Port       = 587;
 * $mail->CharSet    = 'UTF-8';
 * $mail->setFrom('blackmanorttt@gmail.com', 'Black Manor');
 * $mail->addAddress('blackmanorttt@gmail.com', 'Black Manor');
 * $mail->addReplyTo($email, $prenom . ' ' . $nom);
 * $mail->isHTML(true);
 * $mail->Subject = 'Nouveau projet — ' . $prenom . ' ' . $nom;
 * $mail->Body    = $body;
 * foreach ($uploadedFiles as $file) {
 *     $mail->addAttachment($file);
 * }
 * $mail->send();
 * ─────────────────────────────────────────────────────────────────────────────
 */
?>
