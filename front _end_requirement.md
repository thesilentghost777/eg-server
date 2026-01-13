l'app pwa sera developper en react native + vite pwa (et sera utiliser en interne par les ipad exclusivement)
elle sera pro et excellement styliser et ultra responsive
elle doit etre parfaite sur tablette et ipad en prioriter mais aussi parfait sur mobile et pc
elle pourra gerer l'utilisation offline et la sync auto et manuelle
la strategie de sync est simple : des qu'on retourne au dashboard une sync est automatiquement tenter s'il y'a des donnees en attente
lors l'acces au dashboard apres premiere inscription l'app pwa va pull toute les donnees du serveur et stocker en local

et a chaque acces futur a cette vue l'app va essyer auto de pull et push .
le bouton de synchronisation manuelle fera la meme chose
on va developper une app pour une boulangerie patisserie
elle va fonctionner en offline 
le pdg utilisera sur sa machine (serveur)
le pointeur sur un ipad
les vendeurs sur un ipad
on va faire evoluer l'application au fur et a mesure

pour l'instant on veut la premiere fonctionnaliter (flux de produit patisserie)

premierement on a des produits (defini par leur nom , prix , categorie(boulangerie ou patisserie)

ensuite on a les pointeurs , les vendeurs et le pdg

les pointeur rexoivent les produits des producteurs et les passe au vendeurs direct
les pointeurs sont les hommes de confiance
il y'a un seul vendeur a la fois pour chaque categorie en place (xa veut dire qu'a un moment precis il y'a un seul vendeur qui vend pour chaque categorie " un seul patisserie et un seul boulangerie)

lorsque le pointeur declare qu'il a recu un produit d'un producteur(et c'est optionel si le pointeur ne veut pas specifier on va utiliser le producteur d'id 1 par defaut (cette logique sera implementer directement dans le front end)) on assigne directement le produit et la quantite recu au vendeur qui est entrain de travailler pour la categorie du produit
le pointeur enregistre aussi les retour de produits (les produits peuvent etre retourner pour differente raison (gater , perimer ...))
lorsque le pointeur enregistre un retour de produit le serveur responsable du retour est automatiquement le serveur connecter pour la categorie du produit en question et le retour est directement lier au serveur
le pointeur peut modifier a les infos relative a une reception (qui modifiera aussi au niveau du serveur)
mais il ne peut plus modifier lorsque le serveur n'est plus connecter ou lorsque un verrou logique a ete declencher (par le pdg dans ce cas))
le vendeur n'a que 3  chose a faire avec l'application 

l'inventaire est l'operation realiser a chaque fois qu'il y'a changement de vendeur:
il consiste au serveur sortant de preciser pour chaque produit la quantite laisser a son depart
le vendeur entrant voit alors cela et consulte la liste qui s'es former au fur et  a mesure de l'ajout et ensuite les deux valide l'operation en  entrant leur code pin respectif
a rappeler que c'est le serveur connecter qui fait l'inventaire et il est directement considerer comme le serveur sortant et il precise a chaque fois qui est le serveur entrant
et apres l'operation d'inventaire reussi on deconnecte directement le vendeur sortant et on connecte le vendeur entrant et le systeme le marque automatiquement comme le vendeur actif pour la categorie en question

chaque vendeur a deux operation d'inventaire un quand il arrive en tant qu'entrant et un quand il s'en va en tant que sortant

le vendeur peut creer session de vente qui est en effet une journee de vente
pour creer une session de vente le vendeur precise trois montant : le montant obtenu pour les ventes(fond de vente) , le montant trouver dans orange money , le montant trouver dans mtn money
par defaut tout ces montant sont a 0

la session de caisse est fermer par le pdg

enfin le serveur peut visualiser la liste des entrer de la journee (pour verifier que le pointeur ne se soit pas tromper )
il peut aussi visualiser ses retours (veux associer a lui)





## 🔄 Stratégie de Synchronisation
inscription et premiere connexion adsolument online
### Sync Initiale (Premier usage)
1. Connexion avec token
2. Télécharger tous les produits actifs
3. Télécharger liste producteurs
4. Télécharger vendeurs actifs par catégorie
5. Télécharger mes données historiques (7 derniers jours)

### Sync Régulière (A chaque acces au dashboard si la sync queu n'est pas vide)

#### 1. Upload (Envoyer modifications locales)
Parcourir la table `sync_queue` et envoyer dans l'ordre :
- Nouvelles réceptions/retours (`sync_status = 'pending'`)
- Nouveaux inventaires (`sync_status = 'pending'`)
- Nouvelles sessions ouvertes (`sync_status = 'pending'`)
- Modifications non synchronisées



#### 2. Download (Recevoir mises à jour)
Récupérer les données modifiées depuis `last_synced_at` :
- Nouveaux produits/modifications
- Nouveaux vendeurs actifs
- Verrouillages appliqués par PDG
- Sessions fermées par PDG
- Modifications de prix


### Gestion des Conflits
- **Last-write-wins** pour la plupart des cas
- **Priorité serveur** pour:
  - Statut actif/inactif des utilisateurs
  - Verrouillages (verrou = true)
  - Fermeture de sessions
  - Prix des produits
- **Notification utilisateur** en cas de conflit majeur

### Mode Offline
- Toutes les opérations fonctionnent en local
- Marquage `sync_status = 'pending'`
- Ajout dans `sync_queue`
- Queue de synchronisation au retour au dashboard
- Indication visuelle des données non synchronisées

---

## 📱 Écrans Principaux

### Écrans Communs

#### 1. Login
- Input: Numéro de téléphone
- Input: Code PIN (6 chiffres, masqué)
- Bouton: Se connecter
- Lien: Créer un compte
- Indicateur: État connexion réseau

#### 2. Inscription
- Input: Nom complet
- Input: Numéro de téléphone
- Select: Rôle (pointeur/vendeur_boulangerie/vendeur_patisserie)
- Input: Code PIN (6 chiffres, confirmation)
- Bouton: S'inscrire
- Note: Nécessite connexion Internet

---

### Écrans Pointeur

#### 3. Dashboard Pointeur
- **Header**: 
  - Icône réception (actif)
  - Icône retour
  - Icône déconnexion
  - Indicateur sync
- **Formulaire Réception Direct**:
  - Select: Producteur (select et par defaut le producteur d'id 1)
  - Select: Produit (recherche)
  - Input: Quantité (numérique)
  - Textarea: Notes (optionnel)
  - Bouton: Enregistrer réception
  - Info: Vendeur assigné (affiché automatiquement) et avec un effet attirant l'attention

#### 4. Mes Réceptions
- **Header**: 
  - Titre: Mes Réceptions
  - Filtre: Date picker
  - Indicateur: Nombre total
- **Liste**:
  - Carte par réception:
    - Produit (nom + icône)
    - Quantité
    - Producteur
    - Vendeur assigné
    - Heure
    - Badge: Verrouillée/Modifiable
    - Badge sync: Synced/Pending/Conflict
    - Action: Modifier (si non verrouillé)
- **Formulaire Modification** (modal):
  - Input: Quantité
  - Textarea: Notes
  - Boutons: Annuler / Sauvegarder

#### 5. Enregistrer Retour
- **Formulaire**:
  - Select: Produit (recherche)
  - Input: Quantité
  - Select: Raison (périssable/abîmé/autre)
  - Textarea: Description (optionnel)
  - Info: Vendeur concerné (auto)
  - Bouton: Enregistrer retour

#### 6. Mes Retours
- **Header**: 
  - Titre: Mes Retours
  - Filtre: Date picker
  - Indicateur: Nombre total
- **Liste**:
  - Carte par retour:
    - Produit (nom + icône)
    - Quantité
    - Raison
    - Vendeur concerné
    - Heure
    - Badge: Verrouillé/Modifiable
    - Badge sync
    - Action: Modifier (si non verrouillé)

---

### Écrans Vendeur

#### 7. Dashboard Vendeur
- **Header**: 
  - Titre: Mes Produits Reçus
  - Date du jour
  - Indicateur sync
- **Navigation Icônes**:
  - Inventaire
  - Session caisse
  - Visualiser réceptions
  - Visualiser retours
  - Mon flux
- **Liste Produits Reçus** (du jour):
  - Carte par produit:
    - Nom + icône
    - Quantité reçue
    - Producteur
    - Heure réception
    - Badge sync

#### 8. Mes Réceptions (Vendeur - Lecture Seule)
- **Header**: 
  - Titre: Réceptions pour moi
  - Filtre: Date picker
  - Indicateur: Quantité totale
- **Liste**:
  - Carte par réception:
    - Produit (nom + icône)
    - Quantité
    - Producteur
    - Pointeur
    - Heure
    - Note: Lecture seule

#### 9. Mes Retours (Vendeur - Lecture Seule)
- **Header**: 
  - Titre: Retours me concernant
  - Filtre: Date picker
  - Indicateur: Quantité totale
- **Liste**:
  - Carte par retour:
    - Produit (nom + icône)
    - Quantité
    - Raison
    - Pointeur
    - Heure
    - Description

#### 10. Créer Inventaire
- **Étape 1: Vendeur Sortant**:
  - Info: Vous êtes le vendeur sortant
  - Select: Vendeur entrant (même catégorie)
  - Liste produits avec input quantité restante (il y'a 125 produits de patisserie juste donc tu vas afficher les produits exactement comme dans l'ordre de la BD (car enregistrer suivant le classement physique sur place), et je ne sais pas : il faut ajouter sur la screen des outils qui facilite vraiment cela et qui rends l'app tellement bien a utiliser)
  - Bouton: Suivant

- **Étape 2: Validation Double**:
  - Résumé des quantités
  - Input: PIN vendeur sortant
  - Input: PIN vendeur entrant
  - Bouton: Valider inventaire
  - Info: Vous serez automatiquement déconnecté

- **Confirmation**:
  - Message succès
  - Info: Le vendeur entrant est maintenant actif
  - Auto-déconnexion après 3 secondes

#### 11. Ouvrir Session de Vente
- **Formulaire**:
  - Info: Catégorie (auto selon rôle)
  - Input: Fond de vente (XAF)
  - Input: Orange Money initial (XAF, défaut 0)
  - Input: MTN Money initial (XAF, défaut 0)
  - Bouton: Ouvrir session
- **Vérifications**:
  - Aucune session déjà ouverte
  - Synchronisation si en ligne

#### 12. Session Active
- **Header**: 
  - Titre: Session en cours
  - Durée écoulée
  - Date/heure ouverture
- **Infos Session**:
  - Fond de vente
  - Orange Money initial
  - MTN Money initial
- **Aperçu Ventes** (temps réel):
  - Bouton: Actualiser
  - Ventes totales estimées
  - Liste par produit:
    - Nom produit
    - Stock initial
    - Entrées
    - Retours
    - Stock actuel
    - Quantité vendue (calculée)
    - Montant vendu
- **Info**:
  - "Seul le PDG peut fermer cette session"
  - Badge: Session ouverte

#### 13. Mon Flux
- **Header**: 
  - Titre: Mon Flux de Produits
  - Filtre: Date picker
  - Période: Affichage heures ouverture/fermeture
- **Tableau Flux**:
  - Colonnes:
    - Produit
    - Trouvé (stock initial)
    - Reçu
    - Retourné
    - Restant (stock final)
    - Vendu (calculé)
    - Valeur (XAF)
  - Total général en bas
- **Bouton**: Export/Partage (si nécessaire)

#### 14. Historique Sessions
- **Header**: 
  - Titre: Mes Sessions
  - Filtres: 
    - Statut (toutes/ouvertes/fermées)
    - Période (date début/fin)
- **Liste**:
  - Carte par session:
    - Date ouverture
    - Date fermeture (si fermée)
    - Fond de vente
    - Ventes totales
    - Montant versé (si fermée)
    - Manquant (si fermée)
    - Badge: Ouverte/Fermée
    - Action: Voir détails

#### 15. Détails Session
- **Infos générales**:
  - Dates ouverture/fermeture
  - Durée
  - Fond de vente
  - Orange/MTN initial et final
  - Ventes totales
  - Montant versé
  - Manquant
- **Détails par produit**:
  - Tableau avec toutes les colonnes
  - Stock initial/entrées/retours/stock final
  - Quantité vendue / Montant

---

## 🎨 Design & UX

### Palette de Couleurs (Thème Pain Doré)
```
Primary: #D4A574 (doré pain)
Secondary: #8B6F47 (brun pain)
Accent: #F4E5D3 (crème)
Success: #4CAF50 (vert)
Warning: #FF9800 (orange)
Error: #F44336 (rouge)
Background: #FAFAFA (gris très clair)
Surface: #FFFFFF (blanc)
Text Primary: #212121 (noir)
Text Secondary: #757575 (gris)
```

### Indicateurs de Statut
- **Connexion réseau**:
  - 🟢 Connecté (coin supérieur droit)
  - 🔴 Déconnecté
  - 🟡 Synchronisation en cours

- **État de synchronisation**:
  - ✅ Synced (badge vert)
  - ⏳ Pending (badge orange)
  - ⚠️ Conflict (badge rouge)

### Composants Réutilisables
- **ProductCard**: Affichage produit avec icône catégorie
- **SyncBadge**: Badge état synchronisation
- **ConnectionIndicator**: Point couleur connexion
- **DateFilter**: Sélecteur de date stylisé
- **NumericInput**: Input numérique avec +/- buttons
- **PINInput**: Input 6 chiffres masqué
- **SearchableSelect**: Select avec recherche
- **ConfirmationModal**: Modal de confirmation actions
- **LoadingOverlay**: Overlay pendant sync
- **EmptyState**: État vide avec illustration

### Animations
- Transitions fluides entre écrans
- Feedback visuel sur actions (ripple effect)
- Skeleton loaders pendant chargement
- Success animations sur validations
- Pull-to-refresh sur listes

le responsive est la cler (parfait peut importe l'appareil mais surtout sur ipad en paysage et en portrait)
---

genere moi le code react + vite pwa permettant d'avoir xa
ensuite donne moi fichier readme.md qui m'explique depuis le clonage jusqu'au test du front end 

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        //Table client
        Schema::create('clients', function (Blueprint $table) {
            $table->uuid('client_id')->primary();
            $table->string('device_info')->nullable(); // Description de l'appareil
            $table->timestamps();
        });

        // Table des produits
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->decimal('prix', 10, 2);
            $table->enum('categorie', ['boulangerie', 'patisserie']);
            $table->boolean('actif')->default(true);
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des vendeurs actifs
        Schema::create('vendeurs_actifs', function (Blueprint $table) {
            $table->id();
            $table->enum('categorie', ['boulangerie', 'patisserie'])->unique();
            $table->foreignId('vendeur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('connecte_a')->nullable();
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des réceptions
        Schema::create('receptions_pointeur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pointeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('producteur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite');
            $table->foreignId('vendeur_assigne_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('verrou')->default(false);
            $table->timestamp('date_reception');
            $table->text('notes')->nullable();
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des retours
        Schema::create('retours_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pointeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vendeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite');
            $table->enum('raison', ['perime', 'abime', 'autre']);
            $table->text('description')->nullable();
            $table->boolean('verrou')->default(false);
            $table->timestamp('date_retour');
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des inventaires
        Schema::create('inventaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendeur_sortant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vendeur_entrant_id')->constrained('users')->onDelete('cascade');
            $table->enum('categorie', ['boulangerie', 'patisserie']);
            $table->boolean('valide_sortant')->default(false);
            $table->boolean('valide_entrant')->default(false);
            $table->timestamp('date_inventaire');
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des détails d'inventaire
        Schema::create('inventaire_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaire_id')->constrained('inventaires')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite_restante');
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des sessions de vente
        Schema::create('sessions_vente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendeur_id')->constrained('users')->onDelete('cascade');
            $table->enum('categorie', ['boulangerie', 'patisserie']);
            $table->decimal('fond_vente', 10, 2)->default(0);
            $table->decimal('orange_money_initial', 10, 2)->default(0);
            $table->decimal('mtn_money_initial', 10, 2)->default(0);
            $table->decimal('montant_verse', 10, 2)->nullable();
            $table->decimal('orange_money_final', 10, 2)->nullable();
            $table->decimal('mtn_money_final', 10, 2)->nullable();
            $table->decimal('manquant', 10, 2)->nullable();
            $table->decimal('valeur_vente', 10, 2)->nullable();
            $table->enum('statut', ['ouverte', 'fermee'])->default('ouverte');
            $table->foreignId('fermee_par')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_ouverture');
            $table->timestamp('date_fermeture')->nullable();
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des ventes
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_vente_id')->constrained('sessions_vente')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_total', 10, 2);
            $table->enum('mode_paiement', ['cash', 'orange_money', 'mtn_money'])->default('cash');
            $table->timestamp('date_vente');
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });
        
        // Table de configuration PDG
        Schema::create('config_pdg', function (Blueprint $table) {
            $table->id();
            $table->string('code_inscription_pdg');
            $table->timestamps();
        });

        DB::table('config_pdg')->insert([
            'code_inscription_pdg' => 'PDG2025SECURE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('config_pdg');
        Schema::dropIfExists('ventes');
        Schema::dropIfExists('sessions_vente');
        Schema::dropIfExists('inventaire_details');
        Schema::dropIfExists('inventaires');
        Schema::dropIfExists('retours_produits');
        Schema::dropIfExists('receptions_pointeur');
        Schema::dropIfExists('vendeurs_actifs');
        Schema::dropIfExists('produits');
    }
};



# Documentation API - Backend Laravel

## Table des matières
1. [Authentification](#authentification)
2. [Utilisateurs](#utilisateurs)
3. [Produits](#produits)
4. [Réceptions](#réceptions)
5. [Retours](#retours)
6. [Inventaires](#inventaires)
7. [Sessions de vente](#sessions-de-vente)
8. [Flux produits](#flux-produits)
9. [Synchronisation](#synchronisation)

---

## Authentification

### POST /api/auth/inscription
**Headers**: Aucun  
**Request**:
```json
{
  "name": "Jean Dupont",
  "numero_telephone": "237612345678",
  "role": "vendeur_boulangerie",
  "code_pin": "123456",
  "preferred_language": "fr",
  "has_client_id": false,
  "client_id": "",
  "device_info": "Android 12 - Samsung Galaxy S21"
}
```

**Response Success (201)**:
```json
{
  "success": true,
  "message": "Inscription réussie",
  "user": {
    "id": 1,
    "name": "Jean Dupont",
    "numero_telephone": "612345678",
    "role": "vendeur_boulangerie",
    "actif": true,
    "preferred_language": "fr",
    "created_at": "2025-01-03T10:00:00.000000Z",
    "updated_at": "2025-01-03T10:00:00.000000Z"
  },
  "token": "1|abcdef123456...",
  "client_id": "550e8400-e29b-41d4-a716-446655440000"
}
```

**Response Error (400)**:
```json
{
  "success": false,
  "message": "Ce numéro de téléphone est déjà utilisé"
}
```

---

### POST /api/auth/connexion
**Headers**: Aucun  
**Request**:
```json
{
  "numero_telephone": "612345678",
  "code_pin": "123456"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Connexion réussie",
  "user": {
    "id": 1,
    "name": "Jean Dupont",
    "numero_telephone": "612345678",
    "role": "vendeur_boulangerie",
    "actif": true,
    "preferred_language": "fr"
  },
  "token": "2|ghijkl789012..."
}
```

**Response Error (401)**:
```json
{
  "success": false,
  "message": "Identifiants incorrects"
}
```

---

### POST /api/auth/deconnexion
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Déconnexion réussie"
}
```

---

### GET /api/auth/me
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Jean Dupont",
    "numero_telephone": "612345678",
    "role": "vendeur_boulangerie",
    "actif": true,
    "preferred_language": "fr"
  }
}
```

---

## Utilisateurs

### GET /api/users
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Jean Dupont",
      "numero_telephone": "612345678",
      "role": "vendeur_boulangerie",
      "actif": true
    },
    {
      "id": 2,
      "name": "Marie Martin",
      "numero_telephone": "698765432",
      "role": "pointeur",
      "actif": true
    }
  ]
}
```

---

### GET /api/users/role/{role}
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body  
**Params**: `role` peut être: `pdg`, `pointeur`, `vendeur_boulangerie`, `vendeur_patisserie`, `producteur`

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Jean Dupont",
      "numero_telephone": "612345678",
      "role": "vendeur_boulangerie",
      "actif": true
    }
  ]
}
```

---

### GET /api/users/producteurs
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 5,
      "name": "Paul Producteur",
      "numero_telephone": "677123456",
      "role": "producteur",
      "actif": true
    }
  ]
}
```

---

### POST /api/users
**Headers**: `Authorization: Bearer {token}`  
**Request**:
```json
{
  "nom": "Pierre Nouveau",
  "numero_telephone": "655123456",
  "role": "pointeur",
  "code_pin": "654321"
}
```

**Response Success (201)**:
```json
{
  "success": true,
  "message": "Utilisateur créé avec succès",
  "data": {
    "id": 10,
    "name": "Pierre Nouveau",
    "numero_telephone": "655123456",
    "role": "pointeur",
    "actif": true
  }
}
```

---

### PUT /api/users/{id}
**Headers**: `Authorization: Bearer {token}`  
**Request**:
```json
{
  "nom": "Pierre Modifié",
  "numero_telephone": "655123456",
  "code_pin": "111111"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Utilisateur modifié avec succès",
  "data": {
    "id": 10,
    "name": "Pierre Modifié",
    "numero_telephone": "655123456",
    "role": "pointeur",
    "actif": true
  }
}
```

---

### POST /api/users/{id}/toggle-actif
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Statut modifié avec succès",
  "data": {
    "id": 10,
    "name": "Pierre Modifié",
    "actif": false
  }
}
```

---

### DELETE /api/users/{id}
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Utilisateur supprimé avec succès"
}
```

---

## Produits

### GET /api/produits
**Headers**: `Authorization: Bearer {token}`  
**Query Params**: `actif_only` (boolean, default: true)

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nom": "Pain complet",
      "prix": 250,
      "categorie": "boulangerie",
      "actif": true
    },
    {
      "id": 2,
      "nom": "Croissant",
      "prix": 300,
      "categorie": "patisserie",
      "actif": true
    }
  ]
}
```

---

### GET /api/produits/categorie/{categorie}
**Headers**: `Authorization: Bearer {token}`  
**Params**: `categorie` peut être: `boulangerie` ou `patisserie`  
**Query Params**: `actif_only` (boolean, default: true)

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nom": "Pain complet",
      "prix": 250,
      "categorie": "boulangerie",
      "actif": true
    }
  ]
}
```

---

### POST /api/produits
**Headers**: `Authorization: Bearer {token}`  
**Request**:
```json
{
  "nom": "Pain au chocolat",
  "prix": 350,
  "categorie": "patisserie"
}
```

**Response Success (201)**:
```json
{
  "success": true,
  "message": "Produit créé avec succès",
  "data": {
    "id": 15,
    "nom": "Pain au chocolat",
    "prix": 350,
    "categorie": "patisserie",
    "actif": true
  }
}
```

---

### PUT /api/produits/{id}
**Headers**: `Authorization: Bearer {token}`  
**Request**:
```json
{
  "nom": "Pain au chocolat XL",
  "prix": 400,
  "categorie": "patisserie"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Produit modifié avec succès",
  "data": {
    "id": 15,
    "nom": "Pain au chocolat XL",
    "prix": 400,
    "categorie": "patisserie",
    "actif": true
  }
}
```

---

### POST /api/produits/{id}/toggle-actif
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Statut modifié avec succès",
  "data": {
    "id": 15,
    "nom": "Pain au chocolat XL",
    "actif": false
  }
}
```

---

### DELETE /api/produits/{id}
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Produit supprimé avec succès"
}
```

---

## Réceptions

### POST /api/receptions
**Headers**: `Authorization: Bearer {token}`  
**Request**:
```json
{
  "producteur_id": 5,
  "produit_id": 1,
  "quantite": 100,
  "notes": "Livraison matinale"
}
```

**Response Success (201)**:
```json
{
  "success": true,
  "message": "Réception enregistrée avec succès",
  "data": {
    "id": 1,
    "pointeur_id": 2,
    "producteur_id": 5,
    "produit_id": 1,
    "quantite": 100,
    "vendeur_assigne_id": 3,
    "verrou": false,
    "date_reception": "2025-01-03T10:30:00.000000Z",
    "notes": "Livraison matinale",
    "produit": {
      "id": 1,
      "nom": "Pain complet",
      "prix": 250
    },
    "producteur": {
      "id": 5,
      "name": "Paul Producteur"
    },
    "vendeurAssigne": {
      "id": 3,
      "name": "Jean Dupont"
    }
  }
}
```

---

### PUT /api/receptions/{id}
**Headers**: `Authorization: Bearer {token}`  
**Request**:
```json
{
  "quantite": 120,
  "notes": "Quantité corrigée"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Réception modifiée avec succès",
  "data": {
    "id": 1,
    "quantite": 120,
    "notes": "Quantité corrigée"
  }
}
```

**Response Error (400)**:
```json
{
  "success": false,
  "message": "Cette réception est verrouillée"
}
```

---

### GET /api/receptions/mes-receptions
**Headers**: `Authorization: Bearer {token}`  
**Query Params**: `date` (optional, format: YYYY-MM-DD)

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "pointeur_id": 2,
      "producteur_id": 5,
      "produit_id": 1,
      "quantite": 120,
      "vendeur_assigne_id": 3,
      "date_reception": "2025-01-03T10:30:00.000000Z",
      "produit": {
        "nom": "Pain complet"
      },
      "producteur": {
        "name": "Paul Producteur"
      },
      "vendeurAssigne": {
        "name": "Jean Dupont"
      }
    }
  ]
}
```

---

### GET /api/receptions/vendeur
**Headers**: `Authorization: Bearer {token}`  
**Query Params**: `date` (optional, format: YYYY-MM-DD)

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "produit_id": 1,
      "quantite": 120,
      "date_reception": "2025-01-03T10:30:00.000000Z",
      "produit": {
        "nom": "Pain complet",
        "prix": 250
      },
      "producteur": {
        "name": "Paul Producteur"
      }
    }
  ]
}
```

---

## Retours

### POST /api/retours
**Headers**: `Authorization: Bearer {token}`  
**Request**:
```json
{
  "produit_id": 1,
  "quantite": 5,
  "raison": "perime",
  "description": "Produits périmés du matin"
}
```

**Response Success (201)**:
```json
{
  "success": true,
  "message": "Retour enregistré avec succès",
  "data": {
    "id": 1,
    "pointeur_id": 2,
    "vendeur_id": 3,
    "produit_id": 1,
    "quantite": 5,
    "raison": "perime",
    "description": "Produits périmés du matin",
    "verrou": false,
    "date_retour": "2025-01-03T18:00:00.000000Z",
    "produit": {
      "id": 1,
      "nom": "Pain complet"
    },
    "vendeur": {
      "id": 3,
      "name": "Jean Dupont"
    }
  }
}
```

---

### PUT /api/retours/{id}
**Headers**: `Authorization: Bearer {token}`  
**Request**:
```json
{
  "quantite": 7,
  "raison": "abime",
  "description": "Produits abîmés"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Retour modifié avec succès",
  "data": {
    "id": 1,
    "quantite": 7,
    "raison": "abime",
    "description": "Produits abîmés"
  }
}
```

---

### GET /api/retours/vendeur
**Headers**: `Authorization: Bearer {token}`  
**Query Params**: `date` (optional, format: YYYY-MM-DD)

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "produit_id": 1,
      "quantite": 7,
      "raison": "abime",
      "description": "Produits abîmés",
      "date_retour": "2025-01-03T18:00:00.000000Z",
      "produit": {
        "nom": "Pain complet"
      },
      "pointeur": {
        "name": "Marie Martin"
      }
    }
  ]
}
```

