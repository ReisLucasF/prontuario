from flask import Flask, jsonify, request
from flask_cors import CORS
from pymongo.mongo_client import MongoClient
from pymongo.server_api import ServerApi
from pymongo.collection import Collection
from bson import ObjectId
import os



app = Flask(__name__)
CORS(app) 
uri = "mongodb+srv://reislucasf:5lLtb4GM7bB0vFzz@medprontcluster.hm7whyi.mongodb.net/?retryWrites=true&w=majority&appName=medprontcluster"
client = MongoClient(uri, server_api=ServerApi('1'))

db = client.test
receitas_collection: Collection = db.ReceitaMedicaS

try:
    client.admin.command('ping')
    print("Pinged your deployment. You successfully connected to MongoDB!")
except Exception as e:
    print(e)
    
@app.route('/receita', methods=['GET'])
def get_receitas():
    try:
        receitas = list(receitas_collection.find())
        
        for receita in receitas:
            receita['_id'] = str(receita['_id'])
        
        return jsonify(receitas), 200
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/receita/<string:id>', methods=['GET'])
def get_receita_by_id(id):
    try:
        # Encontra a receita com o ID especificado
        receita = receitas_collection.find_one({'_id': ObjectId(id)})
        
        if receita:
            # Converte o ObjectId para string
            receita['_id'] = str(receita['_id'])
            return jsonify(receita), 200
        else:
            return jsonify({'message': 'Receita não encontrada'}), 404
    except Exception as e:
        return jsonify({'error': str(e)}), 500



@app.route('/receita', methods=['POST'])
def create_receita():
    try:
        data = request.json
        receitas_collection.insert_one(data)
        return jsonify({'message': 'Receita criada com sucesso'}), 201
    except Exception as e:
        return jsonify({'error': str(e)}), 500


if __name__ == '__main__':
    app.run(debug=True)
