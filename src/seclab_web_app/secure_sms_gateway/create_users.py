import mysql.connector
import secrets

# MySQL DB connection info
db_config = {
    "host": "localhost",
    "user": "sms_api_user",
    "password": "",
    "database": "secure_sms_gateway"
}

# List of users to create
usernames = ['kamal','sara','alice', 'bob', 'charlie', 'dave','nada']

try:
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor()

    for name in usernames:
        token = secrets.token_hex(16)  # 32-char hex token
        sql = "INSERT INTO sms_users (name, api_token) VALUES (%s, %s)"
        cursor.execute(sql, (name, token))
        print(f"✅ User '{name}' created with token: {token}")

    conn.commit()

except mysql.connector.Error as err:
    print(f"❌ MySQL Error: {err}")

finally:
    if conn.is_connected():
        cursor.close()
        conn.close()

