# Plan du Rapport Final : Projet de Fin d'Année & Entrepreneuriat

Ce plan est directement inspiré de la structure rigoureuse de votre ancien projet académique (SMPP), en y intégrant parfaitement la dimension **Business / Entrepreneuriat** demandée par votre professeur, ainsi que les spécificités de votre nouvelle architecture de cybersécurité (SecLab, Suricata, Falco, AppArmor, ELK).

L'idée est de présenter ce projet technique non pas comme un simple "TP", mais comme le développement d'un véritable **Produit B2B de Cybersécurité (SOC-in-a-Box / Service MDR pour PME)**.

---

## 1. Introduction Générale
- **1.1 Contexte général :** L'explosion des cyberattaques ciblant les serveurs web d'entreprises.
- **1.2 Problématique :** Comment offrir aux PME une détection multi-couches (Défense en Profondeur) abordable, automatisée et efficace face à des menaces avancées ?
- **1.3 Motivation et opportunité métier :** Le besoin croissant d'externaliser la sécurité (Managed Detection & Response) pour les entreprises n'ayant pas de budget pour un SOC interne.
- **1.4 Objectifs du projet :** Concevoir une architecture combinant NIDS, HIDS, MAC et SIEM.
- **1.5 Méthodologie adoptée :** Approche classique en V (Conception, Déploiement, Attaque simulée, Validation).
- **1.6 Gestion de projet et Planification :** *(Reprise du Cahier des Charges)*
  - 1.6.1 Découpage des phases du projet.
  - 1.6.2 Planification temporelle (Diagramme de Gantt).
  - 1.6.3 Ordonnancement des tâches (Diagramme de PERT et Chemin critique).

## 2. État de l'art et Étude de Marché (Volet Entrepreneuriat)
*Cette section combine la théorie académique et l'analyse de marché business.*
- **2.1 État de l'art technique : La Défense en Profondeur**
  - 2.1.1 Les Systèmes de Détection d'Intrusions (NIDS vs HIDS).
  - 2.1.2 Le Contrôle d'Accès Obligatoire (MAC - AppArmor).
  - 2.1.3 Centralisation des logs (SIEM : Elastic Stack).
- **2.2 Outils et solutions concurrentes sur le marché**
  - 2.2.1 Les géants du marché (CrowdStrike, Palo Alto, AWS GuardDuty).
  - 2.2.2 Notre positionnement : Approche 100% Open-Source adaptée aux PME.
- **2.3 Étude de Marché Stratégique**
  - 2.3.1 **Analyse PESTEL :** Politique (RGPD), Économique (Coût des failles), Technologique (eBPF et Cloud).
  - 2.3.2 **Analyse SWOT :** Forces (Solution open-source intégrée), Faiblesses (Configuration requise), Opportunités, Menaces.
  - 2.3.3 **Analyse des 5 Forces de Porter :** Concurrence, Pouvoir des clients/fournisseurs, Nouveaux entrants, Remplaçants.

## 3. Analyse des Besoins et Étude de Faisabilité (Volet Entrepreneuriat)
- **3.1 Besoins Fonctionnels et Techniques (Cahier des charges)**
  - 3.1.1 Besoins de détection temps réel et de journalisation.
  - 3.1.2 Contraintes de performances matérielles.
- **3.2 Étude de Faisabilité et Business Plan**
  - 3.2.1 **Étude Technique :** Matériels informatiques (Serveurs, RAM, TAPs réseau) et profils RH nécessaires (Ingénieurs SOC, Développeurs).
  - 3.2.2 **Étude Commerciale & Marketing :**
    - Politique Produit : Boîtier de sondes réseau (Hardware) + Monitoring (SaaS).
    - Politique Prix : Modèle de facturation par abonnement mensuel (MSSP).
    - Politique Place & Promotion : Vente B2B, Webinaires et démos Live Hacking.
  - 3.2.3 **Étude Financière :** Budget prospection, Acquisition, Taux de conversion prospects, Taux de Churn d'abonnés estimé.

## 4. Conception Globale et Architecture Technique
- **4.1 Objectifs de l'architecture :** Isoler et surveiller l'application cible.
- **4.2 Description des composants :**
  - 4.2.1 Le serveur vulnérable Target (Application "SecLab" avec base PostgreSQL).
  - 4.2.2 La Sonde Réseau (Suricata en mode passif/SPAN).
  - 4.2.3 La Sonde Hôte avec eBPF (Falco) et la prévention (AppArmor).
  - 4.2.4 Le pipeline Collecte/SIEM (Filebeat, Elasticsearch, Kibana).
- **4.3 Schéma d'architecture proposé :** Topologie du réseau virtuel complet.
- **4.4 Flux opérationnel détaillé :** Comment une requête d'un attaquant transite et génère une alerte SOC.

## 5. Implémentation et Phase Offensive (Validation par l'Attaque)
*Démontrez ici que la solution a une utilité face aux assauts.*
- **5.1 Déploiement de l'environnement :** Configuration des VMs, réseau host-only.
- **5.2 Scénario d'attaque : La "Cyber Kill Chain"**
  - 5.2.1 Phase 1 : Reconnaissance réseau (Scans Nmap).
  - 5.2.2 Phase 2 : Interaction web (Découverte de l'application SecLab).
  - 5.2.3 Phase 3 : Exploitation (Injections SQL d'authentification et attaques SSRF/XSS).
  - 5.2.4 Phase 4 : Accès initial / Compromission (Reverse Shell via diagnostic.php).
  - 5.2.5 Phase 5 : Post-exploitation (Lecture de /etc/shadow).

## 6. Résultats : Détection et Supervision (Phase Défensive)
*La validation de votre prototype.*
- **6.1 Détection Réseau avec Suricata :** Preuves de capture des payloads SQLi/XSS sur le fil (Alertes eve.json).
- **6.2 Surveillance Syscalls avec Falco :** Détection du Shell clandestin bash et connexions sortantes.
- **6.3 Prévention active avec AppArmor :** Preuve des blocages (AVC DENIED) empêchant le pire.
- **6.4 Visualisation SOC avec Kibana :**
  - 6.4.1 Ingestion via Filebeat et indexation Elasticsearch.
  - 6.4.2 Présentation du Dashboard SOC global avec graphiques et alertes triées.
- **6.5 Limites et améliorations possibles :** L'angle mort du chiffrement TLS sans proxy de déchiffrement.

## 7. Conclusion et Perspectives
- **7.1 Synthèse de la réalisation technique :** Déploiement d'un pipeline SIEM bout-en-bout.
- **7.2 Synthèse du modèle d'affaire (Entrepreneuriat) :** Transformation d'un PoC académique en idée de start-up MDR B2B.
- **7.3 Apports du projet et Retour d'expérience.**
- **7.4 Perspectives futures :** L'intégration de Machine Learning et de SOAR pour la réponse automatisée.