---

## Inventaires

### POST /api/inventaires/creer
**Headers**: `Authorization: Bearer {token}`  
**Request**:
```json
{
  "vendeur_sortant_id": 3,
  "vendeur_entrant_id": 4,
  "code_pin_sortant": "123456",
  "code_pin_entrant": "654321",
  "produits": [
    {
      "produit_id": 1,
      "quantite_restante": 45
    },
    {
      "produit_id": 2,
      "quantite_restante": 30
    }
  ]
}
```

**Response Success (201)**:
```json
{
  "success": true,
  "message": "Inventaire créé et validé avec succès par les deux parties",
  "data": {
    "id": 1,
    "vendeur_sortant_id": 3,
    "vendeur_entrant_id": 4,
    "categorie": "boulangerie",
    "valide_sortant": true,
    "valide_entrant": true,
    "date_inventaire": "2025-01-03T19:00:00.000000Z",
    "details": [
      {
        "id": 1,
        "inventaire_id": 1,
        "produit_id": 1,
        "quantite_restante": 45,
        "produit": {
          "id": 1,
          "nom": "Pain complet",
          "prix": 250
        }
      },
      {
        "id": 2,
        "inventaire_id": 1,
        "produit_id": 2,
        "quantite_restante": 30,
        "produit": {
          "id": 2,
          "nom": "Croissant",
          "prix": 300
        }
      }
    ],
    "vendeurSortant": {
      "id": 3,
      "name": "Jean Dupont"
    },
    "vendeurEntrant": {
      "id": 4,
      "name": "Sophie Vendeur"
    }
  }
}
```

