# Falco Runtime Detection Analysis

Sysdig Falco is an open-source, cloud-native runtime security tool. Unlike AppArmor, which is a prevention tool (it blocks actions), **Falco is a detection and alerting tool**. It works by deeply monitoring Linux system calls (syscalls) in real-time, often using eBPF, to spot anomalous and malicious behavior on the host or inside containers.

Because Falco monitors at the syscall level, it shares similar blind spots with AppArmor regarding application logic, but it is incredibly powerful at catching **post-exploitation** activities.

Here is how Falco would interact with the vulnerabilities found in this web application:

## 🟢 Vulnerabilities Falco CAN Detect

### 1. Remote Command Execution (RCE) - `admin/diagnostic.php`
* **How Falco Detects it:** When an attacker exploits the command injection flaw to run a command like `; cat /etc/passwd` or `; /bin/bash -i`, the Apache (`www-data`) process has to fork and execute a new process (e.g., `sh`, `cat`, `bash`). Falco easily spots web server processes spawning shells or executing system utilities.
* **Example Falco Rule Triggered:** `Terminal shell in container` or `Run shell untrusted`.
* **Sample Custom Condition:** 
  `spawned_process and proc.pname="apache2" and proc.name in (bash, sh, wget, curl, nc, ping)`

### 2. Server-Side Request Forgery (SSRF) / Local File Inclusion (LFI)
* **How Falco Detects it (File Read):** If the attacker exploits the avatar URL to read `file:///etc/shadow`, Apache will make a syscall to open that file. Falco can be configured to alert when non-administrative processes read highly sensitive files.
  * **Rule Triggered:** `Read sensitive file untrusted`
* **How Falco Detects it (Network Scanning):** If the SSRF is used to scan the internal network (e.g., hitting `http://10.0.0.5:22`), the Apache process makes an unexpected outbound TCP connection. Falco can detect web workers initiating outbound connections to private IP spaces.
  * **Rule Triggered:** `Unexpected outbound network connection`

### 3. File Uploads / Web Shell Creation
* **How Falco Detects it:** If the attacker leverages an injection or SSRF to write a `.php` web shell to the disk, Falco will see the `openat` and `write` syscalls creating a new executable script in the web root. 
  * **Rule Triggered:** `Write below web root` or `File created below /var/www by an unexpected process`.

---

## 🔴 Vulnerabilities Falco CANNOT Detect (Natively)

Like AppArmor, Falco lacks visibility into the HTTP headers, HTML DOM, or the specific text of a SQL query. To Falco, an SQL injection looks exactly the same as a legitimate database query: a read from an HTTP socket and a write to a PostgreSQL socket (port 5432).

Falco cannot detect:
* **SQL Injections (SQLi):** Unless the SQLi forces the database engine to spawn a shell (e.g., PostgreSQL `COPY ... PROGRAM`), Falco won't see it.
* **Insecure Direct Object Reference (IDOR) & Privilege Escalation:** Changing a cookie from `user` to `admin` generates no anomalous system calls.
* **Cross-Site Request Forgery (CSRF) & Cross-Site Scripting (XSS):** Completely invisible at the kernel/syscall level.
* **Mass Assignment (BOPLA):** Modifying unexpected API JSON fields is just normal application data processing to Falco.

## Summary: AppArmor vs Falco for this Lab
* **AppArmor** is your **Shield 🛡️**: It will literally block the `diagnostic.php` RCE from working by throwing a "Permission Denied" error.
* **Falco** is your **Security Camera 📷**: It won't stop the RCE from running, but it will immediately fire off a high-severity alert to your Slack/SIEM saying: *"ALERT: apache2 process spawned /bin/sh with arguments '-c ping 127.0.0.1; cat /etc/passwd'"*, allowing your SOC team to respond.