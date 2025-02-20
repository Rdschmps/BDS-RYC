# Projet Ymerssion - Étapes d'initialisation

Ce projet utilise **Symfony** comme framework PHP. Voici les étapes à suivre pour initialiser et configurer correctement le projet après avoir cloné ou récupéré les dernières modifications.

## Prérequis

Avant de commencer, assure-toi d'avoir installé les outils suivants :

- **PHP** (version 8.x ou supérieure)
- **Composer** (gestionnaire de dépendances PHP)
- **Node.js** et **NPM** ou **Yarn** (Nous utilisons **NPM** dans ce projet)
- **MySQL** (ou tout autre système de gestion de base de données compatible)

## Étapes d'initialisation

### 1. Cloner le projet

Si ce n'est pas déjà fait, clone le projet depuis le dépôt Git :

```bash
git clone https://github.com/Rdschmps/BDS-RYC.git
cd BDS-RYC
```

### 2. Configuration de l'environnement

Le fichier `.env.local` contient les variables d'environnement pour le projet, comme les informations de base de données, les clés API, etc.

- Si tu n'as pas de fichier `.env.local`, copie le fichier `.env` vers `.env.local` :

  ```bash
  cp .env .env.local
  ```

Ensuite, modifie les lignes concernant la base de données en supprimant le commentaire pour activer la connexion (il est recommandé de créer un utilisateur spécifique pour la base de données plutôt que d'utiliser **root**). Voici les lignes à modifier dans le fichier `.env.local` :
![Image lignes du .env.local à modifier](assets/imgReadme/bddLines.png)

Enfin, ajoute la clé publique pour Stripe dans le fichier `.env.local` :

```bash
STRIPE_PUBLIC_KEY=pk_test_51QtsanEIP0qBpZTPWl4gK2O6mmvOouoydK2Is8RCSRD71DiWnPU3UWr0TwkQTCa7vQZfltDhdFPaWz8fR147wJ3c00nvjVMxMh
```

Si tu veux tester l'achat complet, ajoute également ta clé privée Stripe.

### 3. Installer les dépendances PHP

Installe les dépendances PHP avec Composer :

```bash
composer update
```

Puis, pour installer toutes les dépendances nécessaires :

```bash
composer install
```

Cela installera toutes les dépendances PHP pour le bon fonctionnement de l'application.

### 4. Installer les dépendances JavaScript

Pour installer les dépendances front-end, utilise **NPM** :

```bash
npm install
```

### 5. Configurer la base de données

Pour configurer ta base de données, applique d'abord les migrations pour créer la base de données :

```bash
php bin/console doctrine:database:create
```

Ensuite, crée les tables en appliquant les migrations :

```bash
php bin/console doctrine:migrations:migrate
```

### 6. Démarrer le serveur de développement

Tu peux démarrer le serveur de développement intégré de Symfony pour voir si tout fonctionne correctement :

```bash
symfony server:start
```

Dans un autre terminal, pour voir les modifications en direct avec NPM, lance :

```bash
npm run watch
```

Ou, pour démarrer en mode développement :

```bash
npm run dev
```

Cela lancera le serveur à l'adresse [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## Commandes utiles

- `php bin/console doctrine:database:create` - Crée la base de données.
- `php bin/console doctrine:migrations:migrate` - Applique les migrations pour créer les tables.

## Dépendances

Le projet utilise les dépendances suivantes :

- **Symfony** : Framework PHP
- **Composer** : Gestionnaire de dépendances PHP
- **NPM/Yarn** : Gestionnaire de dépendances JavaScript

---

Si tu rencontres des problèmes, vérifie que tous les services (base de données, serveur de cache, etc.) sont bien en cours d'exécution et que la configuration dans le fichier `.env.local` est correcte.

---

Bonne chance et bon développement !

---

### Explication des sections :

1. **Cloner le projet** : Première étape pour récupérer le code source.
2. **Installer les dépendances PHP et JavaScript** : Installation des dépendances via Composer pour PHP et NPM/Yarn pour les dépendances front-end.
3. **Configurer l'environnement** : Configuration du fichier `.env.local` avec les paramètres nécessaires.
4. **Configurer la base de données** : Application des migrations pour créer la base de données et les tables.
5. **Vider le cache et les vues** : Commandes pour assurer que Symfony utilise la dernière configuration et vue.
6. **Démarrer le serveur de développement** : Lancement du serveur intégré de Symfony pour tester l'application.
