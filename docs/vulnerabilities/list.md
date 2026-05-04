# Security Lab - Vulnerability Report

This comprehensive list outlines all the intentional vulnerabilities identified in the active web application currently running from `/var/www/security-lab/`. The application exhibits various textbook security flaws spanning the OWASP Top 10, perfect for penetration testing scenarios using OWASP ZAP.

## 1. Authentication Bypass & SQL Injections (SQLi)
* **Login Authentication Bypass:**
  * **File:** `index.php`
  * **Description:** The login parameter `email` is taken directly from the `$_POST` array without any sanitization or parameterized queries (`SELECT * FROM account WHERE email = '$email'...`). An attacker can easily construct a payload like `admin@test.com' OR '1'='1` to log in without the correct password.
* **API SQL Injections:**
  * **Files:** `api/index.php` and `api/v1/index.php`
  * **Description:** Contains numerous injection vectors across GET and POST routines. Error messages even return raw SQL errors back to the client (`$conn->error`), exposing the backend architecture layout.
* **GraphQL Injection:**
  * **File:** `api/v2/graphql.php`
  * **Description:** The `accountByName` resolver concatenates `$args['name']` directly into the SQL query without proper protection.
* **Stored SQL Injections:**
  * **Files:** `admin/add-product.php` and `ajouter-panier.php`
  * **Description:** Inputs like `id_product` and insert parameters are blindly consumed by `INSERT` queries.

## 2. Insecure Direct Object Reference (IDOR / BOLA)
* **File:** `profile.php` and `panier.php`
* **OWASP Category:** Broken Object Level Authorization (A01:2021)
* **Description:** User identities are managed by directly passing predictable identifiers (e.g., `?id=2` or `?u=123` via `$_GET`). An attacker can easily tamper with these URL identifiers to view modifying profiles or shopping carts belonging to other users.

## 3. Server-Side Request Forgery (SSRF)
* **File:** `profile.php` (Avatar Update)
* **OWASP Category:** Server-Side Request Forgery (A10:2021)
* **Description:** The application allows users to update their profile picture by specifying an `avatar_url`. This URL is saved blindly and later resolved by the server using `file_get_contents($url)`. An attacker can manipulate this to map internal network ranges, hit AWS Cloud metadata endpoints, or access localhost-only services (e.g., `http://127.0.0.1:8081`).

## 4. Cross-Site Request Forgery (CSRF)
* **File:** `profile.php` (Email Update)
* **Description:** The form used to change the user's email address does not employ any anti-CSRF tokens. An attacker can host a malicious script on an external site that forces an authenticated user's browser to submit a background request changing their email to the attacker's email, seizing control of the account.

## 5. Cross-Site Scripting (XSS)
* **DOM-Based XSS:**
  * **File:** `home.php`
  * **Description:** The script reads the URL's search string (e.g. `?query=XXX`) and directly inserts it into the DOM without sanitization to display "Résultats pour: XXX". An attacker can craft a malicious link executing arbitrary JavaScript in the victim's session.

## 6. Remote Command Execution (RCE / Command Injection)
* **File:** `admin/diagnostic.php`
* **Description:** Designed for server diagnostics (e.g., pinging an IP), the script directly concatenates user input strings into a shell command (`shell_exec` or similar). Appending shell metacharacters like `;` or `&&` allows the complete execution of arbitrary system commands.

## 7. Mass Assignment (BOPLA)
* **Files:** `api/index.php` and `api/v1/index.php`
* **Description:** When updating resources via the API (PUT/POST), the script dynamically bridges HTTP request bodies into SQL `UPDATE` queries without filtering the columns allowed for alteration. An attacker can inject restricted attributes—for example, upgrading their tier by forcefully appending `"profile": "admin"` into the payload.

## 8. Excessive Data Exposure
* **File:** `api/v2/graphql.php`
* **Description:** The GraphQL schemas and resolvers expose excessive inner account details on basic requests. Querying user profiles spills highly sensitive rows, including password hashes, to the client.

## 9. Broken Access Control & Forced Browsing
* **Files:** `admin/admin.php`, `admin/add-user.php`, `admin/diagnostic.php`, etc.
* **Description:** The core admin dashboard files inside the `/admin/` folder are completely missing session or role validation checks (e.g. `admin.php` contains the comment `// Test sur le cookie et le role` but implements no PHP code for it). Any unauthenticated user can directly access and execute administrative actions by typing the URL.

## 10. Privilege Escalation (Cookie Tampering)
* **Files:** `index.php`, `admin/categories.php`
* **Description:** When a user logs in, their profile role is stored directly in an insecure, client-side browser cookie (`setcookie("user_role", ...)`). In files where the teacher actually implemented a role check (like `categories.php`), it relies entirely on this easily temperable cookie (`$_COOKIE['user_role'] === 'admin'`). A standard user can modify this cookie to `admin` and hijack administrative privileges.

## 11. Weak Cryptography
* **File:** `index.php`
* **Description:** The application relies on MD5 hashing for user passwords (`MD5($user_password)`). MD5 is considered cryptographically broken and extremely vulnerable to dictionary and rainbow table attacks.

## 12. Information Disclosure
* **Files:** `composer.json`, `composer.lock`
* **Description:** The PHP dependency management files are hosted directly within the public webroot (`/var/www/security-lab/`). An attacker can easily download them to identify exactly what libraries and versions the server is running, aiding in identifying known CVEs.