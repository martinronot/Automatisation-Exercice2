# Annuaire d'entreprise

Ce projet est un petit annuaire d'entreprises.  
Il permet de lister plusieurs entreprises, et d'en voir leur bureaux et employées.

## Technologies utilisées
- PHP 8.4
- MariaDB 11
- Slim 4
- Eloquent 11
- Vite (pour la gestion des assets)

## Préréquis pour une installation locale
- Docker
- Docker compose
- Git
- Node.js (pour le développement front-end)

## Installation locale
1) Cloner le projet

2) Copier le fichier .env.example en .env, et l'alimenter 
```bash
cp .env.example .env
```

3) Installer les dépendances PHP et Node.js
```bash
# Dépendances PHP
docker compose run --rm php composer install

# Dépendances Node.js
npm install
```

4) Compiler les assets
```bash
# Pour le développement (avec hot reload)
npm run dev

# Pour la production
npm run build
```

5) Lancer le container  
```bash
docker compose up
```

## (re)Créer et alimenter la base de données
Il faut que le container database soit lancé pour effectuer ces commandes.
 
**Supprimer et re-créer la base de données**  
```bash
docker compose exec php bin/console db:create
```

**Alimenter la base de données**  
```bash
docker compose exec php bin/console db:populate 
```

## Structure du projet
- **assets** : Contient les fichiers source JS et CSS
- **bin** : Contient le script permettant de lancer des commandes
- **config** : Contient les fichiers de configuration de l'application
- **public** : Contient les fichiers accessibles publiquement
    - **build** : Contient les assets compilés par Vite
- **src** : Contient le code source de l'application
    - **Console** : Contient les commandes de l'application
    - **Controller** : Contient les contrôleurs de l'application
    - **Models** : Contient les modèles de l'application
    - **Twig** : Contient les extension Twig de l'application
- **view** : Contient les fichiers .twig de l'application

## Développement front-end
Le projet utilise Vite pour la gestion des assets (JS et CSS). Les fichiers source se trouvent dans le dossier `assets` et sont compilés dans `public/build`.

### Commandes disponibles
- `npm run dev` : Lance le serveur de développement avec hot reload
- `npm run build` : Compile les assets pour la production
- `npm run lint` : Vérifie le code JavaScript avec ESLint
- `npm run lint:fix` : Corrige automatiquement les erreurs ESLint

## Exercices
Voir le fichier [TODO.md](TODO.md)