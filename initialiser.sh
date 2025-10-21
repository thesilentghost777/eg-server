#!/bin/bash

# Script d'initialisation du backend - Boulangerie Pâtisserie
echo "Initialisation du backend..."

# Models
echo "Création des modèles..."
php artisan make:model Produit
php artisan make:model VendeurActif
php artisan make:model ReceptionPointeur
php artisan make:model RetourProduit
php artisan make:model Inventaire
php artisan make:model InventaireDetail
php artisan make:model SessionVente

# Services
echo "Création des services..."
mkdir -p app/Services
touch app/Services/ProduitService.php
touch app/Services/ReceptionService.php
touch app/Services/RetourService.php
touch app/Services/InventaireService.php
touch app/Services/SessionVenteService.php
touch app/Services/FluxProduitService.php
touch app/Services/AuthService.php
touch app/Services/UserService.php

# Requests
echo "Création des requests..."
php artisan make:request CreateProduitRequest
php artisan make:request CreateReceptionRequest
php artisan make:request CreateRetourRequest
php artisan make:request CreateInventaireRequest
php artisan make:request CreateSessionRequest
php artisan make:request FermerSessionRequest
php artisan make:request InscriptionRequest

# Controllers API
echo "Création des contrôleurs API..."
php artisan make:controller Api/AuthApiController --api
php artisan make:controller Api/ProduitApiController --api
php artisan make:controller Api/ReceptionApiController --api
php artisan make:controller Api/RetourApiController --api
php artisan make:controller Api/InventaireApiController --api
php artisan make:controller Api/SessionVenteApiController --api
php artisan make:controller Api/FluxProduitApiController --api
php artisan make:controller Api/UserApiController --api

# Middleware
echo "Création du middleware..."
php artisan make:middleware CheckRole

echo "Initialisation terminée!"
echo "N'oubliez pas de:"
echo "1. Exécuter: php artisan migrate"
echo "2. Configurer les routes dans routes/api.php"
echo "3. Enregistrer le middleware dans app/Http/Kernel.php"
