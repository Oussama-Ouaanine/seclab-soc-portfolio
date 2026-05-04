# Cybersecurity Student Portfolio

Hi, I’m a cybersecurity student building a practical security lab and documentation portfolio.

This repository showcases a local vulnerable web application protected and monitored with multiple security layers. It is meant to demonstrate hands-on work in:

- web application security
- defensive architecture
- monitoring and detection
- security reporting and documentation
- entrepreneurship-oriented security thinking

## Project Snapshot

The lab is based on a custom vulnerable web application called **SecLab** running on **Apache + PostgreSQL**. The goal is to study how attacks can be detected, blocked, and visualized across different layers.

### Security stack used

- **Suricata** for network intrusion detection
- **Falco** for runtime/syscall detection
- **AppArmor** for access control and prevention
- **Filebeat / Auditbeat** for log shipping
- **Elasticsearch / Kibana** for log storage and dashboards

## What’s in this repository

The public content is organized in the `docs/` folder:

- `docs/defense/` — Suricata, Falco, and AppArmor documentation
- `docs/vulnerabilities/` — vulnerability analysis and security notes
- `docs/gestion_projet/` — academic project documents and final report outline

## Why this project matters

This work shows that I can:

- analyze a vulnerable system
- keep intentional flaws for security testing
- document findings clearly
- design a layered defense strategy
- connect technical work with a business and entrepreneurship perspective

## Academic + Entrepreneurship angle

My final report combines the technical lab with a business view of the project as a **cybersecurity solution that could be sold to small and medium businesses**.

That means the report includes:

- market analysis
- SWOT / PESTEL / Porter’s Five Forces
- technical feasibility
- commercial strategy
- financial assumptions

## How to read the docs

Start here:

1. `docs/gestion_projet/cahier_des_charges_v1_4.md`
2. `docs/gestion_projet/rapport_outline.md`
3. `docs/defense/suricata.md`
4. `docs/defense/falco.md`
5. `docs/defense/apparmor.md`

## Note

This repository is for academic and portfolio purposes only. The lab is intentionally vulnerable for controlled security testing and learning.