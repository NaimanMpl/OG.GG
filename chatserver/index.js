import { createServer } from 'http';
import { Server } from 'socket.io';

const httpServer = createServer();
const io = new Server(httpServer, {
    cors: {
        origin: process.env.NODE_ENV === 'production' ? false : 
        ['http://localhost:8888']
    }
});

const users = {
    chat : [], // General
    iron : [],
    bronze : [],
    silver : [],
    gold : [],
    platinium : [],
    emerald : [],
    diamond : [],
    master : [],
    grandmaster : [],
    challenger : [],
};

io.on('connection', (socket) => {
    const room = socket.handshake.query.room;
    const user = socket.handshake.query.user;
    socket.join(room);

    if (!users[room].includes(user)) {
        users[room].push(user);
        io.to(room).emit('joinMessage', { username: user, message: `${user} a rejoint le canal de discussion.`, onlineUsers: users[room] });
    }

    socket.on('message', (data) => {
        const currentDate = new Date();
        let hours = currentDate.getHours();
        let minutes = currentDate.getMinutes();

        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;

        const response = {
            time: hours + ':' + minutes,
            login: data.username,
            text: data.text
        }

        io.to(room).emit('message', response);
    });

    socket.on('disconnect', () => {
        users[room] = users[room].filter((username) => user != username);
        io.to(room).emit('leaveMessage', { username: user, message: `${user} a quitté le canal de discussion.`, onlineUsers: users[room] });
    });
});

io.on('disconnect', (socket) => {
    console.log(users);
});

httpServer.listen(3000, () => {
    console.log('Server started on 3000')
});