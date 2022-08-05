import requests
import json
import mysql.connector
from mysql.connector import Error

def create_db_connection(host_name, user_name, user_password, db_name):
    connection = None
    try:
        connection = mysql.connector.connect(
            host=host_name,
            user=user_name,
            passwd=user_password,
            database=db_name
        )
        print("MySQL Database connection successful")
    except Error as err:
        print(f"Error: '{err}'")

    return connection

def execute_query(connection, query):
    cursor = connection.cursor()
    try:
        cursor.execute(query)
        connection.commit()
        print("Query successful")
    except Error as err:
        print(f"Error: '{err}'")


url = "https://portal.afyacash.app/api/trans/all"

payload = ""
headers = {}

response = requests.request("POST", url, headers=headers, data=payload)

for resp in response.json():

    update = """
    UPDATE mpesa_requests 
    SET paymentStatus = 202
    WHERE paymentStatus = 201 AND amount = {} AND msisdn = {};
    """.format(resp['amount'], resp['msisdn'])
        
    connection = create_db_connection("localhost", "kap_survey", "mhealth@123!@#", "lima")
    execute_query(connection, update)