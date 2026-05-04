# AppArmor Rules for Mitigation

To actually implement the protections discussed in the previous analysis, you would create or modify an AppArmor profile for the Apache web server (typically located at `/etc/apparmor.d/usr.sbin.apache2`).

Below is an example of the specific rules you would add or ensure exist to stop the **Remote Command Execution (RCE)** and **SSRF/Local File Inclusion (LFI)** vulnerabilities in this project.

```apparmor
# Profile for Apache
# File: /etc/apparmor.d/usr.sbin.apache2

#include <tunables/global>

profile apache2 /usr/sbin/apache2 flags=(attach_disconnected) {
  #include <abstractions/base>
  #include <abstractions/nameservice>
  #include <abstractions/php>

  # ---------------------------------------------------------
  # 1. ALLOW NORMAL WEB SERVER OPERATIONS
  # ---------------------------------------------------------
  # Allow Apache to read the web root files (HTML, PHP, JS, CSS)
  /var/www/security-lab/** r,
  
  # Allow Apache to write to specific upload/cache directories if needed
  # (If the application needs to save avatars locally, you would allow it here)
  # /var/www/security-lab/uploads/** rw,

  # Allow network access to talk to the PostgreSQL database on localhost
  network tcp,

  # ---------------------------------------------------------
  # 2. MITIGATE REMOTE COMMAND EXECUTION (RCE)
  # Target: admin/diagnostic.php
  # ---------------------------------------------------------
  # By default, if we don't grant 'x' (execute) permission, AppArmor blocks it.
  # But we can explicitly heavily deny execution of shells and system utilities 
  # to prevent RCE payloads like `; cat /etc/passwd` or `; /bin/bash -i`.

  audit deny /bin/** x,
  audit deny /sbin/** x,
  audit deny /usr/bin/** x,
  audit deny /usr/sbin/** x,
  
  # Even if PHP tries to use shell_exec(), it will be blocked with Permission Denied.

  # ---------------------------------------------------------
  # 3. MITIGATE SSRF / LOCAL FILE INCLUSION
  # Target: profile.php (avatar_url file_get_contents)
  # ---------------------------------------------------------
  # Prevent Apache from reading sensitive operating system files.
  # This stops an attacker from making the avatar_url = "file:///etc/shadow"

  audit deny /etc/shadow r,
  audit deny /etc/passwd r,
  audit deny /root/** r,
  audit deny /var/log/** r,
  audit deny /proc/** r,

  # ... other standard Apache rules (logging, pid files, etc.) ...
  /var/log/apache2/** rw,
  /run/apache2/** rw,
}
```

### How to apply this in practice:
1. Place/edit the profile in `/etc/apparmor.d/usr.sbin.apache2`
2. Set the profile to enforce mode: `sudo aa-enforce /etc/apparmor.d/usr.sbin.apache2`
3. Reload AppArmor: `sudo systemctl reload apparmor`
4. Restart Apache: `sudo systemctl restart apache2`

With these rules actively enforced, if an attacker attempts to exploit `diagnostic.php` to run a system command, or exploits `profile.php` to read `/etc/passwd`, the Linux kernel will instantly block the action and log the attempt, completely nullifying the impact of the PHP vulnerabilities.