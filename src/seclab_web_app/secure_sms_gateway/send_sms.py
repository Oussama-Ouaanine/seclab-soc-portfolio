import mysql.connector
import requests
import random
import time

# MySQL DB credentials
db_config = {
    "host": "localhost",
    "user": "sms_api_user",
    "password": "",
    "database": "secure_sms_gateway"
}

# PHP API endpoint
API_URL = "http://localhost/secure_sms_gateway/public/send_sms.php"

# Multiple test numbers
DESTINATIONS = [
    "+212612345678", "+212699998888", "+212611112222", "+212645556666",
    "+212622334455", "+212699887766", "+212698112233", "+212600000001"
]

LABELS = ['billing', 'marketing', 'alerts', 'support']
MESSAGES = [
    "urgent: payment failed",
    "please verify your account now",
    "system maintenance alert",
    "follow-up: your request was received",
    "important: delivery scheduled tomorrow"
]

STATUSES = ['delivered', 'undelivered', 'blocked', 'noDlr']

# === Get a random user from DB
def get_random_user():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT * FROM sms_users ORDER BY RAND() LIMIT 1")
    user = cursor.fetchone()
    cursor.close()
    conn.close()
    return user

# === Send SMS via API and return message ID
def send_sms(api_token, sender_name, destination, label, message):
    body = f"name={sender_name}; destination={destination}; message={message}; label={label}"
    payload = {
        "api_token": api_token,
        "message_body": body
    }

    try:
        response = requests.post(API_URL, json=payload)
        res_json = response.json()

        if response.status_code == 200 and "sms_id" in res_json:
            print(f"✅ Sent to {destination} | ID: {res_json['sms_id']} | Label: {label}")
            return res_json["sms_id"]
        else:
            print(f"❌ Failed to send to {destination} | Response: {res_json}")
            return None

    except Exception as e:
        print(f"❌ Error sending to {destination}: {e}")
        return None

# === Update message status in DB
def update_status(sms_id):
    new_status = random.choice(STATUSES)
    try:
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()
        sql = """
        UPDATE sms_messages 
        SET status = %s, delivered_at = NOW()
        WHERE id = %s
        """
        cursor.execute(sql, (new_status, sms_id))
        conn.commit()
        print(f"🟡 Status Updated → SMS ID {sms_id} marked as {new_status}")
    except Exception as e:
        print(f"❌ DB Error updating SMS ID {sms_id}: {e}")
    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()

# === Run full flow
def run_bulk_sms():
    user = get_random_user()
    if not user:
        print("❌ No users found.")
        return

    name = user['name']
    token = user['api_token']

    print(f"\n🚀 Starting SMS sending for user '{name}'")

    for dest in DESTINATIONS:
        label = random.choice(LABELS)
        message = random.choice(MESSAGES)
        sms_id = send_sms(api_token=token, sender_name=name, destination=dest, label=label, message=message)
        if sms_id:
            time.sleep(0.5)  # simulate network delay
            update_status(sms_id)
        print("-" * 60)

if __name__ == "__main__":
    run_bulk_sms()

