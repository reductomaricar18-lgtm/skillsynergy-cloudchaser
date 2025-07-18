const express = require('express');
const http = require('http');
const socketIO = require('socket.io');
const cors = require('cors');
const mysql = require('mysql');
const bodyParser = require('body-parser');

// Setup express app
const app = express();
app.use(cors());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Setup MySQL connection
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'sia1'
});

db.connect(err => {
  if (err) {
    console.error("MySQL connection failed: " + err.stack);
    return;
  }
  console.log("MySQL connected.");
});

// Create HTTP + Socket.IO server
const server = http.createServer(app);
const io = socketIO(server, {
  cors: { origin: '*' }
});

// Handle connections
io.on("connection", (socket) => {
  console.log(`User connected: ${socket.id}`);

  // Join room based on user ID
  socket.on("join_room", (userId) => {
    socket.join(`user_${userId}`);
    console.log(`User ${socket.id} joined room user_${userId}`);
  });

  // LIKE USER
  socket.on("like_user", (data) => {
    const { userId, byUserId } = data;
    const likeQuery = `
      INSERT INTO user_likes (user_id, liked_user_id, action)
      VALUES (?, ?, 'like')
      ON DUPLICATE KEY UPDATE action = 'like'
    `;
    db.query(likeQuery, [byUserId, userId], (err) => {
      if (err) {
        console.error(err);
        return;
      }

      // Notify the liked user about the new like
      io.to(`user_${userId}`).emit("notif_update");
      console.log(`Sent notif_update to user_${userId}`);

      // Check for mutual match
      const checkMutual = `
        SELECT * FROM user_likes
        WHERE user_id = ? AND liked_user_id = ? AND action = 'like'
      `;
      db.query(checkMutual, [userId, byUserId], (err, results) => {
        if (err) {
          console.error(err);
          return;
        }

        if (results.length > 0) {
          // Mutual match found — notify both users
          io.to(`user_${userId}`).emit("update_match", {
            message: "Match found!",
            user1: byUserId,
            user2: userId
          });
          io.to(`user_${byUserId}`).emit("update_match", {
            message: "Match found!",
            user1: byUserId,
            user2: userId
          });
        }
      });
    });
  });

  // DISLIKE USER
  socket.on("dislike_user", (data) => {
    const { userId, byUserId } = data;
    const dislikeQuery = `
      INSERT INTO user_likes (user_id, liked_user_id, action)
      VALUES (?, ?, 'dislike')
      ON DUPLICATE KEY UPDATE action = 'dislike'
    `;
    db.query(dislikeQuery, [byUserId, userId], (err) => {
      if (err) {
        console.error(err);
        return;
      }

      // Optionally notify disliked user or log it
      console.log(`Dislike from ${byUserId} to ${userId}`);
    });
  });

  // Disconnect
  socket.on("disconnect", () => {
    console.log(` User disconnected: ${socket.id}`);
  });
});

// HTTP POST endpoint (for PHP cURL notifications)
app.post('/notify', (req, res) => {
  const targetUserId = req.body.targetUserId;
  if (targetUserId) {
    io.to(`user_${targetUserId}`).emit('notif_update');
    console.log(`Sent notif_update to user_${targetUserId}`);
    res.send("Notification sent.");
  } else {
    res.status(400).send("Missing targetUserId.");
  }
});

// Run server
server.listen(3000, () => {
  console.log(" Socket.IO server running on http://localhost:3000");
});

