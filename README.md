# Black Manor — Site Vitrine

Projet de site vitrine pour le studio de tatouage **Black Manor**.

## Structure du projet

```
black-manor/
├── index.html              ← Page principale (toutes sections)
├── css/
│   └── style.css           ← Styles complets (palette, composants, responsive)
├── js/
│   └── main.js             ← Navigation mobile, AJAX form, animations scroll
├── php/
│   └── send-project.php    ← Traitement formulaire → email blackmanorttt@gmail.com
├── images/                 ← À remplir avec vos photos (artistes, flash, shop…)
│   ├── artiste-1.jpg
│   ├── artiste-2.jpg
│   ├── flash.jpg
│   ├── carte-cadeau.jpg
│   ├── tshirt.jpg
│   └── prints.jpg
└── uploads/                ← Créé automatiquement — fichiers clients (références)
```

## Palette de couleurs

| Nom          | Hex       |
|--------------|-----------|
| Shadow Grey  | `#211E1E` |
| Deep Mocha   | `#382F2E` |
| Taupe        | `#8A756A` |
| Snow         | `#F7F0F0` |

## Fonctionnalités

- ✅ Navigation fixe avec menu mobile (burger)
- ✅ Dropdowns pour « Les Tatoueurs » et « Tatouage Esthétique »
- ✅ Section Shop avec horaires, adresse et Google Maps embed
- ✅ Section Artistes (cartes avec photo, spécialités, lien Instagram)
- ✅ Section Tatouage Esthétique « Bientôt disponible »
- ✅ Section Shopping (Flash, Cartes Cadeau, T-shirts, Prints)
- ✅ FAQ accordéon native HTML (`<details>`)
- ✅ Formulaire projet complet (AJAX) → email vers `blackmanorttt@gmail.com`
  - Champs : identité, artiste, style, zone, taille, couleur, description, références (upload images), disponibilités, budget
  - Accusé de réception automatique au client
- ✅ Animations au scroll (Intersection Observer)
- ✅ Responsive mobile

## Mise en ligne

### Hébergement PHP requis
Le formulaire PHP nécessite un hébergeur avec PHP 7.4+.  
Hébergeurs compatibles : OVH, o2switch, Infomaniak, Ionos, etc.

### Étapes

1. **Téléverser** tous les fichiers sur votre hébergeur (FTP ou panneau de contrôle).
2. **Images** : remplacer les fichiers placeholder dans `/images/`.
3. **Google Maps** : dans `index.html`, remplacer l'`iframe` src par l'embed URL réelle du studio :
   - Google Maps → Partager → Intégrer une carte → Copier l'URL iframe
4. **Artistes** : dupliquer les blocs `<article class="artist-card">` dans `index.html` et adapter noms/pseudos.
5. **PHP + Gmail** : pour une fiabilité maximale, activer PHPMailer + SMTP (instructions en bas de `php/send-project.php`).
6. **Permissions uploads** : `chmod 755 uploads/` sur le serveur.

### Test du formulaire en local

```bash
php -S localhost:8000
# Ouvrir http://localhost:8000
```
> Note : `mail()` ne fonctionne pas en local. Utiliser Mailpit ou PHPMailer/Mailtrap pour tester.

## Personnalisation rapide

- **Nom des artistes** : chercher `Prénom Nom` dans `index.html`
- **Pseudos Instagram** : chercher `PSEUDO` dans `index.html`
- **Adresse** : chercher `123 Rue du Tatouage`
- **Horaires** : section `#shop` dans `index.html`
- **Options artistes dans le formulaire** : `<select id="artiste">`
