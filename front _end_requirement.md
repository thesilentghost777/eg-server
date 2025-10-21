=================================================================
DOCUMENTATION API - BOULANGERIE PATISSERIE
Backend développé avec Laravel + Sanctum
=================================================================

BASE URL: http://localhost/api (je suis en developpement)

=================================================================
AUTHENTIFICATION
=================================================================

1. INSCRIPTION
POST /auth/inscription
Body: {
  "nom": "string",
  "numero_telephone": "string (unique)",
  "role": "pdg|pointeur|vendeur_boulangerie|vendeur_patisserie|producteur",
  "code_pin": "string (6 caractères)",
  "code_pdg": "string (requis si role = pdg)",
  "preferred_language": "fr|en (optionnel, défaut: fr)"
}

2. CONNEXION
POST /auth/connexion
Body: {
  "numero_telephone": "string",
  "code_pin": "string"
}
Response: { "user": {...}, "token": "..." }

3. DÉCONNEXION
POST /auth/deconnexion
Headers: Authorization: Bearer {token}

4. UTILISATEUR CONNECTÉ
GET /auth/me
Headers: Authorization: Bearer {token}

=================================================================
PRODUITS (Tous peuvent lire, PDG seulement pour CRUD)
=================================================================

GET /produits - Liste tous les produits
GET /produits/categorie/{boulangerie|patisserie} - Par catégorie
POST /produits - Créer (PDG uniquement)
PUT /produits/{id} - Modifier (PDG uniquement)
POST /produits/{id}/toggle-actif - Activer/Désactiver (PDG)
DELETE /produits/{id} - Supprimer (PDG)

=================================================================
RÉCEPTIONS (Pointeur uniquement)
=================================================================

POST /receptions
Body: {
  "producteur_id": "number",
  "produit_id": "number",
  "quantite": "number",
  "notes": "string (optionnel)"
}

PUT /receptions/{id} - Modifier une réception
GET /receptions/mes-receptions?date=YYYY-MM-DD - Liste réceptions
GET /vendeur/receptions?date=YYYY-MM-DD - Vendeur voit ses réceptions

=================================================================
RETOURS (Pointeur uniquement)
=================================================================

POST /retours
Body: {
  "produit_id": "number",
  "quantite": "number",
  "raison": "gate|perime|defectueux|autre",
  "description": "string (optionnel)"
}

PUT /retours/{id} - Modifier un retour
GET /vendeur/retours?date=YYYY-MM-DD - Vendeur voit ses retours

=================================================================
INVENTAIRES (Vendeurs uniquement)
=================================================================

POST /inventaires/creer
Body: {
  "vendeur_entrant_id": "number",
  "produits": [
    {
      "produit_id": "number",
      "quantite_restante": "number"
    }
  ]
}

POST /inventaires/{id}/valider
Body: {
  "code_pin": "string (6 caractères)",
  "type": "sortant|entrant"
}

GET /inventaires/mes-inventaires - Liste inventaires du vendeur
GET /inventaires/en-cours - Inventaire en attente de validation

=================================================================
SESSIONS DE VENTE
=================================================================

VENDEUR:
POST /sessions-vente/creer
Body: {
  "fond_vente": "number (optionnel, défaut: 0)",
  "orange_money_initial": "number (optionnel, défaut: 0)",
  "mtn_money_initial": "number (optionnel, défaut: 0)"
}

GET /sessions-vente/active - Session active du vendeur
GET /sessions-vente/mes-sessions - Historique sessions

PDG:
POST /sessions-vente/{id}/fermer
Body: {
  "montant_verse": "number",
  "orange_money_final": "number",
  "mtn_money_final": "number",
  "ventes_totales": "number"
}

GET /sessions-vente/toutes - Toutes les sessions

=================================================================
FLUX DE PRODUITS
=================================================================

VENDEUR:
GET /flux/mon-flux?date=YYYY-MM-DD
Response: [
  {
    "produit": {...},
    "quantite_trouvee": "number",
    "quantite_recue": "number",
    "quantite_retour": "number",
    "quantite_restante": "number",
    "quantite_vendue": "number",
    "valeur_vente": "number"
  }
]

