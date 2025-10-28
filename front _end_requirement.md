# 📋 Brief Technique - Frontend Mobile Boulangerie/Pâtisserie

## 🎯 Objectif
Application mobile de gestion boulangerie/pâtisserie pour **Pointeurs** et **Vendeurs** fonctionnant **totalement offline** avec sync bidirectionnelle vers serveur Laravel.

---

## 🗃️ Architecture

### Stack Technique
- **Frontend**: React Native (Expo)
- **Base locale**: SQLite (via expo-sqlite)
- **Sync**: Bidirectionnelle automatique avec API Laravel
- **Authentification**: Token Bearer (Sanctum)
- **Stockage sécurisé**: Expo SecureStore (token)
- **Déploiement**: APK Android uniquement
- **Note**: Le PDG utilise le serveur Laravel directement sur PC

### Structure du Projet
```
mobile-app/
├── src/
│   ├── api/              # Appels API Laravel
│   ├── components/       # Composants réutilisables
│   ├── screens/          # Écrans par rôle
│   │   ├── auth/
│   │   ├── pointeur/
│   │   └── vendeur/
│   ├── database/         # SQLite setup & migrations
│   ├── services/         # Logique métier
│   │   ├── sync/         # Service de synchronisation
│   │   ├── auth/         # Gestion authentification
│   │   └── offline/      # Gestion mode offline
│   ├── utils/            # Helpers & constants
│   └── navigation/       # Configuration navigation
├── assets/               # Images, fonts, icons
└── app.json             # Configuration Expo
```

---

## 👥 2 Types d'Utilisateurs Mobile

### 1️⃣ **Pointeur** (Tablette/Mobile)
**Barre icônes**: Réception | Retour | Déconnexion

**Dashboard**: Formulaire direct de réception pré-rempli
- Réception produit → assigne auto au vendeur actif de la catégorie
- Retour produit → lié auto au vendeur actif
- Modification possible si vendeur connecté ET pas de verrou PDG

**Fonctionnalités principales**:
- Enregistrer réceptions (producteur, produit, quantité)
- Enregistrer retours (produit, quantité, raison)
- Visualiser mes réceptions du jour
- Visualiser mes retours du jour
- Modifier mes réceptions/retours non verrouillés

### 2️⃣ **Vendeur** (Tablette/Mobile)
**Dashboard**: Liste produits reçus (entrées du pointeur)

**Barre icônes**: Inventaire | Session caisse | Visualisation réceptions | Visualisation retours

**Fonctionnalités principales**:
- **Inventaire** : 
  - Vendeur sortant saisit quantités restantes
  - Vendeur entrant valide
  - Les 2 entrent leur PIN 6 chiffres
  - Switch automatique (sortant déconnecté, entrant connecté)
- **Session de vente** : 
  - Créer avec 3 montants (fond vente, Orange Money, MTN Money) - défaut 0
  - Voir aperçu des ventes en cours
  - Note: Seul le PDG peut fermer les sessions
- **Visualisation**:
  - Mes réceptions du jour
  - Mes retours du jour
  - Mon flux de produits (trouvé/reçu/retourné/restant/vendu)

---

## 🔐 Authentification

### PREMIER USAGE (nécessite réseau):
1. Créer compte (nom, téléphone, rôle, PIN 6 chiffres)
   - Note: Le code PDG n'est pas géré dans l'app mobile
2. Sync données initiales → Prêt

### USAGES SUIVANTS:
- **ONLINE**: PIN → Sync auto
- **OFFLINE**: PIN → Accès local

---

## 🗄️ Tables Principales SQLite

### users
```sql
CREATE TABLE users (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  numero_telephone TEXT UNIQUE NOT NULL,
  role TEXT NOT NULL,
  code_pin TEXT NOT NULL,
  actif BOOLEAN DEFAULT 1,
  preferred_language TEXT DEFAULT 'fr',
  -- Champs de synchronisation
  sync_status TEXT DEFAULT 'synced', -- 'synced' | 'pending' | 'conflict'
  last_synced_at TEXT,
  created_at TEXT,
  updated_at TEXT
);
```

