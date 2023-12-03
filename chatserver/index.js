import { createServer } from 'http';
import { Server } from 'socket.io';

const httpServer = createServer();
const io = new Server(httpServer, {
    cors: {
        origin: process.env.NODE_ENV === 'production' ? false : 
        ['http://localhost:8888']
    }
});

io.on('connection', (socket) => {
    const room = socket.handshake.query.room;
    console.log(room);
    socket.join(room);
    socket.to(room).emit('message', 'Un mec vient de rejoindre !');
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
});

httpServer.listen(3000, () => {
    console.log('Server started on 3000')
});