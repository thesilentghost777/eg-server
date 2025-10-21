# Collection de tests API — Boulangerie (Markdown)

**BASE URL :** `http://localhost/api`

> Instructions : importer ces requêtes dans Postman manuellement ou lire/faire les étapes dans l'ordre. Pour les requêtes nécessitant authentification, utilisez le token retourné par `/auth/connexion` et ajoutez le header : `Authorization: Bearer {{token}}`.

---

## Table des matières

1. Authentification
2. Produits
3. Réceptions
4. Retours
5. Inventaires
6. Sessions de vente
7. Flux de produits
8. Gestion utilisateurs

---

# 1 — AUTHENTIFICATION

### 1.1 INSCRIPTION (PDG)

**Méthode** : `POST`
**URL** : `/auth/inscription`
**Body (JSON)** :

```json
{
  "nom": "Admin PDG",
  "numero_telephone": "0101010101",
  "role": "pdg",
  "code_pin": "123456",
  "code_pdg": "PDG2025SECURE"
}
```

**Step by step / commentaires :**

1. Envoyer la requête pour créer l'utilisateur PDG.
2. Si `code_pdg` est correct (lié à `config_pdg`), l'utilisateur sera créé.

**Réponse attendue :**

* **HTTP** `201 Created` (ou `200 OK` selon l'implémentation).
* Corps : objet `user` (id, nom, role, numero_telephone, preferred_language...) et *optionnellement* un `token`.

**Exemple de réponse attendue :**

```json
{
  "user": {
    "id": 1,
    "nom": "Admin PDG",
    "numero_telephone": "0101010101",
    "role": "pdg",
    "preferred_language": "fr",
    "actif": true
  },
  "token": "eyJ..."
}
```

---

### 1.2 INSCRIPTION (POINTEUR)

**Méthode** : `POST`
**URL** : `/auth/inscription`
**Body :**

```json
{
  "nom": "Jean Pointeur",
  "numero_telephone": "0102020202",
  "role": "pointeur",
  "code_pin": "222222"
}
```

**Commentaires :** créer plusieurs utilisateurs test (pointeur, vendeurs, producteurs).

**Réponse attendue :** `201 Created` et objet `user`.

---

### 1.3 INSCRIPTION (VENDEUR BOULANGERIE)

**Méthode** : `POST`
**URL** : `/auth/inscription`
**Body :**

```json
{
  "nom": "Awa Vendeur Boulangerie",
  "numero_telephone": "0103030303",
  "role": "vendeur_boulangerie",
  "code_pin": "333333"
}
```

**Réponse attendue :** `201 Created` + objet user.

---

### 1.4 CONNEXION

**Méthode** : `POST`
**URL** : `/auth/connexion`
**Body :**

```json
{
  "numero_telephone": "0101010101",
  "code_pin": "123456"
}
```

**Étapes :**

1. Utiliser les identifiants créés dans 1.1.
2. Copier le `token` renvoyé pour l'utiliser dans `Authorization: Bearer {{token}}`.

**Réponse attendue :** `200 OK` et payload `{ "user": {...}, "token": "..." }`.

---

### 1.5 UTILISATEUR CONNECTÉ

**Méthode** : `GET`
**URL** : `/auth/me`
**Headers** : `Authorization: Bearer {{token}}`

**But :** vérifier que le token fonctionne.

**Réponse attendue :** `200 OK` et objet `user` correspondant.

---

### 1.6 DÉCONNEXION

**Méthode** : `POST`
**URL** : `/auth/deconnexion`
**Headers** : `Authorization: Bearer {{token}}`

**Réponse attendue :** `200 OK` (ou 204) ; token invalidé côté serveur si implémenté.

---

# 2 — PRODUITS (lecture: tous; CRUD: PDG seulement)

> **Astuce** : se connecter en PDG pour les opérations de création, modification et suppression.

### 2.1 CRÉER PRODUIT (PDG)

**Méthode** : `POST`
**URL** : `/produits`
**Headers** : `Authorization: Bearer {{token}}` (PDG)

**Body :**

```json
{
  "nom": "Baguette",
  "prix": 250.00,
  "categorie": "boulangerie"
}
```

**Étapes :**

1. Vérifier que l'utilisateur connecté est `pdg`.
2. Envoyer la requête.

**Réponse attendue :**

* `201 Created` et objet produit : `{ id, nom, prix, categorie, actif, created_at }`.
* Si utilisateur non-PDG → `403 Forbidden`.

**Exemple :**

```json
{
  "id": 2,
  "nom": "Baguette",
  "prix": "250.00",
  "categorie": "boulangerie",
  "actif": true
}
```

---

### 2.2 LISTER TOUS LES PRODUITS

**Méthode** : `GET`
**URL** : `/produits`

**But :** vérifier que la liste contient le produit créé.

**Réponse attendue :** `200 OK` et tableau d'objets produits.

---

### 2.3 LISTER PAR CATÉGORIE

**Méthode** : `GET`
**URL** : `/produits/categorie/boulangerie`

**Réponse attendue :** `200 OK` et liste filtrée.

---

### 2.4 MODIFIER PRODUIT (PDG)

**Méthode** : `PUT`
**URL** : `/produits/{id}` (ex: `/produits/2`)
**Headers** : `Authorization: Bearer {{token}}`

**Body :**

```json
{
  "nom": "Baguette Tradition",
  "prix": 300.00,
  "categorie": "boulangerie",
}
```

**Étapes :**

1. Vérifier existence du produit.
2. Envoyer `PUT`.

**Réponse attendue :** `200 OK` et objet produit mis à jour.

---

### 2.5 TOGGLE ACTIF (PDG)

**Méthode** : `POST`
**URL** : `/produits/{id}/toggle-actif`
**Headers** : `Authorization: Bearer {{token}}`

**But :** activer ou désactiver le produit.

**Réponse attendue :** `200 OK` et état `actif` modifié (true/false).

---

### 2.6 SUPPRIMER PRODUIT (PDG)

**Méthode** : `DELETE`
**URL** : `/produits/{id}`
**Headers** : `Authorization: Bearer {{token}}`

**Remarques critiques :**

* Si produit lié à des inventaires/réceptions/ventes, suppression peut échouer (422) ou effectuer une contrainte `ON DELETE`.

**Réponse attendue :** `200 OK` ou `204 No Content` ; sinon `422` si contrainte.

---

# 3 — RÉCEPTIONS (Pointeur uniquement)

### 3.1 CRÉER RÉCEPTION

**Méthode** : `POST`
**URL** : `/receptions`
**Headers** : `Authorization: Bearer {{token}}` (pointeur)

**Body :**

```json
{
  "producteur_id": 5,
  "produit_id": 2,
  "quantite": 100,
  "notes": "Réception du matin"
}
```

**Étapes :**

1. Assurez-vous que `producteur_id` et `produit_id` existent.
2. Envoyer la requête.

**Réponse attendue :** `201 Created` et objet `reception` { id, pointeur_id, producteur_id, produit_id, quantite, verrou:false, date_reception }.

---

### 3.2 MODIFIER RÉCEPTION

**Méthode** : `PUT`
**URL** : `/receptions/{id}`
**Headers** : `Authorization: Bearer {{token}}`

**Body exemple :**

```json
{ "quantite": 120, "notes": "Correction de quantité" }
```

**Réponse attendue :** `200 OK` et reception mise à jour.

---

### 3.3 LISTE - MES RÉCEPTIONS (par date)

**Méthode** : `GET`
**URL** : `/receptions/mes-receptions?date=2025-10-21`
**Headers** : `Authorization: Bearer {{token}}`

**Réponse attendue :** `200 OK` et tableau de réceptions filtrées par date.

---

### 3.4 VENDEUR - VOIR SES RÉCEPTIONS

**Méthode** : `GET`
**URL** : `/vendeur/receptions?date=2025-10-21`
**Headers** : `Authorization: Bearer {{token}}`

**Réponse attendue :** `200 OK` et réceptions assignées au vendeur.

---

# 4 — RETOURS (Pointeur uniquement)

### 4.1 CRÉER RETOUR

**Méthode** : `POST`
**URL** : `/retours`
**Headers** : `Authorization: Bearer {{token}}` (pointeur)

**Body :**

```json
{
  "produit_id": 2,
  "quantite": 5,
  "raison": "perime",
  "description": "Pain rassis"
}
```

**Réponse attendue :** `201 Created` et objet `retour` avec `verrou=false`.

---

### 4.2 MODIFIER RETOUR

**Méthode** : `PUT`
**URL** : `/retours/{id}`
**Headers** : `Authorization: Bearer {{token}}`

**Body :**

```json
{ "quantite": 6, "raison": "autre", "description": "Erreur de stock" }
```

**Réponse attendue :** `200 OK`.

---

### 4.3 LISTE RETOURS (VENDEUR)

**Méthode** : `GET`
**URL** : `/vendeur/retours?date=2025-10-21`
**Headers** : `Authorization: Bearer {{token}}`

**Réponse attendue :** `200 OK` et liste.

---

# 5 — INVENTAIRES (Vendeurs)

### 5.1 CRÉER INVENTAIRE

**Méthode** : `POST`
**URL** : `/inventaires/creer`
**Headers** : `Authorization: Bearer {{token}}` (vendeur)

**Body :**

```json
{
    "vendeur_sortant_id": 3,
    "vendeur_entrant_id": 5,
    "code_pin_sortant": "333333",
    "code_pin_entrant": "333333",
    "produits": [
        {
            "produit_id": 1,
            "quantite_restante": 7
        },
        {
            "produit_id": 2,
            "quantite_restante": 35
        }
    ]
}
```

**Étapes :**

1. Le vendeur sortant crée l'inventaire et renseigne les quantités trouvées.
2. L'inventaire est stocké avec `valide_sortant=false`, `valide_entrant=false`.

**Réponse attendue :** `201 Created` et objet `inventaire` (id, vendeurs, date_inventaire).

---

### 5.2 VALIDER INVENTAIRE (sortant)

**Méthode** : `POST`
**URL** : `/inventaires/{id}/valider`
**Headers** : `Authorization: Bearer {{token}}`

**Body :**

```json
{ "code_pin": "333333", "type": "sortant" }
```

**Réponse attendue :** `200 OK` et `valide_sortant=true`.

---


### 5.4 LISTER MES INVENTAIRES

**Méthode** : `GET`
**URL** : `/inventaires/mes-inventaires`
**Headers** : `Authorization: Bearer {{token}}`

**Réponse attendue :** `200 OK` et tableau d'inventaires du vendeur.

---

### 5.5 INVENTAIRES EN COURS

**Méthode** : `GET`
**URL** : `/inventaires/en-cours`
**Headers** : `Authorization: Bearer {{token}}`

**Réponse attendue :** `200 OK` et inventaires en attente de validation.

---

# 6 — SESSIONS DE VENTE

### 6.1 CRÉER SESSION (VENDEUR)

**Méthode** : `POST`
**URL** : `/sessions-vente/creer`
**Headers** : `Authorization: Bearer {{token}}` (vendeur)

**Body :**

```json
{
  "fond_vente": 10000,
  "orange_money_initial": 5000,
  "mtn_money_initial": 2000
}
```

**Réponse attendue :** `201 Created` et objet session (`statut: "ouverte"`, `date_ouverture`).

---

### 6.2 CONSULTER SESSION ACTIVE

**Méthode** : `GET`
**URL** : `/sessions-vente/active`
**Headers** : `Authorization: Bearer {{token}}`

**Réponse attendue :** `200 OK` et session ouverte ou `404` si aucune.

---

### 6.3 HISTORIQUE SESSIONS

**Méthode** : `GET`
**URL** : `/sessions-vente/mes-sessions`
**Headers** : `Authorization: Bearer {{token}}`

**Réponse attendue :** `200 OK` et liste des sessions.

---

### 6.4 FERMER SESSION (PDG)

**Méthode** : `POST`
**URL** : `/sessions-vente/{id}/fermer`
**Headers** : `Authorization: Bearer {{token}}` (PDG)

**Body :**

```json
{
  "montant_verse": 200000,
  "orange_money_final": 10000,
  "mtn_money_final": 5000,
  "ventes_totales": 215000
}
```

**Calcul critique (manquant) :**

```
manquant = ventes_totales - (montant_verse + orange_money_final + mtn_money_final)
```

**Exemple attendu :** 215000 - (200000 + 10000 + 5000) = **0** → pas de manquant.

**Réponse attendue :** `200 OK` et session `statut: "fermee"`, champ `manquant` calculé.

---

### 6.5 LISTER TOUTES LES SESSIONS (PDG)

**Méthode** : `GET`
**URL** : `/sessions-vente/toutes`
**Headers** : `Authorization: Bearer {{token}}` (PDG)

**Réponse attendue :** `200 OK` et liste complète des sessions.

---

# 7 — FLUX DE PRODUITS

### 7.1 MON FLUX (VENDEUR)

**Méthode** : `GET`
**URL** : `/flux/mon-flux?date=2025-10-21`
**Headers** : `Authorization: Bearer {{token}}`

**Réponse attendue :** `200 OK` et tableau d'objets :

```json
[{
  "produit": { "id": 2, "nom": "Baguette" },
  "quantite_trouvee": 100,
  "quantite_recue": 100,
  "quantite_retour": 5,
  "quantite_restante": 80,
  "quantite_vendue": 15,
  "valeur_vente": 4500
}]
```

**Vérifications importantes :**

* `quantite_restante = quantite_recue - quantite_retour - quantite_vendue` (attendu)
* `valeur_vente` = `quantite_vendue * prix` (vérifier cohérence)

---

### 7.2 FLUX D'UN VENDEUR (PDG)

**Méthode** : `GET`
**URL** : `/flux/vendeur/{vendeurId}?date=2025-10-21`
**Headers** : `Authorization: Bearer {{token}}` (PDG)

**Réponse attendue :** `200 OK` et détails du flux du vendeur.

---

### 7.3 FLUX GLOBAL (PDG)

**Méthode** : `GET`
**URL** : `/flux/tous?date=2025-10-21`
**Headers** : `Authorization: Bearer {{token}}` (PDG)

**Réponse attendue :** `200 OK` et agrégation pour tous les vendeurs.

---

# 8 — GESTION UTILISATEURS (PDG uniquement)

### 8.1 LISTER TOUS LES UTILISATEURS

**Méthode** : `GET`
**URL** : `/users`
**Headers** : `Authorization: Bearer {{token}}` (PDG)

**Réponse attendue :** `200 OK` et tableau d'utilisateurs.

---

### 8.2 FILTRER PAR RÔLE

**Méthode** : `GET`
**URL** : `/users/role/{role}`
**Exemple** : `/users/role/vendeur_boulangerie`

**Réponse attendue :** `200 OK` et utilisateurs du rôle demandé.

---

### 8.3 CREER UTILISATEUR (PDG)

**Méthode** : `POST`
**URL** : `/users`
**Headers** : `Authorization: Bearer {{token}}` (PDG)

**Body :**

```json
{
  "nom": "Nouveau Producteur",
  "numero_telephone": "0104040404",
  "role": "producteur",
  "code_pin": "555555"
}
```

**Réponse attendue :** `201 Created` et utilisateur créé.

---

### 8.4 MODIFIER UTILISATEUR

**Méthode** : `PUT`
**URL** : `/users/{id}`
**Headers** : `Authorization: Bearer {{token}}`

**Body exemple :** `{ "nom": "Awa Modifiée" }`

**Réponse attendue :** `200 OK` et utilisateur mis à jour.

---

### 8.5 ACTIVER / DÉSACTIVER UTILISATEUR

**Méthode** : `POST`
**URL** : `/users/{id}/toggle-actif`
**Headers** : `Authorization: Bearer {{token}}` (PDG)

**Réponse attendue :** `200 OK` et nouvel état `actif`.

---

### 8.6 SUPPRIMER UTILISATEUR

**Méthode** : `DELETE`
**URL** : `/users/{id}`
**Headers** : `Authorization: Bearer {{token}}` (PDG)

**Remarque critique :** suppression d'un utilisateur lié (ex: producteur avec réceptions) peut échouer si contraintes de FK.

**Réponse attendue :** `200/204` ou `422` si contrainte.

---

# Conseils de test / checklist finale

* Tester les erreurs : tentatives de création sans champ requis, numéro de téléphone déjà utilisé → `422`.
* Tester les autorisations : appeler endpoints PDG avec token d'un vendeur → `403 Forbidden`.
* Vérifier calculs critiques : `manquant` à la fermeture de session, `quantite_restante` dans flux.
* Tester cas limites : quantités négatives, pins incorrects (devrait retourner 401/422).

---

*Fini.*

Tu peux maintenant télécharger ou copier ce markdown dans un fichier `Postman_Tests_API_Boulangerie.md` et l'utiliser comme guide pour exécuter les tests. Si tu veux, je peux aussi générer un fichier `.md` téléchargeable ou directement un collection Postman `.json`. Indique ce que tu préfères.
