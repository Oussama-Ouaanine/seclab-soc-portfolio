# SecLab: SOC-in-a-Box Architecture & Defense in Depth 🛡️

[![End-of-Year Project](https://img.shields.io/badge/Type-M2_Engineering_Project-blue.svg)](#)
[![Stack: Elastic | Suricata | Falco | AppArmor](https://img.shields.io/badge/Stack-ELK_|_Suricata_|_Falco_|_AppArmor-orange.svg)](#)
[![Status: Technical MVP](https://img.shields.io/badge/Status-Validated_Technical_MVP-success.svg)](#)

**SecLab** is a comprehensive cybersecurity engineering project (End-of-Year Project). It involves the deployment of a controlled security laboratory (Proof of Concept) aimed at building a multi-layered detection and monitoring architecture for Linux systems.

In a B2B entrepreneurial approach, this technical architecture has been designed and packaged as an open-source **"SOC-in-a-Box" (SOC as a Service)** solution. It aims to remove the financial and technical barriers preventing SMEs from adopting proactive security monitoring.

---

## 🎯 Project Objectives

1. **Defense in Depth:** Design an architecture combining network monitoring (NIDS), host behavioral detection (HIDS), and Mandatory Access Control (MAC).
2. **Centralized SIEM:** Build a complete pipeline for collecting, indexing, and visualizing security events.
3. **Offensive Validation:** Simulate a full attack scenario (Cyber Kill Chain) on a custom-developed vulnerable web application to test the detection capabilities.
4. **Entrepreneurial Dimension:** Validate the market need for SMEs (via market surveys) and model the business plan for a managed monitoring service.

---

## 🏗️ Laboratory Architecture

The laboratory is based on an isolated virtualized environment (host-only internal LAN) structured around 3 Virtual Machines and a Host machine (Attacker):

* **Host (Attacker):** Simulates attacks (Nmap, Netcat, browsers, malicious scripts).
* **Target VM:** Server hosting the business web application **SecLab** (Apache + PostgreSQL). Protected by **Falco** (syscall detection) and **AppArmor** (MAC restrictions).
* **Suricata VM (NIDS):** Network interface in mirror mode (SPAN port) passively listening to the traffic between the attacker and the target via **Suricata**. It does not block traffic to avoid disrupting business services.
* **Monitoring VM (SIEM):** The software control tower (**Elasticsearch & Kibana** Stack). It ingests all network alerts (Suricata) and system alerts (Falco, AppArmor, Auth) shipped by **Filebeat**.

### 🔄 Logical Workflow

```text
┌─────────────┐       Target Network Traffic   ┌──────────────────┐
│    Host     │───────────────────────────────▶│     Target VM    │
│  Attacker   │                                │  SecLab (App)    │
└──────┬──────┘                                │  Falco + AppArmor│
       │                                       └────────┬─────────┘
       │ SPAN Mirror Copy                               │
       ▼                                                │
┌─────────────┐                                         │
│ Suricata VM │                                         │
│   (NIDS)    │                                         │
└──────┬──────┘                                         │
       │                                                │
       └────────────────────────────────────────────────┘
                      Alerts (Filebeat)
                              ▼
                    ┌──────────────────┐
                    │   Monitoring VM  │
                    │    (ELK Stack)   │
                    └──────────────────┘
```

---

## ⚔️ Cyber Kill Chain Validation

The architecture is actively tested via a realistic intrusion scenario to validate the response of each security layer:

| Lockheed Martin Phase | Attacker Action | Detection / Prevention | Triggered Layer |
|-----------------------|-----------------|------------------------|-----------------|
| **1. Reconnaissance** | Aggressive Nmap Scan | Network scan pattern alert | **Suricata** (NIDS) |
| **2. Weaponization/Delivery** | Browsing, form fuzzing | HTTP traffic analysis | **Suricata** (NIDS) |
| **3. Exploitation** | SQLi / Web RCE Payload | Attack signature detection | **Suricata** (NIDS) |
| **4. Installation/Action** | Launching a Reverse Shell | Alert: Shell spawned by web server | **Falco** (HIDS / eBPF) |
| **5. Actions on Objectives**| Attempt to read `/etc/shadow` | **Total Block** of file access | **AppArmor** (MAC) |

All these steps are automatically correlated and timestamped within the **Kibana** dashboards, allowing the virtual "SOC Analyst" to reconstruct the attack end-to-end.

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

### 🐛 Known Vulnerabilities Summary (For Pentesting)
The SecLab Web App is intentionally equipped with flaws from the OWASP Top 10 to train SOC analysts. Below is a breakdown of the specific vulnerabilities:

#### 1. Website Frontend & UI Vulnerabilities
* **Cross-Site Scripting (XSS):** Stored and Reflected XSS vulnerabilities exist in user inputs (e.g., product reviews, profile updates, search fields) which are rendered directly on the HTML pages without sanitization.
* **Form-based SQL Injections (SQLi):** Login portals and search functionalities directly concatenate POST payloads, allowing authentication bypass (e.g., `' OR 1=1--`).
* **Broken Authentication / Session Management:** Unsecure cookie handling, weak hashing algorithms (MD5), and no rate-limiting on login forms allow session hijacking and brute-force attacks.
* **Local File Inclusion (LFI) / SSRF:** Certain user parameters (like the `avatar_url` profile field) mishandle file paths, allowing attackers to request local system protocols (e.g., `file:///proc/self/attr/current`).

#### 2. REST & GraphQL API Vulnerabilities (`/api/`)
* **Mass Assignment (BOPLA):** The `POST` and `PUT` endpoints (`/api/v1/user`) dynamically assign incoming JSON keys directly into the SQL update statements. Attackers can inject privilege escalation fields (e.g., `"profile": "admin"`).
* **Broken Object Level Authorization (BOLA/IDOR):** The API does not verify ownership of the requested resource. An authenticated user can modify or execute `DELETE` queries on other users' records by simply altering the `id` in the URL (e.g., `DELETE /api/v1/product/5`).
* **API SQL Injections:** URL parameters (`GET ?id=`) and POST payloads are executed blindly without prepared statements, allowing the exfiltration of the entire database via UNION-based payloads.
* **GraphQL Sensitive Data Exposure:** The GraphQL endpoint (`/api/v2/graphql`) lacks field-level authorization and exposes sensitive internal database structures upon introspection, including hashed passwords and administrative roles.

*(For detailed information and code-level vulnerability analysis, see [src/seclab_web_app/README.md](src/seclab_web_app/README.md).)*

---

## 📂 Documentation & Deliverables

All academic, technical, and business documentation is available in the `docs/` repository.

### 1. Engineering & Project Management
- `docs/gestion_projet/cahier_des_charges_v1_4.md`: Full technical specifications, network architecture, and project planning (Gantt/PERT).
- `docs/gestion_projet/rapport_outline.md`: The final report outline, mixing state-of-the-art technical analysis and Business Plan components (SWOT, PESTEL, Porter).

### 2. Business Validation (Entrepreneurship)
- `docs/gestion_projet/Reponses_TP1_Origine_Idee.md`: Project genesis, problem/solution definition, and MVP conceptualization.
- `docs/gestion_projet/Questionnaire_Validation_Marche.md`: Market survey matrix (French/English) crafted to validate the "SOC-in-a-Box" need for SMEs.

### 3. Configuration & Defense Playbooks
- `docs/defense/`: Technical deployment notes for AppArmor, Falco, and Suricata.
- `docs/vulnerabilities/`: List of the intentional flaws and CVEs integrated into the SecLab patient-zero application.

---

*Developed as an End-of-Year Project (Cybersecurity Engineering) by Oussama Ouaanine, Nada Rihi, and Aya Er-raoudy.*