**Response Error (400)**:
```json
{
  "success": false,
  "message": "Code PIN incorrect pour le vendeur sortant"
}
```

---

### GET /api/inventaires/mes-inventaires
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "vendeur_sortant_id": 3,
      "vendeur_entrant_id": 4,
      "categorie": "boulangerie",
      "valide_sortant": true,
      "valide_entrant": true,
      "date_inventaire": "2025-01-03T19:00:00.000000Z",
      "details": [
        {
          "produit_id": 1,
          "quantite_restante": 45,
          "produit": {
            "nom": "Pain complet"
          }
        }
      ]
    }
  ]
}
```

---

### GET /api/inventaires/en-cours
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "data": null
}
```

ou si un inventaire est en cours:

```json
{
  "success": true,
  "data": {
    "id": 2,
    "vendeur_sortant_id": 3,
    "vendeur_entrant_id": 4,
    "valide_sortant": false,
    "valide_entrant": false,
    "date_inventaire": "2025-01-03T20:00:00.000000Z"
  }
}
```

---

## Sessions de vente

### POST /api/sessions-vente/ouvrir
**Headers**: `Authorization: Bearer {token}`  
**Request**:
```json
{
  "categorie": "boulangerie",
  "fond_vente": 5000,
  "orange_money_initial": 1000,
  "mtn_money_initial": 500
}
```

