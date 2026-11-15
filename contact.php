<?php
// Formulaire de contact : envoi vers contact@jeromemichaud.art (fonction mail() de l'hebergement OVH)
// Anti-robots : champ piege "site_web" et delai minimal de 3 secondes.
$to =  + to + r;
$back = 'contact.html';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . $back); exit; }
$f = function ($k) { return trim(str_replace(array("", "
"), ' ', isset($_POST[$k]) ? (string)$_POST[$k] : '')); };
$prenom = $f('prenom'); $nom = $f('nom'); $email = $f('email');
$message = trim(isset($_POST['message']) ? (string)$_POST['message'] : '');
$piege = $f('site_web'); $t = (int)(isset($_POST['t']) ? $_POST['t'] : 0);
if ($piege !== '' || $t === 0 || (time() - $t) < 3) { header('Location: ' . $back . '?envoye=1'); exit; }
if ($prenom === '' || $nom === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($message) > 5000) {
  header('Location: ' . $back . '?erreur=1'); exit;
}
$sujet = '=?UTF-8?B?' . base64_encode('[jeromemichaud.art] Message de ' . $prenom . ' ' . $nom) . '?=';
$corps = "Message envoye depuis le formulaire du site.\n\nDe : $prenom $nom <$email>\n\n$message\n";
$entetes = "From: $to\r\nReply-To: $email\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit";
$ok = @mail($to, $sujet, $corps, $entetes, '-f' . $to);
header('Location: ' . $back . ($ok ? '?envoye=1' : '?erreur=1')); exit;