### produits
```sql
CREATE TABLE produits (
  id INTEGER PRIMARY KEY,
  nom TEXT NOT NULL,
  prix REAL NOT NULL,
  categorie TEXT NOT NULL, -- 'boulangerie' | 'patisserie'
  actif BOOLEAN DEFAULT 1,
  -- Champs de synchronisation
  sync_status TEXT DEFAULT 'synced',
  last_synced_at TEXT,
  created_at TEXT,
  updated_at TEXT
);
```

### vendeurs_actifs
```sql
CREATE TABLE vendeurs_actifs (
  id INTEGER PRIMARY KEY,
  categorie TEXT UNIQUE NOT NULL,
  vendeur_id INTEGER NOT NULL,
  -- Champs de synchronisation
  sync_status TEXT DEFAULT 'synced',
  last_synced_at TEXT,
  created_at TEXT,
  updated_at TEXT,
  FOREIGN KEY (vendeur_id) REFERENCES users(id)
);
```

### receptions_pointeur
```sql
CREATE TABLE receptions_pointeur (
  id INTEGER PRIMARY KEY,
  local_id TEXT UNIQUE, -- UUID généré localement
  pointeur_id INTEGER NOT NULL,
  producteur_id INTEGER NOT NULL,
  produit_id INTEGER NOT NULL,
  quantite INTEGER NOT NULL,
  vendeur_assigne_id INTEGER,
  verrou BOOLEAN DEFAULT 0,
  date_reception TEXT NOT NULL,
  notes TEXT,
  -- Champs de synchronisation
  sync_status TEXT DEFAULT 'pending',
  last_synced_at TEXT,
  created_at_local TEXT,
  updated_at_local TEXT,
  FOREIGN KEY (pointeur_id) REFERENCES users(id),
  FOREIGN KEY (producteur_id) REFERENCES users(id),
  FOREIGN KEY (produit_id) REFERENCES produits(id),
  FOREIGN KEY (vendeur_assigne_id) REFERENCES users(id)
);
```

### retours_produits
```sql
CREATE TABLE retours_produits (
  id INTEGER PRIMARY KEY,
  local_id TEXT UNIQUE,
  pointeur_id INTEGER NOT NULL,
  vendeur_id INTEGER,
  produit_id INTEGER NOT NULL,
  quantite INTEGER NOT NULL,
  raison TEXT NOT NULL, -- 'perime' | 'abime' | 'autre'
  description TEXT,
  verrou BOOLEAN DEFAULT 0,
  date_retour TEXT NOT NULL,
  -- Champs de synchronisation
  sync_status TEXT DEFAULT 'pending',
  last_synced_at TEXT,
  created_at_local TEXT,
  updated_at_local TEXT,
  FOREIGN KEY (pointeur_id) REFERENCES users(id),
  FOREIGN KEY (vendeur_id) REFERENCES users(id),
  FOREIGN KEY (produit_id) REFERENCES produits(id)
);
```

### inventaires
```sql
CREATE TABLE inventaires (
  id INTEGER PRIMARY KEY,
  local_id TEXT UNIQUE,
  vendeur_sortant_id INTEGER NOT NULL,
  vendeur_entrant_id INTEGER NOT NULL,
  categorie TEXT NOT NULL,
  valide_sortant BOOLEAN DEFAULT 0,
  valide_entrant BOOLEAN DEFAULT 0,
  date_inventaire TEXT NOT NULL,
  -- Champs de synchronisation
  sync_status TEXT DEFAULT 'pending',
  last_synced_at TEXT,
  created_at_local TEXT,
  updated_at_local TEXT,
  FOREIGN KEY (vendeur_sortant_id) REFERENCES users(id),
  FOREIGN KEY (vendeur_entrant_id) REFERENCES users(id)
);
```

