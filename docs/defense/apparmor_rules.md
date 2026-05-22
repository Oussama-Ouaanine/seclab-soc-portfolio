# AppArmor Rules for Mitigation

To actually implement the protections discussed in the previous analysis, you would create or modify an AppArmor profile for the Apache web server (typically located at `/etc/apparmor.d/usr.sbin.apache2`).

Below is an example of the specific rules you would add or ensure exist to stop the **Remote Command Execution (RCE)** and **SSRF/Local File Inclusion (LFI)** vulnerabilities in this project.

```apparmor
# File: /etc/apparmor.d/usr.sbin.apache2

#include <tunables/global>

profile /usr/sbin/apache2 flags=(attach_disconnected,enforce) {

  #include <abstractions/base>
  #include <abstractions/apache2-common>
  #include <abstractions/nameservice>

  /usr/sbin/apache2 mr,

  # ================= SHELL TRANSITION =================
  /bin/sh Px -> restricted-shell,
  /usr/lib/cargo/bin/coreutils/du Px -> du-profile,
  /usr/bin/dash Px -> restricted-shell,

  # ================= MAIN TOOL =================
  /usr/bin/du Px -> du-profile,

  # ================= BLOCK EXECUTION =================
  deny /usr/bin/python* x,
  deny /usr/bin/perl x,
  deny /usr/bin/ruby x,
  deny /usr/bin/wget x,
  deny /usr/bin/curl x,
  deny /usr/bin/nc x,
  deny /bin/bash x,
  deny /usr/bin/bash x,

  # ================= FILE SYSTEM =================
  /var/www/** r,
  /tmp/** rw,
  /var/log/apache2/** rw,
  /var/run/apache2/** rw,
  /run/apache2/** rw,
  /run/systemd/notify w,

  /etc/apache2/** r,
  /etc/mime.types r,
  /etc/ssl/** r,
  /etc/php/** r,
  /var/lib/php/sessions/ rw,
  /var/lib/php/sessions/** rwk,
  /etc/machine-id r,
  /etc/passwd r,
  /etc/group r,
  /etc/gss/mech.d/ r,
  /etc/gss/mech.d/** r,

  deny /etc/shadow r,
  deny /root/** rwx,
  deny /home/** rwx,
  deny /boot/** rwx,

  deny capability net_admin,
}

# File: /etc/apparmor.d/restricted-shell
profile restricted-shell {
  /bin/sh mr,
  /usr/bin/dash mr,

  # dynamic linker — required for all ELF binaries
  /etc/ld.so.cache r,

  # allowed tools
  /usr/bin/du ix,
  /usr/lib/cargo/bin/coreutils/du ix,
  /usr/lib/cargo/bin/coreutils/ls ix,
  /usr/lib/cargo/bin/coreutils/cat ix,
 #/usr/bin/cat ix,
 #/usr/bin/ls ix,

  /proc/*/auxv r,
  /proc/*/status r,
  /proc/*/maps r,
  /usr/share/coreutils/locales/uucore/** r,

  # essential libraries
  /usr/lib/x86_64-linux-gnu/ld-linux-x86-64.so.2 mr,
  /usr/lib/x86_64-linux-gnu/libc.so.6 mr,
  /usr/lib/x86_64-linux-gnu/libselinux.so.1 mr,
  /usr/lib/x86_64-linux-gnu/** mr,
  /usr/lib/** mr,

  /var/www/** r,
  /tmp/** r,
  /var/log/apache2/error.log a,
  /var/log/apache2/access.log a,

  deny /etc/shadow r,
  deny /root/** rwx,
  deny /home/** rwx,
  deny /boot/** rwx,
  deny /bin/bash x,
  deny /usr/bin/bash x,
  audit deny /usr/bin/python* x,
  audit deny /usr/bin/python3.14 x,
  deny /usr/bin/curl x,
  deny /usr/bin/wget x,
  deny /usr/bin/nc x,
}
```

### How to apply this in practice:

**1. How to reload:**
```bash
sudo apparmor_parser -r /etc/apparmor.d/restricted-shell
sudo apparmor_parser -r /etc/apparmor.d/usr.sbin.apache2
sudo systemctl restart apache2
```

**2. How to disable:**
```bash
sudo apparmor_parser -R /etc/apparmor.d/restricted-shell
sudo apparmor_parser -R /etc/apparmor.d/usr.sbin.apache2
sudo systemctl restart apache2
```

**3. How to verify:**
```bash
sudo aa-status | grep apach
```

**4. How to see logs:**
```bash
sudo dmesg -w
# or
sudo dmesg -w | grep DENIED
```

With these rules actively enforced, if an attacker attempts to exploit `diagnostic.php` to run a system command, or exploits `profile.php` to read `/etc/passwd`, the Linux kernel will instantly block the action and log the attempt, completely nullifying the impact of the PHP vulnerabilities.