from flask import Flask, jsonify
from flask import request
from flask import make_response, send_file
from flask_api import status

#installed using
#pip install Flask-API
import os
import json
from flask import flash, redirect, url_for
from werkzeug.utils import secure_filename
import uuid
app = Flask(__name__)

UPLOAD_FOLDER = 'uploads'
ALLOWED_EXTENSIONS = {'txt', 'pdf', 'png', 'jpg', 'jpeg', 'gif'}

app = Flask(__name__)
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

#dictionary for file data
global file_list



    
@app.route('/files/list', methods=['GET'])
def list_files():
    return jsonify(file_list)

def allowed_file(filename):
    return '.' in filename and \
           filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS
from flask import abort

@app.route('/files/<string:id>', methods=['GET'])
def get_file(id):
    basepath = UPLOAD_FOLDER
    flag = 1
    file_name = ""
    for file in file_list:
        if id == file["id"]:
            file_name = file["file_name"]
            flag = 0
            

    if id == "":
        return make_response("one or more chunks are missing", 500)
    elif flag == 1:
        return make_response("requested object "+id+"is not Found", 404)
    else:        
        basepath = os.path.join(app.root_path, app.config['UPLOAD_FOLDER'])
        return send_file(os.path.join(basepath, file_name), as_attachment = True), 200
        

@app.route('/files/<string:id>', methods=['DELETE'])
def delete_file(id):
    flag = 1
    file_name = ""
    for file in file_list:
        if id == file["id"]:
            file_name = file["file_name"]
            flag = 0
    if flag==1:
        return make_response("Requested object "+id+" is not found", 404)
    else:
        try:
            os.remove(os.path.join(app.config['UPLOAD_FOLDER'], file_name))  
            for i in range(len(file_list)): 
                if file_list[i]["id"] == id: 
                    del file_list[i]
                    break
            #res = list(filter(lambda i: i['id'] != id, file_list)) 
            #file_list = res
            with open('data.json', 'w') as jsondump:
                json.dump(file_list,jsondump)
            return make_response("object "+id+" deleted successfully", 200)
        except Exception as e:
            return "Not Found", 404
    

@app.route('/files', methods = ['PUT'])
def upload_file():
    if request.method == 'PUT':
        # check if the post request has the file part
        if 'file' not in request.files:
            return "empty request", 409
        file = request.files['file']
        # if user does not select file, browser also
        # submit an empty part without filename
        if file.filename == '':
            return "file name empty", 409            
        
        filename = secure_filename(file.filename)
        basepath = UPLOAD_FOLDER
        flag = 1
        for entry in os.listdir(basepath):
            if os.path.isfile(os.path.join(basepath, entry)):
                if filename == entry:
                    flag = 0
        if file and allowed_file(file.filename) and flag == 1:
            file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
            
            unique_id = uuid.uuid4()
            temp_dict={}
            temp_dict["file_name"] = filename
            temp_dict["id"]=str(unique_id)
            file_list.append(temp_dict)
            with open('data.json', 'w') as jsondump:
                json.dump(file_list,jsondump)
            return make_response(str(unique_id),200)
        else:
            return make_response("Conflict", 409)



if __name__ == '__main__':
    file_list =[]
    with open('data.json') as jsonfile:
        file_list = json.load(jsonfile)
    app.run(debug=True)