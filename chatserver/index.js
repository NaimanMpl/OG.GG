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

    console.log(`User ${socket.id}`);

    socket.on('message', (data) => {
        io.emit('message', `${socket.id.substring(0, 5)}: ${data}`);
    });
});

httpServer.listen(3000, () => {
    console.log('Server started on 3000')
});