**Response Success (201)**:
```json
{
  "success": true,
  "message": "Session ouverte avec succès",
  "data": {
    "id": 1,
    "vendeur_id": 3,
    "categorie": "boulangerie",
    "fond_vente": 5000,
    "orange_money_initial": 1000,
    "mtn_money_initial": 500,
    "statut": "ouverte",
    "date_ouverture": "2025-01-03T08:00:00.000000Z",
    "vendeur": {
      "id": 3,
      "name": "Jean Dupont"
    }
  }
}
```

**Response Error (400)**:
```json
{
  "success": false,
  "message": "Vous avez déjà une session ouverte"
}
```

---

### POST /api/sessions-vente/{id}/fermer
**Headers**: `Authorization: Bearer {token}` (PDG uniquement)  
**Request**:
```json
{
  "montant_verse": 45000,
  "orange_money_final": 3000,
  "mtn_money_final": 2000
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Session fermée avec succès",
  "data": {
    "session": {
      "id": 1,
      "vendeur_id": 3,
      "categorie": "boulangerie",
      "fond_vente": 5000,
      "orange_money_initial": 1000,
      "mtn_money_initial": 500,
      "montant_verse": 45000,
      "orange_money_final": 3000,
      "mtn_money_final": 2000,
      "manquant": 1500,
      "valeur_vente": 48000,
      "statut": "fermee",
      "fermee_par": 1,
      "date_ouverture": "2025-01-03T08:00:00.000000Z",
      "date_fermeture": "2025-01-03T19:00:00.000000Z"
    },
    "ventes_totales": 48000,
    "details_calcul": [
      {
        "produit": "Pain complet",
        "prix_unitaire": 250,
        "stock_initial": 100,
        "entrees": 50,
        "retours": 5,
        "stock_final": 45,
        "quantite_vendue": 100,
        "montant_vendu": 25000
      },
      {
        "produit": "Croissant",
        "prix_unitaire": 300,
        "stock_initial": 80,
        "entrees": 20,
        "retours": 2,
        "stock_final": 25,
        "quantite_vendue": 73,
        "montant_vendu": 21900
      }
    ]
  }
}
```

**Response Error (403)**:
```json
{
  "success": false,
  "message": "Seul le PDG peut fermer une session"
}
```

---

### GET /api/sessions-vente/active
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "vendeur_id": 3,
    "categorie": "boulangerie",
    "fond_vente": 5000,
    "statut": "ouverte",
    "date_ouverture": "2025-01-03T08:00:00.000000Z",
    "vendeur": {
      "name": "Jean Dupont"
    }
  }
}
```

ou si aucune session active:

```json
{
  "success": true,
  "message": "Aucune session active",
  "data": null
}
```

---

### GET /api/sessions-vente/historique
**Headers**: `Authorization: Bearer {token}`  
**Query Params**: 
- `statut` (optional): `ouverte` ou `fermee`
- `date_debut` (optional): YYYY-MM-DD
- `date_fin` (optional): YYYY-MM-DD

**Response Success (200)**:
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 2,
        "vendeur_id": 3,
        "categorie": "boulangerie",
        "fond_vente": 5000,
        "montant_verse": 45000,
        "manquant": 1500,
        "statut": "fermee",
        "date_ouverture": "2025-01-02T08:00:00.000000Z",
        "date_fermeture": "2025-01-02T19:00:00.000000Z",
        "vendeur": {
          "name": "Jean Dupont"
        },
        "fermeePar": {
          "name": "Directeur PDG"
        }
      }
    ],
    "per_page": 20,
    "total": 1
  }
}
```

---

### GET /api/sessions-vente/{id}
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "vendeur_id": 3,
    "categorie": "boulangerie",
    "fond_vente": 5000,
    "orange_money_initial": 1000,
    "mtn_money_initial": 500,
    "montant_verse": 45000,
    "orange_money_final": 3000,
    "mtn_money_final": 2000,
    "manquant": 1500,
    "statut": "fermee",
    "date_ouverture": "2025-01-03T08:00:00.000000Z",
    "date_fermeture": "2025-01-03T19:00:00.000000Z",
    "vendeur": {
      "id": 3,
      "name": "Jean Dupont"
    },
    "fermeePar": {
      "id": 1,
      "name": "Directeur PDG"
    }
  }
}
```

**Response Error (403)**:
```json
{
  "success": false,
  "message": "Accès non autorisé"
}
```

---

### GET /api/sessions-vente/{id}/apercu-ventes
**Headers**: `Authorization: Bearer {token}`  
**Request**: Aucun body

**Response Success (200)**:
```json
{
  "success": true,
  "data": {
    "ventes_totales_estimees": 48000,
    "details_par_produit": [
      {
        "produit": "Pain complet",
        "prix_unitaire": 250,
        "stock_initial": 100,
        "entrees": 50,
        "retours": 5,
        "stock_final": 45,
        "quantite_vendue": 100,
        "montant_vendu": 25000
      }
    ],
    "fond_vente": 5000,
    "date_ouverture": "2025-01-03T08:00:00.000000Z"
  }
}
```

---

## Flux produits

### GET /api/flux-produits/mon-flux
**Headers**: `Authorization: Bearer {token}`  
**Query Params**: `date` (optional, format: YYYY-MM-DD)

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "produit_id": 1,
      "produit_nom": "Pain complet",
      "stock_initial": 100,
      "entrees": 50,
      "retours": 5,
      "stock_final": 45,
      "ventes_estimees": 100
    }
  ]
}
```

---

### GET /api/flux-produits/vendeur/{vendeurId}
**Headers**: `Authorization: Bearer {token}`  
**Query Params**: `date` (optional, format: YYYY-MM-DD)

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "produit_id": 1,
      "produit_nom": "Pain complet",
      "stock_initial": 100,
      "entrees": 50,
      "retours": 5,
      "stock_final": 45,
      "ventes_estimees": 100
    }
  ]
}
```

---

### GET /api/flux-produits/tous
**Headers**: `Authorization: Bearer {token}`  
**Query Params**: `date` (optional, format: YYYY-MM-DD)

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "vendeur_id": 3,
      "vendeur_nom": "Jean Dupont",
      "categorie": "boulangerie",
      "produits": [
        {
          "produit_id": 1,
          "produit_nom": "Pain complet",
          "stock_initial": 100,
          "entrees": 50,
          "retours": 5,
          "stock_final": 45,
          "ventes_estimees": 100
        }
      ]
    }
  ]
}
```

---

## Synchronisation

### GET /api/sync/pull
**Headers**: 
- `Authorization: Bearer {token}`
- `X-Client-ID: {client_uuid}`

