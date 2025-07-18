const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const cors = require("cors");

// Setup server
const app = express();
app.use(cors());

const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*", // Allow all origins (adjust for production)
        methods: ["GET", "POST"]
    }
});

// Track connected users: user_id => socket.id
const connectedUsers = new Map();

io.on("connection", (socket) => {
    console.log("🔌 New connection:", socket.id);

    // When a user joins: map user_id to socket
    socket.on("join", ({ user_id }) => {
        console.log(`✅ User ${user_id} joined`);
        connectedUsers.set(user_id, socket.id);
    });

    // When a user disconnects
    socket.on("disconnect", () => {
        console.log("❌ Disconnected:", socket.id);
        for (let [uid, sid] of connectedUsers.entries()) {
            if (sid === socket.id) {
                connectedUsers.delete(uid);
                break;
            }
        }
    });

    // Broadcast a new message to the recipient
    socket.on("send_message", (data) => {
        const { receiver_id, sender_id, message } = data;

        const targetSocket = connectedUsers.get(receiver_id);
        if (targetSocket) {
            io.to(targetSocket).emit("unread_message", {
                receiver_id,
                sender_id,
                message
            });
            console.log(`📨 Message sent from ${sender_id} to ${receiver_id}`);
        } else {
            console.log(`🕸️ Receiver ${receiver_id} not online`);
        }
    });

    // Broadcast a new notification to the recipient
    socket.on("send_notification", (data) => {
        const { user_id, content } = data;

        const targetSocket = connectedUsers.get(user_id);
        if (targetSocket) {
            io.to(targetSocket).emit("new_notification", {
                user_id,
                content
            });
            console.log(`🔔 Notification sent to ${user_id}`);
        } else {
            console.log(`📭 Notification queued: user ${user_id} offline`);
        }
    });
});

// Start the server
const PORT = 3000;
server.listen(PORT, () => {
    console.log(`✅ Socket.IO server running at http://localhost:${PORT}`);
});