PDG:
GET /flux/vendeur/{vendeurId}?date=YYYY-MM-DD
GET /flux/tous?date=YYYY-MM-DD

=================================================================
GESTION UTILISATEURS (PDG uniquement)
=================================================================

GET /users - Tous les utilisateurs
GET /users/role/{role} - Par rôle
GET /users/producteurs - Liste producteurs
POST /users - Créer utilisateur
PUT /users/{id} - Modifier
POST /users/{id}/toggle-actif - Activer/Désactiver
DELETE /users/{id} - Supprimer

=================================================================
RÔLES ET PERMISSIONS
=================================================================

- pdg: Accès complet, gestion utilisateurs, fermeture sessions
- pointeur: Créer/modifier réceptions et retours
- vendeur_boulangerie: Inventaires et sessions catégorie boulangerie
- vendeur_patisserie: Inventaires et sessions catégorie pâtisserie
- producteur: Aucun accès API (juste référence)

=================================================================
CODE PDG PAR DÉFAUT
=================================================================
Code: PDG2025SECURE
(À modifier dans la table config_pdg)

=================================================================
NOTES IMPORTANTES
=================================================================

1. Toutes les routes protégées nécessitent le header:
   Authorization: Bearer {token}

2. Les vendeurs sont automatiquement assignés aux réceptions/retours
   selon la catégorie du produit et le vendeur actif

3. L'inventaire change automatiquement le vendeur actif après 
   validation des deux parties

4. Les modifications de réceptions/retours sont bloquées si:
   - Le vendeur n'est plus actif
   - Le verrou est activé (par PDG)

5. Formule manquant session:
   Manquant = (Ventes totales - Fond) - (Versé + Diff OM + Diff MTN)

=================================================================


🧾 Résumé projet — Application Boulangerie / Pâtisserie (Frontend)
🎯 Objectif

Développer une application de gestion de boulangerie-pâtisserie qui fonctionne :

en réseau local (Wi-Fi, sans Internet)

avec un serveur Laravel (déjà prêt) qui expose des API JSON

utilisable à la fois sur tablettes (Android) et ordinateur du PDG (Windows PC)

⚙️ Architecture globale

