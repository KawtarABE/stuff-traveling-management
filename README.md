# stuff-traveling-management
The project aims to revolutionize the management of travel expenses at Al Omrane company by introducing a process digitization application. Currently, the tedious and error-prone manual task of handling travel data will be transformed into an automated and efficient process. The proposed application will facilitate the input, modification, and retrieval of information related to employee travel, allowing for streamlined and rapid management.

## Table of Contents
* [General Information](#general-information)
* [Technologies Used](#technologies-used)
* [Features](#features)
* [Setup](#setup)
* [Usage](#usage)
* [Screenshots](#screenshots)


## General Information
### Main Features
- Data Management (employees, mission, grades, transportation, horsepower, destinations...).
- Total Expense Calculation to be remunerated.
- Administrative Document Generation.


### File structure
The file structure of the project is the following:
```
├── app (contains the code php+html)   
├── css (contains the styling)
├── fpdf (library to generate pdfs)
├── images
├── js (code javascript)
└── README.md
```


## Technologies Used
* PHP
* HTML
* CSS
* JS
* AJAX
* FPDF
* MYSQL


## Setup
### Pre-Requisites
To set up this project you should install the following:
- XAMPP (apache, mysql)
- VSCODE (or any other IDE)

### Usage
To use the project you should do the following:
- Add the project to **'htdocs'** folder on the xampp environment.
- Create the database on phpmyadmin panel.
  * patients(**`id`**, **`email`**, **`password`**, **`first_name`**, **`last_name`**, **`sexe`**, **`date_of_birth`**)
  * maladies(**`id_maladie`**, **`name_of_maladie`**)
  * cathegories(**`id_cathegory`**, **`name_of_cathegory`**)
  * patient_maladie(**`id_patient`**, **`id_maladie`**)
  * forum(**`id`**, **`Description`**, **`Post`**, **`patient`**, **`id_categorie`**, **`statut`**, **`type`**)
  * answers(**`id_consultation`**, **`id_doctor`**, **`answer`**)
  * admins(**`id_admin`**, **`email`**, **`password`**, **`first_name`**, **`last_name`**)
  * doctors(**`id_doctor`**, **`email`**, **`password`**, **`first_name`**, **`last_name`**, **`sexe`**, **`id_cathegory`**)

## Screenshots
### Home Page
![Example screenshot](Screenshots/home.png)
### Admin dashboard 
![Example screenshot](Screenshots/Dashboard.png)
### Blog page
![Example screenshot](Screenshots/Blog.png)
### Consultations page
![Example screenshot](Screenshots/consultations.png)
