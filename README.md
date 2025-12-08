# 🚀 INEX SPA Framework

A lightweight, ultra-fast, and secure **PHP framework** built for high‑performance web applications.

INEX SPA focuses on **speed, simplicity, security, and developer productivity**, providing a clean architecture for building modern PHP applications with ease.

---

## ✨ Key Features

* ⚡ Ultra-fast execution (1ms response time)
* 🛠 Built-in CLI (**Ammar CLI**)
* 🧭 Advanced routing system
* 🗄 Integrated database management
* 🔐 Built-in CSRF protection
* 🧩 Modular plugin system
* 🧠 Smart caching system
* 📁 Session management
* ⏰ Built-in Cronjob System
* 🎞 Built-in Motion Engine (Beta)
* 🌙 Dark-friendly UI support
* 📦 Clean MVC structure
* 🧪 Easy debugging & logging

---

## 📦 Installation

Clone the official repository:

```bash
git clone https://github.com/AmmarBasha2011/INEX-SPA.git
cd INEX-SPA
```

Then follow the full setup guide from the official documentation:

👉 [https://inex-1.gitbook.io/inex-docs/inex-spa/getting-started](https://inex-1.gitbook.io/inex-docs/inex-spa/getting-started)

---

## 🏁 Project Structure (Simplified)

```
core/
 ├─ app/
 ├─ cron/
 ├─ database/
 ├─ logs/
public/
 ├─ css/
 ├─ js/
index.php
ammar
.env
```

---

## 🛠 Ammar CLI

The framework comes with a powerful command-line tool called **Ammar CLI**.

### Available Commands

```bash
php ammar list:cron
php ammar make:cron TaskName
php ammar run:cron TaskName
php ammar delete:cron TaskName
php ammar clear:cron
```

---

## ⏰ Cronjob Management

INEX SPA includes a full built-in cronjob system.

### Cron Runner

```bash
/usr/bin/php /path/to/project/core/cron/cron_runner.php <TaskName> >> core/logs/cron.log 2>&1
```

### Create a New Cron Task

```bash
php ammar make:cron DailyTask
```

Edit the file in:

```
core/cron/tasks/DailyTask.php
```

Then test it:

```bash
php ammar run:cron DailyTask
```

---

## 🎞 Built-in Motion Engine (Beta)

A lightweight animation engine without external dependencies.

### Enable from `.env`

```
USE_ANIMATE=true
```

### Usage Example

```html
<div id="box">Hello</div>
<script>
animate('#box', 'fade-in', 300);
</script>
```

### Add Custom Animations

Edit:

```
public/css/motion-animations.css
```

---

## 🔐 Security

* Built-in CSRF Protection
* Secure session handling
* Input sanitization helpers
* Protected routing system

---

## 🧩 Plugin System

INEX SPA supports modular development using plugins to extend core features.

---

## 🧪 Logging & Debugging

* Error logs
* Cron logs
* System debug output

All available inside:

```
core/logs/
```

---

## 🗺 Roadmap

* ✅ Core Framework
* ✅ CLI Tools
* ✅ Routing & Database
* ✅ Cron System
* ✅ Motion Engine
* ⏳ Advanced Security Layer
* ⏳ Performance Profiler
* ⏳ Plugin Marketplace
* ⏳ Cloud Integration

---

## 🤝 Contributing

Contributions are welcome!

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to your branch
5. Open a Pull Request

---

## 📧 Contact

* GitHub: [https://github.com/AmmarBasha2011](https://github.com/AmmarBasha2011)
* Email: [inex.own@gmail.com](mailto:inex.own@gmail.com)
* Phone & WhatsApp: +201096730619

---

## 📜 License

MIT License