### inventaire_details
```sql
CREATE TABLE inventaire_details (
  id INTEGER PRIMARY KEY,
  local_id TEXT UNIQUE,
  inventaire_id INTEGER NOT NULL,
  produit_id INTEGER NOT NULL,
  quantite_restante INTEGER NOT NULL,
  -- Champs de synchronisation
  sync_status TEXT DEFAULT 'pending',
  last_synced_at TEXT,
  FOREIGN KEY (inventaire_id) REFERENCES inventaires(id),
  FOREIGN KEY (produit_id) REFERENCES produits(id)
);
```

### sessions_vente
```sql
CREATE TABLE sessions_vente (
  id INTEGER PRIMARY KEY,
  local_id TEXT UNIQUE,
  vendeur_id INTEGER NOT NULL,
  categorie TEXT NOT NULL,
  fond_vente REAL DEFAULT 0,
  orange_money_initial REAL DEFAULT 0,
  mtn_money_initial REAL DEFAULT 0,
  orange_money_final REAL,
  mtn_money_final REAL,
  montant_verse REAL,
  manquant REAL,
  statut TEXT DEFAULT 'ouverte', -- 'ouverte' | 'fermee'
  date_ouverture TEXT NOT NULL,
  date_fermeture TEXT,
  -- Champs de synchronisation
  sync_status TEXT DEFAULT 'pending',
  last_synced_at TEXT,
  created_at_local TEXT,
  updated_at_local TEXT,
  FOREIGN KEY (vendeur_id) REFERENCES users(id)
);
```

### sync_queue (Table technique pour gérer la queue de sync)
```sql
CREATE TABLE sync_queue (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  table_name TEXT NOT NULL,
  record_id TEXT NOT NULL, -- local_id ou id
  operation TEXT NOT NULL, -- 'create' | 'update' | 'delete'
  data TEXT NOT NULL, -- JSON des données
  retry_count INTEGER DEFAULT 0,
  last_error TEXT,
  created_at TEXT NOT NULL
);
```

---

## 📡 API Endpoints & Formats de Réponse

### 1. Authentification

#### POST /auth/inscription
**Request**:
```json
{
  "nom": "Jean Dupont",
  "numero_telephone": "699123456",
  "role": "pointeur",
  "code_pin": "123456",
  "preferred_language": "fr"
}
```

**Response Success (201)**:
```json
{
  "success": true,
  "message": "Inscription réussie",
  "data": {
    "id": 1,
    "name": "Jean Dupont",
    "numero_telephone": "699123456",
    "role": "pointeur",
    "actif": true,
    "preferred_language": "fr"
  }
}
```

**Response Error (400)**:
```json
{
  "success": false,
  "message": "Le numéro de téléphone est déjà utilisé"
}
```

#### POST /auth/connexion
**Request**:
```json
{
  "numero_telephone": "699123456",
  "code_pin": "123456"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Connexion réussie",
  "data": {
    "user": {
      "id": 1,
      "name": "Jean Dupont",
      "numero_telephone": "699123456",
      "role": "pointeur",
      "actif": true,
      "preferred_language": "fr"
    },
    "token": "1|abcdef123456..."
  }
}
```

**Response Error (401)**:
```json
{
  "success": false,
  "message": "Numéro de téléphone ou code PIN incorrect"
}
```

#### POST /auth/deconnexion
**Headers**: `Authorization: Bearer {token}`

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Déconnexion réussie"
}
```

#### GET /auth/me
**Headers**: `Authorization: Bearer {token}`

**Response Success (200)**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Jean Dupont",
    "numero_telephone": "699123456",
    "role": "pointeur",
    "actif": true,
    "preferred_language": "fr"
  }
}
```

---

### 2. Produits