**Query Params**: `last_sync` (optional, format ISO 8601: 2025-01-03T10:00:00Z)

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Données récupérées avec succès",
  "data": {
    "users": [
      {
        "id": 1,
        "name": "Jean Dupont",
        "numero_telephone": "612345678",
        "role": "vendeur_boulangerie",
        "code_pin": "$2y$10$...",
        "actif": true,
        "updated_at":
        
        
        ```json
        "updated_at": "2025-01-03T10:00:00.000000Z"
      }
    ],
    "produits": [
      {
        "id": 1,
        "nom": "Pain complet",
        "prix": 250,
        "categorie": "boulangerie",
        "actif": true,
        "updated_at": "2025-01-03T09:00:00.000000Z"
      }
    ],
    "vendeurs_actifs": [
      {
        "id": 1,
        "categorie": "boulangerie",
        "vendeur_id": 3,
        "connecte_a": "2025-01-03T08:00:00.000000Z",
        "updated_at": "2025-01-03T08:00:00.000000Z"
      }
    ],
    "receptions_pointeur": [
      {
        "id": 1,
        "pointeur_id": 2,
        "producteur_id": 5,
        "produit_id": 1,
        "quantite": 120,
        "vendeur_assigne_id": 3,
        "verrou": false,
        "date_reception": "2025-01-03T10:30:00.000000Z",
        "notes": "Livraison matinale",
        "updated_at": "2025-01-03T11:00:00.000000Z"
      }
    ],
    "retours_produits": [
      {
        "id": 1,
        "pointeur_id": 2,
        "vendeur_id": 3,
        "produit_id": 1,
        "quantite": 7,
        "raison": "abime",
        "description": "Produits abîmés",
        "verrou": false,
        "date_retour": "2025-01-03T18:00:00.000000Z",
        "updated_at": "2025-01-03T18:00:00.000000Z"
      }
    ],
    "sessions_vente": [
      {
        "id": 1,
        "vendeur_id": 3,
        "categorie": "boulangerie",
        "fond_vente": 5000,
        "orange_money_initial": 1000,
        "mtn_money_initial": 500,
        "montant_verse": null,
        "orange_money_final": null,
        "mtn_money_final": null,
        "manquant": null,
        "statut": "ouverte",
        "fermee_par": null,
        "date_ouverture": "2025-01-03T08:00:00.000000Z",
        "date_fermeture": null,
        "updated_at": "2025-01-03T08:00:00.000000Z"
      }
    ]
  },
  "sync_time": "2025-01-03T12:00:00+01:00"
}
```

**Response Error (400)**:
```json
{
  "success": false,
  "message": "Client ID manquant"
}
```

**Response Error (403)**:
```json
{
  "success": false,
  "message": "Client non reconnu"
}
```

---

### POST /api/sync/ack
**Headers**: 
- `Authorization: Bearer {token}`
- `X-Client-ID: {client_uuid}`

**Request**:
```json
{
  "synced_data": [
    {
      "table": "users",
      "ids": [1, 2, 3]
    },
    {
      "table": "produits",
      "ids": [1, 2, 5, 10]
    },
    {
      "table": "receptions_pointeur",
      "ids": [1]
    }
  ]
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Confirmation enregistrée"
}
```

**Response Error (400)**:
```json
{
  "success": false,
  "message": "Client ID manquant"
}
```

---

### POST /api/sync/push
**Headers**: 
- `Authorization: Bearer {token}`
- `X-Client-ID: {client_uuid}`

**Request**:
```json
{
  "receptions": [
    {
      "id": null,
      "pointeur_id": 2,
      "producteur_id": 5,
      "produit_id": 1,
      "quantite": 100,
      "vendeur_assigne_id": 3,
      "verrou": false,
      "date_reception": "2025-01-03T10:30:00+01:00",
      "notes": "Nouvelle livraison"
    }
  ],
  "retours": [
    {
      "id": 2,
      "pointeur_id": 2,
      "vendeur_id": 3,
      "produit_id": 2,
      "quantite": 3,
      "raison": "perime",
      "description": "Produits périmés",
      "verrou": false,
      "date_retour": "2025-01-03T17:00:00+01:00"
    }
  ],
  "inventaires": [
    {
      "id": null,
      "vendeur_sortant_id": 3,
      "vendeur_entrant_id": 4,
      "categorie": "boulangerie",
      "valide_sortant": true,
      "valide_entrant": true,
      "date_inventaire": "2025-01-03T19:00:00+01:00"
    }
  ],
  "inventaire_details": [
    {
      "id": null,
      "inventaire_id": 1,
      "produit_id": 1,
      "quantite_restante": 45
    },
    {
      "id": null,
      "inventaire_id": 1,
      "produit_id": 2,
      "quantite_restante": 30
    }
  ],
  "sessions": [
    {
      "id": 1,
      "vendeur_id": 3,
      "categorie": "boulangerie",
      "fond_vente": 5000,
      "orange_money_initial": 1000,
      "mtn_money_initial": 500,
      "montant_verse": 45000,
      "orange_money_final": 3000,
      "mtn_money_final": 2000,
      "manquant": 1500,
      "statut": "fermee",
      "fermee_par": 1,
      "date_ouverture": "2025-01-03T08:00:00+01:00",
      "date_fermeture": "2025-01-03T19:00:00+01:00"
    }
  ]
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "confirmed": true,
  "message": "Synchronisation confirmée",
  "synced": [
    {
      "table": "receptions_pointeur",
      "id": null,
      "server_id": 15
    },
    {
      "table": "retours_produits",
      "id": 2,
      "server_id": 2
    },
    {
      "table": "inventaires",
      "id": null,
      "server_id": 5
    },
    {
      "table": "inventaire_details",
      "id": null,
      "server_id": 10
    },
    {
      "table": "inventaire_details",
      "id": null,
      "server_id": 11
    },
    {
      "table": "sessions_vente",
      "id": 1,
      "server_id": 1
    }
  ],
  "conflicts": [],
  "sync_time": "2025-01-03T12:30:00+01:00"
}
```

**Response avec conflits (422)**:
```json
{
  "success": false,
  "confirmed": false,
  "message": "Échec de synchronisation",
  "synced": [],
  "conflicts": [
    {
      "table": "receptions_pointeur",
      "id": 1,
      "reason": "Enregistrement verrouillé"
    },
    {
      "table": "inventaires",
      "id": null,
      "reason": "Vendeur sortant introuvable"
    }
  ],
  "sync_time": "2025-01-03T12:30:00+01:00"
}
```

**Response Error (400)**:
```json
{
  "success": false,
  "confirmed": false,
  "message": "Client ID manquant",
  "synced": [],
  "conflicts": []
}
```

**Response Error (500)**:
```json
{
  "success": false,
  "confirmed": false,
  "message": "Erreur critique de synchronisation",
  "error": "Description de l'erreur",
  "synced": [],
  "conflicts": []
}
```

---

## Notes importantes pour le développement front-end

### Authentification
- Tous les endpoints sauf `/api/auth/inscription` et `/api/auth/connexion` nécessitent un token Bearer dans le header `Authorization`
- Le token est retourné lors de l'inscription et de la connexion
- Stocker le token de manière sécurisée (ex: secure storage)

### Client ID
- Lors de l'inscription, le backend génère un `client_id` unique (UUID)
- Ce `client_id` doit être stocké de manière persistante sur l'appareil
- Le `client_id` doit être envoyé dans le header `X-Client-ID` pour tous les appels de synchronisation

### Synchronisation
1. **Pull** : Récupérer les données du serveur
   - Envoyer `last_sync` en paramètre pour récupérer uniquement les modifications
   - Le backend retourne toutes les données non synchronisées ou modifiées depuis `last_sync`

2. **Acknowledgement (ACK)** : Confirmer la réception
   - Après avoir traité les données du pull, envoyer un ACK avec les IDs des enregistrements reçus
   - Cela évite de recevoir les mêmes données au prochain pull

3. **Push** : Envoyer les modifications locales
   - Envoyer les créations (id = null) et modifications (id présent)
   - Le backend retourne les `server_id` pour les nouvelles créations
   - Mettre à jour les IDs locaux avec les `server_id` retournés
   - Gérer les conflits retournés dans le champ `conflicts`

### Gestion des dates
- Toutes les dates sont au format ISO 8601 avec timezone
- Le backend attend des dates au format: `2025-01-03T10:30:00+01:00`
- Les dates retournées sont toujours en timezone `Africa/Douala`

### Codes PIN
- Les codes PIN sont hashés côté backend
- Ne jamais stocker les codes PIN en clair côté client
- Les codes PIN doivent faire exactement 6 caractères

### Numéros de téléphone
- Format attendu: `237XXXXXXXXX` (avec préfixe pays) ou `XXXXXXXXX` (sans préfixe)
- Le backend stocke uniquement les 9 chiffres après le préfixe 237
- Exemple: `237612345678` ou `612345678` → stocké comme `612345678`

### Rôles utilisateur
- `pdg` : Directeur général (peut fermer les sessions)
- `pointeur` : Enregistre les réceptions et retours
- `vendeur_boulangerie` : Vendeur de la catégorie boulangerie
- `vendeur_patisserie` : Vendeur de la catégorie pâtisserie
- `producteur` : Fournisseur de produits

### Catégories
- `boulangerie` : Produits de boulangerie
- `patisserie` : Produits de pâtisserie

### Statuts de session
- `ouverte` : Session en cours
- `fermee` : Session terminée

### Verrouillage
- Les réceptions et retours peuvent être verrouillés (`verrou: true`)
- Une fois verrouillés, ils ne peuvent plus être modifiés
- Seul le PDG peut verrouiller (fonctionnalité à implémenter si nécessaire)

### Gestion des erreurs
- Codes HTTP standards:
  - `200` : Succès
  - `201` : Création réussie
  - `400` : Erreur de validation ou données invalides
  - `401` : Non authentifié
  - `403` : Non autorisé
  - `404` : Ressource non trouvée
  - `422` : Conflits de synchronisation
  - `500` : Erreur serveur

- Toujours vérifier le champ `success` dans la réponse
- Le champ `message` contient une description de l'erreur en français

### Inventaires
- Nécessite la validation des deux vendeurs (sortant et entrant) avec leurs codes PIN
- Une fois validé, l'inventaire change automatiquement le vendeur actif
- Un seul inventaire en cours par catégorie à la fois

### Sessions de vente
- Une seule session ouverte par vendeur à la fois
- Le calcul des ventes est automatique lors de la fermeture
- Seul le PDG peut fermer une session
- Le manquant est calculé automatiquement: `(Ventes + Fond) - (Versé + Diff OM + Diff MTN)`

### Flux de synchronisation recommandé
```
1. Au démarrage de l'app:
   - Vérifier la connexion internet
   - Exécuter PULL pour récupérer les données serveur
   - Traiter les données reçues
   - Envoyer ACK pour confirmer la réception

2. Lors de modifications locales:
   - Sauvegarder localement avec flag "non synchronisé"
   - Tenter PUSH immédiatement si connexion disponible
   - Sinon, mettre en file d'attente pour synchronisation ultérieure

3. Périodiquement (ou sur événement):
   - Exécuter PULL pour récupérer les nouvelles données
   - Exécuter PUSH pour envoyer les modifications locales
   - Gérer les conflits si nécessaires
```

---

## Support et questions
Pour toute question ou clarification, contacter l'équipe backend.


VOICI UNE POLITIQUE DE SYNC QUI MARCHE TRES BIEN AVEC REACT NATIVE

/**
 * Service de Synchronisation Bidirectionnelle
 * Gère la sync entre SQLite local et API Laravel
 */

import axios from 'axios';
import { API_CONFIG, API_ENDPOINTS } from '../../config/api.config';
import { SYNC_CONFIG } from '../../config/database.config';
import { getDatabase } from '../../database/init';
import { AuthService } from '../auth/AuthService';
import { ClientId } from '../../models/ClientId';

export class SyncService {
  private static isSyncing = false;
  private static lastSyncTime: Date | null = null;


  static async fullSync(): Promise<{ success: boolean; errors: string[] }> {
    console.log('🔍 [fullSync] Début synchronisation');
    
    if (this.isSyncing) {
      console.warn('⏳ Sync déjà en cours');
      return { success: false, errors: ['Sync en cours'] };
    }

    this.isSyncing = true;
    const errors: string[] = [];
    
    try {
      // Push
      console.log('📤 Phase 1: Push');
      const pushResult = await this.pushLocalChanges();
      if (!pushResult.success) {
        errors.push(...pushResult.errors);
      }

      // Pull
      console.log('📥 Phase 2: Pull');
      const pullResult = await this.pullServerData();
      if (!pullResult.success) {
        errors.push(...pullResult.errors);
      }

      // ACK (confirmation de réception)
      console.log('✅ Phase 3: Acknowledgement');
      const ackResult = await this.sendAcknowledgement(pullResult.syncedData || []);
      if (!ackResult.success) {
        errors.push(...ackResult.errors);
      }

      this.lastSyncTime = new Date();
      console.log('✅ Sync complète terminée');
      
      return { success: errors.length === 0, errors };
    } catch (error: any) {
      console.error('❌ Erreur fullSync:', error);
      return { success: false, errors: [error.message] };
    } finally {
      this.isSyncing = false;
    }
  }

  /**
   * Push: Envoyer modifications locales
   */
  static async pushLocalChanges(): Promise<{ success: boolean; errors: string[] }> {
  const errors: string[] = [];
  
  try {
    const token = await AuthService.getToken();
    const clientId = await ClientId.getClientId();
    
    if (!token || !clientId) {
      throw new Error('Token ou Client ID manquant');
    }

    const db = getDatabase();
    const pendingData = await this.getPendingData(db);

    if (pendingData.total === 0) {
      console.log('✅ Aucune donnée à pusher');
      return { success: true, errors: [] };
    }

    console.log(`📤 Envoi de ${pendingData.total} enregistrements au serveur...`);

    // Envoi au serveur
    const response = await axios.post(
      `${API_CONFIG.BASE_URL}${API_ENDPOINTS.SYNC.PUSH}`,
      pendingData,
      {
        headers: {
          ...API_CONFIG.HEADERS,
          Authorization: `Bearer ${token}`,
          'X-Client-ID': clientId,
        },
        timeout: SYNC_CONFIG.SYNC_TIMEOUT,
      }
    );

    // Vérification critique de la confirmation du serveur
    if (!response.data.success || !response.data.confirmed) {
      console.error('❌ Serveur n\'a pas confirmé la synchronisation');
      
      const errorMsg = response.data.message || 'Synchronisation non confirmée par le serveur';
      errors.push(errorMsg);
      
      if (response.data.conflicts && response.data.conflicts.length > 0) {
        console.warn(`⚠️ ${response.data.conflicts.length} conflits détectés`);
        response.data.conflicts.forEach((conflict: any) => {
          console.warn(`  - ${conflict.table} [${conflict.local_id}]: ${conflict.reason}`);
        });
      }

      return { 
        success: false, 
        errors: errors.concat(response.data.conflicts?.map((c: any) => c.reason) || [])
      };
    }

    // Le serveur a confirmé : on peut maintenant mettre à jour localement
    const { synced, conflicts } = response.data;

    if (synced && synced.length > 0) {
      console.log(`✅ Serveur confirmé : ${synced.length} enregistrements`);
      
      // Marquer comme synchronisé UNIQUEMENT après confirmation
      await this.markAsSynced(db, synced);
      console.log(`✅ Données locales marquées comme synchronisées`);
    } else {
      console.warn('⚠️ Aucune donnée synchronisée malgré la confirmation');
    }

    if (conflicts && conflicts.length > 0) {
      console.warn(`⚠️ ${conflicts.length} conflits rencontrés`);
      await this.handleConflicts(db, conflicts);
      errors.push(`${conflicts.length} conflits nécessitent attention`);
    }

    console.log(`✅ Push terminé: ${synced.length} synchronisés, ${conflicts.length} conflits`);
    
    return { 
      success: true, 
      errors: conflicts.length > 0 ? errors : [] 
    };
    
  } catch (error: any) {
    console.error('❌ Erreur critique lors du push:', error.message);
    
    // En cas d'erreur réseau ou serveur, NE PAS synchroniser localement
    if (error.response) {
      console.error(`  Status: ${error.response.status}`);
      console.error(`  Message: ${error.response.data?.message || 'Erreur serveur'}`);
      errors.push(error.response.data?.message || 'Erreur serveur');
    } else if (error.request) {
      errors.push('Pas de réponse du serveur - vérifiez votre connexion');
    } else {
      errors.push(error.message);
    }
    
    return { success: false, errors };
  }
}

  /**
   * Pull: Récupérer données serveur
   */
  static async pullServerData(): Promise<{ success: boolean; errors: string[]; syncedData: any[] }> {
    const errors: string[] = [];
    const syncedData: any[] = [];
    
    try {
      const token = await AuthService.getToken();
      const clientId = await ClientId.getClientId();

      if (!token || !clientId) {
        throw new Error('Token ou Client ID manquant');
      }

      const db = getDatabase();
      const lastSync = this.lastSyncTime?.toISOString() || null;

      const response = await axios.get(
        `${API_CONFIG.BASE_URL}${API_ENDPOINTS.SYNC.PULL}`,
        {
          headers: {
            ...API_CONFIG.HEADERS,
            Authorization: `Bearer ${token}`,
            'X-Client-ID': clientId,
          },
          params: { last_sync: lastSync },
          timeout: SYNC_CONFIG.SYNC_TIMEOUT,
        }
      );

      console.log('📦 Données reçues du serveur');
      const serverData = response.data.data;

      // Mettre à jour la BDD locale
      await this.updateLocalDatabase(db, serverData);

      // Préparer les données pour ACK
      for (const [table, records] of Object.entries(serverData)) {
        if (Array.isArray(records) && records.length > 0) {
          syncedData.push({
            table,
            ids: records.map((r: any) => r.id),
          });
        }
      }

      console.log('✅ Pull terminé');
      return { success: true, errors: [], syncedData };
      
    } catch (error: any) {
      console.error('❌ Erreur pull:', error.message);
      return { success: false, errors: [error.message], syncedData: [] };
    }
  }

  /**
   * Envoyer confirmation de réception (ACK)
   */
  static async sendAcknowledgement(syncedData: any[]): Promise<{ success: boolean; errors: string[] }> {
    try {
      if (syncedData.length === 0) {
        return { success: true, errors: [] };
      }

      const token = await AuthService.getToken();
      const clientId = await ClientId.getClientId();

      if (!token || !clientId) {
        throw new Error('Token ou Client ID manquant');
      }

      await axios.post(
        `${API_CONFIG.BASE_URL}${API_ENDPOINTS.SYNC.ACK}`,
        { synced_data: syncedData },
        {
          headers: {
            ...API_CONFIG.HEADERS,
            Authorization: `Bearer ${token}`,
            'X-Client-ID': clientId,
          },
          timeout: SYNC_CONFIG.SYNC_TIMEOUT,
        }
      );

      console.log('✅ Acknowledgement envoyé');
      return { success: true, errors: [] };
      
    } catch (error: any) {
      console.error('❌ Erreur ACK:', error.message);
      return { success: false, errors: [error.message] };
    }
  }

  /**
   * Récupère toutes les données en attente de synchronisation
   */
  private static async getPendingData(db: any): Promise<any> {
    const receptions = await db.getAllAsync(
      "SELECT * FROM receptions_pointeur WHERE sync_status = 'pending'"
    );
    
    const retours = await db.getAllAsync(
      "SELECT * FROM retours_produits WHERE sync_status = 'pending'"
    );
    
    const inventaires = await db.getAllAsync(
      "SELECT * FROM inventaires WHERE sync_status = 'pending'"
    );
    
    const inventaire_details = await db.getAllAsync(
      "SELECT * FROM inventaire_details WHERE sync_status = 'pending'"
    );

    const sessions = await db.getAllAsync(
      "SELECT * FROM sessions_vente WHERE sync_status = 'pending'"
    );
    
    const ventes = await db.getAllAsync(
      "SELECT * FROM ventes WHERE sync_status = 'pending'"
    );

    return {
      receptions,
      retours,
      inventaires,
      inventaire_details,
      sessions,
      ventes,
      total: receptions.length + retours.length + inventaires.length + inventaire_details.length + sessions.length + ventes.length,
    };
  }

  /**
   * Marque les enregistrements comme synchronisés
   */
  private static async markAsSynced(db: any, synced: any[]): Promise<void> {
    for (const item of synced) {
      const { table, id } = item;
      
      // ✅ On utilise 'id' directement comme identifiant
      await db.runAsync(
        `UPDATE ${table} SET 
         sync_status = 'synced',
         last_synced_at = datetime('now')
         WHERE id = ?`,
        [id]
      );
      
      console.log(`✅ [markAsSynced] ${table} id=${id} → synced`);
    }
  }

  /**
   * ✅ CORRECTION : Gère les conflits de synchronisation
   * Utilise 'id' au lieu de 'local_id'
   */
  private static async handleConflicts(db: any, conflicts: any[]): Promise<void> {
    console.log(`⚠️ ${conflicts.length} conflits à gérer`);
    
    for (const conflict of conflicts) {
      const { table, id, reason } = conflict;
      
      // Marquer comme conflit
      await db.runAsync(
        `UPDATE ${table} SET sync_status = 'conflict' WHERE id = ?`,
        [id]
      );
      
      // Logger le conflit
      await db.runAsync(
        `INSERT INTO sync_log (table_name, record_id, action, status, error_message)
         VALUES (?, ?, 'push', 'error', ?)`,
        [table, id, reason]
      );
      
      console.log(`⚠️ [handleConflicts] ${table} id=${id} → conflict: ${reason}`);
    }
  }

/**
 * Met à jour la base de données locale avec les données du serveur
 */
private static async updateLocalDatabase(db: any, serverData: any): Promise<void> {
  console.log('💾 [updateLocalDatabase] Début de la mise à jour de la base locale');
  
  // 🔥 CORRECTION CRITIQUE : Extraire les vraies données
  const data = serverData.data || serverData;
  
  console.log('🔍 [updateLocalDatabase] Vérification des données à insérer:');
  console.log(`   - users: ${data.users?.length || 0} éléments`);
  console.log(`   - produits: ${data.produits?.length || 0} éléments`);
  console.log(`   - vendeurs_actifs: ${data.vendeurs_actifs?.length || 0} éléments`);
  console.log(`   - receptions_pointeur: ${data.receptions_pointeur?.length || 0} éléments`);
  console.log(`   - retours_produits: ${data.retours_produits?.length || 0} éléments`);
  console.log(`   - inventaires: ${data.inventaires?.length || 0} éléments`);
  console.log(`   - inventaire_details: ${data.inventaire_details?.length || 0} éléments`);
  console.log(`   - sessions_vente: ${data.sessions_vente?.length || 0} éléments`);
  console.log(`   - ventes: ${data.ventes?.length || 0} éléments`);

  try {
    // 1. Mise à jour des utilisateurs
    if (data.users && Array.isArray(data.users) && data.users.length > 0) {
      console.log(`👤 [updateLocalDatabase] Insertion de ${data.users.length} utilisateurs...`);
      let insertedCount = 0;
      
      for (const user of data.users) {
  try {
    // Vérifier si l'utilisateur existe déjà
    const existingUser = await db.getFirstAsync(
      'SELECT code_pin FROM users WHERE id = ?',
      [user.id]
    );

    if (existingUser) {
      // L'utilisateur existe : UPDATE sans modifier le code_pin
      await db.runAsync(
        `UPDATE users 
         SET name = ?, 
             numero_telephone = ?, 
             role = ?, 
             actif = ?, 
             preferred_language = ?, 
             sync_status = 'synced', 
             last_synced_at = datetime('now'), 
             updated_at = ?
         WHERE id = ?`,
        [
          user.name,
          user.numero_telephone,
          user.role,
          user.actif ?? 1,
          user.preferred_language ?? 'fr',
          user.updated_at,
          user.id
        ]
      );
    } else {
      // Nouvel utilisateur : INSERT avec le code_pin
      await db.runAsync(
        `INSERT INTO users
         (id, name, numero_telephone, role, code_pin, actif, preferred_language, sync_status, last_synced_at, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, 'synced', datetime('now'), ?, ?)`,
        [
          user.id,
          user.name,
          user.numero_telephone,
          user.role,
          user.code_pin,
          user.actif ?? 1,
          user.preferred_language ?? 'fr',
          user.created_at,
          user.updated_at
        ]
      );
    }
    insertedCount++;
  } catch (error) {
    console.error(`❌ [updateLocalDatabase] Erreur insertion user ${user.id}:`, error);
  }
}
      console.log(`✅ [updateLocalDatabase] ${insertedCount}/${data.users.length} utilisateurs insérés`);
    } else {
      console.log('⚠️ [updateLocalDatabase] Aucun utilisateur à insérer');
    }

    // 2. Mise à jour des produits
    if (data.produits && Array.isArray(data.produits) && data.produits.length > 0) {
      console.log(`📦 [updateLocalDatabase] Insertion de ${data.produits.length} produits...`);
      let insertedCount = 0;
      
      for (const produit of data.produits) {
        try {
          await db.runAsync(
            `INSERT OR REPLACE INTO produits
             (id, nom, prix, categorie, actif, sync_status, last_synced_at, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, 'synced', datetime('now'), ?, ?)`,
            [
              produit.id, 
              produit.nom, 
              produit.prix, 
              produit.categorie, 
              produit.actif ?? 1,
              produit.created_at,
              produit.updated_at
            ]
          );
          insertedCount++;
        } catch (error) {
          console.error(`❌ [updateLocalDatabase] Erreur insertion produit ${produit.id}:`, error);
        }
      }
      console.log(`✅ [updateLocalDatabase] ${insertedCount}/${data.produits.length} produits insérés`);
    } else {
      console.log('⚠️ [updateLocalDatabase] Aucun produit à insérer');
    }

    // 3. Mise à jour des vendeurs actifs
    if (data.vendeurs_actifs && Array.isArray(data.vendeurs_actifs) && data.vendeurs_actifs.length > 0) {
      console.log(`👥 [updateLocalDatabase] Insertion de ${data.vendeurs_actifs.length} vendeurs actifs...`);
      let insertedCount = 0;
      
      for (const va of data.vendeurs_actifs) {
        try {
          await db.runAsync(
            `INSERT OR REPLACE INTO vendeurs_actifs
             (id, categorie, vendeur_id, sync_status, last_synced_at, created_at, updated_at)
             VALUES (?, ?, ?, 'synced', datetime('now'), ?, ?)`,
            [
              va.id, 
              va.categorie, 
              va.vendeur_id,
              va.created_at,
              va.updated_at
            ]
          );
          insertedCount++;
        } catch (error) {
          console.error(`❌ [updateLocalDatabase] Erreur insertion vendeur actif ${va.id}:`, error);
        }
      }
      console.log(`✅ [updateLocalDatabase] ${insertedCount}/${data.vendeurs_actifs.length} vendeurs actifs insérés`);
    } else {
      console.log('⚠️ [updateLocalDatabase] Aucun vendeur actif à insérer');
    }

    // 4. Mise à jour des réceptions pointeur
    if (data.receptions_pointeur && Array.isArray(data.receptions_pointeur) && data.receptions_pointeur.length > 0) {
      console.log(`📥 [updateLocalDatabase] Insertion de ${data.receptions_pointeur.length} réceptions...`);
      let insertedCount = 0;
      
      for (const reception of data.receptions_pointeur) {
        try {
          await db.runAsync(
            `INSERT OR REPLACE INTO receptions_pointeur
             (id, pointeur_id, producteur_id, produit_id, quantite, vendeur_assigne_id, 
              verrou, date_reception, notes, sync_status, last_synced_at, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'synced', datetime('now'), ?, ?)`,
            [
              reception.id,
              reception.pointeur_id,
              reception.producteur_id,
              reception.produit_id,
              reception.quantite,
              reception.vendeur_assigne_id || null,
              reception.verrou ?? 0,
              reception.date_reception,
              reception.notes || null,
              reception.created_at,
              reception.updated_at
            ]
          );
          insertedCount++;
        } catch (error) {
          console.error(`❌ [updateLocalDatabase] Erreur insertion réception ${reception.id}:`, error);
        }
      }
      console.log(`✅ [updateLocalDatabase] ${insertedCount}/${data.receptions_pointeur.length} réceptions insérées`);
    } else {
      console.log('⚠️ [updateLocalDatabase] Aucune réception à insérer');
    }

    // 5. Mise à jour des retours de produits
    if (data.retours_produits && Array.isArray(data.retours_produits) && data.retours_produits.length > 0) {
      console.log(`↩️ [updateLocalDatabase] Insertion de ${data.retours_produits.length} retours...`);
      let insertedCount = 0;
      
      for (const retour of data.retours_produits) {
        try {
          await db.runAsync(
            `INSERT OR REPLACE INTO retours_produits
             (id, pointeur_id, vendeur_id, produit_id, quantite, raison, description,
              verrou, date_retour, sync_status, last_synced_at, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'synced', datetime('now'), ?, ?)`,
            [
              retour.id,
              retour.pointeur_id,
              retour.vendeur_id || null,
              retour.produit_id,
              retour.quantite,
              retour.raison,
              retour.description || null,
              retour.verrou ?? 0,
              retour.date_retour,
              retour.created_at,
              retour.updated_at
            ]
          );
          insertedCount++;
        } catch (error) {
          console.error(`❌ [updateLocalDatabase] Erreur insertion retour ${retour.id}:`, error);
        }
      }
      console.log(`✅ [updateLocalDatabase] ${insertedCount}/${data.retours_produits.length} retours insérés`);
    } else {
      console.log('⚠️ [updateLocalDatabase] Aucun retour à insérer');
    }

    // 6. Mise à jour des inventaires
    if (data.inventaires && Array.isArray(data.inventaires) && data.inventaires.length > 0) {
      console.log(`📊 [updateLocalDatabase] Insertion de ${data.inventaires.length} inventaires...`);
      let insertedCount = 0;
      
      for (const inventaire of data.inventaires) {
        try {
          await db.runAsync(
            `INSERT OR REPLACE INTO inventaires
             (id, vendeur_sortant_id, vendeur_entrant_id, date_inventaire, statut,
              sync_status, last_synced_at, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, 'synced', datetime('now'), ?, ?)`,
            [
              inventaire.id,
              inventaire.vendeur_sortant_id,
              inventaire.vendeur_entrant_id,
              inventaire.date_inventaire,
              inventaire.statut || 'en_cours',
              inventaire.created_at,
              inventaire.updated_at
            ]
          );
          insertedCount++;
        } catch (error) {
          console.error(`❌ [updateLocalDatabase] Erreur insertion inventaire ${inventaire.id}:`, error);
        }
      }
      console.log(`✅ [updateLocalDatabase] ${insertedCount}/${data.inventaires.length} inventaires insérés`);
    } else {
      console.log('⚠️ [updateLocalDatabase] Aucun inventaire à insérer');
    }

    // 7. Mise à jour des détails d'inventaire
    if (data.inventaire_details && Array.isArray(data.inventaire_details) && data.inventaire_details.length > 0) {
      console.log(`📋 [updateLocalDatabase] Insertion de ${data.inventaire_details.length} détails d'inventaire...`);
      let insertedCount = 0;
      
      for (const detail of data.inventaire_details) {
        try {
          await db.runAsync(
            `INSERT OR REPLACE INTO inventaire_details
             (id, inventaire_id, produit_id, quantite_restante)
             VALUES (?, ?, ?, ?)`,
            [
              detail.id,
              detail.inventaire_id,
              detail.produit_id,
              detail.quantite_restante ?? 0
            ]
          );
          insertedCount++;
        } catch (error) {
          console.error(`❌ [updateLocalDatabase] Erreur insertion détail inventaire ${detail.id}:`, error);
        }
      }
      console.log(`✅ [updateLocalDatabase] ${insertedCount}/${data.inventaire_details.length} détails d'inventaire insérés`);
    } else {
      console.log('⚠️ [updateLocalDatabase] Aucun détail d\'inventaire à insérer');
    }

    // 8. Mise à jour des sessions de vente
    if (data.sessions_vente && Array.isArray(data.sessions_vente) && data.sessions_vente.length > 0) {
      console.log(`🔐 [updateLocalDatabase] Insertion de ${data.sessions_vente.length} sessions de vente...`);
      let insertedCount = 0;
      
      for (const session of data.sessions_vente) {
        try {
          await db.runAsync(
            `INSERT OR REPLACE INTO sessions_vente
             (id, vendeur_id, categorie, fond_vente, orange_money_initial, mtn_money_initial,
              montant_verse, orange_money_final, mtn_money_final, manquant, statut, fermee_par,
              date_ouverture, date_fermeture, sync_status, last_synced_at, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'synced', datetime('now'), ?, ?)`,
            [
              session.id,
              session.vendeur_id,
              session.categorie,
              session.fond_vente ?? 0,
              session.orange_money_initial ?? 0,
              session.mtn_money_initial ?? 0,
              session.montant_verse || null,
              session.orange_money_final || null,
              session.mtn_money_final || null,
              session.manquant || null,
              session.statut || 'ouverte',
              session.fermee_par || null,
              session.date_ouverture,
              session.date_fermeture || null,
              session.created_at,
              session.updated_at
            ]
          );
          insertedCount++;
        } catch (error) {
          console.error(`❌ [updateLocalDatabase] Erreur insertion session ${session.id}:`, error);
        }
      }
      console.log(`✅ [updateLocalDatabase] ${insertedCount}/${data.sessions_vente.length} sessions de vente insérées`);
    } else {
      console.log('⚠️ [updateLocalDatabase] Aucune session de vente à insérer');
    }

    // 9. Mise à jour des ventes
    if (data.ventes && Array.isArray(data.ventes) && data.ventes.length > 0) {
      console.log(`💰 [updateLocalDatabase] Insertion de ${data.ventes.length} ventes...`);
      let insertedCount = 0;
      
      for (const vente of data.ventes) {
        try {
          await db.runAsync(
            `INSERT OR REPLACE INTO ventes
             (id, session_vente_id, vendeur_id, produit_id, quantite, prix_unitaire,
              prix_total, mode_paiement, date_vente, notes, sync_status, last_synced_at, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'synced', datetime('now'), ?)`,
            [
              vente.id,
              vente.session_vente_id,
              vente.vendeur_id,
              vente.produit_id,
              vente.quantite,
              vente.prix_unitaire,
              vente.prix_total,
              vente.mode_paiement,
              vente.date_vente,
              vente.notes || null,
              vente.created_at
            ]
          );
          insertedCount++;
        } catch (error) {
          console.error(`❌ [updateLocalDatabase] Erreur insertion vente ${vente.id}:`, error);
        }
      }
      console.log(`✅ [updateLocalDatabase] ${insertedCount}/${data.ventes.length} ventes insérées`);
    } else {
      console.log('⚠️ [updateLocalDatabase] Aucune vente à insérer');
    }

    console.log('═══════════════════════════════════════════════════════');
    console.log('✅ [updateLocalDatabase] Base de données locale mise à jour avec succès');
    console.log('═══════════════════════════════════════════════════════');
    
  } catch (error) {
    console.error('❌ [updateLocalDatabase] Erreur critique lors de la mise à jour:', error);
    throw error;
  }
}
  /**
   * Vérifie le statut de la synchronisation
   */
  static async getSyncStatus(): Promise<{
    lastSync: Date | null;
    pendingCount: number;
    isSyncing: boolean;
  }> {
    const db = getDatabase();
    
    const result: any = await db.getFirstAsync(`
      SELECT 
        (SELECT COUNT(*) FROM receptions_pointeur WHERE sync_status = 'pending') +
        (SELECT COUNT(*) FROM retours_produits WHERE sync_status = 'pending') +
        (SELECT COUNT(*) FROM inventaires WHERE sync_status = 'pending') +
        (SELECT COUNT(*) FROM sessions_vente WHERE sync_status = 'pending') +
        (SELECT COUNT(*) FROM ventes WHERE sync_status = 'pending') as pending_count
    `);

    return {
      lastSync: this.lastSyncTime,
      pendingCount: result?.pending_count || 0,
      isSyncing: this.isSyncing,
    };
  }

  /**
   * Démarre la synchronisation automatique
   */
  static startAutoSync(): void {
  if (!SYNC_CONFIG.AUTO_SYNC_INTERVAL) return;

  setInterval(async () => {
    const start = new Date().toLocaleTimeString();
    console.log(`🕒 [${start}] Tentative de synchronisation automatique...`);

    try {
      const result = await this.fullSync();
      const end = new Date().toLocaleTimeString();
      console.log(
        `✅ [${end}] Sync auto terminée: success=${result.success}, erreurs=${result.errors.length}`
      );
    } catch (error) {
      console.error('❌ Erreur sync auto:', error);
    }
  }, SYNC_CONFIG.AUTO_SYNC_INTERVAL);

  console.log(`✅ Synchronisation automatique activée (intervalle: ${SYNC_CONFIG.AUTO_SYNC_INTERVAL / 1000}s)`);
}

}

mais tu vas simplement adapter : au lieu de stocker dans la base de donner local sqlite tu stocke dans le stockage du navigateur

a chaque synchronisation on doit avoir un mesage qui dit si la sync a reussi ou pas et tu dois ajouter un bouton permettant de visualiser la base de donner locale

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProduitApiController;
use App\Http\Controllers\Api\ReceptionApiController;
use App\Http\Controllers\Api\RetourApiController;
use App\Http\Controllers\Api\InventaireApiController;
use App\Http\Controllers\Api\SessionVenteApiController;
use App\Http\Controllers\Api\FluxProduitApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\PdgApiController;
use App\Http\Controllers\Api\SyncApiController;
/*
|--------------------------------------------------------------------------
| API Routes - Boulangerie Pâtisserie
|--------------------------------------------------------------------------
*/

//route de test
Route::get('/test', function () {
    return response()->json(['message' => 'The ghost is in the shadow.']);
});

 Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'EasyGestBP API is healthy'
    ], 200);
});

