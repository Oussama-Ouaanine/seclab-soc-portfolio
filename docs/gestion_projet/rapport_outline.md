### INTRODUCTION GÉNÉRALE
- Contexte global de la sécurité des infrastructures Linux
- Problématique de la détection monocouche et des contraintes de ressources
- Objectifs du projet — Architecture SOC-in-a-Box multi-couches
- Présentation du double positionnement : laboratoire académique + prototype commercial
- Annonce du plan du rapport

---

### CHAPITRE 1 — Présentation du Cadre de Projet

#### 1.1 Contexte et constat de départ
- 1.1.1 La vulnérabilité systémique des serveurs d'entreprise basés sur Linux
- 1.1.2 Limites de la détection monocouche actuelle
- 1.1.3 Présentation de l'application web cible — SecLab (développée sur mesure, remplace DVWA/Juice Shop)

#### 1.2 Cartographie des frustrations et formulation du problème
- 1.2.1 Analyse des frustrations vécues (Équipe, Étudiants, Professionnels)
- 1.2.2 Méthode des "5 Pourquoi" et approche défectuologique (identification de la cause racine)
- 1.2.3 Formulation structurée des trois énoncés de problèmes clés

#### 1.3 Idéation et opportunité de la solution
- 1.3.1 Brainwriting 6-3-5 et SCAMPER technologique (génération des briques de solution)
- 1.3.2 Matrice Impact / Faisabilité (arbitrage des alternatives)
- 1.3.3 Choix et structuration de l'idée retenue

---

### CHAPITRE 2 — Analyse Stratégique & Étude de Marché

#### 2.1 Analyse de l'environnement sectoriel
- 2.1.1 Le marché de la cybersécurité et de la supervision des logs
- 2.1.2 Analyse PESTEL du secteur de la gestion des cybermenaces

#### 2.2 Analyse concurrentielle et positionnement
- 2.2.1 Benchmark concurrentiel des solutions existantes
  - Splunk, Security Onion, Wazuh — nom, type, taille, ancienneté, prix, forces, faiblesses
- 2.2.2 Les 5 Forces de Porter — attractivité et intensité concurrentielle du secteur
- 2.2.3 Analyse des fournisseurs
  - Types de fournisseurs (hébergement, communautés open-source)
  - Prix, qualité, stabilité, support technique, dépendances critiques
- 2.2.4 Analyse SWOT consolidée du projet
- 2.2.5 Définition de la Proposition de Valeur Unique (UVP)

---

### CHAPITRE 3 — Viabilité Entrepreneuriale & Modèle d'Affaires

#### 3.0 Genèse et origine de l'idée entrepreneuriale
- Source de l'idée et observation ayant conduit au projet
- Opportunité identifiée sur le marché PME sans SOC
- Inspirations et déclencheurs du projet

#### 3.1 Présentation entrepreneuriale de la solution
- Description de la solution en tant que produit/service commercialisable
- Fonctionnalités principales et technologies utilisées (Suricata, Falco, AppArmor, ELK)
- Valeur ajoutée et avantage concurrentiel différenciateur

#### 3.2 Marketing stratégique (SCP + Mix)

##### 3.2.1 Segmentation
- Groupes d'utilisateurs potentiels : PME, administrateurs systèmes, milieu académique
- Profil type : niveau technologique, comportements digitaux, problèmes recherchés

##### 3.2.2 Ciblage
- Segment principal retenu : PME Linux sans équipe SOC
- Potentiel de rentabilité et de croissance du segment cible

##### 3.2.3 Positionnement
- Image souhaitée : innovant, économique, souverain, accessible, open-source
- Principal avantage compétitif : légèreté (10 Go RAM), multi-couches, on-premise

##### 3.2.4 Stratégie Marketing Mix (4P)
- **Produit / Service** : stack de sécurité Linux multi-couches, déployable sur Ubuntu 22.04
- **Prix** : tarification selon nombre de serveurs et niveau de supervision (pénétration)
- **Distribution** : vente directe, GitHub, partenariats intégrateurs
- **Communication** : réseaux sociaux, SEO, événements cybersécurité, bouche-à-oreille

#### 3.3 Architecture du modèle d'affaires (Business Model Canvas)
- 3.3.1 Les 9 blocs du BMC détaillés
- 3.3.2 Choix du modèle économique : Professional Services + Support Open Core

#### 3.4 Étude de faisabilité technique entrepreneuriale
- Matériels nécessaires au déploiement commercial (quantités, coûts)
- Profils humains requis : développeurs Linux/sécurité, analystes SOC, commerciaux
- Autres ressources nécessaires au fonctionnement

