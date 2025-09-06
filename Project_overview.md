# Project Overview

## Project Technologies
- PHP 8.2 with the Laravel 12 framework  
- Blade templating with Blade UI Kit Heroicons  
- Tailwind CSS v3 with PostCSS and Autoprefixer  
- Vite build tool with Axios and Concurrently  
- Alpine.js for lightweight interactivity  
- Mews Purifier for HTML sanitization  
- Database support for SQLite/MySQL via Laravel's Eloquent ORM  

## Project Description
This portfolio website showcases Alex David's professional profile. The home page presents:  
- **Skills and Languages** with level indicators and years of experience  
- **Job Experience** showing companies, roles, locations, and dates  
- **Recent Portfolio Projects** with thumbnails, tech stack badges, and short descriptions  
- **Latest Blog Posts** including categories, publish dates, and excerpts  
- **Services** section displaying up to three service cards with pagination, including title, description, price badge, and an “Inquiry Now” button linked to the contact page (visible only when enabled in the admin dashboard)  

The site also features a full **Blog** and **Portfolio**, with previews integrated into the home page.  

## SEO Features
Blog posts, portfolio items, and category pages pass meta data to a shared SEO partial which outputs:  
- Dynamic page titles and descriptions  
- Canonical URLs  
- Open Graph tags including type, image, and publication times  
- Twitter Card tags  

## Admin Dashboard

### My CV
Manage personal profile and resume content:  
- Profile details: first name, last name, nationality, country, and birthdate  
- Contact email field  
- Skill manager with name, category, level, and years of experience  
- Language manager with name and proficiency level  
- Experience manager with company, role, location, and date range  

### Portfolio
Create and maintain portfolio projects:  
- Title and slug generation  
- Rich text description and short summary  
- Tech stack badges  
- Thumbnail upload  
- Publication status and date  
- Meta title and description for SEO  

### Blog
Write and edit blog posts:  
- Category selection  
- Title and slug generation  
- Body with rich text editor and excerpt field  
- Feature image upload  
- Meta title and description for SEO  

### Categories
CRUD interface for blog categories including name, slug, and description.  

### Inbox Mail System
Manage messages submitted through the contact form:  
- View and delete submitted contact forms  
- Centralized inbox for all messages  

### Contact Page (Admin)
Configure the contact page metadata:  
- Title, description, and meta description fields  
- Uses fallback OG image from site-wide settings  

### Services
Admin panel for managing the Services section:  
- Enable or disable Services globally (controls visibility on the home page and user sidebar menu)  
- Edit the Services page title, description, and meta description  
- Add, update, or delete individual services with title, description, and price  
- SEO fields supported with fallback OG image  

### GSC
Configure site verification and custom code:  
- Google Search Console verification code (content value only)  
- Custom header code inserted before `</head>`  
- Custom body code inserted before `</body>`  
- Applied on all user-facing pages, excluded from admin  

### Settings
Site-wide configuration:  
- Site name, favicon, and home page text  
- Footer copyright and dynamic social links  
- Default social share image  
- Account settings including email and password  

## Credits
By Alex David  
Website: https://byalexdavid.com/  
YouTube: https://www.youtube.com/@ByAlexdavid  
LinkedIn: https://www.linkedin.com/in/alex-david-du-ba01601b1/  
GitHub: https://github.com/Alexdavid1996  
