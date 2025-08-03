# AI Chat Companion

A sleek, mobile-inspired chatbot interface built with Laravel and powered by the OpenAI API. This project was created as the final assignment for CS 85: PHP Programming at Santa Monica College. It blends modern PHP development with AI integration to deliver a responsive and engaging user experience.

## Features

- Laravel backend with Blade templating
- OpenAI API integration for dynamic AI responses
- Mobile-style UI with iPhone-inspired layout
- Dark mode toggle with persistent user preference
- Form validation and error handling
- Responsive design using Bootstrap

## AI Integration

The core AI feature is a chat companion that responds to user input using OpenAI's GPT model. Users can ask questions, seek advice, or explore creative prompts, and receive intelligent responses in real time.

## Setup Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/Baptiste-Williams/ai-chat-companion


2. Install dependencies:
bash
composer install
npm install && npm run dev


3. Configure environment:

Copy .env.example to .env

Set your OpenAI API key:

OPENAI_API_KEY=your_key_here


4. Run migrations:

bash
php artisan migrate


5. Launch the app:

bash
php artisan serve


Screenshots