#### 3.5 Étude de faisabilité financière

##### 3.5.1 Données quantitatives de l'étude terrain
- Analyse des résultats du questionnaire terrain
- Indicateurs de disposition à payer et demande potentielle

##### 3.5.2 Investissement initial
| Élément d'investissement | Quantité | Coût unitaire | Coût total |
|--------------------------|----------|---------------|------------|
| Développement logiciel | | | |
| Hébergement / Cloud | | | |
| Achat matériel informatique | | | |
| Licences / outils | | | |
| Marketing de lancement | | | |
| Création juridique | | | |
| Recrutement | | | |
| **TOTAL** | | | |

##### 3.5.3 Prévisions de ventes (CA mensuel prévisionnel)
##### 3.5.4 Charges variables et charges fixes mensuelles
##### 3.5.5 Résultat prévisionnel, marge brute et taux de marge
##### 3.5.6 Calcul du Seuil de Rentabilité (SR)
- Formule : SR = Charges fixes ÷ Taux de marge sur coût variable
##### 3.5.7 Besoin en Fonds de Roulement (BFR)
- BFR = Stocks + Créances clients – Dettes fournisseurs
##### 3.5.8 Trésorerie prévisionnelle
- Apport initial, revenus prévus, dépenses prévues, analyse de viabilité globale

#### 3.6 Aspect juridique
- Forme juridique choisie (SARL, SAS ou équivalent marocain)
- Justification du choix de la structure
- Cadre réglementaire applicable : Loi 09-08, RGPD, NIS2, ISO 27001
- Gestion des risques juridiques et conformité

#### 3.7 Stratégie de développement & scalabilité
- Perspectives d'évolution à court, moyen et long terme
- Scalabilité de la solution (modularité des briques techniques)
- Évolutions envisagées : SOAR, IA comportementale, mode cloud hybride

---

### CHAPITRE 4 — Étude Technique Comparative des Solutions de Surveillance

#### 4.1 Critères de sélection et contraintes architecturales
- Enveloppe matérielle stricte : 10 Go RAM alloués aux VMs (VM1 : 2 Go, VM2 : 2 Go, VM3 : 6 Go)
- Axes d'évaluation : empreinte mémoire, vélocité, modularité, pérennité

#### 4.2 Analyse comparative des solutions de détection réseau (NIDS)
- Snort (monothread) vs. Suricata (multithread natif)
- Justification du choix de Suricata : format EVE JSON, performances multi-cœurs

#### 4.3 Analyse comparative de la surveillance de l'hôte et du noyau (HIDS)
- Auditd (écritures disque I/O intensives) vs. Falco (sondes légères eBPF)
- Justification du choix de Falco : visibilité comportementale en espace noyau, faible overhead

#### 4.4 Analyse comparative des solutions de centralisation (SIEM)
- Wazuh (agent lourd, OpenSearch) vs. Stack ELK optimisée (Filebeat Go + Auditbeat)
- Stratégie de réduction de l'empreinte matérielle : limitation JVM Elasticsearch à 2 Go

---

### CHAPITRE 5 — Planification & Management de Projet

#### 5.1 Cadrage et cycle de vie du projet
- 5.1.1 Charte de projet et objectifs SMART
- 5.1.2 Choix et justification du cycle de développement : Cycle en V

#### 5.2 Décomposition et ordonnancement des tâches
- 5.2.1 Work Breakdown Structure (WBS) — 5 lots de travail
- 5.2.2 Ordonnancement des tâches : Réseau PERT et Chemin Critique
- 5.2.3 Planification temporelle globale : Diagramme de Gantt sur 6 semaines

#### 5.3 Organisation de l'équipe et maîtrise des risques
- 5.3.1 Matrice RACI — répartition des rôles et responsabilités
- 5.3.2 Registre des risques projet et matrice d'impact (atténuations techniques)
- 5.3.3 Application du principe de Pareto (80/20) aux risques majeurs

---

### CHAPITRE 6 — Ingénierie des Besoins, Conception & Modélisation

#### 6.1 Spécification des exigences du système (MoSCoW)
- 6.1.1 Must have : détection passive (Suricata IDS), surveillance eBPF (Falco), MAC enforce (AppArmor), pipeline ELK
- 6.1.2 Should have : scénarisation Cyber Kill Chain 6 phases, normalisation ECS
- 6.1.3 Could have : alertes déportées Slack/Discord via Webhook, automatisation Ansible
- 6.1.4 Won't have : mode IPS actif (risque faux positifs bloquants), déploiement cloud (hors périmètre)

