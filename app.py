from flask import Flask, request, jsonify
import requests
import geoip2.database
import mysql.connector
from model import load_model
import pandas as pd

app = Flask(__name__)
model = load_model()

@app.route('/detect_anomaly', methods=['POST'])
def detect_anomaly():
    try:
        data = request.get_json()

        # Define the exact 39 feature names and expected order as in the trained dataset
        required_features = [
            'dur', 'spkts', 'dpkts', 'sbytes', 'dbytes', 'rate', 'sttl', 'dttl', 'sload', 'dload',
            'sinpkt', 'dinpkt', 'sjit', 'djit', 'swin', 'stcpb', 'dtcpb', 'dwin', 'tcprtt', 'synack',
            'ackdat', 'smean', 'dmean', 'trans_depth', 'response_body_len', 'ct_srv_src', 'ct_state_ttl',
            'ct_dst_ltm', 'ct_src_dport_ltm', 'ct_dst_sport_ltm', 'ct_dst_src_ltm', 'is_ftp_login',
            'ct_ftp_cmd', 'ct_flw_http_mthd', 'ct_src_ltm', 'ct_srv_dst', 'is_sm_ips_ports', 'feature38', 'feature39'
        ]

        # Construct feature array for prediction
        features = [data.get(feature, 0) for feature in required_features]
        if len(features) != 39:
            return jsonify({'error': 'Invalid feature count. Expected 39 features.'}), 400
        # Make prediction
        prediction = model.predict([features])[0]
        result = "hijacked" if prediction == 1 else "normal"
        # Session info comparison
        last_ip = data.get('last_ip')
        last_geo = data.get('last_geo')
        current_device_info = data.get('device_info')
        user_id = data.get('user_id')

        current_ip = data.get('ip_address')
        current_location = data.get('geolocation')
        previous_devices = data.get('device_info')

        if not current_ip or not current_location:
            return jsonify({'status': 'error', 'message': 'Unable to fetch current session information'}), 500

        # Initialize anomalies list
        anomalies = []
        # Detect IP change
        if current_ip != last_ip:
            anomalies.append('IP Change')

        current_city, current_country = current_location.split(', ')
        last_city, last_country = last_geo.split(', ')
        print("==============")
        print(current_city)
        print(current_country)
        print(last_city)
        print(last_country)
        print("==============")
        if current_city != last_city or current_country != last_country:
            anomalies.append('Geo Shift')

        if current_device_info not in previous_devices:
            anomalies.append('Device Change')

        if anomalies:
            return jsonify({'status': 'anomaly', 'anomalies': anomalies})
        else:
            return jsonify({'status': 'safe', 'prediction': result})

    except Exception as e:
        print(f"Error detecting anomaly: {e}")
        return jsonify({"error": "An error occurred during detection"}), 500

if __name__ == '__main__':
    app.run(debug=True)