#### GET /produits?actif_only=true
**Headers**: `Authorization: Bearer {token}`

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nom": "Pain complet",
      "categorie": "boulangerie",
      "prix": 500,
      "actif": true,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z"
    },
    {
      "id": 2,
      "nom": "Croissant",
      "categorie": "patisserie",
      "prix": 300,
      "actif": true,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### GET /produits/categorie/{boulangerie|patisserie}?actif_only=true
**Headers**: `Authorization: Bearer {token}`

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nom": "Pain complet",
      "categorie": "boulangerie",
      "prix": 500,
      "actif": true,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z"
    }
  ]
}
```

---

### 3. Réceptions (Pointeur)

#### POST /receptions
**Headers**: `Authorization: Bearer {token}`

**Request**:
```json
{
  "producteur_id": 5,
  "produit_id": 1,
  "quantite": 50,
  "notes": "Livraison du matin"
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
    "quantite": 50,
    "vendeur_assigne_id": 3,
    "verrou": false,
    "date_reception": "2025-10-22T10:30:00.000000Z",
    "notes": "Livraison du matin",
    "created_at": "2025-10-22T10:30:00.000000Z",
    "updated_at": "2025-10-22T10:30:00.000000Z",
    "produit": {
      "id": 1,
      "nom": "Pain complet",
      "categorie": "boulangerie",
      "prix": 500
    },
    "producteur": {
      "id": 5,
      "name": "Boulangerie Martin",
      "numero_telephone": "699111222"
    },
    "vendeurAssigne": {
      "id": 3,
      "name": "Marie Kouam",
      "role": "vendeur_boulangerie"
    }
  }
}
```

**Response Error (400)**:
```json
{
  "success": false,
  "message": "Aucun vendeur actif pour cette catégorie de produit"
}
```

#### PUT /receptions/{id}
**Headers**: `Authorization: Bearer {token}`

**Request**:
```json
{
  "quantite": 55,
  "notes": "Livraison du matin - rectifiée"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Réception mise à jour avec succès",
  "data": {
    "id": 1,
    "pointeur_id": 2,
    "producteur_id": 5,
    "produit_id": 1,
    "quantite": 55,
    "vendeur_assigne_id": 3,
    "verrou": false,
    "date_reception": "2025-10-22T10:30:00.000000Z",
    "notes": "Livraison du matin - rectifiée",
    "updated_at": "2025-10-22T10:45:00.000000Z"
  }
}
```

**Response Error (403)**:
```json
{
  "success": false,
  "message": "Cette réception est verrouillée et ne peut plus être modifiée"
}
```

#### GET /receptions/mes-receptions?date=2025-10-22
**Headers**: `Authorization: Bearer {token}`

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
      "quantite": 50,
      "vendeur_assigne_id": 3,
      "verrou": false,
      "date_reception": "2025-10-22T10:30:00.000000Z",
      "notes": "Livraison du matin",
      "produit": {
        "id": 1,
        "nom": "Pain complet",
        "prix": 500
      },
      "producteur": {
        "id": 5,
        "name": "Boulangerie Martin"
      },
      "vendeurAssigne": {
        "id": 3,
        "name": "Marie Kouam"
      }
    }
  ]
}
```

