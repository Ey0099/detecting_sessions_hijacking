import pandas as pd
import numpy as np
from sklearn.ensemble import IsolationForest
import joblib

# Step 1: Generate Synthetic Data
# For the sake of example, let's create a DataFrame that simulates user session data.
def generate_synthetic_data(num_samples=1000):
    np.random.seed(42)  # For reproducibility

    # Generate synthetic IPs (numerical format) and geolocations (numerical)
    ip_addresses = np.random.randint(1, 255, size=(num_samples, 4)).astype(str)
    geo_locations = np.random.randint(1, 100, size=(num_samples, 2))  # Simulating latitude and longitude

    # Combine into a DataFrame
    data = pd.DataFrame({
        'ip_address': ['.'.join(ip) for ip in ip_addresses],
        'latitude': geo_locations[:, 0],
        'longitude': geo_locations[:, 1],
        'response_time': np.random.normal(loc=200, scale=50, size=num_samples)  # Simulated response times
    })

    # Create labels: 0 for normal and 1 for anomalous sessions
    # Assume sessions with unusually high response times are anomalous
    data['label'] = np.where(data['response_time'] > 300, 1, 0)
    return data

# Step 2: Prepare the data for training
data = generate_synthetic_data()

# Split features and labels
X = data[['latitude', 'longitude', 'response_time']]
y = data['label']

# Step 3: Train the Isolation Forest model
model = IsolationForest(contamination=0.05)  # Assuming 5% of sessions are anomalous
model.fit(X)

# Step 4: Save the model to a .pkl file
joblib.dump(model, 'session_hijacking_model.pkl')
print("Model saved as 'session_hijacking_model.pkl'")
