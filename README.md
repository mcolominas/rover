# 🪐 Rover Project - Full Stack Setup

This README provides an overview of how to set up and run the entire Rover project.  
The project consists of two main parts: **Backend (Laravel API)** and **Frontend (Vue.js 3)**.  
Each folder contains a more detailed README with specific installation and configuration instructions.

Additionally, the project is documented in **Mars Rover Mission.pdf**, which explains the project details and objectives.

---

## 🛠️ Frameworks Used

This project is built using the frameworks specified in the job offer:

- **Backend:** Laravel (PHP)
- **Frontend:** Vue.js

---

## 1. **Clone the Repository**

```shell
git clone https://github.com/mcolominas/rover.git
```

> ⚠️ Make sure to follow the separate README files in **backend/** and **frontend/** for detailed setup instructions.

---

## 2. **Running the Projects**

For the application to function correctly, **both projects need to be running simultaneously**:

### 🚀 Backend (Mars Rover API)
Start the backend server using Laravel:

```shell
cd ./backend
composer run setup
php artisan serve
```

- The backend will be available at: **http://127.0.0.1:8000**  
- Follow the instructions in `backend/README.md` for detailed configuration.

---

### 🌐 Frontend (Vue.js 3)
Start the frontend development server:

```shell
cd ../frontend
npm install
npm run build
npm run preview
```

- The frontend will be available at: **http://localhost:4173**  
- See `frontend/README.md` for more detailed setup, build, and linting instructions.

---

## ⚙️ Notes

- Make sure **both backend and frontend are running** at the same time.  
- The frontend communicates with the backend API at the URLs specified in the README.  
- Always follow the individual READMEs for each project for installation, configuration, and advanced setup instructions.
- For a detailed description of the project and objectives, refer to Mars Rover Mission.pdf.