#### GET /vendeur/receptions?date=2025-10-22
**Headers**: `Authorization: Bearer {token}`

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "produit_id": 1,
      "quantite": 50,
      "date_reception": "2025-10-22T10:30:00.000000Z",
      "notes": "Livraison du matin",
      "produit": {
        "id": 1,
        "nom": "Pain complet",
        "categorie": "boulangerie",
        "prix": 500
      },
      "producteur": {
        "id": 5,
        "name": "Boulangerie Martin"
      }
    }
  ]
}
```

---

### 4. Retours (Pointeur)

#### POST /retours
**Headers**: `Authorization: Bearer {token}`

**Request**:
```json
{
  "produit_id": 1,
  "quantite": 5,
  "raison": "abime",
  "description": "Pain brûlé"
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
    "raison": "abime",
    "description": "Pain brûlé",
    "verrou": false,
    "date_retour": "2025-10-22T14:00:00.000000Z",
    "created_at": "2025-10-22T14:00:00.000000Z",
    "produit": {
      "id": 1,
      "nom": "Pain complet",
      "categorie": "boulangerie"
    },
    "vendeur": {
      "id": 3,
      "name": "Marie Kouam"
    }
  }
}
```

**Response Error (400)**:
```json
{
  "success": false,
  "message": "Aucun vendeur actif pour cette catégorie de produit"
}
```

#### PUT /retours/{id}
**Headers**: `Authorization: Bearer {token}`

**Request**:
```json
{
  "quantite": 6,
  "raison": "abime",
  "description": "Pain brûlé - quantité rectifiée"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Retour mis à jour avec succès",
  "data": {
    "id": 1,
    "pointeur_id": 2,
    "vendeur_id": 3,
    "produit_id": 1,
    "quantite": 6,
    "raison": "abime",
    "description": "Pain brûlé - quantité rectifiée",
    "verrou": false,
    "updated_at": "2025-10-22T14:15:00.000000Z"
  }
}
```

#### GET /vendeur/retours?date=2025-10-22
**Headers**: `Authorization: Bearer {token}`

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "produit_id": 1,
      "quantite": 5,
      "raison": "abime",
      "description": "Pain brûlé",
      "date_retour": "2025-10-22T14:00:00.000000Z",
      "produit": {
        "id": 1,
        "nom": "Pain complet",
        "categorie": "boulangerie",
        "prix": 500
      },
      "pointeur": {
        "id": 2,
        "name": "Jean Dupont"
      }
    }
  ]
}
```

---

### 5. Inventaires (Vendeurs)

#### POST /inventaires/creer
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
    "date_inventaire": "2025-10-22T16:00:00.000000Z",
    "valide_sortant": true,
    "valide_entrant": true,
    "created_at": "2025-10-22T16:00:00.000000Z",
    "details": [
      {
        "id": 1,
        "inventaire_id": 1,
        "produit_id": 1,
        "quantite_restante": 45,
        "produit": {
          "id": 1,
          "nom": "Pain complet",
          "prix": 500
        }
      },
      {
        "id": 2,
        "inventaire_id": 1,
        "produit_id": 2,
        "quantite_restante": 30,
        "produit": {
          "id": 2,
          "nom": "Baguette",
          "prix": 200
        }
      }
    ],
    "vendeurSortant": {
      "id": 3,
      "name": "Marie Kouam",
      "role": "vendeur_boulangerie"
    },
    "vendeurEntrant": {
      "id": 4,
      "name": "Paul Nkolo",
      "role": "vendeur_boulangerie"
    }
  }
}
```

**Response Error (400)**:
```json
{
  "success": false,
  "message": "Les deux vendeurs doivent être de la même catégorie"
}
```

**Response Error (401)**:
```json
{
  "success": false,
  "message": "Code PIN incorrect pour le vendeur sortant"
}
```

#### GET /inventaires/mes-inventaires
**Headers**: `Authorization: Bearer {token}`

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
      "date_inventaire": "2025-10-22T16:00:00.000000Z",
      "valide_sortant": true,
      "valide_entrant": true,
      "details": [
        {
          "produit_id": 1,
          "quantite_restante": 45,
          "produit": {
            "nom": "Pain complet",
            "prix": 500
          }
        }
      ],
      "vendeurSortant": {
        "id": 3,
        "name": "Marie Kouam"
      },
      "vendeurEntrant": {
        "id": 4,
        "name": "Paul Nkolo"
      }
    }
  ]
}
```

#### GET /inventaires/en-cours
**Headers**: `Authorization: Bearer {token}`

