# CAHIER DES CHARGES — PROJET DE FIN D'ANNÉES

**Conception et déploiement d'une architecture multi-couches de surveillance et de détection d'intrusions pour les systèmes Linux**

Réalisé par : Oussama Ouaanine · Nada Rihi · Aya Er-raoudy

Projet de Fin d'Années (PFA) — Année universitaire 2025/2026  
Ingénierie Informatique — Cybersécurité  
_Usage académique uniquement._

---

## Table des matières

1. [Contexte et Objectifs](#1-contexte-et-objectifs)
2. [Architecture du Laboratoire](#2-architecture-du-laboratoire)
3. [Architecture Réseau](#3-architecture-réseau)
4. [Architecture Générale des Composants de Sécurité](#4-architecture-générale-des-composants-de-sécurité)
5. [Besoins Fonctionnels et Non Fonctionnels](#5-besoins-fonctionnels-et-non-fonctionnels)
6. [Scénario d'Attaque](#6-scénario-dattaque)
7. [Méthodologie et Planning](#7-méthodologie-et-planning)
8. [Glossaire](#8-glossaire)

---

## 1. Contexte et Objectifs

### 1.1. Contexte du projet

Ce projet de fin d'année s'inscrit dans une formation en ingénierie informatique, spécialisation cybersécurité. Il consiste à concevoir et déployer un laboratoire de sécurité contrôlé permettant d'étudier les mécanismes de détection d'intrusions sur plusieurs couches d'une architecture système.

L'architecture repose sur le modèle de **défense en profondeur**, combinant détection réseau, surveillance comportementale à l'hôte, contrôle d'accès obligatoire et centralisation des événements de sécurité.

### 1.2. Problématique

Comment mettre en œuvre une architecture de détection multi-couches capable d'identifier, de journaliser et de partiellement prévenir un scénario d'attaque interne réaliste, dans un environnement virtualisé à ressources limitées ?

### 1.3. Objectifs

- Déployer et configurer des outils de détection réseau (NIDS) et hôte (HIDS)
- Mettre en pratique le contrôle d'accès obligatoire (MAC) avec AppArmor
- Construire un pipeline complet de collecte, stockage et visualisation des logs
- Simuler et analyser un cycle d'attaque complet du scan initial à la post-exploitation
- Corréler des événements multi-sources dans une interface de type SOC

### 1.4. Périmètre

Projet limité à un environnement virtualisé local. Vulnérabilités intentionnellement introduites (Application web vulnérable "SecLab" basée sur Apache/PostgreSQL). Aucun test sur système tiers ou en production.

---

## 2. Architecture du Laboratoire

### 2.1. Vue d'ensemble

Le laboratoire repose sur **3 machines virtuelles (VM)** et la machine hôte qui joue le rôle d'attaquant. Les VMs sont déployées dans un réseau local virtuel isolé (LAN interne, mode host-only) ; la machine hôte y accède directement via son adaptateur réseau virtuel.

**Tableau 1 — Composition du laboratoire (3 VMs + machine hôte)**

| VM | Nom | OS | Rôle et composants |
|----|-----|----|--------------------|
| Hôte | Machine Hôte (Attaquant) | Windows / Linux (natif) | Simulation des attaques — Nmap, Netcat, curl, navigateur web |
| VM2 | Target Machine | Ubuntu 22.04 | Serveur web vulnérable (Application SecLab), Falco (HIDS), AppArmor (MAC), Filebeat |
| VM3 | Suricata IDS | Ubuntu 22.04 | Surveillance du trafic réseau en mode IDS (mirroring) — Suricata |
| VM4 | Monitoring Stack | Ubuntu 22.04 | Centralisation et visualisation — Elasticsearch, Kibana, Filebeat |

### 2.2. Schéma global du laboratoire

**Flux logique :** `Attaquant → Suricata → Cible → Logs → Elasticsearch → Kibana`

```
┌─────────────┐     trafic réseau     ┌──────────────────┐
│    Hôte     │──────────────────────▶│   VM3            │
│  Attaquant  │                        │   Suricata IDS   │
│  Nmap•Netcat│                        │   (mirroring)    │
└──────┬──────┘                        └────────┬─────────┘
       │  trafic direct                          │ alertes
       ▼                                         ▼
┌─────────────┐     logs (Filebeat)    ┌──────────────────┐
│    VM2      │──────────────────────▶│   VM4            │
│   Target    │                        │ Monitoring Stack │
│ SecLab•Falco│                        │ Elasticsearch    │
│  •AppArmor  │                        │ •Kibana          │
└─────────────┘                        └──────────────────┘
```

### 2.3. Ressources matérielles

**Tableau 2 — Allocation des ressources matérielles**

| VM | Composants | RAM | Remarques |
|----|-----------|-----|-----------|
| Hôte | Nmap, Netcat, curl | — | Machine physique — pas de VM dédiée |
| VM2 | Application SecLab, Falco, AppArmor, Filebeat | 4 Go | Machine la plus sollicitée |
| VM3 | Suricata + règles | 2 Go | Mode IDS — pas de blocage |
| VM4 | Elasticsearch, Kibana | 6 Go | Elasticsearch est gourmand en heap JVM |
| **Total VMs** | | **12 Go** | 4 Go restants pour l'hôte (attaquant) |

---

## 3. Architecture Réseau

### 3.1. Topologie du réseau virtuel

Les 3 VMs sont déployées dans un réseau virtuel isolé (mode host-only). La machine hôte accède directement aux VMs via l'interface virtuelle de l'hyperviseur ; elle joue le rôle d'attaquant sans nécessiter de VM supplémentaire.

**Tableau 3 — Plan d'adressage du réseau virtuel**

| Machine | Adresse IP | Rôle réseau |
|---------|-----------|-------------|
| Hôte (Attaquant) | 192.168.56.1 | Source des attaques (IP par défaut de l'hôte en host-only) |
| VM3 — Suricata IDS | 192.168.56.20 | Point d'écoute passif (mode miroir) |
| VM2 — Target | 192.168.56.30 | Cible des attaques |
| VM4 — Monitoring | 192.168.56.40 | Réception des logs et alertes |

### 3.2. Positionnement de Suricata

Dans cette implémentation, Suricata est configuré en **mode IDS (SPAN)**. Le trafic entre la machine hôte et VM2 est copié vers l'interface d'écoute de Suricata — aucune interférence avec le flux réseau.

Suricata est déployé en mode **IDS (mirroring/SPAN)** :

- Suricata surveille une copie du trafic réseau entre la machine hôte et VM2
- Il n'est pas positionné en coupure — il **ne bloque pas** le trafic
- Les alertes générées sont envoyées vers la VM4 (Elasticsearch)
- **Limitation :** le trafic HTTPS chiffré ne peut pas être inspecté sans déchiffrement TLS préalable

> **Choix du mode IDS vs IPS :** Le mode IDS (passif) a été retenu pour la simplicité du déploiement et pour éviter les interruptions de service involontaires. Le mode IPS (inline) est possible mais nécessite une configuration réseau plus complexe.

### 3.3. Schéma du flux réseau

```
┌─────────────┐──── trafic direct ────▶┌─────────────┐
│    Hôte     │                         │    VM2      │
│  Attaquant  │                         │   Target    │
└─────────────┘                         └─────────────┘
       │
       │ copie trafic (SPAN)
       ▼
┌──────────────────┐
│     VM3          │
│  Suricata IDS    │  ← Analyse passive / aucune interférence
└──────────────────┘
```

---

## 4. Architecture Générale des Composants de Sécurité

### 4.1. Vue d'ensemble

Les composants de sécurité déployés forment une architecture de détection et de contrôle complémentaire. Chaque outil opère à une couche différente du système Linux.

**Tableau 4 — Architecture générale des composants de sécurité**

| Composant | Type | Couche | Rôle |
|-----------|------|--------|------|
| Suricata | NIDS | Réseau | Analyse passive du trafic réseau en mode IDS (mirroring/SPAN) |
| Falco | HIDS | Noyau Linux | Détection comportementale via appels système (syscalls / eBPF) |
| AppArmor | MAC | Noyau Linux | Contrôle d'accès obligatoire — profils de restriction par processus |
| ELK Stack | SIEM | Application | Centralisation, indexation et visualisation des alertes |

### 4.2. Principe de détection multi-couches

L'architecture repose sur la **complémentarité** des outils :

- **Suricata** surveille le trafic réseau depuis la machine hôte et génère des alertes sur les patterns suspects (scans Nmap, injections HTTP).
- **Falco** intercepte les appels système (syscalls) du noyau Linux pour détecter des comportements anormaux : exécution d'un shell depuis un processus web, accès à `/etc/shadow`, connexion réseau sortante (reverse shell).
- **AppArmor** applique des profils de sécurité aux processus : fichiers autorisés/interdits, restrictions d'exécution de shell. En mode `enforce`, il bloque les actions non autorisées.
- **ELK Stack** centralise tous les logs via Filebeat, les indexe dans Elasticsearch, et les visualise dans Kibana : timeline d'attaque, corrélation multi-source (Suricata + Falco + AppArmor), filtrage par adresse IP.

> **Détection vs. prévention :** Suricata et Falco assurent la **détection** (alertes, sans blocage). AppArmor assure la **prévention** (blocage en mode enforce). L'ELK stack fournit la **visibilité** et la corrélation des événements.

---

## 5. Besoins Fonctionnels et Non Fonctionnels

### 5.1. Besoins fonctionnels

- **Détection réseau :** Le système doit analyser le trafic réseau en temps réel et générer des alertes sur les activités suspectes (scans de ports, injections, tentatives d'exploitation).
- **Détection hôte :** Le système doit surveiller les appels système (syscalls) des processus et détecter les comportements anormaux en temps réel (shell spawned depuis un serveur web, accès à des fichiers sensibles).
- **Contrôle d'accès :** Le système doit appliquer des profils de restriction aux processus cibles et bloquer les actions non autorisées (lecture de `/etc/shadow`, exécution de shell).
- **Centralisation des logs :** Le système doit collecter, indexer et stocker les alertes de tous les composants dans un dépôt centralisé.
- **Visualisation SOC :** Le système doit fournir une interface de visualisation permettant de reconstruire la timeline d'attaque, de corréler les alertes multi-sources et de filtrer par adresse IP.
- **Simulation d'attaque :** Le système doit permettre l'exécution d'un scénario d'attaque contrôlé en 5 phases (kill chain) afin de valider chaque couche de détection.

### 5.2. Besoins non fonctionnels

**Tableau 5 — Besoins non fonctionnels du système**

| Catégorie | Exigence |
|-----------|---------|
| Performance | Les alertes doivent être générées en temps réel (latence < 5 secondes entre l'événement et l'alerte visible dans Kibana). |
| Disponibilité | L'environnement doit rester fonctionnel pendant toute la durée des tests (sessions de validation). |
| Maintenabilité | Les règles Suricata et Falco doivent être facilement modifiables sans redémarrage complet du système. |
| Contrainte matérielle | L'ensemble du système doit fonctionner sur une machine physique avec 16 Go de RAM maximum (12 Go alloués aux VMs). |
| Sécurité | L'environnement doit être totalement isolé du réseau de production (LAN virtuel host-only). |
| Portabilité | Tous les outils utilisés sont open-source et déployables sur toute distribution Linux basée sur Ubuntu 22.04. |
| Traçabilité | Tout événement de sécurité doit être journalisé, indexé et consultable dans Elasticsearch avec horodatage. |

---

## 6. Scénario d'Attaque

### 6.1. Description

Le scénario simule la machine hôte jouant le rôle d'attaquant, située dans le même réseau virtuel que la cible (VM2). La méthodologie suit la **Cyber Kill Chain de Lockheed Martin**.

```
Ph.1 Reconn. → Ph.2 Interaction → Ph.3 Exploit. → Ph.4 Accès → Ph.5 Post-exploit.
```

### 6.2. Kill Chain et couverture des composants

**Tableau 6 — Kill Chain et composants de sécurité activés à chaque phase**

| Phase | Action | Outil | Couche de sécurité activée |
|-------|--------|-------|---------------------------|
| Ph.1 — Reconn. | Scan de ports Nmap | Nmap | Suricata : alerte sur pattern de scan (règles actives) |
| Ph.2 — Interaction | Accès appli. web, ident. des champs | Navigateur | Suricata : analyse trafic HTTP |
| Ph.3 — Exploit. | Injection de commande : `; whoami` | curl | Suricata : pattern HTTP suspect (non garanti) |
| Ph.4 — Acc. init. | Reverse shell via RCE | Netcat | Falco : détection via syscalls — shell spawned depuis processus web + connexion sortante |
| Ph.5 — Post-exploit. | Lecture `/etc/shadow` | Shell | Falco : alerte accès fichier sensible. AppArmor : blocage si profil enforce |

### 6.3. Limites

- **Suricata :** détecte des patterns, non la confirmation d'exploitation réussie
- **Falco :** détecte mais ne bloque pas — rôle de HIDS passif
- **AppArmor :** efficace uniquement si le profil est en mode `enforce`
- **HTTPS :** trafic chiffré non inspectable par Suricata sans déchiffrement préalable
- La détection dépend du paramétrage des règles et n'est pas exhaustive

---

## 7. Méthodologie et Planning

### 7.1. Avant-planning

#### 7.1.1. Approche méthodologique

Le projet adopte une approche **classique (séquentielle)**, structurée selon la **méthode en V**. Cette approche garantit une traçabilité entre les exigences définies en amont et les phases de validation en aval.

```
Spécification des exigences
        ↓                              ↑
  Conception de l'architecture    Rapport final
        ↓                              ↑
  Déploiement des composants   Validation pipeline logs
        ↓                              ↑
        └─── Exécution du scénario ───→ Validation des détections
```

#### 7.1.2. Justification du choix méthodologique

- **Approche classique :** Les exigences du projet sont clairement définies dès le départ (outils fixés, scénario prédéfini, durée bornée). Une approche itérative n'apporte pas de valeur ajoutée dans ce contexte.
- **Méthode en V :** Chaque phase de conception est associée à une phase de validation correspondante : les exigences du CDC sont validées par le rapport final ; l'architecture est validée par le pipeline de logs ; le déploiement est validé par l'exécution du scénario d'attaque.
- **Traçabilité :** Chaque composant déployé correspond à un besoin fonctionnel identifié dans le CDC, et sa validation est vérifiable via les alertes et dashboards Kibana.

### 7.2. Phases du projet

**Tableau 7 — Phases du projet**

| # | Phase | Activités | Durée |
|---|-------|-----------|-------|
| 1 | Conception | Étude outils, architecture, rédaction CDC | S1 |
| 2 | Infrastructure VM | Installation VMs, réseau, SecLab | S2–S3 |
| 3 | Sécurité | Suricata (mode IDS), Falco (règles), AppArmor (profils) | S3–S4 |
| 4 | Pipeline logs | Filebeat, Elasticsearch (heap), Kibana, dashboards | S4 |
| 5 | Scénario | Exécution kill chain, validation détections | S5 |
| 6 | Rapport | Analyse résultats, rédaction, soutenance | S6 |

### 7.3. Diagramme de Gantt

30 jours ouvrés sur 6 semaines. `▶` = date du jour (J10).

| Tâche | S1 | S2 | S3 | S4 | S5 | S6 |
|-------|----|----|----|----|----|----|
| **Phase 1 — Conception** | | | | | | |
| Étude bibliographique | ██ | | | | | |
| Conception architecture | ██ | | | | | |
| Rédaction CDC | ██ | | | | | |
| ✔ CDC validé | ◆ | | | | | |
| **Phase 2 — Infrastructure VM** | | | | | | |
| Installation VMs | | ██ | | | | |
| Configuration réseau | | ██ | | | | |
| Déploiement SecLab | | | ██ | | | |
| **Phase 3 — Sécurité** | | | | | | |
| Suricata IDS + règles | | | ██ | | | |
| Falco + règles | | | ██ | | | |
| AppArmor enforce | | | | ██ | | |
| **Phase 4 — Pipeline logs** | | | | | | |
| Filebeat + Elasticsearch | | | | ██ | | |
| Dashboards Kibana | | | | ██ | | |
| ✔ Env. opérationnel | | | | ◆ | | |
| **Phase 5 — Scénario** | | | | | | |
| Exécution kill chain | | | | | ██ | |
| Validation détections | | | | | ██ | |
| Ajustements règles | | | | | ██ | |
| **Phase 6 — Rapport** | | | | | | |
| Rédaction rapport | | | | | | ██ |
| Préparation soutenance | | | | | | ██ |
| Soutenance | | | | | | ◆ |

### 7.4. Diagramme de PERT

Nœuds : `numéro | date au plus tôt / au plus tard`. Flèches `→` = chemin critique (marge = 0).

```
(0)      (1)      (3)      (5)      (7)      (9)      (11)
0/0 -A→ 3/3 -C→ 5/5 -E→  8/8 -H→ 13/13 -J→ 20/20 -K→ 22/24
                  |                              |
                  B:5j (→4)                    M:3j
                  |                              ↓
                 (2)                           (11)
                5/7  -D→ (4) 7/9              30/30
                       |
                      F:2j → (6) 9/11 -G:4j→ (7)
                                    I:4j → (10) 22/24
                                    L:2j → (10)
                                           N:2j → (11)
```

**Chemin critique : A → C → E → H → J → K → M → O**  
**Durée totale : 30 jours** — aucun retard toléré sur ces tâches.

### 7.5. Tableau des tâches PERT

**Tableau 8 — Table des tâches PERT**

| T | Désignation | Durée | Prédécesseurs | Critique |
|---|-------------|-------|---------------|---------|
| A | Étude bibliographique | 3j | — | ✅ Oui |
| B | Conception architecture réseau | 5j | — | Non |
| C | Rédaction cahier des charges | 2j | A | ✅ Oui |
| D | Installation VMs | 2j | A | Non |
| E | Configuration réseau virtuel | 3j | B, C | ✅ Oui |
| F | Déploiement de l'application SecLab | 2j | B, D | Non |
| G | Déploiement Suricata (mode IDS) | 4j | C, E | Non |
| H | Déploiement Falco (règles syscalls) | 5j | E | ✅ Oui |
| I | Configuration AppArmor (enforce) | 4j | D, F, G | Non |
| J | Filebeat + Elasticsearch (heap) | 5j | H | ✅ Oui |
| K | Dashboards Kibana | 2j | I, J | ✅ Oui |
| L | Vérification pipeline bout en bout | 2j | J | Non |
| M | Exécution kill chain (5 phases) | 3j | K | ✅ Oui |
| N | Ajustements des règles | 2j | K, L | Non |
| O | Rapport final + soutenance | 7j | M, N | ✅ Oui |

### 7.6. Livrables

**Tableau 9 — Livrables attendus**

| Livrable | Description | Échéance |
|----------|-------------|---------|
| Cahier des charges | Périmètre, architectures, planning | Fin S1 |
| Environnement déployé | VMs, outils, pipeline fonctionnel | Fin S4 |
| Rapport de tests | Alertes, dashboards Kibana | Fin S5 |
| Rapport final + soutenance | Résultats, analyse, limites | Fin S6 |

---

## 8. Glossaire

**Tableau 10 — Glossaire des termes techniques**

| Terme | Définition |
|-------|-----------|
| NIDS | Network Intrusion Detection System — détection d'intrusions réseau |
| HIDS | Host-based IDS — détection basée sur l'analyse comportementale de l'hôte |
| MAC | Mandatory Access Control — contrôle d'accès obligatoire (indépendant des droits Unix) |
| Syscall | Appel système — interface entre processus utilisateur et noyau Linux |
| eBPF | Extended Berkeley Packet Filter — technologie noyau utilisée par Falco |
| SIEM | Security Information and Event Management — corrélation centralisée |
| SOC | Security Operations Center — centre de supervision sécurité |
| Kill Chain | Modélisation des étapes d'une cyberattaque (Lockheed Martin) |
| RCE | Remote Code Execution — exécution de code arbitraire à distance |
| Reverse Shell | Connexion initiée par la cible vers l'attaquant pour exécuter des commandes |
| SecLab | Application web développée (Apache/PostgreSQL) contenant des vulnérabilités intentionnelles |
| ELK | Elasticsearch + Logstash + Kibana — stack open-source de gestion de logs |
| AppArmor | Module LSM Linux — MAC par profils (modes : complain / enforce) |
| Falco | Moteur HIDS open-source basé sur les syscalls Linux (eBPF / kprobe) |
| Suricata | Moteur IDS/IPS/NSM open-source analysant le trafic réseau |
| Filebeat | Agent léger Elastic — expédition de logs vers Elasticsearch |

---

_Cahier des Charges v1.4 — Projet de Fin d'Année — Mars 2026_  
_Usage académique uniquement_
