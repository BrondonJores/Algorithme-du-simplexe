# Algorithme du Simplexe

Application web PHP qui permet de resoudre des problemes de programmation lineaire avec la methode du simplexe, en affichant les tableaux intermediaires et la solution finale.

## Apercu du projet

Ce depot contient une interface web en 2 pages :

- `index.php` : page de presentation (explication + exemple pas a pas)
- `solveur.php` : interface de saisie dynamique du probleme
- `algo_simplexe.php` : moteur de calcul du simplexe (retour JSON)
- `style.css` : style global (theme neon, animations, mise en page)

Le front genere les formulaires selon le nombre de variables/contraintes, envoie les données à `algo_simplexe.php` via `fetch`, puis affiche chaque tableau de pivot et la solution optimale.

## Fonctionnalites

- Configuration dynamique :
- nombre de variables (1 a 20)
- nombre de contraintes (1 a 20)
- type d'optimisation (max ou min)
- Generation automatique du formulaire de fonction objectif et contraintes
- Resolution cote serveur en PHP
- Affichage pas a pas des iterations du simplexe
- Affichage de la solution finale (`x1...xn`, variables d'ecart de base, et `Z`)
- Interface visuelle animee (canvas, effets, tableaux stylises)

## Stack technique

- PHP 8+
- HTML5 / CSS3
- JavaScript vanilla (DOM + Fetch API)
- Font Awesome (CDN)
- Google Fonts (Rajdhani via CDN)

## Architecture fonctionnelle

1. L'utilisateur configure le probleme dans `solveur.php`.
2. Le JavaScript construit un formulaire de coefficients.
3. A la soumission, les donnees sont envoyees en `POST` a `algo_simplexe.php`.
4. Le serveur :
- lit les coefficients
- ajoute les variables d'ecart (cas `<=`)
- construit le tableau initial
- effectue les pivots jusqu'au critere d'arret
- renvoie `steps` + `solution` en JSON
5. Le navigateur affiche chaque etape et un bloc de solution finale.

## Lancer le projet en local

### Option 1 - Serveur PHP integre

```bash
php -S localhost:8000
```

Puis ouvrir :

- `http://localhost:8000/index.php`

### Option 2 - WAMP/XAMPP

1. Copier le projet dans le dossier web (`www` ou `htdocs`).
2. Demarrer Apache.
3. Ouvrir `index.php` dans le navigateur.

## Exemple d'utilisation

1. Aller sur `solveur.php`.
2. Choisir le nombre de variables et de contraintes.
3. Saisir les coefficients de la fonction objectif.
4. Saisir les contraintes (format `<=`).
5. Cliquer sur "Resoudre le probleme".
6. Lire les tableaux intermediaires puis la solution finale.

## Format des donnees envoyees

Exemple simplifie du `POST` :

- `numVars`, `numConstr`, `optType`
- `obj_x1`, `obj_x2`, ...
- `c11`, `c12`, ... (coeffs des contraintes)
- `rhs1`, `rhs2`, ... (seconds membres)

## Format de la reponse JSON

`algo_simplexe.php` renvoie :

```json
{
  "steps": [
    {
      "table": [["Base", "x1", "..."], ["E1", 1, "..."]],
      "desc": "Tableau initial"
    }
  ],
  "solution": {
    "x1": 2,
    "x2": 2,
    "Z": 10
  }
}
```

## Limites actuelles

- Les contraintes sont traitees uniquement au format `<=`.
- Pas de gestion avancee des cas degeneres / cyclage.
- Le cas non borne est arrete sans message explicite cote interface.
- Les textes de certaines pages montrent des problemes d'encodage selon l'environnement.

## Pistes d'amelioration

- Ajouter le support des contraintes `>=` et `=`.
- Clarifier les messages d'erreur (non borne, infaisable, donnees invalides).
- Ajouter des tests unitaires PHP pour le solveur.
- Isoler la logique simplexe dans une classe/service dedie.
- Corriger l'encodage UTF-8 sur tous les fichiers.

## Auteur

Projet academique : [BrondonJores](https://github.com/BrondonJores)