**Response Success (200)** - Si inventaire en cours:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "vendeur_sortant_id": 3,
    "vendeur_entrant_id": 4,
    "categorie": "boulangerie",
    "valide_sortant": false,
    "valide_entrant": false,
    "details": []
  }
}
```

**Response Success (200)** - Si aucun inventaire en cours:
```json
{
  "success": true,
  "data": null
}
```

---

### 6. Sessions de Vente (Vendeurs)

#### POST /sessions-vente/ouvrir
**Headers**: `Authorization: Bearer {token}`

**Request**:
```json
{
  "categorie": "boulangerie",
  "fond_vente": 10000,
  "orange_money_initial": 5000,
  "mtn_money_initial": 3000
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
    "fond_vente": 10000,
    "orange_money_initial": 5000,
    "mtn_money_initial": 3000,
    "orange_money_final": null,
    "mtn_money_final": null,
    "montant_verse": null,
    "manquant": null,
    "statut": "ouverte",
    "date_ouverture": "2025-10-22T08:00:00.000000Z",
    "date_fermeture": null,
    "created_at": "2025-10-22T08:00:00.000000Z",
    "vendeur": {
      "id": 3,
      "name": "Marie Kouam",
      "role": "vendeur_boulangerie"
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

#### GET /sessions-vente/active
**Headers**: `Authorization: Bearer {token}`

**Response Success (200)** - Session active:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "vendeur_id": 3,
    "categorie": "boulangerie",
    "fond_vente": 10000,
    "orange_money_initial": 5000,
    "mtn_money_initial": 3000,
    "statut": "ouverte",
    "date_ouverture": "2025-10-22T08:00:00.000000Z",
    "vendeur": {
      "id": 3,
      "name": "Marie Kouam"
    }
  }
}
```

**Response Success (200)** - Aucune session active:
```json
{
  "success": true,
  "data": null
}
```


#### GET /sessions-vente/historique?statut=fermee&date_debut=2025-10-01&date_fin=2025-10-31
**Headers**: `Authorization: Bearer {token}`

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "vendeur_id": 3,
      "categorie": "boulangerie",
      "fond_vente": 10000,
      "orange_money_initial": 5000,
      "mtn_money_initial": 3000,
      "orange_money_final": 8000,
      "mtn_money_final": 6000,
      "montant_verse": 155000,
      "manquant": 5000,
      "statut": "fermee",
      "date_ouverture": "2025-10-22T08:00:00.000000Z",
      "date_fermeture": "2025-10-22T18:00:00.000000Z",
      "vendeur": {
        "id": 3,
        "name": "Marie Kouam"
      }
    }
  ]
}
```

---

### 7. Flux de Produits (Vendeurs)

#### GET /flux/mon-flux?date=2025-10-22
**Headers**: `Authorization: Bearer {token}`

**Response Success (200)**:
```json
{
  "success": true,
  "data": {
    "periode": {
      "debut": "2025-10-22T08:00:00.000000Z",
      "fin": "2025-10-22T18:00:00.000000Z"
    },
    "flux": [
      {
        "produit": {
          "id": 1,
          "nom": "Pain complet",
          "categorie": "boulangerie",
          "prix": 500
        },
        "quantite_trouvee": 50,
        "quantite_recue": 100,
        "quantite_retour": 5,
        "quantite_restante": 45,
        "quantite_vendue": 100,
        "valeur_vente": 50000
      },
      {
        "produit": {
          "id": 2,
          "nom": "Baguette",
          "categorie": "boulangerie",
          "prix": 200
        },
        "quantite_trouvee": 80,
        "quantite_recue": 200,
        "quantite_retour": 10,
        "quantite_restante": 70,
        "quantite_vendue": 200,
        "valeur_vente": 40000
      }
    ],
    "total_ventes": 90000
  }
}
```

**Formule de calcul du flux**:
```
quantite_trouvee = stock initial (de l'inventaire d'ouverture)
quantite_recue = total des réceptions du jour
quantite_retour = total des retours du jour
quantite_restante = stock final (de l'inventaire de fermeture)
quantite_vendue = quantite_trouvee + quantite_recue - quantite_retour - quantite_restante
valeur_vente = quantite_vendue × prix_unitaire
```

---

### 8. Utilisateurs (Liste Producteurs)

#### GET /users/producteurs
**Headers**: `Authorization: Bearer {token}`

