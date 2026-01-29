# Online Examination System

A modern, secure online examination system for Debre Markos University Health Campus.

## 🎓 Features

### Student Portal
- **Modern Dashboard** - Clean, intuitive interface
- **Exam Management** - Take exams with real-time timer
- **Practice Mode** - Practice questions without time limits
- **Results Tracking** - View exam history and scores
- **Profile Management** - Update credentials

### Exam Features
- **Fullscreen Mode** - Secure exam environment
- **Anti-Cheating** - Tab switch detection (2 warnings max)
- **Question Navigation** - Jump to any question
- **Auto-Submit** - Automatic submission when time expires
- **Real-time Timer** - Countdown timer with warnings

### Admin Portal
- **Student Management** - Add, edit, delete students
- **Course Management** - Manage courses and departments
- **Exam Scheduling** - Schedule exams with time windows
- **Question Bank** - Create and manage questions

## 🚀 Installation

1. **Clone the repository**
```bash
git clone https://github.com/abre0101/Online_Examination_System.git
cd Online_Examination_System
```

2. **Import Database**
```bash
mysql -u root -p < oes.sql
```

3. **Configure Database** (if needed)
Edit connection settings in PHP files if your database credentials differ from:
- Host: localhost
- User: root
- Password: (empty)
- Database: oes

4. **Start Server**
```bash
php -S localhost:8000
```

5. **Access the System**
- Home: http://localhost:8000/index-modern.php
- Student Login: Use credentials from database
- Admin Login: Use admin credentials

## 📁 Project Structure

```
OES/
├── Student/          # Student portal
├── Admin/            # Admin portal
├── Instructor/       # Instructor portal
├── ExamCommittee/    # Exam committee portal
├── assets/           # CSS, JS, fonts
├── images/           # Images and logos
├── auth/             # Authentication files
├── docs/             # Documentation
├── utils/            # Utility scripts
└── database/         # Database files
```

## 🎨 Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Design**: Modern responsive design with Poppins font

## 🔐 Security Features

- Session management
- Prepared statements (SQL injection prevention)
- Password protection
- Fullscreen exam mode
- Tab switch detection
- Auto-logout on suspicious activity

## 📊 Default Credentials

**Student:**
- ID: 1
- Username: (check database)
- Password: (check database)

**Admin:**
- Username: (check database)
- Password: (check database)

## 🛠️ Utilities

Located in `utils/` folder:
- `create-test-schedule.php` - Create sample exam schedules
- `update-schedule-table.php` - Update database schema
- `reset-database.php` - Reset database to default state

## 📝 License

This project is for educational purposes.

## 👥 Contributors

- Debre Markos University Health Campus

## 📧 Support

For issues and questions, please open an issue on GitHub.

---

**Note**: This is a modernized version of the Online Examination System with improved UI/UX, security features, and responsive design.
