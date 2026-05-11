l'app sera developper en react native + expo (et sera installer  utiliser en interne par les ipad exclusivement apres compilation avec eas)
elle sera pro et excellement styliser et ultra responsive
elle doit etre parfaite sur tablette et ipad en prioriter
elle sera offline-first avec sync auto et manuelle lorsquil ya connexion avec le serveur
la strategie de sync est simple : des qu'on retourne au dashboard un sync est automatiquement tenter s'il y'a des donnees en attente
lors l'acces au dashboard apres premiere inscription l'app pwa va pull toute les donnees du serveur et stocker en local

et a chaque acces futur a cette vue l'app va essyer auto de pull et push .
le bouton de synchronisation manuelle fera la meme chose
on va developper une app pour une boulangerie patisserie
elle va fonctionner en offline first
le pdg utilisera sur sa machine (serveur) (le backend et interface admin est deja prete)
le pointeur sur un ipad
les vendeurs sur un ipad
on va faire evoluer l'application au fur et a mesure

pour l'instant on veut la premiere fonctionnaliter (flux de produit patisserie)

premierement on a des produits (defini par leur nom , prix , categorie(boulangerie ou patisserie)

ensuite on a les pointeurs , les vendeurs et le pdg

les pointeur rexoivent les produits des producteurs et les passe au vendeurs direct
les pointeurs sont les hommes de confiance
il y'a un seul vendeur a la fois pour chaque categorie en place (xa veut dire qu'a un moment precis il y'a un seul vendeur qui vend pour chaque categorie " un seul patisserie et un seul boulangerie)

lorsque le pointeur declare qu'il a recu un produit d'un producteur(et c'est optionel si le pointeur ne veut pas specifier on va utiliser le premier producteur qui est dans la base de donnees par defaut (cette logique sera implementer directement dans le front end)) on assigne directement le produit et la quantite recu au vendeur qui est entrain de travailler pour la categorie du produit avec la possibiliter de modifier le vendeur actif directement du coter du pointeur (dans le cas ou le nouveau vendeur actif n'a pas encore sync ses donnees avec le serveur et que le pointeur a toujours un ancien vendeur actif dans sa bd local et la donc il faut absolument que le pointeur puisse modifier le vendeur actif dans sa bd locale via une option qui s'affiche lorsqu'on affiche le vendeur actif qui recevra la reception ou le retour du coter du pointeur)
le pointeur enregistre aussi les retour de produits (les produits peuvent etre retourner pour differente raison (gater , perimer ...))
lorsque le pointeur enregistre un retour de produit le serveur responsable du retour est automatiquement le serveur connecter pour la categorie du produit en question et le retour est directement lier au serveur (avec possibliter de changer le serveur actif coter pointeurr)
le pointeur peut modifier a les infos relative a une reception (qui modifiera aussi au niveau du serveur)
mais il ne peut plus modifier lorsque le serveur n'est plus connecter ou lorsque un verrou logique a ete declencher (par le pdg dans ce cas))
le vendeur n'a que 3  chose a faire avec l'application 
le pointeur peut supprimer aussi une entrer de reception ou retour si elle n'a pas encore ete sync
l'inventaire est l'operation realiser a chaque fois qu'il y'a changement de vendeur:
il consiste au serveur sortant de preciser pour chaque produit la quantite laisser a son depart
le vendeur entrant voit alors cela et consulte la liste qui s'est former au fur et  a mesure de l'ajout et ensuite les deux valide l'operation en  entrant leur code pin respectif
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
inscription et premiere connexion absolument online
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
- Indicateur parametre qui permet de specifier l'adresse du backend : 

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
  - Select: Producteur (select et par defaut le 1er producteur dans la bd ou alors le user did 1) : mais ceci se fera de faxon cacher donc pas besoin que lutilisateur voit ce champ 
  - Select: Produit (recherche)
  - Input: Quantité (numérique)
  - Textarea: Notes (optionnel)
  - Bouton: Enregistrer réception
  - Info: Vendeur assigné (affiché automatiquement(vendeur actif)) et avec un effet attirant l'attention et avec un bouton pour modifier le vendeur actif dans la bd

#### 4. Mes Réceptions
- **Header**: 
  - Titre: Mes Réceptions
  - Filtre: Date picker
  - Indicateur: Nombre total
- **Liste**:
  - Carte par réception:
    - Produit (nom + prix)
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
  - info : Vendeur assigné (affiché automatiquement(vendeur actif)) et avec un effet attirant l'attention et avec un bouton pour modifier le vendeur actif dans la bd
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
en effet le premier ecran sur le quel le vendeur doit tomber apres le login c le screen dinventaire : xa lui permet vraiment de ne pas chercher la fonctionnaliter principale et xa facilite lexperience utilisateur
#### 7. Dashboard Vendeur
- **Header**: 
  - Titre: Mes Produits Reçus
  - Date du jour
  - Indicateur sync
- **Navigation Icônes**:
  - Inventaire
  - Session caisse(bon ce n'est meme pas important que ce soit visible : tu peux laisser cette fonctionnaliter cacher)
  - Visualiser réceptions
  - Visualiser retours

- **Liste Produits Reçus** (du jour):
  - Carte par produit:
    - Nom + prix
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
  - Liste produits avec input quantité restante (il y'a 125 produits de patisserie juste donc tu vas afficher les produits exactement comme dans l'ordre de la BD (car enregistrer suivant le classement physique sur place), les produits s'affiche un par un avec leur prix respectif , l'utilisateur choisi la quantite pour chacun et passe au suivant jusquau dernier produit et appuie sur terminer : il doit avoir un systeme permettant a lutilisateur d'avancer rapidement entre les produits grace a un systeme de petit point de navigation rapide )
  - Bouton: Suivant/terminer
 - une liste en bas se forme avec les produits - prix et leur quantite specifier au fur et a mesure de linventaire

- **Étape 2: Validation Double**:
  - Liste des Résumé des quantités pour chaque produit-prix
  - Input: PIN vendeur sortant
  - Input: PIN vendeur entrant
  - Bouton: Valider inventaire
  - Info: Vous serez automatiquement déconnecté

- **Confirmation**:
  - Message succès
  - Info: Le vendeur entrant est maintenant actif
  -message "Bienvenu {Nom vendeur entrant} Nous te souhaitetons une tres belle journee"
  - Auto-déconnexion après 3 secondes


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
- **ProductCard**: Affichage produit+prix avec icône catégorie
- **SyncBadge**: Badge état synchronisation
- **ConnectionIndicator**: icon wifi indicateur connexion avec le serveur
- **DateFilter**: Sélecteur de date stylisé
- **NumericInput**: Input numérique avec +/- buttons
- **PINInput**: Input 6 chiffres avec securiter (avec 6 champs independant permetaant d'entre chiffre par chiffre)
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

le responsive est la clair (parfait peut importe l'appareil mais surtout sur ipad en paysage et en portrait)
---

genere moi le code react native + expo  permettant d'avoir xa
ensuite donne moi fichier readme.md qui me donne la liste des commande expo a taper pour installer les dependances et ensuite les manipulations a faire pour tester lapp
je teste app avec expo go



# EasyGestBP — React Native Frontend Requirements

> **Document destiné à l'ingénieur frontend React Native.**  
> Contient tout ce qu'il faut pour consommer le backend Laravel/Sanctum.

---

## 1. Informations générales

| Champ | Valeur |
|---|---|
| Backend | Laravel 10+ avec Sanctum |
| Base URL | `https://<votre-domaine>/api` |
| Format | JSON |
| Auth | Bearer Token (Sanctum) |
| Timezone | `Africa/Douala` (UTC+1) |
| Langue principale | Français |

---

## 2. Rôles utilisateurs

| Rôle | Description |
|---|---|
| `pdg` | Directeur — accès total, ferme les sessions, voit les stats |
| `pointeur` | Enregistre les réceptions et retours de produits |
| `vendeur_boulangerie` | Vendeur du rayon boulangerie |
| `vendeur_patisserie` | Vendeur du rayon pâtisserie |
| `producteur` | Fournisseur (lecture seule dans certaines vues) |

---

## 3. Headers obligatoires

Toutes les requêtes doivent inclure :

```
Content-Type: application/json
Accept: application/json
X-Client-ID: <uuid-du-device>   // OBLIGATOIRE pour sync offline
```

Routes protégées :
```
Authorization: Bearer <token>
```

---

## 4. Authentification

### 4.1 Inscription
`POST /api/auth/inscription`

**Body :**
```json
{
  "name": "Jean Dupont",
  "numero_telephone": "690123456",
  "code_pin": "123456",
  "role": "vendeur_boulangerie",
  "preferred_language": "fr",
  "device_info": "Samsung Galaxy A54",
  "has_client_id": true,
  "client_id": "uuid-existant-ou-nouveau",
  "code_pdg": "PDG2025SECURE"  // REQUIS uniquement si role = pdg
}
```

**Réponse 201 :**
```json
{
  "success": true,
  "message": "Inscription réussie",
  "user": { "id": 1, "name": "Jean Dupont", "role": "vendeur_boulangerie", ... },
  "token": "1|abc123...",
  "client_id": "uuid-du-client"
}
```

**Notes importantes :**
- `numero_telephone` : 9 chiffres camerounais (sans 237). Accepte aussi `237XXXXXXXXX`.
- `code_pin` : exactement 6 caractères.
- Stocker le `client_id` retourné en local persistant (AsyncStorage/SecureStore). Ne jamais le régénérer si déjà présent.

### 4.2 Connexion
`POST /api/auth/connexion`

**Body :**
```json
{
  "numero_telephone": "690123456",
  "code_pin": "123456"
}
```
Envoyer aussi le header `X-Client-ID`.

**Réponse 200 :**
```json
{
  "success": true,
  "user": { ... },
  "token": "2|xyz...",
  "client_id": "uuid-du-client"
}
```

### 4.3 Déconnexion
`POST /api/auth/deconnexion` *(auth requise)*

### 4.4 Utilisateur connecté
`GET /api/auth/me` *(auth requise)*

---

## 5. Produits

| Méthode | Endpoint | Rôle |
|---|---|---|
| GET | `/api/produits` | Tous |
| GET | `/api/produits/categorie/{categorie}` | Tous |
| POST | `/api/produits` | pdg |
| PUT | `/api/produits/{id}` | pdg |
| POST | `/api/produits/{id}/toggle-actif` | pdg |
| DELETE | `/api/produits/{id}` | pdg |

**Valeurs de `categorie` :** `boulangerie` | `patisserie`

**Query param :** `?actif_only=true` (par défaut `true`)

**Modèle Produit :**
```json
{
  "id": 1,
  "nom": "Baguette tradition",
  "prix": 250.00,
  "categorie": "boulangerie",
  "actif": true
}
```

**Body création/modification :**
```json
{
  "nom": "Croissant",
  "prix": 500.00,
  "categorie": "patisserie"
}
```

---

## 6. Réceptions (Pointeur)

### Créer une réception
`POST /api/receptions` *(role: pointeur)*

```json
{
  "producteur_id": 3,
  "produit_id": 2,
  "quantite": 50,
  "notes": "Livraison du matin"
}
```

Le `vendeur_assigne_id` est automatiquement déduit par le backend selon le vendeur actif de la catégorie.

### Modifier
`PUT /api/receptions/{id}` *(role: pointeur)*

```json
{
  "quantite": 55,
  "notes": "Correction"
}
```

⚠️ Impossible si `verrou = true` ou si le vendeur actif a changé.

### Mes réceptions
`GET /api/receptions/mes-receptions?date=2025-01-15` *(role: pointeur)*

### Réceptions d'un vendeur
`GET /api/vendeur/receptions?date=2025-01-15` *(role: vendeur_boulangerie ou vendeur_patisserie)*

**Modèle Réception :**
```json
{
  "id": 10,
  "pointeur_id": 2,
  "producteur_id": 3,
  "produit_id": 1,
  "quantite": 50,
  "vendeur_assigne_id": 5,
  "verrou": false,
  "date_reception": "2025-01-15T07:30:00+01:00",
  "notes": null
}
```

---

## 7. Retours (Pointeur)

### Créer
`POST /api/retours` *(role: pointeur)*

```json
{
  "produit_id": 1,
  "quantite": 5,
  "raison": "perime",
  "description": "Produits périmés du matin"
}
```

**Valeurs de `raison` :** `perime` | `abime` | `autre`

### Modifier
`PUT /api/retours/{id}` *(role: pointeur)*

### Retours d'un vendeur
`GET /api/vendeur/retours?date=2025-01-15` *(role: vendeur)*

---

## 8. Inventaires (Vendeurs)

### Créer un inventaire
`POST /api/inventaires/creer` *(role: vendeur)*

L'inventaire est une **passation de poste** entre vendeur sortant et entrant. Les deux codes PIN sont requis.

```json
{
  "vendeur_sortant_id": 5,
  "vendeur_entrant_id": 7,
  "code_pin_sortant": "123456",
  "code_pin_entrant": "654321",
  "produits": [
    { "produit_id": 1, "quantite_restante": 12 },
    { "produit_id": 2, "quantite_restante": 5 }
  ]
}
```

⚠️ Les deux vendeurs doivent être de la même catégorie.

### Mes inventaires
`GET /api/inventaires/mes-inventaires` *(role: vendeur)*

### Inventaire en cours
`GET /api/inventaires/en-cours` *(role: vendeur)*

**Modèle Inventaire :**
```json
{
  "id": 3,
  "vendeur_sortant_id": 5,
  "vendeur_entrant_id": 7,
  "categorie": "boulangerie",
  "valide_sortant": true,
  "valide_entrant": true,
  "date_inventaire": "2025-01-15T18:00:00+01:00",
  "details": [
    { "produit_id": 1, "quantite_restante": 12 }
  ]
}
```

---

## 9. Sessions de vente

### Ouvrir une session
`POST /api/sessions-vente/ouvrir` *(role: vendeur)*

```json
{
  "categorie": "boulangerie",
  "fond_vente": 5000.00,
  "orange_money_initial": 0.00,
  "mtn_money_initial": 0.00
}
```

### Session active
`GET /api/sessions-vente/active` *(role: vendeur)*

### Historique
`GET /api/sessions-vente/historique?statut=fermee&date_debut=2025-01-01&date_fin=2025-01-31`

### Détails d'une session *(pdg)*
`GET /api/sessions-vente/{id}`

### Aperçu des ventes *(pdg)*
`GET /api/sessions-vente/{id}/apercu-ventes`

### Fermer une session *(pdg)*
`POST /api/sessions-vente/{id}/fermer`

```json
{
  "montant_verse": 45000.00,
  "orange_money_final": 2500.00,
  "mtn_money_final": 1500.00
}
```

**Réponse :**
```json
{
  "success": true,
  "data": {
    "session": { ... },
    "ventes_totales": 48000.00,
    "details_calcul": [
      {
        "produit": "Baguette",
        "prix_unitaire": 250,
        "stock_initial": 100,
        "entrees": 50,
        "retours": 5,
        "stock_final": 20,
        "quantite_vendue": 125,
        "montant_vendu": 31250
      }
    ]
  }
}
```

**Formule de calcul du manquant :**
```
Manquant = (Ventes + Fond) - (Versé + ΔOrange Money + ΔMTN Money)
```

**Modèle Session :**
```json
{
  "id": 10,
  "vendeur_id": 5,
  "categorie": "boulangerie",
  "fond_vente": 5000,
  "orange_money_initial": 0,
  "mtn_money_initial": 0,
  "montant_verse": 45000,
  "orange_money_final": 2500,
  "mtn_money_final": 1500,
  "manquant": 0,
  "valeur_vente": 48000,
  "statut": "fermee",
  "fermee_par": 1,
  "date_ouverture": "2025-01-15T07:00:00+01:00",
  "date_fermeture": "2025-01-15T18:00:00+01:00"
}
```

---

## 10. Flux produits

| Méthode | Endpoint | Rôle |
|---|---|---|
| GET | `/api/flux/mon-flux` | vendeur |
| GET | `/api/flux/vendeur/{vendeurId}` | pdg |
| GET | `/api/flux/tous` | pdg |

---

## 11. Gestion des utilisateurs *(pdg uniquement)*

| Méthode | Endpoint |
|---|---|
| GET | `/api/users` |
| GET | `/api/users/role/{role}` |
| GET | `/api/users/producteurs` |
| POST | `/api/users` |
| PUT | `/api/users/{id}` |
| POST | `/api/users/{id}/toggle-actif` |
| DELETE | `/api/users/{id}` |

---

## 12. Dashboard PDG

| Méthode | Endpoint | Description |
|---|---|---|
| GET | `/api/pdg/dashboard` | Vue globale |
| GET | `/api/pdg/receptions` | Toutes les réceptions |
| GET | `/api/pdg/inventaires` | Tous les inventaires |
| GET | `/api/pdg/sessions-vente` | Toutes les sessions |
| GET | `/api/pdg/flux-operationnel` | Flux complet |
| GET | `/api/pdg/sessions-vente-detaillees` | Sessions détaillées |
| GET | `/api/pdg/statistiques` | Stats globales |
| GET | `/api/pdg/vendeurs-performance` | Perf par vendeur |

---

## 13. Synchronisation Offline (CRITIQUE)

L'application doit fonctionner **100% offline** et synchroniser avec le serveur dès que la connexion est disponible. Le mécanisme repose sur un `client_id` UUID unique par appareil et une colonne `synced_clients` côté serveur.

### 13.1 Architecture offline recommandée

```
SQLite local (WatermelonDB ou expo-sqlite)
       ↕
SyncManager (background service)
       ↕
API Sync (/api/sync/pull + /api/sync/push)
```

### 13.2 Pull — Récupérer les données du serveur
`GET /api/sync/pull?last_sync=2025-01-15T06:00:00+01:00`

Header requis : `X-Client-ID: <uuid>`

**Réponse :**
```json
{
  "success": true,
  "data": {
    "users": [...],
    "produits": [...],
    "vendeurs_actifs": [...],
    "receptions_pointeur": [...],
    "retours_produits": [...],
    "sessions_vente": [...]
  },
  "sync_time": "2025-01-15T07:00:00+01:00"
}
```

**Comportement :** Le serveur envoie les enregistrements **non encore synchronisés** pour ce `client_id`, OU mis à jour depuis `last_sync + 1 minute`.

**À faire côté client :**
1. Stocker le `sync_time` reçu comme prochain `last_sync`.
2. Upsert tous les enregistrements dans la DB locale.
3. Envoyer un ACK pour signaler la réception.

### 13.3 ACK — Confirmer la réception
`POST /api/sync/ack`

```json
{
  "synced_data": [
    { "table": "receptions_pointeur", "ids": [1, 2, 3] },
    { "table": "sessions_vente", "ids": [10] }
  ]
}
```

### 13.4 Push — Envoyer les données locales
`POST /api/sync/push`

```json
{
  "receptions": [
    {
      "local_id": "local-uuid-123",
      "id": null,
      "pointeur_id": 2,
      "producteur_id": 3,
      "produit_id": 1,
      "quantite": 50,
      "vendeur_assigne_id": 5,
      "verrou": false,
      "date_reception": "2025-01-15T07:30:00+01:00",
      "notes": null
    }
  ],
  "retours": [...],
  "inventaires": [
    {
      "local_id": "local-inv-456",
      "id": null,
      "vendeur_sortant_id": 5,
      "vendeur_entrant_id": 7,
      "produits": [...]
    }
  ],
  "inventaire_details": [
    {
      "inventaire_id": null,
      "inventaire_local_id": "local-inv-456",
      "produit_id": 1,
      "quantite_restante": 12
    }
  ],
  "sessions": [...]
}
```

**Réponse :**
```json
{
  "success": true,
  "confirmed": true,
  "synced": [
    {
      "table": "receptions_pointeur",
      "local_id": "local-uuid-123",
      "id": null,
      "server_id": 45
    },
    {
      "table": "inventaires",
      "local_id": "local-inv-456",
      "id": null,
      "server_id": 8
    }
  ],
  "conflicts": [],
  "sync_time": "2025-01-15T07:32:00+01:00"
}
```

**Règles importantes pour le push :**
- Utiliser `local_id` (UUID généré localement) pour les nouveaux enregistrements.
- Utiliser `id` (server_id) pour les mises à jour d'enregistrements déjà synchronisés.
- Pour `inventaire_details`, utiliser `inventaire_local_id` si l'inventaire parent n'a pas encore de `server_id`.
- Après le push, mettre à jour la DB locale avec les `server_id` reçus dans `synced`.

### 13.5 Status de synchronisation
`GET /api/sync/status`

### 13.6 Stratégie locale recommandée

Chaque table locale doit avoir :
```
id            INTEGER PRIMARY KEY AUTOINCREMENT
server_id     INTEGER NULL         -- null = pas encore synchronisé
local_id      TEXT UNIQUE          -- UUID généré localement
is_synced     BOOLEAN DEFAULT 0
updated_at    DATETIME
```

**Ordre de sync (respecter les FK) :**
1. Pull → users, produits, vendeurs_actifs
2. Push → receptions, retours
3. Push → sessions
4. Push → inventaires, puis inventaire_details

---

## 14. Codes d'erreur HTTP

| Code | Signification |
|---|---|
| 200 | OK |
| 201 | Créé |
| 400 | Données invalides |
| 401 | Non authentifié |
| 403 | Non autorisé (rôle insuffisant) |
| 404 | Ressource introuvable |
| 422 | Conflits de synchronisation |
| 500 | Erreur serveur |

**Format d'erreur standard :**
```json
{
  "success": false,
  "message": "Description de l'erreur"
}
```

---

## 15. Gestion des dates

- Toutes les dates sont en **ISO 8601** avec offset `+01:00` (Africa/Douala).
- Exemple : `"2025-01-15T07:30:00+01:00"`
- Côté client : utiliser `dayjs` ou `date-fns-tz` avec le timezone `Africa/Douala`.
- Lors du push, toujours envoyer les dates avec le timezone.

---

## 16. Logique métier importante

### Vendeur actif
- À tout moment, une seule personne est vendeur actif par catégorie (`boulangerie` ou `patisserie`).
- Le vendeur actif change lors d'un inventaire de passation.
- Les réceptions et retours sont automatiquement assignés au vendeur actif.

### Verrouillage (`verrou`)
- Une réception ou un retour avec `verrou = true` **ne peut plus être modifié**.
- Seul le PDG peut verrouiller via l'admin web (pas exposé dans l'API mobile).

### Calcul des ventes (fermeture de session)
```
Ventes = Σ produit [ (Stock_initial + Entrées - Retours - Stock_final) × Prix ]
```
Ce calcul nécessite deux inventaires : un d'entrée (vendeur entrant) et un de sortie (vendeur sortant) dans une fenêtre de 24h.

### Manquant
```
Manquant = (Ventes + Fond_de_caisse) - (Montant_versé + ΔOrange_Money + ΔMTN_Money)
```

---

## 17. Écrans suggérés par rôle

### Vendeur (boulangerie / patisserie)
- Accueil : statut actif, session en cours, résumé du jour
- Réceptions du jour (lecture seule)
- Retours du jour (lecture seule)
- Ouvrir session de vente
- Créer inventaire (passation de poste)
- Historique sessions

### Pointeur
- Enregistrer une réception (sélection produit, producteur, quantité)
- Enregistrer un retour (sélection produit, raison, quantité)
- Liste des réceptions du jour
- Liste des retours du jour

### PDG
- Dashboard (totaux, alertes)
- Liste sessions de vente avec statut
- Fermer une session (saisie montants, aperçu automatique)
- Réceptions et retours (toutes catégories)
- Inventaires
- Gestion des utilisateurs et produits
- Statistiques / performances vendeurs

---

## 18. Librairies React Native recommandées

| Besoin | Librairie suggérée |
|---|---|
| Requêtes HTTP | `axios` |
| Stockage sécurisé token | `expo-secure-store` |
| DB locale offline | `expo-sqlite` ou `WatermelonDB` |
| Gestion du state | `zustand` ou `redux-toolkit` |
| Navigation | `react-navigation` v6 |
| Dates / timezone | `dayjs` + plugin `timezone` |
| UUID local | `react-native-uuid` ou `expo-crypto` |
| Détection connexion | `@react-native-community/netinfo` |
| Formulaires | `react-hook-form` |
| UI | `react-native-paper` ou `NativeBase` |

---

## 19. Checklist d'implémentation

- [ ] Générer et persister le `client_id` (UUID) au premier lancement
- [ ] Envoyer `X-Client-ID` dans tous les headers
- [ ] Implémenter le token refresh / logout automatique sur 401
- [ ] Créer la DB locale SQLite avec les tables miroirs
- [ ] Implémenter le SyncManager (pull → ack → push) au démarrage et en background
- [ ] Gérer les `local_id` pour les créations offline
- [ ] Mapper les `server_id` après un push réussi
- [ ] Gérer les conflits de sync retournés dans `conflicts[]`
- [ ] Respecter l'ordre des FK lors du push
- [ ] Afficher un indicateur de synchronisation dans l'UI
- [ ] Tester le mode 100% offline puis reconnexion

---

*Dernière mise à jour : générée depuis le code source backend EasyGestBP*


genere moi un code react native qui permet d'avoir l'app fonctionnel, performant , reactif , stable 

genere juste le code : je vais cloner et executer dans mon environnement