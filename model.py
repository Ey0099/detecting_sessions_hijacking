import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score
import joblib

MODEL_PATH = 'session_hijack_detector.pkl'
print(MODEL_PATH)
def load_and_train_model():
    # Load the dataset
    data = pd.read_csv('dataset/UNSW_NB15_testing-set.csv')

    # Select features and label (assuming 'label' column indicates hijacked/normal)
    X = data.drop(columns=['id', 'label', 'attack_cat', 'proto', 'service', 'state'])  # Drop irrelevant or non-numeric columns
    y = data['label']

    # Split data into training and testing sets
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

    # Initialize and train the Random Forest model
    model = RandomForestClassifier(n_estimators=100, random_state=42)
    model.fit(X_train, y_train)

    # Evaluate model accuracy
    y_pred = model.predict(X_test)
    accuracy = accuracy_score(y_test, y_pred)
    print(f"Model Accuracy: {accuracy * 100:.2f}%")

    # Save the trained model
    joblib.dump(model, MODEL_PATH)
    return model

def load_model():
    try:
        model = joblib.load(MODEL_PATH)
        print("Loaded saved model.")
    except FileNotFoundError:
        print("start detect model accuracy ......")
        model = load_and_train_model()
    return model


load_model()