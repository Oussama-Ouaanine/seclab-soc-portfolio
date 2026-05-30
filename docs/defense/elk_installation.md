# Centralized SIEM Installation (Elasticsearch & Kibana)

This guide documents the manual deployment of the Monitoring VM (SIEM - SOC) using the `.tar.gz` archives, mirroring the architecture where services run directly out of `/opt/`.

## Prerequisites
- Ubuntu/Debian environment.
- At least 4-6 GB of RAM allocated exclusively for this VM.
- Both components will be installed in `/opt/`.

## 1. System Preparation
Elasticsearch performs heavy system checks and refuses to start as the `root` user to prevent security breaches. Create a dedicated non-root user:

```bash
sudo useradd -m -s /bin/bash elastic
```

## 2. Elasticsearch Installation
Elasticsearch acts as our central datastore and search engine.

```bash
# 1. Download the archive (replace 8.11.0 with the version you actively use)
wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-8.11.0-linux-x86_64.tar.gz

# 2. Extract into /opt
sudo tar -xzf elasticsearch-*.tar.gz -C /opt/
sudo mv /opt/elasticsearch-* /opt/elasticsearch

# 3. Apply correct ownership to our dedicated user
sudo chown -R elastic:elastic /opt/elasticsearch

# 4. Integrate custom SOC configuration
# (Assuming you transferred the repo to the server)
sudo cp configs/elasticsearch.yml /opt/elasticsearch/config/
sudo chown elastic:elastic /opt/elasticsearch/config/elasticsearch.yml
```

## 3. Kibana Installation
Kibana provides the graphical interface and dashboards for the SOC analyst.

```bash
# 1. Download the archive
wget https://artifacts.elastic.co/downloads/kibana/kibana-8.11.0-linux-x86_64.tar.gz

# 2. Extract into /opt
sudo tar -xzf kibana-*.tar.gz -C /opt/
sudo mv /opt/kibana-* /opt/kibana

# 3. Apply ownership
sudo chown -R elastic:elastic /opt/kibana

# 4. Integrate custom SOC configuration
sudo cp configs/kibana.yml /opt/kibana/config/
sudo chown elastic:elastic /opt/kibana/config/kibana.yml
```

## 4. Running the Stack
If you are not using `systemd` daemon services, you can launch these manually in a `tmux` or `screen` session.

**Start Elasticsearch:**
```bash
sudo -u elastic /opt/elasticsearch/bin/elasticsearch
```
*(Wait 1-2 minutes for the JVM to spin up and bind to port 9200).*

**Start Kibana:**
```bash
sudo -u elastic /opt/kibana/bin/kibana
```

Once running, target your web browser to Kibana exposed on port 5601:
`http://<SOC-VM-IP>:5601`
