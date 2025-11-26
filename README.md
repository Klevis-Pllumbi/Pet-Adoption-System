# 🐾 FurEver Home  
A web-based Pet Adoption System designed to connect users with shelters and individuals offering animals for adoption.  
The platform supports adoption, donations, reporting of abuse or lost animals, surrender requests, profile management, and a full admin dashboard.  

---

## 🧾 Project Overview  
**FurEver Home** is a PHP-based web application that simplifies the pet adoption process by providing:  
- a user-friendly interface for browsing pets,  
- secure authentication and profile management,  
- online donations via PayPal,  
- reporting of abuse, found, or injured animals,  
- surrendering pets,  
- full admin control over users, pets, reports, requests, and payments.  

Its goal is to encourage responsible adoption and improve the well-being of animals through transparency, accessibility, and a structured digital system.  

---

## ✨ Key Features

### 👤 User Features  
User registration with email verification, secure login with lockout protection, and full profile management.

### 🐶 Pet Adoption  
Browse and filter pets by type, view detailed profiles, and complete adoptions through PayPal with dynamic AJAX updates.

### 💳 Payments & Donations  
Integrated PayPal payments for adoptions and donations, with secure order flow and admin payment tracking.

### 📝 Pet Surrender  
Submit detailed surrender requests with required validation, storing data in both pets and surrendered_pets tables.

### 🚨 Reporting System  
Report abuse, injured, or lost animals with geolocation, photos, and admin tools for filtering and resolving cases.

### 🛠️ Admin Dashboard  
Manage adoption requests, surrender requests, reports, pets, users, and send approval/rejection email notifications.

---

## 🧱 Tech Stack

**Frontend:**  
- HTML  
- CSS  
- JavaScript  

**Backend:**  
- PHP (procedural + modular structure)  
- MySQL  

**Integrations:**  
- PayPal REST API (Orders & Payments)  

---

## 🧩 Main Modules (Summary)

### 🔐 Authentication
- Secure registration with validation  
- Email verification via token  
- Login with password hashing & account lock  
- “Remember me” via cookies  

### 🏠 Home & Pet Catalog
- Category-based filtering  
- AJAX-loaded pet cards  
- Detailed pet view and adoption flow  

### 💵 Donations & Adoption Payments
- Secure PayPal integration  
- Error handling and return/cancel routes  
- Admin payment logs + totals  

### 🐾 Give Up for Adoption
- Form with all required data + image upload  
- Optional surrender reason  
- Stored in database after validation  

### 🚨 Reports
- Abuse / lost / injured cases  
- Coordinates or manual location  
- Admin filtering + resolution  

### 🛠️ Admin Tools
- View, approve, reject adoption requests  
- Manage surrender requests  
- Add pets with adoption fee  
- Manage users & pets  
- Email notifications  

---

## 📸 Screenshots  

#### Homepage
<img width="100%" height="auto" alt="Homepage" src="https://github.com/user-attachments/assets/4bad7cd4-520b-4ee2-8a72-a5d590d7eedb" />

#### Pet Catalog
<img width="100%" height="auto" alt="Pet Catalog" src="https://github.com/user-attachments/assets/e1ce3c0c-158f-4fca-bb8a-138e49196bc7" />

#### Give Up For Adoption Form
<img width="100%" height="auto" alt="Give Up For Adoption Form" src="https://github.com/user-attachments/assets/6e71abd2-74d6-4208-8052-3e8139677ea5" />

#### Report Form
<img width="100%" height="auto" alt="Report Form" src="https://github.com/user-attachments/assets/b70483bf-ad23-4057-bb0a-5fc69bcc30fe" />

#### Pet Details
<img width="100%" height="auto" alt="Pet Details" src="https://github.com/user-attachments/assets/4f3e30a9-9d4e-4ab9-bc46-5bcf698751a2" />

#### Donations List
<img width="100%" height="auto" alt="Donations List" src="https://github.com/user-attachments/assets/a48ec1d1-a9e2-4003-ad7d-396a6481401f" />

---

## ⚙️ Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/Klevis-Pllumbi/Pet-AdoptioN-System.git
cd <repo>
