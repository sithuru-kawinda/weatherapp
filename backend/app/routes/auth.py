from flask import Blueprint, jsonify, request, current_app
import jwt
import requests
from functools import wraps

auth_bp = Blueprint('auth', __name__)

def require_auth(f):
    @wraps(f)
    def decorated(*args, **kwargs):
        auth_header = request.headers.get('Authorization')
        if not auth_header:
            return jsonify({'error': 'No authorization header'}), 401
        
        try:
            token = auth_header.split(' ')[1]
            # Verify JWT with Auth0
            payload = jwt.decode(
                token,
                verify=False,  # In production, verify with Auth0 JWKS
                algorithms=['RS256'],
                audience=current_app.config['AUTH0_AUDIENCE'],
                issuer=f"https://{current_app.config['AUTH0_DOMAIN']}/"
            )
            request.user = payload
        except Exception as e:
            return jsonify({'error': 'Invalid token'}), 401
        
        return f(*args, **kwargs)
    return decorated

@auth_bp.route('/login', methods=['POST'])
def login():
    """Auth0 login callback"""
    data = request.get_json()
    # Handle Auth0 token exchange
    return jsonify({'message': 'Use Auth0 Universal Login'}), 200

@auth_bp.route('/profile', methods=['GET'])
@require_auth
def profile():
    """Get user profile"""
    return jsonify({'user': request.user}), 200