Backend : Laravel (serveur local, IP fixe ex: http://192.168.0.10/api)

Frontend : React Native (avec Expo)

Déploiement :

Tablettes → APK Android

PC → App Electron (EXE) basée sur React Native Web

🧱 Stack technique front-end
Fonction	Technologie
Framework principal	React Native (Expo)
Mode web / desktop	React Native Web + Electron
Communication API	Axios vers backend Laravel
Stockage local (offline)	SQLite via expo-sqlite
Détection réseau	@react-native-community/netinfo
Gestion offline/sync	Code custom (SQLite → Laravel API)
Design responsive	Flexbox + Dimensions API

voici la description de l'application

on va developper une app pour une boulangerie patisserie
elle va fonctionner en offline 
le pdg utilisera sur sa machine (serveur)
le pointeur sur une tablette
les vendeurs sur une tablette
on va faire evoluer l'application au fur et a mesure

pour l'instant on veut la premiere fonctionnaliter (flux de produit patisserie)

premierement on a des produits (defini par leur nom , prix , categorie(boulangerie ou patisserie)

ensuite on a les pointeurs , les vendeurs et le pdg

les pointeur rexoivent les produits des producteurs et les passe au vendeurs direct
les pointeurs sont les hommes de confiance
il y'a un seul vendeur a la fois pour chaque categorie en place (xa veut dire qu'a un moment precis il y'a un seul vendeur qui vend pour chaque categorie " un seul patisserie et un seul boulangerie)

lorsque le pointeur declare qu'il a recu un produit d'un producteur on assigne directement le produit et la quantite recu au vendeur qui est entrain de travailler pour la categorie du produit
le pointeur enregistre aussi les retour de produits (les produits peuvent etre retourner pour differente raison (gater , perimer ...))
lorsque le pointeur enregistre un retour de produit le serveur responsable du retour est automatiquement le serveur connecter pour la categorie du produit en question et le retour est directement lier au serveur
le pointeur peut modifier a les infos relative a une reception (qui modifiera aussi au niveau du serveur)
mais il ne peut plus modifier lorsque le serveur n'est plus connecter ou lorsque un verrou logique a ete declencher (par le pdg dans ce cas))
le vendeur n'a que 3  chose a faire avec l'application (l'inventaire)

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

enfin le serveur peut visualiser la liste des entrer de la journee (pour verifier que le pointeur ne se soit pas tromper vu que chaque reception du pointeur creer automatiquement une entrer dans la table du vendeur et chaque modification aussi)
a

le pdg
c'est le boss 
il a 5 fonctionnaliter
1-visualiser la liste du flux de produit

la liste de flux de produit est une liste simple qui pour chaque serveur affiche

pour chaque produit , la quantite trouver en arrivant (via l'inventaire) , la quantite recu pendant le journee (recu du pointeur) , la quantite retourner , la quantite restante au depart (2e inventaire) , la quantite vendu calculer (entreer+trouver-retour-restant)

2-modifier toutes les infos dans le cas ou il  ya une erreur (une reception , un retour , un inventaire ... et cette modification doit se repercuter sur les tables qui en dependent quand c'est le cas

3-fermer une session de vente : le pdg precise simplement le montant verser par le vendeur , le montant final dans mobile money ,le montant final dans orange money
apres avoir specifier tout cela le manquant est automatiquement calculer en utilisant la formule Manquant = (somme total de vente - Fond de vente) - (Montant versé + Diff OM + Diff MTN)

4-CRUD et activation/desactivation sur les utilisateur (employer)
5-CRUD sur le produits (en precisant a chaque fois la categories)
un utilisateur doit un role (pdg,producteur,pointeur,vendeur boulangerie, vendeur patisserie)

tu vas developper un controlleur WorkpaceSwitcherController qui sera utiliser lors des login

on va travailler toujours sur une architecture baser responsabiliter

les services pour la logique metier (ils renvoient des json) , deux controlleur (API et l'autre normale qui renvoie les vues blade) mais qui appele le meme service

en effet le controlleur qui renvoie les json sera utiliser par react native et le controlleur pc sera utiliser pour la partie web

pour s'enregistrer dans le systeme , si l'utilisateur precise qu'il est pdg il devra entrer un code qui le prouve , le code sera configurer en clair dans la base de donner par le developpeur



dans le dashboard du pointeur je veux que ce soit directement une interface lui permettant d'enregistrer des reception
dans le dashboard du vendeur je veux que ce soit directement la liste des produits qu'il a recu (obtenu des entreer du pointeur)

l'application doit etre de rang International , de niveau Legende et de Performance maximale

les palettes de couleur doivent etre coherente et le theme principale est tout simplement la couleur doreer du pain

l'architecture a utiliser est la suivante


easygestbp-front-end/
│
├── src/
│   ├── api/
│   │   ├── client.js                    # Configuration Axios + intercepteurs
│   │   ├── endpoints.js                 # Toutes les URLs des endpoints
│   │   └── services/
│   │       ├── authService.js           # Authentification
│   │       ├── productService.js        # Produits
│   │       ├── receptionService.js      # Réceptions
│   │       ├── retourService.js         # Retours
│   │       ├── inventaireService.js     # Inventaires
│   │       ├── sessionService.js        # Sessions de vente
│   │       ├── fluxService.js           # Flux de produits
│   │       └── userService.js           # Gestion utilisateurs
│   │
│   ├── store/
│   │   ├── database.js                  # Configuration SQLite
│   │   ├── syncService.js               # Synchronisation offline/online
│   │   └── models/
│   │       ├── Product.js
│   │       ├── Reception.js
│   │       ├── Retour.js
│   │       ├── Inventaire.js
│   │       └── Session.js
│   │
│   ├── contexts/
│   │   ├── AuthContext.js               # État authentification globale
│   │   ├── NetworkContext.js            # État réseau (online/offline)
│   │   └── SyncContext.js               # État synchronisation
│   │
│   ├── screens/
│   │   ├── auth/
│   │   │   ├── LoginScreen.js
│   │   │   └── RegisterScreen.js
│   │   │
│   │   ├── pointeur/
│   │   │   ├── DashboardPointeurScreen.js    # Interface réception directe
│   │   │   ├── ReceptionFormScreen.js
│   │   │   ├── RetourFormScreen.js
│   │   │   └── HistoriqueScreen.js
│   │   │
│   │   ├── vendeur/
│   │   │   ├── DashboardVendeurScreen.js     # Liste produits reçus
│   │   │   ├── InventaireScreen.js
│   │   │   ├── ValidationInventaireScreen.js
│   │   │   ├── SessionVenteScreen.js
│   │   │   └── MesReceptionsScreen.js
│   │   │
│   │   └── pdg/
│   │       ├── DashboardPDGScreen.js
│   │       ├── FluxProduitsScreen.js         # Tableau flux complet
│   │       ├── GestionUsersScreen.js
│   │       ├── GestionProduitsScreen.js
│   │       ├── FermetureSessionScreen.js
│   │       └── ModificationScreen.js
│   │
│   ├── components/
│   │   ├── common/
│   │   │   ├── Button.js
│   │   │   ├── Input.js
│   │   │   ├── Card.js
│   │   │   ├── Modal.js
│   │   │   ├── LoadingSpinner.js
│   │   │   ├── ErrorBoundary.js
│   │   │   └── OfflineIndicator.js
│   │   │
│   │   ├── forms/
│   │   │   ├── ReceptionForm.js
│   │   │   ├── RetourForm.js
│   │   │   ├── InventaireForm.js
│   │   │   ├── ProductForm.js
│   │   │   └── UserForm.js
│   │   │
│   │   └── lists/
│   │       ├── ProductList.js
│   │       ├── ReceptionList.js
│   │       ├── RetourList.js
│   │       ├── UserList.js
│   │       └── FluxTable.js
│   │
│   ├── navigation/
│   │   ├── AppNavigator.js              # Navigation principale
│   │   ├── PointeurNavigator.js
│   │   ├── VendeurNavigator.js
│   │   └── PDGNavigator.js
│   │
│   ├── utils/
│   │   ├── constants.js                 # Constantes globales
│   │   ├── validators.js                # Validation formulaires
│   │   ├── formatters.js                # Formatage dates/montants
│   │   ├── calculations.js              # Calculs (manquant, vendu, etc.)
│   │   └── permissions.js               # Gestion permissions par rôle
│   │
│   ├── hooks/
│   │   ├── useAuth.js
│   │   ├── useNetwork.js
│   │   ├── useSync.js
│   │   ├── useProducts.js
│   │   ├── useReceptions.js
│   │   └── useInventaire.js
│   │
│   ├── theme/
│   │   ├── colors.js                    # Palette dorée/pain
│   │   ├── typography.js
│   │   └── spacing.js
│   │
│   ├── config/
│   │   ├── app.config.js                # Config générale
│   │   └── api.config.js                # Config API (URL serveur)
│   │
│   └── App.js                           # Point d'entrée
│
├── electron/                            # Pour version PC (Electron)
│   ├── main.js
│   ├── preload.js
│   └── package.json
│
├── android/                             # Configuration Android
├── ios/                                 # Configuration iOS (optionnel)
│
├── assets/
│   ├── images/
│   ├── icons/
│   └── fonts/
│
├── app.json                             # Config Expo
├── package.json
├── babel.config.js
└── README.md

code moi tout le Frontend comme un chef et a la fin tu vas creer un fichier bash contenant les commandes a executer pour installer le projet et initaliser tte les dependances