//route de synchronisation
Route::prefix('sync')->group(function () {
        Route::get('/pull', [SyncApiController::class, 'pull']);
        Route::post('/push', [SyncApiController::class, 'push']);
        Route::get('/status', [SyncApiController::class, 'status']);
        Route::post('/ack', [SyncApiController::class, 'acknowledgement']);
    });


// Routes publiques
Route::prefix('auth')->group(function () {
    Route::post('/inscription', [AuthApiController::class, 'inscription']);
    Route::post('/connexion', [AuthApiController::class, 'connexion']);
});

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/deconnexion', [AuthApiController::class, 'deconnexion']);
        Route::get('/me', [AuthApiController::class, 'me']);
    });

   

    // Produits (PDG uniquement pour créer/modifier)
    Route::prefix('produits')->group(function () {
        Route::get('/', [ProduitApiController::class, 'index']);
        Route::get('/categorie/{categorie}', [ProduitApiController::class, 'parCategorie']);
        Route::post('/', [ProduitApiController::class, 'store'])->middleware('role:pdg');
        Route::put('/{id}', [ProduitApiController::class, 'update'])->middleware('role:pdg');
        Route::post('/{id}/toggle-actif', [ProduitApiController::class, 'toggleActif'])->middleware('role:pdg');
        Route::delete('/{id}', [ProduitApiController::class, 'destroy'])->middleware('role:pdg');
    });

    // Réceptions (Pointeur uniquement)
    Route::middleware('role:pointeur')->prefix('receptions')->group(function () {
        Route::post('/', [ReceptionApiController::class, 'store']);
        Route::put('/{id}', [ReceptionApiController::class, 'update']);
        Route::get('/mes-receptions', [ReceptionApiController::class, 'mesReceptions']);
    });

    // Réceptions pour vendeurs (lecture seule)
    Route::get('/vendeur/receptions', [ReceptionApiController::class, 'receptionsVendeur'])
        ->middleware('role:vendeur_boulangerie,vendeur_patisserie');

    // Retours (Pointeur uniquement)
    Route::middleware('role:pointeur')->prefix('retours')->group(function () {
        Route::post('/', [RetourApiController::class, 'store']);
        Route::put('/{id}', [RetourApiController::class, 'update']);
    });

    // Retours pour vendeurs (lecture seule)
    Route::get('/vendeur/retours', [RetourApiController::class, 'retoursVendeur'])
        ->middleware('role:vendeur_boulangerie,vendeur_patisserie');

    // Inventaires (Vendeurs uniquement)
    Route::middleware('role:vendeur_boulangerie,vendeur_patisserie')->prefix('inventaires')->group(function () {
        Route::post('/creer', [InventaireApiController::class, 'creer']);
        Route::get('/mes-inventaires', [InventaireApiController::class, 'mesInventaires']);
        Route::get('/en-cours', [InventaireApiController::class, 'enCours']);
    });

    // Sessions de vente
    Route::prefix('sessions-vente')->group(function () {
        // Vendeurs
        Route::middleware('role:vendeur_boulangerie,vendeur_patisserie')->group(function () {
            Route::post('/ouvrir', [SessionVenteApiController::class, 'ouvrir']);
            Route::get('/active', [SessionVenteApiController::class, 'getActive']);
            Route::get('/historique', [SessionVenteApiController::class, 'historique']);
        });
        
        // PDG
        Route::middleware('role:pdg')->group(function () {
            Route::get('/{id}', [SessionVenteApiController::class, 'show']);
            Route::get('/{id}/apercu-ventes', [SessionVenteApiController::class, 'apercuVentes']);
            Route::post('/{id}/fermer', [SessionVenteApiController::class, 'fermer']);
        });
    });

    // Flux de produits
    Route::prefix('flux')->group(function () {
        // Vendeurs voient leur propre flux
        Route::get('/mon-flux', [FluxProduitApiController::class, 'monFlux'])
            ->middleware('role:vendeur_boulangerie,vendeur_patisserie');
        
        // PDG voit tous les flux
        Route::middleware('role:pdg')->group(function () {
            Route::get('/vendeur/{vendeurId}', [FluxProduitApiController::class, 'fluxVendeur']);
            Route::get('/tous', [FluxProduitApiController::class, 'fluxTous']);
        });
    });

    // Gestion des utilisateurs (PDG uniquement)
    Route::middleware('role:pdg')->prefix('users')->group(function () {
        Route::get('/', [UserApiController::class, 'index']);
        Route::get('/role/{role}', [UserApiController::class, 'parRole']);
        Route::get('/producteurs', [UserApiController::class, 'producteurs']);
        Route::post('/', [UserApiController::class, 'store']);
        Route::put('/{id}', [UserApiController::class, 'update']);
        Route::post('/{id}/toggle-actif', [UserApiController::class, 'toggleActif']);
        Route::delete('/{id}', [UserApiController::class, 'destroy']);
    });

    Route::prefix('pdg')->middleware(['auth:sanctum', 'role:pdg'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [PdgApiController::class, 'dashboard']);
    
    // Filtrage des données
    Route::get('/receptions', [PdgApiController::class, 'getReceptions']);
    Route::get('/inventaires', [PdgApiController::class, 'getInventaires']);
    Route::get('/sessions-vente', [PdgApiController::class, 'getSessionsVente']);
    
    // Flux opérationnel
    Route::get('/flux-operationnel', [PdgApiController::class, 'getFluxOperationnel']);
    Route::get('/flux-operationnel/imprimer', [PdgApiController::class, 'imprimerFluxOperationnel']);
    
    // Sessions de vente détaillées
    Route::get('/sessions-vente-detaillees', [PdgApiController::class, 'getSessionsVenteDetaillees']);
    Route::get('/sessions-vente-detaillees/imprimer', [PdgApiController::class, 'imprimerSessionsVenteDetaillees']);
    Route::get('/sessions-vente/{id}/imprimer', [PdgApiController::class, 'imprimerSessionVente']);
    
    // Statistiques et analyses
    Route::get('/statistiques', [PdgApiController::class, 'getStatistiques']);
    Route::get('/vendeurs-performance', [PdgApiController::class, 'getVendeursPerformance']);
});
});





