# Suricata (Passive NIDS) Analysis

Suricata is a high-performance Network Intrusion Detection System (NIDS). When deployed in **passive mode** on the same network (e.g., via a SPAN port or network TAP), it acts as a wiretap. It captures and inspects all raw network packets traveling to and from your Apache web server and PostgreSQL database.

Because the vulnerable lab application is running over unencrypted HTTP (port 80), Suricata has full visibility into the HTTP headers, URLs, and body payloads. 

Here is how Suricata would perform against the vulnerabilities in this specific web application:

## 🟢 Vulnerabilities Suricata CAN Detect (Signature-Based)

Suricata excels at detecting known malicious patterns within network traffic. Using default rulesets (like the Emerging Threats (ET) ruleset), it would easily catch the following attacks:

### 1. SQL Injections (SQLi)
* **How it detects it:** Suricata inspects the HTTP request URIs (GET) and bodies (POST). It has thousands of signatures looking for SQL metacharacters and union-based payloads (e.g., `' OR '1'='1`, `UNION SELECT`). In this app, the login form or product IDs are heavily vulnerable.
* **Example Suricata Rule (Specific to this app's Login POST):**
  `alert http $EXTERNAL_NET any -> $HTTP_SERVERS $HTTP_PORTS (msg:"LOCAL SECLAB SQLi Attempt in Login"; flow:established,to_server; http_client_body; content:"username="; nocase; content:"' or "; distance:0; nocase; classtype:web-application-attack; sid:1000001; rev:1;)`

### 2. Cross-Site Scripting (XSS)
* **How it detects it:** Suricata scans the HTTP arguments for common malicious JavaScript tags like `<script>`, `javascript:`, or `onerror=alert(1)`. Since your `home.php` DOM XSS attack relies on passing the payload in the URL (`?query=<script>alert(1)</script>`), Suricata will trigger an alert instantly.
* **Example Suricata Rule (Tailored to home.php 'query' parameter):**
  `alert http $EXTERNAL_NET any -> $HTTP_SERVERS $HTTP_PORTS (msg:"LOCAL SECLAB XSS Attempt on home.php"; flow:established,to_server; http_uri; content:"/home.php"; nocase; content:"query="; distance:0; nocase; content:"<script>"; nocase; classtype:web-application-attack; sid:1000002; rev:1;)`

### 3. Remote Command Execution (RCE) / Command Injection
* **How it detects it:** When an attacker submits a payload via `admin/diagnostic.php` containing shell commands (e.g., `8.8.8.8; cat /etc/passwd`), Suricata analyzes the HTTP POST body. It recognizes standard Linux file paths, shell piping `|`, and chaining `;` characters used maliciously.
* **Example Suricata Rule (Tailored to admin/diagnostic.php):**
  `alert http $EXTERNAL_NET any -> $HTTP_SERVERS $HTTP_PORTS (msg:"LOCAL SECLAB Command Injection in diagnostic.php"; flow:established,to_server; http_uri; content:"/admin/diagnostic.php"; nocase; http_client_body; content:"; cat "; nocase; classtype:attempted-admin; sid:1000003; rev:1;)`

### 4. Information Disclosure & Sensitive Files
* **How it detects it:** Suricata can look for requests targeting known sensitive configuration files. If an attacker requests a config or hidden file in your lab directory, it flags the URI.
* **Example Suricata Rule (Targeting config.php disclosure):**
  `alert http $EXTERNAL_NET any -> $HTTP_SERVERS $HTTP_PORTS (msg:"LOCAL SECLAB Source Code Disclosure (config.php)"; flow:established,to_server; http_uri; content:"/config.php"; fast_pattern; classtype:web-application-activity; sid:1000004; rev:1;)`

### 5. Server-Side Request Forgery (SSRF)
* **How it detects it:** In passive mode on the network, Suricata sees *all* traffic. If the attacker exploits `profile.php` to make the web server request internal network resources (via the `avatar_url` parameter we added), Suricata catches the payload.
* **Alert Logic:** "Why is the DMZ Web Server actively initiating an SSH or HTTP connection to the internal network?"
* **Example Suricata Rule (Detecting SSRF payload in profile.php avatar_url update):**
  `alert http $EXTERNAL_NET any -> $HTTP_SERVERS $HTTP_PORTS (msg:"LOCAL SECLAB SSRF Payload in profile.php (avatar_url)"; flow:established,to_server; http_uri; content:"/profile.php"; nocase; http_client_body; content:"avatar_url="; nocase; content:"127.0.0.1"; distance:0; classtype:attempted-recon; sid:1000005; rev:1;)`

---

## 🔴 Vulnerabilities Suricata CANNOT Detect (Logic-Based)

Suricata only knows what is in the packet. It does not understand application state, user sessions, or business logic. 

### 1. Insecure Direct Object Reference (IDOR) & Privilege Escalation
* **Why it fails:** If a user requests `panier.php?u=5` (someone else's cart) or changes their `user_role` cookie to `admin`, the packets look perfectly normal. Suricata doesn't know that the person holding that session cookie is only supposed to have `user_id=2`. 

### 2. Cross-Site Request Forgery (CSRF)
* **Why it fails:** A CSRF attack is just a perfectly formatted, 100% legitimate HTTP POST request automatically submitted by the victim's browser. Without deeply hooking into the application state to track anti-CSRF nonces across sessions, a network sniffer cannot tell it apart from a deliberate user action.

### 3. Mass Assignment (BOPLA)
* **Why it fails:** If an attacker adds `"profile": "admin"` to a JSON payload sent to the API, Suricata just sees valid JSON. It does not know the API schema or the fact that `profile` is a restricted database column.

---

## 🔒 What if the traffic is HTTPS (TLS encrypted)?

If you deploy TLS (HTTPS) on your Apache web server, the packets on the wire will be encrypted. **In passive mode, Suricata will go completely blind to the HTTP contents** (payloads, headers, URIs). 

As a result, Suricata **will no longer detect SQLi, XSS, or RCE** because the payload `?query=<script>alert(1)</script>` will be encrypted ciphertext over the network. 

To solve this and retain Suricata's visibility, you have three architectural options:

1. **TLS Termination Proxy (Recommended):** 
   Place a TLS terminator (like Nginx, HAProxy, or an F5 Load Balancer) in front of Apache. The proxy handles HTTPS for external clients, but forwards unencrypted HTTP to Apache on the internal network. You plug Suricata into this internal, unencrypted segment.
2. **Session Key Extraction (eBPF / SSLKEYLOGFILE):**
   Modern encryption uses Perfect Forward Secrecy (PFS), meaning you can't just give Suricata the server's private key. Instead, you can run an agent on the web server that extracts symmetric TLS session keys from memory (e.g., using eBPF) and feeds them to Suricata in real-time, allowing Suricata to decrypt the traffic passively.
3. **TLS Fingerprinting (Metadata only):**
   Without decrypting, Suricata can still look at TLS handshakes. Using JA3 / JA4 fingerprinting, Suricata can identify if a known malicious botnet, old script (like a Python `requests` library used by an attacker), or vulnerability scanner (like ZAP) is connecting, based strictly on *how* they negotiate the TLS connection. It won't see the attack itself, but it can flag the malicious actor.

---

## Summary: Suricata's Role
* **Placement:** Placed at the network switch, looking at a copy of all traffic.
* **Visibility:** Relies on unencrypted HTTP. (If you enable HTTPS, Suricata goes blind unless you provide it with the TLS decryption keys).
* **Verdict:** Highly effective at spotting the **payload "signatures"** of classic exploits (SQLi, XSS, RCE) crossing the wire, but entirely blind to **authorization bypasses** (IDOR, CSRF, Cookie tampering) that rely on abusing legitimate application features.