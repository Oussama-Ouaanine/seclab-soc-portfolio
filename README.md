# SecLab : Architecture SOC-in-a-Box & Défense en Profondeur 🛡️

[![Projet de Fin d'Année](https://img.shields.io/badge/Type-Projet_de_Fin_d'Année_M2-blue.svg)](#)
[![Stack: Elastic | Suricata | Falco | AppArmor](https://img.shields.io/badge/Stack-ELK_|_Suricata_|_Falco_|_AppArmor-orange.svg)](#)
[![Statut: MVP Technique](https://img.shields.io/badge/Statut-MVP_Technique_Validé-success.svg)](#)

**SecLab** est un projet d'ingénierie en cybersécurité complet (Projet de Fin d'Année). Il s'agit du déploiement d'un laboratoire de sécurité contrôlé (Proof of Concept) visant à construire une architecture de détection et de supervision multi-couches pour les systèmes Linux. 

Dans une démarche entrepreneuriale B2B, cette architecture technique a été pensée et packagée comme une solution **"SOC-in-a-Box" (SOC as a Service)** open-source, destinée à lever les barrières financières et techniques empêchant les PME de se doter d'une supervision de sécurité proactive.

---

## 🎯 Objectifs du Projet

1. **Défense en Profondeur :** Concevoir une architecture combinant la surveillance réseau (NIDS), la détection comportementale hôte (HIDS) et le contrôle d'accès (MAC).
2. **Centralisation SIEM :** Construire un pipeline complet de collecte, d'indexation et de visualisation d'événements de sécurité.
3. **Validation Offensive :** Simuler un scénario d'attaque complet (Cyber Kill Chain) sur une application web vulnérable développée sur-mesure pour éprouver les capacités de détection.
4. **Dimension Entrepreneuriat :** Valider le besoin marché des PME (via questionnaire) et modéliser le business plan d'un service de supervision infogéré.

---

## 🏗️ Architecture du Laboratoire

Le laboratoire repose sur un environnement virtualisé isolé (LAN interne host-only) structuré autour de 3 Machines Virtuelles et d'une machine hôte (Attaquant) :

* **Hôte (Attaquant) :** Simulation des attaques (Nmap, Netcat, navigateurs, scripts).
* **VM Target (Cible) :** Serveur hébergeant l'application web métier **SecLab** (Apache + PostgreSQL). Protégée par **Falco** (détection des syscalls) et **AppArmor** (restrictions MAC).
* **VM Suricata (NIDS) :** Interface réseau en mode miroir (SPAN port) écoutant passivement le trafic entre l'attaquant et la cible via **Suricata**. Ne bloque pas le trafic pour éviter les interruptions de service métier.
* **VM Monitoring (SIEM) :** La tour de contrôle logicielle (Stack **Elasticsearch & Kibana**). Elle ingère toutes les alertes réseau (Suricata) et système (Falco, AppArmor, Auth) expédiées par **Filebeat**.

### 🔄 Flux Logique

```text
┌─────────────┐       Trafic réseau cible      ┌──────────────────┐
│    Hôte     │───────────────────────────────▶│     VM Target    │
│  Attaquant  │                                │  SecLab (Appli)  │
└──────┬──────┘                                │  Falco + AppArmor│
       │                                       └────────┬─────────┘
       │ Copie miroir (SPAN)                            │
       ▼                                                │
┌─────────────┐                                         │
│ VM Suricata │                                         │
│   (NIDS)    │                                         │
└──────┬──────┘                                         │
       │                                                │
       └────────────────────────────────────────────────┘
                          Alertes (Filebeat)
                                  ▼
                        ┌──────────────────┐
                        │   VM Monitoring  │
                        │    (ELK Stack)   │
                        └──────────────────┘
```

---

## ⚔️ Validation par la Cyber Kill Chain

L'architecture est testée activement via un scénario d'intrusion réaliste pour valider la réaction de chaque couche de sécurité :

| Phase Lockheed Martin | Action Attaquant | Détection / Prévention | Couche activée |
|-----------------------|------------------|------------------------|----------------|
| **1. Reconnaissance** | Scan Nmap agressif | Alerte pattern de scan réseau | **Suricata** (NIDS) |
| **2. Interaction** | Navigation, fuzzing formulaires | Analyse trafic HTTP | **Suricata** (NIDS) |
| **3. Exploitation** | Payload SQLi / RCE web | Détection de signatures d'attaque | **Suricata** (NIDS) |
| **4. Accès Initial** | Lancement d'un Reverse Shell | Alerte : Shell spawné par serveur web | **Falco** (HIDS / eBPF) |
| **5. Post-Exploitation**| Tentative de lecture `/etc/shadow` | **Blocage total** de l'accès fichier | **AppArmor** (MAC) |

Toutes ces étapes sont automatiquement corrélées et horodatées au sein des tableaux de bord **Kibana**, permettant au "SOC Analyst" virtuel de reconstituer l'attaque de bout en bout.

---

## 🚀 Target Application Deployment (SecLab Web App)

The `src/seclab_web_app/` directory contains the source code for the vulnerable e-commerce application used as the target ("Patient Zero") in this environment.

> ⚠️ **WARNING:** This application is INTENTIONALLY VULNERABLE (SQL Injections, XSS, etc.). **Never deploy it on a production server or expose it to the Internet.** Use it only in an isolated local environment (Target Virtual Machine).

### 📋 Prerequisites
* A Web server (Apache2 or Nginx)
* PHP (with PDO and pgsql extensions: `php-pgsql`)
* PostgreSQL

### Step 1: Deploy Files
Move the source code to your web server's root directory (often `/var/www/html`).
```bash
sudo cp -r src/seclab_web_app/* /var/www/html/
sudo chown -R www-data:www-data /var/www/html/
```

### Step 2: Database (PostgreSQL)
The application communicates with a PostgreSQL database by default. Connect to your server:
```bash
sudo -u postgres psql
```
Create the database and set the default credentials expected by the application:
```sql
CREATE DATABASE "Test_Lab";
ALTER USER postgres WITH PASSWORD '123456';
\q
```
*(If you have a `.sql` dump file to regenerate the `users`, `products` tables, etc., import it into this database.)*

### Step 3: Configuration
If you want to use different PostgreSQL credentials, modify the `config.php` file:
```php
$servername = "127.0.0.1";
$username   = "postgres";    // Database username
$password   = "123456";      // Database password
$dbname     = "Test_Lab";    // Database name
```

### Step 4: Start the Application
Restart your services and access the application via your internal network browser:
```bash
sudo systemctl restart apache2
sudo systemctl restart postgresql
```

### 🐛 Known Vulnerabilities Summary
The SecLab Web App and its APIs (`/api/v1/`, `/api/v2/graphql`) are intentionally equipped with flaws from the OWASP Top 10, including:
* **SQL Injection (SQLi):** Parameters map directly to SQL queries (bypassing PDO safety wrappers).
* **Mass Assignment (BOPLA) & BOLA:** APIs allow injecting arbitrary fields into DB updates and modifying other users' resources.
* **Sensitive Data Exposure:** Exposed password hashes and roles via GraphQL endpoints.
* **XSS:** Unsanitized user inputs reflected on the web pages.

*(For detailed information on the specific vulnerabilities, see the [Web App README](src/seclab_web_app/README.md).)*

---

## 📂 Documentation et Livrables

L'ensemble de la documentation académique, technique et business est disponible dans le répertoire `docs/`.

### 1. Ingénierie & Gestion de Projet
- `docs/gestion_projet/cahier_des_charges_v1_4.md` : Les spécifications techniques complètes, l'architecture réseau et la planification (Gantt/PERT).
- `docs/gestion_projet/rapport_outline.md` : Le plan détaillé du rapport final, mixant l'état de l'art technique et le Business Plan (SWOT, PESTEL, Porter).

### 2. Validation Business (Entrepreneuriat)
- `docs/gestion_projet/Reponses_TP1_Origine_Idee.md` : La genèse de l'idée, défectuologie et validation du concept MVP.
- `docs/gestion_projet/Questionnaire_Validation_Marche.md` : Matrice d'enquête marché (français/anglais) pour valider le modèle "SOC-in-a-Box" auprès des PME.

### 3. Fiches de Configuration (Défense & Vulnérabilités)
- `docs/defense/` : Notes techniques de déploiement pour AppArmor, Falco et Suricata.
- `docs/vulnerabilities/` : Liste des failles et CVEs intentionnellement intégrées dans l'application SecLab patient-zéro.

---

*Développé dans le cadre d'un Projet de Fin d'Année (Ingénierie Cybersécurité) par Oussama Ouaanine, Nada Rihi et Aya Er-raoudy.*