**Response Success (200)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 5,
      "name": "Boulangerie Martin",
      "numero_telephone": "699111222",
      "role": "producteur",
      "actif": true
    },
    {
      "id": 6,
      "name": "Pâtisserie Divine",
      "numero_telephone": "699333444",
      "role": "producteur",
      "actif": true
    }
  ]
}
```

---

## 🔄 Stratégie de Synchronisation

### Sync Initiale (Premier usage)
1. Connexion avec token
2. Télécharger tous les produits actifs
3. Télécharger liste producteurs
4. Télécharger vendeurs actifs par catégorie
5. Télécharger mes données historiques (7 derniers jours)

### Sync Régulière (Toutes les 30-60s si connecté)

#### 1. Upload (Envoyer modifications locales)
Parcourir la table `sync_queue` et envoyer dans l'ordre :
- Nouvelles réceptions/retours (`sync_status = 'pending'`)
- Nouveaux inventaires (`sync_status = 'pending'`)
- Nouvelles sessions ouvertes (`sync_status = 'pending'`)
- Modifications non synchronisées

**Process**:
```javascript
// Exemple pour une réception
const receptionLocal = {
  local_id: "uuid-123",
  pointeur_id: 2,
  producteur_id: 5,
  produit_id: 1,
  quantite: 50,
  notes: "Livraison matin"
};

// POST vers API
const response = await api.post('/receptions', receptionLocal);

if (response.success) {
  // Mettre à jour avec l'ID serveur
  await db.update('receptions_pointeur', {
    id: response.data.id,
    sync_status: 'synced',
    last_synced_at: new Date().toISOString()
  }, { local_id: "uuid-123" });
  
  // Supprimer de la queue
  await db.delete('sync_queue', { record_id: "uuid-123" });
}
```

#### 2. Download (Recevoir mises à jour)
Récupérer les données modifiées depuis `last_synced_at` :
- Nouveaux produits/modifications
- Nouveaux vendeurs actifs
- Verrouillages appliqués par PDG
- Sessions fermées par PDG
- Modifications de prix

**Process**:
```javascript
// Récupérer dernière sync
const lastSync = await db.getLastSyncTime();

// Télécharger mises à jour
const updates = await api.get(`/sync/updates?since=${lastSync}`);

// Appliquer en base locale
for (const produit of updates.produits) {
  await db.upsert('produits', produit);
}

for (const reception of updates.receptions) {
  if (reception.verrou) {
    await db.update('receptions_pointeur', 
      { verrou: true }, 
      { id: reception.id }
    );
  }
}
```

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
- Queue de synchronisation à la reconnexion
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
  - Select: Producteur (recherche)
  - Select: Produit (recherche)
  - Input: Quantité (numérique)
  - Textarea: Notes (optionnel)
  - Bouton: Enregistrer réception
  - Info: Vendeur assigné (affiché automatiquement)

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
  - Liste produits avec input quantité restante
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

---

## 📦 Livrables

1. **Code source React Native (Expo)**:
   - Structure complète du projet
   - Tous les composants et écrans
   - Services de synchronisation
   - Gestion SQLite
   - Configuration navigation



creer moi toute l'applicatoin mobile en te rappelant a chaque fois que tout se passe offline d'abord et il y'a juste un excellent service de synchronisation qui gere le reste : (la premiere inscription neccessite vraiment l'acces au serveur et la premiere connexion aussi mais apres ce serait mieux que la connexion hors ligne a une durrer de 15J avant d'expierer si la session n'est plus active (donc 15h d'inactiviter))

de plus au niveau des vendeurs , c'est en utilisant le code pin du vendeurs entrant que tu pourra directement switcher vers son compte(login) apres l'inventaire direct

pour faire cela de faxon modulaire on va commencer par tous les fichiers de configuration , de base de donner , de models s'il y'en a , le service de synchronisation ,le fichier des routes, toute l'authentification et enfin tout le reste

a la fin tu creera un fichier contenant l'architecture utiliser et expliquant comment ajouter des fonctionnaliter dans l'app
tu creeas aussi un fichier contenant toute les commandes pour initialiser le projet et creeer l'architecture(arborescence de fichier (vides))

tout doit etre fonctionnel a la fin: code juste ne prends pas en compte les limitations d'environnement je vais tester en local

 code directement (ne fait pas de plan commence directement  a coder) 