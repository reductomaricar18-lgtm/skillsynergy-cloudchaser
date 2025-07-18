# SkillSynergy Chat Setup Guide

## Overview
The message tab now includes real-time chat functionality with skill selection prompts and Socket.io integration.

## Features
- **Skill Selection Prompt**: When starting a new chat session, users see a prompt to select skills they want to learn
- **Level Selection**: Users can choose Beginner, Intermediate, or Advanced levels
- **Real-time Chat**: Instant messaging using Socket.io
- **Request System**: Learning requests with 24-hour expiration
- **File Sharing**: Ability to send files during chat sessions

## Setup Instructions

### 1. Install Node.js Dependencies
```bash
npm install
```

### 2. Create Database Table
Run the SQL in `learning_requests_table.sql` in your MySQL database:
```sql
-- Execute the contents of learning_requests_table.sql
```

### 3. Start the Chat Server
```bash
npm start
```
or for development with auto-restart:
```bash
npm run dev
```

The server will run on `http://localhost:3001`

### 4. Access the Message Tab
Navigate to `message.php` in your browser. The chat functionality will be available when you select a contact.

## How It Works

### Skill Selection Flow
1. User clicks on a contact in the message tab
2. If no message history exists, a skill selection prompt appears
3. User selects a skill and proficiency level
4. A learning request is sent to the other user
5. If accepted, chat becomes enabled for both users

### Chat Features
- **Real-time messaging**: Messages appear instantly
- **File sharing**: Users can send files through the attachment menu
- **Session management**: Sessions can be ended, triggering rating prompts
- **24-hour expiration**: Learning requests expire after 24 hours

### Socket.io Events
- `send_request`: Sends a learning request
- `notify_{userId}`: Notifies a user of a learning request
- `chat_accepted`: Handles chat session acceptance
- `chat message`: Handles real-time message sending

## File Structure
- `message.php`: Main message interface with chat functionality
- `chatServer.js`: Socket.io server for real-time communication
- `fetch_skills.php`: Backend API to fetch user skills
- `package.json`: Node.js dependencies
- `learning_requests_table.sql`: Database schema

## Troubleshooting

### Common Issues
1. **Socket.io connection failed**: Make sure the chat server is running on port 3001
2. **Skills not loading**: Check that the `user_skills` table exists and has data
3. **Messages not sending**: Verify the `messages` table exists in your database

### Debug Mode
Enable console logging by checking the browser's developer tools for any JavaScript errors.

## Security Notes
- All database queries use prepared statements to prevent SQL injection
- Session validation ensures only logged-in users can access chat features
- File uploads are restricted to prevent malicious uploads 