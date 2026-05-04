# AppArmor Mitigation Analysis

AppArmor is a Mandatory Access Control (MAC) system for Linux that confines programs to a limited set of resources (files, network access, capabilities). It operates at the OS kernel level, meaning it doesn't understand web application logic (like HTTP requests or user sessions), but it heavily restricts what a compromised application process can do to the system.

If we applied a strict AppArmor profile to the Apache/PHP web server, here is how it would interact with the vulnerabilities identified in `list.md`:

## 🟢 Vulnerabilities AppArmor CAN Mitigate

### 1. Remote Command Execution (RCE) - `admin/diagnostic.php`
* **How AppArmor Stops it:** This is where AppArmor excels. If the RCE vulnerability is exploited, the attacker tries to use PHP to spawn a shell (e.g., executing `/bin/sh`, `ping`, or `whoami`). A strict AppArmor profile for `apache2` would **deny execute permissions (`x`)** to any bin directories (`/bin/*`, `/usr/bin/*`) or strictly whitelist only safe binaries. 
* **Result:** Even though the PHP code is completely vulnerable, when the attacker injects `; cat /etc/passwd`, the OS kernel intercepts the `exec` call and blocks it, rendering the RCE useless.

### 2. Server-Side Request Forgery (SSRF) - Local File Access
* **How AppArmor Stops it:** The SSRF vulnerability allows an attacker to manipulate `file_get_contents($url)`. If the attacker changes the URL to `file:///etc/shadow` or `file:///root/.bash_history` (often called Local File Inclusion), AppArmor restricts what files the Apache process can read. With a proper profile, Apache is explicitly only allowed to read files under `/var/www/security-lab/`.
* **Result:** The SSRF attempt to read internal system files is blocked by the OS with a "Permission denied" error. *(Note: AppArmor is less effective at stopping network-based SSRF, like scanning `http://127.0.0.1:8080`, unless outbound network rules are aggressively locked down).*

---

## 🔴 Vulnerabilities AppArmor CANNOT Mitigate

Because AppArmor operates at the operating system level, it cannot differentiate between a legitimate user logging in and a hacker exploiting a web logic flaw. To AppArmor, the Apache process is just successfully talking to PostgreSQL and serving web pages.

AppArmor cannot stop the following Application-Layer flaws:

* **SQL Injections / Authentication Bypass:** AppArmor allows Apache to talk to PostgreSQL (port 5432). It has no idea that the SQL query format is malicious.
* **Insecure Direct Object Reference (IDOR) & Broken Access Control:** AppArmor doesn't know who "user 1" or "admin" is, nor does it understand cookie validation. It only knows that Apache wants to read `profile.php`.
* **Cross-Site Request Forgery (CSRF) & Cross-Site Scripting (XSS):** These attacks happen entirely between the web server and the victim's browser. AppArmor has no visibility into DOM manipulation or missing CSRF tokens in HTML forms.
* **Mass Assignment (BOPLA) & Excessive Data Exposure:** Like SQLi, this is a data-handling flaw inside PHP and the DB. AppArmor is blind to the structure of JSON APIs.
* **Weak Cryptography:** AppArmor cannot force PHP to use bcrypt instead of MD5.
* **Information Disclosure (`composer.json`):** Since `composer.json` is inside `/var/www/security-lab/`, AppArmor intentionally allows Apache to read it to serve websites. Standard Apache configuration (`.htaccess`), not AppArmor, is needed to block access to specific web files.

## Summary
In short, **AppArmor is a containment tool, not a Web Application Firewall (WAF)**. It won't fix the website's broken login or prevent a hacker from stealing data from the database. However, it acts as a critical safety net that **prevents a web vulnerability (like RCE) from resulting in a total Linux server takeover.**