#### 6.2 Analyse fonctionnelle et Cas d'Utilisation (UML)
- 6.2.1 Identification et profilage des acteurs : Attaquant, Analyste SOC, Système SIEM
- 6.2.2 Diagramme de Cas d'Utilisation global (Use Case UML)

#### 6.3 Conception de l'Architecture Technique & Topologie Réseau
- 6.3.1 Topologie réseau : 3 VMs en réseau host-only isolé
  - VM1 — SecLab (2 Go) : Apache, Falco, AppArmor, Filebeat, Auditbeat
  - VM2 — IDS (2 Go) : Suricata
  - VM3 — SOC (6 Go) : Elasticsearch, Kibana
  - Hôte — Attaquant : Nmap, FFUF, Netcat
- 6.3.2 Rôle technique de chaque machine et flux de logs

#### 6.4 Modélisation Dynamique des Flux de Détection
- 6.4.1 Diagramme de Séquence UML : détection multi-couches d'un Reverse Shell
- 6.4.2 Description pas-à-pas de la cinétique opérationnelle : Réseau → Noyau → MAC → SIEM

---

### CHAPITRE 7 — Réalisation, Implémentation & Recette

#### 7.1 Préparation de l'environnement et déploiement des sondes
- 7.1.1 Installation et initialisation de l'application cible SecLab sur Apache (VM1)
- 7.1.2 Configuration et règles personnalisées du moteur réseau Suricata (VM2)
- 7.1.3 Implémentation du pilote eBPF de Falco et écriture des macros comportementales (VM1)
- 7.1.4 Durcissement système : profil AppArmor pour Apache en mode enforce (VM1)

#### 7.2 Configuration du pipeline d'ingestion et centralisation SIEM
- 7.2.1 Paramétrage Filebeat (logs Falco) et Auditbeat (syslogs + AppArmor logs) sur VM1
- 7.2.2 Optimisation Elasticsearch — Heap Size JVM, création des index de sécurité (VM3)
- 7.2.3 Conception des dashboards de sécurité unifiés sous Kibana (VM3)

#### 7.3 Simulation de l'attaque et Cahier de Recette SOC
- 7.3.1 Exécution pas-à-pas de la Cyber Kill Chain — 6 phases :
  - Phase 1 : Reconnaissance (Nmap) → détecté par Suricata
  - Phase 2 : Découverte de ressources (FFUF) → `index.php` trouvé
  - Phase 3 : Injection SQL → authentification contournée → détecté par Suricata
  - Phase 4 : Injection de commandes OS (`/admin/diagnostic.php`)
  - Phase 5 : Reverse Shell → détecté par Falco
  - Phase 6 : Escalade de privilèges (Dirty Pipe) → détecté par Falco
- 7.3.2 Démonstration AppArmor avant/après en mode enforce
  - Sans enforce : attaque complète réussie (RCE, reverse shell, root)
  - Avec enforce : command injection bloquée, reverse shell impossible, escalade impossible
- 7.3.3 Validation visuelle de la corrélation croisée (Réseau / Noyau / Système) sur Kibana
- 7.3.4 Analyse des résultats et calcul du taux de faux positifs/négatifs

---

### CONCLUSION GÉNÉRALE
- Synthèse technique des objectifs atteints
  - Architecture 3 VMs fonctionnelle sous 10 Go de RAM (open-source souverain)
  - Détection validée sur les 6 phases de la kill chain
  - Confinement AppArmor démontré en mode enforce
- Apports du projet pour les différents acteurs : PME, administrateurs Linux, milieu académique
- Limites de la solution
  - Trafic HTTPS non inspectable sans déchiffrement TLS préalable
  - Falco : détection mais pas de blocage
  - Faux positifs possibles selon la granularité des règles
- Perspectives d'évolutions technologiques : SOAR, IA comportementale, mode cloud hybride

---

### ANNEXES
- **Annexe A** : Questionnaire de l'étude terrain et résultats bruts
- **Annexe B** : Règles Suricata personnalisées (scans Nmap, SQL injection)
- **Annexe C** : Macros Falco (règles comportementales — reverse shell, escalade de privilèges)
- **Annexe D** : Profil AppArmor complet (Apache — mode enforce)
- **Annexe E** : Configuration Filebeat (logs Falco) et Auditbeat (syslogs + AppArmor)
- **Annexe F** : Mappings Elasticsearch et index de sécurité
- **Annexe G** : Captures d'écran des Dashboards Kibana (timeline, corrélation, alertes)

---

### BIBLIOGRAPHIE & RÉFÉRENCES

---

*PFA — Référence Complète | Année universitaire 2025/2026*
*Ingénierie Informatique — Cybersécurité | Usage académique*